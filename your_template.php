<!-- your HTML: -->
<div id="posts_list">
	<article class="single-article"> <!-- posts content --> </article>
	<article class="single-article"> <!-- posts content --> </article>
	<article class="single-article"> <!-- posts content --> </article>
	<article class="single-article"> <!-- posts content --> </article>
	<article class="single-article"> <!-- posts content --> </article>
	<article class="single-article"> <!-- posts content --> </article>
</div>

<a class="load_more" <?php wp_create_nonce('load_posts') ?> href="javascript:;">Load more</a>