<?php

session_start();
session_regenerate_id(true);

// Datenbankverbindung
include('include/dbconnector.inc.php');


if(!isset($_SESSION['loggedin'])){
    header('Location: notLoggedIn.php');
}
$username = $_SESSION['username'];

// Initialisierung
$error = $message =  '';
$password =  '';
// Wurden Daten mit "POST" gesendet?


if (!empty($_POST['btnChangePassword'])) {
    if ($_POST['current_password'] == '') {
        $error = 'Current Password field is required!';
    } elseif ($_POST['new_password'] == '') {
        $error = 'New Password field is required!';
    } elseif ($_POST['confirm_new_password'] == '') {
        $error = 'Please confirm your new password!';
    } elseif ($_POST['new_password'] != $_POST['confirm_new_password']) {
        $error = 'Password confirmation does not match with new password!';
    } elseif ($_POST['current_password'] == $_POST['new_password']) {
        $error = 'New Password and current password can not be the same!';
    }
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
if (empty($error)) {
    // Query erstellen
    $query = "SELECT id, username, password from users where username = ?";
    
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

    $password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);

        // Passwort ok?
        if (password_verify($password, $row['password'])) {

                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                // Query erstellen
                $query = "UPDATE users SET password=(?) WHERE username='$username'"; 
                
                // Query vorbereiten
                $stmt = $mysqli->prepare($query);
                if ($stmt === false) {
                $error .= 'prepare() failed ' . $mysqli->error . '<br />';
                }
                
                // Parameter an Query binden
                
                if (!$stmt->bind_param('s', $password_hash)) {
                $error .= 'bind_param() failed ' . $mysqli->error . '<br />';
                }
            
                // Query ausführen
                if (!$stmt->execute()) {
                $error .= 'execute() failed ' . $mysqli->error . '<br />';
                }
            
                // kein Fehler!
                if (empty($error)) {
                $success .= "You changed your Password succesfully please logout and login again<br/ >";
                // Felder leeren und Weiterleitung auf anderes Script: z.B. Login! 
                header('Location: logout.php');
                $current_password =  '';
                // Verbindung schliessen
                $mysqli->close();
                // beenden des Scriptes
                exit();
                }
            
        }else{
            $error = 'Invalid current password, please enter valid password!';
            
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
            <a class="nav-item nav-link" href="home.php">Home</a>
            <a class="nav-item nav-link" href="logout.php">Logout</a> 
        </div>
    </nav>
    <div class="container">
        <h1>Account</h1> 
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading"> 
                        <h4>Change Password</h4></div>
                            <div class="panel-body">

                            <?php
                            if ($error != '') {
                                echo '<div class="alert alert-danger"><strong>Error: </strong> ' . $error . '</div>';
                            }
                            if ($success != '') {
                                echo '<div class="alert alert-success"><strong>Success: </strong> ' . $success . '</div>';
                            }
                            ?>

                                <form action="account.php" method="post">
                                    <div class="form-group">
                                        <input type="password" name="current_password" class="form-control" placeholder="Current Password">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="new_password" class="form-control" placeholder="New Password" pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="at least one upper case letter, one lower case letter, one number and one special character, at least 8 characters long,no umlauts." maxlength="255" required="required">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="confirm_new_password" class="form-control" placeholder="Confirm New Password">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="btnChangePassword" class="btn btn-primary" value="Change Password"/>
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
