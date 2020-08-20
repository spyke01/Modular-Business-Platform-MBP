<?php
$sidebarActive = ( $page->getTemplateVar( 'sidebar_active' ) == ACTIVE ) ? 1 : 0;
$contentClass  = ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_contentColorScheme'] ) ) ? ' ' . $mbp_config['ftsmbp_theme_modern_settings_contentColorScheme'] : '';

// Determine what color themes we are adding to the system
$bodyClass = $menuClass = $menuLogoClass = $sidebarClass = $sidebarTitlesClass = '';

if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_backgroundPattern'] ) ) {
	$bodyClass = $mbp_config['ftsmbp_theme_modern_settings_backgroundPattern'] . ' ';
}
if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_menuColorScheme'] ) ) {
	$bodyClass .= $mbp_config['ftsmbp_theme_modern_settings_menuColorScheme'] . '-mn ';
}
if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_menuLogoColorScheme'] ) ) {
	$bodyClass .= $mbp_config['ftsmbp_theme_modern_settings_menuLogoColorScheme'] . '-mn-brand ';
}
if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_sidebarColorScheme'] ) ) {
	$bodyClass .= $mbp_config['ftsmbp_theme_modern_settings_sidebarColorScheme'] . '-mm ';
	$bodyClass .= $mbp_config['ftsmbp_theme_modern_settings_sidebarColorScheme'] . '-mm-bg ';
}
if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_sidebarTitlesColorScheme'] ) ) {
	$bodyClass .= $mbp_config['ftsmbp_theme_modern_settings_sidebarTitlesColorScheme'] . '-mm-titles';
}
if ( ! $sidebarActive ) {
	$bodyClass .= ' no-mainMenu';
}

// Handle CSS
$extraCSS = 'body { background-color: ' . $mbp_config['ftsmbp_theme_modern_settings_backgroundColor'] . '; }';

if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_backgroundImage'] ) ) {
	$extraCSS .= '
			body { 
				background-image: url(' . $mbp_config['ftsmbp_theme_modern_settings_backgroundImage'] . ') !important;
			}';
}

if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_textColorForSidebarLinks'] ) ) {
	$extraCSS .= '
			#main-menu .navigation a, 
			#main-menu .mmc-dropdown-open-ul a { 
				color: ' . $mbp_config['ftsmbp_theme_modern_settings_textColorForSidebarLinks'] . ' !important;
			}';
}
if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_textColorForSidebarLinksHover'] ) ) {
	$extraCSS .= '
			#main-menu .navigation a:hover, 
			#main-menu .mmc-dropdown-open-ul a:hover { 
				color: ' . $mbp_config['ftsmbp_theme_modern_settings_textColorForSidebarLinksHover'] . ' !important;
			}';
}
if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_textColorForTabLinks'] ) ) {
	$extraCSS .= '
			.nav-pills > li > a { 
				color: ' . $mbp_config['ftsmbp_theme_modern_settings_textColorForTabLinks'] . ' !important;
			}	';
}
if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_textColorForTabLinksHover'] ) ) {
	$extraCSS .= '
			.nav-pills > li > a:hover { 
				color: ' . $mbp_config['ftsmbp_theme_modern_settings_textColorForTabLinksHover'] . ' !important;
			}';
}
if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_textColorForActiveTabLink'] ) ) {
	$extraCSS .= '
			.nav-pills > li.active > a { 
				color: ' . $mbp_config['ftsmbp_theme_modern_settings_textColorForActiveTabLink'] . ' !important;
			}';
}
if ( ! empty( $mbp_config['ftsmbp_theme_modern_settings_textColorForActiveTabLinkHover'] ) ) {
	$extraCSS .= '
			.nav-pills > li.active > a:focus, 
			.nav-pills > li.active > a:hover { 
				color: ' . $mbp_config['ftsmbp_theme_modern_settings_textColorForActiveTabLinkHover'] . ' !important;
			}	';
}

$extraCSS .= $mbp_config['ftsmbp_theme_modern_settings_customCSS'];

