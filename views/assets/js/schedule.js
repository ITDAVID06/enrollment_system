let selectedSemester = '1st Sem'; // Default semester

function filterBySemester(semester) {
    selectedSemester = semester; // Update the selected semester
    console.log(`Selected Semester: ${selectedSemester}`);

    // Update the active button styles
    const buttons = document.querySelectorAll('.semester-buttons .btn');
    buttons.forEach((btn) => btn.classList.remove('active'));

    const activeButton = Array.from(buttons).find((btn) => btn.textContent === semester);
    if (activeButton) activeButton.classList.add('active');
    
    // Refresh the program-section filtering if one is selected
    const activeItem = document.querySelector('.programSectionItem.active');
    if (activeItem) {
        const [programId, sectionId] = activeItem.id.replace('programSection', '').split('-');
        filterByProgramSection(parseInt(programId), parseInt(sectionId));
    }
}

async function filterByProgramSection(programId, sectionId, yearLevel) {
    try {
        console.log(`Filtering by Program ID: ${programId}, Section ID: ${sectionId}, Year Level: ${yearLevel}, Semester: ${selectedSemester}`);

        // Fetch all courses for the selected Program, Year Level, and Semester
        const coursesResponse = await fetch(`/courses/${programId}/${yearLevel}?&semester=${encodeURIComponent(selectedSemester)}`);
        if (!coursesResponse.ok) {
            throw new Error('Failed to fetch courses');
        }
        const courses = await coursesResponse.json();
        console.log('Courses:', courses);

        // Fetch existing schedules for the selected Section
        const schedulesResponse = await fetch(`/sections/courses/${sectionId}`);
        if (!schedulesResponse.ok) {
            throw new Error('Failed to fetch schedules');
        }
        const schedules = await schedulesResponse.json();
        console.log('Schedules:', schedules);

        // Combine courses with schedules
        const combinedCourses = courses.map((course) => {
            const matchingSchedules = schedules.filter((sched) => sched.course_id === course.id);
            return {
                ...course,
                schedules: matchingSchedules, // Array of matching schedules for this course
            };
        });

        console.log('Combined Courses:', combinedCourses);

        // Update the table with the combined data
        const tableBody = document.querySelector('#scheduleTable tbody');
        tableBody.innerHTML = combinedCourses
            .map((course) => {
                const scheduleDays = {
                    Monday: '',
                    Tuesday: '',
                    Wednesday: '',
                    Thursday: '',
                    Friday: '',
                };

                // Populate the schedule for each day
                course.schedules.forEach((sched) => {
                    if (sched.sched_day && sched.TIME_FROM && sched.TIME_TO) {
                        const timeRange = `${sched.TIME_FROM} - ${sched.TIME_TO}`;
                        if (scheduleDays[sched.sched_day]) {
                            // Append if there's already a schedule for the day
                            scheduleDays[sched.sched_day] += `, ${timeRange}`;
                        } else {
                            scheduleDays[sched.sched_day] = timeRange;
                        }
                    }
                });

                return `
                <tr>
                    <td>${course.course_code}</td>
                    <td>${course.title}</td>
                    <td>${scheduleDays.Monday || ''}</td>
                    <td>${scheduleDays.Tuesday || ''}</td>
                    <td>${scheduleDays.Wednesday || ''}</td>
                    <td>${scheduleDays.Thursday || ''}</td>
                    <td>${scheduleDays.Friday || ''}</td>
                    <td>
                        ${
                            course.schedules.length > 0
                                ? `<button onclick="deleteSchedule(${course.id}, ${sectionId})"><span class="material-symbols-rounded facultybutton">
                                    delete
                                    </span></button>`
                                : `<button onclick="openAddScheduleModal(${course.program_id}, ${sectionId}, ${course.id})"><span class="material-symbols-rounded facultybutton">
                                    calendar_add_on
                                    </span></button>`
                        }
                    </td>
                </tr>`;
            })
            .join('');

    } catch (error) {
        console.error('Error fetching courses or schedules:', error);
        alert('An error occurred while fetching courses or schedules.');
    }
}


