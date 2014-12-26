	<div class="copyright">
		<div class="github-btn">
			<iframe src="http://ghbtns.com/github-btn.html?user=michelem09&repo=minera&type=watch&count=true"
  allowtransparency="true" frameborder="0" scrolling="0" width="110" height="20"></iframe>
		</div>
  		<a href="http://getminera.com" target="_blank"><i class="fa fa-asterisk"></i> Minera</a> your next mining dashboard - Need a wallet? Try <a href="https://www.coinbase.com/join/michelem" target="_blank">Coinbase</a>
  	</div>
	
	<!--  Coinbase tip script -->
	<script>!function(d,s,id) {var js,cjs=d.getElementsByTagName(s)[0],e=d.getElementById(id);if(e){return;}js=d.createElement(s);js.id=id;js.src="https://www.coinbase.com/assets/tips.js";cjs.parentNode.insertBefore(js,cjs);}(document, 'script', 'coinbase-tips');</script>
	<script src="https://www.coinbase.com/assets/button.js" type="text/javascript"></script>
	
	<script src="<?php echo base_url('assets/js/jquery-2.1.1.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery-ui-1.10.4.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery.ui.touch-punch.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/lodash.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/bootstrap-switch.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/custom.js') ?>"></script>

    <!-- AdminLTE App -->
    <script src="<?php echo base_url('assets/js/app.js') ?>" type="text/javascript"></script>

    <!-- ScrollTo -->
    <script src="<?php echo base_url('assets/js/jquery.scrollTo.min.js') ?>" type="text/javascript"></script>
    
    <!-- Ion rangeSlider -->
    <script src="<?php echo base_url('assets/js/ion.rangeSlider.min.js') ?>" type="text/javascript"></script>
    
    <!-- Datatables -->
	<script src="<?php echo base_url('assets/js/jquery.dataTables.min.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('assets/js/dataTables.bootstrap.js') ?>" type="text/javascript"></script>
	
	<!-- Moment JS -->
	<script src="<?php echo base_url('assets/js/moment.min.js') ?>" type="text/javascript"></script>
	
    <!-- General script -->
    <script type="text/javascript">
    	
		$(function() {
		    "use strict";
		    
			if( !window.location.hash ) {
				$('html, body').animate({scrollTop : 0}, 800);
			}
			
			startTime();
			
			//$(document).ready(function(){
         	//	bootstro.start();
        	//});
			
			function startTime()
            {
                var today = new Date();
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();

                // add a zero in front of numbers<10
                m = checkTime(m);
                s = checkTime(s);

                //Check for PM and AM
                var day_or_night = (h > 11) ? "PM" : "AM";

                //Convert to 12 hours system
                if (h > 12)
                    h -= 12;

                //Add time to the headline and update every 500 milliseconds
                $('.toptime').html(h + ":" + m + ":" + s + " " + day_or_night);
                setTimeout(function() {
                    startTime()
                }, 500);
            }

            function checkTime(i)
            {
                if (i < 10)
                {
                    i = "0" + i;
                }
                return i;
            }
            
            $(".miner-action").click(function(e) {
	           	e.preventDefault();
	           	var action =  $(this).data("miner-action");
	        	
			   	$("#modal-saving-label").html("Sending action: "+action+" , please wait...");
	        	$('#modal-saving').modal('show');
	        	
	        	saveSettings(false);
	        	
	        	var apiUrl = "<?php echo site_url("app/api") ?>?command=miner_action&action="+action;

				$.ajax({
					type: "GET",
					url: apiUrl,
					cache: false,
					success:  function(resp){
						setTimeout(function() {
							$('#modal-saving').modal('hide');
							window.location.reload();
						}, 5000);
					}
				});
            });
            
            $(".reset-action").click(function(e) {
	           	e.preventDefault();
	           	var action =  $(this).data("reset-action");
	        	
			   	$("#modal-saving-label").html("Resetting: "+action+" , please wait...");
	        	$('#modal-saving').modal('show');
	        	
	        	var apiUrl = "<?php echo site_url("app/api") ?>?command=reset_action&action="+action;

				$.ajax({
					type: "GET",
					url: apiUrl,
					cache: false,
					success:  function(resp){
						$('#modal-saving').modal('hide');
						window.location.reload();
					}
				});
            });
            
			$('.system-open-terminal').click(function(e){
	           	e.preventDefault();

	           	if (!$('iframe').attr("src"))
					$('iframe').attr("src", "http://<?php echo $_SERVER['SERVER_ADDR'] ?>:4200/");

				$('#modal-terminal').modal('show');
			});
			
			$('.modal-hide').click(function(e){
	           	e.preventDefault();
				$('#modal-terminal').modal('hide');
			});
            
		});
		
        function saveSettings(hide)
        {
        	$("#modal-saving-label").html("Saving data, please wait...");
        	$('#modal-saving').modal('show');
        	
        	var saveUrl = "<?php echo site_url("app/save_settings") ?>";
        	var formData = $("#minersettings").serialize();

			$.ajax({
				type: "POST",
				url: saveUrl,
				data: formData,
				cache: false,
				success:  function(resp){
					if (hide) {
						$('#modal-saving').modal('hide');
						window.location.reload();
					}
				}
			});
        }
	</script>

	<?php if (isset($chartsScript) && $chartsScript) : ?>
    <!-- jQuery Morris Charts -->
    <script src="<?php echo base_url('assets/js/raphael-min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/morris.min.js') ?>" type="text/javascript"></script>

    <!-- Charts script -->
    <script type="text/javascript">
    	
		$(function() {
		    "use strict";

		    createChart('hourly', '5 minutes');		    
		    createChart('daily', '15 minutes');
		    createChart('monthly', '1 hour');
		    createChart('yearly', '1 day');
		    		    		    
		    function createChart(period, text_period)
		    {
				/* Morris.js Charts */
				// get Json data from stored_stats url (redis) and create the graphs
				$.getJSON( "<?php echo site_url('app/api') ?>"+"?command=history_stats&type="+period, function( data ) 
	    		{
				    var refresh = false;
				    var areaHash = {};
				    var areaRej = {};
				    
	    			var data = Object.keys(data).map(function(key) { 
								data[key]['timestamp'] = data[key]['timestamp']*1000; 
								data[key]['hashrate'] = (data[key]['hashrate']/1000/1000).toFixed(2);
								data[key]['pool_hashrate'] = (data[key]['pool_hashrate']/1000/1000).toFixed(2);									
								return data[key];
						});
				
					if (data.length > 0)
					{
						
						if (refresh === false)
						{
							// Hashrate history graph
							areaHash = new Morris.Area({
								element: 'hashrate-chart-'+period,
								resize: true,
								data: data,
								xkey: 'timestamp',
								ykeys: ['hashrate', 'pool_hashrate'],
								ymax: 'auto',
								postUnits: "Mh/s",
								labels: ['Devices', 'Pool'],
								lineColors: ['#3c8dbc', '#00c0ef'],
								lineWidth: 2,
								pointSize: 3,
								hideHover: 'auto',
								behaveLikeLine: true
	
							});	
							
							// Rejected/Errors graph
							areaRej = new Morris.Area({
								element: 'rehw-chart-'+period,
								resize: true,
								data: data,
								xkey: 'timestamp',
								ykeys: ['accepted', 'rejected', 'errors'],
								ymax: 'auto',
								labels: ['Accepted', 'Rejected', 'Errors'],
								lineColors: ['#00a65a', '#f39c12', '#f56954'],
								lineWidth: 2,
								pointSize: 3,
								hideHover: 'auto',
								behaveLikeLine: true
							});
						}
						else
						{
							updateGraphs(data);
						}
						
						$(window).resize(function() {
							redrawGraphs()
						});
						
						$('.sidebar-toggle').click(function() { redrawGraphs(); })
					}
					else
					{
						$('#hashrate-chart-'+period).css({'height': '100%', 'overflow': 'visible', 'margin-top': '20px'}).html('<div class="alert alert-warning"><i class="fa fa-warning"></i><b>Alert!</b> <small>No data collected, wait at least '+text_period+' to see the chart.</small></div>');	
						$('#rehw-chart-'+period).css({'height': '100%', 'overflow': 'visible', 'margin-top': '20px'}).html('<div class="alert alert-warning"><i class="fa fa-warning"></i><b>Alert!</b> <small>No data collected, wait at least '+text_period+' to see the chart.</small></div>');	

					}
				
					function redrawGraphs()
					{
					    areaHash.redraw();
					    areaRej.redraw();
						    
					    return false;
					}
					
					function updateGraphs(data)
					{
					    areaHash.setData(data);
					    areaRej.setData(data);
						    
					    return false;
					}
					
					$('.overlay').hide();
					$('.loading-img').hide();
				});
    			
			} //End get stored stats
		});
		
	</script>
	<?php endif; ?>
		
	<?php if ($settingsScript) : ?>
	<!-- jQuery Validation -->
    <script src="<?php echo base_url('assets/js/jquery.validate.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/jquery.iframe-transport.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/jquery.fileupload.js') ?>" type="text/javascript"></script>
        
    <!-- Settings script -->
    <script type="text/javascript">
    	
		$(function() {
		    "use strict";
		    
		    $(".box-tools").click( function(e) { e.preventDefault(); });
		    
		    $('#progress').hide();
			
			$('.import-file').fileupload({
				url: '<?php echo site_url("app/api?command=import_file"); ?>',
				dataType: 'json',
				done: function (e, data) {
					console.log(data.result);
					if (data.result.error)
					{
						$('#files').fadeOut();
						$('#files').html('<div class="callout callout-danger">'+data.result.error+'</div>').fadeIn();
					}
					else
					{
						$('#files').html('<div class="callout callout-grey"><p class="margin-bottom">File seems good, click the button to start trying import.</p><p><button class="btn btn-primary import-file-action" name="import-system" value="1">Import System</button></p></div>');
					}
				},
				progressall: function (e, data) {
					//$('#progress').show();
					var progress = parseInt(data.loaded / data.total * 100, 10);
					$('#progress .progress-bar').css(
						'width',
						progress + '%'
					);
				}
			}).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
		    
		    $(document).on('click', '.import-file-action', function(e) {
	           	e.preventDefault();
	           	
	           	$("#modal-saving-label").html("Cloning the system, please wait...");
	        	$('#modal-saving').modal('show');
	        	
	        	var saveUrl = "<?php echo site_url("app/api?command=clone_system") ?>";
	
				$.ajax({
					type: "GET",
					url: saveUrl,
					cache: false,
					success:  function(resp){
						$('#modal-saving').modal('hide');
						window.location.reload();
					}
				});
			});
			
			$(".export-action").click(function(e) {
	           	e.preventDefault();
	        	
			   	$("#modal-saving-label").html("Generating export file, please wait...");
	        	$('#modal-saving').modal('show');
	        	
	        	var saveUrl = "<?php echo site_url("app/save_settings") ?>";
	        	var formData = $("#minersettings").serialize();
	
				$.ajax({
					type: "POST",
					url: saveUrl,
					data: formData,
					cache: false,
					success:  function(resp){
						$('#modal-saving').modal('hide');
						window.location = "<?php echo site_url("app/export") ?>";
					}
				});      	
            });
            
            $(".save-config-action").click(function(e) {
	           	e.preventDefault();
	        	
			   	$("#modal-saving-label").html("Saving current config, please wait...");
	        	$('#modal-saving').modal('show');
	        	
	        	var saveUrl = "<?php echo site_url("app/save_settings?save_config=1") ?>";
	        	var formData = $("#minersettings").serialize();
	
				$.ajax({
					type: "POST",
					url: saveUrl,
					data: formData,
					cache: false,
					success:  function(resp){
						var date = moment(resp.timestamp*1000);

						var pools = '';
						for (var key in resp.pools) { 
							pools += resp.pools[key].url + ' <i class="fa fa-angle-double-right"></i> ' + resp.pools[key].username + '<br />';
						}
						
						var htmlRow =
							'<tr class="config-'+resp.timestamp+'"> \
								<td><small class="label label-info">'+date.format("MM/DD/YY h:mm a")+'</small></td> \
								<td><small class="label bg-blue">'+resp.software+'</small></td> \
								<td><small class="font-bold">'+resp.settings+'</small></td> \
								<td><small>' + pools + '</small></td> \
								<td class="text-center"> \
									<a href="#" class="share-config-open" data-config-id="'+resp.timestamp+'" data-toggle="tooltip" data-title="Share saved config"><i class="fa fa-share-square-o"></i></a> \
									<a href="#" class="load-config-action" style="margin-left:10px;" data-config-id="'+resp.timestamp+'" data-toggle="tooltip" data-title="Load saved config"><i class="fa fa-upload"></i></a> \
									<a href="#" class="delete-config-action" style="margin-left:10px;" data-config-id="'+resp.timestamp+'" data-toggle="tooltip" data-title="Delete saved config"><i class="fa fa-times"></i></a> \
								</td> \
							</tr>';
								
						$('#saved-configs-table tbody').append(htmlRow);
						
						$('.saved-configs').show();
						$('#modal-saving').modal('hide');
					}
				});      	
            });
            
            $(document).on('click', '.share-config-open', function(e) {
	           	e.preventDefault();
	        	$("input[name='config_id']").val($(this).data('config-id'));
	        	$('#modal-sharing').modal('show');
            });
            
            $(document).on('click', '.share-config-action', function(e) {
	           	e.preventDefault();
	        	
	        	$('.share-error').fadeOut();
	        	
	        	var descr = $("textarea[name='config_description']").val();

	        	if (!descr || 0 === descr.length)
	        	{
		        	$('#formsharingconfig').append('<h6 class="callout bg-red share-error">Description can\'t be empty');
		        	return;
	        	}
	        	
	        	var saveUrl = "<?php echo site_url("app/api/?command=share_config") ?>";
	        	var formData = $("#formsharingconfig").serialize();
	        	
				$.ajax({
					type: "POST",
					url: saveUrl,
					data: formData,
					cache: false,
					success:  function(resp){
						console.log(resp);
						$('#modal-saving').modal('hide');
						window.location.reload();
					}
				});
            });

            $(document).on('click', '.delete-config-action', function(e) {
	           	e.preventDefault();
	        	
	        	var id = $(this).data('config-id');
	        	var saveUrl = "<?php echo site_url("app/api/?command=delete_config&id=") ?>"+id;

				$('.config-'+id).fadeOut();

				$.ajax({
					type: "GET",
					url: saveUrl,
					cache: false,
					success:  function(resp){
						//console.log(resp);
						$('.config-'+id).remove();
					}
				});      	
            });
            
            $(document).on('click', '.load-config-action', function(e) {
	           	e.preventDefault();
	        	
	        	$("#modal-saving-label").html("Loading config, please wait...");
				$('#modal-saving').modal('show');
        	
	        	var id = $(this).data('config-id');
	        	var saveUrl = "<?php echo site_url("app/api/?command=load_config&id=") ?>"+id;

				$('.config-'+id).fadeOut('fast').fadeIn('slow');

				$.ajax({
					type: "GET",
					url: saveUrl,
					cache: false,
					success:  function(resp){
						$('#modal-saving').modal('hide');
						window.location.reload();
					}
				});      	
            });
			
		    //Make the dashboard widgets sortable Using jquery UI
		    $(".poolSortable").sortable({
		        placeholder: "sort-highlight",
		        connectWith: ".sort-attach",
		        handle: ".sort-attach",
		        forcePlaceholderSize: true,
		        zIndex: 999999
		    });
		    $(".sort-attach").css("cursor","move");
		    
		    // Initialize options sliders
		    $(".open-readme-donation").click(function(e) {
				e.preventDefault();
				$(".readme-donation").fadeToggle();
		    });
		    
		    $(".view-stored-donations").click(function(e) {
				e.preventDefault();
				$("#stored-donation-table_wrapper").toggle();
				$("#stored-donation-table").fadeToggle();
		    });
		    
			$("#stored-donation-table").dataTable({bFilter: false, bInfo: false, bPaginate: true, "dom": '<"top"i>rt<"bottom"flp><"clear">'});
			$("#stored-donation-table_wrapper").hide();
		    
		    function changeDonationWorth(profitability, value) 
		    {
		    	var amount = (profitability / 24 / 60 * value);
		    	var string = (value > 0) ? "about" : "exactly";

		    	if (value >= 60)
		    	{
		    		var h = Math.floor(value / 60);
					var new_value = value % 60;
			    	var period = h + ' hour(s) ' + ((new_value > 0) ? ' and ' + new_value + ' minute(s)' : '');
		    	}
		    	else
		    	{
			    	var period = value + ' minutes';
		    	}
		    	
				$(".donation-worth").html('<small>Mining for ' + period + ' in a day your donation per MH/s worths ' + string + ':</small> <span class="label label-success"><i class="fa fa-btc"></i>&nbsp;' + amount.toFixed(8) + '</span>');
				
				if (value > 0 && value < 90)
				{
					$(".donation-mood").html('<i class="fa fa-smile-o"></i> Your support is much appreciate. Thank you!').removeClass().addClass('donation-mood badge bg-blue');
				}
				else if (value >= 90 && value < 180)
				{
					$(".donation-mood").html('<i class="fa fa-sun-o"></i> WOW that\'s really cool! Thank you!').removeClass().addClass('donation-mood badge bg-green');
				}
				else if (value >= 180 && value < 270)
				{
					$(".donation-mood").html('<i class="fa fa-star"></i> That\'s amazing! You are a star! Thank you!').removeClass().addClass('donation-mood badge bg-yellow');
				}
				else if (value >= 270 && value <= 360 )
				{
					$(".donation-mood").html('<i class="fa fa-heart"></i> You are my hero! You really rock! Thank you so much!').removeClass().addClass('donation-mood badge bg-red');
				}
				else
				{
					$(".donation-mood").html('<i class="fa fa-frown-o"></i> Time donation is disabled').removeClass().addClass('donation-mood badge');
				}
			}
		    
		    var donation_profitability = $("#option-minera-donation-time").data("donation-profitability");
		    
		    $("#option-minera-donation-time").ionRangeSlider({
				min: 0,
				max: 360,
				to: <?php echo (isset($mineraDonationTime)) ? $mineraDonationTime : 0; ?>,
				type: 'double',
				step: 10,
				postfix: " Mins",
				hasGrid: true,
				onLoad: function (obj) {
					changeDonationWorth(donation_profitability, obj.toNumber);
				},
				onChange: function (obj) {
					if (obj.fromNumber > 0)
					{
						$("#option-minera-donation-time").ionRangeSlider('update', { from: 0 });
					}
					changeDonationWorth(donation_profitability, obj.toNumber);
				}
			});
			
		    $("#ion-startfreq").ionRangeSlider({
				min: 600,
				max: 1400,
				from: <?php echo (isset($minerdStartfreq)) ? $minerdStartfreq : 800; ?>,
				type: 'single',
				step: 1,
				postfix: " Mhz",
				hasGrid: true,
			});

		    $("#option-dashboard-refresh-time").ionRangeSlider({
				min: 0,
				max: 600,
				to: <?php echo (isset($dashboard_refresh_time)) ? $dashboard_refresh_time : 60; ?>,
				type: 'double',
				step: 5,
				postfix: " Secs",
				hasGrid: true,
				onChange: function (obj) {
					if (obj.fromNumber > 0)
					{
						$("#option-dashboard-refresh-time").ionRangeSlider('update', { from: 0 });
					}
					if (obj.toNumber < 5)
					{
						$("#option-dashboard-refresh-time").ionRangeSlider('update', { to: 5 });
					}
				}
			});
			
		    $(document).on('click', '.help-pool-row', function(e) {
				e.preventDefault();
				$(".minera-pool-help").fadeToggle();
		    });

		    $(document).on('click', '.del-pool-row', function(e) {
				e.preventDefault();
				$(this).closest(".form-group").remove();
		    });
		    		    
		    $(document).on('click', '.add-pool-row', function(e) {
				e.preventDefault();
				$(".pool-group-master").first().clone().appendTo(".poolSortable");
				$(".pool-group-master").last().css("display", "block").removeClass("pool-group-master");
		    });

			$(".form-donation").prop('disabled', true);
			
		    $(document).on('click', '.add-donation-pool-row', function(e) {
				e.preventDefault();
				$(".form-donation").prop('readonly', true).prop('disabled', false);
				$(".pool-donation-group").css("display", "block").removeClass("pool-donation-group");
		    });
		    
		    // Custom miners
		     // Initialize options sliders
		    $(".open-readme-custom-miners").click(function(e) {
				e.preventDefault();
				$(".readme-custom-miners").fadeToggle();
		    });
		    
		    $('#minerd-software').on('change', function() {
				showHideMinerOptions(true);
		    });
		    
		    $(document).on('click', '.del-custom-miner', function(e) {
				e.preventDefault();
				var d = $(this).closest(".input-group");
				
				$.ajax("<?php echo site_url("app/api?command=delete_custom_miner&custom="); ?>"+$(this).data('custom-miner'), {
			        success: function (data) {
			        	if (data)
			        	{
							console.log(data);
							d.fadeOut().remove();
						}
			        }
			    });
		    });
		    
		    showHideMinerOptions(false);
		    
		    // Show or Hide the options related to the selected miner software
		    function showHideMinerOptions(change)
		    {
			    var sel = $('#minerd-software option:selected').text().match(/\[Custom Miner\]/);
			    
			    if ($('#minerd-software').val() !== "cpuminer" && sel === null)
			    {
				    $(".options-selection").show();
			    	$(".legend-option-autodetect").html("(--scan=all)");
			    	$(".legend-option-log").html("(--log-file)");
			    	$("#minerd-autotune").hide();
			    	$("input[name='minerd_autotune']").prop('disabled', true);
				    $("#minerd-startfreq").hide();
			    	$("input[name='minerd_startfreq']").prop('disabled', true);
				    $("#minerd-scrypt").show();
				    $("#minerd-api-allow").show();
			    	$("input[name='minerd_scrypt']").prop('disabled', false);
			    }
			    else if (sel !== null)
			    {
				    $(".options-selection").hide();
				    $(".guided-options").fadeOut();
					$(".manual-options").fadeIn();
					$(".btn-manual-options").addClass("disabled");
					$(".btn-guided-options").removeClass("disabled");
					$("#manual_options").val(1);
					$("#guided_options").val(0);
			    }
			    else
			    {
				    $(".options-selection").show();
			    	$(".legend-option-autodetect").html("(--gc3355-detect)");
			    	$(".legend-option-log").html("(--log)");
					$("#minerd-log").show();
				    $("input[name='minerd_log']").prop('disabled', false);
			    	$("#minerd-autotune").show();
			    	$("input[name='minerd_autotune']").prop('disabled', false);
				    $("#minerd-startfreq").show();
			    	$("input[name='minerd_startfreq']").prop('disabled', false);
				    $("#minerd-scrypt").hide();
				    $("#minerd-api-allow").hide();
			    	$("input[name='minerd_scrypt']").prop('disabled', true);
			    }

			    $(".detail-minerdsoftware").remove();
			    $(".note-minerdsoftware").remove();
			    
			    if (change)
			    {
			    	if ($('#minerd-software').val() == "cpuminer")
					{
						$(".group-minerdsoftware").append('<h6 class="detail-minerdsoftware"><a href="https://github.com/siklon/cpuminer-gc3355" target="_blank"><small class="badge bg-red">CPUminer-GC3355</small></a> is a fork of Cpuminer and is the best software for gridseed devices like Minis and Blades. It is fully optimised and supports autotune, autodetection, frequency and it\'s really stable. <a href="https://github.com/siklon/cpuminer-gc3355" target="_blank">More info</a>.</h6>');
					}
					else if ($('#minerd-software').val() == "bfgminer")
					{
						$(".group-minerdsoftware").append('<h6 class="detail-minerdsoftware"><a href="https://github.com/luke-jr/bfgminer" target="_blank"><small class="badge bg-red">BFGminer</small></a> has a really large amount of devices supported, it has also a lot of features you can use to get the best from your devices. It\'s a stable software. <a href="https://github.com/luke-jr/bfgminer" target="_blank">More info</a>.</h6>');
					}
					else if ($('#minerd-software').val() == "cgminer")
					{
						$(".group-minerdsoftware").append('<h6 class="detail-minerdsoftware"><a href="https://github.com/ckolivas/cgminer" target="_blank"><small class="badge bg-red">CGminer</small></a> is similar to bfgminer, supports a large amount of devices but probably is less updated than bfg. It\'s a stable software. <a href="https://github.com/ckolivas/cgminer" target="_blank">More info</a>.</h6>');
					}
					else if ($('#minerd-software').val() == "cgdmaxlzeus")
					{
						$(".group-minerdsoftware").append('<h6 class="detail-minerdsoftware"><a href="https://github.com/dmaxl/cgminer/" target="_blank"><small class="badge bg-red">CGminer Dmaxl Zeus</small></a> is a Cgminer 4.3.5 fork with GridSeed and Zeus scrypt ASIC support, it has some issues with Minera. Stability is unknown. <a href="https://github.com/dmaxl/cgminer/" target="_blank">More info</a>.</h6>');
					}
					else
					{
						$(".group-minerdsoftware").append('<h6 class="detail-minerdsoftware"><small class="badge bg-red">Custom Miner</small> is a miner you uploaded, it\'s up to you, but it\'s recommended to use "manual" options below, cause Minera can\'t know the "guided" options for your custom miner.</h6>');
					}
										
					$(".group-minerdsoftware").append('<h5 class="note-minerdsoftware"><strong>NOTE:</strong> <i>remember to review your settings below if you change the miner software because they haven\'t the same config options and the miner process could not start.</i></5>');
				}
		    }
		    
		    if ($("#manual_options").val() == "1") $(".guided-options").hide();
		    if ($("#guided_options").val() == "1") $(".manual-options").hide();
		    $(".btn-manual-options").click(function() {
		    	$(".guided-options").fadeOut();
				$(".manual-options").fadeIn();
				$(".btn-manual-options").addClass("disabled");
				$(".btn-guided-options").removeClass("disabled");
				$("#manual_options").val(1);
				$("#guided_options").val(0);
				return false;
		    });
		    $(".btn-guided-options").click(function() {
		    	$(".manual-options").fadeOut();
				$(".guided-options").fadeIn();
				$(".btn-guided-options").addClass("disabled");
				$(".btn-manual-options").removeClass("disabled");
				$("#manual_options").val(0);
				$("#guided_options").val(1);
				return false;
		    });
			
		    // validate signup form on keyup and submit
		    $.validator.addMethod("check_multiple_select", function(value, element) {
				if (value && value.length > 0 && value.length <= 5 )
					return true;
					
				return false;
				
			}, "Select at least 1 rate (max 5)");

			var validator = $("#minersettings").validate({
				rules: {
					minerd_manual_settings: "required",
					mobileminer_system_name: {
						required: {
							depends: function () {
								return $('.mobileminer-checkbox').is(':checked');
							}
						}
					},
					mobileminer_email: {
						required: {
							depends: function () {
								return $('.mobileminer-checkbox').is(':checked');
							}
						},
						email: true
					},
					mobileminer_appkey: {
						required: {
							depends: function () {
								return $('.mobileminer-checkbox').is(':checked');
							}
						}
					},
					minerd_autorestart_devices: {
						required: {
							depends: function () {
								return $('.minerd-autorestart').is(':checked');
							}
						}
					},
					scheduled_event_action: {
						required: {
							depends: function () {
								return $('.scheduled-event-time').val();
							}
						}
					},
					scheduled_event_time: {
						number: true
					}
				},
			    errorPlacement: function(error, element) {
					error.appendTo( $(element).closest(".input-group").parent().after() );
			    },
			    // specifying a submitHandler prevents the default submit, good for the demo
			    submitHandler: function() {
			    	saveSettings(true);
			    },
			    unhighlight: function(element) {
					$(element).closest(".input-group").removeClass("has-error").addClass("has-success");
			    },
			    highlight: function(element, errorClass) {
			    	$(element).closest(".input-group").removeClass("has-success").addClass("has-error");
			    }
			});
			
			$(".dashboard-coin-rates").rules('add', {
				check_multiple_select: true
			});
			
			$(".pool_url").each(function () {
				if ($(this).data('ismain'))
				{
					$(this).rules('add', 'required');
				}
				else
				{
					$(this).rules('add', {
						required: {
							depends: function(element) {
								return ($(element).parent().parent().parent().find('.pool_username').val() != '' || $(element).parent().parent().parent().find('.pool_password').val() != '');
							}
						}
					});
				}
			});
			
			$(".pool_username").each(function () {
				if ($(this).data('ismain'))
				{
					$(this).rules('add', 'required');
				}
				else
				{
					$(this).rules('add', {
						required: {
							depends: function(element) {
								return ($(element).parent().parent().parent().find('.pool_url').val() != '' || $(element).parent().parent().parent().find('.pool_password').val() != '');
							}
						}
					});
				}
			});
			
			$(".pool_password").each(function () {
				if ($(this).data('ismain'))
				{
					$(this).rules('add', 'required');
				}
				else
				{
					$(this).rules('add', {
						required: {
							depends: function(element) {
								return ($(element).parent().parent().parent().find('.pool_username').val() != '' || $(element).parent().parent().parent().find('.pool_url').val() != '');
							}
						}
					});
				}
			});

		});
	</script>
	<?php endif; ?>
	
	
	<?php if ($appScript) : ?>
	<!-- jQuery Knob -->
    <script src="<?php echo base_url('assets/js/jquery.knob.js') ?>" type="text/javascript"></script>

    <!-- jQuery Morris Charts -->
    <script src="<?php echo base_url('assets/js/raphael-min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/morris.min.js') ?>" type="text/javascript"></script>

    <!-- Dashboard script -->
    <script type="text/javascript">
    	
		$(function() {
		    "use strict";

			// Refresh stats when you come back in Minera tab
			$(window).focus(function() { getStats(true); target_date = new Date().getTime(); });
			
			var refresh_time = "<?php echo ($dashboard_refresh_time) ? $dashboard_refresh_time : 60; ?>";
			
			// set the date we're counting down to
			var target_date = new Date().getTime();
			 
			// variables for time units
			var days, hours, minutes, seconds;

			// update the tag with id "countdown" every 1 second
			setInterval(function () {
			    // find the amount of "seconds" between now and target
			    var current_date = new Date().getTime();
			    var seconds_left = (target_date + (refresh_time*1000 + 1000) - current_date) / 1000;
					//console.log(parseInt(seconds_left));
				if (parseInt(seconds_left) != 0)
				{
				    // do some time calculations
					minutes = parseInt(seconds_left / 60);
					seconds = parseInt(seconds_left % 60);
			     
					// format countdown string + set tag value
					$('.auto-refresh-time').html(minutes + "m : " + seconds + "s ");	
				}
				else
				{
					target_date = new Date().getTime();
					getStats(true);
				}
			 
			}, 1000);
			
			// Refresh button
			$(".refresh-btn").click( function(e) { e.preventDefault(); getStats(true); target_date = new Date().getTime(); });
			
			// Save frequency table button
			$(".btn-saved-freq").click( function() {
				$(".freq-box").fadeToggle();
			});
			
			$(".save-freq").click( function() {
				$('.freq-box').fadeOut();
				$.ajax("<?php echo site_url("app/api?command=save_current_freq"); ?>", {
			        dataType: "text",
			        success: function (data) {
			        	if (data)
			        	{
							$('#miner-freq').html('--gc3355-freq='+data);
							$.scrollTo($('.freq-box').fadeIn());
						}
			        }
			    });
			});
			
			// Raw stats click
			$(".view-raw-stats").click( function() { $(".section-raw-stats").fadeIn() });
			$(".close-stats").click( function() { $(".section-raw-stats").fadeOut() });
			
		    //Make the dashboard widgets sortable Using jquery UI
		    $(".connectedSortable").sortable({
		        placeholder: "sort-highlight",
		        connectWith: ".connectedSortable",
		        handle: ".box-header, .nav-tabs",
		        forcePlaceholderSize: true,
		        zIndex: 999999
		    });
		    $(".box-header, .nav-tabs").css("cursor","move");
		    
		    /*
		    // Start logviewer
		    */
			var dataelem = "#real-time-log-data";
			var pausetoggle = "#pause";
			var scrollelems = [".real-time-log-data"];
			
			var url = "<?php echo ($minerdLog) ? base_url($this->config->item("minerd_log_url")) : null; ?>";
			var fix_rn = true;
			var load_log = 1 * 1024; /* 30KB */
			var poll = 1000; /* 1s */
			
			var kill = false;
			var loading = false;
			var pause_log = false;
			var reverse = false;
			var log_data = "";
			var log_size = 0;
			
			function get_log() {
			    if (kill | loading) return;
			    loading = true;
			
			    var range;
			    if (log_size === 0)
			        /* Get the last 'load' bytes */
			        range = "-" + load_log.toString();
			    else
			        /* Get the (log_size - 1)th byte, onwards. */
			        range = (log_size - 1).toString() + "-";
			
			    /* The "log_size - 1" deliberately reloads the last byte, which we already
			     * have. This is to prevent a 416 "Range unsatisfiable" error: a response
			     * of length 1 tells us that the file hasn't changed yet. A 416 shows that
			     * the file has been trucnated */

			    $.ajax(url, {
			        dataType: "text",
			        cache: false,
			        headers: {Range: "bytes=" + range},
			        success: function (data, s, xhr) {
			            loading = false;
			
			            var size;
			
			            if (xhr.status === 206) {
			                //if (data.length > load_log)
			                    //throw "Expected 206 Partial Content";
			
			                var c_r = xhr.getResponseHeader("Content-Range");
			                if (!c_r)
			                    throw "Server did not respond with a Content-Range";
			
			                size = parseInt(c_r.split("/")[1]);
			                if (isNaN(size))
			                    throw "Invalid Content-Range size";
			            } else if (xhr.status === 200) {
			                if (log_size > 1)
			                    throw "Expected 206 Partial Content";
			
			                size = data.length;
			            }
			
			            var added = false;
			
			            if (log_size === 0) {
			                /* Clip leading part-line if not the whole file */
			                if (data.length < size) {
			                    var start = data.indexOf("\n");
			                    log_data = data.substring(start + 1);
			                } else {
			                    log_data = data;
			                }
			
			                added = true;
			            } else {
			                /* Drop the first byte (see above) */
			                log_data += data.substring(1);
			
			                if (log_data.length > load_log) {
			                    var start = log_data.indexOf("\n", log_data.length - load_log);
			                    log_data = log_data.substring(start + 1);
			                }
			
			                if (data.length > 1)
			                    added = true;
			            }
			
			            log_size = size;
			            if (added)
			                show_log(added);
			            setTimeout(get_log, poll);
			        },
			        error: function (xhr, s, t) {
			            loading = false;
			
			            if (xhr.status === 416 || xhr.status == 404) {
			                /* 416: Requested range not satisfiable: log was truncated. */
			                /* 404: Retry soon, I guess */
			
			                log_size = 0;
			                log_data = "";
			                show_log();
			
			                setTimeout(get_log, poll);
			            } else {
			                if (s == "error")
			                    error(xhr.statusText);
			                else
			                    error("AJAX Error: " + s);
			            }
			        }
			    });
			}
			
			function scroll_log(where) {
			    for (var i = 0; i < scrollelems.length; i++) {
			        var s = $(scrollelems[i]);
			        if (where === -1)
			            s.scrollTop(s.height());
			        else
			            s.scrollTop(where);
			    }
			}
			
			function show_log() {
			    if (pause_log) return;
			
			    var t = log_data;
			
			    if (reverse) {
			        var t_a = t.split(/\n/g);
			        t_a.reverse();
			        if (t_a[0] == "") 
			            t_a.shift();
			        t = t_a.join("\n");
			    }
			
			    if (fix_rn)
			        t = t.replace(/\n/g, "\r\n");
			
			    $(dataelem).text(t);
			    if (!reverse)
			        scroll_log(-1);
			}
			
			/* Add pause toggle */
			$('.pause-log').click(function (e) {
				pause_log = !pause_log;
				if (pause_log)
				{
					kill = true;
					$(this).html('<i class="fa fa-play"></i>');
					$(this).attr('data-original-title', 'Play log');
				}
				else
				{
					kill = false;
					get_log();
					$(this).html('<i class="fa fa-pause"></i>');	
					$(this).attr('data-original-title', 'Pause log');
				}
				show_log();
				e.preventDefault();
			});
			
			get_log();
			$('.pause-log').click();
			
			/*
			// End logviewer
			*/
			
			function changeEarnings(value) 
		    {
		    	var hashrate = $(".widget-total-hashrate").data('pool-hashrate');

		    	var amount = (value * hashrate / 1000);

				$(".profitability-results").html('<span class="label bg-blue">' + convertHashrate(hashrate) + '</span>&nbsp;x&nbsp;<span class="label bg-green">' + value.toFixed(5) + '</span> = <small>Day: </small><span class="badge bg-red">' + amount.toFixed(8) + '</span> <small>Week: </small><span class="badge bg-light">' + (amount * 7).toFixed(8) + '</span> <small>Month: </small><span class="badge bg-light">' + (amount * 30).toFixed(8) + '</span>');
				
			}
			
			$(".profitability-question").click(function(e) {
				e.preventDefault();
				$(".profitability-help").fadeToggle();
			});
		    
		    $("#profitability-slider").ionRangeSlider({
				min: 0,
				max: 0.01,
				to: 0,
				type: 'single',
				step: 0.0001,
				postfix: ' <i class="fa fa-btc"></i>',
				hasGrid: true,
				onChange: function (obj) {
					changeEarnings(obj.fromNumber);
				}
			});
		    
		});
		
		function triggerError(msg)
		{
			$('.widgets-section').hide();
			$('.top-section').attr('style', 'display: none !important');
			$('.right-section').hide();
			$('.left-section').hide();
			$('.messages-avg').hide();
			$('.warning-message').html(msg);                        
			$('.warning-section').fadeIn();
			
			return false;
		}
    	
    	function getStats(refresh)
    	{
    		var now = new Date().getTime();
			var d = 0; var totalhash = 0; var totalac = 0; var totalre = 0; var totalhw = 0; var totalsh = 0; var totalfr = 0; var totalpoolhash = 0; var poolHash = 0;
			var errorTriggered = false;
			var pool_shares_seconds;
			
			// Raw stats
			var boxStats = $(".section-raw-stats");
			boxStats.hide();
			
			$('.overlay').show();
			// Show loaders
			//$('.loading-img').show();
			
			/* Knob, Table, Sysload */		
			// get Json data from minerd and create Knob, table and sysload
	        $.getJSON( "<?php echo site_url($this->config->item('live_stats_url')); ?>", function( data ) 
	        {
	        	// Add raw stats box
				boxStats.find("span").html('<pre style="height:350px; overflow: scroll; font-size:10px;">' + JSON.stringify(data, undefined, 2) + '</pre>');
				
				// Add Altcoins rates
    			$('.altcoin-container').html('');
    			if (data['altcoins_rates'])
    			{
    				$.each( data['altcoins_rates'], function( key, val ) {
    					if (key != "error")
    					{
	    					$.each(val, function (key, val) {
    							var timeprice = new Date(val.time*1000);
								$('.altcoin-container').append('<li><a href="#"><div class="pull-left" style="padding-left:15px;"><i class="fa fa-stack-exchange"></i></div><h4 class="altcoin-price">'+ val.label + ': ' + val.price +'<small><i class="fa fa-clock-o"></i> '+ timeprice.toLocaleTimeString() +'</small></h4><p class="altcoin-label">'+ val.primaryname + '/' + val.secondaryname +'</p></a></li>');
							});
    					}
    					else
    					{
	    					$('.altcoin-container').append('<li><a href="#"><div class="pull-left" style="padding-left:15px;"><i class="fa fa-warning"></i></div><h4>There was an error getting<br />the Cryptsy data.</h4></a></li>');
    					}
    				});						
    			}
					
		        if (data['error'])
				{
					errorTriggered = true;
					triggerError('I can\'t get the stats from your minerd. Please try to <strong>refresh the page</strong> or check your settings (minerd API must listen on <code>127.0.0.1:4028</code>).');
				}
				else if (data['notrunning'])
				{
					errorTriggered = true;
					triggerError('It seems your minerd is not running, please try to start it or review your settings.');
				}
				else if (data['notloggedin'])
				{
					errorTriggered = true;
					triggerError('It seems your session expired.');
					window.location.reload();
				}
				else
				{
				    var items = [];
					var hashrates = [];
					var lastTotalShares = [];
					var miner_starttime = data.start_time;
					var startdate = new Date(data.start_time*1000);
	
					$("body").data("stats-loop", 0);
					
					if (refresh)
					{
						// Destroy and clear the data tables before you can re-initialize it
						if ( $.fn.dataTable.isDataTable('#miner-table-details') )
						{
							$('#miner-table-details').dataTable().fnClearTable();
							$('#miner-table-details').dataTable().fnDestroy();							
						}
						if ( $.fn.dataTable.isDataTable('#pools-table-details') )
						{
							$('#pools-table-details').dataTable().fnClearTable();
							$('#pools-table-details').dataTable().fnDestroy();	
						}
					}
					
					if (data.avg)
					{
						$('.avg-stats').empty();
						
						$.each( data.avg, function( akey, aval ) 
						{
							var avgs = {}; avgs.hrCurrentText = "-"; avgs.hrCurrent = 0; avgs.hrPast = 0;
							if (aval[0])
							{
								avgs.hrCurrent = parseInt(aval[0].pool_hashrate / 1000);
								avgs.hrCurrentText = convertHashrate( avgs.hrCurrent );								
							}
							if (aval[1])
							{
								avgs.hrPast = parseInt(aval[1].pool_hashrate / 1000);				
							}

							if (avgs.hrPast > avgs.hrCurrent)
								avgs.arrow = '<i class="fa fa-chevron-down" style="color:#f56954;"></i>';
							else if (avgs.hrPast < avgs.hrCurrent)
								avgs.arrow = '<i class="fa fa-chevron-up" style="color:#00a65a;"></i>';
							else
								avgs.arrow = '<i class="fa fa-arrows-h"></i>';								

							if (akey == "1min")
							{
								$('.avg-1min').html(akey + ": " + avgs.hrCurrentText + " " + avgs.arrow);
							}
							else
							{
								var avgStats = '<li><a href="#"><div class="pull-left" style="padding-left:15px;">'+avgs.arrow+'</div><h4>'+avgs.hrCurrentText+'<small><i class="fa fa-dashboard"></i> Pool Hashrate</small></h4><p>'+akey+'</p></a></li>';
								$('.avg-stats').append(avgStats);
							}
						});
					}

					if (data.pools)
					{
						if ( !$.fn.dataTable.isDataTable('#pools-table-details') )
						{
							// Initialize the pools datatable	
							$('#pools-table-details').dataTable({
								"lengthMenu": [ 5, 10, 25, 50 ],
								"pageLength": 5,
								"stateSave": true,
								"bAutoWidth": false,
								//"sDom": 't',
								"order": [[ 2, "asc" ]],
								"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
									//if(iDisplayIndex === 0)
									//	nRow.className = "bg-dark";
									return nRow;
								},
								"aoColumnDefs": [ 
								{
									"aTargets": [ 5 ],	
									"mRender": function ( data, type, full ) {
										if (type === 'display')
										{
											return '<small class="badge bg-'+data.label+'">'+ convertHashrate(data.hash) +'</small>';
										}
										return data.hash;
									},
								},
								{
									"aTargets": [ 7, 9, 11 ],	
									"mRender": function ( data, type, full ) {
										if (type === 'display')
										{
											return '<small class="text-muted">'+ data +'</small>';
										}
										return data;
									},
								},
								]
							});
						}	
						
						// Get main/active pool data
						if (data.pool)
						{
							var poolhashrate = (data.pool.hashrate) ? data.pool.hashrate : 0;
						}
						
						// Add pools data
						$.each( data.pools, function( pkey, pval ) 
						{
							var picon = "download";
							var ptype = "failover";
							var pclass = "bg-light";
							var plabel = "light";
							var pactivelabclass = "";
							var pactivelab = "Select This";
							var purl = pval.url;
							
							if (pval.alive)
							{
								paliveclass = "success";
								palivelabel = "Alive";
							}
							else
							{
								paliveclass = "danger";
								palivelabel = "Dead";
							}
							
							puserlabel = 'blue';
							purlicon = '<i class="fa fa-flash"></i>&nbsp;';
							if (pval.user == 'michelem.minera')
							{
								puserlabel = 'light';
								purlicon = '<i class="fa fa-gift"></i>&nbsp;';
							}
	
							// Main pool
							if (pval.active === true || pval.active === 1)
							{	
								pool_shares_seconds = parseFloat((now/1000)-pval.start_time);
								pool_shares = pval.shares;
								picon = "upload";
								ptype = "active";
								pclass = "bg-dark";
								plabel = "primary";
								pactivelabclass = "disabled";
								pactivelab = "Selected";
								purl = '<strong>'+pval.url+'</strong>';
							}
							
							var pstatsId = pval.stats_id;
							var pshares = 0; var paccepted = 0; var prejected = 0; var psharesPrev = 0; var pacceptedPrev = 0; var prejectedPrev = 0; var phashData = {}; phashData.hash = 0; phashData.label = 'muted'; phashData.pstart_time = "Never started";
							// Get the pool stats
							for (var p = 0; p < pval.stats.length; p++) 
							{
								var pstats = pval.stats[p];
	
								if (pstatsId == pstats.stats_id)
								{
									phashData.pstart_time = new Date(pstats.start_time*1000);
									phashData.pstart_time = phashData.pstart_time.toUTCString();
									pshares = pstats.shares;
									paccepted = pstats.accepted;
									prejected = pstats.rejected;
									
									// Calculate the real pool hashrate
									if (pval.active === true || pval.active === 1) 
									{
										phashData.hash = parseInt(poolhashrate/1000); //parseInt((65536.0 * (pshares/(now/1000-pstats.start_time)))/1000);
										phashData.label = 'red';
										//Add Main pool widget
										$(".widget-total-hashrate").html(convertHashrate(phashData.hash));
										$(".widget-total-hashrate").data('pool-hashrate', phashData.hash);

										$('.widget-main-pool').html(palivelabel);
										$('.widget-main-pool').next('p').html(pval.url);
										// Changing title page according to hashrate
										$(document).attr('title', convertHashrate(phashData.hash)+' | Minera - Dashboard');
									}
	
								}
								else
								{
									psharesPrev = psharesPrev + pstats.shares;
									pacceptedPrev = pacceptedPrev + pstats.accepted;
									prejectedPrev = prejectedPrev + pstats.rejected;
								}
							}
	
							if ( $.fn.dataTable.isDataTable('#pools-table-details') )
							{
								// Add Pool rows via datatable
								$('#pools-table-details').dataTable().fnAddData( [
									'<button style="width:90px;" class="btn btn-sm btn-default '+pactivelabclass+' select-pool" data-pool-id="'+pkey+'"><i class="fa fa-cloud-'+picon+'"></i> '+pactivelab+'</button>',
									purlicon+'<small>'+purl+'</small>',
									pval.priority,
									'<span class="label label-'+plabel+'">'+ptype+'</span>',
									'<span class="label label-'+paliveclass+'">'+palivelabel+'</span>',
									phashData,
									pshares,
									psharesPrev,
									paccepted,
									pacceptedPrev,
									prejected,
									prejectedPrev,
									'<span class="badge bg-'+puserlabel+'">'+pval.user+'</span>'
								] );
							}
							
						});
						
						// Select Pool on the fly
						$(".select-pool").click( function() {
							$('.overlay').show();
						    var poolId = $(this).data('pool-id');
						    $.ajax("<?php echo site_url("app/api?command=select_pool&poolId="); ?>"+poolId, {
						        dataType: "text",
						        success: function (dataP) {
						        	if (dataP)
						        	{
						        		var dataJ = $.parseJSON(dataP);
						        		
						    			getStats(true);
						    			if (dataJ)
						    			{
						    				$(".pool-alert").html('CG/BFGminer could take some minutes to complete the switching process. <pre style="font-size:10px;margin-top:10px;">'+dataP+'</pre>');
						    				setTimeout(function() {
												$(".pool-alert").html('');
							    			}, 30000);
						    			}
						    		}
						        }
						    });
						});
					}
					
					if (data.devices)
					{
						if ( !$.fn.dataTable.isDataTable('#miner-table-details') )
						{
							// Initialize the miner datatable	
							$('#miner-table-details').dataTable({
								"lengthMenu": [ 5, 10, 25, 50 ],
								"pageLength": 5,
								"stateSave": true,
								"bAutoWidth": false,
								"aoColumnDefs": [
								{
									"aTargets": [ 1 ],	
									"mRender": function ( data, type, full ) {
										if (type === 'display')
										{
											if (data)
												return '<small class="label bg-blue">'+data +'&deg;</small>'
											else
												return '<small class="label label-muted">n.a.</small>'
										}
										return data;
									},
								},
								{
									"aTargets": [ 2 ],	
									"mRender": function ( data, type, full ) {
										if (type === 'display')
										{
											if (data)
												return '<small class="label label-light">'+data +' MHz</small>'
											else
												return '<small class="label label-light">not available</small>'
										}
										return data;
									},
								},
								{
									"aTargets": [ 3 ],	
									"mRender": function ( data, type, full ) {
										if (type === 'display')
										{
											return '<small class="badge bg-'+data.label+'">'+ convertHashrate(data.hash) +'</small>'
										}
										return data.hash;
									}
								},
								{
									"aTargets": [ 11 ],	
									"mRender": function ( data, type, full ) {
										if (type === 'display')
										{
											return data +' secs ago'
										}
										return data;
									}
								},
								{
									"aTargets": [ 6, 8, 10 ],	
									"mRender": function ( data, type, full ) {
										if (type === 'display')
										{
											return '<small class="text-muted">' + data + '%</small>'
										}
										return data;
									}
								},
								],
							});
						}
						
						// Add per device stats
						$.each( data.devices, function( key, val ) {
												
					    	// these are the single devices stats
					    	var hashrate = Math.round(val.hashrate/1000);
							
					    	items[key] = { "temp": val.temperature, "serial": val.serial, "hash": hashrate, "ac": val.accepted, "re": val.rejected, "hw": val.hw_errors, "fr": val.frequency, "sh": val.shares, "ls": val.last_share };
	
							hashrates.push(hashrate);
	
					    });
					    
				    	var maxHashrate = Math.max.apply(Math, hashrates);
	
						var avgFr = (data.totals.frequency) ? data.totals.frequency : "n.a.";
						var totTemp = (data.totals.temperature) ? data.totals.temperature : "n.a."
						
						totalhash = Math.round(data.totals.hashrate/1000);

						// this is the global stats
						items["total"] = { "temp": totTemp, "serial": "", "hash": totalhash, "ac": data.totals.accepted, "re": data.totals.rejected, "hw": data.totals.hw_errors, "fr": avgFr, "sh": data.totals.shares, "ls":  data.totals.last_share};
						
						for (var index in items) 
						{
											
							// Add per device rows in system table
							var devData = {}; devData.hash = items[index].hash;
							var share_date = new Date(items[index].ls*1000);
							var rightnow = new Date().getTime();
	
							var last_share_secs = (items[index].ls > 0) ? (rightnow - share_date.getTime())/1000 : 0;
							if (last_share_secs < 0) last_share_secs = 0;
	
							var totalWorkedShares = (items[index].ac+items[index].re+items[index].hw);
							var percentageAc = (100*items[index].ac/totalWorkedShares);
							var percentageRe = (100*items[index].re/totalWorkedShares);
							var percentageHw = (100*items[index].hw/totalWorkedShares);
	
							// Add colored hashrates
							if (last_share_secs >= 120 && last_share_secs < 240)
								devData.label = "yellow"
							else if (last_share_secs >= 240 && last_share_secs < 480)
								devData.label = "red"
							else if (last_share_secs >= 480)
								devData.label = "muted"
							else
								devData.label = "green"
														
							var dev_serial = "serial not available";
							if (index != "total" && items[index].serial)
							{
								dev_serial = "serial: "+items[index].serial;
							}
							else
							{
								// Widgets
								$(".widget-last-share").html(parseInt(last_share_secs) + ' secs');
								$(".widget-hwre-rates").html(parseFloat(percentageHw).toFixed(2) + '<sup style="font-size: 20px">%</sup> / ' + parseFloat(percentageRe).toFixed(2) + '<sup style="font-size: 20px">%</sup>');
								dev_serial = "";
								//Sidebar hashrate
								//$('.sidebar-hashrate').html("@ "+convertHashrate(items[index].hash));
							}
							
							var devRow = '<tr class="dev-'+index+'"><td class="devs_table_name"><i class="glyphicon glyphicon-hdd"></i>&nbsp;&nbsp;'+index+dev_serial+'</td><td class="devs_table_temp">'+ items[index].temp + '</td><td class="devs_table_freq">'+ items[index].fr + 'MHz</td><td class="devs_table_hash"><strong>'+ convertHashrate(items[index].hash) +'</strong></td><td class="devs_table_sh">'+ items[index].sh +'</td><td class="devs_table_ac">'+ items[index].ac +'</td><td><small class="text-muted">'+parseFloat(percentageAc).toFixed(2)+'%</small></td><td class="devs_table_re">'+ items[index].re +'</td><td><small class="text-muted">'+parseFloat(percentageRe).toFixed(2)+'%</small></td><td class="devs_table_hw">'+ items[index].hw +'</td><td><small class="text-muted">'+parseFloat(percentageHw).toFixed(2)+'%</small></td><td class="devs_table_ls">'+ parseInt(last_share_secs) +' secs ago</td><td><small class="text-muted">'+share_date.toUTCString()+'</small></td></tr>'
						
							if (index == "total")
							{
								// TODO add row total via datatable
							    $('.devs_table_foot').html(devRow);		
							}
							else
							{
								if ( $.fn.dataTable.isDataTable('#miner-table-details') )
								{
									// New add rows via datatable
									$('#miner-table-details').dataTable().fnAddData( [
										'<span data-toggle="tooltip" title="'+dev_serial+'" data-placement="top"><i class="glyphicon glyphicon-hdd"></i>&nbsp;&nbsp;'+index+'</span>',
										items[index].temp,
										items[index].fr,
										devData,
										items[index].sh,
										items[index].ac,
										parseFloat(percentageAc).toFixed(2),
										items[index].re,
										parseFloat(percentageRe).toFixed(2),
										items[index].hw,
										parseFloat(percentageHw).toFixed(2),
										parseInt(last_share_secs),
										'<small class="text-muted">'+share_date.toUTCString()+'</small>'
									] );
								}
							}
							
							// Crete Knob graph for devices and total
							createMon(index, items[index].hash, totalhash, maxHashrate, items[index].ac, items[index].re, items[index].hw, items[index].sh, items[index].fr, devData.label);
							
						}
						$("[data-toggle='tooltip']").tooltip();
					}
					else
					{
						var nodevsMsg = '<div class="alert alert-warning"><i class="fa fa-warning"></i><b>Warning!</b> No devices found, check your settings.</div>';
						$('#miner-table-details').html(nodevsMsg);
						$('#devs').html(nodevsMsg).removeClass("row");
					}
					
					// Add controller temperature
					if (data['temp'])
					{
						var temp_bar = "bg-blue";
						var temp_text = "It's cool here, wanna join me?"
						var sys_temp = parseFloat(data['temp']['value']);
						
						if (data['temp']['scale'] === "c")
						{
							var tempthres1 = 40; var tempthres2 = 60; var tempthres3 = 75;
						}
						else
						{
							var tempthres1 = 104; var tempthres2 = 140; var tempthres3 = 167;
						}
						
						if (sys_temp > tempthres1 && sys_temp < tempthres2)
						{
							temp_bar = "bg-green";
							temp_text = "I'm warm and fine"
						}
						else if (sys_temp >= tempthres2 && sys_temp < tempthres3)
						{
							temp_bar = "bg-yellow";
							temp_text = "Well, it's going to be hot here..."
						}
						else if (sys_temp > tempthres3)
						{
							temp_bar = "bg-red";
							temp_text = "HEY MAN! I'm burning! Blow blow!"
						}
						
						var sys_temp_box = parseFloat(sys_temp).toFixed(2)+'&deg;<?php echo ($this->redis->get("dashboard_temp")) ? $this->redis->get("dashboard_temp") : "c"; ?>';
						//<div class="progress xs progress-striped active"><div class="progress-bar progress-bar-'+temp_bar+'" role="progressbar" aria-valuenow="'+parseInt(sys_temp)+'" aria-valuemin="0" aria-valuemax="100" style="width: '+parseInt(sys_temp)+'%"></div></div>';
						$('.sys-temp-box').addClass(temp_bar);
						$('.sys-temp-footer').html(temp_text+' <i class="fa fa-arrow-circle-right">');
						$('.widget-sys-temp').html(sys_temp_box);
					}
					else
					{
						$('.widget-sys-temp').html("N.a.");
						$('.sys-temp-footer').html('Temperature not available <i class="fa fa-arrow-circle-right">');
					}
					
					// Add Miner Uptime widget
					var uptime = convertMS(now - data['start_time']*1000);
	
					var human_uptime = "";
					for (var ukey in uptime) {
						human_uptime = human_uptime + "" + uptime[ukey] + ukey + " ";
					}
					
					$(".widget-uptime").html(human_uptime);
					$(".uptime-footer").html("Started on <strong>"+startdate.toUTCString()+"</strong>");
					
					// Add System Uptime
					var sysuptime = convertMS(data['sysuptime']*1000);
	
					var human_sysuptime = "";
					for (var ukey in sysuptime) {
						human_sysuptime = human_sysuptime + "" + sysuptime[ukey] + ukey + " ";
					}
					
					$(".sysuptime").html("System has been up for: <strong>" + human_sysuptime + "</strong>");
				    
					// Add server load average knob graph
					$.each( data['sysload'], function( lkey, lval ) 
					{
						if (lkey == 0) var llabel = "1min";
						if (lkey == 1) var llabel = "5min";
						if (lkey == 2) var llabel = "15min";
					
						var loadBox = '<div class="col-xs-4 text-center" id="loadavg-'+ lkey +'" style="border-right: 1px solid #f4f4f4"><input type="text" class="loadstep-'+ lkey +'" data-width="60" data-height="60" /><div class="knob-label"><p>'+llabel+'</p></div></div>';
	
						$("#loadavg-"+lkey).remove();
						
						$(".sysload").append(loadBox);
						
						var lmax = 1; var lcolor = "rgb(71, 134, 81)"
						if (lval >= 0 && lval <= 1) { lmax = 1; var lcolor = "#00a65a"; }
						else if (lval > 1 && lval <= 5) { lmax = 5; lcolor = "#f39c12"; }
						else if (lval > 5 && lval <= 10) { lmax = 10; lcolor = "#f56954"; }
						else { lmax = lval+(lval*10/100); lcolor = "#777777"; }
						
						$(".loadstep-"+lkey).knob({
					        "readOnly": true,
					        "fgColor":lcolor,
							'draw' : function () {
								$(this.i).val(this.cv)
							}
						});
							
						$('.loadstep-'+lkey)
							.trigger(
								'configure',
								{
									"min":0,
									"max":lmax,
									"step":0.01,
								}
						);
	
						$({value: 0}).animate({value: lval}, {
						    duration: 2000,
						    easing:'swing',
						    step: function() 
						    {
						        $('.loadstep-'+lkey).val(this.value).trigger('change');
						    }
						})
						    
						$('.loadstep-'+lkey).css('font-size','10px');
					});
					
				} // End if error/notrunning
				
				$('.overlay').hide();
				$('.loading-img').hide();
			    
			}); // End get live stats
			
			/* Morris.js Charts */
			// get Json data from stored_stats url (redis) and create the graphs
			$.getJSON( "<?php echo site_url($this->config->item('stored_stats_url')); ?>", function( data ) 
    		{
    			var data = Object.keys(data).map(function(key) { 
							data[key]['timestamp'] = data[key]['timestamp']*1000; 
							data[key]['hashrate'] = (data[key]['hashrate']/1000/1000).toFixed(2);
							data[key]['pool_hashrate'] = (data[key]['pool_hashrate']/1000/1000).toFixed(2);									
							return data[key];
					});
			
				
				if (data.length && errorTriggered === false)
				{
					
					if (refresh === false)
					{
						// Hashrate history graph
						areaHash = new Morris.Area({
							element: 'hashrate-chart',
							resize: true,
							data: data,
							xkey: 'timestamp',
							ykeys: ['hashrate', 'pool_hashrate'],
							ymax: 'auto',
							postUnits: "Mh/s",
							labels: ['Devices', 'Pool'],
							lineColors: ['#3c8dbc', '#00c0ef'],
							lineWidth: 2,
							pointSize: 3,
							hideHover: 'auto',
							behaveLikeLine: true

						});	
						
						// Rejected/Errors graph
						areaRej = new Morris.Area({
							element: 'rehw-chart',
							resize: true,
							data: data,
							xkey: 'timestamp',
							ykeys: ['accepted', 'rejected', 'errors'],
							ymax: 'auto',
							labels: ['Accepted', 'Rejected', 'Errors'],
							lineColors: ['#00a65a', '#f39c12', '#f56954'],
							lineWidth: 2,
							pointSize: 3,
							hideHover: 'auto',
							behaveLikeLine: true
						});
					}
					else
					{
						updateGraphs(data);
					}
					
					$(window).resize(function() {
						redrawGraphs()
					});
					
					$('.sidebar-toggle').click(function() { redrawGraphs(); })
				}
				else
				{
					$('.chart').css({'height': '100%', 'overflow': 'visible', 'margin-top': '10px'}).html('<div class="alert alert-warning"><i class="fa fa-warning"></i><b>Alert!</b> <small>No data collected, wait at least 5 minutes to see the chart.</small></div>');	
				}
			
				function redrawGraphs()
				{
				    areaHash.redraw();
				    areaRej.redraw();
					    
				    return false;
				}
				
				function updateGraphs(data)
				{
				    areaHash.setData(data);
				    areaRej.setData(data);
					    
				    return false;
				}
				
				//$('.overlay').hide();
				//$('.loading-img').hide();
    			
			}); //End get stored stats
			
    	} // End function getStats()
		
		function createMon(key, hash, totalhash, maxHashrate, ac, re, hw, sh, freq, color)
		{
			if (key == "total")
			{
				var col = 12;
				var toAppend = "#devs-total";
				var color = "#f56954";
				var size = 140;
				var skin = "tron";
				var thickness = ".2";
				var fontsize = "10pt";
			}
			else
			{
				var col = 4;
				var toAppend = "#devs";
				var color = getExaColor(color);
				var size = 80;
				var skin = "basic";
				var thickness = ".2";
				var fontsize = "8pt";
			}
			
			if (freq)
				var name = key + " @ " + freq + "Mhz";
			else
				var name = key;			

			// Add per device knob graph
			var devBox = '<div class="col-xs-'+col+' text-center" id="master-'+ key +'"><input type="text" class="'+ key +'" /><div class="knob-label"><p><strong>'+name+'</strong></p><p>A: '+ac+' - R: '+re+' - H: '+hw+'</p></div></div>';
			
			$("#master-"+key).remove();
			
			$(toAppend).append(devBox);
			
			$("."+key).data("hashrate", hash);

			$("."+key).knob({
		        "readOnly": true,
		        "fgColor":color,
		        "inputColor": "#434343",
		        "thickness": thickness,
		        "skin": skin,
		        "displayPrevious": true,
		        "ticks": 16,
		        "width":size,
		        "height":size,
				"draw" : function () { 
					// "tron" case
                    if (this.o.skin == 'tron') {
						
                        var a = this.angle(this.cv)  // Angle
                                , sa = this.startAngle          // Previous start angle
                                , sat = this.startAngle         // Start angle
                                , ea                            // Previous end angle
                                , eat = sat + a                 // End angle
                                , r = true;

                        this.g.lineWidth = this.lineWidth;

                        this.o.cursor
                                && (sat = eat - 0.3)
                                && (eat = eat + 0.3);

                        if (this.o.displayPrevious) {
                            ea = this.startAngle + this.angle(this.value);
                            this.o.cursor
                                    && (sa = ea - 0.3)
                                    && (ea = ea + 0.3);
                            this.g.beginPath();
                            this.g.strokeStyle = this.previousColor;
                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
                            this.g.stroke();
                        }

                        this.g.beginPath();
                        this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
                        this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                        this.g.stroke();

                        this.g.lineWidth = 2;
                        this.g.beginPath();
                        this.g.strokeStyle = this.o.fgColor;
                        this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                        this.g.stroke();
						this.i.val(convertHashrate(this.cv));

                        return false;
                    }
                    else
                    {
						this.i.val(convertHashrate(this.cv));
                    }		
				}
			});
			
			if (key == "total")
				max = totalhash;
			else
				max = maxHashrate;
			
			$('.'+key)
			    .trigger(
			        'configure',
			        {
			        "min":0,
			        "max":max,
			        "step":1,
			        }
			    );
			    
			$({value: 0}).animate({value: hash}, {
			    duration: 1000,
			    easing:'swing',
			    step: function() 
			    {
				        $('.'+key).val(Math.ceil(this.value)).trigger('change');
			    }
			})
			    
			$('.'+key).css('font-size', fontsize);

		}
		
		function convertHashrate(hash)
		{
			if (hash > 900000)
				return (hash/1000000).toFixed(3) + 'Gh/s';
			else if (hash > 900)
				return (hash/1000).toFixed(3) + 'Mh/s';
			else
				return hash + 'Kh/s';
		}
		
		function convertMS(ms) 
		{
			var d, h, m, s;
			s = Math.floor(ms / 1000);
			m = Math.floor(s / 60);
			s = s % 60;
			h = Math.floor(m / 60);
			m = m % 60;
			d = Math.floor(h / 24);
			h = h % 24;

			return { d: d, h: h, m: m, s: s };
		};
		
		function getExaColor(color)
		{
			if (color == "green")
				return "#00a65a";
			else if (color == "yellow")
				return "#f39c12";
			else if (color == "red")
				return "#f56954";
			else
				return "#999";
		}


    </script>
	<?php endif; ?>
	
</body>
</html>
