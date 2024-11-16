var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
    // This function will display the specified tab of the form...
    var x = document.getElementsByClassName("step");
    x[n].style.display = "block";
    // ... and fix the Previous/Next buttons:
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";
    } else {
        document.getElementById("nextBtn").innerHTML = "Next";
    }
    // ... and run a function that will display the correct step indicator:
    fixStepIndicator(n);
}

function nextPrev(n) {
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("step");

    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) return false;

    // Hide the current tab:
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;

    // If moving to the next step (step 1), populate review data:
    if (n === 1) {
        populateReviewSection(); // Populate the review section with form data
    }

    // If you have reached the end of the form...
    if (currentTab >= x.length) {
        // Submit the form if on the last tab
        document.getElementById("signUpForm").submit();
        return false;
    }

    // Otherwise, display the correct tab:
    showTab(currentTab);
    // Ensure the step indicator updates after each tab change
    fixStepIndicator(currentTab);
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
