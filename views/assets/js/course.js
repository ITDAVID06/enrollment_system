let currentProgramId = null;
let currentYear = 1;

const loadCoursesByProgramAndYear = async (programId, year) => {
    currentProgramId = programId;
    currentYear = year;

    const response = await fetch(`/courses/${programId}/${year}`);
    const courses = await response.json();

    const tableBody = document.querySelector("#courseTable tbody");
    tableBody.innerHTML = courses.map(course => `
        <tr>
            <td>${course.course_code}</td>
            <td>${course.title}</td>
            <td>${course.unit}</td>
            <td>${course.semester}</td>
            <td>
                <button onclick="editCourse(${course.id})">Edit</button>
                <button onclick="deleteCourse(${course.id})">Delete</button>
            </td>
        </tr>
    `).join("");
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

    // Call your logic to filter courses based on the selected year
    loadCoursesByYear(year);
};


const selectProgram = (programId) => {
    // Remove 'active' class from all program items
    const programItems = document.querySelectorAll('.programItem');
    programItems.forEach((item) => item.classList.remove('active'));

    // Add 'active' class to the selected program item
    const activeProgram = document.getElementById(`program${programId}`);
    activeProgram.classList.add('active');

    // Call your logic to load courses for the selected program
    loadCoursesByProgramAndYear(programId, 1); // Replace with your actual function
}

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
// Fetch course details from backend
const response = await fetch(`/course/${courseId}`);
const course = await response.json();

// Pre-fill the form with course data
document.getElementById("editCourseId").value = course.id;
document.getElementById("editCourseCode").value = course.course_code;
document.getElementById("editCourseTitle").value = course.title;
document.getElementById("editCourseUnit").value = course.unit;
document.getElementById("editCourseSemester").value = course.semester;
document.getElementById("editCourseYear").value = course.year;

editCourseModal.style.display = "block";
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



loadCoursesByProgramAndYear(1, 1); // Default load


