<body>
    <div class="app_data"
         data-page="login"
         ></div>
    <header class="header noheader" data-this-section="<?php echo $sectionPage ?>"></header>
    <!-- Automatic element centering using js -->
    <div class="center">
        <div class="lockscreen-cover"></div>
        <div class="toptime headline text-center" id="time"></div>

        <!-- User name -->
        <div class="lockscreen-name">Hello Pirate</div>

        <!-- START LOCK SCREEN ITEM -->
        <div class="lockscreen-item">
            <!-- lockscreen image -->
            <div class="lockscreen-image">
                <img src="<?php echo base_url("assets/img/avatar.png") ?>" alt="user image"/>
            </div>
            <!-- /.lockscreen-image -->
            <form action="<?php echo site_url("app/login") ?>" method="post">
                <!-- lockscreen credentials (contains the form) -->
                <div class="lockscreen-credentials">

                    <div class="input-group">

                        <input type="password" name="password" class="pass-form form-control" placeholder="password" />
                        <div class="input-group-btn">
                            <button class="btn btn-flat"><i class="fa fa-arrow-right text-muted"></i></button>
                        </div>

                    </div>
                </div><!-- /.lockscreen credentials -->

            </form>
        </div><!-- /.lockscreen-item -->

        <div class="lockscreen-link">
            Welcome to RaspiNode
            <div class="mt20">
                <p class="terminal-font">System: <?php echo gethostname() ?></p>
                <p class="terminal-font">Ip Address: <strong><?php echo $_SERVER['SERVER_ADDR'] ?></strong></p>
            </div>
        </div>

        <div class="lockscreen-link">
            <p class="terminal-font"><strong><?php if ($isOnline) : ?><i class="fa fa-circle text-success"></i> Online<?php else: ?><i class="fa fa-circle text-muted"></i> Offline<?php endif; ?></strong> | Raspinode Version: <?php echo $raspinode_version ?></p>
        </div>

    </div><!-- /.center -->