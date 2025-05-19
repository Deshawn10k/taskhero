<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit;
}

$user_id = $_SESSION['user_id'];
$earned_xp = intval($_POST['xp'] ?? 10); 

$stmt = $conn->prepare("SELECT xp, level FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($xp, $level);
$stmt->fetch();
$stmt->close();


$new_xp = $xp + $earned_xp;
$new_level = $level;


while ($new_xp >= 100) {
    $new_level++;
    $new_xp -= 100;
}

$stmt = $conn->prepare("UPDATE users SET xp = ?, level = ? WHERE id = ?");
$stmt->bind_param("iii", $new_xp, $new_level, $user_id);
$stmt->execute();
$stmt->close();

echo "XP bijgewerkt!";
?>
