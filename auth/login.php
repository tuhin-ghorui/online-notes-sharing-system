<?php
session_start();
include("../config/db.php");

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Fetch user
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $user['password'])) {

            // Create session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../student/dashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login | NotesHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">

        <!-- LEFT SIDE -->
        <div class="auth-left">
            <h1>NotesHub 📚</h1>
            <p>
                Access, upload and share study notes<br>
                anytime, anywhere.
            </p>
        </div>

        <!-- RIGHT SIDE -->
        <div class="auth-right">
            <h2>Welcome Back 👋</h2>
            <p class="subtext">Login to continue</p>

            <form method="POST">
                <div class="input-box">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="input-box">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" name="login">Login</button>
            </form>

            <p class="bottom-text">
                Don’t have an account?
                <a href="register.php">Create one</a>
            </p>
        </div>

    </div>
</div>

</body>

</html>
