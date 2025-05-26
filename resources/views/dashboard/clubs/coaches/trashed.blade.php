@extends('layouts.dashboard')
@section('title', 'Club - Deleted Coaches')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
                    <span>Deleted Coaches</span>
                    <a href="{{ route('club.coaches') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Coaches
                    </a>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                    @endif
                    @if($coaches->isEmpty())
                        <div class="alert alert-info">No deleted coaches found.</div>
                    @else
                        <div class="table-responsive">
                            <table id="trashedCoachesTable" class="table table-bordered table-dark table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Experience</th>
                                        <th>Deleted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coaches as $coach)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="d-flex align-items-center">
                                                @if($coach->profile_image)
                                                    <img src="{{ asset('storage/' . $coach->profile_image) }}" alt="{{ $coach->name }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                @endif
                                                {{ $coach->name }}
                                            </td>
                                            <td>{{ $coach->email }}</td>
                                            <td>{{ $coach->phone }}</td>
                                            <td>
                                                @if($coach->experience_years)
                                                    {{ $coach->experience_years }} {{ Str::plural('year', $coach->experience_years) }}
                                                @else
                                                    <span class="text-muted">Not specified</span>
                                                @endif
                                            </td>
                                            <td>{{ $coach->deleted_at->format('Y-m-d H:i') }}</td>
                                            <td class="d-flex justify-content-center">
                                                <form action="{{ route('club.coaches.restore', $coach->encoded_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-sm" title="Restore">
                                                        <i class="fas fa-trash-restore"></i>
                                                    </button>
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
    let coachIdToRestore = null;
    function showRestoreModal(coachId, coachName) {
        coachIdToRestore = coachId;
        document.getElementById('coachNameToRestore').textContent = coachName;
        const restoreModal = new bootstrap.Modal(document.getElementById('restoreModal'));
        restoreModal.show();
    }
    document.getElementById('confirmRestoreBtn').addEventListener('click', function () {
        if(coachIdToRestore) {
            const form = document.getElementById('restore-form-' + coachIdToRestore);
            if(form) {
                form.submit();
            }
        }
    });
    $(document).ready(function() {
        $('#trashedCoachesTable').DataTable({
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
