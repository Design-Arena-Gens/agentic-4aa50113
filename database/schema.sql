CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    role ENUM('admin','faculty','student','staff') NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    status ENUM('active','suspended') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    level VARCHAR(60) NOT NULL,
    duration_semesters TINYINT NOT NULL,
    department_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_program_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    code VARCHAR(20) NOT NULL,
    name VARCHAR(160) NOT NULL,
    credits TINYINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_course_code UNIQUE (code),
    CONSTRAINT fk_course_program FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS class_sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    faculty_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    schedule TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_section_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    CONSTRAINT fk_section_faculty FOREIGN KEY (faculty_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    section_id INT NOT NULL,
    status ENUM('pending','active','completed','cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_enrollment_student FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_enrollment_section FOREIGN KEY (section_id) REFERENCES class_sections(id) ON DELETE CASCADE,
    CONSTRAINT uq_enrollment UNIQUE (student_id, section_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS grades (
    enrollment_id INT PRIMARY KEY,
    grade DECIMAL(4,1) NOT NULL,
    obs TEXT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_grade_enrollment FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    audience ENUM('todos','student','faculty') NOT NULL DEFAULT 'todos',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (full_name, email, role, password_hash, status)
VALUES
    ('Administrador Geral', 'admin@iic-aeg.ac.mz', 'admin', '$2y$10$kH9JQ6k5vWck/dwZ0pniS.kf/6Fujh2VsxG9G9VvxLjYbH0osLOY2', 'active'), -- password: admin123
    ('Docente Exemplo', 'docente@iic-aeg.ac.mz', 'faculty', '$2y$10$O0uGfYzUKGwEuKXXSb9V..A36TObgGJE0E7E5Wdl66iRS0LlwM651', 'active'), -- password: docente123
    ('Estudante Exemplo', 'estudante@iic-aeg.ac.mz', 'student', '$2y$10$.Zjg5eQof8I4eAbOeXtfLOcOfeUeawuO/7dBDEuDfSU/EYEYVplpW', 'active') -- password: estudante123
ON DUPLICATE KEY UPDATE email = email;
