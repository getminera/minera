<!DOCTYPE html>
<html lang="en" class="<?php echo $htmlTag ?>">
<head>
	<?php if (isset($refreshUrl) && $refreshUrl) : ?>
		<meta http-equiv="refresh" content="<?php echo $seconds+2 ?>;URL='<?php echo $refreshUrl ?>'" />  
	<?php endif; ?>
	<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	
	<title>Minera - Mining Dashboard</title>
	
	<link href="<?php echo base_url('favicon.ico') ?>" rel="icon">
	<link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/font-awesome.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/ionicons.min.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/datatables/dataTables.bootstrap.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/custom.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/morris.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/jQueryUI/jquery-ui-1.10.3.custom.min.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/AdminLTE.css') ?>" rel="stylesheet" />
</head>
