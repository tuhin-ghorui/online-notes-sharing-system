# 📚 Online Notes Sharing Platform - Complete Project Details

## 1. Project Overview
The Online Notes Sharing System is a PHP and MySQL-based web application designed to facilitate academic resource sharing. It empowers students to upload, organize, search, and download study materials categorized by subject. It incorporates a dual-panel architecture (Admin & Student) to ensure quality control, moderation, and efficient content management. Recently, it received a modern UI overhaul utilizing a premium glassmorphism design system for improved user experience.

## 2. Core Features

### 👩‍🎓 Student Module
- **Registration & Authentication:** Secure session-based login and registration.
- **Resource Contribution:** File uploads for varied formats including PDFs, Images, Word Documents, and PPTs.
- **Content Discovery:**
  - View notes organized by specific subjects.
  - Search functionality to find materials easily.
- **Resource Acquisition:** Download functionality for all uploaded materials.

### 🛡️ Admin Module
- **Platform Moderation:** Secure Admin Login.
- **Subject Lifecycle Management:** Full CRUD capabilities for adding, viewing, updating, and removing subjects from the curriculum list.
- **Content Moderation:** View all uploaded notes globally and delete inappropriate or irrelevant student uploads to maintain academic integrity.

## 3. Technology Stack & Architecture

- **Frontend:** HTML5, CSS3 (Modern Glassmorphism UI)
- **Backend Core:** PHP (Procedural/Session-based architecture)
- **Database Engine:** MySQL (`notes_sharing_db`)
- **Web Server:** Apache (via XAMPP or InfinityFree hosting)

### 📂 Directory Structure
```text
online_notes_sharing/
│
├── admin/               # Admin panel secure area
│ ├── dashboard.php      # Main admin overview
│ ├── manage_subjects.php# Subject CRUD operations
│ ├── manage_notes.php   # Global note moderation
│ └── view_note.php      # Display specific note details
│
├── auth/                # Authentication logic
│ ├── login.php          # User login
│ ├── register.php       # New student registration
│ └── logout.php         # Session termination
│
├── student/             # Student user area
│ ├── dashboard.php      # User homepage & stats
│ ├── upload_notes.php   # File upload handler
│ ├── view_notes.php     # Subject-filtered note browsing
│ └── view_note.php      # Display individual notes
│
├── assets/css/          # Styling resources
│ └── styles.css         # Modern UI framework
│
├── config/              # Core configuration
│ └── db.php             # Database connection strings
│
├── includes/            # Reusable UI fragments
│ └── student_nav.php    # Shared navigation layouts
│
├── uploads/             # Blob storage
│ └── notes/             # Physical file storage for notes
│
├── database.sql         # Main DB schema dump
├── project_analysis.md  # Detailed audit & vulnerabilities
├── README.md            # Quickstart documentation
└── index.php            # Base routing / Landing
```

## 4. Database Schema Breakdown (`notes_sharing_db`)

### Entities & Relationships
1. **`users` Table (Student & Admin profiles)**
   - **Fields:** `user_id` (PK), `name`, `email` (Unique), `password`, `role` (ENUM: 'admin', 'student'), `created_at`
   - **Purpose:** Stores authentication and profile data. The root admin is explicitly seeded.

2. **`subjects` Table (Curriculum Taxonomy)**
   - **Fields:** `subject_id` (PK), `subject_name` (Unique)
   - **Purpose:** Master list of valid subjects for note classification.

3. **`notes` Table (Resource Metadata)**
   - **Fields:** `note_id` (PK), `user_id` (FK), `subject_id` (FK), `title`, `file_name`, `file_type`, `uploaded_on`
   - **Constraints:** 
     - `FOREIGN KEY (user_id)` references `users` (`ON DELETE CASCADE`)
     - `FOREIGN KEY (subject_id)` references `subjects` (`ON DELETE RESTRICT`)
   - **Purpose:** Links physical files to subjects and their uploaders securely.

## 5. Security Posture & Known Vulnerabilities (Security Audit)
A recent critical analysis (`project_analysis.md`) identified technical debt and security concerns that serve as an immediate roadmap for platform hardening:

- **🔴 Critical Risks:**
  - **SQL Injection:** Exposed inputs in login flows requiring migration to PHP Prepared Statements.
  - **Cross-Site Scripting (XSS):** Unescaped outputs (`echo`) in `view_notes.php`, `manage_notes.php` requiring `htmlspecialchars()` sanitization.
  - **CSRF Vulnerabilities:** Lack of CSRF tokenization across forms and destructive GET requests (e.g., `?delete=id`).
  - **Path Traversal:** Unsanitized file path rendering in `view_note.php` requiring `basename()` constraints.
  - **Session Issues:** Missing `session_start()` directives before accessing session superglobals.

- **🟠 Functional / UX Debt:**
  - Lack of Post-Redirect-Get (PRG) patterns after forms to avoid re-submissions.
  - Overly permissive file naming. Standardize upload hashes to not leak user filenames via `uniqid()`.
  - Lack of empty-state rendering rules for Data Tables (Subjects, Notes lists).
  - Deleting subjects causing orphaned foreign keys.
  - Missing authorization checks on explicit routes (i.e. admin routes being accessed via absolute URLs by students).

## 6. Recent Developments
- **Modern UI Overhaul (March 2026):** The project frontend was entirely modernized. Adopted a premium glassmorphism styling paradigm, improved interactive visual layout, standardized navigation routing using `/includes` templates, and elevated user experience across the login interfaces, student portal, and admin administration panel.
