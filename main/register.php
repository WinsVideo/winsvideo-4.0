<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include("includes/config.php");
    include("includes/classes/Account.php");
    include("includes/classes/FormSanitizer.php");
    include("includes/classes/Constants.php");

    $account = new Account($con);

    if(isset($_POST["submitButton"])) {

        $displayName = FormSanitizer::sanitizeFormString($_POST["displayName"]);
        $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
        $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
        $password2 = FormSanitizer::sanitizeFormPassword($_POST["password2"]);
        $email = FormSanitizer::sanitizeFormEmal($_POST["email"]);
        $email2 = FormSanitizer::sanitizeFormEmal($_POST["email2"]);

        $success = $account->register($displayName, $username, $password, $password2, $email, $email2);

        if($success) {
            $_SESSION["userLoggedIn"] = $username;
            header("Location: https://winsvideo.net");
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
    <title>WinsVideo - Register</title>

    <!-- css file -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>

    <div class="signInContainer">
        <div class="column">
            <h1>Register</h1>
                <form action="register.php" method="POST">                 
                    <?php echo $account->getError(Constants::$displayNameCharacters); ?>
                    <input type="text" name="displayName" placeholder="Display Name" value="<?php getInputValue('displayName'); ?>" autocomplete="off" required>
                
                    <?php echo $account->getError(Constants::$usernameCharacters); ?>
                    <?php echo $account->getError(Constants::$usernameTaken); ?>
                    <input type="text" name="username" placeholder="Username" autocomplete="off" value="<?php getInputValue('username'); ?>" required>
                
                    <?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
                    <?php echo $account->getError(Constants::$emailInvalid); ?>
                    <?php echo $account->getError(Constants::$emailTaken); ?>
                    <input type="email" name="email" placeholder="Email" autocomplete="off" value="<?php getInputValue('email'); ?>" required>
                    <input type="email" name="email2" placeholder="Confirm email" autocomplete="off" value="<?php getInputValue('email2'); ?>" required>
                                
                    <?php echo $account->getError(Constants::$passwordsDoNotMatch); ?>
                    <?php echo $account->getError(Constants::$passwordNotAlphanumeric); ?>
                    <?php echo $account->getError(Constants::$passwordLength); ?>
                    <input type="password" name="password" placeholder="Password" autocomplete="off" required>
                    <input type="password" name="password2" placeholder="Confirm password" autocomplete="off" required>
                
                    <input type="submit" name="submitButton" value="Register">
                </form>
        </div>
    </div>

    
    
</body>
</html>