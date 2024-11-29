var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
    var steps = document.getElementsByClassName("step"); // Get all steps
    steps[n].style.display = "block"; // Show the current step

    // Update button visibility
    if (n === 0) {
        document.getElementById("prevBtn").style.display = "none"; // Hide "Previous" button on the first step
        document.getElementById("nextBtn").style.display = "inline"; // Show "Next" button
        document.getElementById("nextBtn").innerHTML = "Next"; // Ensure "Next" button text
    } else if (n === steps.length - 1) {
        document.getElementById("prevBtn").style.display = "inline"; // Show "Previous" button
        document.getElementById("nextBtn").style.display = "inline"; // Show "Submit" button
        document.getElementById("nextBtn").innerHTML = "Submit"; // Change "Next" button to "Submit"
    } else {
        document.getElementById("prevBtn").style.display = "inline"; // Show "Previous" button
        document.getElementById("nextBtn").style.display = "inline"; // Show "Next" button
        document.getElementById("nextBtn").innerHTML = "Next"; // Ensure "Next" button text
    }

    fixStepIndicator(n); // Update step indicators
}


function nextPrev(n) {
    var steps = document.getElementsByClassName("step"); // Get all steps

    // Validate the current tab
    if (n === 1 && !validateForm()) return false;

    // Hide the current tab
    steps[currentTab].style.display = "none";

    // Update the current tab index
    currentTab += n;

    // If we are on the review step, populate the review section
    if (currentTab === steps.length - 1) {
        // Enrollment Information
        document.getElementById("reviewStatus").textContent = document.getElementById("status").value;
        document.getElementById("reviewSchool").textContent = document.getElementById("previousSchool").value;
        document.getElementById("reviewProgram").textContent = document.getElementById("program").value;
        document.getElementById("reviewYearLevel").textContent = document.getElementById("yearLevel").value;
        document.getElementById("reviewTerm").textContent = document.getElementById("term").value;
        document.getElementById("reviewSchoolYear").textContent = document.getElementById("schoolYear").value;

        // Personal Information
        document.getElementById("reviewFullName").textContent = 
            `${document.getElementById("firstName").value} ${document.getElementById("middleName").value || ''} ${document.getElementById("lastName").value}`;
        document.getElementById("reviewGender").textContent = document.getElementById("gender").value;
        document.getElementById("reviewDOB").textContent = document.getElementById("dob").value;
        document.getElementById("reviewPlaceOfBirth").textContent = document.getElementById("placeOfBirth").value;
        document.getElementById("reviewContactMobile").textContent = document.getElementById("contactMobile").value;
        document.getElementById("reviewEmail").textContent = document.getElementById("email").value;
        document.getElementById("reviewCitizenship").textContent = document.getElementById("citizenship").value;
        document.getElementById("reviewFirstGen").textContent = document.getElementById("firstGenStudent").value;

        // Residence Information
        document.getElementById("reviewEmergencyName").textContent = document.getElementById("fullname").value;
        document.getElementById("reviewEmergencyMobile").textContent = document.getElementById("mobile").value;
        document.getElementById("reviewStreet").textContent = document.getElementById("street_address").value;
        document.getElementById("reviewProvince").textContent = document.getElementById("province").value;
        document.getElementById("reviewCity").textContent = document.getElementById("city").value;
        document.getElementById("reviewBarangay").textContent = document.getElementById("barangay").value;
        document.getElementById("reviewZip").textContent = document.getElementById("zipcode").value;

        // Educational Information
        document.getElementById("reviewElementary").textContent = document.getElementById("elementarySchool").value;
        document.getElementById("reviewJuniorHigh").textContent = document.getElementById("juniorHighSchool").value;
        document.getElementById("reviewSeniorHigh").textContent = document.getElementById("seniorHighSchool").value;
        document.getElementById("reviewSHSTrack").textContent = document.getElementById("shsTrack").value;

        // File Upload
        var gradeFile = document.getElementById("gradeFile").files[0];
        document.getElementById("reviewGradeFile").textContent = gradeFile ? gradeFile.name : "No file uploaded";
    }

    // If we reached the last step, submit the form
    if (currentTab >= steps.length) {
        document.getElementById("signUpForm").submit();
        return false;
    }

    // Otherwise, show the next/previous tab
    showTab(currentTab);
}



