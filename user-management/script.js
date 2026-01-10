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


// 3. Interactive Tag Input for Skills
document.addEventListener('DOMContentLoaded', () => {
    const skillsInput = document.getElementById('skillsInput');
    const tagsContainer = document.getElementById('tagsContainer');
    const hiddenSkillsInput = document.getElementById('hiddenSkillsInput');
    const options = document.querySelectorAll('#skill-options option');
    
    const skillMap = {};
    options.forEach(opt => {
        skillMap[opt.value] = opt.dataset.id;
    });
    if (skillsInput && tagsContainer && hiddenSkillsInput) {
        let skillIds = [];
        let skillNames = [];

        function updateHiddenInput() { hiddenSkillsInput.value = skillIds.join(','); }
        function renderTags() {
            tagsContainer.querySelectorAll('.tag').forEach(tag => tag.remove());
            skillNames.forEach((skill, index) => {
                const tag = document.createElement('div');
                tag.className = 'tag';
                tag.innerHTML = `${skill} <span class="close" data-index="${index}">&times;</span>`;
                tagsContainer.insertBefore(tag, skillsInput);
            });
        }

        skillsInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                const name = skillsInput.value.trim();

                if (skillMap[name] && !skillIds.includes(skillMap[name])) {
                    skillNames.push(name);
                    skillIds.push(skillMap[name]);
                    skillsInput.value = '';
                    renderTags();
                    updateHiddenInput();
                }
            }
        });

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

// admin_user_form image upload
function previewUserImage(input) {
    console.log("File selected!"); // To Check browser console
    
    const preview = document.getElementById('user-img');
    const placeholder = document.getElementById('placeholder-icon');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            // 1. Set the image source to the file data
            preview.src = e.target.result;
            // 2. Show the image
            preview.style.setProperty("display", "block", "important");
            // 3. Hide the placeholder icon
            placeholder.style.setProperty("display", "none", "important");
            
            console.log("Image switched!");
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function removeUserImage() {
    const preview = document.getElementById('user-img');
    const placeholder = document.getElementById('placeholder-icon');
    const input = document.getElementById('user-photo-input');

    input.value = ""; // Reset file input
    preview.src = "";
    preview.style.display = 'none';
    placeholder.style.display = 'block';
    console.log("Image removed!");
}

// 1. DATA OBJECT (Must be global)
// Ensure this data is at the top of your script.js
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
    // Automatically populate regions for Sri Lanka on page load
    if (document.getElementById("country-select")) {
        updateRegions(); 
    }
});

window.updateRegions = function() {
    const countrySel = document.getElementById("country-select");
    const regionSel = document.getElementById("region-select");
    const citySel = document.getElementById("city-select");
    
    const selectedCountry = countrySel.value;

    // Reset dropdowns
    regionSel.innerHTML = '<option value="">Select Region</option>';
    citySel.innerHTML = '<option value="">Select Town</option>';

    if (selectedCountry && locationData[selectedCountry]) {
        const regions = Object.keys(locationData[selectedCountry]);
        regions.forEach(region => {
            const opt = document.createElement("option");
            opt.value = region;
            opt.text = region;
            regionSel.add(opt);
        });
    }
};

window.updateTowns = function() {
    const countrySel = document.getElementById("country-select");
    const regionSel = document.getElementById("region-select");
    const citySel = document.getElementById("city-select");
    
    const selectedCountry = countrySel.value;
    const selectedRegion = regionSel.value;

    citySel.innerHTML = '<option value="">Select Town</option>';

    if (selectedCountry && selectedRegion && locationData[selectedCountry][selectedRegion]) {
        const towns = locationData[selectedCountry][selectedRegion];
        towns.forEach(town => {
            const opt = document.createElement("option");
            opt.value = town;
            opt.text = town;
            citySel.add(opt);
        });
    }
};

//Resume Upload Preview/Check 
    const resumeInput = document.getElementById('resume_upload');
    if (resumeInput) {
        resumeInput.addEventListener('change', function() {
            if (this.files[0]) {
                console.log("File selected: " + this.files[0].name);
            }
        });
    }

let searchTimer;

/**
 * Automatically triggers search after the user stops typing
 */
function liveSearch() {
    // Clear the timer if the user is still typing
    clearTimeout(searchTimer);

    // Wait 500ms after the last keystroke before reloading
    searchTimer = setTimeout(function() {
        const searchValue = document.getElementById('searchInput').value.trim();
        const urlParams = new URLSearchParams(window.location.search);

        if (searchValue !== "") {
            urlParams.set('search', searchValue);
        } else {
            // If the field is cleared, remove the search parameter entirely
            urlParams.delete('search');
        }

        // Reset to first page
        urlParams.delete('page');

        // Reload the page with the new filters
        window.location.href = window.location.pathname + '?' + urlParams.toString();
    }, 500); 
}

/**
 * Filters the user list based on the selected account status
 */
function filterByStatus() {
    const statusValue = document.getElementById('statusFilter').value;
    const urlParams = new URLSearchParams(window.location.search);

    if (statusValue !== "") {
        urlParams.set('status', statusValue);
    } else {
        // If "All Status" is selected, remove the parameter
        urlParams.delete('status');
    }

    // Always return to page 1 when filter changes
    urlParams.delete('page');

    // Reload with all combined filters (Search + Role + Status)
    window.location.href = window.location.pathname + '?' + urlParams.toString();
}

/**
 * Filters the user list based on registration date range
 */
function filterByDate() {
    const dateValue = document.getElementById('dateFilter').value;
    const urlParams = new URLSearchParams(window.location.search);

    if (dateValue !== "") {
        urlParams.set('date_range', dateValue);
    } else {
        urlParams.delete('date_range');
    }

    // Reset pagination
    urlParams.delete('page');

    window.location.href = window.location.pathname + '?' + urlParams.toString();
}