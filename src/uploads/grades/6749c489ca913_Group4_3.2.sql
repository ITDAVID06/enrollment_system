SELECT id, name, academic_session_id, department_id, online_id, password, seated_id, room_id, date_time_id
FROM courses
WHERE academic_session_id = 100;

SELECT student_id, course_id, exam_id, grade
FROM exam_results
WHERE grade > 95;

SELECT student_id, course_id, exam_id, grade
FROM exam_results
WHERE grade BETWEEN 65 AND 70;

SELECT id, firstname, lastname, registration_year, email, parent_id
FROM students
WHERE registration_year > '06/01/2012';

SELECT id, name, academic_session_id, department_id, online_id, seated_id, room_id, date_time_id
FROM courses
WHERE department_id IN (10,30);

SELECT id, firstname, lastname, registration_year, email, parent_id
FROM students
WHERE firstname LIKE 'J%';

SELECT student_id, course_id, grade
FROM student_course_details
WHERE course_id IN (190,193);

SELECT id, name, academic_session_id, department_id, online_id, password, seated_id, room_id, date_time_id
FROM courses
WHERE department_id = 30 AND academic_session_id = 200;

SELECT id, name, academic_session_id, department_id, online_id, seated_id, room_id, date_time_id
FROM courses
WHERE academic_session_id NOT IN (200,300);

SELECT id, name, academic_session_id, department_id, online_id, password, seated_id, room_id, date_time_id
FROM courses
WHERE department_id = 20;


