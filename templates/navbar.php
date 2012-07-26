
<?PHP
require_once('controllers/authentication.php');
require_once('controllers/users.php');

$userArray = UserController::getUserDetails(AuthenticationController::GetCurrentUserID());

?>

		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container" style="">


				<a class="brand" href="#">Marcel TV</a>
					
					<div class="nav-collapse collapse" style="height: 0px;">
						<ul class="nav">
							<li class="active"><a href="#">Home</a></li>
							<li><a href="upload">Upload</a></li>
						</ul>
					</div>

				<?PHP
				if(isset($userArray['user_id'])){
				?>
					<div class="btn-group pull-right">
						<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="icon-user"></i>
							<?PHP echo $userArray['first'] . " " . $userArray['last']; ?>
							<span class="caret"></span>
						</a>

						<ul class="dropdown-menu">
							<li><a href="#">My Videos</a></li>
							<li class="divider"></li>
							<li><a href="logout">Sign Out</a></li>
						</ul>

						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
					</div>
				<?
				}
				?>
			</div>

			</div>
		</div>