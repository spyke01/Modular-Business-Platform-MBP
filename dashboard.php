<?php 
/***************************************************************************
 *                               dashboard.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/


 
$page_content = $dashboard_alerts = $JQueryReadyScripts = '';
		
// Show the new primary theme notice
if ( user_access('view_theme_update_notice') && get_config_value('shown_theme_update_notice') == 0 && $mbp_config['ftsmbp_theme'] != 'modern' ) {
	$dashboard_alerts .= '
		<div id="themeUpdateNotice" class="jumbotron">
			<h1>Update Your Look</h1>
			<p>We have added a brand new theme called Modern. This theme has a multitude of new features, here are some of them:</p>
			<ul>
				<li>98,280 Color Combinations</li>
				<li>New Backgrounds</li>
				<li>Dropdown Support</li>
				<li>Custom CSS Support</li>
				<li>Login Page Styling</li>
				<li>Text Styling</li>
				<li>And much much more!</li>
			</ul>
			<p><a href="' . il( $menuvar['THEMES'] . '&amp;action=change_theme&amp;theme=modern' ) . '" class="btn btn-success btn-lg" role="button">Check it out!</a> <a class="btn btn-default btn-lg" role="button">I like the old style.</a></p>
		</div>';
		
	$JQueryReadyScripts .= "
		$('#themeUpdateNotice a.btn').click(function(){
	        $.get(SITE_URL + '/ajax.php?action=showedThemeUpdateNotice');  
	    });";
}
	
// Show the application tour	
if ( user_access('view_tour') && get_config_value('shown_tour') == 0 ) {
	$dashboard_alerts .= '
		<div id="tour" class="jumbotron">
			<h1>Your Business, Your Platform, Your Way</h1>
			<p>Thank you for installing the Modular Business Platform (MBP). To learn more about how to use your new software suite, simply click the button below.</p>
			<p>
				<a class="btn btn-success btn-lg" role="button" id="startTourButton">Take a Tour</a> 
				<a href="https://youtu.be/RgZ36FTpHcU" role="button" class="btn btn-success btn-lg" target="_blank">Watch our Training Video</a>
			</p>
		</div>';
		
	$JQueryReadyScripts .= "
		$('#startTourButton').click(function(){
	        bootstro.start('', {
	        	stopOnBackdropClick : false, 
	        	stopOnEsc : false,
	            url : SITE_URL + '/ajax.php?action=getTourDetailsJSON&type=dashboard',
	            onComplete : function(params) {
	                $.get(SITE_URL + '/ajax.php?action=showedTour');
	            },
	            onExit : function(params) {
	                $.get(SITE_URL + '/ajax.php?action=showedTour');
	            },
	            finishButton : '<button class=\"btn btn-mini btn-success bootstro-finish-btn\"><i class=\"icon-ok\"></i> Ok I got it, hide this</button>',
	        });    
	    });";
}
	
// Show the update box
if ( user_access('perform_update') ) {	
	$dashboard_alerts .= version_functions('checkForExpiredLicense');
	$dashboard_alerts .= version_functions('checkForUpdates');
}

// Allow us to add additional dashboard alerts
$page_content .= apply_filters( 'dashboard_alerts', $dashboard_alerts );

// Show main dashboard items
$dashboardItems = apply_filters( 'dashboard_text', get_config_value('ftsmbp_content_dashboard') );
$dashboardItems .= callModuleHook('', 'dashboardPage', array(
	'section' => 'content'
));	
$dashboardItemJQuery .= callModuleHook('', 'dashboardPage', array(
	'section' => 'jQuery'
));	

$page_content .= '
		<div id="dashboard" class="bootstro box">
			<div class="box-header">
				<h3><i class="glyphicons glyphicons-fire"></i> Dashboard</h3>
			</div>
			<div class="box-content">
				' . $dashboardItems . '
			</div>
		</div>';

// Handle our JQuery needs
$JQueryReadyScripts .= $dashboardItemJQuery;
if ( user_access('perform_update') && showUpdatePopup() ) {
	$JQueryReadyScripts .= "
		$.get(SITE_URL + '/ajax.php?action=showUpdateDetails', function(updatesText) {
			bootbox.dialog({
				message: updatesText,
				title: 'System Updated!',
				buttons: {
					'Hide Me!':function() {
						$.get(SITE_URL + '/ajax.php?action=showedUpdateDetails');
					}
				}
			});
		});";
}

$page->setTemplateVar('PageContent', $page_content);
$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);