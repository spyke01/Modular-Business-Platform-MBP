<?php
global $menuvar, $mbp_config, $ftsdb;

// Changes before 4.14.07.11
if ( !menu_item_exists( '2', 'Email Users', $menuvar['EMAILUSERS'], 'System' ) ) {
	addMenuItem( '2', 'Email Users', $menuvar['EMAILUSERS'], 'System', '', '2', 'glyphicon glyphicon-envelope' );
}
$sql = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "email_templates` (
		`id` bigint(19) NOT NULL auto_increment,
		`template_id` varchar(255) DEFAULT NULL, #internal text id for use by certain plugins
		`name` varchar(255) DEFAULT NULL,
		`subject` varchar(255) DEFAULT NULL,
		`message` longtext NULL,
		`added_by` varchar(100) DEFAULT NULL,
		`prefix` varchar(100) DEFAULT NULL,
		PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$result = $ftsdb->run($sql);

// Copy all of our current email templates into the email templates table
$templatesToCopy = array(
	array(
		'template_id' => 'mbp-account-created',
		'name' => 'MBP: New Account Alert',
		'subject' => $mbp_config['ftsmbp_email_account_new_subject'],
		'message' => $mbp_config['ftsmbp_email_account_new'],
	),
	array(
		'template_id' => 'mbp-account-updated',
		'name' => 'MBP: Account Updated Alert',
		'subject' => $mbp_config['ftsmbp_email_account_update_subject'],
		'message' => $mbp_config['ftsmbp_email_account_update'],
	),
);

foreach ( $templatesToCopy as $templateData ) {
	if ( !emailTemplateExists( $templateData['template_id'] ) ) {
		addEmailTemplate( $templateData['template_id'], $templateData['name'], $templateData['subject'], $templateData['message'], 'System' );
	}
}

// Add icons
$menus = array(
	$menuvar['CATEGORIES'] => 'glyphicon glyphicon-random',
	$menuvar['EMAILUSERS'] => 'glyphicon glyphicon-envelope',
	$menuvar['GRAPHS'] => 'glyphicons stats',
	$menuvar['MENUS'] => 'glyphicon glyphicon-list',
	$menuvar['PERMISSIONS'] => 'glyphicon glyphicon-lock',
	$menuvar['REPORTS'] => 'glyphicons table',
	$menuvar['THEMES'] => 'glyphicon glyphicon-tint',
	$menuvar['USERS'] => 'glyphicons group',
	$menuvar['WIDGETS'] => 'glyphicons cogwheels',
);

foreach ( $menus as $link => $icon ) {
	$result = $ftsdb->update(DBTABLEPREFIX . 'menu_items', array(
			'icon' => $icon
		), 
		"link = :link", array(
			":link" => $link
		)
	);
}