<?PHP


require_once('libraries/settings.php');

// Libraries
require_once('libraries/database.php');

// Controllers
require_once('authentication.php');

class VideoController 
{

	static function getVideosInCategory($category_id = -1) {
		if( ! AuthenticationController::checkLogin()) return array();

		// Return videos from a specific category
	    if( $category_id != -1) {
	    	$data = array("category_id" => $category_id);
		   	$statement 	= "SELECT videos.video_id as id, videos.filesize as thumbnail, videos.title as title, videos.description as description, videos.filesize as length, videos.visibility as visibility, videos.upload_date as upload_date, users.username as owner, users.user_id as owner_id  FROM VIDEO_Upload_data as videos, VIDEO_Category_map as VCmap, AUTH_Users as users WHERE VCmap.category_id = :category_id AND VCmap.video_id = videos.video_id AND videos.owner_id = users.user_id";

		   	return Database::query($statement, $data);
	    } else {
	      	// Get all videos
		   	$statement = "SELECT videos.video_id as id, videos.filesize as thumbnail, videos.title as title, videos.description as description, videos.filesize as length, videos.visibility as visibility, videos.upload_date as upload_date, users.username as owner, users.user_id as owner_id FROM VIDEO_Upload_data as videos, AUTH_Users as users WHERE videos.owner_id = users.user_id";
		   	return Database::query($statement);
	    } 
	}

	static function getTotalVideoCount() {
		if(AuthenticationController::checkLogin()){
			try 
	        {
	        	$dbh = new PDO('mysql:host=' . $GLOBALS['HOST'] . ';dbname='. $GLOBALS['DATABASE'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD']);
	        	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        	$statement = $dbh->prepare("SELECT count(*) as count FROM VIDEO_Upload_data");
	        	$statement->execute();

	        	$row = $statement->fetchAll();

	            return $row[0]['count'];
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

	static function getAllCategories() {

		if(AuthenticationController::checkLogin()){

			try 
	        {
	        	$dbh = new PDO('mysql:host=' . $GLOBALS['HOST'] . ';dbname='. $GLOBALS['DATABASE'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD']);
	        	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        	$statement = $dbh->prepare("SELECT META_Category.category_id as id, META_Category.name as name, count(VCMap.video_id) as video_count FROM VIDEO_Category_map as VCmap RIGHT JOIN META_Category ON VCmap.category_id=META_Category.category_id GROUP BY META_Category.category_id ORDER BY name ASC");
	        	$statement->execute();

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

	static function getUserVideos($user_id){

		if(AuthenticationController::checkLogin()){
			try 
	        {
	        	$dbh = new PDO('mysql:host=' . $GLOBALS['HOST'] . ';dbname='. $GLOBALS['DATABASE'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD']);
	        	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        	$data = array("user_id" => $user_id);

	        	$statement = $dbh->prepare("SELECT title, upload_date, video_id, owner_id FROM VIDEO_Upload_data WHERE owner_id = :user_id ORDER BY video_id DESC");
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

	//TODO - Multple Category Support
	static function getVideoMeta($video_id){
	    $data = array("video_id" => $video_id);
	    $statement = "SELECT a.*, category.name as category FROM VIDEO_Upload_data a LEFT JOIN VIDEO_Category_map b ON a.video_id = b.video_id LEFT JOIN META_Category category ON b.category_id = category.category_id WHERE a.video_id = :video_id LIMIT 1";

	    $result = Database::query($statement, $data);
	    return $result[0];

	}
}