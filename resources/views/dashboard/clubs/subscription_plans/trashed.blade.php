@extends('layouts.dashboard')
@section('title', 'Club - Deleted Subscription Plans')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
                    <span>Deleted Subscription Plans</span>
                    <div>
                        <a href="{{ route('club.subscription-plans') }}" class="btn btn-primary btn-sm mx-1">
                            <i class="fas fa-arrow-left"></i> Back to Plans
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

                    @if($trashedPlans->isEmpty())
                        <div class="alert alert-info">No deleted subscription plans found.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-dark table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Duration (days)</th>
                                        <th>Type</th>
                                        <th>Deleted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trashedPlans as $plan)
                                        <tr>
                                            <td>{{ $plan->name }}</td>
                                            <td>${{ number_format($plan->price, 2) }}</td>
                                            <td>{{ $plan->duration_days }}</td>
                                            <td>{{ ucfirst($plan->type) }}</td>
                                            <td>{{ $plan->deleted_at->format('Y-m-d H:i') }}</td>
                                            <td class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-success btn-sm mx-1"
                                                    onclick="showRestoreModal('{{ $plan->getEncodedId() }}', '{{ $plan->name }}')"
                                                    title="Restore Plan">
                                                    <i class="fas fa-trash-restore"></i>
                                                </button>

                                                <form id="restore-form-{{ $plan->getEncodedId() }}" action="{{ route('club.subscription-plans.restore', $plan->getEncodedId()) }}" method="POST" style="display:none;">
                                                    @csrf
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
<div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreModalLabel">Confirm Restore</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to restore the subscription plan: <strong id="planNameToRestore"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmRestoreBtn">Restore</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    let planIdToRestore = null;
    function showRestoreModal(planId, planName) {
        planIdToRestore = planId;
        document.getElementById('planNameToRestore').textContent = planName;
        const modal = new bootstrap.Modal(document.getElementById('restoreModal'));
        modal.show();
    }
    document.getElementById('confirmRestoreBtn').addEventListener('click', function () {
        if (planIdToRestore) {
            const form = document.getElementById('restore-form-' + planIdToRestore);
            if (form) {
                form.submit();
            }
        }
    });
</script>
@endsection
