@extends('layouts.dashboard')
@section('title', 'Admin - Deleted Users')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
                    <span>Deleted Users</span>
                    <div>
                        <a href="{{ route('admin.users') }}" class="btn btn-primary btn-sm mx-1">
                            <i class="fas fa-arrow-left"></i> Back to All Users
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                    @endif
                    @if(count($users) > 0)
                    <div class="table-responsive">
                        <table id="trashedUsersTable" class="table table-bordered table-dark table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->deleted_at->format('Y-m-d H:i') }}</td>
                                    <td class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-success btn-sm mx-1"
                                            onclick="showRestoreModal('{{ $user->getEncodedId() }}', '{{ $user->name }}')"
                                            title="Restore User">
                                            <i class="fas fa-trash-restore"></i>
                                        </button>

                                        <form id="restore-form-{{ $user->getEncodedId() }}" action="{{ route('admin.users.restore', $user->getEncodedId()) }}" method="POST" style="display:none;">
                                            @csrf
                                            @method('POST')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                    @else
                    <div class="alert alert-info">No deleted users found.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreModalLabel">Confirm Restore</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to restore user: <strong id="userNameToRestore"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmRestoreBtn">Restore</button>
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
    let userIdToRestore = null;

    function showRestoreModal(userId, userName) {
        userIdToRestore = userId;
        document.getElementById('userNameToRestore').textContent = userName;
        const restoreModal = new bootstrap.Modal(document.getElementById('restoreModal'));
        restoreModal.show();
    }
    document.getElementById('confirmRestoreBtn').addEventListener('click', function() {
        if (userIdToRestore) {
            const form = document.getElementById('restore-form-' + userIdToRestore);
            if (form) {
                form.submit();
            }
        }
    });
    $(document).ready(function() {
        $('#trashedUsersTable').DataTable({
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