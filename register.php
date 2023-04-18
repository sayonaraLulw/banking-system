<?php

// TODO - Sessionhandling starten
session_start();
session_regenerate_id(true);

// Datenbankverbindung
include('include/dbconnector.inc.php');

// Initialisierung
$error = $message =  '';
$firstname = $lastname = $email = $username = $password =  '';

// Wurden Daten mit "POST" gesendet?
if ($_SERVER['REQUEST_METHOD'] == "POST") {

  // Vorname ausgefüllt?
  if (isset($_POST['firstname'])) {
    //trim and sanitize
    $firstname = htmlspecialchars(trim($_POST['firstname']));

    //mindestens 1 Zeichen und maximal 30 Zeichen lang
    if (empty($firstname) || strlen($firstname) > 30) {
      $error .= "Please enter a correct first name.<br />";
    }
  } else {
    $error .= "Please enter your first name.<br />";
  }

  // Nachname ausgefüllt?
  if (isset($_POST['lastname'])) {
    //trim and sanitize
    $lastname = htmlspecialchars(trim($_POST['lastname']));

    //mindestens 1 Zeichen und maximal 30 Zeichen lang
    if (empty($lastname) || strlen($lastname) > 30) {
      $error .= "Please enter a correct last surname.<br />";
    }
  } else {
    $error .= "Please enter a surname.<br />";
  }

  // Email ausgefüllt?
  if (isset($_POST['email'])) {
    //trim an sanitize
    $email = htmlspecialchars(trim($_POST['email']));

    //mindestens 1 Zeichen und maximal 100 Zeichen lang, gültige Emailadresse
    if (empty($email) || strlen($email) > 100 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
      $error .= "Please enter a correct email address.<br />";
    }
  } else {
    $error .= "Please enter your email address.<br />";
  }

  // Username ausgefüllt?
  if (isset($_POST['username'])) {
    //trim and sanitize
    $username = htmlspecialchars(trim($_POST['username']));

    //mindestens 1 Zeichen , entsprich RegEX
    if (empty($username) || !preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,30}/", $username)) {
      $error .= "Please enter a correct username.<br />";
    }
  } else {
    $error .= "Please enter a username.<br />";
  }

  // Passwort ausgefüllt
  if (isset($_POST['password'])) {
    //trim and sanitize
    $password = trim($_POST['password']);

    //mindestens 1 Zeichen , entsprich RegEX
    if (empty($password) || !preg_match("/(?=^.{8,255}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)) {
      $error .= "Please enter a correct password.<br />";
    }
  } else {
    $error .= "Please enter a password.<br />";
  }

  // wenn kein Fehler vorhanden ist, schreiben der Daten in die Datenbank
  if (empty($error)) {
    // Password haschen
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Query erstellen
    $query = "Insert into users (firstname, lastname, username, password, email) values (?,?,?,?,?)";
    
    // Query vorbereiten
    $stmt = $mysqli->prepare($query);
    if ($stmt === false) {
      $error .= 'prepare() failed ' . $mysqli->error . '<br />';
    }
    
    // Parameter an Query binden
    if (!$stmt->bind_param('sssss', $firstname, $lastname, $username, $password_hash, $email)) {
      $error .= 'bind_param() failed ' . $mysqli->error . '<br />';
    }

    // Query ausführen
    if (!$stmt->execute()) {
      $error .= 'execute() failed ' . $mysqli->error . '<br />';
    }

    // kein Fehler!
    if (empty($error)) {
      $message .= "The data was successfully written to the database<br/ >";
      // Felder leeren und Weiterleitung auf anderes Script: z.B. Login!
      $username = $password = $firstname = $lastname = $email =  '';
      // Verbindung schliessen
      $mysqli->close();
      // Weiterleiten auf login.php
      header('Location: login.php');
      // beenden des Scriptes
      exit();
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
          <a class="nav-item nav-link " href="login.php">Login</a>
          <a class="nav-item nav-link active" href="register.php">Register</a>
      </div>
    </nav>
  <div class="container">
    <h1>Register</h1>
    <?php
    // Ausgabe der Fehlermeldungen
    if (!empty($error)) {
      echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
    } else if (!empty($message)) {
      echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
    }
    ?>
    <form action="" method="post">
      <!-- vorname -->
      <div class="form-group">
        <label for="firstname">First name *</label>
        <input type="text" name="firstname" class="form-control" id="firstname" value="<?php echo $firstname ?>" placeholder="Enter your first name." maxlength="30" required="true">
      </div>
      <!-- nachname -->
      <div class="form-group">
        <label for="lastname">Surname *</label>
        <input type="text" name="lastname" class="form-control" id="lastname" value="<?php echo $lastname ?>" placeholder="Enter your surname." maxlength="30" required="true">
      </div>
      <!-- email -->
      <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" name="email" class="form-control" id="email" value="<?php echo $email ?>" placeholder="Enter your email address." maxlength="100" required="true">
      </div>
      <!-- benutzername -->
      <div class="form-group">
        <label for="username">Username *</label>
        <input type="text" name="username" class="form-control" id="username" value="<?php echo $username ?>" placeholder="Upper and lower case letters, min 6 characters." pattern="(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}" title="Upper and lower case letters, min 6 characters." maxlength="30" required="true">
      </div>
      <!-- password -->
      <div class="form-group">
        <label for="password">Password *</label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Upper and lower case letters, numbers, special characters, min. 8 characters, no umlauts" pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="at least one upper case letter, one lower case letter, one number and one special character, at least 8 characters long,no umlauts." maxlength="255" required="true">
      </div>
      <!-- Send / Reset -->
      <button type="submit" name="btn-info" value="submit" class="btn btn-primary">Send</button>
      <button type="reset" name="btn btn-warning" value="reset" class="btn btn-secondary">Reset</button>
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