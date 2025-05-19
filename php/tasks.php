<?php
session_start();
require 'php/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $stmt = $conn->prepare("INSERT INTO tasks (user_id, title) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $title);
    $stmt->execute();
}

$result = $conn->query("SELECT * FROM tasks WHERE user_id = $user_id");

?>

<h2>Mijn taken</h2>
<form method="POST">
  <input type="text" name="title" placeholder="Nieuwe taak" required>
  <button type="submit">Toevoegen</button>
</form>

<ul>
<?php while ($row = $result->fetch_assoc()) : ?>
  <li>
    <?php if ($row['completed']): ?>
      <s><?php echo htmlspecialchars($row['title']); ?></s>
    <?php else: ?>
      <?php echo htmlspecialchars($row['title']); ?>
      <a href="completetask.php?id=<?php echo $row['id']; ?>">Voltooid</a>
    <?php endif; ?>
  </li>
<?php endwhile; ?>
</ul>

<a href="dashboard.php">Terug</a>
