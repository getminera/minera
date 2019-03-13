<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side ">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            PirateCash
            <small>wallet</small>
        </h1>

        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("app/dashboard") ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        </ol>
    </section>
    <?php
    $getinfo = $this->rpc->getinfo();
    $mn = $this->rpc->masternode('count');
    $newaddress = $this->rpc->getaccountaddress('staking');
    $listaddress = $this->rpc->getaddressesbyaccount('staking');
    $address = isset($listaddress[0]) ? $listaddress[0] : $newaddress;
    $tx = $this->rpc->listtransactions('staking', 10);
    $getstakinginfo = $this->rpc->getstakinginfo();
    $unit = 'minutes';
    $interval = $getstakinginfo['expectedtime'] / 60;
    $hours = $interval / 60;
    $days = $hours / 24;
    if ($hours > 1) {
        $interval = $hours;
        $unit = "hours";
    }
    if ($days > 1) {
        $interval = $days;
        $unit = "days";
    }
    $pending = 0;
    $txTable = "";
    $index = count($tx);
    while ($index) {
        $txLine = $tx[--$index];
        $amount = $txLine['amount'];
        if ($txLine['confirmations'] == 0) {
            $pending += $amount;
        }
        if (isset($txLine['generated'])) {
            if ($txLine['generated']) {
                $amount = 0;
                $txinfo = $this->rpc->gettransaction($txLine['txid']);
                foreach ($txinfo['vin'] as $vin) {
                    $txvin = $this->rpc->gettransaction($vin['txid']);
                    $detail = $txvin['vout'][$vin['vout']];
                    $validate = $this->rpc->validateaddress($detail['scriptPubKey']['addresses'][0]);
                    if ($validate['ismine']) {
                        $amount -= $detail['value'];
                    }
                }
                foreach ($txinfo['vout'] as $vout) {
                    if (isset($vout['scriptPubKey']['addresses'])) {
                        $validate = $this->rpc->validateaddress($vout['scriptPubKey']['addresses'][0]);
                        if ($validate['ismine']) {
                            $amount += $vout['value'];
                        }
                    }
                }
            }
        }

        $txTime = date('o-m-j H:i', $txLine['time']);
        $txTable .= "<tr><td>" . $txTime . "</td><td>" . $txLine['address'] . "</td><td>" . sprintf('%.8f', $amount) . " PIRATE</td></tr>";
    }
    ?>

    <!-- Top section -->
    <section class="col-md-12">

        <div class="row">

            <section class="right-section col-xs-12 col-md-6">

                <!-- Panels box -->
                <div class="box box-primary" id="top-bar-box">
                    <div class="box-header">
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                        </div><!-- /. tools -->
                        <i class="fa fa-map-signs"></i>

                        <h3 class="box-title">Dashboard panels</h3>
                    </div>

                    <div class="box-body">
                        <!-- Overview -->
                        <div class="form-group">
                            <label>Show general overview of wallet.</label>
                            <table class="box-panels">
                                <tr>
                                    <td><strong>Available:</strong></td>
                                    <td><strong><?= sprintf('%.8f', $getinfo['balance']); ?> PIRATE</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Stake:</strong></td>
                                    <td><strong><?= sprintf('%.8f', $getinfo['stake']); ?> PIRATE</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Pending:</strong></td>
                                    <td><strong><?= sprintf('%.8f', $pending); ?> PIRATE</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Total:</strong></td>
                                    <td><strong><?= sprintf('%.8f', $getinfo['balance'] + $getinfo['stake'] + $pending); ?> PIRATE</strong></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

            </section><!-- End right section -->

            <section class="right-section col-xs-12 col-md-6">

                <!-- Panels box -->
                <div class="box box-primary" id="top-bar-box">
                    <div class="box-header">
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                        </div><!-- /. tools -->
                        <i class="fa fa-map-signs"></i>

                        <h3 class="box-title">Network info</h3>
                    </div>

                    <div class="box-body">
                        <!-- Overview -->
                        <div class="form-group">
                            <label>Show general overview of network.</label>
                            <table class="box-panels">
                                <tr>
                                    <td><strong>Client version:</strong></td>
                                    <td><strong><?= $getinfo['version']; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Number of connections:</strong></td>
                                    <td><strong><?= $getinfo['connections']; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Number of masternodes:</strong></td>
                                    <td><strong><?= $mn; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Current number of blocks:</strong></td>
                                    <td><strong><?= $getinfo['blocks']; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Testnet:</strong></td>
                                    <td><strong><font color=<?= $getinfo['testnet'] ? "'red'>YES" : "'blue'>NO"; ?></font></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

            </section><!-- End right section -->

            <section class="right-section col-xs-12 col-md-6">

                <!-- Panels box -->
                <div class="box box-primary" id="top-bar-box">
                    <div class="box-header">
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                        </div><!-- /. tools -->
                        <i class="fa fa-map-signs"></i>

                        <h3 class="box-title">Recive</h3>
                    </div>

                    <div class="box-body" id="address">
                        <!-- Overview -->
                        <div class="form-group">
                            <label>These are your PirateCash addresses.</label>
                            <table class="box-panels">
                                <tr>
                                    <td><strong>Your staking address:</strong></td>
                                    <td><strong><font color="green"><?= $address; ?></font></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="right-section col-xs-12 col-md-6">

                <!-- Panels box -->
                <div class="box box-primary" id="top-bar-box">
                    <div class="box-header">
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                        </div><!-- /. tools -->
                        <i class="fa fa-map-signs"></i>

                        <h3 class="box-title">Staking info</h3>
                    </div>

                    <div class="box-body" id="address">
                        <!-- Overview -->
                        <div class="form-group">
                            <table class="box-panels">
                                <tr>
                                    <td><strong>Enabled:</strong></td>
                                    <td><strong><font color=<?= $getstakinginfo['enabled'] ? '"green">OK' : '"red">NO'; ?></font></strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Staking:</strong></td>
                                    <td><strong><font color=<?= $getstakinginfo['staking'] ? '"green">OK' : '"red">NO'; ?></font></strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Expected time to earn reward is</strong></td>
                                    <td><strong><font color="green"><?= sprintf('%.1f', $interval) . " " . $unit; ?></font></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="right-section col-xs-12 col-md-6">

                <!-- Panels box -->
                <div class="box box-primary" id="top-bar-box">
                    <div class="box-header">
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                        </div><!-- /. tools -->
                        <i class="fa fa-map-signs"></i>

                        <h3 class="box-title">Recent transactions</h3>
                    </div>

                    <div class="box-body" id="address">
                        <!-- Overview -->
                        <div class="form-group">
                            <table class="box-panels">
                                <?= $txTable; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

        </div><!-- End row -->
    </section>
</aside>