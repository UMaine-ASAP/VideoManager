<?PHP
session_start();

require_once('controllers/authentication.php');
require_once('controllers/users.php');
require_once('controllers/video.php');


require_once('libraries/helpers.php');
require_once('libraries/Slim/Slim.php');
require_once('libraries/Views/TwigView.php');

TwigView::$twigDirectory = __DIR__ . '/libraries/Twig';

$app = new Slim(array(
	'view' => new TwigView
	));

/**
 *  Render
 * 
 *  Renders the template with given data and global data
 */
function render($templateName, $data, $location='') {
	// Set special global values for every template
	$userData = UserController::getUserDetails(AuthenticationController::GetCurrentUserID());
	
	$GLOBALS['app']->flashNow('location', $location);
	$GLOBALS['app']->flashNow('userData', $userData);
	$GLOBALS['app']->flashNow('web_root', $GLOBALS['web_root']);

	// Render
	$GLOBALS['app']->render($templateName, $data);
}

/** Root directory **/
$app->get('/', function() use ($app) {
	$app->flashKeep();
	if(!AuthenticationController::checkLogin()){
		return redirect('/login');
	}
	else{
		return redirect('/upload');
	}
});

$authenticate = function() use ($app) {
	if(!AuthenticationController::checkLogin()) {
		return redirect('/login');
	}
};

/*********************/
/* Login Features
/*********************/

$app->get('/login', function() use ($app) {
	render('login.html.tpl', array());
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

$app->get('/logout', function() use ($app) {
	AuthenticationController::logout();
	$app->flash('header', 'You have been successfully logged out.');
	return redirect('/login');
});

$app->post('/register', function() use ($app){
	$app->flash('error', 'Registration is currently disabled');
	return redirect('/login');
	exit(1);
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

/*********************/
/* Uploading
/*********************/

$app->get('/upload', $authenticate, function() use ($app) {
	$app->flashKeep();
		render('upload.html.tpl', array(), 'upload');
	
});


/*********************/
/* Video Management
/*********************/
$app->get('/videos(/:category_id)', $authenticate, function($category_id=-1) use ($app) {

	$categories = VideoController::getAllCategories();
	$totalVideoCount = VideoController::getTotalVideoCount();
	$videos = array( 
				array('video_id'=>2, 'title'=>'Video 1', 'upload_date'=>'01/05/2012', 'length'=>'5:02', 'status'=>'visible', 'owner'=>'Test', 'description'=>'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
				array('video_id'=>2, 'title'=>'Video 1', 'upload_date'=>'01/05/2012', 'length'=>'5:02', 'status'=>'visible', 'owner'=>'Test', 'description'=>'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
				array('video_id'=>2, 'title'=>'Video 1', 'upload_date'=>'01/05/2012', 'length'=>'5:02', 'status'=>'visible', 'owner'=>'Test', 'description'=>'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),								
				array('video_id'=>2, 'title'=>'Video 1', 'upload_date'=>'01/05/2012', 'length'=>'5:02', 'status'=>'visible', 'owner'=>'Test', 'description'=>'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
				array('video_id'=>2, 'title'=>'Video 1', 'upload_date'=>'01/05/2012', 'length'=>'5:02', 'status'=>'visible', 'owner'=>'Test', 'description'=>'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'));

	$videos = videoController::getVideosInCategory($category_id);

	// Get category name
	$categoryName = "All Videos";
	if( $category_id != -1) {
		$found = false;	
		foreach( $categories as $category) {
			if( $category['id'] == $category_id) {
				$categoryName = $category['name'];
				$found = true;
				break;
			}
		}
		if( !$found ) {
			$categoryName = "Category not found. Please choose a category on the left.";
		}

	}

	render('videos.html.tpl', array('videos'=>$videos, 'categories'=>$categories, 'selectedCategory'=>$category_id, 'categoryName'=>$categoryName, 'totalVideoCount' => $totalVideoCount), 'videos');
});

$app->get('/my-videos', $authenticate, function() use ($app) {

	$videos = VideoController::getUserVideos(AuthenticationController::getCurrentUserID());
	$videos = array( 
				array('video_id'=>2, 'title'=>'Video 1', 'upload_date'=>'01/05/2012'),
				array('video_id'=>2, 'title'=>'Video 1', 'upload_date'=>'01/05/2012'),
				array('video_id'=>2, 'title'=>'Video 1', 'upload_date'=>'01/05/2012'),								
				array('video_id'=>2, 'title'=>'Video 1', 'upload_date'=>'01/05/2012'));

	render('my-videos.html.tpl', array('videos'=>$videos));
});


$app->get('/edit/:mode/:id', $authenticate, function($mode, $id) use ($app) {

	$video = VideoController::getVideoMeta($id);
	if($mode == "meta"){
		if(VideoController::getVideoOwnerID($id) == AuthenticationController::getCurrentUserID()){
			render('editMeta.html.tpl', array('video'=>$video), 'videos' );
		}
		else {
			$app->flash('error', 'You do not have premission to edit that video');
			return redirect ('/videos');
		}
	}
	else {
		$app->flash('error', 'Invalid Edit Mode');
		return redirect ('/videos');
	}
});




$app->post('/sync', $authenticate, function() use ($app) {
		if(!is_null($_POST['model']))
		{
			$data = json_decode($_POST['model']);
			try
			{
				$dbh = new PDO('mysql:host=' . $GLOBALS['HOST'] . ';dbname='. $GLOBALS['DATABASE'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD']);
		    	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    	$input = array('unique_id' => substr(md5(rand(0, 1000000)), 0, 8), 'user_id' => AuthenticationController::getCurrentUserID(), 'title' => $data->title, 'description' => $data->description, 'mime_type' => $data->type, 'filesize' => $data->size, 'visibility' => $data->visibility);

		    	$statement = $dbh->prepare("INSERT INTO VIDEO_Upload_data (unique_id, owner_id, title, description, visibility, mime_type, filesize, upload_date) VALUES (:unique_id, :user_id, :title, :description, :visibility, :mime_type, :filesize, NOW())");
		    	$statement->execute($input);

		    	$response['id'] = $dbh->lastInsertId();

		    	if(!is_null($data->category) && is_int($data->category)){
		    		try
		    		{
		    			$category_map = array('video_id' => $response['id'], 'category_id' => $data->category);

		    			$statement = $dbh->prepare("INSERT INTO VIDEO_Category_map (video_id, category_id) VALUES (:video_id, :category_id)");
		    			$statement->execute($category_map);
		    		}
		    		catch(PDOException $ex)
					{
						error_log($ex);
						$dbh = null;
						return false;
					}
		    	}
		    	elseif(!is_null($data->category) && is_string($data->category))
		    	{
		    		$category_data = array('name' => $data->category);
		    		$statement = $dbh->prepare("INSERT INTO META_Category (name) VALUES (:name)");
		    		$statement->execute($category_data);

		    		$category_id = $dbh->lastInsertId();

		    		$category_map = array('video_id' => $response['id'], 'category_id'=> $category_id);
		    		$statement = $dbh->prepare("INSERT INTO VIDEO_Category_map (video_id, category_id) VALUES (:video_id, :category_id)");
		    		$statement->execute($category_map);
		    	}
			}
			catch(PDOException $ex)
			{
				error_log($ex);
				$dbh = null;
				return false;
			}

			
			$dbh = null;


			echo json_encode($response);
		}
		else 
		{
			return false;
		}
});


$app->run();
