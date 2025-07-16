<?php
require 'auth.php';
require_login();
require 'db.php';

$user_id = $_SESSION['user_id'];

// Handle add
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $date = $_POST['date'] ?: null;
    $from = $_POST['from_time'] ?: null;
    $to = $_POST['to_time'] ?: null;
    if ($title) {
        $stmt = $pdo->prepare('INSERT INTO tasks (user_id, title, description, date, from_time, to_time) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$user_id, $title, $desc, $date, $from, $to]);
    }
    header('Location: tasks.php');
    exit;
}

// Handle complete
if (isset($_GET['complete'])) {
    $stmt = $pdo->prepare('UPDATE tasks SET completed = 1 WHERE id = ? AND user_id = ?');
    $stmt->execute([$_GET['complete'], $user_id]);
    header('Location: tasks.php');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?');
    $stmt->execute([$_GET['delete'], $user_id]);
    header('Location: tasks.php');
    exit;
}

// Fetch tasks
$stmt = $pdo->prepare('SELECT * FROM tasks WHERE user_id = ? ORDER BY completed, date, from_time');
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Tasks</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>My Tasks</h2>
    <a href="index.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
    <form method="post" class="row g-3 mb-4">
        <div class="col-md-3"><input type="text" name="title" class="form-control" placeholder="Title" required></div>
        <div class="col-md-3"><input type="text" name="description" class="form-control" placeholder="Description"></div>
        <div class="col-md-2"><input type="date" name="date" class="form-control"></div>
        <div class="col-md-2"><input type="time" name="from_time" class="form-control"></div>
        <div class="col-md-2"><input type="time" name="to_time" class="form-control"></div>
        <div class="col-md-12 mt-2"><button type="submit" name="add" class="btn btn-primary">Add Task</button></div>
    </form>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>Title</th><th>Description</th><th>Date</th><th>From</th><th>To</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= htmlspecialchars($task['title']) ?></td>
                <td><?= htmlspecialchars($task['description']) ?></td>
                <td><?= htmlspecialchars($task['date']) ?></td>
                <td><?= htmlspecialchars($task['from_time']) ?></td>
                <td><?= htmlspecialchars($task['to_time']) ?></td>
                <td><?= $task['completed'] ? '<span class="badge bg-success">Done</span>' : '<span class="badge bg-warning text-dark">Pending</span>' ?></td>
                <td>
                    <?php if (!$task['completed']): ?>
                        <a href="?complete=<?= $task['id'] ?>" class="btn btn-sm btn-success">Complete</a>
                    <?php endif; ?>
                    <a href="?delete=<?= $task['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this task?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html> 