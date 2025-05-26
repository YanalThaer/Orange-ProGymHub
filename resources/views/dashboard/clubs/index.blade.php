@extends('layouts.dashboard')
@section('title', 'Admin - Clubs Management')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
                    <span>Clubs Management</span>
                    <div>
                        <a href="{{ route('admin.trashed-clubs') }}" class="btn btn-warning btn-sm mx-1">
                            <i class="fas fa-trash-restore"></i> View Deleted Clubs
                        </a>
                        <a href="{{ route('clubs.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Club
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-dark table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>City</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clubs as $club)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $club->name }}</td>
                                        <td>{{ $club->email }}</td>
                                        <td>{{ $club->phone }}</td>
                                        <td>{{ $club->city }}</td>
                                        <td>
                                            <span class="badge {{ $club->status == 'active' ? 'bg-success' : ($club->status == 'inactive' ? 'bg-danger' : 'bg-warning') }}">
                                                {{ ucfirst($club->status) }}
                                            </span>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <a href="{{ route('clubs.show', $club->getEncodedId()) }}" class="btn btn-sm btn-info mx-1" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('clubs.edit', $club->getEncodedId()) }}" class="btn btn-sm btn-warning mx-1" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form id="delete-form-{{ $club->getEncodedId() }}" action="{{ route('clubs.destroy', $club->getEncodedId()) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger mx-1" 
                                                onclick="showDeleteModal('{{ $club->getEncodedId() }}', '{{ $club->name }}')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No clubs found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $clubs->links('pagination::bootstrap-5') }}
                    </div>
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
        Are you sure you want to delete: <strong id="itemNameToDelete"></strong>?
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
@push('scripts')
<script>
    let currentDeleteId = null;
    function showDeleteModal(id, name) {
        currentDeleteId = id;
        document.getElementById('itemNameToDelete').textContent = name;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (currentDeleteId) {
            const form = document.getElementById('delete-form-' + currentDeleteId);
            if (form) {
                form.submit();
            } else {
                console.error('Delete form not found for id:', currentDeleteId);
            }
        }
    });
</script>
@endpush
