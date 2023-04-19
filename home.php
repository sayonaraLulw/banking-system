<?php

//Datenbankverbindung
include('include/dbconnector.inc.php');

session_start();
session_regenerate_id(true);

$error = $message = '';
$username = $_SESSION['username'];

if(isset($_SESSION['loggedin'])){
    $message = "Welcome " . $_SESSION['username'];
    
    } else {

    header('Location: notLoggedIn.php');
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
          <a class="nav-item nav-link" href="account.php">Account</a>
          <a class="nav-item nav-link" href="logout.php">Logout</a>
      </div>
    </nav>

    </nav>
    <div class="container">
        <h1>Home</h1>
        <?php
        // Ausgabe der Fehlermeldungen
        if (!empty($error)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
        } else if (!empty($message)) {
            echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
        }
        ?>
        <!-- Kontostand Anzeige -->
        <?php
        $query = "SELECT username, money FROM users WHERE username = ?";

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
        // Daten auslesen
        $result = $stmt->get_result();

        // Userdaten lesen
		    if ($row = $result->fetch_assoc()) {
          echo "<h2>Kontostand: " . $row["money"] . "</h2>";
        }
        ?>

        <!-- Kontostand ändern -->
        <form action="" method="POST">
          <label for="deposit">Geld einzahlen</label>
          <input type="text" name="modvalue" class="form-control" id="modvalue" value="0" maxlength="30"> <br>
          <button type="submit" name="btn-login" value="submit" class="btn btn-primary">Einzahlen</button>
          <button type="reset" name="btn-reset" value="reset" class="btn btn-secondary">Reset</button>
        </form>
        <?php

        // Aktueller Kontostand auslesen
        $query = "SELECT username, money FROM users WHERE username = ?";

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

        // Daten auslesen
        $result = $stmt->get_result();

        // Userdaten lesen
        $currentMoney = $row['money'];

        // Geld einzahlen

        ?>

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
