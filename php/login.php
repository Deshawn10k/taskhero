<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
       
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;

         
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Wachtwoord incorrect";
        }
    } else {
        echo "Gebruiker niet gevonden";
    }
}
?>
