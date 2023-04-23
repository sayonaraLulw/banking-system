<?php

session_start();
session_regenerate_id(true);

// Datenbankverbindung
include('include/dbconnector.inc.php');


if (!isset($_SESSION['loggedin'])) {
    header('Location: notLoggedIn.php');
}
$username = $_SESSION['username'];

// Initialisierung
$error = $message =  '';
$password =  '';
// Wurden Daten mit "POST" gesendet?

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty($_POST['btnDeleteAccount'])) {
        // Query erstellen
        $query = "DELETE FROM users WHERE username= ?";

        // Query vorbereiten
        $stmt = $mysqli->prepare($query);
        if ($stmt === false) {
            $error .= 'prepare() failed ' . $mysqli->error . '<br />';
        }

        // Parameter an Query binden
        if (!$stmt->bind_param("s", $username)) {
            $error .= 'bind_param() failed ' . $mysqli->error . '<br />';
        }
        // Query ausführen
        if (!$stmt->execute()) {
            $error .= 'execute() failed ' . $mysqli->error . '<br />';
        }

        // kein Fehler!
        if (empty($error)) {
            // Verbindung schliessen
            $mysqli->close();
            header('Location: register.php');
            exit();
        } else {
            $error .= "Error delete Account";
        }
    }
}





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Banking System</title>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="login.php">Banking System</a>
        <div class="navbar-nav">
            <a class="nav-item nav-link" href="home.php">Home</a>
            <a class="nav-item nav-link" href="logout.php">Logout</a>
            <a class="nav-item nav-link" href="delete.php">Delete Account</a>
        </div>
    </nav>
    <div class="container">
        <h1>Account</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Delete account</h4>
                    </div>
                    <div class="panel-body">

                        <?php
                        if ($error != '') {
                            echo '<div class="alert alert-danger"><strong>Error: </strong> ' . $error . '</div>';
                        }
                        if ($success != '') {
                            echo '<div class="alert alert-success"><strong>Success: </strong> ' . $success . '</div>';
                        }
                        ?>
                        <form action="delete.php" method="post">
                            <div class="form-group">
                                <input style="background-color:Tomato; border:1px solid Tomato;" type="submit" name="btnDeleteAccount" class="btn btn-primary" value="Delete Account" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="bg-dark fixed-bottom text-white text-center">
        <a>© 2023 Copyright O.Lam & F.Strub</a>
    </footer>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>