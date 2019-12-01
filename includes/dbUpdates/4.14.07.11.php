<?php
global $menuvar, $mbp_config, $ftsdb;

// Changes before 4.14.07.11
if ( ! menu_item_exists( '2', 'Email Users', $menuvar['EMAILUSERS'], 'System' ) ) {
	addMenuItem( '2', 'Email Users', $menuvar['EMAILUSERS'], 'System', '', '2', 'glyphicon glyphicon-envelope' );
}
$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "email_templates` (
		`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
		`template_id` VARCHAR(255) DEFAULT NULL, #internal TEXT id FOR USE BY certain PLUGINS
		`name` VARCHAR(255) DEFAULT NULL,
		`subject` VARCHAR(255) DEFAULT NULL,
		`message` LONGTEXT NULL,
		`added_by` VARCHAR(100) DEFAULT NULL,
		`prefix` VARCHAR(100) DEFAULT NULL,
		PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$result = $ftsdb->run( $sql );

// Copy all of our current email templates into the email templates table
$templatesToCopy = [
	[
		'template_id' => 'mbp-account-created',
		'name'        => 'MBP: New Account Alert',
		'subject'     => $mbp_config['ftsmbp_email_account_new_subject'],
		'message'     => $mbp_config['ftsmbp_email_account_new'],
	],
	[
		'template_id' => 'mbp-account-updated',
		'name'        => 'MBP: Account Updated Alert',
		'subject'     => $mbp_config['ftsmbp_email_account_update_subject'],
		'message'     => $mbp_config['ftsmbp_email_account_update'],
	],
];

foreach ( $templatesToCopy as $templateData ) {
	if ( ! emailTemplateExists( $templateData['template_id'] ) ) {
		addEmailTemplate( $templateData['template_id'], $templateData['name'], $templateData['subject'], $templateData['message'], 'System' );
	}
}

// Add icons
$menus = [
	$menuvar['CATEGORIES']  => 'glyphicon glyphicon-random',
	$menuvar['EMAILUSERS']  => 'glyphicon glyphicon-envelope',
	$menuvar['GRAPHS']      => 'glyphicons stats',
	$menuvar['MENUS']       => 'glyphicon glyphicon-list',
	$menuvar['PERMISSIONS'] => 'glyphicon glyphicon-lock',
	$menuvar['REPORTS']     => 'glyphicons table',
	$menuvar['THEMES']      => 'glyphicon glyphicon-tint',
	$menuvar['USERS']       => 'glyphicons group',
	$menuvar['WIDGETS']     => 'glyphicons cogwheels',
];

foreach ( $menus as $link => $icon ) {
	$result = $ftsdb->update( DBTABLEPREFIX . 'menu_items',
		[
			'icon' => $icon,
		],
		"link = :link",
		[
			":link" => $link,
		]
	);
}