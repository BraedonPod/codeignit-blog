<h2><?= $title ?></h2>
<?php foreach($posts as $post) : ?>
	<div class="row">
		<div class="col-md-9">
			<h3><?php echo $post['title']; ?></h3>
			<small class="post-date">Posted on: <?php echo $post['created_at']; ?> in <strong><?php echo $post['name']; ?></strong></small><br>
		<?php echo word_limiter($post['body'], 60); ?>
		<br><br>
		<p><a class="btn btn-info" href="<?php echo site_url('/post/'.$post['slug']); ?>">Read More</a></p>
		</div>
	</div>
<?php endforeach; ?>
<div class="pagination-links">
		<?php echo $this->pagination->create_links(); ?>
</div>