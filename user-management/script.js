// script.js

// Function to show a specific popup by its ID
function showPopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = 'flex';
    }
}

// Function to close a specific popup
function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = 'none';
    }
}

// Helper to close popup if clicking outside the content box
window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.style.display = 'none';
    }
}
// script.js

// Function to switch between steps
function navigateToStep(stepNumber) {
    // Hide all steps
    document.getElementById('step1-content').style.display = 'none';
    document.getElementById('step2-content').style.display = 'none';

    // Show the target step
    document.getElementById('step' + stepNumber + '-content').style.display = 'block';

    // Update Tab UI
    const tabs = document.querySelectorAll('.tab-link');
    tabs.forEach((tab, index) => {
        if (index + 1 === stepNumber) {
            tab.classList.add('active');
        } else {
            tab.classList.remove('active');
        }
    });
}

// Popup logic
function showPopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = 'flex';
    }
}

function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = 'none';
    }
}