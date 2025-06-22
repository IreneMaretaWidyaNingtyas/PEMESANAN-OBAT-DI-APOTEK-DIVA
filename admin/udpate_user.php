<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    mysqli_query($conn, "UPDATE users SET username = '$username', email = '$email' WHERE id = $id");

    header('Location: users.php');
    exit;
}
?>
