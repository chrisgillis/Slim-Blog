<!doctype html>
<html>
	<head>
		<link href='http://fonts.googleapis.com/css?family=Lato:300|Open+Sans+Condensed:300|Nothing+You+Could+Do' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="/css/main.css" />

		<title>{{ title }}</title>
	</head>

	<body>
		{% if logged_in %}
			{% block postform %} {% endblock %}
		{% endif %}
		<div id="container">
			<div id="header">
				<h1><a href="/">{{ title }}</a></h1>
				<h2>{{ subtitle }}</h1>
			</div>
			
			<div id="content">
				{% block content %}{% endblock %}
			</div>
		</div>
	</body>
</html>