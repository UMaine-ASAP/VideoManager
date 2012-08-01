<?PHP
	require_once('controllers/authentication.php');
	require_once('controllers/video.php');
?>
	<html>
	
	<?php require_once('templates/header.php'); ?>

	<body>

	<?php require_once('templates/navbar.php'); ?>

<?PHP
echo "<pre>";
print_r(VideoController::getVideoMeta($id));
echo "</pre>";
?>

<div class="container">
	<div class="row">

		<div class="span7">
			<h2>Edit Meta Data</h2>
			<div class="meta_edit">
				<form class="form-horizontal">
					<div class="control-group">
						<label class="control-label" for="title">Video Title</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="title">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="description">Description</label>
						<div class="controls">
							<textarea id="description" style="width: 300px; height: 100px;"></textarea>
						</div>
					</div>
				</form>
			</div>
		</div>


		<div class="span5 video_view">

		</div>

	</div>
</div>

<?php require_once('templates/footer.php');?>

	</body>
</html>