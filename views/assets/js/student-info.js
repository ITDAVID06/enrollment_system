
function navigateTabs(direction) {
// Get all tab links and contents
var tabs = document.querySelectorAll('#profileTabs button');
var contents = document.querySelectorAll('.tab-pane');

// Find the currently active tab
var activeIndex = Array.from(tabs).findIndex(tab => tab.classList.contains('active'));

// Calculate the new index
var newIndex = activeIndex + direction;

// Ensure the new index is within bounds
if (newIndex >= 0 && newIndex < tabs.length) {
    // If navigating to the Review tab, transfer the selected data
    if (tabs[newIndex].id === 'reviewStudentInfo-tab') {
        transferDataToReviewTab();
    }

    // Remove 'active' and 'show' classes from current tab and content
    tabs[activeIndex].classList.remove('active');
    contents[activeIndex].classList.remove('active', 'show');

    // Add 'active' and 'show' classes to the new tab and content
    tabs[newIndex].classList.add('active');
    contents[newIndex].classList.add('active', 'show');
}
}
function transferDataToReviewTab() {
// Map of source input IDs to target review field IDs
const dataMapping = {
    lastname: 'reviewLastName',
    firstname: 'reviewFirstName',
    middlename: 'reviewMiddleName',
    gender: 'reviewGender',
    dob: 'reviewDOB',
    placeOfBirth: 'reviewPOB',
    contactMobile: 'reviewMobile',
    email: 'reviewEmail',
    profilePicture: 'reviewPic',
    fullname: 'reviewContactName',
    mobile: 'reviewMobileNo',
    province: 'reviewProvince',
    city: 'reviewcity',
    barangay: 'reviewBarangay',
    street_address: 'reviewStreetAdd',
    zipcode: 'reviewZipCode',
    elementary_school: 'reviewElemSchool',
    high_school: 'reviewHighSchool',
    course: 'reviewCourse',
    gradeFile: 'reviewGrade'
};

// Iterate over the mapping and populate the review tab fields
for (const [sourceId, targetId] of Object.entries(dataMapping)) {
    const sourceElement = document.getElementById(sourceId);
    const targetElement = document.getElementById(targetId);

    if (sourceElement && targetElement) {
        if (sourceElement.tagName === 'SELECT') {
            // Handle select dropdowns
            targetElement.textContent = sourceElement.options[sourceElement.selectedIndex]?.text || '';
        } else if (sourceElement.tagName === 'INPUT' && sourceElement.type === 'file') {
            // Handle file inputs
            targetElement.textContent = sourceElement.files[0]?.name || 'No file uploaded';
        } else if (sourceElement.tagName === 'INPUT') {
            // Handle text input fields
            targetElement.textContent = sourceElement.value || '';
        } else if (sourceElement.tagName === 'IMG') {
            // Handle images (e.g., profile pictures)
            targetElement.textContent = sourceElement.src || '';
        }
    }
}
}

function validateAndSubmit() {
const requiredFields = [
    'lastname', 'firstname', 'middlename', 'gender', 'dob',
    'placeOfBirth', 'contactMobile', 'email', 'profilePicture',
    'fullname', 'mobile', 'province', 'city', 'barangay',
    'street_address', 'zipcode', 'elementary_school', 'high_school',
    'course', 'gradeFile'
];

let allFieldsFilled = true;

// Check each required field
for (const fieldId of requiredFields) {
    const field = document.getElementById(fieldId);

    if (field) {
        if (field.tagName === 'INPUT') {
            if ((field.type === 'text' || field.type === 'email' || field.type === 'date') && !field.value.trim()) {
                allFieldsFilled = false;
                field.classList.add('is-invalid'); // Add visual cue for invalid fields
            } else if (field.type === 'file' && (!field.files || field.files.length === 0)) {
                allFieldsFilled = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid'); // Remove invalid class if valid
            }
        } else if (field.tagName === 'SELECT' && !field.value.trim()) {
            allFieldsFilled = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    }
}

// Show validation modal if any field is empty
if (!allFieldsFilled) {
    const validationModal = new bootstrap.Modal(document.getElementById('validationModal'));
    validationModal.show();
} else {
    // Submit the form
    document.getElementById('studentForm').submit();

    // Show the success modal
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
}
}
