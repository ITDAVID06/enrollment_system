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

    // Search functionality
    document.getElementById("searchButton").onclick = () => {
        const searchQuery = document.getElementById("searchInput").value;
        currentPage = 1;
        loadEnrollees(searchQuery);
    };

    // View enrollee details
    window.viewEnrollee = async (id) => {
        try {
            const response = await fetch(`/enrollee/${id}`);
            if (!response.ok) throw new Error("Failed to fetch enrollee details.");

            const enrollee = await response.json();
            document.getElementById("editId").value = enrollee.id;
            document.getElementById("editLastName").value = enrollee.last_name;
            document.getElementById("editFirstName").value = enrollee.first_name;
            document.getElementById("editEmail").value = enrollee.email;
            document.getElementById("editMobile").value = enrollee.contact_mobile;

            document.getElementById("editEnrolleeModal").style.display = "block";
        } catch (error) {
            console.error("Error fetching enrollee details:", error);
            alert("Failed to fetch enrollee details.");
        }
    };


    // Open enroll modal
    window.enrollEnrollee = async (id) => {
        try {
            const response = await fetch(`/enrollee/${id}`);
            if (!response.ok) throw new Error("Failed to fetch enrollee details.");

            const enrollee = await response.json();

            // Populate modal fields
            document.getElementById("enrollId").value = enrollee.id;
            await populateSections();

            // Show modal
            enrollModal.style.display = "block";
        } catch (error) {
            console.error("Error opening enroll modal:", error);
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

    // Handle enroll form submission
    document.getElementById("enrollForm").onsubmit = async (event) => {
        event.preventDefault();

        const formData = new FormData(event.target);
        const studentId = formData.get("student_id");
        const sectionId = formData.get("section_id");

        try {
            const response = await fetch(`/enrollee/enroll/${formData.get("id")}`, {
                method: "POST",
                body: formData,
            });
            const result = await response.json();

            if (response.ok && result.success) {
                alert(result.message);
                usedStudentIds.add(studentId); // Mark the student ID as used
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
    window.deleteEnrollee = async (id) => {
        if (confirm("Are you sure you want to delete this enrollee?")) {
            try {
                const response = await fetch(`/enrollee/delete/${id}`, { method: "DELETE" });
                if (!response.ok) throw new Error("Failed to delete enrollee.");

                alert("Enrollee successfully deleted!");
                loadEnrollees();
            } catch (error) {
                console.error("Error deleting enrollee:", error);
                alert("Failed to delete enrollee.");
            }
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
