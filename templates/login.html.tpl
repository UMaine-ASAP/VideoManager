{% extends 'layout.html.tpl' %}
{% block content %}
	<div class="container" style="height: 100%;">

		<div class="page-header">
			<h1>MarcelTV Uploader:</h1>
		</div>

		{% if flash['header'] %}
			<div class='alert alert-success'>{{ flash['header'] }}</div>
		{% elseif flash['error'] %}
			<div class='alert alert-error'>{{ flash['error'] }}</div>
		{% endif %}

		<div class="well">

			<div class="row">
				<div class="span6">
					<h2>Register</h2>
					<hr>
  					<form method="post" action="register">
					<label for="username">Username: </label><input type="text" name="username">
					<label for="password">Password: </label><input type="password" name="password">
					<label for="confirm_password">Confirm Password: </label><input type="password" name="confirm_password">
					<label for="email">Email: </label><input type="text" name="email">
					<label for="name">Name: </label><input style="width: 102px;" type="text" name="first_name">&nbsp;&nbsp;<input style="width: 102px;" type="text" name="last_name">
				
					<div class="form-actions">
						<input class="btn btn-large btn-inverse" type="submit" value="Register">
					</div>

					</form>
				</div>				
				<div class="span5">
					<h2>Login</h2>
					<hr>
  					<form method="post" action="login">
					<label for="username">Username: </label><input type="text" name="username"><br>
					<label for="password">Password: </label><input type="password" name="password"><br>

					<div class="form-actions">
						<input class="btn btn-large btn-inverse" type="submit" value="Login">
					</div>

					</form>
				</div>
			</div>

		</div>
	</div>
</div>
{% endblock %}