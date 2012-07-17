	<?
	require_once('controllers/authentication.php');
	require_once('controllers/video.php');
	?>

	<html>
	
	<? require_once('templates/header.php'); ?>

	<body>

	<? require_once('templates/navbar.php'); ?>


	<div class="container" style="height: 100%;">

		<div class="videos pull-right" style="width: 900px;">
			<div class="well" style="position: relative;">
				<button class="btn btn-danger" style="position: absolute; right: 90px; bottom: 10px;"><i class="icon-remove icon-white"></i> Delete</button>
				<div class="btn-group" style="position: absolute; right: 10px; bottom: 10px;">
					<button class="btn dropdown-toggle" data-toggle="dropdown">
						Modify
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li><a href="#">Edit Meta Data</a></li>
						<li><a href="#">Reconvert</a></li>
					</ul>
				</div>

				<div class="row">
				<div class="span 3">
				<img src="http://placekitten.com/230/110">
				</div>

					<div class="span4">
						<h3>This is a video title</h3>
					</div>
					<div class="span2">
						Categories
					</div>
				</div>
			</div>
			<div class="well">
				<div class="row">
				<div class="span 3">
				<img src="http://placekitten.com/230/110">
				</div>

					<div class="span4">
						<h3>This is a video title</h3>
					</div>
					<div class="span2">
						Categories
					</div>
				</div>
			</div>
			<div class="well">
				<div class="row">
				<div class="span 3">
				<img src="http://placekitten.com/230/110">
				</div>

					<div class="span4">
						<h3>This is a video title</h3>
					</div>
					<div class="span2">
						Categories
					</div>
				</div>
			</div>
			<div class="well">
				<div class="row">
				<div class="span 3">
				<img src="http://placekitten.com/230/110">
				</div>

					<div class="span4">
						<h3>This is a video title</h3>
					</div>
					<div class="span2">
						Categories
					</div>
				</div>
			</div>
			<div class="well">
				<div class="row">
				<div class="span 3">
				<img src="http://placekitten.com/230/110">
				</div>

					<div class="span4">
						<h3>This is a video title</h3>
					</div>
					<div class="span2">
						Categories
					</div>
				</div>
			</div>
			<div class="well">
				<div class="row">
				<div class="span 3">
				<img src="http://placekitten.com/230/110">
				</div>

					<div class="span4">
						<h3>This is a video title</h3>
					</div>
					<div class="span2">
						Categories
					</div>
				</div>
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