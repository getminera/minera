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
	
	<title>Minera - <?php echo $pageTitle ?></title>
	
	<link href="<?php echo base_url('favicon.ico') ?>" rel="icon">
	<link href="<?php echo base_url('assets/vendor/font-awesome/css/font-awesome.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/vendor/ionicons/css/ionicons.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/vendor/bootstrap/dist/css/bootstrap.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/vendor/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/vendor/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/vendor/morrisjs/morris.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/vendor/ion.rangeSlider/css/ion.rangeSlider.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/vendor/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css') ?>" rel="stylesheet" />	
	<link href="<?php echo base_url('assets/vendor/blueimp-file-upload/css/jquery.fileupload.css') ?>" rel="stylesheet" />	
	<link href="<?php echo base_url('assets/vendor/blueimp-file-upload/css/jquery.fileupload-ui.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/AdminLTE.css') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/custom.css') ?>" rel="stylesheet" />
</head>
