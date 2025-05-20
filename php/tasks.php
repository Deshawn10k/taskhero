<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT id, title, status FROM tasks WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Takenoverzicht</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4">
    <a class="navbar-brand fw-bold" href="dashboard.php">TaskHero</a>
    <div class="ms-auto">
      <a href="logout.php" class="btn btn-danger">Uitloggen</a>
    </div>
  </nav>


  <div class="container mt-5">
     <?php if (isset($_GET['success']) && $_GET['success'] == 'task_done'): ?>
    <div class="alert alert-success">Taak succesvol afgerond! XP is verhoogd.</div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Er ging iets mis bij het verwerken van de taak.</div>
  <?php endif; ?>

    <h1 class="text-primary mb-4">Jouw Taken</h1>

    <form action="addtask.php" method="POST" class="d-flex mb-4">
      <input type="text" name="title" class="form-control me-2" placeholder="Nieuwe taak..." required>
      <button type="submit" class="btn btn-warning">Toevoegen</button>
    </form>

    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="card mb-2 p-3 shadow-sm d-flex flex-row justify-content-between align-items-center rounded-4">
        <div>
          <strong><?php echo htmlspecialchars($row['title']); ?></strong>
          <span class="badge bg-<?php echo $row['status'] === 'done' ? 'success' : 'secondary'; ?>">
            <?php echo ucfirst($row['status']); ?>
          </span>
        </div>
        <div>
          <a href="markdone.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success me-1">✓</a>
          <a href="deletetask.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">🗑️</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</body>

</html>