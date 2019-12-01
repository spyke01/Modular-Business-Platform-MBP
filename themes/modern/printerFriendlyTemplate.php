<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
    <head>
        <title><?php echo $mbp_config['ftsmbp_site_name']; ?> - <?php $page->printTemplateVar( 'PageTitle' ); ?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta http-equiv="content-language" content="en-us" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--Icons Begin-->
        <link rel="shortcut icon" href="<?php echo $mbp_config['ftsmbp_icon']; ?>" type="image/x-icon" />
        <link rel="apple-touch-icon" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/apple-touch-icon.png" />
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/apple-touch-icon-57x57.png" />
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/apple-touch-icon-72x72.png" />
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/apple-touch-icon-76x76.png" />
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/apple-touch-icon-114x114.png" />
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/apple-touch-icon-120x120.png" />
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/apple-touch-icon-144x144.png" />
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/apple-touch-icon-152x152.png" />
        <!--Icons End-->
        <!--Stylesheets Begin-->
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/glyphicons.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/jui/timepicker/jquery-ui-timepicker.css" media="screen" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/animate.min.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/bootstrap-icon-picker/css/icon-picker.min.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/bootstrap-treeview/dist/bootstrap-treeview.min.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/bootstro/bootstro.min.css" />
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/jquery-minicolors/jquery.minicolors.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/select2/dist/css/select2.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/smartmenus/dist/addons/bootstrap/jquery.smartmenus.bootstrap.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/jquery/uploadify/uploadify.css" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.1/fullcalendar.css" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.1/fullcalendar.print.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/javascripts/addons/file-manager/jquery.file.manager.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/main.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/rtl.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/themes.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/printerFriendly.css" />
		<?php $page->printStyles(); ?>
        <!--[if lt IE 7]>
        <style>
        </style>
        <![endif]-->
        <style>
            body {
                background-color: <?php echo $mbp_config['ftsmbp_theme_modern_settings_backgroundPattern']; ?>;
            }

            <?php echo $mbp_config['ftsmbp_theme_modern_settings_customCSS']; ?>
        </style>
        <!--Stylesheets End-->
        <script src="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body<?php if ( ! empty( $bodyClass ) ) {
		echo ' class="' . trim( $bodyClass ) . '"';
	} ?>>
        <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <header>
            <img src="<?php echo $mbp_config['ftsmbp_logo']; ?>" alt="<?php echo $mbp_config['ftsmbp_site_name']; ?> Logo" />
        </header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12<?php if ( ! empty( $contentClass ) ) {
					echo ' ' . trim( $contentClass );
				} ?>">
					<?php $page->printBreadCrumbs( 'ul', ' <span class="divider">/</span>', '', '', 'breadcrumb', 1 ); ?>
					<?php $page->printTemplateVar( 'PageContent' ); ?>
                </div><!-- /col-sm-10 -->
            </div> <!-- /row -->
            <footer>
				<?php echo parseForTagsFromArray( $mbp_config['ftsmbp_copyright'], getBuiltinTags() ); ?>
				<?php if ( $mbp_config['ftsmbp_show_powered_by'] == 1 ) { ?>
                    <span class="pull-right">Powered By: <a href="https://github.com/spyke01/Modular-Business-Platform-MBP">Modular Business Platform</a></span>

				<?php } ?>
            </footer>
        </div> <!-- /container-fluid -->
        <!--Javascripts Begin-->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/js/vendor/bootstrap.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="//code.jquery.com/jquery-migrate-1.2.1.js"></script>
        <script src="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/jui/jquery-ui.custom.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.1/fullcalendar.min.js"></script>
        <script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
        <script src="//use.fontawesome.com/e30b1ab369.js"></script>

        <script src="<?php echo SITE_URL; ?>/javascripts/jquery.uploadify.min.js"></script>

        <script src="<?php echo SITE_URL; ?>/node_modules/jquery-minicolors/jquery.minicolors.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/justgage/justgage.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/autosize/dist/autosize.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/bootbox.js/bootbox.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/bootstrap-treeview/dist/bootstrap-treeview.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/bootstro/bootstro.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/bootstrap-icon-picker/js/iconPicker.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/form/dist/jquery.form.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/jquery-jeditable/dist/jquery.jeditable.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/jQuery-Timepicker-Addon/dist/jquery-ui-timepicker-addon.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/jquery.pwstrength/dist/pwstrength-bootstrap.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/knockout/build/output/knockout-latest.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/nestedSortable/jquery.mjs.nestedSortable.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/raphael/raphael.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/select2/dist/js/select2.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/smartmenus/dist/jquery.smartmenus.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/smartmenus/dist/addons/bootstrap/jquery.smartmenus.bootstrap.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/tablesorter/jquery.tablesorter.min.js"></script>

        <script type="text/javascript">
          SITE_URL = '<?php echo SITE_URL; ?>';
          indicatorImage = '<?php echo progressSpinnerHTML(); ?>';
        </script>
        <script src="<?php echo SITE_URL; ?>/javascripts/common.js"></script>
        <script src="<?php echo SITE_URL; ?>/javascripts/functions.js"></script>
        <script src="<?php echo SITE_URL; ?>/javascripts/widgets.js"></script>
        <script src="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/js/main.js"></script>
		<?php $page->printScripts(); ?>
        <script type="text/javascript">
          $(document).ready(function() {
			  <?php $page->printTemplateVar( 'JQueryReadyScript' ); ?>
          });
        </script>
		<?php echo $mbp_config['ftsmbp_analytics_code']; ?>
        <!--Javascripts End-->
    </body>
</html>
