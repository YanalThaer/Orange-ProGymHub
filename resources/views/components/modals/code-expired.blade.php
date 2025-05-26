<div class="modal fade" id="expiredModal" tabindex="-1" aria-labelledby="expiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expiredModalLabel">Code Expired</h5>
                <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>The verification code has expired. You will need to register again to receive a new verification code.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="closeModal()">Return to Registration</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="register-route" value="{{ route('register') }}">