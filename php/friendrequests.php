<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT f.id, u.username 
    FROM friends f 
    JOIN users u ON f.user_id = u.id 
    WHERE f.friend_id = ? AND f.status = 'pending'
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <title>Vriendenverzoeken</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card p-4 shadow rounded-4">
    <h2 class="mb-3">Vriendenverzoeken</h2>
    <?php if ($result->num_rows > 0): ?>
      <ul class="list-group">
        <?php while ($row = $result->fetch_assoc()): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($row['username']) ?>
            <div>
              <a href="php/acceptfriend.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Accepteren</a>
              <a href="php/declinefriend.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Weigeren</a>
            </div>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p>Geen nieuwe verzoeken.</p>
    <?php endif; ?>
    <a href="friends.php" class="btn btn-outline-primary mt-3">Terug</a>
  </div>
</div>
</body>
</html>
