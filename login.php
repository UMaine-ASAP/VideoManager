<?PHP
session_start();
require_once('libraries/authentication.php');

AuthenticationController::attemptLogin($_POST['username'], $_POST['password']);

header("Location: index.php");
?>