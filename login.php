<?PHP
session_start();
require_once('controllers/authentication.php');

AuthenticationController::attemptLogin($_POST['username'], $_POST['password']);

header("Location: index.php");
?>