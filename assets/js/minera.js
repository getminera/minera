'use strict';

var _baseUrl = window._baseUrl;

function loadScript(url, callback)
{
    var script = document.createElement('script');
    script.type = 'text/javascript';

    if (script.readyState){  //IE
        script.onreadystatechange = function(){
            if (script.readyState === 'loaded' ||
                    script.readyState === 'complete'){
                script.onreadystatechange = null;
                callback();
            }
        };
    } else {  //Others
        script.onload = function(){
            callback();
        };
    }

    script.src = url;
    document.getElementsByTagName('head')[0].appendChild(script);
}

function convertHashrate(hash)
{
	if (!hash) return 0 + 'H/s';
	hash = parseInt(hash);
	if (hash > 900000000000)
		return (hash/1000000000000).toFixed(2) + 'Ph/s';
	if (hash > 900000000)
		return (hash/1000000000).toFixed(2) + 'Th/s';
	else if (hash > 900000)
		return (hash/1000000).toFixed(2) + 'Gh/s';
	else if (hash > 900)
		return (hash/1000).toFixed(2) + 'Mh/s';
	else
		return hash.toFixed(2) + 'Kh/s';
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
}

function getExaColor(color)
{

	if (color === 'green')
		return '#00a65a';
	else if (color === 'yellow')
		return '#f39c12';
	else if (color === 'red')
		return '#f56954';
	else
		return '#999';
}

function callUpdate()
{

	//$('.center').append('<div class="form-box" style="width:90%"><div class="header" id="msglog-box" style="font-size:16px;"></div></div>');
    $.ajax({
	    url: _baseUrl + '/app/api?command=update_minera',
        dataType: 'json',
        success: function (data) {
        	if (data)
        	{
        		console.log(data);
        		//$('#msglog-box').html('<p>'+data+'</p>');
    		}
        }
    });
}

function timer(counter)
{

	var count = (counter) ? counter-1 : $('body').data('count')-1;

	if (count < 0)
	{
		clearInterval(counter);
		//counter ended, do something here
		return;
	}
	
	if (count === 0)
	{
		$('.lockscreen-name').hide();
		$('#time').html($('body').data('count-message'));
		$('body').data('count', 0);
		return true;
	}
	else
		$('#time').html(count);
	
	setTimeout(function() {
		timer(count);
	}, 1000);
}

function saveSettings(hide, saveonly)
{

    if (saveonly === false)
    {
    	$('#modal-saving-label').html('Saving data, please wait...');
    	$('#modal-saving').modal('show');		        
    }
	
	var saveUrl = _baseUrl+'/app/save_settings';
	var formData = $('#minersettings').serialize();

	$.ajax({
		type: 'POST',
		url: saveUrl,
		data: formData,
		cache: false,
		success:  function(resp){
			if (hide && !saveonly) {
				$('#modal-saving').modal('hide');
				window.location.reload();
			}
		}
	});
}

function changeDonationWorth(profitability, value) 
{

	var amount = (profitability / 24 / 60 * value), string = (value > 0) ? 'about' : 'exactly', h = 0, new_value = 0, period = 0;

	if (value >= 60)
	{
		h = Math.floor(value / 60);
		new_value = value % 60;
    	period = h + ' hour(s) ' + ((new_value > 0) ? ' and ' + new_value + ' minute(s)' : '');
	}
	else
	{
    	period = value + ' minutes';
	}
	
	$('.donation-worth').html('<small>Mining for ' + period + ' in a day your donation per MH/s worths ' + string + ':</small> <span class="label label-success"><i class="fa fa-btc"></i>&nbsp;' + amount.toFixed(8) + '</span>');
	
	if (value > 0 && value < 90)
	{
		$('.donation-mood').html('<i class="fa fa-smile-o"></i> Your support is much appreciate. Thank you!').removeClass().addClass('donation-mood badge bg-blue');
	}
	else if (value >= 90 && value < 180)
	{
		$('.donation-mood').html('<i class="fa fa-sun-o"></i> WOW that\'s really cool! Thank you!').removeClass().addClass('donation-mood badge bg-green');
	}
	else if (value >= 180 && value < 270)
	{
		$('.donation-mood').html('<i class="fa fa-star"></i> That\'s amazing! You are a star! Thank you!').removeClass().addClass('donation-mood badge bg-yellow');
	}
	else if (value >= 270 && value <= 360 )
	{
		$('.donation-mood').html('<i class="fa fa-heart"></i> You are my hero! You really rock! Thank you so much!').removeClass().addClass('donation-mood badge bg-red');
	}
	else
	{
		$('.donation-mood').html('<i class="fa fa-frown-o"></i> Time donation is disabled').removeClass().addClass('donation-mood badge');
	}
}

// Show or Hide the options related to the selected miner software
function showHideMinerOptions(change)
{	

    var sel = $('#minerd-software option:selected').text().match(/\[Custom Miner\]/);
    
    if ($('#minerd-software').val() !== 'cpuminer' && sel === null)
    {
	    $('.options-selection').show();
    	$('.legend-option-autodetect').html('(--scan=all)');
    	$('.legend-option-log').html('(--log-file)');
    	$('#minerd-autotune').hide();
    	$('input[name="minerd_autotune"]').prop('disabled', true);
	    $('#minerd-startfreq').hide();
    	$('input[name="minerd_startfreq"]').prop('disabled', true);
	    $('#minerd-scrypt').show();
	    $('#minerd-api-allow').show();
    	$('input[name="minerd_scrypt"]').prop('disabled', false);
    }
    else if (sel !== null)
    {
	    $('.options-selection').hide();
	    $('.guided-options').fadeOut();
		$('.manual-options').fadeIn();
		$('.btn-manual-options').addClass('disabled');
		$('.btn-guided-options').removeClass('disabled');
		$('#manual_options').val(1);
		$('#guided_options').val(0);
    }
    else
    {
	    $('.options-selection').show();
    	$('.legend-option-autodetect').html('(--gc3355-detect)');
    	$('.legend-option-log').html('(--log)');
		$('#minerd-log').show();
	    $('input[name="minerd_log"]').prop('disabled', false);
    	$('#minerd-autotune').show();
    	$('input[name="minerd_autotune"]').prop('disabled', false);
	    $('#minerd-startfreq').show();
    	$('input[name="minerd_startfreq"]').prop('disabled', false);
	    $('#minerd-scrypt').hide();
	    $('#minerd-api-allow').hide();
    	$('input[name="minerd_scrypt"]').prop('disabled', true);
    }

    $('.detail-minerdsoftware').remove();
    $('.note-minerdsoftware').remove();
    
    if (change)
    {
    	if ($('#minerd-software').val() === 'cpuminer')
		{
			$('.group-minerdsoftware').append('<h6 class="detail-minerdsoftware"><a href="https://github.com/siklon/cpuminer-gc3355" target="_blank"><small class="badge bg-red">CPUminer-GC3355</small></a> is a fork of Cpuminer and is the best software for gridseed devices like Minis and Blades. It is fully optimised and supports autotune, autodetection, frequency and it\'s really stable. <a href="https://github.com/siklon/cpuminer-gc3355" target="_blank">More info</a>.</h6>');
		}
		else if ($('#minerd-software').val() === 'bfgminer')
		{
			$('.group-minerdsoftware').append('<h6 class="detail-minerdsoftware"><a href="https://github.com/luke-jr/bfgminer" target="_blank"><small class="badge bg-red">BFGminer</small></a> has a really large amount of devices supported, it has also a lot of features you can use to get the best from your devices. It\'s a stable software. <a href="https://github.com/luke-jr/bfgminer" target="_blank">More info</a>.</h6>');
		}
		else if ($('#minerd-software').val() === 'cgminer')
		{
			$('.group-minerdsoftware').append('<h6 class="detail-minerdsoftware"><a href="https://github.com/ckolivas/cgminer" target="_blank"><small class="badge bg-red">CGminer</small></a> is similar to bfgminer, supports a large amount of devices but probably is less updated than bfg. It\'s a stable software. <a href="https://github.com/ckolivas/cgminer" target="_blank">More info</a>.</h6>');
		}
		else if ($('#minerd-software').val() === 'cgdmaxlzeus')
		{
			$('.group-minerdsoftware').append('<h6 class="detail-minerdsoftware"><a href="https://github.com/dmaxl/cgminer/" target="_blank"><small class="badge bg-red">CGminer Dmaxl Zeus</small></a> is a Cgminer 4.3.5 fork with GridSeed and Zeus scrypt ASIC support, it has some issues with Minera. Stability is unknown. <a href="https://github.com/dmaxl/cgminer/" target="_blank">More info</a>.</h6>');
		}
		else
		{
			$('.group-minerdsoftware').append('<h6 class="detail-minerdsoftware"><small class="badge bg-red">Custom Miner</small> is a miner you uploaded, it\'s up to you, but it\'s recommended to use "manual" options below, cause Minera can\'t know the "guided" options for your custom miner.</h6>');
		}

		$('.group-minerdsoftware').append('<h5 class="note-minerdsoftware"><strong>NOTE:</strong> <i>remember to review your settings below if you change the miner software because they haven\'t the same config options and the miner process could not start.</i></5>');
	}
}

