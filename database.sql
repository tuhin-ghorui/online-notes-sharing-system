-- ============================================================
-- NotesHub — Database Setup
-- Import this file via phpMyAdmin or run in MySQL terminal
-- ============================================================

CREATE DATABASE IF NOT EXISTS notes_sharing_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE notes_sharing_db;

-- ────────────────────────────────────────────────────────────
-- USERS
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    user_id    INT          NOT NULL AUTO_INCREMENT,
    name       VARCHAR(120) NOT NULL,
    email      VARCHAR(180) NOT NULL,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('admin','student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- SUBJECTS
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS subjects (
    subject_id   INT          NOT NULL AUTO_INCREMENT,
    subject_name VARCHAR(150) NOT NULL,
    PRIMARY KEY (subject_id),
    UNIQUE KEY uq_subject_name (subject_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- NOTES
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS notes (
    note_id     INT          NOT NULL AUTO_INCREMENT,
    user_id     INT          NOT NULL,
    subject_id  INT          NOT NULL,
    title       VARCHAR(255) NOT NULL,
    file_name   VARCHAR(255) NOT NULL,
    file_type   VARCHAR(20)  NOT NULL,
    uploaded_on TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (note_id),
    CONSTRAINT fk_notes_user    FOREIGN KEY (user_id)    REFERENCES users(user_id)    ON DELETE CASCADE,
    CONSTRAINT fk_notes_subject FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- SEED: Default admin account
-- Password: Admin@123  (change after first login!)
-- ────────────────────────────────────────────────────────────
INSERT IGNORE INTO users (name, email, password, role)
VALUES (
    'Admin',
    'admin@noteshub.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- Admin@123
    'admin'
);
