{% extends "base.php" %}

{%block content %}
<form action="login" method="POST">
	<input type="text" name="username" value="" /><br />
	<input type="password" name="password" value="" /><br />
	<input type="submit" value="Login" />
</form>
{% endblock %}