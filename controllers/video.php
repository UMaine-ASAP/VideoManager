<?PHP


require_once('libraries/settings.php');
require_once('authentication.php');

class VideoController 
{
	static function getUserVideos($user_id){

		if(AuthenticationController::checkLogin()){
			try 
	        {
	        	$dbh = new PDO('mysql:host=' . $GLOBALS['HOST'] . ';dbname='. $GLOBALS['DATABASE'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD']);
	        	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        	$data = array("user_id" => $user_id);

	        	$statement = $dbh->prepare("SELECT * FROM VIDEO_Upload_data WHERE owner_id = :user_id ORDER BY video_id DESC");
	        	$statement->execute($data);

	        	$row = $statement->fetchAll();

	            return $row;
			}
			catch(PDOException $ex)
			{
				error_log($ex);
				$dbh = null;
				return false;
			}

			$dbh = null;
		}
	}
	static function getVideoOwnerID($video_id){
		if(AuthenticationController::checkLogin()){
			try 
	        {
	        	$dbh = new PDO('mysql:host=' . $GLOBALS['HOST'] . ';dbname='. $GLOBALS['DATABASE'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD']);
	        	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        	$data = array("video_id" => $video_id);

	        	$statement = $dbh->prepare("SELECT owner_id FROM VIDEO_Upload_data WHERE video_id = :video_id");
	        	$statement->execute($data);

	        	$row = $statement->fetch(PDO::FETCH_ASSOC);

	            return $row['owner_id'];
			}
			catch(PDOException $ex)
			{
				error_log($ex);
				$dbh = null;
				return false;
			}

			$dbh = null;
		}
	}
}