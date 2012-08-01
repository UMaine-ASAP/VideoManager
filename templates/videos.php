	<?php
	require_once('controllers/authentication.php');
	require_once('controllers/video.php');
	?>

	<html>
	
	<?php require_once('templates/header.php'); ?>

	<body>

	<?php require_once('templates/navbar.php'); ?>


	<div class="container" style="height: 100%;">

		<?PHP
		if(isset($_SESSION['slim.flash']['error'])){
    		echo "<div class=\"alert alert-error\">";
    		echo $_SESSION['slim.flash']['error'];
    		echo "</div>";
    	}
    	?>
		<div class="row">

			<div class="span4">
				<h2>My Account</h2>
				<div class="profile">
				

				<div><div class="thumbnail pull-left" style="margin-right: 10px;"><img src="http://placehold.it/160x120"></div>
					<div class="user_data">Benjamin Carlson<br>carlson.j.ben@gmail.com</div>
				</div>

			</div></div>


			<div class="span8">
				<h2>My Videos</h2>
				<table class="table table-bordered">

					<?php

					$videos = VideoController::getUserVideos(AuthenticationController::getCurrentUserID());

					foreach($videos as $video){
						?>
						<tr><td>
						<div class="video_list">

							<div class="video_thumbnail">
								<img src="http://placekitten.com/180/100">
							</div>

							<div class="video_info">
								<h3><?php echo $video['title']; ?></h3>
								<div class="control_buttons">
									<div class="btn-group" data-toggle="buttons-radio">
									  <a href="edit/meta/<?php echo $video['video_id']; ?>" class="btn">Edit Meta</a>
									  <button class="btn disabled">Privileges</button>
									  <button class="btn disabled">Conversion</button>
									</div>
								</div>
							</div>

							<div class="extra_meta pull-right">
								<p><i class="icon-calendar"></i><?php echo $video['upload_date']; ?></p>
							</div>

						</div>
					</td></tr>

						<?php
					}

					?>

				</table>

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