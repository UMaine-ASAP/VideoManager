<?PHP
session_start();

require_once('controllers/authentication.php');
require_once('controllers/users.php');

require_once('libraries/helpers.php');
require_once('libraries/Slim/Slim.php');

$app = new Slim();

$app->get('/', function() use ($app) {
	if(!AuthenticationController::checkLogin()){
		return redirect('/login');
	}
	else{
		include('templates/upload.php');
	}
});

$app->get('/upload', function() use ($app) {
	if(!AuthenticationController::checkLogin()){
		return redirect('/login');
	}
	else {
		include('templates/upload.php');
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
	if ($_POST['username'] != "" && $_POST['password'] != "" &&
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

$app->post('/register', function() use ($app){
	if($_POST['username'] == "" || $_POST['password'] == "" || $_POST['email'] == "" || $_POST['first_name'] == "" || $_POST['last_name'] == "")
	{
		$app->flash('error', 'All fields are required.');
		return redirect('/login');
	}
	else
	{
		if($hash = AuthenticationController::createHash($_POST['password']))
		{
			try 
		    {
        		$dbh = new PDO('mysql:host=' . $GLOBALS['HOST'] . ';dbname='. $GLOBALS['DATABASE'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD']);
		    	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    	$data = array('username' => $_POST['username'], 'password' => $hash, 'email' => $_POST['email'], 'first' => $_POST['first_name'], 'last' => $_POST['last_name']);

		    	$statement = $dbh->prepare("INSERT INTO AUTH_Users (username, password, first, last, email) VALUES (:username, :password, :first, :last, :email)");
		    	$statement->execute($data);
			}
			catch(PDOException $ex)
			{
				error_log($ex);
				$dbh = null;
				return false;
			}

			$dbh = null;

			return redirect('/');
		}
	}

});

$app->post('/sync', function() use ($app) {

	if(!AuthenticationController::checkLogin())
	{
		return redirect('/login');
	}
	else 
	{
		if(!is_null($_POST['model']))
		{
			$data = json_decode($_POST['model']);
			try
			{
				$dbh = new PDO('mysql:host=' . $GLOBALS['HOST'] . ';dbname='. $GLOBALS['DATABASE'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD']);
		    	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    	$data = array('unique_id' => substr(md5(rand(0, 1000000)), 0, 8), 'user_id' => AuthenticationController::getCurrentUserID(), 'title' => $data->title, 'description' => $data->description, 'mime_type' => $data->type, 'filesize' => $data->size);

		    	$statement = $dbh->prepare("INSERT INTO VIDEO_Upload_data (unique_id, owner_id, title, description, mime_type, filesize) VALUES (:unique_id, :user_id, :title, :description, :mime_type, :filesize)");
		    	$statement->execute($data);
			}
			catch(PDOException $ex)
			{
				error_log($ex);
				$dbh = null;
				return false;
			}

			$response['id'] = $dbh->lastInsertId();
			$dbh = null;


			echo json_encode($response);
		}
		else 
		{
			return false;
		}
	}
});

$app->get('/videos', function() use ($app) {
	include('templates/videos.php');
});


$app->run();

?>