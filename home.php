<?php

//Datenbankverbindung
include('include/dbconnector.inc.php');

session_start();
session_regenerate_id(true);

$calculated_balance = $money_value = $error = $message = '';
$username = $_SESSION['username'];
echo $balance = '';


if(isset($_SESSION['loggedin'])){
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
    if ($row = $result->fetch_assoc()) {
  
    $balance = $row['money'];
    }
    } else {

    header('Location: notLoggedIn.php');
}    




if (!empty($_POST['btn-deposit'])) {
    if ($_POST['money_value'] == '') {
        $error = 'Please enter a figure!';
    } elseif ($_POST['money_value'] == '0') {
        $error = 'Please enter a figure over zero!';
    } elseif (empty($error)){
    $money_value = trim($_POST['money_value']);
    $calculated_balance = $balance + $money_value;
    $message = 'Calculated new Balance';
    }
}


if (!empty($_POST['btn-withdraw'])) {
  if ($_POST['money_value'] == '') {
      $error = 'Please enter a figure!';
  } elseif ($_POST['money_value'] == '0') {
      $error = 'Please enter a figure over zero!';
  } elseif (empty($error)){
  $money_value = trim($_POST['money_value']);
  $calculated_balance = $balance - $money_value;
  $message = 'Calculated new Balance';
  }
}


if (!empty($_POST['btn-reset'])) {
  $calculated_balance = 0;
  $message = 'Calculated new Balance';
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {

  if (empty($error)|| $message == 'Calculated new Balance') {
      // Query erstellen
      $query = "UPDATE users SET money=$calculated_balance WHERE username= ?";;
      
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
      }else{
        // kein Fehler!
        if (empty($error)) {
          $calculated_balance =  '';
          // Verbindung schliessen
          $mysqli->close();
          header('Location: home.php');
        } else {
          $error .= "Error update Balance";
        }
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
          <a class="nav-item nav-link" href="account.php">Account</a>
          <a class="nav-item nav-link" href="logout.php">Logout</a>
      </div>
    </nav>

    </nav>
    <div class="container">
        <h1>Account</h1>
    <div class="row">
        <div class="col-md-6">
                <div class="panel panel-default">
                <div class="panel-heading"> 
                <h4>Account balance <?=$balance?> CHF</h4>
              </div>
                <div class="panel-body">
        <?php
        // Ausgabe der Fehlermeldungen
        if (!empty($error)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
        } else if (!empty($message)) {
            echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
        }
        
        ?>

        <!-- Kontostand ändern -->
        <form action="" method="POST">
          <label for="deposit">Deposit or Withdraw Money</label>
          <input type="number" name="money_value" class="form-control" id="modvalue" value="0" maxlength="30"> <br>
          <button type="submit" name="btn-deposit" value="submit" class="btn btn-primary">Deposit</button>
          <button style="background-color:Tomato; border:1px solid Tomato;" type="submit" name="btn-withdraw" value="submit" class="btn btn-primary">Withdraw</button>
          <button type="submit" name="btn-reset" value="reset" class="btn btn-secondary">Reset</button>
        </form>
        
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
