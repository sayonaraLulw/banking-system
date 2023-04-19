<?php
session_start();
session_regenerate_id(true);


// Datenbankverbindung
include('include/dbconnector.inc.php');

$error = '';
$message = '';
$username = $password = '';


// Formular wurde gesendet und Besucher ist noch nicht angemeldet.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	// username
	if (isset($_POST['username'])) {
		//trim and sanitize
		$username = htmlspecialchars(trim($_POST['username']));

		// Prüfung username
		if (empty($username) || !preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,30}/", $username)) {
			$error .= "The username does not match the required format.<br />";
		}
	} else {
		$error .= "Please enter the username.<br />";
	}
	// password
	if (isset($_POST['password'])) {
		//trim and sanitize
		$password = trim($_POST['password']);
		// passwort gültig?
		if (empty($password) || !preg_match("/(?=^.{8,255}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)) {
			$error .= "The password does not match the required format.<br />";
		}
	} else {
		$error .= "Please enter the password.<br />";
	}

	// kein Fehler
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

			// Passwort ok?
			if (password_verify($password, $row['password'])) {

				// TODO - Session personifizieren
				$_SESSION['username'] = $username;
				$_SESSION['loggedin'] = true;
				// TODO - Session ID regenerieren
				session_regenerate_id(true);
				// TODO - weiterleiten auf admin.php
				header('Location: home.php');
				// TODO - Script beenden
				die();
			} else {
				$error .= "Username or password are incorrect";
			}
		} else {
			$error .= "Username or password are incorrect";
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
          <a class="nav-item nav-link active" href="login.php">Login</a>
          <a class="nav-item nav-link" href="register.php">Register</a>
      </div>
    </nav>

    <div class="container">
      <!-- Login -->
      <div class="login">
        <h1>Login</h1>
        <?php
        // fehlermeldung oder nachricht ausgeben
        if (!empty($message)) {
          echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
        } else if (!empty($error)) {
          echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
        }
        ?>
        <form method="POST">
          <!-- Username input -->
          <div class="form-group">
            <label for="username">Username *</label>
			<input type="text" name="username" class="form-control" id="username"
						value=""
						placeholder="Upper and lowercase, atleast 6 characters."
						pattern="(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}"
						title="Upper and lowercase, atleast 6 characters."
						maxlength="30" 
						required="required">
		</div>
          <!-- Password input -->
          <div class="form-group">
            <label for="password">Password *</label>
		    <input type="password" name="password" class="form-control" id="password"
						placeholder="Upper and lower case letters, numbers, special characters, min. 8 characters, no umlauts"
						pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"
						title="at least one upper case letter, one lower case letter, one number and one special character, at least 8 characters long, no umlauts."
						maxlength="255"
						required="required">
		</div>
          <button type="submit" name="btn-login" value="submit" class="btn btn-primary">Login</button>
          <button type="reset" name="btn-reset" value="reset" class="btn btn-secondary">Reset</button>
        </form>
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