<?php
session_start();
include('../config/db.php');

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$note_id = intval($_GET['id']);

// ✅ CORRECT COLUMN NAME: note_id
$query = "
    SELECT notes.*, subjects.subject_name, users.name AS uploader
    FROM notes
    LEFT JOIN subjects ON notes.subject_id = subjects.subject_id
    LEFT JOIN users ON notes.user_id = users.user_id
    WHERE notes.note_id = $note_id
";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Note not found.");
}

$note = mysqli_fetch_assoc($result);

$filePath = "../uploads/notes/" . $note['file_name'];
$fileType = strtolower($note['file_type']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($note['title']); ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="note-view-container">
    <h1><?php echo htmlspecialchars($note['title']); ?></h1>

    <div class="note-meta">
        <span>📘 <?php echo htmlspecialchars($note['subject_name']); ?></span>
        <span>👤 <?php echo htmlspecialchars($note['uploader']); ?></span>
        <span>🕒 <?php echo $note['uploaded_on']; ?></span>
    </div>

    <div class="note-preview-box">
        <?php if (!file_exists($filePath)) : ?>
            <p class="error-text">File not found on server.</p>

        <?php elseif ($fileType === 'pdf') : ?>
            <iframe src="<?php echo $filePath; ?>" frameborder="0"></iframe>

        <?php elseif (in_array($fileType, ['jpg', 'jpeg', 'png'])) : ?>
            <img src="<?php echo $filePath; ?>" alt="Note Image">

        <?php else : ?>
            <p class="info-text">
                Preview not available for this file type.
            </p>
            <a class="download-btn" href="<?php echo $filePath; ?>" download>
                ⬇ Download File
            </a>
        <?php endif; ?>
    </div>

    <a href="view_notes.php" class="back-link">← Back to Notes</a>
</div>

</body>
</html>