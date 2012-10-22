<?PHP

require_once 'FFmpegPHP2/FFmpegAutoloader.php';
require_once 'database.php';


function randomAlphaNum($length){ 

			$rangeMin = pow(36, $length-1); //smallest number to give length digits in base 36 
			$rangeMax = pow(36, $length)-1; //largest number to give length digits in base 36 
			$base10Rand = mt_rand($rangeMin, $rangeMax); //get the random number 
			$newRand = base_convert($base10Rand, 10, 36); //convert it 
		
			return $newRand; //spit it out 

			}


	$srcFile  =  $_POST['file_path'];
	//$destFile = "/var/www/html/upload/videos/". $source;
	$ffmpegPath = "/usr/local/bin/ffmpeg";

	$ffmpegObj = new ffmpeg_movie($srcFile);
	$srcWidth = $ffmpegObj->getFrameWidth();
	$srcHeight = $ffmpegObj->getFrameHeight();
	$srcFPS = $ffmpegObj->getFrameRate();
	$srcAB = intval($ffmpegObj->getAudioBitRate()/1000);
	$srcAR = $ffmpegObj->getAudioSampleRate();
	$srcVC = $ffmpegObj->getVideoCodec();
	$duration = (int)$ffmpegObj->getDuration();

$filename = $_POST['unique_id'];
rename($_POST['file_path'], "/srv/src/marcel2_orig/" . $filename );

$db = new Database;

$data = array("md5" => $_POST['file_md5'], "duration" => $duration, "id" => $_POST['id']);
$querystring = "UPDATE VIDEO_Upload_data SET md5 = :md5, duration = :duration WHERE video_id = :id";
$db::query($querystring, $data);

$data = array("video_id" => $_POST['id']);
$querystring = "INSERT INTO CONVERSION_Progress (video_id, toConvert) VALUES (:video_id, 1)";
$db::query($querystring, $data);