/**
 * Email verification page countdown timer and modal functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const countdown = document.getElementById('countdown');
    const submitBtn = document.getElementById('submit-btn');
    const modal = document.getElementById('expiredModal');
    const form = document.getElementById('verify-form');

    // Set duration to 5 minutes (300 seconds)
    let duration = 1 * 60;

    // Initialize the countdown timer
    const timer = setInterval(function () {
        let minutes = Math.floor(duration / 60);
        let seconds = duration % 60;

        // Update the countdown display with padding for single digits
        countdown.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

        // When countdown reaches zero
        if (--duration < 0) {
            clearInterval(timer);
            countdown.textContent = "Expired";
            submitBtn.disabled = true;
            
            // Prevent form submission when expired
            form.onsubmit = function(e) {
                e.preventDefault();
                new bootstrap.Modal(modal).show();
                return false;
            };
            
            // Show the expired modal
            new bootstrap.Modal(modal).show();
        }
    }, 1000);

    // Close modal and redirect to registration
    window.closeModal = function() {
        bootstrap.Modal.getInstance(modal).hide();
        window.location.href = document.getElementById('register-route').value;
    };
});