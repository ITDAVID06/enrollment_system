CREATE TABLE departments (
	id char(3) NOT NULL,
	name varchar(50) NOT NULL
	CONSTRAINT pk_departments PRIMARY KEY (id)
);

CREATE TABLE shifts (
    id int NOT NULL,
    start_time TIMESTAMP NOT NULL, 
    end_time TIMESTAMP NOT NULL,   
    CONSTRAINT pk_shifts PRIMARY KEY (id)
);

CREATE TABLE locations (
	id int(3) NOT NULL,
	name varchar(50) NOT NULL,
	CONSTRAINT pk_locations PRIMARY KEY (id)
);

CREATE TABLE user_roles (
	id int(3) NOT NULL,
	name varchar(20) NOT NULL,
	CONSTRAINT pk_user_roles PRIMARY KEY (id)
);

CREATE TABLE employees (
	id int(3) NOT NULL,
	name varchar(50) NOT NULL,
	email varchar(128) NOT NULL,
	gender char(3) NOT NULL,
	image varchar(128) NOT NULL,
	birth_date date NOT NULL,
	hire_date date NOT NULL,
	shift_id int(3) NOT NULL,
	dept_id char(3) NOT NULL,
	CONSTRAINT pk_employees PRIMARY KEY (id)
);

CREATE TABLE users (
	username char(6) NOT NULL,
	password varchar(128) NOT NULL,
	employee_id int(3) NOT NULL,
	role_id int(3) NOT NULL,
	CONSTRAINT pk_users PRIMARY KEY (username)
);

CREATE TABLE attendances (
	id int(3) NOT NULL,
	username char(6) NOT NULL,
	employee_id int(3) NOT NULL,
	department_id char(3) NOT NULL,
	shift_id int(3) NOT NULL,
	location_id int(3) NOT NULL,
	in_time int(11) NOT NULL,
	notes varchar(120) NOT NULL,
	image varchar(50) NOT NULL,
	lack_of varchar(11) NOT NULL,
	in_status varchar(15) NOT NULL,
	out_time int(11) NOT NULL,
	out_status varchar(15) NOT NULL,
	CONSTRAINT pk_attendances PRIMARY KEY (id)
);



