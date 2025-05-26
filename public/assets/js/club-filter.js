// Club filtering functionality
function filterClubsInstantly(searchTerm) {
    searchTerm = searchTerm.toLowerCase().trim();

    // If search term is empty, show all clubs and exit
    if (searchTerm === '') {
        showAllClubs();
        return;
    }

    // Get all club cards
    const clubCards = document.querySelectorAll('.club-card');
    let hasVisibleCards = false;

    // Loop through all club cards
    clubCards.forEach(card => {
        const clubName = card.querySelector('.club-name').textContent.toLowerCase();
        const clubBio = card.querySelector('.club-bio').textContent.toLowerCase();
        const clubCity = card.querySelector('.club-city').textContent.toLowerCase();

        // Check if the club matches the search term
        if (clubName.includes(searchTerm) ||
            clubBio.includes(searchTerm) ||
            clubCity.includes(searchTerm)) {
            card.style.display = ''; // Show card
            hasVisibleCards = true;
        } else {
            card.style.display = 'none'; // Hide card
        }
    });

    // Show or hide the "no results" message
    const noResultsMessage = document.getElementById('no-results-message');
    if (noResultsMessage) {
        noResultsMessage.style.display = hasVisibleCards ? 'none' : 'block';
    }

    // Update the search results indicator
    updateSearchIndicator(searchTerm);
}

// Function to update the search indicator
function updateSearchIndicator(searchTerm) {
    const searchIndicator = document.getElementById('search-indicator');
    
    if (!searchIndicator) return;
    
    if (searchTerm && searchTerm.trim() !== '') {
        searchIndicator.innerHTML = 'Showing results for: <strong>"' + searchTerm + '"</strong>';
        searchIndicator.parentElement.parentElement.parentElement.style.display = 'block';
    } else {
        searchIndicator.innerHTML = '';
        searchIndicator.parentElement.parentElement.parentElement.style.display = 'none';
    }
}

// Initialize filtering with any existing search term
document.addEventListener('DOMContentLoaded', function() {
    const searchBox = document.getElementById('search-box');
    
    if (searchBox) {
        // Apply initial filtering if there's a value
        if (searchBox.value) {
            filterClubsInstantly(searchBox.value);
        }
        
        // Add event listener for input events including delete/backspace
        searchBox.addEventListener('input', function(e) {
            filterClubsInstantly(this.value);
            
            // If search box is cleared, show all clubs
            if (this.value === '') {
                showAllClubs();
            }
        });
    }
});

// Function to clear the search input and show all clubs
function clearSearch() {
    const searchBox = document.getElementById('search-box');
    if (searchBox) {
        searchBox.value = '';
        showAllClubs();
    }
}

// Function to show all clubs when search is cleared
function showAllClubs() {
    // Show all club cards
    const clubCards = document.querySelectorAll('.club-card');
    clubCards.forEach(card => {
        card.style.display = '';
    });
    
    // Hide the "no results" message
    const noResultsMessage = document.getElementById('no-results-message');
    if (noResultsMessage) {
        noResultsMessage.style.display = 'none';
    }
    
    // Clear the search indicator
    updateSearchIndicator('');
}
