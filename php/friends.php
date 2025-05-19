<?php
session_start();
require 'php/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['friend_username'])) {
    $friend_username = trim($_POST['friend_username']);
    
   
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmt->bind_param("si", $friend_username, $user_id);
    $stmt->execute();
    $stmt->bind_result($friend_id);
    if ($stmt->fetch()) {
        
        $stmt->close();
        $stmt = $conn->prepare("SELECT * FROM friends WHERE user_id = ? AND friend_id = ?");
        $stmt->bind_param("ii", $user_id, $friend_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 0) {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO friends (user_id, friend_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $friend_id);
            $stmt->execute();
            $message = "Vriend toegevoegd!";
        } else {
            $message = "Je bent al vrienden met deze gebruiker.";
        }
    } else {
        $message = "Gebruiker niet gevonden.";
    }
    $stmt->close();
}


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

        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" class="mb-4">
            <div class="input-group">
                <input type="text" name="friend_username" class="form-control" placeholder="Gebruikersnaam toevoegen..." required>
                <button type="submit" class="btn btn-primary">Toevoegen</button>
            </div>
        </form>

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
