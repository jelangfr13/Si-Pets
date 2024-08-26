<?php
ob_start();
session_start();
require_once 'php_actions/db_connect.php';

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    header("Location: index.php");
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

$resultAnimals = mysqli_query($conn, "Select *, TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) AS age from adopsi_binatang.animals");

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
                    <li class="nav-item active">
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
                <?php if (!$loggedIn) : ?>
                    <li class="nav-item">
                        <a class="nav-item nav-link" href="login.php">Login</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-item nav-link mr-auto text-right" href="logout.php?logout">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4 mt-3">Love and Save Them!</h1>
            <p class="lead">Save a life by giving a new home to a stray animal. Adopt a loyal companion who will bring joy and love into your life. Provide a better future for a deserving pet. Make a difference and adopt today!</p>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-5">
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <?php if ($loggedIn) : ?>
                <form class="form-inline mx-auto my-lg-0">
                    <label class="mr-sm-2" for="search">Search animals by name, type, size or location: </label>
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search"
                           name="search" id="search">
                </form>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <?php if (!$loggedIn) : ?>
            <div class="row">
                <div class="col-8 m-auto">
                    <p class="text-center">Please login or register if you want to get more info about our precious
                        pets.</p>
                </div>
            </div>
        <?php endif; ?>

        <div class="row" style="display:flex; flex-direction:column">
            <p id="result"></p>


        </div>
    </div>
    <script
            src="https://code.jquery.com/jquery-3.4.0.min.js"
            integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg="
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>
    <script>
        var request;
        $("#search").keyup(function (event) {
            event.preventDefault();
            if (request) {
                request.abort();
            }
            var $form = $(this);
            var $inputs = $form.find("input, select, button, textarea");
            var serializedData = $form.serialize();
            var search = document.getElementById("search").value;
            if (search.length > 0) {
                $inputs.prop("disabled", true);

                request = $.ajax({
                    url: "php_actions/a_search_animals.php",
                    type: "post",
                    data: serializedData
                });

                request.done(function (response, textStatus, jqXHR) {
                    document.getElementById("result").innerHTML = response;
                    // console.log(response);
                });

                request.fail(function (jqXHR, textStatus, errorThrown) {
                    console.error(
                        "The following error occurred: " +
                        textStatus, errorThrown
                    );
                });

                request.always(function () {
                    $inputs.prop("disabled", false);
                });
            } else {
                document.getElementById("result").innerHTML = "";
            }
        });
    </script>
    </body>
    </html>
<?php ob_end_flush(); ?>