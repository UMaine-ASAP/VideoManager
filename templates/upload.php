	<html>
	<script>
	function format_category(category){
		if(category.category_id == '-1'){
			return category.name + "  <span class=\"label label-warning\" style=\"display: inline;\">New</span>";
		}
		else {
			return category.name;
		}
	}

</script>
	<? require_once('templates/header.php'); ?>

	<body>

	<? require_once('templates/navbar.php'); ?>

	<div id="uploads_container" class="container" style="height: 100%;">
		<div class="page-header">
			<h1>Upload Files:</h1>
		</div>
		<div id="upload_list">
		</div>
	</div>

<!--Scripts that are dependent on the DOM-->

	<script src="js/underscore.js"></script>
	<script src="js/backbone.js"></script>
	<script src="js/upload.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>

	</body>
</html>