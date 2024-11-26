  
// Load all sections on page load
const loadSections = async () => {
    try {
        const response = await fetch('/api/sections'); // Use your API route
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
                    <button onclick="editSection(${section.id})">Edit</button>
                    <button onclick="deleteSection(${section.id})">Delete</button>
                    <button onclick="loadCourses(${section.id})">Load Courses</button>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        console.error('Error fetching sections:', error);
    }
};

// Function for "Load Courses" button
const loadCourses = async (sectionId) => {
    const coursesTable = document.getElementById('coursesTable');
    const tableBody = coursesTable.querySelector('tbody');

    try {
        const response = await fetch(`/sections/courses/${sectionId}`);
        if (!response.ok) throw new Error('Failed to fetch courses');

        const courses = await response.json();
        console.log('Courses data:', courses);
        

        if (Array.isArray(courses) && courses.length > 0) {

            const groupedCourses = groupCoursesByCode(courses);
            console.log(groupedCourses);
            
            // Populate courses table
            tableBody.innerHTML = groupedCourses.map(course => `
                <tr>
                    <td>${course.course_code}</td>
                    <td>${course.Monday || ''}</td>
                    <td>${course.Tuesday || ''}</td>
                    <td>${course.Wednesday || ''}</td>
                    <td>${course.Thursday || ''}</td>
                    <td>${course.Friday || ''}</td>
                    <td><button onclick="updateSchedule(${course.course_id}, ${course.program_id}, ${sectionId})">Edit</button></td> 
                </tr>
            `).join('');
        
            coursesTable.style.display = 'table';
        } else {
             // Fetch courses by program_id and year_level if no schedules
             const responseAllCourses = await fetch(`/get-courses/${sectionId}`);
             if (!responseAllCourses.ok) throw new Error('Failed to fetch courses by program and year');
            
             const allCourses = await responseAllCourses.json();

             console.log(allCourses);

             tableBody.innerHTML = allCourses.map(course => `
                 <tr>
                     <td>${course.course_code}</td>
                     <td></td>
                     <td></td>
                     <td></td>
                     <td></td>
                     <td></td>
                     <td><button onclick="editSchedule(${course.course_id}, ${course.program_id}, ${sectionId})">Add</button></td>
                 </tr>
             `).join('') || `<tr><td colspan="7">No courses available.</td></tr>`;
             coursesTable.style.display = 'table';
         }
        
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
};

const groupCoursesByCode = (courses) => {
    const grouped = {};

    courses.forEach(course => {
        if (!grouped[course.course_code]) {
            grouped[course.course_code] = {
                course_code: course.course_code,
                course_id: course.course_id, // Ensure you have course_id
                program_id: course.program_id, // Ensure you have program_id
                schedID: course.schedID,
                sched_semester: course.sched_semester,
                sched_sy: course.sched_sy,
                sched_room: course.sched_room,
                Monday: '',
                Tuesday: '',
                Wednesday: '',
                Thursday: '',
                Friday: ''
            };
        }

        // Add schedule for the respective day
        if (course.sched_day && course.TIME_FROM && course.TIME_TO) {
            grouped[course.course_code][course.sched_day] = `${course.TIME_FROM} - ${course.TIME_TO}`;
        }
    });

    return Object.values(grouped); // Convert the object back to an array
};

loadSections();




// Open the Add Section modal
const addSectionModal = document.getElementById('addSectionModal');
document.getElementById('addSectionButton').onclick = () => {
    loadPrograms(); // Populate program dropdown
    addSectionModal.style.display = 'block';
};

const closeAddSectionModal = () => {
    addSectionModal.style.display = 'none';
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

// Add a section
document.getElementById('addSectionForm').onsubmit = async (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);

    await fetch('/section', {
        method: 'POST',
        body: formData
    });

    closeAddSectionModal();
    loadSections();
};

// Edit a section
const editSection = async (sectionId) => {
    const response = await fetch(`/section/${sectionId}`);
    const section = await response.json();

    // Populate the Add Section form for editing
    document.getElementById('program').value = section.program_id;
    document.getElementById('semester').value = section.semester;
    document.getElementById('yearLevel').value = section.year_level;
    document.getElementById('sectionName').value = section.name;

    addSectionModal.style.display = 'block';
};

// Delete a section
const deleteSection = async (sectionId) => {
    if (confirm('Are you sure you want to delete this section?')) {
        await fetch(`/section/${sectionId}`, { method: 'DELETE' });
        loadSections();
    }
};

// Add Schedule Function
const editSchedule = async (courseId, programId, sectionId) => {
    try {
        console.log('editSchedule called with courseId:', courseId, 'programId:', programId, 'sectionId', sectionId);

        // Populate modal fields
        document.getElementById('courseId').value = courseId;
        document.getElementById('programId').value = programId;
        document.getElementById('sectionId').value = sectionId;
        document.getElementById('displayCourseId').innerText = courseId;
        document.getElementById('displaySectionId').innerText = sectionId;
        document.getElementById('displayProgramId').innerText = programId;
        document.getElementById('editScheduleModal').style.display = 'block';
    } catch (error) {
        console.error('Error loading schedule:', error);
        alert('Failed to load schedule. Please try again.');
    }
};


document.getElementById("updateScheduleForm").onsubmit = async (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);
    const courseID = formData.get("updatecourseId");

    console.log(formData);
    console.log("Form data being submitted:", Object.fromEntries(formData.entries()));

    const response = await fetch(`/schedule/update/${courseID}`, {
        method: "POST",
        body: formData.entries,
    });

    const result = await response.json();

    if (response.ok) {
        alert("Schedule updated successfully!");
        document.getElementById("updateScheduleModal").style.display = "none";
        // loadSchedules(); // Refresh the schedule list
    } else {
        alert(result.message || "Failed to update schedule.");
    }
};



