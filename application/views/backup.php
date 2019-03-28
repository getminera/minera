<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side ">                	
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Wallet
            <small>Backup</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("app/dashboard") ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        </ol>
    </section>

    <!-- Save toolbox -->
    <div class="save-toolbox">
        <ul>
            <li><a href="#" class="toggle-save-toolbox"><i class="fa fa-close"></i></a></li>
            <li>
                <button type="submit" class="btn btn-lg btn-primary save-minera-settings" name="save" value="1"><i class="fa fa-floppy-o"></i> Save</button>
            </li>
            <li>
                <button type="submit" class="btn btn-lg btn-danger save-minera-settings-restart" name="save_restart" value="1"><i class="fa fa-repeat"></i> Save & Restart Miner</button>
            </li>
        </ul>
    </div>

    <!-- Main content -->
    <section class="content">
        <form action="#" method="post" role="form" id="wallet_backup" enctype="multipart/form-data">
        <!-- Import/Export box -->
        <div class="box box-primary" id="importexport-box">
            <div class="box-header">
                <!-- tools box -->
                <div class="pull-right box-tools">
                    <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                </div><!-- /. tools -->
                <i class="fa fa-code-fork"></i>

                <h3 class="box-title">Backup/Restore wallet</h3>
            </div>

            <div class="box-body">
                <p>This allows you to make a backup your keys from PirateCash (this will allow you to restore your balance in case of problems with the device/flash).</p>

                <div class="import-export-box margin-bottom">
                    <span class="btn btn-info backup-action" data-toggle="tooltip" data-title="This make backup file from your RaspiNode (piratecashd)">
                        <i class="glyphicon glyphicon-upload"></i>
                        Backup wallet
                    </span>
                    <span class="btn btn-danger fileinput-button" data-toggle="tooltip" data-title="This upload new wallet.dat file into RaspiNode (piratecashd)">
                        <i class="glyphicon glyphicon-download"></i>
                        Restore wallet
                        <input class="import-file" type="file" name="import_wallet_dat">
                    </span> 
                </div>

            </div>
            <div class="box-footer">
                <h6><em>*</em> Loading a backup file from your RaspiNode (piratecashd).</h6>
            </div>
        </div>
        </form>

    </section><!-- /.left col -->

</aside><!-- /.right-side -->