function createChart(period, text_period)
{

	
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
		
	/* Morris.js Charts */
	// get Json data from stored_stats url (redis) and create the graphs
	$.getJSON( _baseUrl+'/app/api?command=history_stats&type='+period, function( data ) 
	{
	    var refresh = false, 
	    	areaHash = {}, 
	    	areaRej = {}, 
	    	dataChart = Object.keys(data).map(function(key) { 
					data[key].timestamp = data[key].timestamp*1000; 
					data[key].hashrate = (data[key].hashrate/1000/1000).toFixed(2);
					data[key].pool_hashrate = (data[key].pool_hashrate/1000/1000).toFixed(2);									
					return data[key];
			});
	
		if (dataChart.length > 0)
		{
			
			if (refresh === false)
			{
				// Hashrate history graph
				areaHash = new Morris.Area({
					element: 'hashrate-chart-'+period,
					resize: true,
					data: dataChart,
					xkey: 'timestamp',
					ykeys: ['hashrate', 'pool_hashrate'],
					ymax: 'auto',
					postUnits: 'Mh/s',
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
					data: dataChart,
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
				updateGraphs(dataChart);
			}
			
			$(window).resize(function() {
				redrawGraphs();
			});
			
			$('.sidebar-toggle').click(function() { redrawGraphs(); });
		}
		else
		{
			$('#hashrate-chart-'+period).css({'height': '100%', 'overflow': 'visible', 'margin-top': '20px'}).html('<div class="alert alert-warning"><i class="fa fa-warning"></i><b>Oops!</b> <small>No data collected, wait at least '+text_period+' to see the chart.</small></div>');	
			$('#rehw-chart-'+period).css({'height': '100%', 'overflow': 'visible', 'margin-top': '20px'}).html('<div class="alert alert-warning"><i class="fa fa-warning"></i><b>Oops!</b> <small>No data collected, wait at least '+text_period+' to see the chart.</small></div>');	

		}
		
		$('.overlay').hide();
		$('.loading-img').hide();
	});
	
} //End get stored stats

function createMon(key, hash, totalhash, maxHashrate, ac, re, hw, sh, freq, color)
{

	var col, toAppend, size, skin, thickness, fontsize, name, max;
		
	if (key === 'total')
	{
		col = 12;
		toAppend = '#devs-total';
		color = '#f56954';
		size = 140;
		skin = 'tron';
		thickness = '.2';
		fontsize = '10pt';
	}
	else
	{
		col = 4;
		toAppend = '#devs';
		color = getExaColor(color);
		size = 80;
		skin = 'basic';
		thickness = '.2';
		fontsize = '8pt';
	}
	
	if (freq)
		name = key + ' @ ' + freq + 'Mhz';
	else
		name = key;			

	// Add per device knob graph
	var devBox = '<div class="col-xs-'+col+' text-center" id="master-'+ key +'"><input type="text" class="'+ key +'" /><div class="knob-label"><p><strong>'+name+'</strong></p><p>A: '+ac+' - R: '+re+' - H: '+hw+'</p></div></div>';
	
	$('#master-'+key).remove();
	
	$(toAppend).append(devBox);
	
	$('.'+key).data('hashrate', hash);

	$('.'+key).knob({
        'readOnly': true,
        'fgColor':color,
        'inputColor': '#434343',
        'thickness': thickness,
        'skin': skin,
        'displayPrevious': true,
        'ticks': 16,
        'width':size,
        'height':size,
		'draw' : function () { 
			// 'tron' case
            if (this.o.skin === 'tron') {
				
                var a = this.angle(this.cv),  // Angle
                	sa = this.startAngle,          // Previous start angle
                    sat = this.startAngle,         // Start angle
                    ea,                            // Previous end angle
                    eat = sat + a,                 // End angle
                    r = true;

                this.g.lineWidth = this.lineWidth;

                this.o.cursor && (sat = eat - 0.3) && (eat = eat + 0.3);

                if (this.o.displayPrevious) {
                    ea = this.startAngle + this.angle(this.value);
                    this.o.cursor && (sa = ea - 0.3) && (ea = ea + 0.3);
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
	
	if (key === 'total')
		max = totalhash;
	else
		max = maxHashrate;
	
	$('.'+key)
	    .trigger(
	        'configure',
	        {
	        'min':0,
	        'max':max,
	        'step':1,
	        }
	    );
	    
	$({value: 0}).animate({value: hash}, {
	    duration: 1000,
	    easing:'swing',
	    step: function() 
	    {
		        $('.'+key).val(Math.ceil(this.value)).trigger('change');
	    }
	});
	    
	$('.'+key).css('font-size', fontsize);
}

function changeEarnings(value) 
{

	var hashrate = $('.widget-total-hashrate').data('pool-hashrate');

	var amount = (value * hashrate / 1000);

	$('.profitability-results').html('<span class="label bg-blue">' + convertHashrate(hashrate) + '</span>&nbsp;x&nbsp;<span class="label bg-green">' + value.toFixed(5) + '</span> = <small>Day: </small><span class="badge bg-red">' + amount.toFixed(8) + '</span> <small>Week: </small><span class="badge bg-light">' + (amount * 7).toFixed(8) + '</span> <small>Month: </small><span class="badge bg-light">' + (amount * 30).toFixed(8) + '</span>');
	
}

// Errors
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

Number.prototype.noExponents= function()
{

    var data= String(this).split(/[eE]/);
    if(data.length === 1) return data[0]; 

    var  z= '', sign= this<0? '-':'',
    str= data[0].replace('.', ''),
    mag= Number(data[1])+ 1;

    if(mag<0){
        z= sign + '0.';
        while(mag++) z += '0';
        return z + str.replace(/^\-/,'');
    }
    mag -= str.length;  
    while(mag--) z += '0';
    return str + z;
};

/*
// Main startup function //
*/
$(function() {

	
	var thisSection = $('.header').data('this-section');
	
	/*
	if (thisSection === 'settings') {
		jQuery.ajax({
			url: 'https://www.coinbase.com/assets/button.js',
			dataType: 'script',
			cache: true
		}).done(function() {
			console.log('Coinbase loaded');
		});
	}
	*/
	
	$('body').tooltip({ selector: '[data-toggle="tooltip"]', trigger: 'hover' });
	$('body').popover({ selector: '[data-toggle="popover"]', trigger: 'hover' });
	
	var timeNow = new Date().getTime(), promoInterval = 3600000, theInterval = null, adsFree = $('.app_data').data('ads-free');
	
	if (!adsFree) {			
		var setPromoInterval = function () {
			if (theInterval) {
				//console.log('Clear');
				clearInterval(theInterval);
			}
			Cookies.remove('promoClicked');
			if (!Cookies.get('timestamp') || new Date().getTime() >= parseInt(Cookies.get('timestamp')) && !Cookies.get('promoModal')) {
				//console.log('Set');
				Cookies.set('timestamp', new Date().getTime()+promoInterval);
			}
			
			if (!Cookies.get('promoModal')) {
				theInterval = setInterval(function () {
					//console.log('Start');
					$('#modal-promo').modal('show');
					Cookies.set('promoModal', true);
					setPromoInterval();
				}, Cookies.get('timestamp')-new Date().getTime());
			}
		};

		// Promo ads
		if (new Date().getTime() >= parseInt(Cookies.get('timestamp')) && !Cookies.get('promoClicked')) {
			//console.log('Go');
			$('#modal-promo').modal('show');
		} else {
			setPromoInterval();
		}
		
		$('.modal-promo-hide').click(function(e){
	       	e.preventDefault();
			$('#modal-promo').modal('hide');
			Cookies.set('promoClicked', true);
			Cookies.remove('promoModal');
	  		setPromoInterval();
		});
		
		/*
		var overiFrame = -1;
	    $('.promo-iframe').hover( function() {
	        overiFrame = $(this).closest('.banner').attr('bannerid');
	    }, function() {
	        overiFrame = -1;
	    });

	    $(window).blur( function() {
	        if( overiFrame !== -1 ) {
				$('#modal-promo').modal('hide');
				Cookies.set('promoClicked', true);
				Cookies.remove('promoModal');
		  		setPromoInterval();
			}
	    });
	    */
	} else {
		Cookies.remove('promoModal');
		Cookies.remove('promoClicked');
		Cookies.remove('timestamp');
	}

	// Show/hide coinhive info
	$('.open-browser-mining-info').on('click', function () {
		$('.browser-mining-info-box').toggle();
	});
	
	// Scroll ad
	if (thisSection !== 'lockscreen') {
	    if ($(document).scrollTop() > 64) {
			$('.scroll-ad').fadeIn();
		}
		$(document).scroll(function() {
			var y = $(this).scrollTop();
			if (y > 64) {
				$('.scroll-ad').fadeIn();
			} else {
				$('.scroll-ad').fadeOut();
			}
		});
	}
	
	// Add responsive class to datatables
	$('.responsive-datatable-minera').on('DOMChanged', function () {
		$(this).parent().addClass('table-responsive');
	});
	
	// Smmoth scroll
	$('a[href*=#]:not([href=#])').click(function() {
	    if (location.pathname.replace(/^\//,'') === this.pathname.replace(/^\//,'') && location.hostname === this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top - 60
				}, 1000);
				return false;
			}
		}
	});
	
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
        var dayOrNight = (h > 11) ? 'PM' : 'AM';

        //Convert to 12 hours system
        if (h > 12) { h -= 12; }

        //Add time to the headline and update every 500 milliseconds
        $('.toptime').html(h + ':' + m + ':' + s + ' ' + dayOrNight);
        setTimeout(function() {
            startTime();
        }, 500);
    }

    function checkTime(i)
    {
        if (i < 10)
        {
            i = '0' + i;
        }
        return i;
    }
    
	startTime();

	var mineraId = $('.app_data').data('minera-id');

	// Start/Stop browser mining
	var browserMining = $('.app_data').data('browser-mining'),
		currentPage = $('.app_data').data('page'),
		bmThreads = parseInt($('.app_data').data('browser-mining-threads')) || 2;

	if (window.CoinHive) {
		var miner = new window.CoinHive.User('mT0rsHpa6hBvLP6UT5sXJuxZCQ25PPR2', mineraId);

	 	$(document).on('click', '.dropdown-menu-mining', function (e) {
	 		var clicked = e.target.className,
	 			apiUrl = _baseUrl + '/app/manage_browser_mining';

	 		if (clicked === 'fa fa-plus' && bmThreads < 32) {
	 			bmThreads += 1;

				$.ajax({
					type: 'POST',
					data: {
						action: 'enable',
						threads: bmThreads
					},
					url: apiUrl,
					cache: false,
					success:  function(resp){
						console.log(resp);
					}
				});
	 		}
	 		if (clicked === 'fa fa-minus' && bmThreads > 2) {
	 			bmThreads -= 1;
	 			$.ajax({
					type: 'POST',
					data: {
						action: 'enable',
						threads: bmThreads
					},
					url: apiUrl,
					cache: false,
					success:  function(resp){
						console.log(resp);
					}
				});
			}
	 		if (miner) miner.setNumThreads(bmThreads);
	 		$('.bm-threads').text(bmThreads);
			e.stopPropagation();
		});

		if (browserMining) {
			miner.start();
			// Listen on events
			miner.on('found', function() {
				// console.log('BM Found new hash');
			});
			miner.on('bm accepted', function() {
				// console.log('BM Accepted new hash');
			});

			// Update stats once per second
			setInterval(function() {
				$('.bmWarm').hide();
				var bmHashrate = miner.getHashesPerSecond(),
					bmData = {
						hashrate: bmHashrate.toFixed(2) + 'H/s',
						totalHashes: miner.getTotalHashes(),
						acceptedHashes: miner.getAcceptedHashes(),
						threads: bmThreads
					};
				$('.bmHash').show();
				$('.bmHashText').text(bmData.hashrate);
				$('.bmAccepted').text(bmData.acceptedHashes);
				$('.bm-threads').text(bmThreads);
				// console.log('BM Info', bmData);
			}, 5000);
		} else {
			miner.stop();
		}
	}
    
    $('.miner-action').click(function(e) {
       	e.preventDefault();
       	var action =  $(this).data('miner-action');
    	
	   	$('#modal-saving-label').html('Sending action: '+action+' , please wait...');
    	$('#modal-saving').modal('show');
    	
    	saveSettings(false, false);
    	
    	var apiUrl = _baseUrl+'/app/api?command=miner_action&action='+action;

		$.ajax({
			type: 'GET',
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
    
    $('.reset-action').click(function(e) {
       	e.preventDefault();
       	var action =  $(this).data('reset-action');
    	
	   	$('#modal-saving-label').html('Resetting: '+action+' , please wait...');
    	$('#modal-saving').modal('show');
    	
    	var apiUrl = _baseUrl+'/app/api?command=reset_action&action='+action;

		$.ajax({
			type: 'GET',
			url: apiUrl,
			cache: false,
			success:  function(resp){
				$('#modal-saving').modal('hide');
				window.location.reload();
			}
		});
    });
    
    $('.reset-factory-action').click(function(e) {
       	e.preventDefault();
    	
	   	$('#modal-saving-label').html('Resetting Minera please wait...');
    	$('#modal-saving').modal('show');
    	
    	var apiUrl = _baseUrl+'/app/api?command=factory_reset_action';

		$.ajax({
			type: 'GET',
			url: apiUrl,
			cache: false,
			success:  function(resp){
				setTimeout(function () { 
					$('#modal-saving').modal('hide');
					window.location.reload();
				}, 1500);
			}
		});
    });
    
	$('.system-open-terminal').click(function(e){
       	e.preventDefault();
       	
       	var a = document.createElement('a');
       	a.href = _baseUrl;

       	if (!$('#terminal-iframe').attr('src')) {
			$('#terminal-iframe').attr('src', 'http://'+a.host+':4200/');
		}

		$('#modal-terminal').modal('show');
	});

	$('.manage-browser-mining').click(function(e) {
       	e.preventDefault();
       	var action =  $(this).data('action');

	   	$('#modal-saving-label').html(action + ' browser mining, please wait...');
    	$('#modal-saving').modal('show');

    	var apiUrl = _baseUrl + '/app/manage_browser_mining';

		$.ajax({
			type: 'POST',
			data: {
				action: action
			},
			url: apiUrl,
			cache: false,
			success:  function(resp){
				$('#modal-saving').modal('hide');
				window.location.reload();
			}
		});
    });
	
	$('.modal-hide').click(function(e){
       	e.preventDefault();
		$('#modal-terminal').modal('hide');
	});
	
	if (thisSection === 'charts') {
	    
		// Chart Scripts
		createChart('hourly', '5 minutes');		    
		createChart('daily', '15 minutes');
		createChart('monthly', '1 hour');
		createChart('yearly', '1 day');

	} else if (thisSection === 'lockscreen') {
		
    	$('.pass-form').trigger('focus');
        startTime();
        $('.copyright').addClass('copyright-lockscreen');
        
        if ($('body').data('timer')) { timer(); }

        /* CENTER ELEMENTS IN THE SCREEN */
        /*
		$('.center').center();
        $(window).resize(function() {
            $('.center').center();
        });
		jQuery.fn.center = function() {
            this.css('position', 'absolute');
            this.css('top', Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                    $(window).scrollTop()) - 30 + 'px');
            this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                    $(window).scrollLeft()) + 'px');
            return this;
        }*/
		
	} else if (thisSection === 'settings') {

		// Settings Scripts
		$('.box-tools').click( function(e) { e.preventDefault(); });
		
		if ($(document).scrollTop() > 64) {
			$('.save-toolbox').fadeIn();
		}
		$(document).scroll(function() {
			var y = $(this).scrollTop();
			if (y > 64) {
				$('.save-toolbox').fadeIn();
			} else {
				$('.save-toolbox').fadeOut();
			}
		});
		
		$('.toggle-save-toolbox').click( function(e) { 
			e.preventDefault(); 
			var nextIcon = $(this).find('.fa');
			if (nextIcon.hasClass('fa-close')) {
				nextIcon.addClass('fa-arrow-left');
				nextIcon.removeClass('fa-close');
				$('.save-toolbox').css('right', '-324px');
			} else {
				nextIcon.addClass('fa-close');
				nextIcon.removeClass('fa-arrow-left');
				$('.save-toolbox').css('right', '0');
			}
		});
    
	    if (window.location.href.match(/settings/g))
	    {
		    $('.treeview-menu-settings-icon').removeClass('fa-angle-left').addClass('fa-angle-down');
		    $('.treeview-menu-settings-icon').parent('a').first().addClass('activeTree');
			$('.treeview-menu-settings').fadeIn();
	    }
	    
	    $('#progress').hide();
	    
	    $('.save-minera-settings').click(function(e) {
	       	e.preventDefault();
	       	saveSettings(true, false);
	    });
	    $('.save-minera-settings-restart').click(function(e) {
	       	e.preventDefault();
		   	var input = $('<input>')
               .attr('type', 'hidden')
               .attr('name', 'save_restart').val(true);
			$('#minersettings').append($(input));
	       	saveSettings(true, false);
	    });
		
		$('.import-file').fileupload({
			url: _baseUrl+'/app/api?command=import_file',
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
	       	
	       	$('#modal-saving-label').html('Cloning the system, please wait...');
	    	$('#modal-saving').modal('show');
	    	
	    	var saveUrl = _baseUrl+'/app/api?command=clone_system';
	
			$.ajax({
				type: 'GET',
				url: saveUrl,
				cache: false,
				success:  function(resp){
					$('#modal-saving').modal('hide');
					window.location.reload();
				}
			});
		});
		
		$('.export-action').click(function(e) {
	       	e.preventDefault();
	    	
		   	$('#modal-saving-label').html('Generating export file, please wait...');
	    	$('#modal-saving').modal('show');
	    	
	    	var saveUrl = _baseUrl+'/app/save_settings';
	    	var formData = $('#minersettings').serialize();
	
			$.ajax({
				type: 'POST',
				url: saveUrl,
				data: formData,
				cache: false,
				success:  function(resp){
					$('#modal-saving').modal('hide');
					window.location = _baseUrl+'/app/export';
				}
			});      	
	    });
	    
	    $('.save-config-action').click(function(e) {
	       	e.preventDefault();
	    	
		   	$('#modal-saving-label').html('Saving current config, please wait...');
	    	$('#modal-saving').modal('show');
	    	
	    	var saveUrl = _baseUrl+'/app/save_settings?save_config=1';
	    	var formData = $('#minersettings').serialize();
	
			$.ajax({
				type: 'POST',
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
						'<tr class="config-'+resp.timestamp+'">' +
							'<td><small class="label label-info">'+date.format('MM/DD/YY h:mm a')+'</small></td>' +
							'<td><small class="label bg-blue">'+resp.software+'</small></td>' +
							'<td><small class="font-bold">'+resp.settings+'</small></td>' +
							'<td><small>' + pools + '</small></td>' +
							'<td class="text-center">' +
								'<a href="#" class="share-config-open" data-config-id="'+resp.timestamp+'" data-toggle="tooltip" data-title="Share saved config"><i class="fa fa-share-square-o"></i></a>' +
								'<a href="#" class="load-config-action" style="margin-left:10px;" data-config-id="'+resp.timestamp+'" data-toggle="tooltip" data-title="Load saved config"><i class="fa fa-upload"></i></a>' +
								'<a href="#" class="delete-config-action" style="margin-left:10px;" data-config-id="'+resp.timestamp+'" data-toggle="tooltip" data-title="Delete saved config"><i class="fa fa-times"></i></a>' +
							'</td>' +
						'</tr>'; 
							
					$('#saved-configs-table tbody').append(htmlRow);
					
					$('.saved-configs').show();
					$('#modal-saving').modal('hide');
				}
			});      	
	    });
	    
	    $(document).on('click', '.share-config-open', function(e) {
	       	e.preventDefault();
	    	$('input[name="config_id"]').val($(this).data('config-id'));
	    	$('#modal-sharing').modal('show');
	    });
	    
	    $(document).on('click', '.share-config-action', function(e) {
	       	e.preventDefault();
	    	
	    	$('.share-error').fadeOut();
	    	
	    	var descr = $('textarea[name="config_description"]').val();
	
	    	if (!descr || 0 === descr.length)
	    	{
	        	$('#formsharingconfig').append('<h6 class="callout bg-red share-error">Description can\'t be empty');
	        	return;
	    	}
	    	
	    	var saveUrl = _baseUrl+'/app/api/?command=share_config';
	    	var formData = $('#formsharingconfig').serialize();
	    	
			$.ajax({
				type: 'POST',
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
	    	var saveUrl = _baseUrl+'/app/api/?command=delete_config&id='+id;
	
			$('.config-'+id).fadeOut();
	
			$.ajax({
				type: 'GET',
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
	    	
	    	$('#modal-saving-label').html('Loading config, please wait...');
			$('#modal-saving').modal('show');
		
	    	var id = $(this).data('config-id');
	    	var saveUrl = _baseUrl+'/app/api/?command=load_config&id='+id;
	
			$('.config-'+id).fadeOut('fast').fadeIn('slow');
	
			$.ajax({
				type: 'GET',
				url: saveUrl,
				cache: false,
				success:  function(resp){
					$('#modal-saving').modal('hide');
					window.location.reload();
				}
			});      	
	    });
	    
	    // Network miners
	    $(document).on('click', '.scan-network', function(e) {
	       	e.preventDefault();
	       	
	       	$('#modal-saving-label').html('Scanning the network, please wait...');
	    	$('#modal-saving').modal('show');
	    	
	    	var scanUrl = _baseUrl+'/app/api?command=scan_network';
	
			$.ajax({
				type: 'GET',
				url: scanUrl,
				cache: false,
				success:  function(resp){
					$('#modal-saving').modal('hide');

					if (resp.length > 0)
					{
						$('.net-group-master').first().clone().prependTo('.netSortable');
						$('.net-group-master').first().css('display', 'block').removeClass('net-group-master');
	
						$.each(resp, function (index, value)
						{
							$('.net-group:first .net-row .net_miner_status').html('<i class="fa fa-circle text-success"></i> Online');
							$('.net-group:first .net-row .net_miner_name').val(value.name);
							$('.net-group:first .net-row .net_miner_ip').val(value.ip);
							$('.net-group:first .net-row .net_miner_port').val('4028');
							$('.net-group:first .net-row .net_miner_status').removeClass('label-primary').addClass('label-success');
						});
						
						setTimeout(function () { saveSettings(true, true); }, 2000);
					} else {
						$('.alert-no-net-devices').fadeIn();
						setTimeout(function () { $('.alert-no-net-devices').fadeOut(); }, 5000);
					}
				}
			});
		});
	
		$(document).on('click', '.net_miner_status', function(e) {
			e.preventDefault();
	    });
		
		$(document).on('click', '.del-net-row', function(e) {
			e.preventDefault();
			$(this).closest('.form-group').remove();
			saveSettings(false, true);
	    });
	    		    
	    $(document).on('click', '.add-net-row', function(e) {
			e.preventDefault();
			$('.net-group-master').first().clone().appendTo('.netSortable');
			$('.net-group-master').last().css('display', 'block').removeClass('net-group-master');
	    });
		
		$('.netSortable').sortable({
	        placeholder: 'sort-highlight',
	        connectWith: '.sort-attach',
	        handle: '.sort-attach',
	        forcePlaceholderSize: true,
	        zIndex: 999999
	    });
	    $('.sort-attach').css('cursor','move');
	    
	    //Make the dashboard widgets sortable Using jquery UI
	    $('.poolSortable').sortable({
	        placeholder: 'sort-highlight',
	        connectWith: '.sort-attach',
	        handle: '.sort-attach',
	        forcePlaceholderSize: true,
	        zIndex: 999999
	    });
	    $('.sort-attach').css('cursor','move');
	    
	    // Initialize options sliders
	    $('.open-readme-donation').click(function(e) {
			e.preventDefault();
			$('.readme-donation').fadeToggle();
	    });
	    
	    $('.view-stored-donations').click(function(e) {
			e.preventDefault();
			$('#stored-donation-table_wrapper').toggle();
			$('#stored-donation-table').fadeToggle();
	    });
	    
		$('#stored-donation-table').dataTable({bFilter: false, bInfo: false, bPaginate: true, 'dom': '<"top"i>rt<"bottom"flp><"clear">'});
		$('#stored-donation-table_wrapper').hide();
	    	    
	    var donationProfitability = $('#option-minera-donation-time').data('donation-profitability'),
	    	savedDonationTime = $('#option-minera-donation-time').data('saved-donation-time');
	    
	    $('#option-minera-donation-time').ionRangeSlider({
			min: 0,
			max: 360,
			from: (savedDonationTime) ? savedDonationTime : 0,
			type: 'single',
			step: 10,
			postfix: ' Mins',
			grid: true,
			onStart: function (obj) {
				changeDonationWorth(donationProfitability, obj.from);
			},
			onChange: function (obj) {
				if (obj.from > 0)
				{
					$('#option-minera-donation-time').ionRangeSlider('update', { to: 0 });
				}
				changeDonationWorth(donationProfitability, obj.from);
			}
		});
		
	    $('#ion-startfreq').ionRangeSlider({
			min: 600,
			max: 1400,
			from: $('#ion-startfreq').data('saved-startfreq'),
			type: 'single',
			step: 1,
			postfix: ' Mhz',
			grid: true,
		});
	
	    $('#option-dashboard-refresh-time').ionRangeSlider({
			min: 0,
			max: 600,
			from: $('#option-dashboard-refresh-time').data('saved-refresh-time'),
			type: 'single',
			step: 5,
			postfix: ' Secs',
			grid: true,
			onChange: function (obj) {
				if (obj.from > 0)
				{
					$('#option-dashboard-refresh-time').ionRangeSlider('update', { from: 0 });
				}
				if (obj.to < 5)
				{
					$('#option-dashboard-refresh-time').ionRangeSlider('update', { to: 5 });
				}
			}
		});
		
	    $(document).on('click', '.help-pool-row', function(e) {
			e.preventDefault();
			$('.minera-pool-help').fadeToggle();
	    });
	
	    $(document).on('click', '.del-pool-row', function(e) {
			e.preventDefault();
			$(this).closest('.form-group').remove();
	    });
	    		    
	    $(document).on('click', '.add-pool-row', function(e) {
			e.preventDefault();
			if ($(this).data('network')) {
				$('.pool-'+$(this).data('networkminer')).first().clone().appendTo('.net-'+$(this).data('networkminer'));
				$('.pool-'+$(this).data('networkminer')).last().css('display', 'block').removeClass('.pool-'+$(this).data('networkminer'));
			} else {
				$('.pool-group-master').first().clone().appendTo('.poolSortable');
				$('.pool-group-master').last().css('display', 'block').removeClass('pool-group-master');
			}
	    });
	
		$('.form-donation').prop('disabled', true);
		
	    $(document).on('click', '.add-donation-pool-row', function(e) {
			e.preventDefault();
			if ($(this).data('network')) {
				$('.form-donation').prop('readonly', true).prop('disabled', false);
				$('.pool-net-donation-'+$(this).data('networkminer')).css('display', 'block').removeClass('.pool-net-donation-'+$(this).data('networkminer'));
			} else {
				$('.form-donation').prop('readonly', true).prop('disabled', false);
				$('.pool-donation-group').css('display', 'block').removeClass('pool-donation-group');
			}
			
			$(this).fadeOut();
	    });
	    
	    // Custom miners
	     // Initialize options sliders
	    $('.open-readme-custom-miners').click(function(e) {
			e.preventDefault();
			$('.readme-custom-miners').fadeToggle();
	    });
	    
	    $('#minerd-software').on('change', function() {
			showHideMinerOptions(true);
			$('#miners-conf').val('');
			$('.miners-conf-box').fadeOut();
	    });
	    
	    $('#miners-conf').on('change', function() {
		    $('#minerd-software').val($(this).find(':selected').data('miner-id'));
			$('.miners-conf-box').html('<code>'+$(this).val()+'</code>').fadeIn();
			showHideMinerOptions(true);
			$('.guided-options').fadeOut();
			$('.manual-options').fadeIn();
			$('.btn-manual-options').addClass('disabled');
			$('.btn-guided-options').removeClass('disabled');
			$('#manual_options').val(1);
			$('#guided_options').val(0);
			$('.manual-settings').val($(this).val());
	    });
	    
	    $(document).on('click', '.del-custom-miner', function(e) {
			e.preventDefault();
			var d = $(this).closest('.input-group');
			
			$.ajax(_baseUrl+'/app/api?command=delete_custom_miner&custom='+$(this).data('custom-miner'), {
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
	    	    
	    if ($('#manual_options').val() === '1') { $('.guided-options').hide(); }
	    if ($('#guided_options').val() === '1') { $('.manual-options').hide(); }
	    $('.btn-manual-options').click(function() {
	    	$('.guided-options').fadeOut();
			$('.manual-options').fadeIn();
			$('.btn-manual-options').addClass('disabled');
			$('.btn-guided-options').removeClass('disabled');
			$('#manual_options').val(1);
			$('#guided_options').val(0);
			return false;
	    });
	    $('.btn-guided-options').click(function() {
	    	$('.manual-options').fadeOut();
			$('.guided-options').fadeIn();
			$('.btn-guided-options').addClass('disabled');
			$('.btn-manual-options').removeClass('disabled');
			$('#manual_options').val(0);
			$('#guided_options').val(1);
			return false;
	    });
		
	    // validate signup form on keyup and submit
	    $.validator.addMethod('check_multiple_select', function(value, element) {
			if (value && value.length > 0 && value.length <= 5 ) {
				return true;
			}
				
			return false;
			
		}, 'Select at least 1 rate (max 5)');
		
	    $.validator.addMethod('no_quote', function(value, element) {
			if (!value.match(/\'/) ) {
				return true;
			}
				
			return false;
			
		}, 'You can use any symbol but single quote');
		
		jQuery.validator.addMethod('validIP', function(value) {
		    var split = value.split('.');
		    if (split.length !== 4) {
		        return false;
		    }
		            
		    for (var i=0; i<split.length; i++) {
		        var s = split[i];
		        if (s.length === 0 || isNaN(s) || s<0 || s>255) {
		            return false;
		        }
		    }
		    return true;
		}, ' Invalid IP Address');
	
		var validator = $('#minersettings').validate({
			rules: {
				minerd_manual_settings: 'required',
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
				},
				system_password: {
					minlength: 6,
					no_quote: true
				},
			    system_password2: {
			      equalTo: '#system_password'
			    }
			},
		    errorPlacement: function(error, element) {
				error.appendTo( $(element).closest('.input-group').parent().after() );
		    },
		    // specifying a submitHandler prevents the default submit, good for the demo
		    submitHandler: function() {
		    	saveSettings(true, false);
		    },
		    unhighlight: function(element) {
				$(element).closest('.input-group').removeClass('has-error').addClass('has-success');
		    },
		    highlight: function(element, errorClass) {
		    	$(element).closest('.input-group').removeClass('has-success').addClass('has-error');
		    }
		});
		
		if ($('.dashboard-coin-rates').length) {
			$('.dashboard-coin-rates').rules('add', {
				check_multiple_select: true
			});
		}
		
		$('.pool_url').each(function () {
			if ($(this).data('ismain'))
			{
				$(this).rules('add', 'required');
			}
			else
			{
				$(this).rules('add', {
					required: {
						depends: function(element) {
							return ($(element).parent().parent().parent().find('.pool_username').val() !== '' || $(element).parent().parent().parent().find('.pool_password').val() !== '');
						}
					}
				});
			}
		});
		
		$('.pool_username').each(function () {
			if ($(this).data('ismain'))
			{
				$(this).rules('add', 'required');
			}
			else
			{
				$(this).rules('add', {
					required: {
						depends: function(element) {
							return ($(element).parent().parent().parent().find('.pool_url').val() !== '' || $(element).parent().parent().parent().find('.pool_password').val() !== '');
						}
					}
				});
			}
		});
		
		$('.pool_password').each(function () {
			if ($(this).data('ismain'))
			{
				$(this).rules('add', 'required');
			}
			else
			{
				$(this).rules('add', {
					required: {
						depends: function(element) {
							return ($(element).parent().parent().parent().find('.pool_username').val() !== '' || $(element).parent().parent().parent().find('.pool_url').val() !== '');
						}
					}
				});
			}
		});
		
		$('.net_miner_name').each(function () {
			$(this).rules('add', {
				required: {
					depends: function(element) {
						return ($(element).parent().parent().parent().find('.net_miner_ip').val() !== '' || $(element).parent().parent().parent().find('.net_miner_port').val() !== '');
					}
				}
			});
		});
		$('.net_miner_ip').each(function () {
			$(this).rules('add', {
				required: {
					depends: function(element) {
						return ($(element).parent().parent().parent().find('.net_miner_name').val() !== '' || $(element).parent().parent().parent().find('.net_miner_port').val() !== '');
					}
				},
				validIP: true
			});
		});
		$('.net_miner_port').each(function () {
			$(this).rules('add', {
				required: {
					depends: function(element) {
						return ($(element).parent().parent().parent().find('.net_miner_ip').val() !== '' || $(element).parent().parent().parent().find('.net_miner_name').val() !== '');
					}
				},
				number: true
			});
		});
	} else if (thisSection === 'dashboard') {
		
		// Dashboard Scripts
	    if (window.location.href.match(/dashboard/g))
	    {
		    $('.treeview-menu-dashboard-icon').removeClass('fa-angle-left').addClass('fa-angle-down');
		    $('.treeview-menu-dashboard-icon').parent('a').first().addClass('activeTree');
			$('.treeview-menu-dashboard').fadeIn();
	    }
		
		// Refresh stats when you come back in Minera tab
		window.onblur= function() { window.onfocus= function () { getStats(true); target_date = new Date().getTime(); }; };
		
		var refresh_time = $('.app_data').data('refresh-time');
		
		// set the date we're counting down to
		var target_date = new Date().getTime();
		 
		// variables for time units
		var days, hours, minutes, seconds;
		
		// update the tag with id 'countdown' every 1 second
		setInterval(function () {
		    // find the amount of 'seconds' between now and target
		    var current_date = new Date().getTime();
		    var seconds_left = (target_date + (refresh_time*1000 + 1000) - current_date) / 1000;
				//console.log(parseInt(seconds_left));
			if (parseInt(seconds_left) !== 0)
			{
			    // do some time calculations
				minutes = parseInt(seconds_left / 60);
				seconds = parseInt(seconds_left % 60);
		     
				// format countdown string + set tag value
				$('.auto-refresh-time').html(minutes + 'm : ' + seconds + 's ');	
			}
			else
			{
				target_date = new Date().getTime();
				getStats(true);
			}
		 
		}, 1000);
			
		// Refresh button
		$(document).on('click', '.refresh-btn', function(e) { e.preventDefault(); getStats(true); target_date = new Date().getTime(); });
		
		// Save frequency table button
		$('.btn-saved-freq').click( function() {
			$('.freq-box').fadeToggle();
		});
		
		$('.save-freq').click( function() {
			$('.freq-box').fadeOut();
			$.ajax(_baseUrl+'/app/api?command=save_current_freq', {
		        dataType: 'text',
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
		$('.view-raw-stats').click( function() { $('.section-raw-stats').fadeIn(); });
		$('.close-stats').click( function() { $('.section-raw-stats').fadeOut(); });
		
		//Make the dashboard widgets sortable Using jquery UI
		$('.connectedSortable').sortable({
		    placeholder: 'sort-highlight',
		    connectWith: '.connectedSortable',
		    handle: '.box-header, .nav-tabs',
		    forcePlaceholderSize: true,
		    zIndex: 999999
		});
		$('.box-header, .nav-tabs').css('cursor','move');
		    
		/*
		// Start logviewer
		*/
		var dataelem = '#real-time-log-data';
		var pausetoggle = '#pause';
		var scrollelems = ['.real-time-log-data'];
		
		var log_url = $('.app_data').data('minerd-log');
		var fix_rn = true;
		var load_log = 1 * 1024; /* 30KB */
		var poll = 1000; /* 1s */
		
		var kill = false;
		var loading = false;

		var reverse = false;
		var log_data = '';
		var log_size = 0;
		var pause_log = false;
		
		// Logs
		var get_log = function() {
		    if (kill | loading) return;
		    loading = true;
		
		    var range;
		    if (log_size === 0)
		        /* Get the last 'load' bytes */
		        range = '-' + load_log.toString();
		    else
		        /* Get the (log_size - 1)th byte, onwards. */
		        range = (log_size - 1).toString() + '-';
		
		    /* The 'log_size - 1' deliberately reloads the last byte, which we already
		     * have. This is to prevent a 416 'Range unsatisfiable' error: a response
		     * of length 1 tells us that the file hasn't changed yet. A 416 shows that
		     * the file has been trucnated */
		
		    $.ajax(log_url, {
		        dataType: 'text',
		        cache: false,
		        headers: {Range: 'bytes=' + range},
		        success: function (data, s, xhr) {
		            loading = false;
		
		            var size;
		
		            if (xhr.status === 206) {
		                //if (data.length > load_log)
		                    //throw 'Expected 206 Partial Content';
		
		                var c_r = xhr.getResponseHeader('Content-Range');
		                if (!c_r)
		                    throw 'Server did not respond with a Content-Range';
		
		                size = parseInt(c_r.split('/')[1]);
		                if (isNaN(size))
		                    throw 'Invalid Content-Range size';
		            } else if (xhr.status === 200) {
		                if (log_size > 1)
		                    throw 'Expected 206 Partial Content';
		
		                size = data.length;
		            }
		
		            var added = false, start = 0;
		
		            if (log_size === 0) {
		                /* Clip leading part-line if not the whole file */
		                if (data.length < size) {
		                    start = data.indexOf('\n');
		                    log_data = data.substring(start + 1);
		                } else {
		                    log_data = data;
		                }
		
		                added = true;
		            } else {
		                /* Drop the first byte (see above) */
		                log_data += data.substring(1);
		
		                if (log_data.length > load_log) {
		                    start = log_data.indexOf('\n', log_data.length - load_log);
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
		
		            if (xhr.status === 416 || xhr.status === 404) {
		                /* 416: Requested range not satisfiable: log was truncated. */
		                /* 404: Retry soon, I guess */
		
		                log_size = 0;
		                log_data = '';
		                show_log();
		
		                setTimeout(get_log, poll);
		            } else {
		                if (s === 'error')
		                    console.log(xhr.statusText);
		                else
		                    console.log('AJAX Error: ' + s);
		            }
		        }
		    });
		};
		
		var scroll_log = function(where) {
		    for (var i = 0; i < scrollelems.length; i++) {
		        var s = $(scrollelems[i]);
		        if (where === -1)
		            s.scrollTop(s.height());
		        else
		            s.scrollTop(where);
		    }
		};
		
		var show_log = function() {
		    if (pause_log) return;
		
		    var t = log_data;
		
		    if (reverse) {
		        var t_a = t.split(/\n/g);
		        t_a.reverse();
		        if (t_a[0] === '') 
		            t_a.shift();
		        t = t_a.join('\n');
		    }
		
		    if (fix_rn)
		        t = t.replace(/\n/g, '\r\n');
		
		    $(dataelem).text(t);
		    if (!reverse)
		        scroll_log(-1);
		};
					
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
			
		$('.profitability-question').click(function(e) {
			e.preventDefault();
			$('.profitability-help').fadeToggle();
		});
		
		$('#profitability-slider').ionRangeSlider({
			min: 0,
			max: 0.001,
			to: 0,
			type: 'single',
			step: 0.00001,
			postfix: ' <i class="fa fa-btc"></i>',
			grid: true,
			onChange: function (obj) {
				changeEarnings(obj.from);
			}
		});	
	}
    
});

// ****************************** //
// NETWORK miners pools functions //
// ****************************** //

// Select Pool on the fly
$(document).on('click', '.select-net-pool', function(e) {

	e.preventDefault();
	$('.overlay').show();
    var poolId = $(this).data('pool-id'),
    	netConfig = $(this).data('pool-config'),
    	netMiner = $(this).data('netminer');
    $.ajax(_baseUrl+'/app/api?command=select_pool&poolId='+poolId+'&network='+netConfig, {
        dataType: 'text',
        success: function (dataP) {
        	if (dataP)
        	{
        		var dataJ = $.parseJSON(dataP);
    			setTimeout(function() { getStats(true); }, 2000);
				$('.net-pool-alert-'+netMiner).html('Miner could take some minutes to complete the switching process, try to <a href="#" class="refresh-btn">refresh the dashboard</a>. <pre style="font-size:10px;margin-top:10px;">'+dataP+'</pre>');
				setTimeout(function() {
					$('.net-pool-alert-'+netMiner).html('');
    			}, 30000);
    		}
        }
    });
});

// Remove Pool on the fly
$(document).on('click', '.remove-net-pool', function(e) {

	e.preventDefault();
	$('.overlay').show();
    var poolId = $(this).data('pool-id'),
    	netConfig = $(this).data('pool-config'),
    	netMiner = $(this).data('netminer');
    $.ajax(_baseUrl+'/app/api?command=remove_pool&poolId='+poolId+'&network='+netConfig, {
        dataType: 'text',
        success: function (dataP) {
        	if (dataP)
        	{
        		var dataJ = $.parseJSON(dataP);
    			setTimeout(function() { getStats(true); }, 2000);
    			if (dataJ)
    			{
    				$('.net-pool-alert-'+netMiner).html('Miner response: <pre style="font-size:10px;margin-top:10px;">'+dataP+'</pre>');
    				setTimeout(function() {
						$('.net-pool-alert-'+netMiner).html('');
	    			}, 30000);
    			}
    		}
        }
    });
});

// Add network Pool on the fly
$(document).on('click', '.toggle-add-net-pool', function(e) {

	e.preventDefault();
	//$('.overlay').show();
	if ($(this).data('open')) {
		$(this).nextAll('.form-group').fadeOut();
		$(this).data('open', false);
	} else {
		$(this).nextAll('.form-group').fadeIn();
		$(this).data('open', true);
	}
});

$(document).on('click', '.add-net-pool', function(e) {

	e.preventDefault();
	$('.overlay').show();
	var netMiner = $(this).data('netminer');
	if ($('.pool_url_'+$(this).data('netminer')).val() && $('.pool_username_'+$(this).data('netminer')).val() && $('.pool_password_'+$(this).data('netminer')).val()) {
		$('.net-pool-error-'+$(this).data('netminer')).html('').fadeOut();
		var params = {
				command: 'add_pool',
				url: $('.pool_url_'+$(this).data('netminer')).val(),
				user: $('.pool_username_'+$(this).data('netminer')).val(),
				pass: $('.pool_password_'+$(this).data('netminer')).val(),
				network: $(this).data('network')
			},
			query = $.param(params);

		$.ajax(_baseUrl+'/app/api?'+query, {
	        dataType: 'text',
	        success: function (dataP) {
	        	if (dataP)
	        	{
	        		var dataJ = $.parseJSON(dataP);
	        		//console.log(dataJ);
	    			getStats(true);
	    			if (dataJ)
	    			{
	    				$('.net-pool-alert-'+netMiner).html('Miner response: <pre style="font-size:10px;margin-top:10px;">'+dataP+'</pre>');
	    				setTimeout(function() {
							$('.net-pool-alert-'+netMiner).html('');
		    			}, 30000);
	    			}
	    			$('.pool_url_'+netMiner).val('').addClass('error');
					$('.pool_username_'+netMiner).val('').addClass('error');
					$('.pool_password_'+netMiner).val('').addClass('error');
	    		}
	        }
	    });
	} else {
		$('.net-pool-error-'+netMiner).html('<i class="fa fa-warning"></i> Each field is required').fadeIn();
	}
});

$(document).on('click', '.add-net-donation-pool', function(e) {

	e.preventDefault();
	$('.overlay').show();
	var netMiner = $(this).data('netminer'),
		donationUrl = ($(this).data('netcoin') === 'SHA-256') ? $('.app_data').data('minera-pool-url-sha256') : $('.app_data').data('minera-pool-url-scrypt'),
		params = {
			command: 'add_pool',
			url: donationUrl,
			user: $('.app_data').data('minera-pool-username'),
			pass: 'x',
			network: $(this).data('network')
		},
		query = $.param(params);

	$.ajax(_baseUrl+'/app/api?'+query, {
        dataType: 'text',
        success: function (dataP) {
        	if (dataP)
        	{
        		var dataJ = $.parseJSON(dataP);
        		//console.log(dataJ);
    			getStats(true);
    			if (dataJ)
    			{
    				$('.net-pool-alert-'+netMiner).html('Miner response: <pre style="font-size:10px;margin-top:10px;">'+dataP+'</pre>');
    				setTimeout(function() {
						$('.net-pool-alert-'+netMiner).html('');
	    			}, 30000);
    			}
    			$('.pool_url_'+netMiner).val('').addClass('error');
				$('.pool_username_'+netMiner).val('').addClass('error');
				$('.pool_password_'+netMiner).val('').addClass('error');
    		}
        }
    });
});

// ****************************** //
//  LOCAL miners pools functions  //
// ****************************** //

// Select Pool on the fly
$(document).on('click', '.select-pool', function(e) {

	e.preventDefault();
	$('.overlay').show();
    var poolId = $(this).data('pool-id');
    $.ajax(_baseUrl+'/app/api?command=select_pool&poolId='+poolId, {
        dataType: 'text',
        success: function (dataP) {
        	if (dataP)
        	{
        		var dataJ = $.parseJSON(dataP);
        		
    			getStats(true);
    			if (dataJ)
    			{
    				$('.pool-alert').html('CG/BFGminer could take some minutes to complete the switching process, try to <a href="#" class="refresh-btn">refresh the dashboard</a>.. <pre style="font-size:10px;margin-top:10px;">'+dataP+'</pre>').fadeIn();
    				
    				setTimeout(function() {
						$('.pool-alert').html('');
	    			}, 30000);
    			}
    		}
        }
    });
});

// Toggle local pool on the fly
$(document).on('click', '.toggle-add-pool', function(e) {

	e.preventDefault();
	//$('.overlay').show();
	if ($(this).data('open')) {
		$(this).nextAll('.form-group').fadeOut();
		$(this).data('open', false);
	} else {
		$(this).nextAll('.form-group').fadeIn();
		$(this).data('open', true);
	}
});

$(document).on('click', '.add-pool', function(e) {

	e.preventDefault();
	$('.overlay').show();
	if ($('.local_pool_url').val() && $('.local_pool_username').val() && $('.local_pool_password').val()) {
		$('.pool-alert').html('').fadeOut();
		var params = {
				command: 'add_pool',
				url: $('.local_pool_url').val(),
				user: $('.local_pool_username').val(),
				pass: $('.local_pool_password').val()
			},
			query = $.param(params);

		$.ajax(_baseUrl+'/app/api?'+query, {
	        dataType: 'text',
	        success: function (dataP) {
	        	if (dataP)
	        	{
	        		var dataJ = $.parseJSON(dataP);
	        		//console.log(dataJ);
	    			getStats(true);
	    			if (dataJ)
	    			{
	    				$('.pool-alert').html('Miner response: <pre style="font-size:10px;margin-top:10px;">'+dataP+'</pre>').fadeIn();
	    				setTimeout(function() {
							$('.pool-alert').html('');
		    			}, 30000);
	    			}
	    			$('.local_pool_url').val('').addClass('error');
					$('.local_pool_username').val('').addClass('error');
					$('.local_pool_password').val('').addClass('error');
	    		}
	        }
	    });
	} else {
		$('.pool-alert').html('<i class="fa fa-warning"></i> Each field is required').fadeIn();
	}
});

// Remove local pool on the fly
$(document).on('click', '.remove-pool', function(e) {

	e.preventDefault();

	if ($('.app_data').data('miner-running') !== 'cpuminer' && $('.app_data').data('miner-running') !== undefined) {
		$('.overlay').show();
	    var poolId = $(this).data('pool-id');
	    $.ajax(_baseUrl+'/app/api?command=remove_pool&poolId='+poolId, {
	        dataType: 'text',
	        success: function (dataP) {
	        	if (dataP)
	        	{
	        		var dataJ = $.parseJSON(dataP);
	    			setTimeout(function() { getStats(true); }, 2000);
	    			if (dataJ)
	    			{
	    				$('.pool-alert').html('Miner response: <pre style="font-size:10px;margin-top:10px;">'+dataP+'</pre>').fadeIn();
	    				setTimeout(function() {
							$('.pool-alert').html('');
		    			}, 30000);
	    			}
	    		}
	        }
	    });
	} else {
		$('.pool-alert').html('<pre style="font-size:10px;margin-top:10px;">Sorry, but CPUMiner doesn\'t support remove pool on the fly</pre>').fadeIn();
	}
});

$(document).on('click', '.cron-unlock', function(e) {

   	e.preventDefault();
	
   	$('#modal-saving-label').html('Unlocking cron...');
	$('#modal-saving').modal('show');
	
	var apiUrl = _baseUrl+'/app/api?command=cron_unlock';

	$.ajax({
		type: 'GET',
		url: apiUrl,
		cache: false,
		success:  function(resp){
			$('#modal-saving').modal('hide');
			getStats(true);
		}
	});
});

// Define underscore variable template
if ($('.header').data('this-section') !== 'lockscreen') {
	_.templateSettings.variable = 'rc';

	var btcRatesTemplate = _.template(
			$( 'script.btc-rates-template' ).html()
		),
		avgStatsTemplate = _.template(
			$( 'script.avg-stats-template' ).html()
		);
}

// Stats scripts
function getStats(refresh)
{

	var now = new Date().getTime();
	var d = 0, totalhash = 0, totalac = 0, totalre = 0, totalhw = 0, totalsh = 0, totalfr = 0, totalpoolhash = 0, poolHash = 0,
		errorTriggered = false,
		pool_shares_seconds,
		log_file = $('.app_data').data('minerd-log').replace(/^.*[\\\/]/, ''),
		miner_status = $('.app_data').data('miner-status'),
		// Raw stats
		boxStats = $('.section-raw-stats'),
		thisSection = $('.header').data('this-section');
		
	boxStats.hide();
	
	$('.overlay').show();
	$('.refresh-icon').addClass('fa-spin');
	// Show loaders
	//$('.loading-img').show();
	
	/* Knob, Table, Sysload */		
	// get Json data from minerd and create Knob, table and sysload
    $.getJSON( _baseUrl+'/app/stats', function( data ) 
    {
    	// console.log(data);
		if (data.notloggedin)
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

			$('body').data('stats-loop', 0);
			
			if (data.notrunning)
			{
				$('.disable-if-not-running').fadeOut();
				$('.enable-if-not-running').fadeIn();
				$('.local-widget').removeClass('col-lg-4 col-sm-4').addClass('col-lg-6 col-sm-6');

				if (miner_status) {
					errorTriggered = true;
					$('.warning-message').html('Your local miner is offline. Click here to check last logs.');
					$('.widget-warning').html('Not running');
					$.ajax(_baseUrl+'/app/api?command=tail_log&file='+log_file, {
				        dataType: 'text',
				        success: function (dataP) {
				        	if (dataP)
				        	{
				        		var dataJ = $.parseJSON(dataP);
				        		$('#modal-log-label').html('Check the logs');
				        		$('#modal-log .modal-log-lines').html(dataJ.join('<br />'));
				    		}
				        }
				    });
				    
				    $('.warning-message').click(function (e) {
					    e.preventDefault();
						$('#modal-log').modal('show');
				    });
			    } else {
					$('.disable-if-stopped').fadeOut();
			    }

			}
			else if (data.error)
			{
				errorTriggered = true;
				$('.disable-if-not-running').fadeOut();
				$('.enable-if-not-running').fadeIn();
				$('.warning-message').html(data.msg);
				$('.widget-warning').html('Error');
				$('.local-widget').removeClass('col-lg-4 col-sm-4').addClass('col-lg-6 col-sm-6');
				$.ajax(_baseUrl+'/app/api?command=tail_log&file='+log_file, {
			        dataType: 'text',
			        success: function (dataP) {
			        	if (dataP)
			        	{
			        		var dataJ = $.parseJSON(dataP);
			        		$('#modal-log-label').html('Check the logs');
			        		$('#modal-log .modal-log-lines').html(dataJ.join('<br />'));
			    		}
			        }
			    });
			    
			    $('.warning-message').click(function (e) {
				    e.preventDefault();
					$('#modal-log').modal('show');
			    });
			}
			
			if (refresh)
			{
				// Destroy and clear the data tables before you can re-initialize it
				if ( $.fn.dataTable.isDataTable('#miner-table-details') )
				{
					$('#miner-table-details').dataTable().fnClearTable();
					$('#miner-table-details').dataTable().fnDestroy();							
				}
				if ( $.fn.dataTable.isDataTable('#network-miner-table-details') )
				{
					$('#network-miner-table-details').dataTable().fnClearTable();
					$('#network-miner-table-details').dataTable().fnDestroy();							
				}
				if ( $.fn.dataTable.isDataTable('#pools-table-details') )
				{
					$('#pools-table-details').dataTable().fnClearTable();
					$('#pools-table-details').dataTable().fnDestroy();	
				}
				if ( $.fn.dataTable.isDataTable('#profit-table-details') )
				{
					$('#profit-table-details').dataTable().fnClearTable();
					$('#profit-table-details').dataTable().fnDestroy();	
				}
				$('.net-pools-table').each(function(key, obj) {
					if ( $.fn.dataTable.isDataTable($(this)) )
					{
						$(this).dataTable().fnClearTable();
						$(this).dataTable().fnDestroy();	
					}
				});
			}
			
			// Cron status
			if (data.cron) {
				$('.cron-status').fadeIn();
			} else {
				$('.cron-status').fadeOut();
			}
			
			// Add raw stats box
			boxStats.find('span').html('<pre style="height:350px; overflow: scroll; font-size:10px;">' + JSON.stringify(data, undefined, 2) + '</pre>');
			
			// Add BTC rates
		    if (data.btc_rates) {
			    var btcRatesData = {}; btcRatesData.btc_rates = data.btc_rates;
		        $('.messages-btc-rates').html(btcRatesTemplate(btcRatesData));
			}
			
			if (data.avg)
			{
				var avgStats = [], avgStatsData = {};
				_.each( data.avg, function( aval, akey ) 
				{
					var avgs = {}; avgs.hrCurrentText = '-'; avgs.hrCurrent = 0; avgs.hrPast = 0;
					if (aval[0])
					{
						avgs.hrCurrent = parseInt(aval[0].pool_hashrate / 1000);
						avgs.hrCurrentText = convertHashrate( avgs.hrCurrent );								
					}
					if (aval[1])
					{
						avgs.hrPast = parseInt(aval[1].pool_hashrate / 1000);				
					}

					if (avgs.hrPast > avgs.hrCurrent) {
						avgs.arrow = 'fa-chevron-down';
						avgs.color = '#f56954';
					} else if (avgs.hrPast < avgs.hrCurrent) {
						avgs.arrow = 'fa-chevron-up';
						avgs.color = '#00a65a';
					} else {
						avgs.arrow = 'fa-arrows-h';
						avgs.color = '#dddddd';
					}

					if (akey === '1min')
					{
						avgStatsData = { avgonemin: akey + ': ' + avgs.hrCurrentText, arrow: avgs.arrow, color: avgs.color };
					}
					else
					{
						avgStats.push({
							arrow: avgs.arrow,
							color: avgs.color,
							hrCurrentText: avgs.hrCurrentText,
							key: akey
						});
					}
				});
				
				avgStatsData.avgs = avgStats;

				$('.messages-avg').html(avgStatsTemplate(avgStatsData));
			}
			
			$('.navbar .menu').slimscroll({
		        height: '200px',
		        alwaysVisible: false,
		        size: '3px'
		    }).css('width', '100%');

			if (data.pools)
			{
				if ( !$.fn.dataTable.isDataTable('#pools-table-details') )
				{
					// Initialize the pools datatable	
					$('#pools-table-details').dataTable({
						'lengthMenu': [ 5, 10, 25, 50 ],
						'pageLength': $('.app_data').data('records-per-page'),
						'stateSave': true,
						'stateSaveCallback': function (settings, data) {

						},
						'bAutoWidth': false,
						//'sDom': 't',
						'order': [[ 3, 'asc' ]],
						'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
							//if(iDisplayIndex === 0)
							//	nRow.className = 'bg-dark';
							return nRow;
						},
						'aoColumnDefs': [ 
						{
							'aTargets': [ 5 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									return '<small class="badge bg-'+data.label+'">'+ convertHashrate(data.hash) +'</small>';
								}
								return data.hash;
							},
						},
						{
							'aTargets': [ 7, 9, 11 ],	
							'mRender': function ( data, type, full ) {
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
					var parser = document.createElement('a'),
						picon = 'download',
						ptype = 'failover',
						pclass = 'bg-light',
						plabel = 'light',
						pactivelabclass = '',
						paliveclass = '',
						palivelabel = '',
						puserlabel = '',
						pactivelab = 'Select This',
						purlicon = '',
						purl = pval.url,
						pshorturl = purl,
						pool_shares = 0;
					
					parser.href = pval.url;

					if (parser.hostname) {
						pshorturl = parser.hostname;
					} else {
						pshorturl = pval.url.replace('stratum+tcp://', '').split(':')[0];	
					}
					
					if (pval.alive)
					{
						paliveclass = 'success';
						palivelabel = 'Alive';
					}
					else
					{
						paliveclass = 'danger';
						palivelabel = 'Dead';
					}
					
					puserlabel = 'blue';
					purlicon = '<i class="fa fa-flash"></i>&nbsp;';
					if (pval.user === $('.app_data').data('minera-pool-username'))
					{
						puserlabel = 'navy';
						purlicon = '<i class="fa fa-gift"></i>&nbsp;';
					}

					// Main pool
					if (pval.active === true || pval.active === 1)
					{	
						pool_shares_seconds = parseFloat((now/1000)-pval.start_time);
						pool_shares = pval.shares;
						picon = 'upload';
						ptype = 'active';
						pclass = 'bg-dark';
						plabel = 'primary';
						pactivelabclass = 'disabled';
						pactivelab = 'Selected';
						pshorturl = '<strong>'+pshorturl+'</strong>';
					}
					
					var pstatsId = pval.stats_id;
					var pshares = 0; var paccepted = 0; var prejected = 0; var psharesPrev = 0; var pacceptedPrev = 0; var prejectedPrev = 0; var phashData = {}; phashData.hash = 0; phashData.label = 'muted'; phashData.pstart_time = 'Never started';
					// Get the pool stats
					for (var p = 0; p < pval.stats.length; p++) 
					{
						var pstats = pval.stats[p];

						if (pstatsId === pstats.stats_id)
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
								$('.widget-total-hashrate').html(convertHashrate(phashData.hash));
								$('.widget-total-hashrate').data('pool-hashrate', phashData.hash);

								$('.widget-main-pool').html(palivelabel);
								$('.widget-main-pool').next('p').html(pval.url);
								// Changing title page according to hashrate
								$(document).attr('title', 'Local: '+convertHashrate(phashData.hash));
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
							'<button class="btn btn-xs btn-danger '+pactivelabclass+' remove-pool" data-pool-id="'+pkey+'"><i class="fa fa-close"></i></button>',
							'<button style="width:90px;" class="btn btn-sm btn-default '+pactivelabclass+' select-pool" data-pool-id="'+pkey+'"><i class="fa fa-cloud-'+picon+'"></i> '+pactivelab+'</button>',
							purlicon+'<small data-toggle="popover" data-html="true" data-title="Priority: '+pval.priority+'" data-content="<small>'+purl+'</small>">'+pshorturl+'</small>',
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
			}
			else
			{
				$('#pools-table-details').html('<div class="alert alert-warning"><i class="fa fa-warning"></i><strong>No active pools</strong> data available.</div>');
			}
			
			if (data.devices)
			{
				if ( !$.fn.dataTable.isDataTable('#miner-table-details') )
				{
					// Initialize the miner datatable	
					$('#miner-table-details').dataTable({
						'lengthMenu': [ 5, 10, 25, 50 ],
						'pageLength': $('.app_data').data('records-per-page'),
						'stateSave': true,
						'bAutoWidth': false,
						'aoColumnDefs': [
						{
							'aTargets': [ 1 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									if (data)
										return '<small class="label bg-blue">'+data +'&deg;</small>';
									else
										return '<small class="label label-muted">n.a.</small>';
								}
								return data;
							},
						},
						{
							'aTargets': [ 2 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									if (data)
										return '<small class="label label-light">'+data +' MHz</small>';
									else
										return '<small class="label label-light">not available</small>';
								}
								return data;
							},
						},
						{
							'aTargets': [ 3 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									return '<small class="badge bg-'+data.label+'">'+ convertHashrate(data.hash) +'</small>';
								}
								return data.hash;
							}
						},
						{
							'aTargets': [ 11 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									return data +' secs ago';
								}
								return data;
							}
						},
						{
							'aTargets': [ 6, 8, 10 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									return '<small class="text-muted">' + data + '%</small>';
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
					
			    	items[key] = { 'temp': val.temperature, 'serial': val.serial, 'hash': hashrate, 'ac': val.accepted, 're': val.rejected, 'hw': val.hw_errors, 'fr': val.frequency, 'sh': val.shares, 'ls': val.last_share };

					hashrates.push(hashrate);

			    });
			    
		    	var maxHashrate = Math.max.apply(Math, hashrates);

				var avgFr = (data.totals.frequency) ? data.totals.frequency : 'n.a.';
				var totTemp = (data.totals.temperature) ? data.totals.temperature : 'n.a.';
				
				totalhash = Math.round(data.totals.hashrate/1000);

				// this is the global stats
				items.total = { 'temp': totTemp, 'serial': '', 'hash': totalhash, 'ac': data.totals.accepted, 're': data.totals.rejected, 'hw': data.totals.hw_errors, 'fr': avgFr, 'sh': data.totals.shares, 'ls':  data.totals.last_share};
				
				for (var index in items) 
				{
									
					// Add per device rows in system table
					var devData = {}; devData.hash = items[index].hash;
					var share_date = new Date(items[index].ls*1000);
					var rightnow = new Date().getTime();

					var last_share_secs = (items[index].ls > 0) ? (rightnow - share_date.getTime())/1000 : 0;
					if (last_share_secs < 0) last_share_secs = 0;

					var totalWorkedShares = (parseFloat(items[index].ac) + parseFloat(items[index].re) + parseFloat(items[index].hw));
					var percentageAc = parseFloat(100*items[index].ac/totalWorkedShares);
					var percentageRe = parseFloat(100*items[index].re/totalWorkedShares);
					var percentageHw = parseFloat(100*items[index].hw/totalWorkedShares);

					if (isNaN(percentageAc)) percentageAc = 0;
					if (isNaN(percentageRe)) percentageRe = 0;
					if (isNaN(percentageHw)) percentageHw = 0;

					// Add colored hashrates
					if (last_share_secs >= 120 && last_share_secs < 240)
						devData.label = 'yellow';
					else if (last_share_secs >= 240 && last_share_secs < 480)
						devData.label = 'red';
					else if (last_share_secs >= 480)
						devData.label = 'muted';
					else
						devData.label = 'green';
												
					var dev_serial = 'serial not available';
					if (index !== 'total' && items[index].serial)
					{
						dev_serial = 'serial: '+items[index].serial;
					}
					else
					{
						// Widgets
						$('.widget-last-share').html(parseInt(last_share_secs) + ' secs');
						$('.widget-hwre-rates').html(parseFloat(percentageHw).toFixed(2) + '<sup style="font-size: 20px">%</sup> / ' + parseFloat(percentageRe).toFixed(2) + '<sup style="font-size: 20px">%</sup>');
						dev_serial = '';
						//Sidebar hashrate
						//$('.sidebar-hashrate').html("@ "+convertHashrate(items[index].hash));
					}
					
					var devRow = '<tr class="dev-'+index+'"><td class="devs_table_name"><i class="glyphicon glyphicon-hdd"></i>&nbsp;&nbsp;'+index+dev_serial+'</td><td class="devs_table_temp">'+ items[index].temp + '</td><td class="devs_table_freq">'+ items[index].fr + 'MHz</td><td class="devs_table_hash"><strong>'+ convertHashrate(items[index].hash) +'</strong></td><td class="devs_table_sh">'+ items[index].sh +'</td><td class="devs_table_ac">'+ items[index].ac +'</td><td><small class="text-muted">'+parseFloat(percentageAc).toFixed(2)+'%</small></td><td class="devs_table_re">'+ items[index].re +'</td><td><small class="text-muted">'+parseFloat(percentageRe).toFixed(2)+'%</small></td><td class="devs_table_hw">'+ items[index].hw +'</td><td><small class="text-muted">'+parseFloat(percentageHw).toFixed(2)+'%</small></td><td class="devs_table_ls">'+ parseInt(last_share_secs) +' secs ago</td><td><small class="text-muted">'+share_date.toUTCString()+'</small></td></tr>';
				
					if (index === 'total')
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
					
					if ($('.app_data').data('device-tree')) {
						// Crete Knob graph for devices and total
						createMon(index, items[index].hash, totalhash, maxHashrate, items[index].ac, items[index].re, items[index].hw, items[index].sh, items[index].fr, devData.label);
					}
					
				}
				$('[data-toggle="tooltip"]').tooltip();
			}
			else
			{
				var nodevsMsg = '<div class="alert alert-warning"><i class="fa fa-warning"></i>No active local devices found</div>';
				$('#miner-table-details').html(nodevsMsg);
				$('#devs').html(nodevsMsg).removeClass('row');
			}
			
			//******************//
			//					//
			//  Network miners  //
			//					//
			//******************//
			if (data.network_miners)
			{				
				$('.local-miners-title').show();
				$('.network-miners-widget-section').show();
				$('.network-miner-details').show();
																		
				var netHashrates = 0,
					netPoolHashrates = 0,
					networkMiners = [],
					tLastShares = [],
					tAc = 0, tRe = 0, tHw = 0, tSh = 0;
					
				//console.log(data.network_miners);
				
				if (Object.keys(data.network_miners).length > 0)
				{
					if ( !$.fn.dataTable.isDataTable('#network-miner-table-details') )
					{
						// Initialize the miner datatable	
						$('#network-miner-table-details').dataTable({
							'lengthMenu': [ 5, 10, 25, 50 ],
							'pageLength': $('.app_data').data('records-per-page'),
							'stateSave': true,
							'bAutoWidth': false,
							'aoColumnDefs': [
							{
								'aTargets': [ 1 ],	
								'mRender': function ( data, type, full ) {
									if (type === 'display')
									{
										if (data)
											return '<small class="label bg-blue">'+data +'&deg;</small>';
										else
											return '<small class="label label-muted">n.a.</small>';
									}
									return data;
								},
							},
							{
								'aTargets': [ 2 ],	
								'mRender': function ( data, type, full ) {
									if (type === 'display')
									{
										if (data)
											return '<small class="label label-light">'+data +' MHz</small>';
										else
											return '<small class="label label-light">not available</small>';
									}
									return data;
								},
							},
							{
								'aTargets': [ 3 ],	
								'mRender': function ( data, type, full ) {
									if (type === 'display')
									{
										return '<small class="badge bg-'+data.label+'">'+ convertHashrate(data.hash) +'</small>';
									}
									return data.hash;
								}
							},
							{
								'aTargets': [ 11 ],	
								'mRender': function ( data, type, full ) {
									if (type === 'display')
									{
										return data +' secs ago';
									}
									return data;
								}
							},
							{
								'aTargets': [ 6, 8, 10 ],	
								'mRender': function ( data, type, full ) {
									if (type === 'display')
									{
										return '<small class="text-muted">' + data + '%</small>';
									}
									return data;
								}
							},
							],
						});
					}

					$.each(data.network_miners, function (netKey, networkMinerData) {
						if (networkMinerData.devices) 
						{	
							networkMiners[netKey] = networkMinerData.devices;
							
							// Add per network device stats
							$.each( networkMinerData.devices, function( key, val ) {	
						    	// these are the single devices stats
						    	var hashrate = Math.round(val.hashrate/1000);
								
						    	networkMiners[netKey][key] = { 'temp': val.temperature, 'serial': val.serial, 'hash': hashrate, 'ac': val.accepted, 're': val.rejected, 'hw': val.hw_errors, 'fr': val.frequency, 'sh': val.shares, 'ls': val.last_share };
		
								netHashrates += hashrate;
								
								tAc += val.accepted;
								tRe += val.rejected;
								tHw += val.hw_errors;
								tSh += val.shares;
								tLastShares.push(val.last_share);
		
								// this is the global stats
								networkMiners.total = { 'ac': tAc, 're': tRe, 'hw': tHw, 'sh': tSh };
						    });
						    
							
							for (var index in networkMiners[netKey])
							{
								// Add per device rows in system table
								var devData = {}; devData.hash = networkMiners[netKey][index].hash;
								var share_date = new Date(networkMiners[netKey][index].ls*1000);
								var rightnow = new Date().getTime();
		
								var last_share_secs = (networkMiners[netKey][index].ls > 0) ? (rightnow - share_date.getTime())/1000 : 0;
								if (last_share_secs < 0) last_share_secs = 0;
		
								var totalWorkedShares = (networkMiners[netKey][index].ac+networkMiners[netKey][index].re+networkMiners[netKey][index].hw);
								var percentageAc = (100*networkMiners[netKey][index].ac/totalWorkedShares);
								var percentageRe = (100*networkMiners[netKey][index].re/totalWorkedShares);
								var percentageHw = (100*networkMiners[netKey][index].hw/totalWorkedShares);
		
								// Add colored hashrates
								if (last_share_secs >= 120 && last_share_secs < 240)
									devData.label = 'yellow';
								else if (last_share_secs >= 240 && last_share_secs < 480)
									devData.label = 'red';
								else if (last_share_secs >= 480)
									devData.label = 'muted';
								else
									devData.label = 'green';
															
								var dev_serial = 'serial not available';
								if (networkMiners[netKey][index].serial)
								{
									dev_serial = 'serial: '+networkMiners[netKey][index].serial;
								}

								/*
								// Widgets
								$('.widget-last-share').html(parseInt(last_share_secs) + ' secs');
								$('.widget-hwre-rates').html(parseFloat(percentageHw).toFixed(2) + '<sup style="font-size: 20px">%</sup> / ' + parseFloat(percentageRe).toFixed(2) + '<sup style="font-size: 20px">%</sup>');
								dev_serial = '';
								//Sidebar hashrate
								//$('.sidebar-hashrate').html('@ '+convertHashrate(items[index].hash));
								*/
								
								if ( $.fn.dataTable.isDataTable('#network-miner-table-details') )
								{
									// New add rows via datatable
									$('#network-miner-table-details').dataTable().fnAddData( [
										'<span><i class="gi gi-server"></i>&nbsp;&nbsp;'+index+'<br /><span class="label label-success" data-toggle="popover" data-title="'+netKey+'" data-content="'+[networkMinerData.config.ip, networkMinerData.config.port].join(':')+'">'+netKey+'</span></span>',
										networkMiners[netKey][index].temp,
										networkMiners[netKey][index].fr,
										devData,
										networkMiners[netKey][index].sh,
										networkMiners[netKey][index].ac,
										parseFloat(percentageAc).toFixed(2),
										networkMiners[netKey][index].re,
										parseFloat(percentageRe).toFixed(2),
										networkMiners[netKey][index].hw,
										parseFloat(percentageHw).toFixed(2),
										parseInt(last_share_secs),
										'<small class="text-muted">'+share_date.toUTCString()+'</small>'
									] );
								}
							}								
							
							// Add network pools table
							$('.net-pools-label-'+md5(netKey)).html('<h4><span class="label label-success" data-toggle="popover" data-title="'+netKey+'" data-content="'+[networkMinerData.config.ip, networkMinerData.config.port].join(':')+'">Online</span> '+netKey+'</h4>');

							// Get main/active network pool data
							if (networkMinerData.pool)
							{
								var netpoolhashrate = (networkMinerData.pool.hashrate) ? networkMinerData.pool.hashrate : 0;
							}
							
							if (networkMinerData.pools)
							{
								if ( !$.fn.dataTable.isDataTable('#net-pools-table-details-'+md5(netKey)) )
								{
									// Initialize the pools datatable	
									$('#net-pools-table-details-'+md5(netKey)).dataTable({
										'lengthMenu': [ 5, 10, 25, 50 ],
										'pageLength': $('.app_data').data('records-per-page'),
										'stateSave': true,
										'bAutoWidth': false,
										//'sDom': 't',
										'order': [[ 4, 'asc' ]],
										'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
											//if(iDisplayIndex === 0)
											//	nRow.className = 'bg-dark';
											return nRow;
										},
										'aoColumnDefs': [ 
										{
											'aTargets': [ 5 ],	
											'mRender': function ( data, type, full ) {
												if (type === 'display')
												{
													return '<small class="badge bg-'+data.label+'">'+ convertHashrate(data.hash) +'</small>';
												}
												return data.hash;
											},
										},
										{
											'aTargets': [ 7, 9, 11 ],	
											'mRender': function ( data, type, full ) {
												if (type === 'display')
												{
													return '<small class="text-muted">'+ data +'</small>';
												}
												return data;
											},
										}
										]
									});
								}
								
								// Add pools data
								var pdonationUrl = false;
								$.each(networkMinerData.pools, function( pkey, pval ) 
								{
									var parser = document.createElement('a'),
										picon = 'download',
										ptype = 'failover',
										pclass = 'bg-light',
										plabel = 'light',
										pactivelabclass = '',
										paliveclass = '',
										palivelabel = '',
										puserlabel = '',
										pactivelab = 'Select This',
										purlicon = '',
										purl = pval.url,
										pshorturl = purl,
										pool_shares = 0;
									
									parser.href = pval.url;

									if (parser.hostname) {
										pshorturl = parser.hostname;
									} else {
										pshorturl = pval.url.replace('stratum+tcp://', '').split(':')[0];	
									}
									
									if (pval.alive)
									{
										paliveclass = 'success';
										palivelabel = 'Alive';
									}
									else
									{
										paliveclass = 'danger';
										palivelabel = 'Dead';
									}
									
									puserlabel = 'blue';
									purlicon = '<i class="fa fa-flash"></i>&nbsp;';
									if (pval.user === $('.app_data').data('minera-pool-username'))
									{
										puserlabel = 'navy';
										purlicon = '<i class="fa fa-gift"></i>&nbsp;';
										pdonationUrl = true;
										$('.net-pools-addbox-'+md5(netKey)+' .add-net-donation-pool').fadeOut();
									}
				
									// Main pool
									if (pval.active === true || pval.active === 1)
									{	
										pool_shares_seconds = parseFloat((now/1000)-pval.start_time);
										pool_shares = pval.shares;
										picon = 'upload';
										ptype = 'active';
										pclass = 'bg-dark';
										plabel = 'primary';
										pactivelabclass = 'disabled';
										pactivelab = 'Selected';
										pshorturl = '<strong>'+pshorturl+'</strong>';
									}
									
									var pstatsId = pval.stats_id;
									var pshares = 0; var paccepted = 0; var prejected = 0; var psharesPrev = 0; var pacceptedPrev = 0; var prejectedPrev = 0; var phashData = {}; phashData.hash = 0; phashData.label = 'muted'; phashData.pstart_time = 'Never started';
									// Get the pool stats
									for (var p = 0; p < pval.stats.length; p++) 
									{
										var pstats = pval.stats[p];
				
										if (pstatsId === pstats.stats_id)
										{
											phashData.pstart_time = new Date(pstats.start_time*1000);
											phashData.pstart_time = phashData.pstart_time.toUTCString();
											pshares = pstats.shares;
											paccepted = pstats.accepted;
											prejected = pstats.rejected;
											
											// Calculate the real pool hashrate
											if (pval.active === true || pval.active === 1) 
											{
												phashData.hash = parseInt(netpoolhashrate/1000); //parseInt((65536.0 * (pshares/(now/1000-pstats.start_time)))/1000);
												phashData.label = 'red';
												netPoolHashrates += phashData.hash;
											}
				
										}
										else
										{
											psharesPrev = psharesPrev + pstats.shares;
											pacceptedPrev = pacceptedPrev + pstats.accepted;
											prejectedPrev = prejectedPrev + pstats.rejected;
										}
									}
				
									if ( $.fn.dataTable.isDataTable('#net-pools-table-details-'+md5(netKey)) )
									{
										pval.usershort = (pval.user.length > 15) ? pval.user.substring(0, 15)+'...' : pval.user;
										
										// Add Pool rows via datatable
										$('#net-pools-table-details-'+md5(netKey)).dataTable().fnAddData( [
											'<button class="btn btn-xs btn-danger '+pactivelabclass+' remove-net-pool" data-pool-id="'+pkey+'" data-pool-config="'+[networkMinerData.config.ip, networkMinerData.config.port].join(':')+'" data-netminer="'+md5(netKey)+'"><i class="fa fa-close"></i></button>',
											'<button style="width:90px;" class="btn btn-sm btn-default '+pactivelabclass+' select-net-pool" data-pool-id="'+pkey+'" data-pool-config="'+[networkMinerData.config.ip, networkMinerData.config.port].join(':')+'" data-netminer="'+md5(netKey)+'"><i class="fa fa-cloud-'+picon+'"></i> '+pactivelab+'</button>',
											purlicon+'<small data-toggle="popover" data-html="true" data-title="Priority: '+pval.priority+'" data-content="<small>'+purl+'</small>">'+pshorturl+'</small>',
											'<span class="label label-'+plabel+'">'+ptype+'</span>',
											'<span class="label label-'+paliveclass+'">'+palivelabel+'</span>',
											phashData,
											pshares,
											psharesPrev,
											paccepted,
											pacceptedPrev,
											prejected,
											prejectedPrev,
											'<span class="badge bg-'+puserlabel+'" data-toggle="tooltip" title="'+pval.user+'">'+pval.usershort+'</span>'
										] );
									}
									
								});
								
								if (pdonationUrl === false) {
									$('.net-pools-addbox-'+md5(netKey)+' .add-net-donation-pool').fadeIn();
								}
							}
							else
							{
								$('#net-pools-table-details-'+md5(netKey)).html('<div class="alert alert-warning"><i class="fa fa-warning"></i><strong>No active pools</strong> data available.</div>');
							}
						}
						else
						{
							if ( $.fn.dataTable.isDataTable('#network-miner-table-details') )
							{
								// New add rows via datatable
								$('#network-miner-table-details').dataTable().fnAddData( [
									'<span><i class="gi gi-server_ban"></i>&nbsp;&nbsp;Offline<br /><span class="label label-danger" data-toggle="popover" data-title="'+netKey+'" data-content="'+[networkMinerData.config.ip, networkMinerData.config.port].join(':')+'">'+netKey+'</span></span>',
									0,
									0,
									{hash: 0, label: 'muted'},
									0,
									0,
									0,
									0,
									0,
									0,
									0,
									0,
									0
								] );
							}
							
							// Add empty network pools table
							$('.net-pools-label-'+md5(netKey)).html('<h4><span class="label label-danger" data-toggle="popover" data-title="'+netKey+'" data-content="'+[networkMinerData.config.ip, networkMinerData.config.port].join(':')+'">Offline</span> '+netKey+'</h4>');
							$('#net-pools-table-details-'+md5(netKey)).html('<div class="alert alert-warning"><i class="fa fa-warning"></i><strong>No active pools</strong> data available.</div>');
							$('.net-pools-addbox-'+md5(netKey)).fadeOut();
						}
					});
					
					var tPercentageRe = 0, tPercentageHw = 0, tot_last_share_secs = 0, netDevRow;
					
					if (networkMiners.total !== undefined) {
						var totalShares = (networkMiners.total.ac+networkMiners.total.re+networkMiners.total.hw),
							tPercentageAc = (100*networkMiners.total.ac/totalShares),
							tot_last_share_date = Math.min.apply(Math, tLastShares)*1000;
						
						tPercentageRe = (100*networkMiners.total.re/totalShares);
						tPercentageHw = (100*networkMiners.total.hw/totalShares);
						tot_last_share_secs = (tot_last_share_date > 0) ? (new Date().getTime() - tot_last_share_date)/1000 : 0;
							
						if (tot_last_share_secs < 0) tot_last_share_secs = 0;
						
						netDevRow = '<tr class="dev-total"><td class="devs_table_name"><i class="gi gi-server"></i>&nbsp;&nbsp;Total</td><td class="devs_table_temp">-</td><td class="devs_table_freq">-</td><td class="devs_table_hash"><strong>'+ convertHashrate(netHashrates) +'</strong></td><td class="devs_table_sh">'+ networkMiners.total.sh +'</td><td class="devs_table_ac">'+ networkMiners.total.ac +'</td><td><small class="text-muted">'+parseFloat(tPercentageAc).toFixed(2)+'%</small></td><td class="devs_table_re">'+ networkMiners.total.re +'</td><td><small class="text-muted">'+parseFloat(tPercentageRe).toFixed(2)+'%</small></td><td class="devs_table_hw">'+ networkMiners.total.hw +'</td><td><small class="text-muted">'+parseFloat(tPercentageHw).toFixed(2)+'%</small></td><td class="devs_table_ls">'+ parseInt(tot_last_share_secs) +' secs ago</td><td><small class="text-muted">'+new Date(tot_last_share_date).toUTCString()+'</small></td></tr>';				
						
						// Network Widgets
						$('.network-widget-last-share').html(parseInt(tot_last_share_secs) + ' secs');
						$('.network-widget-hwre-rates').html(parseFloat(tPercentageHw).toFixed(2) + '<sup style="font-size: 20px">%</sup> / ' + parseFloat(tPercentageRe).toFixed(2) + '<sup style="font-size: 20px">%</sup>');
						
					} else {
						netDevRow = '<tr class="dev-total"><td class="devs_table_name"><i class="gi gi-server"></i>&nbsp;&nbsp;Total</td><td class="devs_table_temp">-</td><td class="devs_table_freq">-</td><td class="devs_table_hash"><strong>-</strong></td><td class="devs_table_sh">-</td><td class="devs_table_ac">-</td><td><small class="text-muted">-</small></td><td class="devs_table_re">-</td><td><small class="text-muted">-</small></td><td class="devs_table_hw">-</td><td><small class="text-muted">-</small></td><td class="devs_table_ls">-</td><td><small class="text-muted">-</small></td></tr>';
						
						// Network Widgets
						$('.network-widget-last-share').html('&infin; secs');
						$('.network-widget-hwre-rates').html('Not available');

					}

				    $('.network_devs_table_foot').html(netDevRow);
				    
				    //Add Network Main pool widget
					$('.network-widget-total-hashrate').html(convertHashrate(netPoolHashrates));
					$('.network-widget-total-hashrate').data('pool-hashrate', netPoolHashrates);

					// Changing title page according to hashrate
					$(document).attr('title', $(document).attr('title')+' | Network: '+convertHashrate(netPoolHashrates));
				}
				else
				{
					var nonetdevsMsg = '<div class="alert alert-warning"><i class="fa fa-warning"></i>No network devices found</div>';
					$('#network-miner-table-details').html(nonetdevsMsg);
					$('.network-miners-widget-section').hide();
				}
			} // End network miner details
			
			// Add controller temperature
			if (data.temp)
			{
				var temp_bar = 'bg-blue', 
					temp_text = 'It\'s cool here',
					sys_temp = parseFloat(data.temp.value),
					tempthres1,
					tempthres2,
					tempthres3,
					sys_temp_box;
				
				if (data.temp.scale === 'c')
				{
					tempthres1 = 40; tempthres2 = 60; tempthres3 = 75;
				}
				else
				{
					tempthres1 = 104; tempthres2 = 140; tempthres3 = 167;
				}
				
				if (sys_temp > tempthres1 && sys_temp < tempthres2)
				{
					temp_bar = 'bg-green';
					temp_text = 'I\'m warm and fine';
				}
				else if (sys_temp >= tempthres2 && sys_temp < tempthres3)
				{
					temp_bar = 'bg-yellow';
					temp_text = 'Well, it\'s going to be hot here...';
				}
				else if (sys_temp > tempthres3)
				{
					temp_bar = 'bg-red';
					temp_text = 'HEY MAN! I\'m burning! Blow blow!';
				}
				
				sys_temp_box = parseFloat(sys_temp).toFixed(2)+'&deg;'+$('.app_data').data('dashboard-temp');
				//<div class="progress xs progress-striped active"><div class="progress-bar progress-bar-'+temp_bar+'" role="progressbar" aria-valuenow="'+parseInt(sys_temp)+'" aria-valuemin="0" aria-valuemax="100" style="width: '+parseInt(sys_temp)+'%"></div></div>';
				$('.sys-temp-box').addClass(temp_bar);
				$('.sys-temp-footer').html(temp_text+' <i class="fa fa-arrow-circle-right">');
				$('.widget-sys-temp').html(sys_temp_box);
			}
			else
			{
				$('.widget-sys-temp').html('N.a.');
				$('.sys-temp-footer').html('Temperature not available <i class="fa fa-arrow-circle-right">');
			}
			
			// Get profitability stats
			if (data.profits)
			{
				var btcPrice = (data.btc_rates && data.btc_rates.last) ? data.btc_rates.last : 0,
					btcEurPrice = (data.btc_rates && data.btc_rates.last_eur) ? data.btc_rates.last_eur : 0;

				if ( !$.fn.dataTable.isDataTable('#profit-table-details') )
				{
					// Initialize the profit datatable	
					$('#profit-table-details').dataTable({
						'bAutoWidth': false,
						'lengthChange': false,
						'paging': false,
						'searching': false,
						'info': false,
						'stateSave': true,
						'order': [[ 6, 'desc' ]],
						'aoColumnDefs': [{
							'aTargets': [ 3 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									return '<small>'+data.blocks+'</span>';
								}
								return data;
							},
						},{
							'aTargets': [ 4 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									return (data > 0) ? '<span class="badge bg-light-blue">'+convertHashrate(data)+'</span>' : '<span class="badge badge-muted">n.a.</span>';
								}
								return data;
							},
						},{
							'aTargets': [ 5 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									if ( full[3].coin === 'btc') {
										return (btcPrice && btcEurPrice) ? btcPrice + ' <i class="fa fa-dollar"></i> <span class="small">(' + btcEurPrice + ' <i class="fa fa-eur"></i>)' : data;
									} else
										return '<i class="fa fa-btc"></i> '+data;
								}
								return data;
							},
						},{
							'aTargets': [ 6 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									return '<i class="fa fa-btc"></i> <strong>'+data+'</strong>';
								}
								return data;
							},
						},{
							'aTargets': [ 7 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									return '<i class="fa fa-btc"></i> '+data;
								}
								return data;
							},
						},{
							'aTargets': [ 1 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									return '<small>'+data+'</small>';
								}
								return data;
							},
						},{
							'aTargets': [ 8 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									return '<small>'+data+'</small>';
								}
								return data;
							},
						},{
							'aTargets': [ 9 ],	
							'mRender': function ( data, type, full ) {
								if (type === 'display')
								{
									return '<small class="label label-primary">' + data.toUpperCase() + '</small>';
								}
								return data;
							},
						}],
					});	
				}

				// Update datatable profit function
				var updateProfitDataTable = function (data, currentProfitData) {
					// Add profit data

					$('#profit-table-details').dataTable().fnClearTable();
					var thisHash = (data.totals && data.totals.hashrate) ? data.totals.hashrate/1000 : 0;
					$('.profit_local_hashrate').html( ($('.profit_hashrate').val() > 0) ? $('.profit_hashrate').val()+$('.profit_unit').find('option:selected').data('profit-unit') : convertHashrate(thisHash));
					$('.profit_local_period').html($('.profit_period').find('option:selected').data('profit-period'));
					$('.profit_local_algo').html($('.profit_algo').data('profit-algo'));
						
					$.each( data.profits, function( coin, profit ) 
					{
						if ( $.fn.dataTable.isDataTable('#profit-table-details') && !profit.error )
						{
							// Add profit rows via datatable
							$('#profit-table-details').dataTable().fnAddData( [
								'<span class="label label-dark" data-toggle="tooltip" data-title="'+profit.coin.charAt(0).toUpperCase() + profit.coin.slice(1)+'">'+ profit.symbol.toUpperCase() +'</span>',
								profit.difficulty.toFixed(2),
								profit.reward.toFixed(2),
								{blocks: profit.blocks.toFixed(0), max: maxProfit.symbol, coin: profit.symbol},
								(profit.networkhashps) ? profit.networkhashps/1000 : 0,
								(profit.price) ? profit.price.toFixed(8) : 0,
								(currentProfitData.hash*profit.btc_profitability).toFixed(8),
								(profit.algo === 'scrypt') ? profit.btc_profitability.toFixed(8) : (profit.btc_profitability * 1000).toFixed(8),
								(profit.algo === 'scrypt') ? profit.coin_profitability.toFixed(8) : (profit.coin_profitability * 1000).toFixed(8),
								profit.algo
							] );
						}
						
					});

					maxProfit = _.max(data.profits, function (v) { return (currentProfitData.hash*v.btc_profitability); });
				};	

				var btc = _.filter(data.profits, function (v) { return v.symbol === 'btc'; });
				btc = (btc[0]) ? btc[0] : 0;
				
				var totalHash = (data.totals && data.totals.hashrate) ? data.totals.hashrate : 0,
					selHashrate = ($('.profit_hashrate').val() > 0) ? $('.profit_hashrate').val() : 0,
					selUnit = $('.profit_unit').val(),
					selPeriod = $('.profit_period').val(),
					currentProfitData = { hash: (selHashrate > 0) ? selUnit*selHashrate*selPeriod : totalHash/1000000 },
					maxProfit = _.max(data.profits, function (v) { return (currentProfitData.hash*v.btc_profitability); });

				currentProfitData.hash = (data.totals && data.totals.hashrate) ? data.totals.hashrate/1000000 : currentProfitData.hash;
				if (!selHashrate) selHashrate = 1000000;
				updateProfitDataTable(data, currentProfitData);

				// Recalculate value when user change input elements
				$('.profit_data').change(function (e) {
					selHashrate = ($('.profit_hashrate').val() > 0) ? $('.profit_hashrate').val() : 0;
					selUnit = $('.profit_unit').val();
					selPeriod = $('.profit_period').val();
					
					currentProfitData.hash = (selHashrate > 0) ? selUnit*selHashrate*selPeriod : totalHash/1000000;
					updateProfitDataTable(data, currentProfitData);
				});
				
				$('.profit_hashrate').change(function (e) {
					selHashrate = ($('.profit_hashrate').val() > 0) ? $('.profit_hashrate').val() : 0;
					selUnit = $('.profit_unit').val();
					selPeriod = $('.profit_period').val();

					currentProfitData.hash = (selHashrate > 0) ? selUnit*selHashrate*selPeriod : totalHash/1000000;
					updateProfitDataTable(data, currentProfitData);
				});
				
				$('.profit-table-details-error').html('');
			}
			else
			{
				$('.profit-table-details-error').html('<div class="alert alert-warning"><i class="fa fa-warning"></i><strong>No coins</strong> data available, please wait and try to refresh.</div>');
			}
						
			// Add Miner Uptime widget
			var uptime = convertMS(now - data.start_time*1000);

			var human_uptime = '';
			for (var ukey in uptime) {
				human_uptime = human_uptime + '' + uptime[ukey] + ukey + ' ';
			}
			
			$('.widget-uptime').html(human_uptime);
			$('.uptime-footer').html('Started on <strong>'+startdate.toUTCString()+'</strong>');
			
			// Add System Uptime
			var sysuptime = convertMS(data.sysuptime*1000),
				human_sysuptime = '',
				llabel;
				
			for (var uukey in sysuptime) {
				human_sysuptime = human_sysuptime + '' + sysuptime[uukey] + uukey + ' ';
			}
			
			$('.sysuptime').html('System has been up for: <strong>' + human_sysuptime + '</strong>');
		    
			// Add server load average knob graph
			$.each( data.sysload, function( lkey, lval ) 
			{
				if (lkey === 0) llabel = '1min';
				if (lkey === 1) llabel = '5min';
				if (lkey === 2) llabel = '15min';
			
				var loadBox = '<div class="col-xs-4 text-center" id="loadavg-'+ lkey +'" style="border-right: 1px solid #f4f4f4"><input type="text" class="loadstep-'+ lkey +'" data-width="60" data-height="60" /><div class="knob-label"><p>'+llabel+'</p></div></div>';

				$('#loadavg-'+lkey).remove();
				
				$('.sysload').append(loadBox);
				
				var lmax = 1, lcolor = 'rgb(71, 134, 81)';
				
				if (lval >= 0 && lval <= 1) { lmax = 1; lcolor = '#00a65a'; }
				else if (lval > 1 && lval <= 5) { lmax = 5; lcolor = '#f39c12'; }
				else if (lval > 5 && lval <= 10) { lmax = 10; lcolor = '#f56954'; }
				else { lmax = lval+(lval*10/100); lcolor = '#777777'; }
				
				$('.loadstep-'+lkey).knob({
			        'readOnly': true,
			        'fgColor':lcolor,
					'draw' : function () {
						$(this.i).val(this.cv);
					}
				});
					
				$('.loadstep-'+lkey)
					.trigger(
						'configure',
						{
							'min':0,
							'max':lmax,
							'step':0.01,
						}
				);

				$({value: 0}).animate({value: lval}, {
				    duration: 2000,
				    easing:'swing',
				    step: function() 
				    {
				        $('.loadstep-'+lkey).val(this.value).trigger('change');
				    }
				});
				    
				$('.loadstep-'+lkey).css('font-size','10px');
			});
			
		} // End if error/notrunning
		
		$('.overlay').hide();
		$('.loading-img').hide();
		$('.refresh-icon').removeClass('fa-spin');
	    
	}); // End get live stats
	
	/* Morris.js Charts */
	if (thisSection === 'dashboard') {
		// get Json data from stored_stats url (redis) and create the graphs
		$.getJSON(_baseUrl+'/app/api?command=history_stats&type=hourly', function( data ) 
		{		
			var dataMorris = Object.keys(data).map(function(key) { 
				data[key].timestamp = data[key].timestamp*1000; 
				data[key].hashrate = (data[key].hashrate/1000/1000).toFixed(2);
				data[key].pool_hashrate = (data[key].pool_hashrate/1000/1000).toFixed(2);									
				return data[key];
			});
		
			var redrawGraphs = function ()
			{
			    charts.areaHash.redraw();
			    charts.areaRej.redraw();
				    
			    return false;
			};
			
			var updateGraphs = function()
			{
				console.log(dataMorris);
			    charts.areaHash.setData(dataMorris);
			    charts.areaRej.setData(dataMorris);
				    
			    return false;
			};
			
			if (dataMorris.length && errorTriggered === false)
			{
					// Initialise new obj
					var charts = {
						areaHash: {},
						areaRej: {}
					};
					
					// Hashrate history graph
					charts.areaHash = new Morris.Area({
						element: 'hashrate-chart',
						resize: true,
						data: dataMorris,
						xkey: 'timestamp',
						ykeys: ['hashrate', 'pool_hashrate'],
						ymax: 'auto',
						postUnits: 'Mh/s',
						labels: ['Devices', 'Pool'],
						lineColors: ['#3c8dbc', '#00c0ef'],
						lineWidth: 2,
						pointSize: 3,
						hideHover: 'auto',
						behaveLikeLine: true
	
					});	
					
					// Rejected/Errors graph
					charts.areaRej = new Morris.Area({
						element: 'rehw-chart',
						resize: true,
						data: dataMorris,
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
				
				
				$(window).resize(function() {
					redrawGraphs();
				});
				
				$('.sidebar-toggle').click(function() { redrawGraphs(); });
			}
			else
			{
				$('.chart').css({'height': '100%', 'overflow': 'visible', 'margin-top': '10px'}).html('<div class="alert alert-warning"><i class="fa fa-warning"></i><b>Oops!</b> <small>No data collected, You need at least 5 minutes of data to see the chart.</small></div>');	
			}
			
			//$('.overlay').hide();
			//$('.loading-img').hide();
			
		}); //End get stored stats
	}
} // End function getStats()