const groupSchedulesByCourse = (schedules) => {
    const grouped = {};

    schedules.forEach((schedule) => {
        if (!grouped[schedule.course_code]) {
            grouped[schedule.course_code] = {
                course_code: schedule.course_code,
                title: schedule.title || '',
                course_id: schedule.course_id,
                program_id: schedule.program_id,
                Monday: '',
                Tuesday: '',
                Wednesday: '',
                Thursday: '',
                Friday: ''
            };
        }

        // Assign the schedule to the respective day
        if (schedule.sched_day && schedule.TIME_FROM && schedule.TIME_TO) {
            const dayKey = schedule.sched_day; // e.g., "Monday"
            const timeRange = `${schedule.TIME_FROM} - ${schedule.TIME_TO}`;

            if (grouped[schedule.course_code][dayKey]) {
                // Append if there's already a schedule for the day
                grouped[schedule.course_code][dayKey] += `, ${timeRange}`;
            } else {
                grouped[schedule.course_code][dayKey] = timeRange;
            }
        }
    });

    return Object.values(grouped); // Convert the grouped object into an array
};

async function deleteSchedule(courseId, sectionId) {
    try {
        // Confirm the action
        const confirmation = confirm("Are you sure you want to delete all schedules for this course?");
        if (!confirmation) return;

        // Send DELETE request to the server
        const response = await fetch(`/schedule/${courseId}/${sectionId}`, {
            method: 'DELETE',
        });

        if (!response.ok) {
            throw new Error('Failed to delete schedules');
        }

        const result = await response.json();
        alert(result.message || 'Schedules deleted successfully!');

        // Refresh the table after deletion
        filterByProgramSection(null, sectionId);
    } catch (error) {
        console.error('Error deleting schedules:', error);
        alert('An error occurred while deleting schedules.');
    }
}

// Fetch and render the program-section list
async function loadProgramSections() {
    try {
        const response = await fetch('/schedule/program-sections');
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const programSections = await response.json();
        console.log('Program Sections:', programSections);

        const list = document.getElementById('programSectionList');
        list.innerHTML = programSections
            .map(
                (item) => `
                <li class="programSectionItem" 
                    id="programSection${item.program_id}-${item.section_id}" 
                    data-section-id="${item.section_id}" 
                    data-program-id="${item.program_id}" 
                    data-year-level="${item.year}" 
                    onclick="selectProgramSection(${item.program_id}, ${item.section_id}, '${item.year}')">
                    ${item.program_code}-${item.section_name} (Year ${item.year})
                </li>`
            )
            .join('');
    } catch (error) {
        console.error('Error loading program sections:', error);
        alert('Failed to load program sections.');
    }
}

function selectProgramSection(programId, sectionId, yearLevel) {
    console.log(`Selected Program ID: ${programId}, Section ID: ${sectionId}, Year Level: ${yearLevel}`);

    // Highlight the active section
    document.querySelectorAll('.programSectionItem').forEach((item) => {
        item.classList.remove('active');
    });

    const activeItem = document.querySelector(`#programSection${programId}-${sectionId}`);
    if (activeItem) {
        activeItem.classList.add('active');
    }

    // Call filterByProgramSection with the selected program, section, and year level
    filterByProgramSection(programId, sectionId, yearLevel);
}

// Initialize the page
document.addEventListener('DOMContentLoaded', loadProgramSections);

function openAddScheduleModal(programId, sectionId, courseId) {

    console.log('AddSchedule called with courseId:', courseId, 'programId:', programId, 'sectionId', sectionId);
    // Populate hidden fields with programId and sectionId
    document.getElementById('programId').value = programId;
    document.getElementById('courseId').value = courseId;
    document.getElementById('sectionId').value = sectionId;
    

    // Optionally display the program and section info
    document.getElementById('displayProgramId').textContent = programId;
    document.getElementById('displaySectionId').textContent = sectionId;
    document.getElementById('displayCourseId').textContent = courseId;

    // Clear any previous schedule inputs
    document.querySelectorAll('#editScheduleForm input[type="time"]').forEach((input) => {
        input.value = '';
        input.disabled = true;
    });
    document.querySelectorAll('#editScheduleForm input[type="checkbox"]').forEach((checkbox) => {
        checkbox.checked = false;
    });

    // Open the modal
    document.getElementById('editScheduleModal').style.display = 'block';
}

function closeEditScheduleModal() {
    document.getElementById('editScheduleModal').style.display = 'none';
}

document.querySelectorAll('#editScheduleForm input[type="checkbox"]').forEach((checkbox) => {
    checkbox.addEventListener('change', (event) => {
        const day = event.target.id.split('_')[0]; // Extract day (e.g., "monday" from "monday_enabled")
        const timeStart = document.getElementById(`${day}_start`);
        const timeEnd = document.getElementById(`${day}_end`);
        if (event.target.checked) {
            timeStart.disabled = false;
            timeEnd.disabled = false;
        } else {
            timeStart.value = '';
            timeEnd.value = '';
            timeStart.disabled = true;
            timeEnd.disabled = true;
        }
    });
});


