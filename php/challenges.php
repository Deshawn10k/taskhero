<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, title, description, status FROM challenges WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Uitdagingen</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Jouw Uitdagingen</h1>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Terug naar Dashboard</a>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
                    <span class="badge bg-<?= $row['status'] === 'completed' ? 'success' : 'warning' ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
