<?php
session_start();
require_once 'php_actions/db_connect.php';

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $query = "?id=$id";
    }
    $_SESSION['redirectTo'] = "animal_info.php$query";
    header("Location: login.php");
    exit;
}

$loggedUser = false;
if (isset($_SESSION["user"])) {
    $loggedUser = true;
}
$loggedAdmin = false;
if (isset($_SESSION["admin"])) {
    $loggedAdmin = true;
}

$loggedIn = $loggedUser || $loggedAdmin;

// select logged-in users details

if ($loggedUser) {
    $res = mysqli_query($conn, "SELECT * FROM adopsi_binatang.users WHERE id=" . $_SESSION['user']);
    $userRow = mysqli_fetch_array($res, MYSQLI_ASSOC);
} else if ($loggedAdmin) {
    $res = mysqli_query($conn, "SELECT * FROM adopsi_binatang.users WHERE id=" . $_SESSION['admin']);
    $userRow = mysqli_fetch_array($res, MYSQLI_ASSOC);
}

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$user_id = isset($_SESSION['user']) ? $_SESSION['user'] : $_SESSION['admin'];

$query = "SELECT cart.*, animals.name, animals.image FROM cart 
          JOIN animals ON cart.animal_id = animals.id 
          WHERE cart.user_id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>
<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Si-Pets</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <?php if ($loggedAdmin) : ?>
                <li class="nav-item">
                    <a class="nav-link text-info" href="admin.php">Admin panel</a>
                </li>
            <?php endif; ?>
            <?php if (!$loggedAdmin) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
                </li>
            <?php endif; ?>
            <?php if ($loggedIn) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="large_animals.php">Large animals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="small_animals.php">Small animals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="search_animals.php">Search</a>
                </li>
            <?php endif; ?>
        </ul>

        <?php if ($loggedUser) : ?>
            <span class="navbar-text text-right mr-5">Logged in as user: <b><?php echo $userRow['useremail']; ?></b></span>
        <?php endif; ?>
        <?php if ($loggedAdmin) : ?>
            <span class="navbar-text text-right mr-5">Logged in as admin: <b><?php echo $userRow['useremail']; ?></b></span>
        <?php endif; ?>

        <ul class="navbar-nav">
            <?php if ($loggedUser) : ?>
                <li class="nav-item">
                <a class="nav-item nav-link" href="cart.php"><i class="fa-solid fa-cart-plus"></i> Cart</a>
            </li>
            <li class="nav-item">
                <a class="nav-item nav-link mr-auto text-right" href="logout.php?logout">Logout</a>
            </li>
            <?php endif; ?>
            <?php if ($loggedAdmin): ?>
                <li class="nav-item">
                    <a class="nav-item nav-link mr-auto text-right" href="logout.php?logout">Logout</a>
                </li>
            <?php endif; ?>
            <?php if (!$loggedIn):?>    
                <li class="nav-item">
                    <a class="nav-item nav-link" href="login.php">Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Your Cart</h2>
    <table class="table">
        <thead>
        <tr>
            <th scope="col" style="text-align:center">Image</th>
            <th scope="col" style="text-align:center">Name</th>
            <th scope="col" style="text-align:center">Quantity</th>
            <th scope="col" style="text-align:center">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td style="align:center; max-width:500px"><img class="cartIMG" src="<?= "upload/" . $row['image'] ?>" alt="<?= $row['name'] ?>" width="50"></td>
                <td style="align-items:center; text-align:center"><?= $row['name'] ?></td>
                <td style="text-align:center"><?= $row['quantity'] ?></td>
                <td style="display:flex; flex-direction:column; gap:10px; align-items:center">
                    <a href="./php_actions/remove_from_cart.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete()" style="width:100px;">Remove</a>
                    <a href="./php_actions/checkout.php?id=<?= $row['animal_id'] ?>" class="btn btn-success btn-sm" onclick="return confirmAdopt()" style="width:100px">Adopt Now!</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to remove this item from your cart?");
    }
    function confirmAdopt() {
        return confirm("Are you sure you want to adopt this Animal?");
    }
</script>

</body>
</html>
