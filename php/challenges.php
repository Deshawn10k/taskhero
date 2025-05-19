<?php
session_start();
require 'php/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT * FROM challenges WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<h2>Mijn Uitdagingen</h2>

<ul>
<?php while ($row = $result->fetch_assoc()) : ?>
  <li>
    <?php echo htmlspecialchars($row['challenge_text']); ?>
    <?php if ($row['is_completed']) : ?>
      <strong>Voltooid</strong>
    <?php else : ?>
      <a href="php/completechallenge.php?id=<?php echo $row['id']; ?>">Voltooien</a>
    <?php endif; ?>
  </li>
<?php endwhile; ?>
</ul>

<h3>Nieuwe uitdaging aanmaken</h3>
<form action="php/addchallenge.php" method="POST">
  <input type="text" name="challenge_text" placeholder="Uitdaging beschrijving" required>
  <button type="submit
