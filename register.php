<?PHP

require_once('libraries/authentication.php');

if($_POST){
	if($hash = AuthenticationController::createHash($_POST['password'])){
	   	try 
	    {
	    	$dbh = new PDO("mysql:host=$HOST;dbname=$DATABASE", $USERNAME, $PASSWORD);
	    	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    	$data = array('username' => $_POST['username'], 'password' => $hash);

	    	$dbh->beginTransaction();
	    	$statement = $dbh->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
	    	$statement->execute($data);
	    	echo $dbh->commit();
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
else {


?>

<form method="post" action="register.php">
	Register Up In HERE <Br>
	<label for="username">Username: </label><input type="text" name="username"><br>
	<label for="password">Password: </label><input type="password" name="password"><br>
	<input type="submit" value="Hamburger Time">

</form>

<?PHP
}
?>