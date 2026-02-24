<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$note_id = intval($_GET['id']);

$query = "SELECT * FROM notes WHERE note_id = $note_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) != 1) {
    die("Note not found");
}

$note = mysqli_fetch_assoc($result);

$file_path = "../uploads/notes/" . $note['file_name'];
$file_ext = $note['file_type'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Note</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<h2><?php echo $note['title']; ?></h2>

<?php if (in_array($file_ext, ['jpg', 'jpeg', 'png'])): ?>

    <!-- Image preview -->
    <img src="<?php echo $file_path; ?>" style="max-width:100%; height:auto;">

<?php elseif ($file_ext === 'pdf'): ?>

    <!-- PDF preview -->
    <iframe src="<?php echo $file_path; ?>" width="100%" height="600px"></iframe>

<?php else: ?>

    <!-- Word file -->
    <p>This file cannot be previewed.</p>
    <a href="<?php echo $file_path; ?>" download>⬇ Download File</a>

<?php endif; ?>

<br><br>
<a href="manage_notes.php">⬅ Back</a>

</body>
</html>
