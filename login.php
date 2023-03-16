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
        <form action="" method="POST">
          <!-- Username input -->
          <div class="form-group">
            <label for="username">Username *</label>
            <input type="text" name="username" class="form-control" id="username" value="" placeholder="Upper and lowercase, atleast 6 characters." pattern="(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}" title="Upper and lowercase, atleast 6 characters." maxlength="30" required="true">
          </div>
          <!-- Password input -->
          <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" name="password" class="form-control" id="password" value="" placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute" pattern="(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}" title="mindestens einen Gross-, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen, mindestens 8 Zeichen lang,keine Umlaute." maxlength="255" required="true">
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