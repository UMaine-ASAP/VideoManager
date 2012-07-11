<?PHP
session_start();

require_once('controllers/authentication.php');

require_once('libraries/helpers.php');
require_once('libraries/Slim/Slim.php');

$app = new Slim();

$app->get('/', function() use ($app) {
	if(!AuthenticationController::checkLogin()){
		return redirect('/login');
	}
	else{
		include('templates/main.php');
	}
});

$app->get('/logout', function() use ($app) {
	AuthenticationController::logout();
	$app->flash('header', 'You have been successfully logged out.');
	return redirect('/login');
});

$app->get('/login', function() {
	include('templates/login.php');
});

$app->post('/login', function() use ($app){
	if (isset($_POST['username']) && isset($_POST['password']) &&
		AuthenticationController::attemptLogin($_POST['username'], $_POST['password']))
	{	// Success!
		return redirect('/');
	}
	else
	{	// Fail :(
		$app->flash('error', 'Username or password was incorrect.');
		return redirect('/login');
	}
});


$app->run();

?>