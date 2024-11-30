const sectionAddModal = document.getElementById("addSectionModal");
const sectionEditModal = document.getElementById("editSectionModal");

document.getElementById("addSectionButton").onclick = () => {
    loadPrograms();
    sectionAddModal.style.display = "block";}

document.querySelectorAll(".close").forEach(btn => {
    btn.onclick = () => {
        sectionAddModal.style.display = "none";
        sectionEditModal.style.display = "none";
    };
});

window.onclick = (event) => {
    if (event.target === sectionAddModal || event.target === sectionEditModal) {
        sectionAddModal.style.display = "none";
        sectionEditModal.style.display = "none";
    }
};


// Load program options
const loadPrograms = async () => {
    const response = await fetch('/programs');
    const programs = await response.json();

    const programDropdown = document.getElementById('program');
    programDropdown.innerHTML = programs.map(program => `
        <option value="${program.id}">${program.program_code}</option>
    `).join('');
};


// Load all sections on page load
const loadSections = async () => {
    try {
        const response = await fetch('/sections/list'); // Use your API route
        if (!response.ok) throw new Error('Failed to fetch sections');

        const data = await response.json();

        // Render sections
        const tableBody = document.querySelector('#sectionTable tbody');
        tableBody.innerHTML = data.sections.map(section => `
            <tr>
                <td>${section.name}</td>
                <td>${section.program_code}</td>
                <td>${section.semester}</td>
                <td>${section.year_level}</td>
                <td>${section.course_count || 0}</td>
                <td>
                    <button onclick="openEditSectionModal(${section.id})">
                    <span class="material-symbols-rounded sectionButton">
                    edit
                    </span>
                    </button>
                    <button onclick="deleteSection(${section.id})">
                    <span class="material-symbols-rounded sectionButton">
                    delete
                    </span>
                    </button>
                    </td>
            </tr>
        `).join('');
    } catch (error) {
        console.error('Error fetching sections:', error);
    }
};

// Add Section
document.getElementById("addSectionForm").onsubmit = async (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);
    await fetch("/section", { method: "POST", body: formData });

    sectionAddModal.style.display = "none";
    loadSections();
};

// Edit Section
const openEditSectionModal = async (id) => {
    const response = await fetch(`/section/${id}`);
    const section = await response.json();

    document.getElementById("editSectionId").value = section.id;
    document.getElementById("editSectionName").value = section.name;
    document.getElementById("editProgram").value = section.program_id;
    document.getElementById("editSemester").value = section.semester;
    document.getElementById("editYearLevel").value = section.year_level;

    sectionEditModal.style.display = "block";
};

document.getElementById("editSectionForm").onsubmit = async (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);
    await fetch(`/section/update/${formData.get("id")}`, { method: "POST", body: formData });
    sectionEditModal.style.display = "none";
    loadSections();
};

// Delete Section
const deleteSection = async (id) => {
    if (confirm("Are you sure you want to delete this section?")) {
        await fetch(`/section/${id}`, { method: "DELETE" });
        loadSections();
    }
};

// Initial Load
loadSections();
