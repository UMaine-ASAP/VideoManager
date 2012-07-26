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

			<table class="table table-striped" id="video_table">
				<tr>
					<td style="position: relative">
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

						<div style="width: 100%;">
							<div style="float: left">
								<img src="http://placekitten.com/230/110">
							</div>
							<div style="float: left; padding-left: 8px;">
								<h3>This is a video title</h3>
							</div>
							<div style="float: right;">
								Categories
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td style="position: relative">
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

						<div style="width: 100%;">
							<div style="float: left">
								<img src="http://placekitten.com/230/110">
							</div>
							<div style="float: left; padding-left: 8px;">
								<h3>This is a video title</h3>
							</div>
							<div style="float: right;">
								Categories
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td style="position: relative">
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

						<div style="width: 100%;">
							<div style="float: left">
								<img src="http://placekitten.com/230/110">
							</div>
							<div style="float: left; padding-left: 8px; width: 50%;">
								<h3>This is a video title</h3>
								<p class="description">Well, the way they make shows is, they make one show. That show's called a pilot. Then they show that show to the people who make shows, and on the strength of that one show they decide if they're going to make more shows.</p>
							</div>
							<div style="float: right;">
								Categories
							</div>
						</div>
					</td>
				</tr>
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