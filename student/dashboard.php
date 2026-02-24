<?php
session_start();

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard | NotesHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="dashboard-wrapper">

    <!-- HEADER -->
    <div class="dashboard-header">
        <h1>Welcome, <?php echo $_SESSION['user_name']; ?> 👋</h1>
        <p>Manage your notes from here</p>
    </div>

    <!-- CARDS -->
    <div class="dashboard-cards">

        <a href="upload_notes.php" class="dash-card">
            <h3>📤 Upload Notes</h3>
            <p>Share your study materials</p>
        </a>

        <a href="view_notes.php" class="dash-card">
            <h3>📚 View Notes</h3>
            <p>Access uploaded notes</p>
        </a>

        <a href="../auth/logout.php" class="dash-card logout">
            <h3>🚪 Logout</h3>
            <p>Sign out of your account</p>
        </a>

    </div>

</div>

</body>
</html>

