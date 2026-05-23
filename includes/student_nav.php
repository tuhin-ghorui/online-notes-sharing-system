<?php
// Student top navigation bar
$initials = strtoupper(substr($_SESSION['user_name'] ?? 'S', 0, 1));
$current  = basename($_SERVER['PHP_SELF']);
?>
<nav class="topnav">
    <a href="dashboard.php" class="nav-logo">
        <span>📚</span> NotesHub
    </a>
    <div class="nav-links">
        <a href="dashboard.php"   class="nav-link <?= $current==='dashboard.php'   ? 'active' : '' ?>">🏠 <span>Home</span></a>
        <a href="view_notes.php"  class="nav-link <?= $current==='view_notes.php'  ? 'active' : '' ?>">📖 <span>Browse</span></a>
        <a href="upload_notes.php" class="nav-link <?= $current==='upload_notes.php'? 'active' : '' ?>">📤 <span>Upload</span></a>
    </div>
    <div class="nav-user">
        <div class="nav-avatar"><?= htmlspecialchars($initials) ?></div>
        <span class="nav-username"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
        <a href="../auth/logout.php" class="nav-logout">⏻ <span>Logout</span></a>
    </div>
</nav>
