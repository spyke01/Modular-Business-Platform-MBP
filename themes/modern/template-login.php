<?php
$sidebarActive = ( $page->getTemplateVar( 'sidebar_active' ) == ACTIVE ) ? 1 : 0;

// Determine what color themes we are adding to the system
$bodyClass = '';

if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_backgroundPatternForLoginPage'] ) ) {
	$bodyClass = $mbp_config['ftsmbp_theme_modern_settings_backgroundPatternForLoginPage'] . ' ';
}
if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_bodyStyleThemeForLoginPage'] ) ) {
	$bodyClass .= $mbp_config['ftsmbp_theme_modern_settings_bodyStyleThemeForLoginPage'] . '-bodyStyle ';
}

// Handle CSS
$extraCSS = '';

if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_backgroundImageForLoginPage'] ) ) {
	$extraCSS .= '
			body { 
				background-image: url(' . $mbp_config['ftsmbp_theme_modern_settings_backgroundImageForLoginPage'] . ') !important;
			}';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?php echo $mbp_config['ftsmbp_site_name']; ?> - <?php $page->printTemplateVar( 'PageTitle' ); ?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="content-language" content="en-us" />
        <!--Stylesheets Begin-->
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/glyphicons.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/jui/timepicker/jquery-ui-timepicker.css" media="screen" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/animate.min.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/bootstrap-icon-picker/css/icon-picker.min.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/jquery-minicolors/jquery.minicolors.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/select2/dist/css/select2.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/jquery/uploadify/uploadify.css" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.1/fullcalendar.css" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.1/fullcalendar.print.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/main.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/login.css" />
		<?php $page->printStyles(); ?>
        <!--[if lt IE 7]>
        <style>
        </style>
        <![endif]-->
        <style>
            body {
                background-color: <?php echo $mbp_config['ftsmbp_theme_modern_settings_backgroundColorForLoginPage']; ?>;
            }

            div#login #login-box,
            div#login #login-box #login-box-header h1 {
                color: <?php echo $mbp_config['ftsmbp_theme_modern_settings_textColorForLoginPage']; ?>;
                text-shadow: 0 1px 0<?php echo $mbp_config['ftsmbp_theme_modern_settings_textShadowColorForLoginPage']; ?>;
            }

            <?php echo $extraCSS . $mbp_config['ftsmbp_theme_modern_settings_customCSSForLoginPage']; ?>
        </style>
        <!--Stylesheets End-->
        <script src="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body<?php if ( ! empty( $bodyClass ) ) {
		echo ' class="' . trim( $bodyClass ) . '"';
	} ?>>
        <div id="login">
            <div id="login-box-wrapper" class="bounceInDown animated">
                <div id="login-logo" class="slideInLeft animated"><img src="<?php echo $mbp_config['ftsmbp_logo']; ?>" alt="<?php echo $mbp_config['ftsmbp_site_name']; ?> Logo" /></div>
                <div id="login-top-shadow">
                </div>
                <div id="login-box">
                    <div id="login-box-header">
                        <h1><?php $page->printTemplateVar( 'PageTitle' ); ?></h1>
                    </div>
                    <div id="login-box-content">
						<?php $page->printTemplateVar( 'PageContent' ); ?>
                    </div>
                    <div id="login-box-footer">
                        <div id="login-tape"></div>
                    </div>
                </div>
                <div id="login-bottom-shadow">
                </div>
            </div>
        </div>
        <!--Javascripts Begin-->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/js/vendor/bootstrap.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="//code.jquery.com/jquery-migrate-1.2.1.js"></script>
        <script src="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/jui/jquery-ui.custom.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.1/fullcalendar.min.js"></script>
        <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
        <script src="//use.fontawesome.com/e30b1ab369.js"></script>

        <script src="<?php echo SITE_URL; ?>/node_modules/bootbox.js/bootbox.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/form/dist/jquery.form.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/jquery-minicolors/jquery.minicolors.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/select2/dist/js/select2.js"></script>

        <script type="text/javascript">
          SITE_URL = '<?php echo SITE_URL; ?>';
          indicatorImage = '<?php echo progressSpinnerHTML(); ?>';
        </script>
        <script src="<?php echo SITE_URL; ?>/javascripts/common.js"></script>
        <script src="<?php echo SITE_URL; ?>/javascripts/functions.js"></script>
		<?php $page->printScripts(); ?>
        <script type="text/javascript">
          $(document).ready(function() {
			  <?php $page->printTemplateVar( 'JQueryReadyScript' ); ?>
          });
        </script>
        <!--Javascripts End-->
    </body>
</html>
