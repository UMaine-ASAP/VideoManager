<html>
<head>
		<script>
			var WEB_ROOT = '{{flash['web_root']}}';
		</script>
		<script src="{{flash['web_root']}}/js/jquery.js"></script>
		<link href="{{flash['web_root']}}/bootstrap/css/bootstrapold.css" rel="stylesheet">
		<link href="{{flash['web_root']}}/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="{{flash['web_root']}}/libraries/marceltv.css" rel="stylesheet">
		<link href="{{flash['web_root']}}/libraries/select2/select2.css" rel="stylesheet">
		<script src="{{flash['web_root']}}/libraries/select2/select2.js" rel="stylesheet"></script>
		 <script src="http://kenai.asap.um.maine.edu:8080/socket.io/socket.io.js"></script>

		<style type="text/css">
			body {
				padding-top: 60px;
				padding-bottom: 40px;
			}
		</style>
		{% block header_extra %}{% endblock %}
</head>
	<body>
		<!-- Navigation bar -->
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container" style="">


				<a class="brand">Marcel TV Uploader</a>
				{% if flash['userData']['user_id'] %}
					
					<div class="nav-collapse collapse" style="height: 0px;">
						<ul class="nav">
							<li {% if flash['location'] == 'upload' %}class='active'{% endif %}><a href="{{flash['web_root']}}/upload">Upload</a></li>
							<li {% if flash['location'] == 'videos' %}class='active'{% endif %}><a href="{{flash['web_root']}}/videos">Manage Videos</a></li>

						</ul>
					</div>

					<div class="btn-group pull-right">
						<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="icon-user"></i>
							{{ flash['userData']['first'] }} {{ flash['userData']['last'] }}
							<span class="caret"></span>
						</a>

						<ul class="dropdown-menu">
							<!--
							<li><a href="{{flash['web_root']}}/my-videos">My Profile and Videos</a></li>
							<li class="divider"></li>
							-->

							<li><a href="{{flash['web_root']}}/logout">Sign Out</a></li>
						</ul>

						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
					</div>
				{% endif %}
			</div>

			</div>
		</div>

{% block content %}
{% endblock %}


<!--Scripts that are dependent on the DOM-->
	<script src="{{flash['web_root']}}/js/underscore.js"></script>
	<script src="{{flash['web_root']}}/js/backbone.js"></script>
	<script src="{{flash['web_root']}}/js/upload.js"></script>
	<script src="{{flash['web_root']}}/bootstrap/js/bootstrap.js"></script>
</body>
</html>
