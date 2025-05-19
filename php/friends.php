<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}
$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT u.username, f.id FROM friends f JOIN users u ON f.friend_id = u.id WHERE f.user_id = ? AND f.status = 'accepted'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$friends = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();


$stmt = $conn->prepare("SELECT f.id, u.username FROM friends f JOIN users u ON f.user_id = u.id WHERE f.friend_id = ? AND f.status = 'pending'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result2 = $stmt->get_result();
$requests = $result2->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<h2>Vriendenlijst</h2>

<h3>Vrienden</h3>
<?php if (count($friends) > 0): ?>
  <ul>
    <?php foreach ($friends as $friend): ?>
      <li><?php echo htmlspecialchars($friend['username']); ?></li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>Je hebt nog geen vrienden.</p>
<?php endif; ?>

<h3>Vriendschapsverzoeken</h3>
<?php if (count($requests) > 0): ?>
  <ul>
    <?php foreach ($requests as $req): ?>
      <li>
        <?php echo htmlspecialchars($req['username']); ?>
        <a href="php/acceptfriend.php?id=<?php echo $req['id']; ?>">Accepteren</a> |
        <a href="php/declinefriend.php?id=<?php echo $req['id']; ?>">Weigeren</a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>Geen nieuwe verzoeken.</p>
<?php endif; ?>

<h3>Vriend toevoegen</h3>
<form action="php/addfriend.php" method="POST">
  <input type="text" name="friend_username" placeholder="Gebruikersnaam vriend" required>
  <button type="submit">Verzoek versturen</button>
</form>

<a href="dashboard.php">Terug</a>