// This theme uses blocks for widgets which add extra spacing so we handle them a bit differently
$widgets                        = [];
$widgets['wa-leftColAboveMenu'] = displayWidgetsByArea( 'wa-leftColAboveMenu' );
$widgets['wa-leftCol']          = displayWidgetsByArea( 'wa-leftCol' );
?>
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
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/jquery-minicolors/jquery.minicolors.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/select2/dist/css/select2.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/node_modules/smartmenus/dist/addons/bootstrap/jquery.smartmenus.bootstrap.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/jquery/uploadify/uploadifive.css" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.1/fullcalendar.css" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.1/fullcalendar.print.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/javascripts/addons/file-manager/jquery.file.manager.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/main.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/rtl.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/themes.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/widgets.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/css/widgets.min.css" />
		<?php $page->printStyles(); ?>
        <!--[if lt IE 7]>
        <style>
        </style>
        <![endif]-->
        <style>
            <?php echo $extraCSS; ?>
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
        <div id="main-wrapper">
            <div id="main-navbar" class="navbar">
                <!-- Main menu toggle -->
                <button type="button" id="main-menu-toggle"><i class="navbar-icon fa fa-bars icon"></i><span class="hide-menu-text">HIDE MENU</span></button>

                <div class="navbar-inner">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#"><img src="<?php echo $mbp_config['ftsmbp_logo']; ?>" alt="<?php echo $mbp_config['ftsmbp_site_name']; ?> Logo" /></a>

                        <!-- Main menu toggle -->
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse"><i class="navbar-icon fa fa-bars"></i>
                    </div> <!-- / .navbar-header -->

                    <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
                        <div>
							<?php $page->printMenu( 'top', 'ul', '', '', '', 'nav navbar-nav', '', 0, 1 ); ?>

							<?php if ( isset( $_SESSION['userid'] ) ) { ?>
                                <div class="right clearfix">
                                    <ul class="nav navbar-nav pull-right right-navbar-nav">

										<?php
										$additionalTopRightNavItems = apply_filters( 'additional_top_right_nav_items', '' );

										if ( ! empty( $additionalTopRightNavItems ) ) {
											echo "<li class=\"dropdown\">{$additionalTopRightNavItems}</li>";
										}
										?>

                                        <li class="nav-icon-btn nav-icon-btn-danger dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">

												<?php $unreadNotificationsCount = getUnreadUserNotificationsCount( $_SESSION['userid'] ); ?>

												<?php if ( $unreadNotificationsCount > 0 ) { ?>
                                                    <span class="label notifications-count"><?php echo $unreadNotificationsCount; ?></span>
												<?php } ?>

                                                <i class="nav-icon fa fa-bullhorn"></i>
                                                <span class="small-screen-text">Notifications</span>
                                            </a>

                                            <!-- NOTIFICATIONS -->

											<?php $notifications = getUserNotifications( $_SESSION['userid'], 'unread' ); ?>

                                            <div class="dropdown-menu widget-notifications no-padding" style="width: 300px">

                                                <div class="notifications actions">
                                                    Notification
													<?php if ( $unreadNotificationsCount > 0 ) { ?>
                                                        <a class="mark-all-read" href="<?php echo SITE_URL . '/ajax.php?action=markAllUserNotificationsAsRead'; ?>">
                                                            <i class="zmdi zmdi-check-all"></i>
                                                        </a>
													<?php } ?>
                                                </div>

                                                <div class="notifications-list" id="main-navbar-notifications">

													<?php
													foreach ( $notifications as $notification ) {

														$default = returnUserNotificationTypeText( $notification['type'] );

														$notifArr = [
															'title'     => isset( $default['title'] ) ? strtoupper( $default['title'] ) : '',
															'icon'      => ! empty( $notification['icon'] ) ? $notification['icon'] : $default['icon'],
															'textClass' => isset( $default['colorClass'] ) ? 'text-' . $default['colorClass'] : '',
															'bgClass'   => isset( $default['colorClass'] ) ? 'bg-' . $default['colorClass'] : '',
															'message'   => ! empty( $notification['message'] ) ? $notification['message'] : '',
															'created'   => ! empty( $notification['created'] ) ? get_time_diff( current_time( 'timestamp' ), strtotime( $notification['created'] ) ) : '',
															'link'      => ! empty( $notification['link'] ) ? $notification['link'] : '',
														];
														?>

                                                        <div id="notification-item-<?php echo $notification['id']; ?>" class="notification">

															<?php if ( ! empty( $notifArr['link'] ) ) { ?>
                                                            <a href="<?php echo $notifArr['link']; ?>">
																<?php } ?>

                                                                <div class="notification-title <?php echo $notifArr['textClass']; ?>"><?php echo $notifArr['title']; ?></div>
                                                                <div class="notification-description"><?php echo $notifArr['message']; ?></div>
                                                                <div class="notification-ago"><?php echo $notifArr['created']; ?></div>
                                                                <div class="notification-icon <?php echo $notifArr['icon']; ?> <?php echo $notifArr['bgClass']; ?>"></div>

																<?php if ( ! empty( $notifArr['link'] ) ) { ?>
                                                            </a>
														<?php } ?>
                                                        </div> <!-- / .notification -->

													<?php } ?>

                                                </div> <!-- / .notifications-list -->
                                                <a href="<?php echo il( $menuvar['NOTIFICATIONS'] . '&id=' . $_SESSION['userid'] ); ?>" class="notifications-link">MORE NOTIFICATIONS</a>
                                            </div> <!-- / .dropdown-menu -->
                                        </li>

                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle user-menu" data-toggle="dropdown">
                                                <img src="<?php echo get_gravatar( $_SESSION['email_address'] ); ?>" alt="User Gravatar" />
                                                <span><?php echo $_SESSION['username']; ?></span>
                                            </a>
                                            <ul class="dropdown-menu">
												<?php if ( user_access( 'users_edit' ) ) { ?>
                                                    <li><a href="<?php echo il( $menuvar['USERS'] . '&action=edituser&id=' . $_SESSION['userid'] ); ?>"><i class="glyphicon glyphicon-pencil"></i> Edit Profile</a></li><?php } ?>
												<?php if ( user_access( 'changePassword' ) ) { ?>
                                                    <li><a href="" id="changePasswordLink"><i class="glyphicon glyphicon-wrench"></i> Change Password</a></li><?php } ?>
                                                <li class="divider"></li>
                                                <li><a href="<?php echo il( $menuvar['LOGOUT'] ); ?>"><i class="glyphicon glyphicon-off"></i> Logout</a></li>
                                            </ul>
                                        </li>

                                    </ul> <!-- / .navbar-nav -->
                                </div> <!-- / .right -->
							<?php } ?>
                        </div>
                    </div> <!-- / #main-navbar-collapse -->
                </div> <!-- / .navbar-inner -->
            </div> <!-- / #main-navbar -->

            <div id="main-menu" role="navigation">
                <div id="main-menu-inner">
					<?php if ( ! empty( $widgets['wa-leftColAboveMenu'] ) ) { ?>
                        <div class="menu-content">
							<?php echo $widgets['wa-leftColAboveMenu']; ?>
                        </div>
					<?php } ?>

					<?php $page->printSidebar( '', 'navigation', '', '', 1 ); ?>

					<?php if ( ! empty( $widgets['wa-leftCol'] ) ) { ?>
                        <div class="menu-content">
							<?php echo $widgets['wa-leftCol']; ?>
                        </div>
					<?php } ?>
                </div> <!-- / #main-menu-inner -->
            </div> <!-- / #main-menu -->

            <div id="content-wrapper"<?php if ( ! empty( $contentClass ) ) {
				echo ' class="' . trim( $contentClass ) . '"';
			} ?>>

				<?php $page->printBreadCrumbs( 'ul', '', '', '', 'breadcrumb breadcrumb-no-padding', 1 ); ?>
				<?php $page->printTemplateVar( 'PageContent' ); ?>
                <footer>
					<?php echo parseForTagsFromArray( $mbp_config['ftsmbp_copyright'], getBuiltinTags() ); ?>
					<?php if ( $mbp_config['ftsmbp_show_powered_by'] == 1 ) { ?>
                        <span class="pull-right"><?php echo $mbp_config['ftsmbp_powered_by']; ?></span>

					<?php } ?>
                </footer>

            </div> <!-- / #content-wrapper -->

            <div id="main-menu-bg">&nbsp;</div>
        </div> <!-- / #main-wrapper -->
        <!--Javascripts Begin-->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/js/vendor/bootstrap.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="//code.jquery.com/jquery-migrate-1.2.1.js"></script>
        <script src="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/jui/jquery-ui.custom.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.1/fullcalendar.min.js"></script>
        <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/dataTables.bootstrap.min.js"></script>
        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
        <script src="//use.fontawesome.com/e30b1ab369.js"></script>

        <script src="<?php echo SITE_URL; ?>/javascripts/jquery.uploadifive.min.js"></script>

        <script src="<?php echo SITE_URL; ?>/node_modules/autosize/dist/autosize.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/bootbox.js/bootbox.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/bootstrap-treeview/dist/bootstrap-treeview.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/bootstro/bootstro.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/bootstrap-icon-picker/js/iconPicker.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/jquery-form/dist/jquery.form.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/jquery-jeditable/dist/jquery.jeditable.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/@claviska/jquery-minicolors/jquery.minicolors.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/jquery-ui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/jquery.pwstrength/dist/pwstrength-bootstrap.min.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/raphael/raphael.js"></script>
        <script src="<?php echo SITE_URL; ?>/node_modules/justgage/justgage.js"></script>
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
        <script src="<?php echo SITE_URL; ?>/javascripts/addons/file-manager/jquery.file.manager.js"></script>
        <script src="<?php echo SITE_URL; ?>/javascripts/common.js"></script>
        <script src="<?php echo SITE_URL; ?>/javascripts/functions.js"></script>
        <script src="<?php echo SITE_URL; ?>/javascripts/widgets.js"></script>
        <script src="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/js/main.js"></script>
        <script src="<?php echo SITE_URL; ?>/themes/<?php echo $mbp_config['ftsmbp_theme']; ?>/js/vendor/jquery.slimscroll-1.3.2.js"></script>
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
