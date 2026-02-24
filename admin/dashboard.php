<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | NotesHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="admin-wrapper">

    <!-- HEADER -->
    <div class="admin-header">
        <h1>👑 Admin Dashboard</h1>
        <p>Manage platform data and content</p>
    </div>

    <!-- ADMIN ACTIONS -->
    <div class="admin-cards">

        <a href="manage_subjects.php" class="admin-card">
            <h3>📘 Manage Subjects</h3>
            <p>Add, edit or remove subjects</p>
        </a>

        <a href="manage_notes.php" class="admin-card">
            <h3>🗂️ Manage Notes</h3>
            <p>Review, approve or delete notes</p>
        </a>

        <a href="../auth/logout.php" class="admin-card logout">
            <h3>🚪 Logout</h3>
            <p>Sign out of admin panel</p>
        </a>

    </div>

</div>

</body>
</html>
