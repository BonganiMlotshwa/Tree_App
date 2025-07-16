<?php
require 'auth.php';
require_login();
require 'db.php';

$user_id = $_SESSION['user_id'];

// Handle add
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    if ($title) {
        $stmt = $pdo->prepare('INSERT INTO courses (user_id, title, description) VALUES (?, ?, ?)');
        $stmt->execute([$user_id, $title, $desc]);
    }
    header('Location: courses.php');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM courses WHERE id = ? AND user_id = ?');
    $stmt->execute([$_GET['delete'], $user_id]);
    header('Location: courses.php');
    exit;
}

// Fetch courses
$stmt = $pdo->prepare('SELECT * FROM courses WHERE user_id = ?');
$stmt->execute([$user_id]);
$courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Courses</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>My Courses</h2>
    <a href="index.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
    <form method="post" class="row g-3 mb-4">
        <div class="col-md-4"><input type="text" name="title" class="form-control" placeholder="Course Title" required></div>
        <div class="col-md-6"><input type="text" name="description" class="form-control" placeholder="Description"></div>
        <div class="col-md-2"><button type="submit" name="add" class="btn btn-primary">Add Course</button></div>
    </form>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>Title</th><th>Description</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($courses as $course): ?>
            <tr>
                <td><?= htmlspecialchars($course['title']) ?></td>
                <td><?= htmlspecialchars($course['description']) ?></td>
                <td>
                    <a href="?delete=<?= $course['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this course?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html> 