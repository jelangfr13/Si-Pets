<?php
session_start();
require_once 'db_connect.php'; // Sesuaikan jalur dengan struktur folder Anda

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    header("Location: ../login.php"); // Sesuaikan jalur dengan struktur folder Anda
    exit;
}

if (!isset($_GET['id'])) {
    echo "Error: ID not set";
    exit;
}

$user_id = isset($_SESSION['user']) ? $_SESSION['user'] : $_SESSION['admin'];
$cart_id = intval($_GET['id']);

$query = "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id";

if (mysqli_query($conn, $query)) {
    header("Location: ../cart.php"); // Sesuaikan jalur dengan struktur folder Anda
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}
exit;
?>
