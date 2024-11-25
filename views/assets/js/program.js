const addProgramModal = document.getElementById("addProgramModal");
const editProgramModal = document.getElementById("editProgramModal");

document.getElementById("addProgramButton").onclick = () => addProgramModal.style.display = "block";

document.querySelectorAll(".close").forEach(btn => {
    btn.onclick = () => {
        addProgramModal.style.display = "none";
        editProgramModal.style.display = "none";
    };
});

window.onclick = (event) => {
    if (event.target === addProgramModal || event.target === editProgramModal) {
        addProgramModal.style.display = "none";
        editProgramModal.style.display = "none";
    }
};

const loadPrograms = async () => {
    const response = await fetch("/program/list");
    const programs = await response.json();

    const tableBody = document.querySelector("#programTable tbody");
    tableBody.innerHTML = programs.map(prog => `
        <tr>
            <td>${prog.id}</td>
            <td>${prog.program_code}</td>
            <td>${prog.title}</td>
            <td>${prog.years}</td>
            <td>
                <button onclick="openEditProgramModal(${prog.id})">Edit</button>
                <button onclick="deleteProgram(${prog.id})">Delete</button>
            </td>
        </tr>
    `).join("");
};

const openEditProgramModal = async (id) => {
    const response = await fetch(`/program/${id}`);
    const program = await response.json();

    document.getElementById("editProgramId").value = program.id;
    document.getElementById("editProgramCode").value = program.program_code;
    document.getElementById("editTitle").value = program.title;
    document.getElementById("editYears").value = program.years;

    editProgramModal.style.display = "block";
};

document.getElementById("addProgramForm").onsubmit = async (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);

    await fetch("/program", {
        method: "POST",
        body: formData
    });

    addProgramModal.style.display = "none";
    loadPrograms();
};

document.getElementById("editProgramForm").onsubmit = async (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);

    await fetch(`/program/update/${formData.get("id")}`, {
        method: "POST",
        body: formData
    });

    editProgramModal.style.display = "none";
    loadPrograms();
};

const deleteProgram = async (id) => {
    if (confirm("Are you sure you want to delete this program?")) {
        await fetch(`/program/${id}`, { method: "DELETE" });
        loadPrograms();
    }
};

loadPrograms();