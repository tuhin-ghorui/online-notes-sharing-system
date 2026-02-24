<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Add subject
if (isset($_POST['add_subject'])) {
    $subject_name = mysqli_real_escape_string($conn, $_POST['subject_name']);

    if (!empty($subject_name)) {
        mysqli_query($conn, "INSERT INTO subjects (subject_name) VALUES ('$subject_name')");
    }
}

// Delete subject
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM subjects WHERE subject_id=$id");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Subjects | NotesHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="admin-page">

    <!-- HEADER -->
    <div class="admin-page-header">
        <h2>📘 Manage Subjects</h2>
        <p>Add or remove subjects available to students</p>
    </div>

    <!-- ADD SUBJECT -->
    <div class="admin-form-card">
        <form method="POST" class="admin-form">
            <input
                type="text"
                name="subject_name"
                placeholder="Enter subject name"
                required
            >
            <button type="submit" name="add_subject">Add Subject</button>
        </form>
    </div>

    <!-- SUBJECT TABLE -->
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $subjects = mysqli_query($conn, "SELECT * FROM subjects");
                while ($row = mysqli_fetch_assoc($subjects)) {
                    echo "<tr>
                            <td>{$row['subject_id']}</td>
                            <td>{$row['subject_name']}</td>
                            <td>
                                <a
                                    class='delete-btn'
                                    href='manage_subjects.php?delete={$row['subject_id']}'
                                    onclick=\"return confirm('Delete this subject?')\"
                                >
                                    Delete
                                </a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <a href="dashboard.php" class="back-link">⬅ Back to Dashboard</a>

</div>

</body>
</html>
