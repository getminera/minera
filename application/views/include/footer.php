	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/1.3.1/lodash.min.js"></script>
	<script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/custom.js') ?>"></script>

    <!-- AdminLTE App -->
    <script src="<?php echo base_url('assets/js/app.js') ?>" type="text/javascript"></script>

	<?php if ($settingsScript) : ?>
	<!-- jQuery Validation -->
    <script src="<?php echo base_url('assets/js/jquery.validate.min.js') ?>" type="text/javascript"></script>
    
    <!-- Settings script -->
    <script type="text/javascript">
    	
		$(function() {
		    "use strict";

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
			var validator = $("#minersettings").validate({
				rules: {
					minerd_manual_settings: "required"
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
			
			$(".pool_url").each(function () {
				if ($(this).attr("name") == "pool_url[0]")
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
				if ($(this).attr("name") == "pool_username[0]")
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
				if ($(this).attr("name") == "pool_password[0]")
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

    <!-- DATA TABES SCRIPT -->
	<script src="<?php echo base_url('assets/js/jquery.dataTables.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('assets/js/dataTables.bootstrap.js') ?>" type="text/javascript"></script>
    
    <!-- jQuery Morris Charts -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="<?php echo base_url('assets/js/morris.min.js') ?>" type="text/javascript"></script>

    <!-- Dashboard script -->
    <script type="text/javascript">
    	
		$(function() {
		    "use strict";

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
			
			$(".refresh-btn").click( function() { getStats(true); target_date = new Date().getTime(); });
			
		    //Make the dashboard widgets sortable Using jquery UI
		    $(".connectedSortable").sortable({
		        placeholder: "sort-highlight",
		        connectWith: ".connectedSortable",
		        handle: ".box-header, .nav-tabs",
		        forcePlaceholderSize: true,
		        zIndex: 999999
		    });
		    $(".box-header, .nav-tabs").css("cursor","move");
		    
		});
		
		function triggerError(msg)
		{
			$('.widgets-section').hide();
			$('.top-section').hide();
			$('.right-section').hide();
			$('.left-section').hide();
			$('.warning-message').html(msg);                        
			$('.warning-section').fadeIn();
			
			return false;
		}
    	
    	function getStats(refresh)
    	{
    		var now = new Date().getTime();
			var d = 0; var totalhash = 0; var totalac = 0; var totalre = 0; var totalhw = 0; var totalsh = 0; var totalfr = 0;
			
			$('.overlay').show();
			//$('.loading-img').show();
			
			/* Morris.js Charts */
			// get Json data from stored_stats url (redis) and create the graphs
			$.getJSON( "<?php echo site_url($this->config->item('stored_stats_url')); ?>", function( data ) 
	        {
	        	var data = Object.keys(data).map(function(key) { 
							data[key]['timestamp'] = data[key]['timestamp']*1000; 
							data[key]['hashrate'] = (data[key]['hashrate']/1000/1000).toFixed(2);
							
							return data[key];
					});
				
				if (data.length)
				{
					// Hashrate history graph
					var areaHash = new Morris.Area({
						element: 'hashrate-chart',
						resize: true,
						data: data,
						xkey: 'timestamp',
						ykeys: ['hashrate'],
						ymax: 'auto',
						postUnits: "Mh/s",
						labels: ['Hashrate'],
						lineColors: ['#65b8e7'],
						hideHover: 'auto',
					});	
					
					// Rejected/Errors graph
					var areaRej = new Morris.Area({
						element: 'rehw-chart',
						resize: true,
						data: data,
						xkey: 'timestamp',
						ykeys: ['rejected', 'errors'],
						ymax: 'auto',
						labels: ['Rejected', 'Errors'],
						lineColors: ['#f5b989', '#f59189'],
						hideHover: 'auto',
					});
					
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
	        	
			});
			
			/* Knob, Table, Sysload */		
			// get Json data from minerd and create Knob, table and sysload
	        $.getJSON( "<?php echo site_url($this->config->item('live_stats_url')); ?>", function( data ) 
	        {
		        if (data['error'])
				{
					triggerError('I can\'t get the stats from your minerd. Please try to <strong>refresh the page</strong> or check your settings (minerd API must listen on <code>127.0.0.1:4028</code>).');
				}
				else if (data['notrunning'])
				{
					triggerError('It seems your minerd is not running, please try to start it or review your settings.');
				}
				else
				{
				    var items = [];
					var hashrates = [];
					var lastTotalShares = [];
					
					var startdate = new Date(data['start_time']*1000);
					
				    $.each( data['devices'], function( key, val ) {
				    	d = d+1;
				    	//console.log(val);
				    	// Build dev stats from single procs
						var lastShares = [];
				    	var hash = 0; var ac = 0; var re = 0; var hw = 0; var fr = 0; var sh = 0;
				    	for (var i = 0; i < val['chips'].length; i++) {
				    		hash = hash + val['chips'][i]['hashrate'];
				    		ac = ac + val['chips'][i]['accepted'];
				    		re = re + val['chips'][i]['rejected'];
				    		hw = hw + val['chips'][i]['hw_errors'];
				    		fr = fr + val['chips'][i]['frequency'];
				    		sh = sh + val['chips'][i]['shares'];
							lastShares.push(val['chips'][i]['last_share'])
				    	}

				    	devFr = fr/i;
				    	
				    	totalhash = totalhash + hash;
				    	totalac = totalac + ac;
				    	totalre = totalre + re;
				    	totalhw = totalhw + hw;
				    	totalsh = totalsh + sh;
				    	totalfr = totalfr + devFr;
				    	
				    	hash = Math.round(hash/1000);
				    	
				    	var serial = val['serial'];
				    	var lastShare = Math.max.apply(Math, lastShares);

				    	// these are the single devices stats	
				    	items[key] = { "serial": serial, "hash": hash, "ac": ac, "re": re, "hw": hw, "fr": devFr, "sh": sh, "ls": lastShare };
						hashrates.push(hash);
						lastTotalShares.push(lastShare);
				    	
				    });
				    
			    	var maxHashrate = Math.max.apply(Math, hashrates);
			    	var lastTotalShare = Math.max.apply(Math, lastTotalShares);
					var avgFr = Math.round(totalfr/d);
			    	
					totalhash = Math.round(totalhash/1000);
					
					// this is the global stats
					items["total"] = { "serial": "", "hash": totalhash, "ac": totalac, "re": totalre, "hw": totalhw, "fr": avgFr, "sh": totalsh, "ls":  lastTotalShare};
	
					$("body").data("stats-loop", 0);
					
					if (refresh)
					{
						// Destroy and clear the miner table before you can re-initialize it
						$('#miner-table-details').dataTable().fnClearTable();
						$('#miner-table-details').dataTable().fnDestroy();						
					}
					
					for (var index in items) 
					{
						// Crete Knob graph for devices and total
						createMon(index, items[index].hash, totalhash, maxHashrate, items[index].ac, items[index].re, items[index].hw, items[index].sh, items[index].fr);
						
						// Add per device rows in system table
						var share_date = new Date(items[index].ls*1000);
						var last_share_secs = (now - share_date)/1000;
						var totalWorkedShares = (items[index].ac+items[index].re+items[index].hw);
						var percentageAc = (100*items[index].ac/totalWorkedShares);
						var percentageRe = (100*items[index].re/totalWorkedShares);
						var percentageHw = (100*items[index].hw/totalWorkedShares);
						
						var dev_serial = "";
						if (index != "total")
						{
							dev_serial = ' <small class="text-muted">('+items[index].serial+')</small>';	
						}
						else
						{
							$(".widget-total-hashrate").html(convertHashrate(items[index].hash));
							$(".widget-last-share").html(parseInt(last_share_secs) + ' secs');
							$(".widget-hwre-rates").html(parseFloat(percentageHw).toFixed(2) + '<sup style="font-size: 20px">%</sup> / ' + parseFloat(percentageRe).toFixed(2) + '<sup style="font-size: 20px">%</sup>');
						}
						
						var devRow = '<tr class="dev-'+index+'"><td class="devs_table_name"><i class="glyphicon glyphicon-hdd"></i>&nbsp;&nbsp;'+index+dev_serial+'</td><td class="devs_table_freq">'+ items[index].fr + ' Mhz</td><td class="devs_table_hash"><strong>'+ convertHashrate(items[index].hash) +'</strong></td><td class="devs_table_sh">'+ items[index].sh +'</td><td class="devs_table_ac">'+ items[index].ac +' <small class="text-muted">('+parseFloat(percentageAc).toFixed(2)+'%)</small></td><td class="devs_table_re">'+ items[index].re +' <small class="text-muted">('+parseFloat(percentageRe).toFixed(2)+'%)</small></td><td class="devs_table_hw">'+ items[index].hw +' <small class="text-muted">('+parseFloat(percentageHw).toFixed(2)+'%)</small></td><td class="devs_table_ls">'+ parseInt(last_share_secs) +' secs ago <small class="text-muted">('+share_date.toUTCString()+')</small></td></tr>'
						    
						if (index == "total")
						{
						    $('.devs_table_foot').html(devRow);		
						}
						else
						{
							$('.dev-'+index).remove();
						    $('.devs_table').append(devRow);
						}
						
					}
					
					// Initialize the miner datatable	
					$('#miner-table-details').dataTable();
				    
					// Add pools data
					$('.pool-row').remove();
					
					$.each( data['pools'], function( pkey, pval ) 
					{
						var picon = "download";
						var ptype = "failover";
						var pclass = "bg-light";
						var plabel = "light";
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

						// Main pool
						if (pkey == 0)
						{	
							picon = "upload";
							ptype = "main";
							pclass = "bg-dark";
							plabel = "white";
							purl = '<strong>'+pval.url+'</strong>';
							
							//Add Main pool widget
							$('.widget-main-pool').html(palivelabel);
							$('.widget-main-pool').next('p').html(pval.url);
						}
							
						var poolRow = '<tr class="pool-row '+pclass+'"><td><i class="fa fa-cloud-'+picon+'"></i> '+purl+'</td><td><span class="label label-'+plabel+'">'+ptype+'</span></td><td><span class="label label-'+paliveclass+'">'+palivelabel+'</span></td><td>'+pval.username+'</td><td>'+pval.password+'</td></tr>';
						
						$('.pools_table').append(poolRow);
					});
					
					// Add controller temperature
					if (data['temp'])
					{
						var temp_bar = "bg-blue";
						var temp_text = "It's cool here, wanna join me?"
						var sys_temp = parseFloat(data['temp']);
						
						if (sys_temp > 40 && sys_temp < 60)
						{
							temp_bar = "bg-green";
							temp_text = "I'm warm and fine."
						}
						else if (sys_temp >= 60 && sys_temp < 75)
						{
							temp_bar = "bg-yellow";
							temp_text = "Well, it's going to be hot here..."
						}
						else if (sys_temp > 75)
						{
							temp_bar = "bg-red";
							temp_text = "HEY MAN! I'm burning! Blow blow!"
						}
						
						var sys_temp_box = parseFloat(sys_temp).toFixed(2)+'&deg;c';
						//<div class="progress xs progress-striped active"><div class="progress-bar progress-bar-'+temp_bar+'" role="progressbar" aria-valuenow="'+parseInt(sys_temp)+'" aria-valuemin="0" aria-valuemax="100" style="width: '+parseInt(sys_temp)+'%"></div></div>';
						$('.sys-temp-box').addClass(temp_bar);
						$('.sys-temp-footer').html(temp_text+'<i class="fa fa-arrow-circle-right">');
						$('.widget-sys-temp').html(sys_temp_box);
					}
					else
					{
						$('.widget-sys-temp').html("N.a.");
						$('.sys-temp-footer').html('Temperature not available <i class="fa fa-arrow-circle-right">');
					}
					
					// Add Uptime widget
					var uptime = convertMS(now - data['start_time']*1000);
	
					var human_uptime = "";
					for (var ukey in uptime) {
						human_uptime = human_uptime + "" + uptime[ukey] + ukey + " ";
					}
					
					$(".widget-uptime").html(human_uptime);
					$(".uptime-footer").html("Started on <strong>"+startdate.toUTCString()+"</strong>");
				    
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
						if (lval >= 0 && lval <= 1) { lmax = 1; var lcolor = "rgb(71, 134, 81)"; }
						else if (lval > 1 && lval <= 5) { lmax = 5; lcolor = "rgb(253, 227, 37)"; }
						else if (lval > 5 && lval <= 10) { lmax = 10; lcolor = "rgb(253, 167, 37)"; }
						else { lmax = lval+(lval*10/100); lcolor = "rgb(253, 46, 37)"; }
						
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
			
					$('.overlay').hide();
					$('.loading-img').hide();
					
				}
			    
			});
    	}
		
		function createMon(key, hash, totalhash, maxHashrate, ac, re, hw, sh, freq)
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
				var color = "#e6cc64";
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
			if (hash > 900)
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


    </script>
	<?php endif; ?>
	
</body>
</html>
