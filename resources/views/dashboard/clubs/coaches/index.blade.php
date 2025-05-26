@extends('layouts.dashboard')
@section('title', 'Club - Coach Management')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
                    <span>Coach Management</span>
                    <div>
                        <a href="{{ route('club.coaches.trashed') }}" class="btn btn-warning btn-sm mx-1">
                            <i class="fas fa-trash-alt"></i> View Deleted Coaches
                        </a>
                        <a href="{{ route('club.coaches.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Coach
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                    @endif
                    @if($coaches->isEmpty())
                        <div class="alert alert-info">No coaches found.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-dark table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Experience</th>
                                        <th>Specializations</th>
                                        <th>Employment Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coaches as $coach)
                                        <tr>
                                            <td>{{ $coach->name }}</td>
                                            <td>{{ $coach->email }}</td>
                                            <td>{{ $coach->phone ?? 'N/A' }}</td>
                                            <td>
                                                @if($coach->experience_years)
                                                    {{ $coach->experience_years }} {{ Str::plural('year', $coach->experience_years) }}
                                                @else
                                                    <span class="text-muted">Not specified</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($coach->specializations)
                                                    @foreach(json_decode($coach->specializations) as $specialization)
                                                        <span class="badge bg-info me-1">{{ $specialization }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">None specified</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($coach->employment_type)
                                                    <span class="badge bg-primary">{{ $coach->employment_type }}</span>
                                                @else
                                                    <span class="text-muted">Not specified</span>
                                                @endif
                                            </td>
                                            <td class="d-flex justify-content-center">
                                                <a href="{{ route('club.coaches.show', $coach->encoded_id) }}" class="btn btn-sm btn-info mx-1" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('club.coaches.edit', $coach->encoded_id) }}" class="btn btn-sm btn-primary mx-1" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger mx-1" title="Delete"
                                                    onclick="showDeleteModal('{{ $coach->encoded_id }}', '{{ $coach->name }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <form id="delete-form-{{ $coach->encoded_id }}" action="{{ route('club.coaches.delete', $coach->encoded_id) }}" method="POST" style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $coaches->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete coach: <strong id="coachNameToDelete"></strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>
@endsection
@push('styles')
<style>
    .pagination {
        --bs-pagination-color: #fff;
        --bs-pagination-bg: #343a40;
        --bs-pagination-border-color: #495057;
        --bs-pagination-hover-color: #fff;
        --bs-pagination-hover-bg: #495057;
        --bs-pagination-hover-border-color: #6c757d;
        --bs-pagination-active-bg: red;
        --bs-pagination-active-border-color: black;
        --bs-pagination-active-color: #fff;
    }
    .pagination .page-link {
        background-color: var(--bs-pagination-bg);
        border-color: var(--bs-pagination-border-color);
        color: var(--bs-pagination-color);
    }
    .pagination .page-link:hover {
        background-color: var(--bs-pagination-hover-bg);
        border-color: var(--bs-pagination-hover-border-color);
        color: var(--bs-pagination-hover-color);
    }
    .pagination .page-item.active .page-link {
        background-color: var(--bs-pagination-active-bg);
        border-color: var(--bs-pagination-active-border-color);
        color: var(--bs-pagination-active-color);
    }
</style>
@endpush
@section('scripts')
<script>
    let coachIdToDelete = null;
    function showDeleteModal(coachId, coachName) {
        coachIdToDelete = coachId;
        document.getElementById('coachNameToDelete').textContent = coachName;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (coachIdToDelete) {
            const form = document.getElementById('delete-form-' + coachIdToDelete);
            if (form) {
                form.submit();
            }
        }
    });
</script>
@endsection
