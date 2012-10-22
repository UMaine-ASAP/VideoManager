<?php
require_once __dir__ . "/settings.php";

class Database {

	private static $dbh;

	private static function startDB() {
		if( Database::$dbh == null ) {
	        	Database::$dbh = new PDO('mysql:host=' . $GLOBALS['HOST'] . ';dbname='. $GLOBALS['DATABASE'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD'], array(\PDO::MYSQL_ATTR_INIT_COMMAND =>  'SET NAMES utf8'));
	        	Database::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	}

	static function query($query_string, $data = array()) {
		Database::startDB();

		try 
	       {
		       	$statement = Database::$dbh->prepare($query_string);
		       	$statement->execute($data);
	
		       	$result = $statement->fetchAll();

	           return $result;
		}
		catch(PDOException $ex)
		{
			error_log($ex);
			Database::$dbh = null;
			return false;
		}

	}

	function __deconstruct__() {
		print "destroying DB";
		Database::$dbh = null;
	}

}
