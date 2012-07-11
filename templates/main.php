	<html>
	
	<? require_once('templates/header.php'); ?>

	<body>

	<? require_once('templates/navbar.php'); ?>

	<div class="container" style="height: 100%;">
		<div class="page-header">
			<h1>Upload Files:</h1>
		</div>
		<div class="well" id="upload_list">
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