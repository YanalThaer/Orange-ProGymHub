@extends('layouts.dashboard')
@section('title', 'Club - Subscription Plans')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
                    <h4 class="mb-0">Subscription Plans</h4>
                    <div>
                        <a href="{{ route('club.subscription-plans.trashed') }}" class="btn btn-warning btn-sm mx-1">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                        <a href="{{ route('club.subscription-plans.create') }}" class="btn btn-primary btn-sm mx-1">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if($subscriptionPlans->isEmpty())
                    <div class="alert alert-info">
                        No subscription plans found. Click the "+" button to create your first subscription plan.
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="plansTable" class="table table-bordered table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Duration (days)</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptionPlans as $plan)
                                <tr>
                                    <td>{{ $plan->name }}</td>
                                    <td>${{ number_format($plan->price, 2) }}</td>
                                    <td>{{ $plan->duration_days }}</td>
                                    <td>{{ ucfirst($plan->type) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $plan->is_active ? 'success' : 'danger' }}">
                                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="d-flex justify-content-center">
                                        <a href="{{ route('club.subscription-plans.edit', $plan->getEncodedId()) }}" class="btn btn-sm btn-info mx-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger mx-1"
                                            onclick="showDeleteModal('{{ $plan->getEncodedId() }}', '{{ $plan->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $plan->getEncodedId() }}"
                                            action="{{ route('club.subscription-plans.delete', $plan->getEncodedId()) }}"
                                            method="POST" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                Are you sure you want to delete plan: <strong id="planNameToDelete"></strong>?
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
    let planIdToDelete = null;

    function showDeleteModal(planId, planName) {
        planIdToDelete = planId;
        document.getElementById('planNameToDelete').textContent = planName;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (planIdToDelete) {
            const form = document.getElementById('delete-form-' + planIdToDelete);
            if (form) {
                form.submit();
            }
        }
    });
    $(document).ready(function() {
        $('#plansTable').DataTable({
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