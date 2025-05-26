/**
 * Club management JavaScript functionality
 */

// Handle club deletion modal
function setupClubDeletion() {
    document.addEventListener('DOMContentLoaded', function() {
        // Get all delete buttons
        const deleteButtons = document.querySelectorAll('.delete-btn');
        
        // Add click event listener to each delete button
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const clubId = this.getAttribute('data-club-id');
                const clubName = this.getAttribute('data-club-name');
                
                // Set the club name in the modal
                document.getElementById('itemNameToDelete').textContent = clubName;
                
                // Set the form action URL
                document.getElementById('deleteForm').setAttribute('action', '/dashboard/clubs/' + clubId);
                
                // Show the modal using Bootstrap 5
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            });
        });
    });
}

// Initialize club functions
setupClubDeletion();