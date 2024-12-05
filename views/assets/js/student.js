let students = [];
let currentPage = 1;
const recordsPerPage = 10;

const scheduleModal = document.getElementById("viewScheduleModal");
const editSectionModal = document.getElementById("editSectionModal");

// Fetch and display students
const loadStudents = async (searchQuery = "") => {
    try {
        const response = await fetch("/student/list");
        if (!response.ok) throw new Error("Failed to fetch students.");

        students = await response.json();

        if (searchQuery) {
            students = students.filter(student =>
                student.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                student.section_name.toLowerCase().includes(searchQuery.toLowerCase())
            );
        }

        displayPage();
    } catch (error) {
        console.error("Error loading students:", error);
        alert("Failed to load students.");
    }
};

// Render the current page
const displayPage = () => {
    const start = (currentPage - 1) * recordsPerPage;
    const end = start + recordsPerPage;
    const paginatedData = students.slice(start, end);

    const tableBody = document.querySelector("#studentTable tbody");
    tableBody.innerHTML = paginatedData
        .map(
            (student) => `
            <tr>
                <td>${student.id}</td>
                <td>${student.name}</td>
                <td>${student.gender}</td>
                <td>${student.section_name || "Unassigned"}</td>
                <td>${student.program_code}</td>
                <td>${student.year_level}</td>
                 <td>
                    ${student.email}
                    <button class="btn btn-copy" onclick="copyToClipboard('${student.email}')">
                        <span class="material-symbols-rounded">content_copy</span>
                    </button>
                </td>
                <td>${student.address}</td>
                <td>
                    <button class="btn btn-edit" onclick="editStudentSection(${student.id})">
                        <span class="material-symbols-rounded facultybutton">edit</span>
                    </button>
                    <button class="btn btn-view" onclick="viewSchedule('${student.program_id}', '${student.section_id}', '${student.section_name}', '${student.year_level}', '${student.semester}')">
                        <span class="material-symbols-rounded facultybutton">visibility</span>
                    </button>
                    <button class="btn btn-delete" onclick="deleteStudentSection(${student.id})">
                    <span class="material-symbols-rounded facultybutton">delete</span>
                    </button>
                </td>
            </tr>
        `
        )
        .join("");

    document.getElementById("recordInfo").textContent = `Showing ${start + 1}-${Math.min(
        end,
        students.length
    )} of ${students.length}`;
    document.getElementById("prevPageButton").disabled = currentPage === 1;
    document.getElementById("nextPageButton").disabled = end >= students.length;
};

window.editStudentSection = async (id) => {
    try {
        const response = await fetch(`/student/${id}`);
        if (!response.ok) throw new Error("Failed to fetch student details.");

        const student = await response.json();
        document.getElementById("editStudentId").value = student.id;

        // Populate dropdown and pre-select the current section
        await populateSections(student.section_id);

        // Display the modal
        document.getElementById("editSectionModal").style.display = "block";
    } catch (error) {
        console.error("Error fetching student details:", error);
        alert("Failed to fetch student details.");
    }
};

