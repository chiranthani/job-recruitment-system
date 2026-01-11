/**
 * UI & MODAL MANAGEMENT
 */

// Function to show a specific popup/modal by its ID
function showPopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = 'flex'; // Centered overlay
    }
}

// Function to close a specific popup/modal
function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = 'none';
    }
}

// Global click listener: Close modal if the user clicks the dark background (overlay)
window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.style.display = 'none';
    }
}

/**
 * MULTI-STEP FORM NAVIGATION
 * Used for complex forms like Candidate Profile Registration
 */
function navigateToStep(stepNumber) {
    // Hide all step containers (Add more IDs here if you have 3+ steps)
    document.getElementById('step1-content').style.display = 'none';
    document.getElementById('step2-content').style.display = 'none';

    // Show the specific target step
    document.getElementById('step' + stepNumber + '-content').style.display = 'block';

    // Highlight the active Tab in the UI
    const tabs = document.querySelectorAll('.tab-link');
    tabs.forEach((tab, index) => {
        if (index + 1 === stepNumber) {
            tab.classList.add('active');
        } else {
            tab.classList.remove('active');
        }
    });
}

/**
 * SKILLS TAG INPUT SYSTEM
 * Converts text input into visual tags and stores IDs in a hidden field for PHP
 */
document.addEventListener('DOMContentLoaded', () => {
    const skillsInput = document.getElementById('skillsInput');
    const tagsContainer = document.getElementById('tagsContainer');
    const hiddenSkillsInput = document.getElementById('hiddenSkillsInput');
    const options = document.querySelectorAll('#skill-options option');
    
    // Map skill names to their Database IDs from the datalist
    const skillMap = {};
    options.forEach(opt => {
        skillMap[opt.value] = opt.dataset.id;
    });

    if (skillsInput && tagsContainer && hiddenSkillsInput) {
        let skillIds = [];
        let skillNames = [];

        // Synchronize the hidden input with the array for form submission
        function updateHiddenInput() { hiddenSkillsInput.value = skillIds.join(','); }

        // Redraw tags inside the container
        function renderTags() {
            tagsContainer.querySelectorAll('.tag').forEach(tag => tag.remove());
            skillNames.forEach((skill, index) => {
                const tag = document.createElement('div');
                tag.className = 'tag';
                tag.innerHTML = `${skill} <span class="close" data-index="${index}">&times;</span>`;
                tagsContainer.insertBefore(tag, skillsInput);
            });
        }

        // Listen for Enter or Comma to add a tag
        skillsInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                const name = skillsInput.value.trim();

                // Validation: Only add if it exists in the database list and isn't already added
                if (skillMap[name] && !skillIds.includes(skillMap[name])) {
                    skillNames.push(name);
                    skillIds.push(skillMap[name]);
                    skillsInput.value = '';
                    renderTags();
                    updateHiddenInput();
                }
            }
        });

        // Remove a tag when the 'x' is clicked
        tagsContainer.addEventListener('click', e => {
            if (e.target.classList.contains('close')) {
                const i = e.target.dataset.index;
                skillNames.splice(i, 1);
                skillIds.splice(i, 1);
                renderTags();
                updateHiddenInput();
            }
        });
    }
});

/**
 * IMAGE UPLOAD & PREVIEW LOGIC
 * Used in admin_user_form.php for profile pictures
 */
function previewUserImage(input) {
    const preview = document.getElementById('user-img');
    const placeholder = document.getElementById('placeholder-icon');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            // Set preview source to the local file data
            preview.src = e.target.result;
            // Ensure visual consistency: Show image, Hide icon
            preview.style.setProperty("display", "block", "important");
            placeholder.style.setProperty("display", "none", "important");
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// Resets the image upload field
function removeUserImage() {
    const preview = document.getElementById('user-img');
    const placeholder = document.getElementById('placeholder-icon');
    const input = document.getElementById('user-photo-input');

    input.value = ""; 
    preview.src = "";
    preview.style.display = 'none';
    placeholder.style.display = 'block';
}

/**
 * LOCATION DROPDOWN HIERARCHY
 * Dynamic cascading dropdowns: Country -> Region -> Town
 */
const locationData = {
    "Sri Lanka": {
        "Western": ["Colombo", "Gampaha", "Kalutara"],
        "Central": ["Kandy", "Matale", "Nuwara Eliya"],
        "Southern": ["Galle", "Matara", "Hambantota"],
        "North": ["Jaffna", "Kilinochchi", "Mannar"],
        "Eastern": ["Trincomalee", "Batticaloa", "Ampara"]
    }
};

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById("country-select")) {
        updateRegions(); 
    }
});

