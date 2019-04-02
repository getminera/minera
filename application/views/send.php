<?php
$getinfo = $this->rpc->getinfo();
?>
<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side ">                	
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Send to
            <small>address</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("app/dashboard") ?>"><i class="fa fa-dashboard"></i> Send to address</a></li>
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
        <form action="#" method="post" role="form" id="wallet_send" enctype="multipart/form-data">
            <!-- Import/Export box -->
            <div class="box box-primary" id="importexport-box">
                <div class="box-header">
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /. tools -->
                    <i class="fa fa-money"></i>

                    <h3 class="box-title"><strong>Balance:</strong> <?= sprintf('%.8f', $getinfo['balance']); ?> PIRATE</h3>
                </div>

                <div class="box-body">

                    <div class="import-export-box margin-bottom">                    
                        <div class="box-body">
                            Pay to:
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user-secret"></i></span>
                                <input type="address" class="form-control" id="address" name="send_address" placeholder="Enter PirateCash address" autocomplete="off">
                            </div>

                            <div class="input-group mt10">
                                <span class="input-group-addon"><i class="fa fa-calculator"></i></span>
                                <input type="amount" class="form-control" name="send_amount" placeholder="Amount:" autocomplete="off">
                            </div>
                        </div>
                        <div class="box-footer">
                            <span class="btn btn-info send-action" data-toggle="tooltip" data-title="Confirm the send action">
                                <i class="glyphicon glyphicon-upload"></i>
                                Send
                            </span>
                        </div>
                    </div>

                </div>
                <div class="box-footer">
                    <h6><em>*</em> Send coins to the PirateCash address.</h6>
                </div>
            </div>
        </form>

    </section><!-- /.left col -->

</aside><!-- /.right-side -->
