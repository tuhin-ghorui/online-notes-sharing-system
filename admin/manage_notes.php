<?php
session_start();
include("../config/db.php");

// Admin protection
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Delete note
if (isset($_GET['delete'])) {
    $note_id = intval($_GET['delete']);

    // Get file name
    $result = mysqli_query($conn, "SELECT file_name FROM notes WHERE note_id = $note_id");
    if ($row = mysqli_fetch_assoc($result)) {

        $file_path = "../uploads/notes/" . $row['file_name'];

        // Delete file from server
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete record from database
        mysqli_query($conn, "DELETE FROM notes WHERE note_id = $note_id");

        echo "<script>alert('Note deleted successfully');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Notes | NotesHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="admin-page">

    <!-- HEADER -->
    <div class="admin-page-header">
        <h2>🗂️ Manage Uploaded Notes</h2>
        <p>Review and control all uploaded notes</p>
    </div>

    <!-- TABLE -->
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Uploaded By</th>
                    <th>Date</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "
                    SELECT notes.*, users.name, subjects.subject_name 
                    FROM notes
                    JOIN users ON notes.user_id = users.user_id
                    JOIN subjects ON notes.subject_id = subjects.subject_id
                    ORDER BY notes.uploaded_on DESC
                ";

                $notes = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($notes)) {

    $noteId = $row['note_id'];

    echo "
    <tr>
        <td>{$row['title']}</td>
        <td>{$row['subject_name']}</td>
        <td>{$row['name']}</td>
        <td>{$row['uploaded_on']}</td>
        <td>
            <a class='view-btn' href='view_note.php?id=$noteId'>View</a>
        </td>
        <td class='action-cell'>
            <a class='delete-btn'
               href='manage_notes.php?delete=$noteId'
               onclick=\"return confirm('Are you sure you want to delete this note?')\">
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
