{% extends 'layout.html.tpl' %}
{% block header_extra %}
	<script>
	function format_category(category){
		if(category.id[0] == '-1'){
			return category.text + "  <span class=\"label label-warning\" style=\"display: inline;\">New</span>";
		}
		else {
			return category.text;
		}
	}

</script>
	<style>
	#upload_list_container {
		padding: 0;
	}
	#add_video {
		margin-top: 10px;
		margin-right: 20px;
	}
	</style>
{% endblock %}
{% block content %}
	<div id="uploads_container" class="container" style="height: 100%;">
		<div class="page-header">
			<h1>Upload Files:</h1>
		</div>
		<div id="upload_list_container" class="well">
		</div>
	</div>
{% endblock %}