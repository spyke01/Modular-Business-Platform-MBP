<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<title>Paden Clayton Installer - <?php $page->printTemplateVar('PageTitle');  ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta http-equiv="content-language" content="en-us" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--Icons Begin-->
			<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<!--Icons End-->
		<!--Stylesheets Begin-->
			<link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/bootstrap.min.css" />
			<link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/bootstrap-theme.min.css" />
			<link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/glyphicons.css" />
			<link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" />
			<link rel="stylesheet" href="//cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" />
			<link rel="stylesheet" href="themes/modern/jui/css/jquery-ui.css" media="screen" />
			<link rel="stylesheet" href="themes/modern/jui/jquery-ui.custom.css" media="screen" />
			<link rel="stylesheet" href="themes/modern/animate.min.css" />
			<link rel="stylesheet" href="themes/modern/css/main.css" />
			<link rel="stylesheet" href="themes/installer/main.css" />
			<!--[if lt IE 7]>
				<style>
				</style>
			<![endif]-->
		<!--Stylesheets End-->
		<script src="themes/modern/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
	</head>
	<body>
		<!--[if lt IE 7]>
			<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->		
		<div class="container">
			<div id="header">
				<img src="themes/installer/images/logo.png" alt="Paden Clayton" />
			</div>	
			<div class="installation-container">
				<div class="row">
					<div class="col-xs-12 col-sm-3 col-md-3"> 
						<ul class="nav nav-tabs nav-stacked sidebar">
							<li class="step <?php if ($actual_step == 1) { echo ' active'; } ?>"><span><span class="badge">1</span> Introduction</span></li>
							<li class="step <?php if ($actual_step == 2) { echo ' active'; } ?>"><span><span class="badge">2</span> Database Connection</span></li>
							<li class="step <?php if ($actual_step == 3) { echo ' active'; } ?>"><span><span class="badge">3</span> Create database Tables</span></li>
							<li class="step <?php if ($actual_step == 4) { echo ' active'; } ?>"><span><span class="badge">4</span> Create Admin Account</span></li>
							<li class="step <?php if ($actual_step == 5) { echo ' active'; } ?>"><span><span class="badge">5</span> Finish</span></li>
						</ul>
					</div>
					<div class="col-xs-12 col-sm-9 col-md-9" id="content">
						<?php $page->printTemplateVar('PageContent'); ?>
					</div>
				</div> <!-- /row -->
			</div> <!-- /installation-container -->	
			<footer>
				Copyright &copy; 2009 - <?php echo date('Y'); ?> Paden Clayton
				<span class="pull-right">Powered By: <a href="http://www.fasttracksites.com">Paden Clayton Installer</a></span>
			</footer>
		</div> <!-- /container -->
		<!--Javascripts Begin-->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/js/vendor/bootstrap.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.js"></script>
		<script src="themes/modern/jui/jquery-ui.custom.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
		<script src="//use.fontawesome.com/e30b1ab369.js"></script>

		<script src="<?php echo SITE_URL; ?>/node_modules/bootbox.js/bootbox.min.js"></script>
		<script src="<?php echo SITE_URL; ?>/node_modules/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>
		<script src="<?php echo SITE_URL; ?>/node_modules/form/dist/jquery.form.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
		<script src="<?php echo SITE_URL; ?>/node_modules/jquery.pwstrength/dist/pwstrength-bootstrap.min.js"></script>

		<script type="text/javascript">
			$(document).ready(function(){
				<?php $page->printTemplateVar('JQueryReadyScript'); ?>
			});
		</script>
		<!--Javascripts End-->
	</body>
</html>
