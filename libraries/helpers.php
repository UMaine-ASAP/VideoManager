<?PHP

function redirect($destination)
{
	$GLOBALS['app']->redirect($GLOBALS['web_root'] . $destination);
}

?>