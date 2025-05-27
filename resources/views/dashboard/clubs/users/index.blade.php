@extends('layouts.dashboard')
@section('title', 'Club - User Management')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
                    <span>User Management</span>
                    <div>
                        <a href="{{ route('club.users.trashed') }}" class="btn btn-warning btn-sm mx-1">
                            <i class="fas fa-trash"></i> View Deleted Users
                        </a>
                        <a href="{{ route('club.users.create') }}" class="btn btn-primary btn-sm mx-1">
                            <i class="fas fa-plus"></i> Add New User
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @foreach (['status', 'success'] as $msg)
                        @if (session($msg))
                            <div class="alert alert-success">{{ session($msg) }}</div>
                        @endif
                    @endforeach

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="mb-4">
                        <div class="btn-group" role="group" aria-label="User filters">
                            @foreach(['all' => 'All Users', 'active' => 'Active Members', 'inactive' => 'Inactive Members'] as $key => $label)
                                <a href="{{ route('club.users', ['filter' => $key]) }}"
                                   class="btn {{ $filter == $key ? 'btn-primary' : 'btn-outline-primary' }}">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>

                    @if($users->isEmpty())
                        <div class="alert alert-info text-white">No users found.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-dark table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Fitness Level</th>
                                        <th>Goal</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone_number }}</td>
                                            <td>
                                                @if($user->fitness_level)
                                                    <span class="badge bg-{{ $user->fitness_level == 'beginner' ? 'primary' : ($user->fitness_level == 'intermediate' ? 'info' : 'success') }}">
                                                        {{ ucfirst($user->fitness_level) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Not specified</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->goal }}</td>
                                            <td class="d-flex justify-content-center">
                                                <a href="{{ route('club.users.show', $user->getEncodedId()) }}" class="btn btn-sm btn-info mx-1">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('club.users.edit', $user->getEncodedId()) }}" class="btn btn-sm btn-primary mx-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger mx-1"
                                                        onclick="showDeleteModal('{{ $user->getEncodedId() }}', '{{ $user->name }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <form id="delete-user-form-{{ $user->getEncodedId() }}" action="{{ route('club.users.delete', $user->getEncodedId()) }}" method="POST" style="display:none;">
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
                            {{ $users->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete user: <strong id="userNameToDelete"></strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    let deleteUserId = '';
    function showDeleteModal(id, name) {
        deleteUserId = id;
        document.getElementById('userNameToDelete').textContent = name;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        document.getElementById(`delete-user-form-${deleteUserId}`).submit();
    });
</script>
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
</style>
@endpush
