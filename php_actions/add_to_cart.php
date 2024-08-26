<?php
session_start();
require_once 'db_connect.php'; // Sesuaikan jalur dengan struktur folder Anda

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    header("Location: ../login.php"); // Sesuaikan jalur dengan struktur folder Anda
    exit;
}

if (!isset($_POST['animal_id'])) {
    echo "Error: Animal ID not set";
    exit;
}

$user_id = isset($_SESSION['user']) ? $_SESSION['user'] : $_SESSION['admin'];
$animal_id = intval($_POST['animal_id']);

// Periksa apakah item sudah ada di keranjang
$query = "SELECT * FROM cart WHERE user_id = $user_id AND animal_id = $animal_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Jika item sudah ada di keranjang, tingkatkan jumlahnya
    $query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND animal_id = $animal_id";
} else {
    // Jika item belum ada di keranjang, tambahkan item baru
    $query = "INSERT INTO cart (user_id, animal_id, quantity) VALUES ($user_id, $animal_id, 1)";
}

if (mysqli_query($conn, $query)) {
    header("Location: ../cart.php"); // Sesuaikan jalur dengan struktur folder Anda
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
