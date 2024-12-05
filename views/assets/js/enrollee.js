document.addEventListener("DOMContentLoaded", () => {
    let enrollees = [];
    let currentPage = 1;
    const recordsPerPage = 10;

    // Fetch all enrollees
    const loadEnrollees = async (searchQuery = "") => {
        try {
            const response = await fetch("/enrollee/list");
            if (!response.ok) throw new Error("Failed to fetch enrollees.");
            
            enrollees = await response.json();

            if (searchQuery) {
                enrollees = enrollees.filter(e =>
                    e.last_name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                    e.first_name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                    e.email.toLowerCase().includes(searchQuery.toLowerCase())
                );
            }

            displayPage();
        } catch (error) {
            console.error("Error loading enrollees:", error);
            alert("Failed to load enrollees.");
        }
    };

    // Render the current page
    const displayPage = () => {
        const start = (currentPage - 1) * recordsPerPage;
        const end = start + recordsPerPage;
        const paginatedData = enrollees.slice(start, end);
    
        const tableBody = document.querySelector("#enrolleeTable tbody");
    
        if (paginatedData.length === 0) {
            // If no enrollees, show a message
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; color: #555;">
                        No pending enrollees available.
                    </td>
                </tr>
            `;
        } else {
            // Render rows for enrollees
            tableBody.innerHTML = paginatedData.map(enrollee => `
                <tr>
                    <td>${enrollee.id}</td>
                    <td>${enrollee.last_name}</td>
                    <td>${enrollee.first_name}</td>
                    <td>${enrollee.email}</td>
                    <td>${enrollee.contact_mobile}</td>
                    <td>
                        <button class="btn btn-view" onclick="viewEnrollee(${enrollee.id})">
                <span class="material-symbols-rounded facultybutton">visibility</span>
            </button>
            <button class="btn btn-enroll" onclick="enrollEnrollee(${enrollee.id})">
                <span class="material-symbols-rounded facultybutton">person_add</span>
            </button>
            <button class="btn btn-delete" onclick="deleteEnrollee(${enrollee.id})">
                <span class="material-symbols-rounded facultybutton">delete</span>
            </button>
                    </td>
                </tr>
            `).join("");
        }
    
        // Update pagination info
        document.getElementById("recordInfo").textContent = `Showing ${start + 1}-${Math.min(end, enrollees.length)} of ${enrollees.length}`;
        document.getElementById("prevPageButton").disabled = currentPage === 1;
        document.getElementById("nextPageButton").disabled = end >= enrollees.length;
    };
    
    // Pagination handlers
    document.getElementById("prevPageButton").onclick = () => {
        if (currentPage > 1) {
            currentPage--;
            displayPage();
        }
    };

    document.getElementById("nextPageButton").onclick = () => {
        if (currentPage * recordsPerPage < enrollees.length) {
            currentPage++;
            displayPage();
        }
    };

    document.getElementById("searchInput").addEventListener("input", (event) => {
        const searchQuery = event.target.value.trim();
        currentPage = 1; // Reset to the first page
        loadEnrollees(searchQuery);
    });
    

    // View enrollee details
    window.viewEnrollee = async (id) => {
        try {
            const response = await fetch(`/enrollee/${id}`);
            if (!response.ok) throw new Error("Failed to fetch enrollee details.");

            const enrollee = await response.json();
            document.getElementById("editId").value = enrollee.id;
            document.getElementById("editLastName").value = document.getElementById("editLastName").value = (enrollee.last_name || "") + ", " + (enrollee.first_name || ""); 
            document.getElementById("editEmail").value = enrollee.email;
            document.getElementById("editMobile").value = enrollee.contact_mobile;
            document.getElementById("editYear").value = enrollee.year_level;
            document.getElementById("editProgram").value = enrollee.program;

            document.getElementById("editEnrolleeModal").style.display = "block";
        } catch (error) {
            console.error("Error fetching enrollee details:", error);
            alert("Failed to fetch enrollee details.");
        }
    };

    let students = []; // Global array to store student data

    // Function to check if an ID is already used
    function isIdUsed(id) {
        const existingIds = students.map(student => parseInt(student.student_id, 10)); // Map student objects to their IDs
        return existingIds.includes(id);
    }
    
    // Fetch existing students to ensure unique IDs
    const loadStudents = async () => {
        try {
            const response = await fetch("/student/list");
            if (!response.ok) throw new Error("Failed to fetch students.");
            students = await response.json(); // Store the fetched students
        } catch (error) {
            console.error("Error loading students:", error);
        }
    };
    
    // Function to generate a unique student ID
    const generateStudentId = () => {
        if (students.length === 0) {
            alert("Unable to generate ID. No student data available.");
            return;
        }
    
        // Start with the highest ID + 1
        let newId = Math.max(...students.map(student => parseInt(student.student_id, 10))) + 1;
    
        // Ensure the ID is unique
        while (isIdUsed(newId)) {
            newId++;
        }
    
        // Set the new unique ID in the input field
        document.getElementById("studentId").value = newId;
    };
    
    // Example usage when opening the enrollment modal
    window.enrollEnrollee = async (id) => {
        try {
            const response = await fetch(`/enrollee/${id}`);
            if (!response.ok) throw new Error("Failed to fetch enrollee details.");
    
            const enrollee = await response.json();
            document.getElementById("enrollId").value = enrollee.id;
    
            // Ensure students are loaded before generating an ID
            await loadStudents();
    
            // Generate a unique student ID
            generateStudentId();
    
            // Populate sections dropdown
            await populateSections();
    
            // Show the modal
            document.getElementById("enrollModal").style.display = "block";
        } catch (error) {
            console.error("Error opening enrollment modal:", error);
            alert("Failed to open enrollment modal.");
        }
    };
    

    // Populate sections in the dropdown
    const populateSections = async () => {
        try {
            const response = await fetch("/sections/all");
            if (!response.ok) throw new Error("Failed to fetch sections.");

            const sections = await response.json();
            const sectionDropdown = document.getElementById("sectionId");
            sectionDropdown.innerHTML = sections
                .map(section => `<option value="${section.id}">${section.name}</option>`)
                .join("");
        } catch (error) {
            console.error("Error populating sections:", error);
            alert("Failed to load sections.");
        }
    };

    document.getElementById("enrollForm").onsubmit = async (event) => {
        event.preventDefault();
    
        const formData = new FormData(event.target);
        const studentId = formData.get("student_id");
    
        try {
            const response = await fetch(`/enrollee/enroll/${formData.get("id")}`, {
                method: "POST",
                body: formData,
            });
    
            if (!response.ok) {
                console.error("Error Response:", await response.text());
                throw new Error("Failed to enroll enrollee. Check server response.");
            }
    
            const result = await response.json();
            console.log("Result:", result);
    
            if (result.success) {
                alert(result.message);
                enrollModal.style.display = "none";
                loadEnrollees();
            } else {
                alert(result.message || "Failed to enroll enrollee.");
            }
        } catch (error) {
            console.error("Error enrolling enrollee:", error);
            alert("Failed to enroll enrollee.");
        }
    };
    

    // Close modal
    document.getElementById("closeEnrollModal").onclick = () => {
        enrollModal.style.display = "none";
    };

    // Delete enrollee
    const customConfirmModal = document.getElementById("customConfirmModal");
    const confirmDeleteButton = document.getElementById("confirmDeleteButton");
    const cancelDeleteButton = document.getElementById("cancelDeleteButton");
    
    let enrolleeToDelete = null; // Store the enrollee ID temporarily
    
    window.deleteEnrollee = async (id) => {
        // Show the custom modal
        enrolleeToDelete = id;
        customConfirmModal.style.display = "flex";
    };
    
    // Handle the delete confirmation
    confirmDeleteButton.onclick = async () => {
        if (enrolleeToDelete !== null) {
            try {
                const response = await fetch(`/enrollee/delete/${enrolleeToDelete}`, { method: "DELETE" });
                if (!response.ok) throw new Error("Failed to delete enrollee.");
    
                alert("Enrollee successfully deleted!");
                loadEnrollees();
            } catch (error) {
                console.error("Error deleting enrollee:", error);
                alert("Failed to delete enrollee.");
            } finally {
                enrolleeToDelete = null;
                customConfirmModal.style.display = "none";
            }
        }
    };
    
    // Handle cancellation
    cancelDeleteButton.onclick = () => {
        enrolleeToDelete = null;
        customConfirmModal.style.display = "none";
    };
    
    // Close modal on outside click
    window.onclick = (event) => {
        if (event.target === customConfirmModal) {
            enrolleeToDelete = null;
            customConfirmModal.style.display = "none";
        }
    };
    

    // Handle edit form submission
    document.getElementById("editEnrolleeForm").onsubmit = async (event) => {
        event.preventDefault();

        const formData = new FormData(event.target);
        try {
            const response = await fetch(`/enrollee/update/${formData.get("id")}`, {
                method: "POST",
                body: formData,
            });
            if (!response.ok) throw new Error("Failed to update enrollee.");

            alert("Enrollee successfully updated!");
            document.getElementById("editEnrolleeModal").style.display = "none";
            loadEnrollees();
        } catch (error) {
            console.error("Error updating enrollee:", error);
            alert("Failed to update enrollee.");
        }
    };

    // Close modal
    document.getElementById("closeEditModal").onclick = () => {
        document.getElementById("editEnrolleeModal").style.display = "none";
    };

    // Initial load
    loadEnrollees();
});
