<?php
require 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];


$sql_check = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
 
    echo "Deze gebruikersnaam is al in gebruik.";
} else {

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $sql_insert = "INSERT INTO users (username, password, xp, level) VALUES (?, ?, 0, 1)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ss", $username, $hashed_password);

    if ($stmt_insert->execute()) {
        echo "Registratie succesvol!";
    } else {
        echo "Er is iets fout gegaan, probeer het opnieuw.";
    }
}

$stmt->close();
$conn->close();
?>
