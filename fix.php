<?PHP
$dbh = new PDO('mysql:host=localhost;dbname=blackbox', 'root', 'asap4u2u');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$statement = $dbh->prepare("SELECT video_id FROM VIDEO_Upload_data");
$statement->execute();

$results = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $result) {

	$dbh2 = new PDO('mysql:host=localhost;dbname=blackbox', 'root', 'asap4u2u');
	$dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$data = array('video_id' => $result['video_id']);
	$new_statement = $dbh2->prepare("INSERT INTO CONVERSION_Progress (video_id, toConvert, inProgress, toTransfer, toDelete, onMarcel) VALUES (:video_id, 0, 0, 0, 0, 0)");
	$new_statement->execute($data);
}
?>