document.addEventListener("DOMContentLoaded", () => {
    const enrolleeTableBody = document.querySelector("#enrolleeTable tbody");
    const editEnrolleeModal = document.getElementById("editEnrolleeModal");
    const editEnrolleeForm = document.getElementById("editEnrolleeForm");
    const reviewInformationModal = document.getElementById("reviewInformationModal");

    let enrollees = [];

    // Close Modals
    document.querySelectorAll(".close").forEach((btn) => {
        btn.onclick = () => {
            editEnrolleeModal.style.display = "none";
        };
    });

    // Load enrollees from the server
    const loadEnrollees = async () => {
        try {
            const response = await fetch("/enrollees/list");
            const data = await response.json();

            if (Array.isArray(data)) {
                enrollees = data;
                displayEnrollees();
            } else {
                console.error("Expected an array but got:", data);
                enrolleeTableBody.innerHTML = "<tr><td colspan='6'>No data available</td></tr>";
            }
        } catch (error) {
            console.error("Error loading enrollees:", error);
            enrolleeTableBody.innerHTML = "<tr><td colspan='6'>Failed to load data</td></tr>";
        }
    };

    // Function to display enrollees in the table
    const displayEnrollees = () => {
        if (!enrollees || enrollees.length === 0) {
            enrolleeTableBody.innerHTML = "<tr><td colspan='6'>No enrollees found</td></tr>";
            return;
        }

        enrolleeTableBody.innerHTML = enrollees
            .map(
                (e) => `
                <tr>
                    <td>${e.id}</td>
                    <td>${e.name}</td>
                    <td>${e.program_applying_for || "Not Assigned"}</td>
                    <td>${e.year_level}</td>
                    <td>${e.student_status}</td>
                    <td>
                        <button onclick="openViewEnrolleeModal()">View</button>
                        <button onclick="openEditEnrolleeModal(${e.id})">Edit</button>
                    </td>
                </tr>
            `
            )
            .join("");
    };

    // Open the edit modal and populate fields
    window.openEditEnrolleeModal = async (id) => {
        const enrollee = enrollees.find((e) => e.id === id);

        if (!enrollee) {
            alert("Enrollee not found.");
            return;
        }

        document.getElementById("editEnrolleeId").value = enrollee.id;
        document.getElementById("editStudentStatus").value = enrollee.student_status;
        document.getElementById("editStudentId").value = enrollee.student_id || "";

        editEnrolleeModal.style.display = "block";
    };

    window.openViewEnrolleeModal = async () => {
      
        reviewInformationModal.style.display = "block";
    };



    // Handle the edit form submission
    editEnrolleeForm.onsubmit = async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);

        try {
            const response = await fetch(`/enrollees/update/${formData.get("id")}`, {
                method: "POST",
                body: formData,
            });
            const result = await response.json();

            if (result.success) {
                alert("Enrollee updated successfully!");
                loadEnrollees(); // Reload the list
            } else {
                alert("Failed to update enrollee: " + result.message);
            }
        } catch (error) {
            console.error("Error updating enrollee:", error);
            alert("Error updating enrollee.");
        }

        editEnrolleeModal.style.display = "none";
    };

    // Initial load of enrollees
    loadEnrollees();
});
