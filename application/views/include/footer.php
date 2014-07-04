	<script src="<?php echo base_url('assets/js/jquery-2.1.1.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery-ui-1.10.4.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery.ui.touch-punch.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/lodash.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
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

    <!-- General script -->
    <script type="text/javascript">
    	
		$(function() {
		    "use strict";
			
			startTime();
			
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

		});
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
    
    <!-- Settings script -->
    <script type="text/javascript">
    	
		$(function() {
		    "use strict";
		    
		    $(".box-tools").click( function(e) { e.preventDefault(); });
		    
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
		    
		    function changeDonationMood(value) 
		    {
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
			
		    function changeDonationWorth(value) 
		    {
		    	var amount = (0.0018 / 24 / 60 * value);
		    	var string = (value > 0) ? "about" : "exactly";

		    	if (value >= 60)
		    	{
		    		var h = Math.floor(value / 60);
					value = value % 60;
			    	var period = h + ' hour(s) ' + ((value > 0) ? ' and ' + value + ' minute(s)' : '');
		    	}
		    	else
		    	{
			    	var period = value + ' minutes';
		    	}
		    	
				$(".donation-worth").html('<small>Mining for ' + period + ' in a day your donation per MH/s worths ' + string + ':</small> <span class="label label-success"><i class="fa fa-btc"></i>&nbsp;' + amount.toFixed(8) + '</span>');
			}
		    
		    $("#option-minera-donation-time").ionRangeSlider({
				min: 0,
				max: 360,
				to: <?php echo (isset($mineraDonationTime)) ? $mineraDonationTime : 0; ?>,
				type: 'double',
				step: 15,
				postfix: " Mins",
				hasGrid: true,
				onLoad: function (obj) {
					changeDonationMood(obj.toNumber);
					changeDonationWorth(obj.toNumber);
				},
				onChange: function (obj) {
					if (obj.fromNumber > 0)
					{
						$("#option-minera-donation-time").ionRangeSlider('update', { from: 0 });
					}
					changeDonationMood(obj.toNumber);
					changeDonationWorth(obj.toNumber);
				}
			});
			
		    $("#option-startfreq").ionRangeSlider({
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
					}
				},
			    errorPlacement: function(error, element) {
					error.appendTo( $(element).closest(".input-group").parent().after() );
			    },
			    // specifying a submitHandler prevents the default submit, good for the demo
			    /*submitHandler: function() {
			    	alert("submitted!");
			    },*/
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
					$('.auto-refresh-time').html("auto-refreshing in " + minutes + "m / " + seconds + "s ");	
				}
				else
				{
					target_date = new Date().getTime();
					getStats(true);
				}
			 
			}, 1000);
			
			// Refresh button
			$(".refresh-btn").click( function() { getStats(true); target_date = new Date().getTime(); });
			
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
			
			var url = "<?php echo base_url($this->config->item("minerd_log_url")); ?>";
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
			
			$('.overlay').show();
			// Show loaders
			//$('.loading-img').show();
			
			/* Knob, Table, Sysload */		
			// get Json data from minerd and create Knob, table and sysload
	        $.getJSON( "<?php echo site_url($this->config->item('live_stats_url')); ?>", function( data ) 
	        {
				// Add Altcoins rates
    			$('.altcoin-container').html('');
    			if (data['altcoins_rates'])
    			{
    				$.each( data['altcoins_rates'], function( key, val ) {
    					$.each(val, function (key, val) {
    						var timeprice = new Date(val.time*1000);
    						$('.altcoin-container').append('<li><a href="#"><div class="pull-left" style="padding-left:15px;"><i class="fa fa-stack-exchange"></i></div><h4 class="altcoin-price">'+ val.label + ': ' + val.price +'<small><i class="fa fa-clock-o"></i> '+ timeprice.toLocaleTimeString() +'</small></h4><p class="altcoin-label">'+ val.primaryname + '/' + val.secondaryname +'</p></a></li>');
    					});
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
						$('#miner-table-details').dataTable().fnClearTable();
						$('#miner-table-details').dataTable().fnDestroy();		
						$('#pools-table-details').dataTable().fnClearTable();
						$('#pools-table-details').dataTable().fnDestroy();					
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
						// Initialize the pools datatable	
						$('#pools-table-details').dataTable({
							"stateSave": true,
							"bAutoWidth": false,
							"sDom": 't',
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
							if (pval.active === 1)
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
									if (pval.active === 1) 
									{
										phashData.hash = parseInt((65536.0 * (pshares/(now/1000-pstats.start_time)))/1000);
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
	
							// Add Pool rows via datatable
							$('#pools-table-details').dataTable().fnAddData( [
								'<button style="width:90px;" class="btn btn-sm btn-default '+pactivelabclass+' select-pool" data-pool-id="'+pval.priority+'"><i class="fa fa-cloud-'+picon+'"></i> '+pactivelab+'</button>',
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
						    			if (dataJ.err === 0)
						    			{
							    			getStats(true);
						    			}
						    			else
						    				$(".pool-alert").html('Error: <pre>'+dataP+'</pre>');
						    		}
						        }
						    });
						});
					}
					
					if (data.devices)
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
										return '<small class="label label-light">'+data +' MHz</small>'
									}
									return data;
								},
							},
							{
								"aTargets": [ 2 ],	
								"mRender": function ( data, type, full ) {
									if (type === 'display')
									{
										return '<small class="badge bg-'+data.label+'">'+ convertHashrate(data.hash) +'</small>'
									}
									return data.hash;
								}
							},
							{
								"aTargets": [ 10 ],	
								"mRender": function ( data, type, full ) {
									if (type === 'display')
									{
										return data +' secs ago'
									}
									return data;
								}
							},
							{
								"aTargets": [ 5, 7, 9 ],	
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
						
						// Add per device stats
						$.each( data.devices, function( key, val ) {
												
					    	// these are the single devices stats
					    	var hashrate = Math.round(val.hashrate/1000);
					    	items[key] = { "serial": val.serial, "hash": hashrate, "ac": val.accepted, "re": val.rejected, "hw": val.hw_errors, "fr": val.frequency, "sh": val.shares, "ls": val.last_share };
	
							hashrates.push(hashrate);
	
					    });
					    
				    	var maxHashrate = Math.max.apply(Math, hashrates);
	
						var avgFr = data.totals.frequency;
				    	
						totalhash = Math.round(data.totals.hashrate/1000);
						
						// this is the global stats
						items["total"] = { "serial": "", "hash": totalhash, "ac": data.totals.accepted, "re": data.totals.rejected, "hw": data.totals.hw_errors, "fr": data.totals.frequency, "sh": data.totals.shares, "ls":  data.totals.last_share};
						
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
														
							var dev_serial = "";
							if (index != "total")
							{
								dev_serial = ' <small class="text-muted">('+items[index].serial+')</small>';	
							}
							else
							{
								// Widgets
								$(".widget-last-share").html(parseInt(last_share_secs) + ' secs');
								$(".widget-hwre-rates").html(parseFloat(percentageHw).toFixed(2) + '<sup style="font-size: 20px">%</sup> / ' + parseFloat(percentageRe).toFixed(2) + '<sup style="font-size: 20px">%</sup>');
								
								//Sidebar hashrate
								//$('.sidebar-hashrate').html("@ "+convertHashrate(items[index].hash));
							}
							
							var devRow = '<tr class="dev-'+index+'"><td class="devs_table_name"><i class="glyphicon glyphicon-hdd"></i>&nbsp;&nbsp;'+index+dev_serial+'</td><td class="devs_table_freq">'+ items[index].fr + ' Mhz</td><td class="devs_table_hash"><strong>'+ convertHashrate(items[index].hash) +'</strong></td><td class="devs_table_sh">'+ items[index].sh +'</td><td class="devs_table_ac">'+ items[index].ac +'</td><td><small class="text-muted">'+parseFloat(percentageAc).toFixed(2)+'%</small></td><td class="devs_table_re">'+ items[index].re +'</td><td><small class="text-muted">'+parseFloat(percentageRe).toFixed(2)+'%</small></td><td class="devs_table_hw">'+ items[index].hw +'</td><td><small class="text-muted">'+parseFloat(percentageHw).toFixed(2)+'%</small></td><td class="devs_table_ls">'+ parseInt(last_share_secs) +' secs ago</td><td><small class="text-muted">'+share_date.toUTCString()+'</small></td></tr>'
						
							if (index == "total")
							{
								// TODO add row total via datatable
							    $('.devs_table_foot').html(devRow);		
							}
							else
							{
								// New add rows via datatable
								$('#miner-table-details').dataTable().fnAddData( [
									'<i class="glyphicon glyphicon-hdd"></i>&nbsp;&nbsp;'+index+dev_serial,
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
							
							// Crete Knob graph for devices and total
							createMon(index, items[index].hash, totalhash, maxHashrate, items[index].ac, items[index].re, items[index].hw, items[index].sh, items[index].fr, devData.label);
							
						}
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
				return hash/1000000 + 'Gh/s';
			else if (hash > 900)
				return hash/1000 + 'Mh/s';
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
