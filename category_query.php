<?PHP
require_once('libraries/settings.php');

	try 
		    {
        		$dbh = new PDO('mysql:host=' . $GLOBALS['HOST'] . ';dbname='. $GLOBALS['DATABASE'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD']);
		    	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    	$data = array('query_string' => $_GET['q'] . '%');

		    	$statement = $dbh->prepare("SELECT * FROM META_Category WHERE name LIKE :query_string LIMIT 10");
		    	$statement->execute($data);

		    	$row = $statement->fetchAll();

		    	if(!is_array($row)){
                	return false;
            	}
			}
	catch(PDOException $ex)
			{
				error_log($ex);
				$dbh = null;
				return false;
			}

foreach($row as $value){
	if($value['name'] == $_GET['q']){
		header('Content-type: application/json');
		echo json_encode($row);
		exit;
	}
}
	$queryArray['category_id'] = '-1';
	$queryArray['name'] = $_GET['q'];

	array_unshift($row, $queryArray);

	header('Content-type: application/json');
	echo json_encode($row);

?>