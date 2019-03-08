<!-- General script -->
    <script type="text/javascript">
	    var _baseUrl = '<?php echo site_url() ?>';
	</script>
	
	<?php if ($this->config->item("ENV") !== "production") : ?>
		<?php
		$medias = json_decode(file_get_contents(base_url('assets/media.json')));
		foreach ($medias->js as $js) : 
		?>
			<script src="<?php echo base_url($js) ?>" type="text/javascript"></script>
		<?php endforeach; ?>
	<?php else : ?>
		<script src="<?php echo base_url('assets/js/application.js') ?>?time=<?php echo $now ?>" type="text/javascript"></script>
	<?php endif; ?>

    <!-- External files -->
	<script defer id="github-bjs" src="https://buttons.github.io/buttons.js"></script>
	<!-- Removed namespace variables collides with coinbase js -->
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

</body>
</html>
