<?php
require 'auth.php';
require_login();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tree App Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <div class="mb-3">
        <a href="tasks.php" class="btn btn-success">My Tasks</a>
        <a href="courses.php" class="btn btn-info">My Courses</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>
</body>
</html> 