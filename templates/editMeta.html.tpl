{% extends 'layout.html.tpl' %}
{% block header_extra %}
<script>
$(document).ready( function() {
	$('#save_changes').click( function() {
		var videoData = {
			title: $('#title').val(),
			description: $('#description').val(),
			visibility: 1,
			category: 'test'
		};

		$.ajax({
			url: '{{flash['web_root']}}/editVideo/{{video.video_id}}',
			type: 'POST',
			data: 'videoData=' + JSON.stringify(videoData),
			success: function(data) {
				if(data != '') {
					alert(data);
				}
			}
		});

	});

	$('#remove_video').click( function() {

		$.ajax({
			url: '{{flash['web_root']}}/deleteVideo/{{video.video_id}}',
			method: 'POST',
			success: function(data) {

			}
		});

	});


	function format_category(category){
		if(category.id[0] == '-1'){
			return category.text + "  <span class=\"label label-warning\" style=\"display: inline;\">New</span>";
		}
		else {
			return category.text;
		}
	}


	$("#category_select").select2({
		multiple: false,
		ajax: {
			url: WEB_ROOT + "/findCategoriesLike",
			type: 'get',
			dataType: 'json',
			data: function (term, page) {
				return {
					q: term,
					limit: 10,
				};
			},
			results: function (data, page) {
				return {results: data}
			}
		},
		formatResult: format_category,
		formatSelection: format_category,
	});

});


</script>
{% endblock %}
{% block content%}
<div class="container">
			<h2>Editing <span style='font-weight: normal;'>{{video.title}}</span></h2>
			<div class="well" style='position: relative; height: 200px;'>
	
				<div style="width: 60%; float: left;">
					<form class="form-horizontal">
						<div class="control-group">
							<label class="control-label" for="title">Change Title</label>
							<div class="controls">
								<input type="text" class="input-xlarge" id="title" value="{{video.title}}">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="description">Description</label>
							<div class="controls">
								<textarea id="description" style="width: 100%; height: 150px;"
								{{video.description}}></textarea>
							</div>
						</div>
					</form>
				</div>
				<div style="position: relative; float: left; height: 100%; width: 40%;">
					<button style="position: absolute; top: -54px; right: 120px;" id="remove_video" class="btn btn-danger">Delete Video</button>
					<button id="save_changes" style="position: absolute; right: -10px; top: -54px;" class="btn btn-success">Save Changes</button>
					<form class="form-horizontal">
						<div class="control-group">
							<label class="control-label" for="private">Visibility</label>
							<div class="controls">
								<div id="visibility" class="btn-group" data-toggle="buttons-radio">
									<a id="1" data-content="Determines if Video will be visible on MarcelTV" class="btn {% if video.visibility == 1 %}active{% endif %}" value="1" data-original-title="">Public</a>
									<a class="btn {% if video.visibility != 1 %}active{% endif %}" data-content="Determines if Video will be visible on MarcelTV" id="0" value="0" data-original-title="">Private</a>
								</div>
							</div>
						</div>
							<div class="control-group">
								<label class="control-label" for="category">Category</label>
								<div class="controls">
									<input type="hidden" id="category_select" data-placeholder='{{video.category}}'/>
								</div>
							</div>
					</form>
				</div>
				<div style='clear: both;'></div>
			</div>

</div>
{% endblock %}