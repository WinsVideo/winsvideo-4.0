<?php 
require_once("includes/config.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php"); 
require_once("includes/classes/FormSanitizer.php"); 

$account = new Account($con);

if(isset($_POST["submitButton"])) {
    
    $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);

    $wasSuccessful = $account->login($username, $password);

    if($wasSuccessful) {
        $_SESSION["userLoggedIn"] = $username;
        header("Location: index.php");
    }

}

function getInputValue($name) {
    if(isset($_POST[$name])) {
        echo $_POST[$name];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WinsVideo - Login</title>

    <!-- css file -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <div class="signInContainer">
        <div class="loginForm">
            <h1>Login</h1>
            <hr>
            <form action="login.php" method="POST">
                <?php echo $account->getError(Constants::$loginFailed); ?>
                <label for="username">Username</label>
                <input class="form-control" type="text" name="username" placeholder="Username" value="<?php getInputValue('username'); ?>" required autocomplete="off">
                <label for="password">Password</label>
                <input class="form-control" type="password" name="password" placeholder="Password" required>
                <br><input type="submit" name="submitButton" value="Login" class="btn btn-primary">
            </form>
            <a href="register.php">Don't have an account? Sign up here</a>
        </div>
    </div>
</body>
</html>