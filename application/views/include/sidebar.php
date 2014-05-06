    <body class="skin-black"<?php if ($appScript) : ?> onload="getStats(false);"<?php endif; ?>>

        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="<?php echo site_url() ?>" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                Minera
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
				<div class="navbar-right">
					<ul class="nav navbar-nav">
						<!-- BTC/USD rates -->
						<li class="messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-btc"></i> price: <?php echo $btc->last ?> <i class="fa fa-dollar"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Data from Bitstamp</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
                                        <li>
                                            <a href="#">
                                            	<div class="pull-left" style="padding-left:15px;">
                                                    <i class="fa fa-archive"></i>
                                                </div>
                                                <h4>
                                                    <?php echo $btc->volume ?>
                                                    <small><i class="fa fa-clock-o"></i> <?php echo date("H:i", $btc->timestamp) ?></small>
                                                </h4>
                                                <p>Volume</p>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                            	<div class="pull-left" style="padding-left:15px;">
                                                    <i class="fa fa-arrow-circle-up"></i>
                                                </div>
                                                <h4>
                                                    <?php echo $btc->high ?>
                                                    <small><i class="fa fa-clock-o"></i> <?php echo date("H:i", $btc->timestamp) ?></small>
                                                </h4>
                                                <p>High</p>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                            	<div class="pull-left" style="padding-left:15px;">
                                                    <i class="fa fa-arrow-circle-down"></i>
                                                </div>
                                                <h4>
                                                    <?php echo $btc->low ?>
                                                    <small><i class="fa fa-clock-o"></i> <?php echo date("H:i", $btc->timestamp) ?></small>
                                                </h4>
                                                <p>Low</p>
                                            </a>
                                        </li>
                                    </ul>                                </li>
                                <li class="footer"><a href="https://www.bitstamp.net">Go to Bitstamp</a></li>
                            </ul>
                        </li>
                        
                        <!-- LTC/BTC Rates -->
						<li class="messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                LTC price: <?php echo $ltc->return->markets->LTC->lasttradeprice ?> <i class="fa fa-btc"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Data from Cryptsy</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
                                        <li>
                                            <a href="#">
                                            	<div class="pull-left" style="padding-left:15px;">
                                                    <i class="fa fa-archive"></i>
                                                </div>
                                                <h4>
                                                    <?php echo $ltc->return->markets->LTC->volume ?>
                                                    <small><i class="fa fa-clock-o"></i> <?php echo date("H:i", strtotime($ltc->return->markets->LTC->lasttradetime." EST")) ?></small>
                                                </h4>
                                                <p>Volume</p>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                            	<div class="pull-left" style="padding-left:15px;">
                                                    <i class="fa fa-exchange"></i>
                                                </div>
                                                <h4>
                                                    <?php echo $ltc->return->markets->LTC->lasttradetime." EST" ?>
                                                    <small><i class="fa fa-clock-o"></i> <?php echo date("H:i", strtotime($ltc->return->markets->LTC->lasttradetime." EST")) ?></small>
                                                </h4>
                                                <p>Last trade time</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="https://www.cryptsy.com/users/register?refid=243592">Register at Cryptsy</a></li>
                            </ul>
                        </li>
                        
						<!-- DOGE/BTC Rates -->
						<li class="messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                DOGE price: <?php echo $doge->return->markets->DOGE->lasttradeprice ?> <i class="fa fa-btc"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Data from Cryptsy</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
                                        <li>
                                            <a href="#">
                                            	<div class="pull-left" style="padding-left:15px;">
                                                    <i class="fa fa-archive"></i>
                                                </div>
                                                <h4>
                                                    <?php echo $doge->return->markets->DOGE->volume ?>
                                                    <small><i class="fa fa-clock-o"></i> <?php echo date("H:i", strtotime($doge->return->markets->DOGE->lasttradetime." EST")) ?></small>
                                                </h4>
                                                <p>Volume</p>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                            	<div class="pull-left" style="padding-left:15px;">
                                                    <i class="fa fa-exchange"></i>
                                                </div>
                                                <h4>
                                                    <?php echo $doge->return->markets->DOGE->lasttradetime." EST" ?>
                                                    <small><i class="fa fa-clock-o"></i> <?php echo date("H:i", strtotime($doge->return->markets->DOGE->lasttradetime." EST")) ?></small>
                                                </h4>
                                                <p>Last trade time</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="https://www.cryptsy.com/users/register?refid=243592">Register at Cryptsy</a></li>
                            </ul>
                        </li>
                        
					    <!-- Donate/Help dropdown -->
					    <li class="dropdown user user-menu">
					        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
					            <i class="glyphicon glyphicon-gift"></i>
					            <span>Help <i class="caret"></i></span>
					        </a>
					        <ul class="dropdown-menu">
					            <!-- User image -->
					            <li class="user-header bg-dark-grey">
					            	<p><i class="glyphicon glyphicon-heart"></i></p>
					                <p>
					                    <small>Made with heart</small>
					                    Minera is a free and open source software
					                    <small>Please help Minera: spread it, share, donate</small>
					                </p>
					                <div style="margin:22px;">
										<a href="https://www.facebook.com/sharer/sharer.php?u=https://github.com/michelem09/minera" target="_blank"><button class="btn bg-light-blue btn-circle"><i class="fa fa-facebook"></i></button></a>
										<a href="https://twitter.com/home?status=Try%20Minera%20for%20your%20%23bitcoin%20mining%20monitor%20https://github.com/michelem09/minera" target="_blank"><button class="btn bg-aqua btn-circle"><i class="fa fa-twitter"></i></button></a>
										<a href="https://plus.google.com/share?url=https://github.com/michelem09/minera" target="_blank"><button class="btn bg-red btn-circle"><i class="fa fa-google-plus"></i></button></a>
									</div>
					            </li>
					            <!-- Menu Body -->
					            <li class="user-body">
					                <div class="col-xs-12 text-center">
										<a href="https://github.com/michelem09/minera/issues">Problems?</a>
									</div>
					            </li>
					            <!-- Menu Footer-->
					            <li class="user-footer">
					                <div class="pull-left">
					                    <a href="https://github.com/michelem09/minera" class="btn btn-default btn-flat">Github</a>
					                </div>
					                <div class="pull-right">
					                    <a href="http://twitter.com/michelem" class="btn btn-default btn-flat">Twitter</a>
					                </div>
					            </li>
					        </ul>
					    </li>
					</ul>
				</div>
			</nav>
        </header>
        
        <!-- Main content -->
        <div class="wrapper row-offcanvas row-offcanvas-left">
			
			<!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar main panel -->
                    <div class="user-panel">
                        <div class="pull-left info">
                            <p>Hello, your miner is</p>
							<?php if ($isOnline) : ?>
	                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
	                        <?php else: ?>
	                            <a href="#"><i class="fa fa-circle text-danger"></i> Offline</a>
							<?php endif; ?>
                        </div>
                    </div>

                    <!-- sidebar menu -->
                    <ul class="sidebar-menu">
                        <li>
                            <a href="<?php echo site_url("app/dashboard") ?>">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-desktop"></i>
                                <span>Miner</span>
                                <i class="fa pull-right fa-angle-left"></i>
                            </a>
                            <ul class="treeview-menu" style="display: none;">
                                <li><a href="<?php echo site_url("app/settings") ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Settings</a></li>

                                <li><a href="<?php echo site_url("app/start_miner") ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Start miner</a></li>
                                <li><a href="<?php echo site_url("app/stop_miner") ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Stop miner</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-rocket"></i>
                                <span>System</span>
                                <i class="fa pull-right fa-angle-left"></i>
                            </a>
                            <ul class="treeview-menu" style="display: none;">
                                <li><a href="<?php echo site_url("app/shutdown") ?>" style="margin-left: 10px;" id="opener"><i class="fa fa-angle-double-right"></i> Shutdown</a></li>
                                <li><a href="<?php echo site_url("app/reboot") ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> Reboot</a></li>
                            </ul>
                        </li>
                    </ul>
                </section>
                
                <!-- /.sidebar -->
            </aside>
            
