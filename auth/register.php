<?php
include("../config/db.php");

if (isset($_POST['register'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already registered!');</script>";
    } else {
        // Insert user
        $query = "INSERT INTO users (name, email, password) 
                  VALUES ('$name', '$email', '$hashed_password')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registration successful!'); window.location='login.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration | NotesHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SAME CSS FILE -->
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">

        <!-- LEFT SIDE -->
        <div class="auth-left">
            <h1>Join NotesHub 🚀</h1>
            <p>
                Create your account and start<br>
                sharing and accessing study notes.
            </p>
        </div>

        <!-- RIGHT SIDE -->
        <div class="auth-right">
            <h2>Create Account ✨</h2>
            <p class="subtext">Register to get started</p>

            <form method="POST">
                <div class="input-box">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Your full name" required>
                </div>

                <div class="input-box">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="student@email.com" required>
                </div>

                <div class="input-box">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Create a password" required>
                </div>

                <button type="submit" name="register">Register</button>
            </form>

            <p class="bottom-text">
                Already have an account?
                <a href="login.php">Login</a>
            </p>
        </div>

    </div>
</div>

</body>
</html>