window.deleteStudentSection = async (id) => {
    if (confirm("Are you sure you want to remove this student from the section?")) {
        try {
            const response = await fetch(`/student/remove-section/${id}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ section_id: null, student_id: null}),
            });

            const result = await response.json();

            if (response.ok && result.success) {
                alert(result.message || "Section removed successfully!");
                loadStudents(); // Refresh the student list
            } else {
                alert(result.message || "Failed to remove section.");
            }
        } catch (error) {
            console.error("Error removing student section:", error);
            alert("Failed to remove student section.");
        }
    }
};

const customConfirmModal = document.getElementById("customConfirmModal");
const confirmButton = document.getElementById("confirmDeleteButton");
const cancelButton = document.getElementById("cancelDeleteButton");
const confirmModalTitle = document.getElementById("confirmModalTitle");
const confirmModalMessage = document.getElementById("confirmModalMessage");

let studentToDelete = null; // Store the student ID temporarily

window.deleteStudentSection = async (id) => {
    // Set the modal's title and message
    confirmModalTitle.textContent = "Confirm Deletion";
    confirmModalMessage.textContent = "Are you sure you want to remove this student from the section?";
    
    // Show the modal and store the student ID
    studentToDelete = id;
    customConfirmModal.style.display = "flex";
};

// Handle confirm button click
confirmButton.onclick = async () => {
    if (studentToDelete !== null) {
        try {
            const response = await fetch(`/student/remove-section/${studentToDelete}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ section_id: null, student_id: null }),
            });

            const result = await response.json();

            if (response.ok && result.success) {
                alert(result.message || "Section removed successfully!");
                loadStudents(); // Refresh the student list
            } else {
                alert(result.message || "Failed to remove section.");
            }
        } catch (error) {
            console.error("Error removing student section:", error);
            alert("Failed to remove student section.");
        } finally {
            studentToDelete = null;
            customConfirmModal.style.display = "none";
        }
    }
};

// Handle cancel button click
cancelButton.onclick = () => {
    studentToDelete = null;
    customConfirmModal.style.display = "none";
};

// Close modal when clicking outside it
window.onclick = (event) => {
    if (event.target === customConfirmModal) {
        studentToDelete = null;
        customConfirmModal.style.display = "none";
    }
};

const copyToClipboard = (email) => {
    navigator.clipboard
        .writeText(email)
        .then(() => {
            alert(`Copied to clipboard: ${email}`);
        })
        .catch((error) => {
            console.error("Failed to copy text: ", error);
            alert("Failed to copy email. Please try again.");
        });
};

// Pagination
document.getElementById("prevPageButton").onclick = () => {
    if (currentPage > 1) {
        currentPage--;
        displayPage();
    }
};

document.getElementById("nextPageButton").onclick = () => {
    if (currentPage * recordsPerPage < students.length) {
        currentPage++;
        displayPage();
    }
};

// Submit the updated section
document.getElementById("editSectionForm").onsubmit = async (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);

    try {
        const response = await fetch(`/student/update/${formData.get("id")}`, {
            method: "POST",
            body: formData,
        });

        const result = await response.json();

        if (result.success) {
            alert("Section updated successfully!");
            editSectionModal.style.display = "none";
            loadStudents(); // Refresh the student list
        } else {
            alert(result.message || "Failed to update section.");
        }
    } catch (error) {
        console.error("Error updating section:", error);
        alert("Failed to update section.");
    }
};

document.getElementById("closeEditSectionModal").onclick = () => {
    editSectionModal.style.display = "none";
};


