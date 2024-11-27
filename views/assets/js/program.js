document.addEventListener("DOMContentLoaded", () => {
    const addProgramModal = document.getElementById("addProgramModal");
    const editProgramModal = document.getElementById("editProgramModal");

    const addProgramButton = document.getElementById("addProgramButton");
    const addProgramForm = document.getElementById("addProgramForm");
    const editProgramForm = document.getElementById("editProgramForm");

    const searchInput = document.getElementById("searchInput");
    const searchButton = document.getElementById("searchButton");
    const prevPageButton = document.getElementById("prevPageButton");
    const nextPageButton = document.getElementById("nextPageButton");

    const recordInfo = document.getElementById("recordInfo");
    const programTableBody = document.querySelector("#programTable tbody");

    let programs = [];
    let currentPage = 1;
    const recordsPerPage = 10;

    // Open Add Program Modal
    addProgramButton.onclick = () => (addProgramModal.style.display = "block");

    // Close Modals
    document.querySelectorAll(".close").forEach((btn) => {
        btn.onclick = () => {
            addProgramModal.style.display = "none";
            editProgramModal.style.display = "none";
        };
    });

    // Close Modals When Clicking Outside
    window.onclick = (event) => {
        if (event.target === addProgramModal || event.target === editProgramModal) {
            addProgramModal.style.display = "none";
            editProgramModal.style.display = "none";
        }
    };

    // Fetch Programs and Display with Pagination
    const loadPrograms = async (searchQuery = "") => {
        const response = await fetch("/program/list");
        programs = await response.json();

        if (searchQuery) {
            programs = programs.filter(
                (prog) =>
                    prog.program_code.toLowerCase().includes(searchQuery.toLowerCase()) ||
                    prog.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
                    prog.years.toString().includes(searchQuery)
            );
        }

        displayPage();
    };

    const displayPage = () => {
        const start = (currentPage - 1) * recordsPerPage;
        const end = start + recordsPerPage;
        const paginatedData = programs.slice(start, end);

        programTableBody.innerHTML = paginatedData
            .map(
                (prog) => `
                <tr>
                    <td>${prog.id}</td>
                    <td>${prog.program_code}</td>
                    <td>${prog.title}</td>
                    <td>${prog.years}</td>
                    <td>
                        <button onclick="ProgramPage.openEditProgramModal(${prog.id})">
                            <span class="material-symbols-rounded programbutton">edit</span>
                        </button>
                        <button onclick="ProgramPage.deleteProgram(${prog.id})">
                            <span class="material-symbols-rounded programbutton">delete</span>
                        </button>
                    </td>
                </tr>
            `
            )
            .join("");

        updatePagination();
    };

    const updatePagination = () => {
        const totalRecords = programs.length;
        const start = (currentPage - 1) * recordsPerPage + 1;
        const end = Math.min(start + recordsPerPage - 1, totalRecords);

        recordInfo.textContent = `Showing ${start}-${end} of ${totalRecords} records`;

        prevPageButton.disabled = currentPage === 1;
        nextPageButton.disabled = end >= totalRecords;
    };

    // Search Handler
    searchInput.addEventListener("input", (event) => {
        const searchQuery = event.target.value.trim();
        currentPage = 1;
        loadPrograms(searchQuery);
    });

    searchButton.onclick = () => {
        const searchQuery = searchInput.value.trim();
        currentPage = 1;
        loadPrograms(searchQuery);
    };

    // Pagination Handlers
    prevPageButton.onclick = () => {
        if (currentPage > 1) {
            currentPage--;
            displayPage();
        }
    };

    nextPageButton.onclick = () => {
        if (currentPage * recordsPerPage < programs.length) {
            currentPage++;
            displayPage();
        }
    };

    // Open Edit Program Modal
    const openEditProgramModal = async (id) => {
        const response = await fetch(`/program/${id}`);
        const program = await response.json();

        document.getElementById("editProgramId").value = program.id;
        document.getElementById("editProgramCode").value = program.program_code;
        document.getElementById("editTitle").value = program.title;
        document.getElementById("editYears").value = program.years;

        editProgramModal.style.display = "block";
    };

    // Add Program Submission
    addProgramForm.onsubmit = async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);

        await fetch("/program", {
            method: "POST",
            body: formData,
        });

        addProgramModal.style.display = "none";
        loadPrograms();
    };

    // Edit Program Submission
    editProgramForm.onsubmit = async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);

        await fetch(`/program/update/${formData.get("id")}`, {
            method: "POST",
            body: formData,
        });

        editProgramModal.style.display = "none";
        loadPrograms();
    };

    // Delete Program
    const deleteProgram = async (id) => {
        if (confirm("Are you sure you want to delete this program?")) {
            await fetch(`/program/${id}`, { method: "DELETE" });
            loadPrograms();
        }
    };

    // Expose Public Methods
    window.ProgramPage = {
        openEditProgramModal,
        deleteProgram,
    };

    // Initial Load
    loadPrograms();
});
