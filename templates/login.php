<html>
	
	<? require_once('templates/header.php'); ?>

	<body>

	<? require_once('templates/navbar.php'); ?>

	<!--<?php
	    echo "<pre>";
    	print_r($_SESSION);
    	echo "</pre>";
    	
    ?>-->
	<div class="container" style="height: 100%;">

		<div class="page-header">
			<h1>Login to Uploader:</h1>
		</div>

		<?php

		if(isset($_SESSION['slim.flash']['header']))
    	{
    		echo "<div class=\"alert alert-success\">";
    		echo $_SESSION['slim.flash']['header'];
    		echo "</div>";
    	}

		?>
		<div class="well">
			<div class="">
				<form method="post" action="login">
				<label for="username">Username: </label><input type="text" name="username"><br>
				<label for="password">Password: </label><input type="password" name="password"><br>
				<input type="submit" value="Hamburger Time">

				</form>
			</div>
		</div>
	</div>

<!--Scripts that are dependent on the DOM-->
	<script src="js/jquery.js"></script>
	<script src="js/underscore.js"></script>
	<script src="js/backbone.js"></script>
	<script src="js/upload.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>

	</body>
</html>
