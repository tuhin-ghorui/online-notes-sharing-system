<?php
session_start();

// If user is already logged in
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
        exit();
    } else {
        header("Location: student/dashboard.php");
        exit();
    }
}

// If not logged in, redirect to login
header("Location: auth/login.php");
exit();