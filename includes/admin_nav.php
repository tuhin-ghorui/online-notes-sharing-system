<?php
// Admin top navigation bar
$initials = strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1));
$current  = basename($_SERVER['PHP_SELF']);
?>
<nav class="topnav">
    <a href="dashboard.php" class="nav-logo">
        <span>👑</span> NotesHub <span style="font-size:12px;font-weight:500;opacity:0.6;-webkit-text-fill-color:initial;background:none;color:#818cf8">Admin</span>
    </a>
    <div class="nav-links">
        <a href="dashboard.php"       class="nav-link <?= $current==='dashboard.php'       ? 'active' : '' ?>">🏠 <span>Dashboard</span></a>
        <a href="manage_subjects.php" class="nav-link <?= $current==='manage_subjects.php' ? 'active' : '' ?>">📘 <span>Subjects</span></a>
        <a href="manage_notes.php"    class="nav-link <?= $current==='manage_notes.php'    ? 'active' : '' ?>">🗂️ <span>Notes</span></a>
    </div>
    <div class="nav-user">
        <div class="nav-avatar" style="background:linear-gradient(135deg,#f59e0b,#ef4444)"><?= htmlspecialchars($initials) ?></div>
        <span class="nav-username"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
        <a href="../auth/logout.php" class="nav-logout">⏻ <span>Logout</span></a>
    </div>
</nav>
