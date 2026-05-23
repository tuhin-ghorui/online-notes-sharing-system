# 📚 Online Notes Sharing System

A PHP & MySQL based web application that allows students to upload, share, view, and download academic notes subject-wise.  
The system also includes an Admin panel to manage subjects and moderate uploaded content.

---

## 🚀 Features

### 👩‍🎓 Student Module
- Student Registration & Login (Session-based)
- Upload Notes (PDF, Image, Word, PPT)
- View Notes by Subject
- Download Notes
- Search Notes by Subject

### 🛡️ Admin Module
- Secure Admin Login
- Add & Manage Subjects
- View All Uploaded Notes
- Delete Inappropriate or Irrelevant Notes

---

## 🧩 CRUD Operations Used

| Operation | Module | Description |
|----------|------|-------------|
| Create | Student / Admin | Register user, upload notes, add subjects |
| Read | Student / Admin | View notes, subjects, users |
| Update | Admin | Manage subjects |
| Delete | Admin | Delete inappropriate notes |

---

## 🛠️ Technologies Used

- **Frontend:** HTML, CSS  
- **Backend:** PHP  
- **Database:** MySQL  
- **Server:** Apache (XAMPP / InfinityFree)  

---

## 📂 Project Structure
online-notes-sharing-system/
│
├── admin/
│ ├── dashboard.php
│ ├── manage_subjects.php
│ ├── manage_notes.php
│ └── view_note.php
│
├── auth/
│ ├── login.php
│ ├── register.php
│ └── logout.php
│
├── student/
│ ├── dashboard.php
│ ├── upload_notes.php
│ ├── view_notes.php
│ └── view_note.php
│
├── assets/css/
│ └── styles.css
│
├── config/
│ └── db.php (ignored for security)
│
├── uploads/
│ └── notes/ (ignored)
│
├── index.php
├── .gitignore
└── README.md


---

## 🗄️ Database Setup

1. Create a MySQL database  
2. Import the provided SQL file  
3. Configure database credentials in `config/db.php`

```php
$host = "YOUR_DB_HOST";
$user = "YOUR_DB_USERNAME";
$pass = "YOUR_DB_PASSWORD";
$db   = "YOUR_DB_NAME";

🌍 Live Demo

🔗 Hosted on InfinityFree
https://onlinenotessharing.page.gd/auth/login.php