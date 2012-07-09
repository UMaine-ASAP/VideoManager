<html>
	<head>

		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
		<script src="http://localhost:8080/socket.io/socket.io.js"></script>

		<style type="text/css">
			body {
				padding-top: 60px;
				padding-bottom: 40px;
			}
		</style>

	</head>

	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container" style="">


				<a class="brand" href="#">Marcel TV</a>
					
					<div class="nav-collapse collapse" style="height: 0px;">
						<ul class="nav">
							<li class="active"><a href="#">Home</a></li>
							<li><a href="#">Browse</a></li>
						</ul>
					</div>


				<div class="btn-group pull-right">
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="icon-user"></i>
						
						<span class="caret"></span>
					</a>

					<ul class="dropdown-menu">
						<li><a href="#">My Videos</a></li>
						<li class="divider"></li>
						<li><a href="#">Sign Out</a></li>
					</ul>

					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
				</div>
			</div>

			</div>
		</div>

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