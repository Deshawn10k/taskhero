<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}
$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT xp, level FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($xp, $level);
$stmt->fetch();
$stmt->close();


$stmt = $conn->prepare("SELECT badge_name FROM badges WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$badges = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<h2>Profiel van <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
<p>XP: <?php echo $xp; ?> | Level: <?php echo $level; ?></p>

<h3>Badges:</h3>
<?php if (count($badges) > 0): ?>
  <ul>
    <?php foreach ($badges as $badge): ?>
      <li><?php echo htmlspecialchars($badge['badge_name']); ?></li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>Je hebt nog geen badges.</p>
<?php endif; ?>

<a href="dashboard.php">Terug </a>
