<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT u.id, u.username 
    FROM friends f
    JOIN users u ON (f.friend_id = u.id)
    WHERE f.user_id = ? AND f.status = 'accepted'
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$friends = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <title>Vriendenlijst</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow p-4 rounded-4 border-0">
      <h2 class="custom-profile-title fw-bold mb-3">Jouw vrienden</h2>

      <?php if ($friends->num_rows > 0): ?>
        <ul class="list-group mb-3">
          <?php while ($friend = $friends->fetch_assoc()): ?>
            <li class="list-group-item">
              <?= htmlspecialchars($friend['username']) ?>
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
