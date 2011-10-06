{% extends "base.php" %}

{%block content %}
	<section id="posts">
		{% for post in posts %}
			<article class="post">
				<header>
					<h1>{{ post.title }}</h1>
					<span class="date">on {{ post.created_at|date('m/d/Y') }} {% if logged_in is not empty %} <a href="edit/{{post.id}}">edit</a> | <a href="/delete/{{post.id}}">delete</a> {% endif %}</span>
				</header>

				{{ post.body|raw }}
			</article>
			{% if next_page  %}
			<div class="post-controls">
				<a class="next-post" href="{{ next_page }}">Next Post</a>
			</div>
			{% endif %}
		{% endfor %}
		{% if posts is empty %}
		  <p>There are no posts.</p>
		{% endif %}
	</section>
	{% if logged_in is not empty %}
	{% endif %}
{% endblock %}

{%block postform %}
<div id="new-post">
		<form action="new_post" method="POST">
			<input type="text" name="title" value="" placeholder="Title..."/><br />
			<textarea name="body" ></textarea><br />
			<input type="submit" value="Create Post" /> <a href="/logout">logout</a>
		</form>
</div>
{%endblock%}