function validateForm() {
    // This function deals with validation of the form fields
    var x, y, i, valid = true;
    x = document.getElementsByClassName("step");
    y = x[currentTab].querySelectorAll("input, select");
    
    // A loop that checks every input and select field in the current tab:
    for (i = 0; i < y.length; i++) {
        // Add event listeners to each input and select field to remove red border once the user starts typing
        y[i].addEventListener("input", function() {
            if (this.value !== "") {
                this.classList.remove("is-invalid");
            }
        });
        
        // If a field is empty and it is required...
        if (y[i].value === "" && y[i].hasAttribute("required")) {
            // Add an "is-invalid" class to the field to apply styling (red border):
            y[i].classList.add("is-invalid");
            // Set the current valid status to false
            valid = false;
        } else {
            // Remove "is-invalid" class if field is valid
            y[i].classList.remove("is-invalid");
        }
    }
    
    // If the valid status is true, mark the step as finished and valid:
    if (valid) {
        document.getElementsByClassName("stepIndicator")[currentTab].className += " finish";
    }

    return valid; // return the valid status
}


function populateReviewSection() {
    // Enrollment Information
    document.getElementById("reviewStatus").textContent = document.getElementById("status").value;
    document.getElementById("reviewPreviousSchool").textContent = document.getElementById("previousSchool").value;
    document.getElementById("reviewProgram").textContent = document.getElementById("program").value;
    document.getElementById("reviewYearLevel").textContent = document.getElementById("yearLevel").value;
    document.getElementById("reviewTerm").textContent = document.getElementById("term").value;
    document.getElementById("reviewSchoolYear").textContent = document.getElementById("schoolYear").value;

    // Personal Information
    document.getElementById("reviewLastName").textContent = document.getElementById("lastName").value;
    document.getElementById("reviewFirstName").textContent = document.getElementById("firstName").value;
    document.getElementById("reviewMiddleName").textContent = document.getElementById("middleName").value;
    document.getElementById("reviewGender").textContent = document.getElementById("gender").value;
    document.getElementById("reviewDob").textContent = document.getElementById("dob").value;
    document.getElementById("reviewPlaceOfBirth").textContent = document.getElementById("placeOfBirth").value;
    document.getElementById("reviewContactMobile").textContent = document.getElementById("contactMobile").value;
    document.getElementById("reviewEmail").textContent = document.getElementById("email").value;
    document.getElementById("reviewCitizenship").textContent = document.getElementById("citizenship").value;
    document.getElementById("reviewFirstGenStudent").textContent = document.getElementById("firstGenStudent").value;

    // Residence Information
    document.getElementById("reviewFullname").textContent = document.getElementById("fullname").value;
    document.getElementById("reviewMobile").textContent = document.getElementById("mobile").value;
    document.getElementById("reviewProvince").textContent = document.getElementById("province").value;
    document.getElementById("reviewCity").textContent = document.getElementById("city").value;
    document.getElementById("reviewBarangay").textContent = document.getElementById("barangay").value;
    document.getElementById("reviewZipcode").textContent = document.getElementById("zipcode").value;
}


function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    var i, x = document.getElementsByClassName("stepIndicator");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
        x[i].classList.remove("finish"); // Ensure to reset the "finish" class if we're going back
    }
    // ...and adds the "active" class on the current step:
    x[n].className += " active";
    // If we are going forward, mark previous steps as finished
    for (i = 0; i < n; i++) {
        x[i].classList.add("finish");
    }
}

function validateEmail() {
    var email = document.getElementById("email");
    var errorMessage = document.getElementById("email-error");

    // If the field is empty, remove error message and invalid class
    if (email.value === "") {
        email.classList.remove("is-invalid");
        errorMessage.style.display = "none"; // Hide error message
        return;
    }

    // Check if the email is valid
    if (!email.validity.valid) {
        email.classList.add("is-invalid");
        errorMessage.style.display = "block"; // Show error message
    } else {
        email.classList.remove("is-invalid");
        errorMessage.style.display = "none"; // Hide error message
    }
}

document.getElementById("contactMobile").addEventListener("input", function() {
    var contactMobile = document.getElementById("contactMobile");
    var errorMessage = document.getElementById("contactMobile-error");

    // Check if the value contains non-numeric characters
    if (!contactMobile.value.match(/^\d*$/)) {
        contactMobile.classList.add("is-invalid");
        errorMessage.style.display = "block";
    } else {
        contactMobile.classList.remove("is-invalid");
        errorMessage.style.display = "none";
    }
});

document.getElementById("mobile").addEventListener("input", function() {
    var mobile = document.getElementById("mobile");
    var errorMessage = document.getElementById("mobile-error");

    // Check if the value contains non-numeric characters
    if (!mobile.value.match(/^\d*$/)) {
        mobile.classList.add("is-invalid");
        errorMessage.style.display = "block";
    } else {
        mobile.classList.remove("is-invalid");
        errorMessage.style.display = "none";
    }
});






