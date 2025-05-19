<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
?>
<h1>Welkom bij TaskHero, <?php echo $_SESSION['username']; ?>!</h1>
<a href="tasks.php">Ga naar je taken</a>
<a href="php/logout.php">Uitloggen</a>
