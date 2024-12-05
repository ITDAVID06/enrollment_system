const filterSemester = (semester) => {
    console.log('Selected semester:', semester);

    // Remove 'active' class from all semester buttons
    const buttons = document.querySelectorAll('.semesterButton');
    buttons.forEach((button) => button.classList.remove('active'));

    // Find the button that matches the semester and add the 'active' class
    const activeButton = Array.from(buttons).find((button) => button.textContent.trim() === semester);
    if (activeButton) {
        activeButton.classList.add('active');
        activeButton.blur(); // Remove focus from the button
    }

    // Load courses for the selected semester
    loadCoursesByProgramAndYear(currentProgramId, currentYear, semester, 1);
};




let currentPage = 1;
let recordsPerPage = 5; // Number of rows per page
let totalRecords = 0; // To store total number of records

const loadCoursesByProgramAndYear = async (programId, year, semester = null, page = 1) => {
    // Update current state variables
    currentProgramId = programId;
    currentYear = year;
    currentPage = page;

    // Construct the URL based on parameters
    let url = `/courses/${programId}/${year}`;
    if (semester) {
        url += `?semester=${encodeURIComponent(semester)}`; // Add semester as a query parameter
    }

    console.log('Fetching courses from URL:', url); // Debugging: Log the constructed URL

    try {
        // Fetch data from the server
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`Failed to fetch courses: ${response.statusText}`);
        }

        // Parse the JSON response
        const courses = await response.json();
        console.log('Fetched courses:', courses); // Debugging: Log the fetched courses

        // Handle pagination logic (client-side slicing)
        const paginatedCourses = courses.slice((page - 1) * recordsPerPage, page * recordsPerPage);
        totalRecords = courses.length; // Update total record count for pagination

        // Populate the table with the fetched courses
        const tableBody = document.querySelector("#courseTable tbody");
        tableBody.innerHTML = paginatedCourses
            .map(
                (course) => `
                <tr>
                    <td>${course.course_code}</td>
                    <td>${course.title}</td>
                    <td>${course.unit}</td>
                    <td>${course.semester}</td>
                    <td>
                        <button onclick="editCourse(${course.id})">
                <span class="material-symbols-rounded facultybutton">
                edit
                </span>
                </button>
                <button onclick="deleteCourse(${course.id})">
                <span class="material-symbols-rounded facultybutton">
                delete
                </span>
                </button>
                    </td>
                </tr>`
            )
            .join("");

        // Update pagination controls
        updatePaginationControls();
    } catch (error) {
        console.error('Error loading courses:', error); // Debugging: Log any errors
        document.querySelector("#courseTable tbody").innerHTML = `
            <tr>
                <td colspan="5">Failed to load courses. Please try again later.</td>
            </tr>`;
    }
};


const updatePaginationControls = () => {
    const totalPages = Math.ceil(totalRecords / recordsPerPage);

    document.getElementById("prevPageButton").disabled = currentPage === 1;
    document.getElementById("nextPageButton").disabled = currentPage === totalPages;

    document.getElementById("pageInfo").textContent = `Page ${currentPage} of ${totalPages}`;
};

// Pagination button handlers
document.getElementById("prevPageButton").onclick = () => {
    if (currentPage > 1) {
        loadCoursesByProgramAndYear(currentProgramId, currentYear, null, currentPage - 1);
    }
};

document.getElementById("nextPageButton").onclick = () => {
    const totalPages = Math.ceil(totalRecords / recordsPerPage);
    if (currentPage < totalPages) {
        loadCoursesByProgramAndYear(currentProgramId, currentYear, null, currentPage + 1);
    }
};