window.viewSchedule = async (programId, sectionId, sectionName, yearLevel, semester) => {
    try {
        console.log(`Fetching schedule for Program ID: ${programId}, Section ID: ${sectionId}, Year Level: ${yearLevel}, Semester: ${semester}`);

        // Fetch courses for the section and semester
        const coursesResponse = await fetch(`/courses/${programId}/${yearLevel}?&semester=${encodeURIComponent(semester)}`);
        if (!coursesResponse.ok) throw new Error("Failed to fetch courses.");
        const courses = await coursesResponse.json();
        console.log("Courses:", courses);

        // Fetch schedules for the section
        const schedulesResponse = await fetch(`/sections/courses/${sectionId}`);
        if (!schedulesResponse.ok) throw new Error("Failed to fetch schedules.");
        const schedules = await schedulesResponse.json();
        console.log("Schedules:", schedules);

        // Combine courses and schedules
        const combinedData = courses.map((course) => {
            const courseSchedules = schedules.filter((schedule) => schedule.course_id === course.id);
            const scheduleDays = {
                Monday: "",
                Tuesday: "",
                Wednesday: "",
                Thursday: "",
                Friday: "",
            };

            // Populate schedule for each day
            courseSchedules.forEach((schedule) => {
                if (schedule.sched_day && schedule.TIME_FROM && schedule.TIME_TO) {
                    const timeRange = `${schedule.TIME_FROM} - ${schedule.TIME_TO}`;
                    scheduleDays[schedule.sched_day] = scheduleDays[schedule.sched_day]
                        ? `${scheduleDays[schedule.sched_day]}, ${timeRange}`
                        : timeRange;
                }
            });

            return {
                course_code: course.course_code,
                course_title: course.title,
                Monday: scheduleDays.Monday,
                Tuesday: scheduleDays.Tuesday,
                Wednesday: scheduleDays.Wednesday,
                Thursday: scheduleDays.Thursday,
                Friday: scheduleDays.Friday,
                room: courseSchedules[0]?.sched_room || "",
                semester: semester,
            };
        });

        console.log("Combined Data:", combinedData);

        // Populate the modal with the schedule
        document.getElementById("sectionName").textContent = `${sectionName} - ${semester}`;
        const tableBody = document.querySelector("#scheduleTable tbody");
        tableBody.innerHTML = combinedData
            .map(
                (row) => `
                <tr>
                    <td>${row.course_code}</td>
                    <td>${row.course_title}</td>
                    <td>${row.Monday}</td>
                    <td>${row.Tuesday}</td>
                    <td>${row.Wednesday}</td>
                    <td>${row.Thursday}</td>
                    <td>${row.Friday}</td>
                    <td>${row.room}</td>
                    <td>${row.semester}</td>
                </tr>
            `
            )
            .join("");

        // Show the modal
        scheduleModal.style.display = "block";
    } catch (error) {
        console.error("Error fetching schedule:", error);
        alert(error.message || "Failed to load schedule.");
    }
};


// Close the schedule modal
document.getElementById("closeScheduleModal").onclick = () => {
    scheduleModal.style.display = "none";
};


// Load initial students
loadStudents();

// Search functionality
document.getElementById("searchInput").addEventListener("input", (event) => {
    const searchQuery = event.target.value.trim();
    currentPage = 1; // Reset to the first page
    loadStudents(searchQuery);
});

document.getElementById("searchButton").onclick = () => {
    const searchQuery = document.getElementById("searchInput").value.trim();
    currentPage = 1;
    loadStudents(searchQuery);
};

// Populate sections dropdown
const populateSections = async (currentSectionId = null) => {
    try {
        const response = await fetch("/sections/all");
        if (!response.ok) throw new Error("Failed to fetch sections.");

        const sections = await response.json();
        const sectionDropdown = document.getElementById("editSectionId");

        // Populate dropdown with section options and include year_level
        sectionDropdown.innerHTML = sections
            .map(section => `
                <option value="${section.id}" data-year-level="${section.year}" ${currentSectionId == section.id ? "selected" : ""}>
                    ${section.name} (Year ${section.year})
                </option>
            `)
            .join("");
    } catch (error) {
        console.error("Error populating sections:", error);
        alert("Failed to load sections.");
    }
};


document.getElementById("printScheduleButton").addEventListener("click", () => {
    const modalContent = document.querySelector("#viewScheduleModal .modal-content").innerHTML;

    // Open a new window and write the modal content to it
    const printWindow = window.open("", "_blank");
    printWindow.document.write(`
        <html>
        <head>
            <title>Schedule</title>
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                th { background-color: #b30000; color: white; }
            </style>
        </head>
        <body>
            ${modalContent}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
});

document.getElementById("emailScheduleButton").addEventListener("click", async () => {
    const email = prompt("Enter the recipient's email address:");
    if (!email) return;

    const modalContent = document.querySelector("#viewScheduleModal .modal-content").innerHTML;

    try {
        const response = await fetch("/send-schedule-email", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ email: email, scheduleHTML: modalContent }),
        });

        const result = await response.json();

        if (response.ok && result.success) {
            alert("Schedule sent successfully!");
        } else {
            alert(result.message || "Failed to send schedule.");
        }
    } catch (error) {
        console.error("Error sending schedule:", error);
        alert("Failed to send schedule.");
    }
});


