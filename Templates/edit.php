{% extends "base.php" %}

{%block content %}
	<section id="posts">
		{% if post is not empty %}
		<form action="/edit_post/{{post.id}}" method="POST" class="postedit">
			<article class="post">
				<header>
					<input type="text" name="title" value="{{ post.title }}" /></h1>
					<span class="date">on {{ post.created_at|date('m/d/Y') }}</span>
				</header>

				<textarea name="body">{{ post.body }}</textarea>
				<input type="submit" value="Update Post" />
			</article>
		</form>
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