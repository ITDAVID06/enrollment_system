const addModal = document.getElementById("addFacultyModal");
const editModal = document.getElementById("editFacultyModal");

document.getElementById("addFacultyButton").onclick = () => addModal.style.display = "block";

document.querySelectorAll(".close").forEach(btn => {
    btn.onclick = () => {
        addModal.style.display = "none";
        editModal.style.display = "none";
    };
});

window.onclick = (event) => {
    if (event.target === addModal || event.target === editModal) {
        addModal.style.display = "none";
        editModal.style.display = "none";
    }
};

let currentPage = 1;
let recordsPerPage = 10;
let facultyData = [];

document.getElementById("searchInput").addEventListener("input", (event) => {
    const searchQuery = event.target.value.trim();
    currentPage = 1; // Reset to the first page
    loadFaculty(searchQuery);
});

const loadFaculty = async (searchQuery = "") => {
    const response = await fetch("/faculty/list");
    facultyData = await response.json();

    if (searchQuery) {
        facultyData = facultyData.filter(fac =>
            fac.lastname.toLowerCase().includes(searchQuery.toLowerCase()) ||
            fac.firstname.toLowerCase().includes(searchQuery.toLowerCase()) ||
            fac.contact.toLowerCase().includes(searchQuery.toLowerCase()) ||
            fac.email.toLowerCase().includes(searchQuery.toLowerCase()) ||
            fac.username.toLowerCase().includes(searchQuery.toLowerCase())
        );
    }

    displayPage();
};

// Display the current page
const displayPage = () => {
    const start = (currentPage - 1) * recordsPerPage;
    const end = start + recordsPerPage;
    const paginatedData = facultyData.slice(start, end);

    const tableBody = document.querySelector("#facultyTable tbody");
    tableBody.innerHTML = paginatedData.map(fac => `
        <tr>
            <td>${fac.id}</td>
            <td>${fac.lastname}</td>
            <td>${fac.firstname}</td>
            <td>${fac.contact}</td>
            <td>${fac.email}</td>
            <td>${fac.username}</td>
            <td>${fac.program_code}</td>
            <td>
                <button onclick="openEditModal(${fac.id})">
                <span class="material-symbols-rounded facultybutton">
                edit
                </span>
                </button>
                <button onclick="deleteFaculty(${fac.id})">
                <span class="material-symbols-rounded facultybutton">
                delete
                </span>
                </button>
            </td>
        </tr>
    `).join("");

    // Update record info and enable/disable navigation buttons
    document.getElementById("recordInfo").textContent = `Showing ${start + 1}-${Math.min(end, facultyData.length)} of ${facultyData.length} records`;
    document.getElementById("prevPageButton").disabled = currentPage === 1;
    document.getElementById("nextPageButton").disabled = end >= facultyData.length;
};

// Pagination button event handlers
document.getElementById("prevPageButton").onclick = () => {
    if (currentPage > 1) {
        currentPage--;
        displayPage();
    }
};

document.getElementById("nextPageButton").onclick = () => {
    if (currentPage * recordsPerPage < facultyData.length) {
        currentPage++;
        displayPage();
    }
};

// Initial load
loadFaculty();

// Handle search
document.getElementById("searchButton").onclick = () => {
    const searchQuery = document.getElementById("searchInput").value;
    currentPage = 1; // Reset to the first page
    loadFaculty(searchQuery);
};


// Handle Add Faculty Form submission
document.getElementById("addFacultyForm").onsubmit = async (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);
    await fetch("/faculty", {
        method: "POST",
        body: formData
    });
    addModal.style.display = "none";
    loadFaculty();
};

const programDropdown = document.getElementById("editProgramId");

// Load programs into the dropdown
const loadProgramsForDropdown = async (faculty = null) => {
    try {
        const response = await fetch("/program/list");
        const programs = await response.json();

        programDropdown.innerHTML = programs
            .map(program => `
                <option value="${program.id}" ${faculty?.program_id === program.id ? "selected" : ""}>
                    ${program.program_code}
                </option>
            `)
            .join("");
    } catch (error) {
        console.error("Error loading programs:", error);
        alert("Failed to load programs.");
    }
};

// Open Edit Modal
const openEditModal = async (id) => {
    try {
        const response = await fetch(`/faculty/${id}`);
        const faculty = await response.json();

        document.getElementById("editId").value = faculty.id;
        document.getElementById("editLastname").value = faculty.lastname;
        document.getElementById("editFirstname").value = faculty.firstname;
        document.getElementById("editContact").value = faculty.contact;
        document.getElementById("editEmail").value = faculty.email;
        document.getElementById("editUsername").value = faculty.username;

        // Load programs and set selected value
        await loadProgramsForDropdown(faculty);

        editModal.style.display = "block";
    } catch (error) {
        console.error("Error opening edit modal:", error);
        alert("Failed to open edit modal.");
    }
};



// Handle Edit Faculty Form submission
document.getElementById("editFacultyForm").onsubmit = async (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);
    console.log("Form data being submitted:", Object.fromEntries(formData.entries())); // Debugging

    const response = await fetch(`/faculty/update/${formData.get("id")}`, {
        method: "POST",
        body: formData,
    });

    const result = await response.json();

    if (response.ok) {
        alert("Faculty updated successfully!");
        editModal.style.display = "none";
        loadFaculty();
    } else {
        alert(result.message || "Failed to update faculty.");
    }
};

const deleteFaculty = async (id) => {
    // Open the custom confirm modal
    const customConfirmModal = document.getElementById("customConfirmModal");
    const confirmModalTitle = document.getElementById("confirmModalTitle");
    const confirmModalMessage = document.getElementById("confirmModalMessage");
    const confirmButton = document.getElementById("confirmDeleteButton");
    const cancelButton = document.getElementById("cancelDeleteButton");

    // Set modal content
    confirmModalTitle.textContent = "Delete Faculty";
    confirmModalMessage.textContent = "Are you sure you want to delete this faculty?";
    customConfirmModal.style.display = "block";

    // Add event listeners for the buttons
    confirmButton.onclick = async () => {
        try {
            await fetch(`/faculty/${id}`, { method: "DELETE" });
            alert("Faculty deleted successfully!");
            customConfirmModal.style.display = "none";

            // Reload the faculty list
            loadFaculty();
        } catch (error) {
            console.error("Error deleting faculty:", error);
            alert("Failed to delete faculty. Please try again.");
        }
    };

    cancelButton.onclick = () => {
        customConfirmModal.style.display = "none";
    };
};
