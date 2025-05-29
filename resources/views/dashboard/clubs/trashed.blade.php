@extends('layouts.dashboard')
@section('title', 'Admin - Deleted Clubs')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
                    <span>Deleted Clubs</span>
                    <div>
                        <a href="{{ route('admin.clubs') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Clubs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">{{ session('status') }}</div>
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
                                    <th>Deleted At</th>
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
                                    <td>{{ $club->deleted_at->format('Y-m-d H:i') }}</td>
                                    <td class="d-flex justify-content-center">
                                        {{-- Restore Button --}}
                                        <button type="button" class="btn btn-sm btn-success mx-1" title="Restore"
                                            onclick="showRestoreModal('{{ $club->getEncodedId() }}', '{{ $club->name }}')">
                                            <i class="fas fa-trash-restore"></i>
                                        </button>

                                        <form id="restore-form-{{ $club->getEncodedId() }}" action="{{ route('clubs.restore', $club->getEncodedId()) }}" method="POST" style="display:none;">
                                            @csrf
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No deleted clubs found</td>
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
<div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreModalLabel">Confirm Restore</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to restore the club: <strong id="clubNameToRestore"></strong>?
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
@push('scripts')
<script>
    let currentRestoreId = null;

    function showRestoreModal(id, name) {
        currentRestoreId = id;
        document.getElementById('clubNameToRestore').textContent = name;
        const restoreModal = new bootstrap.Modal(document.getElementById('restoreModal'));
        restoreModal.show();
    }
    document.getElementById('confirmRestoreBtn').addEventListener('click', function() {
        if (currentRestoreId) {
            const form = document.getElementById('restore-form-' + currentRestoreId);
            if (form) {
                form.submit();
            }
        }
    });
</script>
@endpush