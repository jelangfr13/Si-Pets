<?php
ob_start();
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$loggedAdmin = false;
if (isset($_SESSION["admin"])) {
    $loggedAdmin = true;
}

$loggedIn = $loggedAdmin;


// select logged-in users details

if ($loggedAdmin) {
    $res = mysqli_query($conn, "SELECT * FROM adopsi_binatang.users WHERE id=" . $_SESSION['admin']);
    $userRow = mysqli_fetch_array($res, MYSQLI_ASSOC);
} else{};


?>

    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Si-Pets</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
              integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
              crossorigin="anonymous">
        <link rel="stylesheet" href="../style/main.css">
        <link rel="stylesheet" href="../style/admin.css">
    </head>
    <body>

    <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="../index.php">Si-Pets</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="nav-link" href="../admin.php">Admin panel<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Page preview</a>
                </li>
            </ul>

            <?php if ($loggedAdmin) : ?>
                <span class="navbar-text text-right mr-5">Logged in as admin: <b><?php echo $userRow['useremail']; ?></b></span>
            <?php endif; ?>

            <ul class="navbar-nav">
                <?php if (!$loggedIn) : ?>
                    <li class="nav-item">
                        <a class="nav-item nav-link" href="../login.php">Login</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-item nav-link mr-auto text-right" href="../logout.php?logout">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="row col-12">
        <nav class="sidebar-menu col-md-2 navbar-dark bg-dark pt-4">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-item nav-link" href="admin.php">Admin Panel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-item nav-link active" href="edit_index.php">Edit Content</a>
                </li>
                <li class="nav-item">
                    <a class="nav-item nav-link" href="add_animals.php">Add Content</a>
                </li>
                <li class="nav-item">
                    <a class="nav-item nav-link" href="edit_users.php">Edit Users</a>
                </li>
            </ul>
        </nav>
        <div class="col-md-10 pt-4">
            <div class="col-md-8 ml-3">
                <?php
                $id = $_POST['id'];

                $sql = "delete from adopsi_binatang.animals where id = $id";

                if (mysqli_query($conn, $sql)) {
                    echo "<div>
                <h3 class='text-success'>Entry successfully deleted!</h3>
                <p>You are being redirected to the edit content page</p></div>";
                    header("Refresh:2; url=../edit_index.php");
                } else {
                    echo "<div><h3 class='text-danger'>There has been an error, please try again later!</h3>
               <p>go back to <a href='../admin.php'>homepage</a></p></div>";
                }
                $conn->close();

                ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>
    </body>
    </html>

<?php ob_end_flush(); ?>