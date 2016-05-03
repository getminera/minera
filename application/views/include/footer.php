	<div class="scroll-ad">
	    <?php if (!$adsFree) : ?>
	    <div class="text-right">
			<?php echo $ads['468x60'] ?>
    	</div>
    	<?php endif; ?>
		<div class="copyright">
			<div class="github-btn">
				<a href="https://twitter.com/michelem" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false">Follow @michelem</a>
				<a class="github-button" href="https://github.com/michelem09/minera" data-count-href="/michelem09/minera/stargazers" data-count-api="/repos/michelem09/minera#stargazers_count" data-count-aria-label="# stargazers on GitHub" aria-label="Star michelem09/minera on GitHub">Star</a>
			</div>
	  		<?php if (!$adsFree) : ?><a href="http://getminera.com" target="_blank"><i class="fa fa-asterisk"></i> Minera</a> your next mining dashboard - Need a wallet? Try <a href="https://www.coinbase.com/join/michelem" target="_blank">Coinbase</a> - <a href="<?php echo site_url("app/settings") ?>"><i class="fa fa-ban"></i> <strong>REMOVE ADS</strong></a><?php endif; ?>
	  	</div>
	</div>
	
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
		<script src="<?php echo base_url('assets/js/application.min.js') ?>" type="text/javascript"></script>
	<?php endif; ?>

    <!-- External files -->
	<script defer id="github-bjs" src="https://buttons.github.io/buttons.js"></script>
	<!-- Removed namespace variables collides with coinbase js -->
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
	<!--  Coinbase script moved to minera.js Ajax load -->
	<script src="https://<?php echo (isset($env) && $env === 'development') ? 'sandbox' : 'www'; ?>.coinbase.com/assets/button.js" type="text/javascript"></script>
	
</body>
</html>