// Add event listeners to enable/disable time inputs based on checkbox
const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
days.forEach(day => {
    const checkbox = document.getElementById(`${day.toLowerCase()}_enabled`);
    const startTimeInput = document.getElementById(`${day.toLowerCase()}_start`);
    const endTimeInput = document.getElementById(`${day.toLowerCase()}_end`);

    checkbox.addEventListener('change', function() {
        const isEnabled = this.checked;
        startTimeInput.disabled = !isEnabled;
        endTimeInput.disabled = !isEnabled;
    });
});

const closeEditScheduleModal = () => {
    document.getElementById('editScheduleModal').style.display = 'none';
};

const closeupdateScheduleModal = () => {
    document.getElementById('updateScheduleModal').style.display = 'none';
};


// Open Update Schedule Modal
const updateSchedule = async (courseId, programId, sectionId) => {
    console.log('updateSchedule called with courseId:', courseId, 'programId:', programId, 'sectionId:', sectionId);
    const response = await fetch(`/get-schedule/${courseId}/${sectionId}`);
    const schedule = await response.json();

    if (!Array.isArray(schedule) || schedule.length === 0) {
        console.error("Schedule data is undefined, null, or empty.");
        return;
    }

    // Populate general fields (use the first item as a reference for shared fields)
    const firstSched = schedule[0];
    document.getElementById('updatecourseId').value = courseId;
    document.getElementById('updateprogramId').value = programId;
    document.getElementById('updatesectionId').value = sectionId;
    document.getElementById('updatedisplayProgramId').innerText = programId;
    document.getElementById('updatedisplayCourseId').innerText = courseId;
    document.getElementById('updatedisplaySectionId').innerText = sectionId;

    // Semester mapping
    const semesterMapping = {
        "1st Sem": "1st Sem",
        "2nd Sem": "2nd Sem"
    };
    document.getElementById("updatesemester").value = semesterMapping[firstSched.sched_semester] || '';

    // Populate school year and room
    document.getElementById("updateschoolYear").value = firstSched.sched_sy || '';
    document.getElementById("updateroom").value = firstSched.sched_room || '';

    // Time conversion for input type="time"
    const convertTo24Hour = (time) => {
        const [hours, minutes] = time.split(/[: ]/);
        const isPM = time.includes('PM');
        const convertedHours = isPM ? (parseInt(hours, 10) % 12) + 12 : parseInt(hours, 10) % 12;
        return `${convertedHours.toString().padStart(2, '0')}:${minutes}`;
    };

    // Handle days and times
    const days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
    days.forEach(day => {
        const dayLower = day.toLowerCase(); // Convert day to lowercase for IDs
        const dayEnabled = document.getElementById(`update${dayLower}_enabled`);
        const dayStart = document.getElementById(`update${dayLower}_start`);
        const dayEnd = document.getElementById(`update${dayLower}_end`);

        // Find schedule for the current day
        const daySchedule = schedule.find(sched => sched.sched_day === day);

        if (daySchedule) {
            // Populate the time fields if a schedule exists for this day
            dayEnabled.checked = true;
            dayStart.value = convertTo24Hour(daySchedule.TIME_FROM);
            dayEnd.value = convertTo24Hour(daySchedule.TIME_TO);
        } else {
            // Clear the fields if no schedule exists for this day
            dayEnabled.checked = false;
            dayStart.value = '';
            dayEnd.value = '';
        }

        // Enable or disable time inputs based on checkbox state
        const toggleTimeInputs = () => {
            const isEnabled = dayEnabled.checked;
            dayStart.disabled = !isEnabled;
            dayEnd.disabled = !isEnabled;
        };

        toggleTimeInputs();
        dayEnabled.addEventListener("change", toggleTimeInputs);
    });

    // Show modal
    document.getElementById("updateScheduleModal").style.display = "block";
};


// Handle Update Schedule Form Submission
document.getElementById("updateScheduleForm").onsubmit = async (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);
    const courseID = formData.get("updatecourseId");

    console.log(formData);
    console.log("Form data being submitted:", Object.fromEntries(formData.entries()));

    const response = await fetch(`/schedule/update/${courseID}`, {
        method: "POST",
        body: formData,
    });

    const result = await response.json();
    console.log(result);

    if (response.ok) {
        alert("Schedule updated successfully!");
        document.getElementById("updateScheduleModal").style.display = "none";
        // loadSchedules(); // Refresh the schedule list
    } else {
        alert(result.message || "Failed to update schedule.");
    }
};
