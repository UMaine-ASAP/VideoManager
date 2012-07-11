<?PHP
session_start();

require_once('controllers/authentication.php');

require_once('libraries/Slim/Slim.php');


if (!AuthenticationController::checkLogin())
{

?>
<form method="post" action="login.php">
	<label for="username">Username: </label><input type="text" name="username"><br>
	<label for="password">Password: </label><input type="password" name="password"><br>
	<input type="submit" value="Hamburger Time">

</form>

<?PHP
}
else {

$app = new Slim();

$app->get('/', function(){
	include('templates/main.php');
});

$app->run();

}
?>