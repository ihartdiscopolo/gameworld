<?php
require("functions.php");
signup();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Sign-Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>

<body class="login">
    <?php
    htmlHead();
    ?>
    <main class = banner>
        <h1>SignUp Form</h1>
        <form name="form" action="signup.php" method="post">
            <input type="text" id="user" name="user" placeholder="Enter Username" required><br><br>
            <input type="email" id="email" name="email" placeholder="Enter E-mail" required><br><br>
            <input type="password" id="pass" name="pass" placeholder="Create Password" required><br><br>
            <input type="password" id="cpass" name="cpass" placeholder="Retype Password" required><br><br>
            <input class="btn" type="submit" value="Sign Up" name="submit">
            <a href="login.php" class="back-to-login">Go to Login</a>
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <?php
    htmlFooter();
    ?>
</body>
</html>