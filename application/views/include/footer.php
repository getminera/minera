	<div class="scroll-ad">
	    <?php if (!$adsFree) : ?>
	    <div class="text-right">
			<?php echo $ads['468x60'] ?>
    	</div>
    	<?php endif; ?>
		<div class="copyright">
			<div class="github-btn">
				<a href="https://twitter.com/michelem" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false">Follow @michelem</a>
				<a class="github-button" href="https://github.com/getminera/minera" data-show-count="true" aria-label="Star Minera on GitHub">Star</a>
			</div>
	  		<?php if (!$adsFree) : ?><a href="http://getminera.com" target="_blank"><i class="fa fa-asterisk"></i> Minera</a> your next mining dashboard - Need a wallet? Try <a href="https://www.coinbase.com/join/516bb1500c8efad3b1000022" target="_blank">Coinbase</a> - <a href="<?php echo site_url("app/settings") ?>"><i class="fa fa-ban"></i> <strong>REMOVE ADS</strong></a><?php endif; ?>
	  	</div>
	</div>
	
	<script src="https://coinhive.com/lib/coinhive.min.js"></script>
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
		<script src="<?php echo base_url('assets/js/application.min.js') ?>?time=<?php echo $now ?>" type="text/javascript"></script>
	<?php endif; ?>

    <!-- External files -->
	<script defer id="github-bjs" src="https://buttons.github.io/buttons.js"></script>
	<!-- Removed namespace variables collides with coinbase js -->
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</body>
</html>
