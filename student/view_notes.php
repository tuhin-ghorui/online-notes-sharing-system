<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Notes | NotesHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="notes-page">

    <!-- HEADER -->
    <div class="notes-header">
        <h2>📚 Available Notes</h2>
        <p>Browse and download study materials</p>
    </div>

    <!-- FILTER -->
    <form method="GET" class="filter-bar">
        <label>Select Subject</label>
        <select name="subject_id">
            <option value="">-- All Subjects --</option>
            <?php
            $subjects = mysqli_query($conn, "SELECT * FROM subjects");
            while ($sub = mysqli_fetch_assoc($subjects)) {
                $selected = (isset($_GET['subject_id']) && $_GET['subject_id'] == $sub['subject_id']) ? "selected" : "";
                echo "<option value='{$sub['subject_id']}' $selected>
                        {$sub['subject_name']}
                      </option>";
            }
            ?>
        </select>

        <button type="submit">Search</button>
    </form>

    <!-- TABLE -->
    <div class="table-wrapper">
        <table class="notes-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Uploaded By</th>
                    <th>Date</th>
                    <th>View</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $where = "";

                if (isset($_GET['subject_id']) && $_GET['subject_id'] != "") {
                    $subject_id = intval($_GET['subject_id']);
                    $where = "WHERE notes.subject_id = $subject_id";
                }

                $query = "
                    SELECT notes.*, users.name, subjects.subject_name 
                    FROM notes
                    JOIN users ON notes.user_id = users.user_id
                    JOIN subjects ON notes.subject_id = subjects.subject_id
                    $where
                    ORDER BY notes.uploaded_on DESC
                ";

                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['title']}</td>
                            <td>{$row['subject_name']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['uploaded_on']}</td>
                            <td>
                                <a class='action view' href='view_note.php?id={$row['note_id']}'>View</a>
                            </td>
                            <td>
                                <a class='action download' href='../uploads/notes/{$row['file_name']}' download>
                                    Download
                                </a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <a href="dashboard.php" class="back-link">⬅ Back to Dashboard</a>

</div>

</body>
</html>
