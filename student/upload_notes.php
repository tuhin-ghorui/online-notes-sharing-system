<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config/db.php");

// Protect page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_POST['upload'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $subject_id = intval($_POST['subject_id']);
    $user_id = $_SESSION['user_id'];

    $file = $_FILES['note_file'];
    $file_name = time() . "_" . $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];

    $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'ppt', 'pptx'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_ext)) {
        echo "<script>alert('Invalid file type');</script>";
    } elseif ($file_size > 5 * 1024 * 1024) {
        echo "<script>alert('File size must be under 5MB');</script>";
    } else {

        $upload_path = "../uploads/notes/" . $file_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {

            $query = "INSERT INTO notes 
                      (user_id, subject_id, title, file_name, file_type)
                      VALUES ('$user_id', '$subject_id', '$title', '$file_name', '$file_ext')";

            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Notes uploaded successfully');</script>";
            } else {
                echo "<script>alert('Database error');</script>";
            }
        } else {
            echo "<script>alert('File upload failed');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Notes | NotesHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="page-wrapper">

    <div class="form-card">
        <h2>📤 Upload Notes</h2>
        <p class="subtext">Share your study materials with others</p>

        <form method="POST" enctype="multipart/form-data">

            <div class="input-box">
                <label>Notes Title</label>
                <input type="text" name="title" placeholder="Enter notes title" required>
            </div>

            <div class="input-box">
                <label>Select Subject</label>
                <select name="subject_id" required>
                    <option value="">-- Select Subject --</option>
                    <?php
                    $subjects = mysqli_query($conn, "SELECT * FROM subjects");
                    while ($row = mysqli_fetch_assoc($subjects)) {
                        echo "<option value='{$row['subject_id']}'>{$row['subject_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="input-box">
                <label>Upload File</label>
                <input type="file" name="note_file" required>
            </div>

            <button type="submit" name="upload">Upload Notes</button>
        </form>

        <a href="dashboard.php" class="back-link">⬅ Back to Dashboard</a>
    </div>

</div>

</body>
</html>

