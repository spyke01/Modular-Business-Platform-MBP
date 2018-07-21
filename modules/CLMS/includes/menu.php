<?php
/***************************************************************************
 *                               menu.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/



$clmsUserMenuItems  = array(
	'MYDOWNLOADS' => array(
		'text'        => 'My Downloads',
		'link'        => 'index.php?p=module&prefix=CLMS&module_page=mydownloads',
		'icon'        => 'glyphicon glyphicon-download',
		'permissions' => '0,2,5,6'
	),
	'MYINVOICES'  => array(
		'text'        => 'My Invoices',
		'link'        => 'index.php?p=module&prefix=CLMS&module_page=myinvoices',
		'icon'        => 'glyphicons glyphicons-table',
		'permissions' => '0,2,5,6'
	),
	'MYNOTES'     => array(
		'text'        => 'My Notes',
		'link'        => 'index.php?p=module&prefix=CLMS&module_page=mynotes',
		'icon'        => 'glyphicons glyphicons-notes',
		'permissions' => '0,2,5,6'
	),
);
$clmsAdminMenuItems = array(
	'APPOINTMENTS' => array(
		'text'        => 'Appointments',
		'link'        => 'index.php?p=module&prefix=CLMS&module_page=appointments',
		'icon'        => 'glyphicon glyphicon-calendar',
		'permissions' => '2,5'
	),
	'CLIENTS'      => array(
		'text'        => 'Clients',
		'link'        => 'index.php?p=module&prefix=CLMS&module_page=clients',
		'icon'        => 'glyphicons glyphicons-address-book',
		'permissions' => '0,2,5,6'
	),
	'PRODUCTS'     => array(
		'text'        => 'Products',
		'link'        => 'index.php?p=module&prefix=CLMS&module_page=products',
		'icon'        => 'glyphicons glyphicons-shopping-cart',
		'permissions' => '2'
	),
);
$clmsMenus          = array(
	'DOWNLOADS'      => array(
		'text'        => 'Downloads',
		'link'        => 'index.php?p=module&prefix=CLMS&module_page=downloads',
		'permissions' => '2,5'
	),
	'EMAILINVOICE'   => array(
		'text'        => 'Email Invoice',
		'link'        => 'index.php?p=module&prefix=CLMS&module_page=invoices&action=emailinvoice',
		'permissions' => '2,5'
	),
	'EMAILORDER'     => array(
		'text'        => 'Email Order',
		'link'        => 'index.php?p=module&prefix=CLMS&module_page=orders&action=emailorder',
		'permissions' => '2,5'
	),
	'INVOICEPAYMENT' => array(
		'text'        => 'Invoice Payments',
		'link'        => 'index.php?p=module&prefix=CLMS&module_page=invoices&action=paymenthistory',
		'permissions' => '2,5'
	),
	'INVOICES'       => array(
		'text'        => 'Invoices',
		'link'        => 'index.php?p=module&prefix=CLMS&module_page=invoices',
		'permissions' => '0,2,5,6'
	),
	'NOTES'          => array(
		'text'        => 'Notes',
		'link'        => 'index.php?p=module&prefix=CLMS&module_page=notes',
		'permissions' => '2,5'
	),
	'VIEWINVOICE'    => array(
		'text'        => 'View Invoice',
		'link'        => 'index.php?p=module&prefix=CLMS&module_page=invoices&action=viewinvoice',
		'permissions' => '0,2,5,6'
	),
);