<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = isset($_SESSION['user']) ? $_SESSION['user'] : $_SESSION['admin'];
$animal_id = $_GET['id']; // Ambil ID hewan dari parameter URL

// Hapus data animal dari tabel sesuai dengan ID
$query = "DELETE FROM animals WHERE id = $animal_id";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "<script>alert('Your animal has been adopted!'); window.location.href = '../index.php';</script>";
    // header("Location: ../cart.php");
    exit;
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}
?>