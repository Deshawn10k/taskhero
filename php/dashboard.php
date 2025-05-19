<?php
session_start();
require 'php/db.php';

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
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4">
    <a class="navbar-brand fw-bold" href="#">TaskHero</a>
    <div class="ms-auto">
      <a href="tasks.php" class="btn btn-light me-2">Taken</a>
      <a href="profile.php" class="btn btn-light me-2">Profiel</a>
      <a href="php/logout.php" class="btn btn-danger">Uitloggen</a>
    </div>
  </nav>

  <div class="container mt-5 text-center">
    <h1 class="mb-3">Welkom terug, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p class="lead">Je hebt <strong><?php echo $xp; ?> XP</strong> en je bent op <strong>level <?php echo $level; ?></strong>.</p>

    <div class="row mt-4">
      <div class="col-md-4 mb-3">
        <div class="card border-0 shadow rounded-4 p-3">
          <h5 class="fw-bold text-primary mb-2">Taken</h5>
          <p>Bekijk of voeg nieuwe taken toe.</p>
          <a href="tasks.php" class="btn btn-custom w-100">Taken bekijken</a>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card border-0 shadow rounded-4 p-3">
          <h5 class="fw-bold text-primary mb-2">Profiel</h5>
          <p>Bekijk je badges, XP en uitdagingen.</p>
          <a href="profile.php" class="btn btn-custom w-100">Ga naar profiel</a>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card border-0 shadow rounded-4 p-3">
          <h5 class="fw-bold text-primary mb-2">Vrienden</h5>
          <p>Beheer je vrienden en uitdagingen.</p>
          <a href="friends.php" class="btn btn-custom w-100">Vriendenlijst</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
