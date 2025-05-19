<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT u.username FROM friends f JOIN users u ON u.id = f.friend_id WHERE f.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Vrienden</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4 rounded-4 border-0">
            <h2 class="text-primary fw-bold mb-3">Je vrienden</h2>
            <?php if ($result->num_rows > 0): ?>
                <ul class="list-group">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($row['username']) ?>
                            <span class="badge bg-success">Toegevoegd</span>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Je hebt nog geen vrienden toegevoegd.</p>
            <?php endif; ?>
            <a href="dashboard.php" class="btn btn-outline-primary mt-4">Terug naar dashboard</a>
        </div>
    </div>
</body>
</html>
