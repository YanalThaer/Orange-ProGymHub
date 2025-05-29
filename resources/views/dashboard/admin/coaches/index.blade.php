@extends('layouts.dashboard')
@section('title', 'Admin - All Coaches')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
                    <span>All Coaches</span>
                    <div>
                        <a href="{{ route('admin.coaches.trashed') }}" class="btn btn-warning btn-sm mx-1">
                            <i class="fas fa-trash-alt"></i> View Deleted Coaches
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table id="coachesTable" class="table table-bordered table-dark table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Club</th>
                                    <th>Experience</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coaches as $coach)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $coach->name }}</td>
                                    <td>{{ $coach->email }}</td>
                                    <td>{{ $coach->phone ?? 'N/A' }}</td>
                                    <td>{{ $coach->club ? $coach->club->name : 'N/A' }}</td>
                                    <td>{{ $coach->experience_years ?? 'N/A' }} years</td>
                                    <td class="d-flex justify-content-center">
                                        <a href="{{ route('admin.coaches.show', $coach->getEncodedId()) }}" class="btn btn-sm btn-info mx-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger mx-1"
                                            onclick="showDeleteModal('{{ $coach->getEncodedId() }}', '{{ $coach->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $coach->getEncodedId() }}" action="{{ route('admin.coaches.delete', $coach->getEncodedId()) }}" method="POST" style="display:none;">
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
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (coachIdToDelete) {
            const form = document.getElementById('delete-form-' + coachIdToDelete);
            if (form) {
                form.submit();
            }
        }
    });
    $(document).ready(function() {
        $('#coachesTable').DataTable({
            responsive: true,
            "pageLength": 25,
            "paging": false,
            "info": false,
            "searching": false,
            "ordering": true,
        });
    });
</script>
@endsection