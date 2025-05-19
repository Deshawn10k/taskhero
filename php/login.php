<?php
session_start();
require 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $hashed_pw);
    $stmt->fetch();
    if (password_verify($password, $hashed_pw)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        header("Location: ../dashboard.php");
        exit;
    } else {
        echo "Verkeerde inloggegevens.";
    }
} else {
    echo "Gebruiker niet gevonden.";
}
?>
