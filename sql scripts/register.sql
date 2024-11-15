-- Statuses Table
CREATE TABLE statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

-- Schools Table
CREATE TABLE schools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Programs Table
CREATE TABLE programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Year Levels Table
CREATE TABLE year_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level VARCHAR(50) NOT NULL
);

-- Terms Table
CREATE TABLE terms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

-- School Years Table
CREATE TABLE school_years (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year VARCHAR(9) NOT NULL
);

-- Enrollment Table
CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_id INT,
    school_id INT,
    program_id INT,
    year_level_id INT,
    term_id INT,
    school_year_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (status_id) REFERENCES statuses(id),
    FOREIGN KEY (school_id) REFERENCES schools(id),
    FOREIGN KEY (program_id) REFERENCES programs(id),
    FOREIGN KEY (year_level_id) REFERENCES year_levels(id),
    FOREIGN KEY (term_id) REFERENCES terms(id),
    FOREIGN KEY (school_year_id) REFERENCES school_years(id)
);

--DML

INSERT INTO statuses (name) VALUES
('New Student'),
('Old Student');


INSERT INTO schools (name) VALUES
('School A'),
('School B');
-- Add more schools as needed

INSERT INTO programs (name) VALUES
('BMMA - Bachelor of Multimedia Arts'),
('BSCS - Bachelor of Science in Computer Science'),
('BSIT - Bachelor of Science in Information Technology');

INSERT INTO year_levels (level) VALUES
('1st Year'),
('2nd Year'),
('3rd Year'),
('4th Year');

INSERT INTO terms (name) VALUES
('1st Term'),
('2nd Term'),
('3rd Term'),
('Summer');

INSERT INTO school_years (year) VALUES
('2025-2026');

INSERT INTO enrollments (status_id, school_id, program_id, year_level_id, term_id, school_year_id) 
VALUES (1, 1, 2, 3, 1, 1);


