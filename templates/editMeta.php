<?PHP
	require_once('controllers/authentication.php');
	require_once('controllers/video.php');


echo VideoController::getVideoOwnerID($id);

?>