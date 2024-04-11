<?php
/**
 * Following this page: https://www.allphptricks.com/forgot-password-recovery-reset-using-php-and-mysql/
 */

/* Note: Check that a used with that mail exists */
/* Note: Send mail to user with token link thingy */
require_once __DIR__ . "/../dbClasses/User.php";
require_once __DIR__ . "/../dbClasses/PasswordToken.php";

session_start();

require_once __DIR__ . "/../../tools/monolog.php";

$systemLogger = createLogger("glemtPassord::action");
$systemLogger->pushHandler($systemFileHandler);

$validationLogger = createLogger("glemtPassord::action");
$validationLogger->pushHandler($validationFileHandler);

// Check the CSRF token
$token = filter_input(INPUT_POST, 'authenticity_token', FILTER_SANITIZE_STRING);
if (! $token || $token !== $_SESSION['CSRF_token']) {
    $validationLogger->alert("CSRFToken is invalid!");

    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
}

if (! empty($_POST["email"])) {
    $_SESSION["errorMessage"] = "";

    // Clean email input from user
    $email = $_POST["email"];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (! $email) {
        $validationLogger->alert("Email not formatted correctly!", ["email" => $email]);
        $_SESSION["errorMessage"] .= "Ugyldig e-post oppgitt. ";
    } else {
        $user = new User();
        $userExists = $user->getUserByEmail($email);
        if (! $userExists) {
            $_SESSION["errorMessage"] .= "En e-post med instruksjoner for å endre passordet ditt er sendt. ";
        }
    }

    // If errors then send back to page with error message
    if (! empty($_SESSION["errorMessage"])) {
        header("Location: ./");
        exit;
    }

    $expFormat = mktime(
        date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y")
    );
    $expDate = date("Y-m-d H:i:s", $expFormat);
    $key = md5((string)(2418 * 2) . $email);
    $addKey = substr(md5(uniqid(rand(), 1)), 3, 10);
    $key = $key . $addKey;

    $passToken = new PasswordToken();
    $isCreated = $passToken->createPasswordResetToken($email, $key, $expDate);
    if (! $isCreated) {
        $systemLogger->notice("Failed during creation of token", ["email" => $email]);

        $_SESSION["errorMessage"] .= "Feilet under oppretting av token. ";
        header("Location: ./");
        exit;
    }

?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Glemt Passord?</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>NB! Dette er en STUB som bare viser mailen som "skal" bli sendt til brukeren. Dette er fordi vi ikke tar oss tid til å implementere en mailserver.</h1>
        <h3>E-post sendt til <?php echo $email; ?></h3>
        <div class="emailbox">
            <p>Venligst klikk på følgende link for å resette ditt passord.</p>
            <br>
            <p><a <?php echo 'href="./reset_password.php?key='.$key.'&email='.$email.'&action=reset"'; ?> rel="noopener noreferrer" target="_blank">Klikk for å resette passord</a></p>
            <br>
            <p>Husk å kopiere hele lenken til nettleseren din. Linken vil utløpe etter 1 dag av sikkerhetsmessig grunner.</p>
            <p>Hvis du ikke ba om denne e-posten med glemt passord, ingen handling er nødvendig, vil ikke passordet ditt tilbakestilles. Det kan imidlertid være lurt å logge på kontoen din og endre sikkerhetspassordet slik noen kanskje har gjettet det.</p>
            <br>
            <p>Fra, Datasikkerhet Projekt Gruppe 3</p>
        </div>
    </body>
<?php
}
?>

