<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$note_id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM notes WHERE note_id = $note_id");

if (mysqli_num_rows($result) != 1) {
    die("Note not found");
}

$note = mysqli_fetch_assoc($result);

$file_path = "../uploads/notes/" . $note['file_name'];
$file_ext  = strtolower($note['file_type']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $note['title']; ?> | NotesHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="note-view-page">

    <!-- HEADER -->
    <div class="note-header">
        <h2><?php echo $note['title']; ?></h2>
        <p>Preview your selected note</p>
    </div>

    <!-- CONTENT -->
    <div class="note-content">

        <?php if (in_array($file_ext, ['jpg','jpeg','png'])): ?>

            <img src="<?php echo $file_path; ?>" alt="Note Image">

        <?php elseif ($file_ext === 'pdf'): ?>

            <iframe
                src="<?php echo $file_path; ?>"
                title="PDF Preview">
            </iframe>

        <?php else: ?>

            <div class="note-download-box">
                <p>Preview not available for this file type.</p>
                <a href="<?php echo $file_path; ?>" download>
                    ⬇ Download File
                </a>
            </div>

        <?php endif; ?>

    </div>

    <!-- FOOTER -->
    <a href="view_notes.php" class="back-link">⬅ Back to Notes</a>

</div>

</body>
</html>

