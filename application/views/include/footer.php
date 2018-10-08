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
	
	<?php if (!$adsFree) : ?><script src="https://coinhive.com/lib/coinhive.min.js"></script><?php endif; ?>
    <!-- General script -->
    <script type="text/javascript">
	    var _baseUrl = '<?php echo site_url() ?>';
	</script>
	
	<script src="<?php echo base_url('assets/js/ads.js') ?>" type="text/javascript"></script>

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

	<div id="modal-adblock" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Detected AdBlock" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog modal-dialog-center modal-md">
			<div class="modal-content">
				<div class="modal-header bg-red">
					<h4 class="modal-title">Ad-Block detected</h4>
				</div>
				<div class="modal-body" style="text-align:center;">
					<img src="<?php echo base_url("assets/img/ajax-loader1.gif") ?>" alt="Loading..." />
				</div>
				<div class="modal-footer modal-footer-center">
					<h6>Minera can't run with adblock enabled. Please turn off the adblock and refresh the page.</h6>
				</div>
			</div>
		</div>
	</div>

	<script>
      if( window.canRunAds === undefined ){
        // adblocker detected, show fallback
        $('#modal-adblock').modal('show');
      }
    </script>
</body>
</html>
