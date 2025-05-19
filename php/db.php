<?php
$conn = new mysqli("localhost", "root", "", "taskhero");
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}
?>
