	<div class="copyright">
		<div class="github-btn">
			<!-- a href="https://twitter.com/michelem" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false">Follow @michelem</a -->
			<a class="github-button" href="https://github.com/michelem09/minera" data-count-href="/michelem09/minera/stargazers" data-count-api="/repos/michelem09/minera#stargazers_count" data-count-aria-label="# stargazers on GitHub" aria-label="Star michelem09/minera on GitHub">Star</a>
		</div>
  		<a href="http://getminera.com" target="_blank"><i class="fa fa-asterisk"></i> Minera</a> your next mining dashboard - Need a wallet? Try <a href="https://www.coinbase.com/join/michelem" target="_blank">Coinbase</a>
  	</div>
	
	<script src="<?php echo base_url('assets/vendor/jquery/dist/jquery.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/jquery-ui/jquery-ui.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/bootstrap/dist/js/bootstrap.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/bootstrap-switch/dist/js/bootstrap-switch.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/md5/src/md5.js') ?>"></script>
    <script src="<?php echo base_url('assets/vendor/jquery.slimscroll/jquery.slimscroll.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/vendor/jquery.scrollTo/jquery.scrollTo.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/vendor/jquery-scrollspy-thesmart/scrollspy.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/vendor/ion.rangeSlider/js/ion.rangeSlider.js') ?>" type="text/javascript"></script>
    
    <!-- Datatables -->
	<script src="<?php echo base_url('assets/vendor/datatables/media/js/jquery.dataTables.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('assets/vendor/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.js') ?>" type="text/javascript"></script>
	
	<!-- Moment JS -->
	<script src="<?php echo base_url('assets/vendor/momentjs/moment.js') ?>" type="text/javascript"></script>
	
    <!-- Charts script -->
    <!-- jQuery Morris Charts -->
    <script src="<?php echo base_url('assets/vendor/raphael/raphael.js') ?>"></script>
    <script src="<?php echo base_url('assets/vendor/morrisjs/morris.js') ?>" type="text/javascript"></script>
		
    <!-- Settings script -->
	<!-- jQuery Validation -->
    <script src="<?php echo base_url('assets/vendor/jquery-validate/dist/jquery.validate.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/vendor/jquery.iframe-transport/jquery.iframe-transport.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/vendor/blueimp-file-upload/js/jquery.fileupload.js') ?>" type="text/javascript"></script>
	
    <!-- Dashboard script -->
	<!-- jQuery Knob -->
    <script src="<?php echo base_url('assets/vendor/jquery-knob/js/jquery.knob.js') ?>" type="text/javascript"></script>
    
	<!-- Underscore.js -->
    <script src="<?php echo base_url('assets/vendor/underscore/underscore.js') ?>" type="text/javascript"></script>
    
    <!-- AdminLTE App -->
    <script src="<?php echo base_url('assets/js/app.js') ?>" type="text/javascript"></script>

	<!-- Minera main script -->    
    <script async src="<?php echo base_url('assets/js/minera.js') ?>" type="text/javascript"></script>

    <!-- Github star -->
	<script defer id="github-bjs" src="https://buttons.github.io/buttons.js"></script>
	
	<!-- Twitter follow -->
	<!-- Removed namespace variables collides with coinbase js -->
	<!-- script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script -->
	
	<!--  Coinbase script moved to minera.js Ajax load -->

    <!-- General script -->
    <script type="text/javascript">
	    var _baseUrl = "<?php echo site_url() ?>";
	</script>
	
</body>
</html>
