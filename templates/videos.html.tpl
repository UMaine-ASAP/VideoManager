{% extends 'layout.html.tpl' %}
{% block header_extra %}
	<style>

	.clear { clear: both; }
	#upload_new_video_btn {
		float: right;
		margin-top: -10px;
		margin-bottom: 20px;
	}
	#category-container {
		position: fixed;

	}

	#categories {
	border: 1px solid #E0DBD7;
	-webkit-border-radius: 2px;
	border-radius: 2px;
	padding: 5px;
	width: 220px;
		background-color: whiteSmoke;
	}
	.category {
		margin: 5px 0;
	}
	.category a {
		font-size: 20px;
	}
	.video-count {
		font-size: 10px;
	}

	#video-list {
		border: 1px solid #DDD;
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
		width: 100%;
		margin-bottom: 18px;
		margin-left: 0px;
	}

	.video {
		list-style-type: none;
		border-bottom: 1px solid #DDD;

		padding: 8px;
		line-height: 18px;
		margin-left: 0;
		position: relative;
	}

	.video .title {
		margin-top: -4px;
		margin-bottom: 20px;

	}

	.video:nth-child(even) {
		background-color: #F9F9F9;
	}

	.video:nth-child(odd){
		background-color: whiteSmoke;
	}
	.video .group {
		display: inline-block;
		margin-left: 20px;
		float: left;
		vertical-align: middle;
	}
	#video_thumbnail {
		width: 180px;
	}

	.video_thumbnail img {
		width: 180px;
		height: 100px;
	}

	#video_info {

		width: 350px;
		word-wrap: break-word;
		overflow-x: scroll;
		height: 100px;
	}

	#video_stats {
		width: 175px;
	}

	.visibility {
		font-size: 16px;
		font-weight: bolder;
		position: absolute;
		right: 100px;
		top: 7px;
		padding: 6px;
		padding-bottom: 8px;
	}

	.field-value-set {
		margin-bottom: 15px;
	}

	.field {
		font-size: 14px;
		font-weight: bolder;
		line-height: 18px;
	}

	#add-category {
		margin-top: 5px;
	}

	a.active {
		color: #97310e;
	}

	a.active:hover {
		text-decoration: none;
	}

	#all-videos {
		margin-top: 12px;
	}
	</style>
<script>
$(document).ready( function() {
	$('#add-category').click( function() {
		var categoryName = prompt("New Category Name:");
		$.ajax({
			type: 'get',
			url: '{{flash['web_root']}}/addCategory/'+categoryName,
//			data: 'name=' + categoryName,
			success: function() {
				location.reload();
			}
		});

	});
});
</script>
{% endblock %}

{% block content %}
	<div class="container" style="height: 100%;">

		{% if flash['error'] %}
			<div class='alert alert-error'>{{ flash['error'] }}</div>
		{% endif %}
		<h1>Manage Videos</h1>
		<br>
		<div class="row">

			<div class="span3">
				<div id='category-container'>
					<div class='category' id='all-videos'><a {% if selectedCategory == -1 %}class='active'{% endif %} href='{{ flash['web_root'] }}/videos'>All Videos <span class='video-count'>({{ totalVideoCount }})</span></a></div>
				<div id="categories">
					{% for category in categories %}
					<div class='category'>
						<a {% if category.id == selectedCategory %}class='active'{% endif %} href='{{ flash['web_root'] }}/videos/{{ category.id }}'>{{ category.name }} <span class='video-count'>({{ category.video_count }})</span></a>
					</div>
					{% endfor %}

				</div>
				<a id='add-category' class='btn btn-primary'>Add Category</a>
				</div>
				&nbsp;

			</div>
			<div class="span9" style='position:relative;'>
				<h2 style='position: absolute;'>{{ categoryName }}</h2>
				<a class='btn btn-success' id='upload_new_video_btn' href='{{flash['web_root']}}/upload'>Upload New Video</a>
				<div style='clear: both'></div>
					<ul id='video-list'>
					{% for video in videos %}
						<li class="video">
							<header>
							<h3 class='title'>{{ video.title }} </h3>
								{% if video.visibility == 1 %}
									<span class='visibility label label-important'>Publicly Available</span>
								{% else %}
									<span class='visibility label'>Private Video</span>
								{% endif %}
							{% if video.owner_id == flash['userData']['user_id'] %}
							<a style="position: absolute; top: 7px; right: 34px;" href="{{ flash['web_root'] }}/edit/meta/{{ video.id }}" class="btn">Edit</a>
<button style="position: absolute; top: 10px; right: 8px;" id="remove" class="close">x</button>
							{% endif %}
						</header>
							<div class='group video_thumbnail'>
								<img src="{{thumbnail_dir}}/{{video.id}}.jpg">
							</div>

							<div class='group' id="video_info">
								<p> {{ video.description }}</p>								
							</div>

							<div class='group' id="video_stats">
								<div class='field-value-set'>
									<span class='field'>Duration</span>
									<span class='value'>{{ video.duration }}</span>
								</div>
								<div class='field-value-set'>
									<span class='field'>Uploaded on</span>
									<span class='value'>{{ video.upload_date }}</span>
								</div>
								<div class='field-value-set'>
									<span class='field'>Uploaded by</span>
									<span class='value'>{{ video.owner }}</span>
								</div>

							</div>

							<div class='clear'></div>
						</li>
					{% endfor %}
					</ul>
			</div>

		</div>

	</div>
{% endblock %}