{% extends 'layout.html.tpl' %}
{% block content%}
<div class="container">
			<h2>Editing <span style='font-weight: normal;'>{{video.title}}</span></h2>
			<div class="well" style='position: relative; height: 200px;'>
	
				<div style="width: 60%; float: left;">
					<form class="form-horizontal">
						<div class="control-group">
							<label class="control-label" for="title">Change Title</label>
							<div class="controls">
								<input type="text" class="input-xlarge" id="title" value="category_query.php">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="description">Description</label>
							<div class="controls">
								<textarea id="description" style="width: 100%; height: 150px;"></textarea>
							</div>
						</div>
					</form>
				</div>
				<div style="position: relative; float: left; height: 100%; width: 40%;">
					<button style="position: absolute; top: -54px; right: -10px;" id="remove" class="btn btn-danger">Delete Video</button>
					<button id="queue" style="position: absolute; right: 120px; top: -54px;" class="btn btn-success">Save Changes</button>
					<form class="form-horizontal">
						<div class="control-group">
							<label class="control-label" for="private">Visibility</label>
							<div class="controls">
								<div id="visibility" class="btn-group" data-toggle="buttons-radio">
									<a id="0" data-content="Determines if Video will be visible on MarcelTV" class="btn" value="0" data-original-title="">Public</a>
									<a class="btn active" data-content="Determines if Video will be visible on MarcelTV" id="1" value="1" data-original-title="">Private</a>
								</div>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="category">Category</label>
							<div class="controls">
								<input type="hidden" id="category">
							</div>
						</div>
					</form>
				</div>
				<div style='clear: both;'></div>
			</div>

</div>
{% endblock %}