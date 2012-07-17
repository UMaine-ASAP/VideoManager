<?PHP

require_once('libraries/settings.php');
require_once('authentication.php');



class UserController
{
	static function getUserDetails($user_id)
	{
		if(AuthenticationController::checkLogin())
		{
			$dbh = new PDO('mysql:host=' . $GLOBALS['HOST'] . ';dbname='. $GLOBALS['DATABASE'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD']);
        	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        	$data = array("user_id" => $user_id);

        	$statement = $dbh->prepare("SELECT * FROM users WHERE user_id = :user_id");
        	$statement->execute($data);

        	return $statement->fetch(PDO::FETCH_ASSOC);

		}
		return false;
	}
}

?>