// Search Courses
document.getElementById("searchButton").onclick = async () => {
    const query = document.getElementById("searchInput").value.trim().toLowerCase();
    const programItems = document.querySelectorAll('.programItem');
    programItems.forEach((item) => item.classList.remove('active'));
    const sbuttons = document.querySelectorAll('.semesterButton');
    sbuttons.forEach((button) => button.classList.remove('active'));
    const ybuttons = document.querySelectorAll('.yearButton');
    ybuttons.forEach((button) => button.classList.remove('active'));


    if (!query) {
        alert("Please enter a search term."); // Optional: Alert if search input is empty
        return;
    }

    try {
        // Make a fetch request to the search endpoint
        const response = await fetch(`/courses/search?query=${encodeURIComponent(query)}`);

        if (!response.ok) {
            throw new Error("Failed to fetch search results.");
        }

        // Parse the JSON response
        const courses = await response.json();
        console.log('Search Results:', courses); // Debugging: Log search results

        // Populate the table with the search results
        const tableBody = document.querySelector("#courseTable tbody");
        if (courses.length > 0) {
            tableBody.innerHTML = courses
                .map(
                    (course) => `
                    <tr>
                        <td>${course.course_code}</td>
                        <td>${course.title}</td>
                        <td>${course.unit}</td>
                        <td>${course.semester}</td>
                        <td>
                            <button onclick="editCourse(${course.id})">Edit</button>
                            <button onclick="deleteCourse(${course.id})">Delete</button>
                        </td>
                    </tr>`
                )
                .join("");
        } else {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5">No courses found for the search term "${query}".</td>
                </tr>`;
        }

        // Reset pagination since search bypasses program and year filtering
        totalRecords = courses.length;
        currentPage = 1;
        updatePaginationControls();
    } catch (error) {
        console.error('Error during search:', error); // Debugging: Log any errors
        alert("An error occurred while searching for courses. Please try again later.");
    }
};


const filterYear = (year) => {
    if (currentProgramId) {
        loadCoursesByProgramAndYear(currentProgramId, year);
    }
    const buttons = document.querySelectorAll('.yearButton');
    buttons.forEach((button) => button.classList.remove('active'));

    // Add 'active' class to the clicked button
    const activeButton = document.getElementById(`year${year}`);
    activeButton.classList.add('active');

};


let programs = []; // Global variable to store program data

const loadPrograms = async () => {
    try {
        const response = await fetch('/program/list'); // Replace with your API endpoint
        if (!response.ok) {
            throw new Error('Failed to fetch programs');
        }

        programs = await response.json(); // Store fetched programs globally

        // Render program buttons dynamically
        const programList = document.getElementById('programList');
        programList.innerHTML = programs
            .map(
                (program) => `
                <li class="programItem" id="program${program.id}" onclick="selectProgram(${program.id})">
                    ${program.program_code}
                </li>
                `
            )
            .join('');

        // Set default program
        const defaultProgram = programs.find((program) => program.program_code === 'BSIT');
        if (defaultProgram) {
            selectProgram(defaultProgram.id); // Set BSIT as default
        }
    } catch (error) {
        console.error('Error fetching programs:', error);
    }
};

const selectProgram = (programId) => {
    // Remove 'active' class from all program items
    const programItems = document.querySelectorAll('.programItem');
    programItems.forEach((item) => item.classList.remove('active'));

    // Add 'active' class to the selected program item
    const activeProgram = document.getElementById(`program${programId}`);
    if (activeProgram) {
        activeProgram.classList.add('active');
    }

    // Find the program by ID from the global programs list
    const selectedProgram = programs.find((program) => program.id === programId);
    if (selectedProgram) {
        // Update the header with the full program title
        // const programTitle = `${selectedProgram.program_code} - ${selectedProgram.title}`;
        const programTitle = `${selectedProgram.title}`;
        document.getElementById('selectedProgram').textContent = programTitle;
    }

    // Load courses for the selected program and default year
    loadCoursesByProgramAndYear(programId, 1);
};

// Load programs on page load
document.addEventListener('DOMContentLoaded', loadPrograms);



const addCourseModal = document.getElementById("addCourseModal");

// Open the Add Course modal
document.getElementById("addCourseButton").onclick = () => {
if (!currentProgramId || !currentYear) {
    alert("Please select a program and year first.");
    return;
}

// Pre-fill hidden fields
document.getElementById("addProgramId").value = currentProgramId;
document.getElementById("addYear").value = currentYear;

addCourseModal.style.display = "block";
};

// Close Add Course modal
const closeAddCourseModal = () => {
addCourseModal.style.display = "none";
};

// Handle Add Course form submission
document.getElementById("addCourseForm").onsubmit = async (event) => {
event.preventDefault();
const formData = new FormData(event.target);

// Send data to backend
await fetch("/course", {
    method: "POST",
    body: formData,
});

closeAddCourseModal();
loadCoursesByProgramAndYear(currentProgramId, currentYear);
};


const editCourseModal = document.getElementById("editCourseModal");

// Open the Edit Course modal
const editCourse = async (courseId) => {
    try {
        // Fetch course data
        const response = await fetch(`/course/${courseId}`);
        if (!response.ok) {
            throw new Error('Failed to fetch course data');
        }

        const course = await response.json();
        if (!course) {
            alert('Course not found');
            return;
        }

        // Populate the modal fields
        document.getElementById('editCourseId').value = course.id;
        document.getElementById('editCourseCode').value = course.course_code;
        document.getElementById('editCourseTitle').value = course.title;
        document.getElementById('editCourseUnit').value = course.unit;
        document.getElementById('editCourseSemester').value = course.semester;
        document.getElementById('editCourseYear').value = course.year;

        // Show the modal
        document.getElementById('editCourseModal').style.display = 'block';
    } catch (error) {
        console.error('Error fetching course data:', error);
        alert('An error occurred while fetching course data.');
    }
};

// Close Edit Course modal
const closeEditCourseModal = () => {
editCourseModal.style.display = "none";
};

// Handle Edit Course form submission
document.getElementById("editCourseForm").onsubmit = async (event) => {
event.preventDefault();
const formData = new FormData(event.target);

// Send updated data to backend
await fetch(`/course/update/${formData.get("id")}`, {
    method: "POST",
    body: formData,
});

closeEditCourseModal();
loadCoursesByProgramAndYear(currentProgramId, currentYear);
};


const deleteCourse = async (id) => {
    if (confirm("Are you sure you want to delete this course?")) {
        await fetch(`/course/delete/${id}`, { method: "DELETE" });
        loadCoursesByProgramAndYear(currentProgramId, currentYear);
    }
}; 

document.addEventListener("DOMContentLoaded", () => {
    // Select the first program item and year button by default
    const firstProgramItem = document.querySelector(".programItem");
    const firstYearButton = document.getElementById("year1");

    if (firstProgramItem) {
        firstProgramItem.classList.add("active");
        const programId = firstProgramItem.id.replace("program", ""); // Extract program ID from ID attribute
        currentProgramId = parseInt(programId, 10); // Set current program ID
    }

    if (firstYearButton) {
        firstYearButton.classList.add("active");
        currentYear = 1; // Set current year
    }

    // Load courses for the first program and first year
    if (currentProgramId && currentYear) {
        loadCoursesByProgramAndYear(currentProgramId, currentYear);
    }
});



