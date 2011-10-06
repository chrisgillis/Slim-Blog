{% extends "base.php" %}

{%block content %}
	<section id="posts">
		{% if post is not empty %}
			<article class="post">
				<header>
					<h1>{{ post.title }}</h1>
					<span class="date">on {{ post.created_at|date('m/d/Y') }} {% if logged_in is not empty %} <a href="/edit/{{post.id}}">edit</a> | <a href="/delete/{{post.id}}">delete</a> {% endif %}</span>
				</header>

				{{ post.body|raw }}
			</article>
			{% if next_page %}
			<div class="post-controls">
				<a class="next-post" href="{{ next_page }}">Next Post</a>
			</div>
			{% endif %}
		{% else %}
		  <p>There are no posts.</p>
		{% endif %}
	</section>
{% endblock %}