// Updates the Region list based on selected Country
window.updateRegions = function() {
    const countrySel = document.getElementById("country-select");
    const regionSel = document.getElementById("region-select");
    const citySel = document.getElementById("city-select");
    const selectedCountry = countrySel.value;

    regionSel.innerHTML = '<option value="">Select Region</option>';
    citySel.innerHTML = '<option value="">Select Town</option>';

    if (selectedCountry && locationData[selectedCountry]) {
        Object.keys(locationData[selectedCountry]).forEach(region => {
            const opt = document.createElement("option");
            opt.value = region;
            opt.text = region;
            regionSel.add(opt);
        });
    }
};

// Updates Town list based on selected Region
window.updateTowns = function() {
    const countrySel = document.getElementById("country-select");
    const regionSel = document.getElementById("region-select");
    const citySel = document.getElementById("city-select");
    
    const selectedCountry = countrySel.value;
    const selectedRegion = regionSel.value;

    citySel.innerHTML = '<option value="">Select Town</option>';

    if (selectedCountry && selectedRegion && locationData[selectedCountry][selectedRegion]) {
        locationData[selectedCountry][selectedRegion].forEach(town => {
            const opt = document.createElement("option");
            opt.value = town;
            opt.text = town;
            citySel.add(opt);
        });
    }
};

/**
 * ADMIN SEARCH & FILTER LOGIC
 * Reloads the user list page with URL parameters for PHP filtering
 */
let searchTimer;

// Live Search: Waits for user to stop typing (Debounce)
function liveSearch() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(function() {
        const searchValue = document.getElementById('searchInput').value.trim();
        const urlParams = new URLSearchParams(window.location.search);

        if (searchValue !== "") urlParams.set('search', searchValue);
        else urlParams.delete('search');

        urlParams.delete('page'); // Reset to page 1
        window.location.href = window.location.pathname + '?' + urlParams.toString();
    }, 500); 
}

// Filter by Account Status (Active/Inactive)
function filterByStatus() {
    const statusValue = document.getElementById('statusFilter').value;
    const urlParams = new URLSearchParams(window.location.search);

    if (statusValue !== "") urlParams.set('status', statusValue);
    else urlParams.delete('status');

    urlParams.delete('page');
    window.location.href = window.location.pathname + '?' + urlParams.toString();
}

/**
 * FORM CONTROLS
 */

// Reset form and UI elements
function clearForm() {
    if (confirm("Are you sure you want to clear all entered information?")) {
        const form = document.getElementById('multiStepProfileForm');
        form.reset();
        document.getElementById('hiddenSkillsInput').value = "";
        const tagsContainer = document.getElementById('tagsContainer');
        if (tagsContainer) {
            tagsContainer.querySelectorAll('.tag').forEach(tag => tag.remove());
        }
        navigateToStep(1);
    }
}

// Dynamic UI: Show/Hide Company dropdown based on Role (Recruiter = 2)
function toggleCompanyField() {
    const roleSelect = document.getElementById('role_id');
    const hiddenRole = document.getElementById('hidden_role_id');
    const companyWrapper = document.getElementById('company_wrapper');
    const companySelect = document.getElementById('company_id');

    if (!roleSelect || !companyWrapper) return;

    // Check role ID (Works for both dynamic select and disabled Edit-mode select)
    const currentRole = roleSelect.disabled && hiddenRole ? hiddenRole.value : roleSelect.value;

    if (currentRole === "2") { // ROLE: RECRUITER
        companyWrapper.style.display = "block";
        companySelect.disabled = false;
        companySelect.setAttribute('required', 'required');
    } else {
        companyWrapper.style.display = "none";
        companySelect.disabled = true;
        companySelect.removeAttribute('required');
        companySelect.value = ""; 
    }
}

// Initialize listeners on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleCompanyField();
    const roleSelect = document.getElementById('role_id');
    if (roleSelect) {
        roleSelect.addEventListener('change', toggleCompanyField);
    }
});
