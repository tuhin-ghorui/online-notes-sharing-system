# NotesHub — Full Project Analysis

## Overview

The project is a XAMPP-based PHP web app with student and admin roles. Files are spread across `auth/`, `admin/`, `student/`, and `config/`. Here is a complete list of issues found.

---

## 🔴 Critical — Security Vulnerabilities

### 1. No `session_start()` in [register.php](file:///c:/xampp/htdocs/online_notes_sharing/auth/register.php)
**File:** [auth/register.php](file:///c:/xampp/htdocs/online_notes_sharing/auth/register.php) — Line 2 (missing)

The file includes [db.php](file:///c:/xampp/htdocs/online_notes_sharing/config/db.php) but never calls `session_start()`. If you ever store flash messages in `$_SESSION` (e.g., after redirect), it would silently fail. It also means a logged-in user can still access the register page freely.

**Fix:** Add `session_start()` at the top, then redirect away if already logged in.

---

### 2. SQL Injection in [login.php](file:///c:/xampp/htdocs/online_notes_sharing/auth/login.php)
**File:** [auth/login.php](file:///c:/xampp/htdocs/online_notes_sharing/auth/login.php) — Line 11

```php
// VULNERABLE
$query = "SELECT * FROM users WHERE email='$email'";
```

`$email` uses `mysqli_real_escape_string`, which is a partial defence but **not sufficient** as a replacement for prepared statements.

**Fix:** Use a prepared statement:
```php
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
```

---

### 3. XSS in [view_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/student/view_notes.php) — Note title/name output unescaped
**File:** [student/view_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/student/view_notes.php) — Lines 77, 82–83

User-supplied data (`$row['title']`, `$row['name']`) is echoed directly into HTML without `htmlspecialchars()`.

```php
// VULNERABLE
echo "<h3>{$row['title']}</h3>";
echo "👤 {$row['name']}";
```

**Fix:** Wrap all user-controlled output with `htmlspecialchars()`.

---

### 4. XSS in [manage_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_notes.php) — Note title/name output unescaped
**File:** [admin/manage_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_notes.php) — Lines 83–86

Same issue as above — table cells echo raw `$row['title']`, `$row['name']`, etc.

---

### 5. XSS in [admin/view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/view_note.php) — Title output unescaped
**File:** [admin/view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/view_note.php) — Line 36

```php
// VULNERABLE
echo $note['title'];
```

**Fix:** Use `htmlspecialchars($note['title'])` as done in the student version.

---

### 6. Path Traversal / Direct File Access in [view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/view_note.php) (both admin & student)
**Files:** [admin/view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/view_note.php) line 25–26, [student/view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/student/view_note.php) lines 28–29, 55, 58, 64

The file path is built from the DB value and rendered directly into `src=` / `href=` attributes with no sanitization. If the `file_name` column were ever injected with `../../config/db.php`, it could leak files.

**Fix:** Use `basename()` when building the path:
```php
$file_path = "../uploads/notes/" . basename($note['file_name']);
```

---

### 7. No CSRF Protection Anywhere
Every form (`login`, `register`, `upload`, `add_subject`) and destructive GET action (`?delete=`) have **no CSRF token**. An attacker can trick a logged-in user into submitting forms or triggering deletes.

**Fix:** Generate a token on form load, store in session, and validate on submit.

---

### 8. Deletion via GET Request (CSRF-deletable)
**Files:** [admin/manage_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_notes.php) line 12, [admin/manage_subjects.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_subjects.php) line 20

Deletes are triggered with a plain `?delete=id` GET link. This is dangerous alongside the missing CSRF protection above.

**Fix:** Move deletes to POST forms with a CSRF token.

---

### 9. Error Reporting Left On in Production
**File:** [student/upload_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/student/upload_notes.php) — Lines 2–3

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

This exposes server paths and internal errors to users.

**Fix:** Remove these lines (or move to a dev-only config).

---

## 🟠 Medium — Functional Bugs

### 10. Logout Redirect Broken
**File:** [auth/logout.php](file:///c:/xampp/htdocs/online_notes_sharing/auth/logout.php) — Line 4

```php
header("Location: login.php");
```

[logout.php](file:///c:/xampp/htdocs/online_notes_sharing/auth/logout.php) is inside `auth/`, so [login.php](file:///c:/xampp/htdocs/online_notes_sharing/auth/login.php) is a relative path that works. But when accessed from `admin/` or `student/` (e.g., `../auth/logout.php`), PHP resolves the `Location` header relative to [logout.php](file:///c:/xampp/htdocs/online_notes_sharing/auth/logout.php)'s actual location — so it should be fine. However, to be safe and explicit, use an absolute path:

```php
header("Location: /online_notes_sharing/auth/login.php");
```

---

### 11. No Redirect After Delete in [manage_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_notes.php)
**File:** [admin/manage_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_notes.php) — Lines 29–30

After deleting a note, only an `alert()` is shown and the page continues rendering with the full table (which now shows a `<script>` tag inline before the `<!DOCTYPE html>`). The HTML output is broken.

**Fix:** After the delete operation, add:
```php
header("Location: manage_notes.php?deleted=1");
exit();
```
Then display a success message using `$_GET['deleted']` after the HTML starts.

---

### 12. Same Bug in [manage_subjects.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_subjects.php)
**File:** [admin/manage_subjects.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_subjects.php) — Line 15

Adding a subject doesn't redirect after the POST. The subject list below immediately reflects the new entry, but there's no Post-Redirect-Get (PRG) pattern, so refreshing the page will re-submit the form.

**Fix:** After `mysqli_query(...)` for insert, redirect:
```php
header("Location: manage_subjects.php");
exit();
```

---

### 13. File Upload: Original Filename Exposed
**File:** [student/upload_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/student/upload_notes.php) — Line 21

```php
$file_name = time() . "_" . $file['name'];
```

The user's original filename is embedded in the stored filename. This can leak info (e.g., `1234567890_my_secret_assignment.pdf`). Also, special characters in the original name could cause issues.

**Fix:** Generate a random/hashed filename:
```php
$file_name = uniqid('note_', true) . '.' . $file_ext;
```

---

### 14. No Check If Subject Exists Before Deleting It (Orphan Notes)
**File:** [admin/manage_subjects.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_subjects.php) — Line 22

```php
mysqli_query($conn, "DELETE FROM subjects WHERE subject_id=$id");
```

If notes are linked to a subject via `subject_id`, deleting the subject creates orphan notes unless the DB has a cascade delete. There is no user warning either.

**Fix:** Check if notes exist for the subject before deleting and warn the admin, or use `ON DELETE CASCADE` in the DB schema.

---

### 15. Student Can Access Admin Pages If Role Check is Bypassed
**File:** [student/view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/student/view_note.php) — Lines 1–7

The student [view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/view_note.php) **does not check the user's role** — only that they are logged in. So an admin accidentally visiting [/student/view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/student/view_note.php) is allowed in. More importantly, there is **no role check** at all — so if anyone knows the URL, and is just logged in, they can view any note.

---

## 🟡 Low — UI / UX Issues

### 16. No "Empty State" Message in [view_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/student/view_notes.php) Notes Cards
There is a `no-notes` paragraph for zero results, but the table in [manage_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_notes.php) has **no empty state** — the table just shows headers with no rows.

**Fix:** Add an empty row message:
```php
if (mysqli_num_rows($notes) == 0) {
    echo "<tr><td colspan='6'>No notes uploaded yet.</td></tr>";
}
```

---

### 17. No "Empty State" for Subjects Table in [manage_subjects.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_subjects.php)
Same issue — no fallback row when the subjects table is empty.

---

### 18. No Validation on Subject Name for Duplicates
**File:** [admin/manage_subjects.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_subjects.php) — Line 15

The same subject name can be added multiple times. There's no `UNIQUE` constraint check in the PHP code.

**Fix:** Query for the subject name before inserting, or rely on a `UNIQUE` index on `subject_name` in the DB.

---

### 19. [admin/view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/view_note.php) Missing `lang` Attribute
**File:** [admin/view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/view_note.php) — Line 29

```html
<html>
```

Should be:
```html
<html lang="en">
```

---

### 20. No Password Strength Validation on Registration
**File:** [auth/register.php](file:///c:/xampp/htdocs/online_notes_sharing/auth/register.php)

Any password (even `"a"`) is accepted. There's no minimum length, character complexity, or confirmation field.

**Fix:** Add a client-side and server-side check for minimum 8 characters, mix of upper/lowercase, numbers, and symbols.

---

## Summary Table

| # | File | Issue | Severity |
|---|------|-------|----------|
| 1 | [auth/register.php](file:///c:/xampp/htdocs/online_notes_sharing/auth/register.php) | Missing `session_start()` | 🔴 Critical |
| 2 | [auth/login.php](file:///c:/xampp/htdocs/online_notes_sharing/auth/login.php) | SQL Injection risk | 🔴 Critical |
| 3 | [student/view_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/student/view_notes.php) | XSS — unescaped output | 🔴 Critical |
| 4 | [admin/manage_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_notes.php) | XSS — unescaped output | 🔴 Critical |
| 5 | [admin/view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/view_note.php) | XSS — title unescaped | 🔴 Critical |
| 6 | Both [view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/view_note.php) | Path traversal via `file_name` | 🔴 Critical |
| 7 | All forms | No CSRF protection | 🔴 Critical |
| 8 | `manage_notes/subjects.php` | DELETE via GET (no CSRF) | 🔴 Critical |
| 9 | [student/upload_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/student/upload_notes.php) | Error reporting on in prod | 🟠 Medium |
| 10 | [auth/logout.php](file:///c:/xampp/htdocs/online_notes_sharing/auth/logout.php) | Relative redirect path | 🟠 Medium |
| 11 | [admin/manage_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_notes.php) | No redirect after delete (broken HTML) | 🟠 Medium |
| 12 | [admin/manage_subjects.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_subjects.php) | No PRG after add subject | 🟠 Medium |
| 13 | [student/upload_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/student/upload_notes.php) | Original filename stored | 🟠 Medium |
| 14 | [admin/manage_subjects.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_subjects.php) | Deleting subject orphans notes | 🟠 Medium |
| 15 | [student/view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/student/view_note.php) | No role check | 🟠 Medium |
| 16 | [admin/manage_notes.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_notes.php) | No empty state in table | 🟡 Low |
| 17 | [admin/manage_subjects.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_subjects.php) | No empty state in table | 🟡 Low |
| 18 | [admin/manage_subjects.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/manage_subjects.php) | Duplicate subject names allowed | 🟡 Low |
| 19 | [admin/view_note.php](file:///c:/xampp/htdocs/online_notes_sharing/admin/view_note.php) | Missing `lang` on `<html>` | 🟡 Low |
| 20 | [auth/register.php](file:///c:/xampp/htdocs/online_notes_sharing/auth/register.php) | No password strength check | 🟡 Low |
