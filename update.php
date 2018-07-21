<?php 
/***************************************************************************
 *                               update.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/


/* Define our Paths */
define('ABSPATH', dirname(__FILE__) . '/');
define('BASEPATH', rtrim(ABSPATH, '/'));

include BASEPATH . '/includes/header.php';

// Make sure we are in the https version if needed
if ( !is_https() && $mbp_config['ftsmbp_use_https'] == 1 ){
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $redirect");
}

if(!isset($_SESSION['userid'])){
	header("Location: http" . (isset($_SERVER['HTTPS']) ? 's' : '') . "://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['LOGIN']);
	exit();
}

$page_content = '';
// Configure our menus
$page->setTemplateVar( 'sidebar_active', INACTIVE );
$page->makeMenuItem("top", "Home", $menuvar['HOME'], "");

// Set theme values
$page->setTemplateVar('PageTitle', "Update");
$page->addBreadCrumb("Update", '');
$page->setTemplateVar('Theme', $mbp_config['ftsmbp_theme']);
if (isset($actual_style) && $actual_style == "printerFriendly") $page->setTemplateVar('Template', 'printerFriendlyTemplate.php');
else $page->setTemplateVar('Template', 'template.php');	

if ( A_LICENSE == 'FREE_VERSION' ) {
	$page_content = '
		<div class="box tabbable">
			<div class="box-header">
				<h3><i class="glyphicon glyphicon-download-alt"></i> ' . __('Update System') . '</h3>
			</div>
			<div class="box-content">
				<div id="updateSystem">
					' . return_error_alert('Your license does not allow for automated updates, this feature is only available for paid license holders. <a href="https://www.fasttracksites.com/product/license-renewal/">Click here to purchase a new license.</a>') . '
				</div>
			</div>
		</div>';
		
	$JQueryReadyScripts = "";
} else {
	$page_content = '
		<div class="box tabbable">
			<div class="box-header">
				<h3><i class="glyphicon glyphicon-download-alt"></i> ' . __('Update System') . '</h3>
			</div>
			<div class="box-content">
				<div id="updateSystem">
					' . __('Starting Update...') . '
				</div>
			</div>
		</div>';
		
	$JQueryReadyScripts = "
		$('#updateSystem').html('Checking for MBP Updates..." . progressSpinnerHTML() . "');
		
		jQuery.get('" . SITE_URL . "/ajax.php?action=performMainUpdate', function(data) {
			$('#updateSystem').html('Starting DB Updates..." . progressSpinnerHTML() . "');
			
			jQuery.get('" . SITE_URL . "/ajax.php?action=performDBUpdate', function(data2) {
				$('#updateSystem').html(data + data2);
				$('#updateSystem').effect('highlight',{},500);
			});
		});";
}

$page->setTemplateVar('PageContent', $page_content);
$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);
include BASEPATH . '/themes/' . $page->getTemplateVar('Theme') . '/' . $page->getTemplateVar('Template');