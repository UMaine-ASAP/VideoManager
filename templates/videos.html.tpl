{% extends 'layout.html.tpl' %}
{% block header_extra %}
	<style>
	#upload_new_video_btn {
		position: absolute;
		top: 0;
		right: 0;
	}
	</style>
{% endblock %}

{% block content %}
	<div class="container" style="height: 100%;">

		{% if flash['error'] %}
			<div class='alert alert-error'>{{ flash['error'] }}</div>
		{% endif %}
		<div class="row">

			<div class="span4">
				<h2>My Account</h2>
				<div class="profile">
				

				<div><div class="thumbnail pull-left" style="margin-right: 10px;"><img src="http://placehold.it/160x120"></div>
					<div class="user_data">Benjamin Carlson<br>carlson.j.ben@gmail.com</div>
				</div>

			</div></div>


			<div class="span8" style='position:relative;'>
				<h2>My Videos</h2>
				<a class='btn btn-success' id='upload_new_video_btn' href='upload'>Upload New Video</a>
				<table class="table table-bordered">
					{% for video in videos %}
						<tr><td>
						<div class="video_list">

							<div class="video_thumbnail">
								<img src="http://placekitten.com/180/100">
							</div>

							<div class="video_info">
								<h3>{{ video.title }}</h3>
								<div class="control_buttons">
									<div class="btn-group" data-toggle="buttons-radio">
									  <a href="edit/meta/{{ video.video_id }}" class="btn">Edit Meta</a>
									  <button class="btn disabled">Privileges</button>
									  <button class="btn disabled">Conversion</button>
									</div>
								</div>
							</div>

							<div class="extra_meta pull-right">
								<p><i class="icon-calendar"></i>{{ video.upload_date }}</p>
							</div>

						</div>
					</td></tr>
					{% endfor %}

				</table>

			</div>

		</div>

	</div>
{% endblock %}