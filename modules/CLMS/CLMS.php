<?php
/***************************************************************************
 *                               CLMS.php
 *                            -------------------
 *   begin                : Wednesday, November 26, 2008
 *   copyright            : (C) 2008 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/


class CLMS {
	//====================================
	// Basic Module information
	//====================================
	public $name = "Client Management System";
	public $description = "Adds the FTS CLMS capabilities to your system.";
	public $developer = "Paden Clayton";
	public $version = "2.17.07.12";
	public $updateRequestURL = '';
	private $prefix = "CLMS";

	// Module variable
	public $FTS_PAYMENTTYPES = array(
		"0" => "Cash",
		"1" => "Check",
		"2" => "Credit Card",
		"3" => "Google Checkout",
		"4" => "PayPal"
	);
	public $FTS_CURRENCIES = array(
		"$"       => "Dollar ($)",
		"&euro;"  => "Euro (&euro;)",
		"&pound;" => "Pound (&pound;)",
		"&yen;"   => "Yen (&yen;)"
	);
	public $CITYSTATEZIP_TYPE = array( '0' => 'US', '1' => 'England' );

	//===============================================================
	// Our class constructor
	//===============================================================
	public function __construct() {
		global $page, $mbp_config;

		$myFolder = SITE_URL . "/modules/$this->prefix/";

		// Add stylsheet files
		$page->addStyle( $myFolder . 'style.css' );

		// Add script files
		$page->addScript( $myFolder . 'javascripts/functions.js' );

		// Set our updateRequestURL
		$this->updateRequestURL = 'https://www.fasttracksites.com/versions/serialChecker.php?response=json&app=module_' . $this->prefix . '&serial=' . $mbp_config['ftsmbp_clms_serial'];
	}

	//====================================
	// Install hook
	//====================================
	public function install() {
		global $ftsdb, $mbp_config;

		installModule( $this->prefix, $this->name, $this->description, $this->developer, $this->version );

		include_once( BASEPATH . '/modules/' . $this->prefix . '/includes/menu.php' );
		include_once( BASEPATH . '/modules/' . $this->prefix . '/includes/constants.php' );

		// Add Menus
		if ( count( $clmsUserMenuItems ) ) {
			foreach ( $clmsUserMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'] );
				}
			}
		}
		if ( count( $clmsAdminMenuItems ) ) {
			foreach ( $clmsAdminMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'] );
				}
			}
		}

		// Create database tables
		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "appointments` (
				`id` mediumint(8) NOT NULL auto_increment,
				`client_id` mediumint(8) NOT NULL,
				`datetimestamp` varchar(50) DEFAULT NULL,
				`place` varchar(100) DEFAULT NULL,
				`description` text,
				`urgency` tinyint(1) DEFAULT 0,
				`attire` varchar(50) DEFAULT NULL,
				PRIMARY KEY  (`id`),
				KEY `client_id` (`client_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;";
		$result = $ftsdb->run( $sql );

		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "clients` (
				`id` mediumint(8) NOT NULL auto_increment,
				`user_id` mediumint(8) NOT NULL,
				`cat_id` mediumint(8) NOT NULL,
				`username` varchar(25) DEFAULT NULL,
				`password` varchar(32) DEFAULT NULL,
				`first_name` varchar(50) DEFAULT NULL,
				`last_name` varchar(50) DEFAULT NULL,
				`title` varchar(50) DEFAULT NULL,
				`company` varchar(50) DEFAULT NULL,
				`street1` varchar(50) DEFAULT NULL,
				`street2` varchar(50) DEFAULT NULL,
				`city` varchar(50) DEFAULT NULL,
				`state` varchar(50) DEFAULT NULL,
				`zip` varchar(50) DEFAULT NULL,
				`daytime_phone` varchar(50) DEFAULT NULL,
				`nighttime_phone` varchar(50) DEFAULT NULL,
				`cell_phone` varchar(50) DEFAULT NULL,
				`email_address` varchar(50) DEFAULT NULL,
				`website` varchar(50) DEFAULT NULL,
				`preffered_client` tinyint(1) DEFAULT 0,
				`found_us_through` varchar(50) DEFAULT NULL,
				PRIMARY KEY  (`id`),
				KEY `user_id` (`user_id`),
				KEY `cat_id` (`cat_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;";
		$result = $ftsdb->run( $sql );

		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "downloads` (
				`id` mediumint(8) NOT NULL auto_increment,
				`client_id` mediumint(8) NOT NULL,
				`name` varchar(100) DEFAULT NULL,
				`url` varchar(250) DEFAULT NULL,
				`serial_number` varchar(100) DEFAULT NULL,
				`datetimestamp` varchar(50) DEFAULT NULL,
				PRIMARY KEY  (`id`),
				KEY `client_id` (`client_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;";
		$result = $ftsdb->run( $sql );

		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "invoices` (
				`id` mediumint(8) NOT NULL auto_increment,
				`client_id` mediumint(8) NOT NULL,
				`datetimestamp` varchar(50) DEFAULT NULL,
				`description` varchar(100) DEFAULT NULL,
				`discount` decimal(12,2) default '0.00',
				`note` text,
				`status` tinyint(1) DEFAULT 0,
				PRIMARY KEY  (`id`),
				KEY `client_id` (`client_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;";
		$result = $ftsdb->run( $sql );

		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "invoices_products` (
				`id` mediumint(8) NOT NULL auto_increment,
				`invoice_id` mediumint(8) NOT NULL,
				`name` varchar(50) NOT NULL DEFAULT '',
				`price` decimal(12,2) default '0.00',
				`profit` decimal(12,2) default '0.00',
				`qty` mediumint(8),
				`shipping` decimal(12,2) default '0.00',
				PRIMARY KEY  (`id`),
				KEY `invoice_id` (`invoice_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;";
		$result = $ftsdb->run( $sql );

		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "invoices_payments` (
				`id` mediumint(8) NOT NULL auto_increment,
				`invoice_id` mediumint(8) NOT NULL,
				`datetimestamp` varchar(50) DEFAULT NULL,
				`type` mediumint(8),
				`paid` decimal(12,2) default '0.00',
				PRIMARY KEY  (`id`),
				KEY `invoice_id` (`invoice_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;";
		$result = $ftsdb->run( $sql );

		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "notes` (
				`id` mediumint(8) NOT NULL auto_increment,
				`client_id` mediumint(8) NOT NULL,
				`datetimestamp` varchar(50) DEFAULT NULL,
				`note` text,
				`urgency` tinyint(1) DEFAULT 0,
				PRIMARY KEY  (`id`),
				KEY `client_id` (`client_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;";
		$result = $ftsdb->run( $sql );

		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "products` (
				`id` mediumint(8) NOT NULL auto_increment,
				`name` varchar(100) NOT NULL DEFAULT '',
				`price` decimal(12,2) default '0.00',
				`profit` decimal(12,2) default '0.00',
				`shipping` decimal(12,2) default '0.00',
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;";
		$result = $ftsdb->run( $sql );

		// Check our permissions and config items
		callModuleHook( $this->prefix, 'checkDBSettings' );
	}

	//====================================
	// Uninstall hook
	//====================================
	public function uninstall() {
		uninstallModule( $this->prefix );

		// Remove Menus
		removeMenuItemsByPrefix( $this->prefix );
	}

	//====================================
	// Activate hook
	//====================================
	public function activate() {
		activateModule( $this->prefix );
	}

	//====================================
	// Deactivate hook
	//====================================
	public function deactivate() {
		deactivateModule( $this->prefix );
	}

	//====================================
	// Update hook
	//====================================
	public function update() {
		global $modules, $ftsdb;
		updateModule( $this->prefix, $this->name, $this->description, $this->developer, $this->version );

		// Check our permissions and config items
		callModuleHook( $this->prefix, 'checkDBSettings' );

		// Version 2.13.08.08 - We converted a lot of fields to allow nulls, handle the updates here
		if ( $modules[ $this->prefix ]->version <= '2.13.08.08' ) {
			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "appointments`
				CHANGE `datetimestamp` `datetimestamp` varchar(50) DEFAULT NULL,
				CHANGE `place` `place` varchar(100) DEFAULT NULL,
				CHANGE `description` `description` text,
				CHANGE `urgency` `urgency` tinyint(1) DEFAULT 0,
				CHANGE `attire` `attire` varchar(50) DEFAULT NULL,
				ADD INDEX (`client_id`),
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
			$result = $ftsdb->run( $sql );

			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "clients`
				CHANGE `username` `username` varchar(25) DEFAULT NULL,
				CHANGE `password` `password` varchar(32) DEFAULT NULL,
				CHANGE `first_name` `first_name` varchar(50) DEFAULT NULL,
				CHANGE `last_name` `last_name` varchar(50) DEFAULT NULL,
				CHANGE `title` `title` varchar(50) DEFAULT NULL,
				CHANGE `company` `company` varchar(50) DEFAULT NULL,
				CHANGE `street1` `street1` varchar(50) DEFAULT NULL,
				CHANGE `street2` `street2` varchar(50) DEFAULT NULL,
				CHANGE `city` `city` varchar(50) DEFAULT NULL,
				CHANGE `state` `state` varchar(50) DEFAULT NULL,
				CHANGE `zip` `zip` varchar(50) DEFAULT NULL,
				CHANGE `daytime_phone` `daytime_phone` varchar(50) DEFAULT NULL,
				CHANGE `nighttime_phone` `nighttime_phone` varchar(50) DEFAULT NULL,
				CHANGE `cell_phone` `cell_phone` varchar(50) DEFAULT NULL,
				CHANGE `email_address` `email_address` varchar(50) DEFAULT NULL,
				CHANGE `website` `website` varchar(50) DEFAULT NULL,
				CHANGE `preffered_client` `preffered_client` tinyint(1) DEFAULT 0,
				CHANGE `found_us_through` `found_us_through` varchar(50) DEFAULT NULL,
				ADD INDEX (`user_id`),
				ADD INDEX (`cat_id`),
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
			$result = $ftsdb->run( $sql );

			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "downloads`
				CHANGE `name` `name` varchar(100) DEFAULT NULL,
				CHANGE `url` `url` varchar(250) DEFAULT NULL,
				CHANGE `serial_number` `serial_number` varchar(100) DEFAULT NULL,
				CHANGE `datetimestamp` `datetimestamp` varchar(50) DEFAULT NULL,
				ADD INDEX (`client_id`),
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
			$result = $ftsdb->run( $sql );

			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "invoices`
				CHANGE `datetimestamp` `datetimestamp` varchar(50) DEFAULT NULL,
				CHANGE `description` `description` varchar(100) DEFAULT NULL,
				CHANGE `discount` `discount` decimal(12,2) default '0.00',
				CHANGE `note` `note` text,
				CHANGE `status` `status` tinyint(1) DEFAULT 0,
				ADD INDEX (`client_id`),
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
			$result = $ftsdb->run( $sql );

			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "invoices_products`
				CHANGE `price` `price` decimal(12,2) default '0.00',
				CHANGE `profit` `profit` decimal(12,2) default '0.00',
				CHANGE `qty` `qty` mediumint(8),
				CHANGE `shipping` `shipping` decimal(12,2) default '0.00',
				ADD INDEX (`invoice_id`),
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
			$result = $ftsdb->run( $sql );

			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "invoices_payments`
				CHANGE `datetimestamp` `datetimestamp` varchar(50) DEFAULT NULL,
				CHANGE `type` `type` mediumint(8),
				CHANGE `paid` `paid` decimal(12,2) default '0.00',
				ADD INDEX (`invoice_id`),
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
			$result = $ftsdb->run( $sql );

			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "notes`
				CHANGE `note` `note` text,
				CHANGE `urgency` `urgency` tinyint(1) DEFAULT 0,
				ADD INDEX (`client_id`),
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
			$result = $ftsdb->run( $sql );

			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "products` 
				CHANGE `price` `price` decimal(12,2) default '0.00',
				CHANGE `profit` `profit` decimal(12,2) default '0.00',
				CHANGE `shipping` `shipping` decimal(12,2) default '0.00',
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
			$result = $ftsdb->run( $sql );

			// Move orders and then delete the tables		
			$result = $ftsdb->select( DBTABLEPREFIX . "orders" );

			if ( $result ) {
				foreach ( $result as $row ) {
					$result    = $ftsdb->insert( DBTABLEPREFIX . 'invoices', array(
						"datetimestamp" => $row['datetimestamp'],
						"client_id"     => $row['client_id'],
						"description"   => $row['description'],
						"discount"      => $row['discount'],
						"note"          => $row['note'],
						"status"        => $row['status'],
					) );
					$invoiceID = $ftsdb->lastInsertId();

					$result2 = $ftsdb->select( DBTABLEPREFIX . "orders_products", "`order_id` = :order_id", array(
						':order_id' => $row['order_id']
					) );

					if ( $result2 ) {
						foreach ( $result2 as $row2 ) {
							$result3 = $ftsdb->insert( DBTABLEPREFIX . 'invoices_products', array(
								"invoice_id" => $invoiceID,
								"name"       => $row['name'],
								"price"      => $row['price'],
								"profit"     => $row['profit'],
								"qty"        => $qty,
								"shipping"   => $row['shipping'],
							) );
						}
						$result2 = null;
					}

					$result2 = $ftsdb->select( DBTABLEPREFIX . "orders_payments", "`order_id` = :order_id", array(
						':order_id' => $row['order_id']
					) );

					if ( $result2 ) {
						foreach ( $result2 as $row2 ) {
							$result3 = $ftsdb->insert( DBTABLEPREFIX . 'invoices_payments', array(
								"datetimestamp" => $row2['datetimestamp'],
								"invoice_id"    => $invoiceID,
								'type'          => $row2['type'],
								"paid"          => $row2['paid'],
							) );
						}
						$result2 = null;
					}
				}
				$result = null;
			}
			$sql    = "DROP TABLE IF EXISTS `" . DBTABLEPREFIX . "orders`;";
			$result = $ftsdb->run( $sql );

			$sql    = "DROP TABLE IF EXISTS `" . DBTABLEPREFIX . "orders_products`;";
			$result = $ftsdb->run( $sql );

			$sql    = "DROP TABLE IF EXISTS `" . DBTABLEPREFIX . "orders_payments`;";
			$result = $ftsdb->run( $sql );
		}
	}

	//====================================
	// Return Includes hook
	//====================================
	public function returnIncludes() {
		return BASEPATH . '/modules/CLMS/includes/constants.php;' . BASEPATH . '/modules/CLMS/includes/functions.php;' . BASEPATH . '/modules/CLMS/includes/menu.php;';
	}


	//===============================================================
	// Prep Settings hook
	//===============================================================
	public function prepSettings() {
		global $FTS_PAYMENTTYPES, $FTS_CURRENCIES, $CITYSTATEZIP_TYPE;

		// This allows easy access to these variables via our functionstions istead of calling them as $modules['CLMS']->FTS_PAYMENTTYPES
		$FTS_PAYMENTTYPES  = $this->FTS_PAYMENTTYPES;
		$FTS_CURRENCIES    = $this->FTS_CURRENCIES;
		$CITYSTATEZIP_TYPE = $this->CITYSTATEZIP_TYPE;

		// Add our actions
		add_action( 'perform_login', 'clms_perform_login', 10, 3 );
		add_action( 'perform_impersonation', 'clms_perform_impersonation', 1, 1 );
	}

	//====================================
	// Prep Menus hook
	//====================================
	public function prepMenus() {
		global $page, $clmsUserMenuItems, $clmsAdminMenuItems, $clmsMenus;
		$myFolder = "modules/$this->prefix/";

		if ( count( $clmsUserMenuItems ) ) {
			foreach ( $clmsUserMenuItems as $key => $menuItemArray ) {
				$clmsMenus[ $key ] = $menuItemArray;
			}
		}
		if ( count( $clmsAdminMenuItems ) ) {
			foreach ( $clmsAdminMenuItems as $key => $menuItemArray ) {
				$clmsMenus[ $key ] = $menuItemArray;
			}
		}
	}

	//===============================================================
	// Check DB Settings hook
	//===============================================================
	public function checkDBSettings() {
		global $ftsdb, $clmsUserMenuItems, $clmsAdminMenuItems;

		$defaultPermissions = array(
			'clms_appointments_access'                => '2,',
			'clms_appointments_delete'                => '2,',
			'clms_appointments_printCalendar'         => '2,',
			'clms_categories_create'                  => '2,',
			'clms_clients_access'                     => '2,',
			'clms_clients_create'                     => '2,',
			'clms_clients_delete'                     => '2,',
			'clms_clients_edit'                       => '2,',
			'clms_clients_manage_all_clients'         => '2,',
			'clms_clients_manage_owner'               => '2,',
			'clms_clients_manage_client_login'        => '2,',
			'clms_clients_sendWelcomeMessage'         => '2,',
			'clms_deleteitem'                         => '2,',
			'clms_downloads_access'                   => '2,',
			'clms_downloads_create'                   => '2,',
			'clms_downloads_delete'                   => '2,',
			'clms_downloads_edit'                     => '2,',
			'clms_invoices_access'                    => '2,',
			'clms_invoices_accessDetails'             => '0,2,5,',
			'clms_invoices_create'                    => '2,',
			'clms_invoices_createPayment'             => '2,',
			'clms_invoices_delete'                    => '2,',
			'clms_invoices_deletePayment'             => '2,',
			'clms_invoices_edit'                      => '2,',
			'clms_invoices_editInvoice'               => '2,',
			'clms_invoices_email'                     => '2,',
			'clms_invoices_getLineTotal'              => '2,',
			'clms_invoices_getSubtotal'               => '2,',
			'clms_invoices_getTotalDue'               => '2,',
			'clms_invoices_makePayments'              => '2,',
			'clms_invoices_reprint'                   => '0,2,5,',
			'clms_invoices_returnProductTableRowHTML' => '2,',
			'clms_invoices_viewPayments'              => '2,',
			'clms_mydownloads_access'                 => '0,2,5,',
			'clms_myinvoices_access'                  => '0,2,5,',
			'clms_mynotes_access'                     => '0,2,5,',
			'clms_myorders_access'                    => '0,2,5,',
			'clms_notes_access'                       => '2,',
			'clms_notes_create'                       => '2,',
			'clms_notes_delete'                       => '2,',
			'clms_notes_edit'                         => '2,',
			'clms_orders_access'                      => '2,',
			'clms_orders_accessDetails'               => '0,2,5,',
			'clms_orders_create'                      => '2,',
			'clms_orders_createPayment'               => '2,',
			'clms_orders_delete'                      => '2,',
			'clms_orders_deletePayment'               => '2,',
			'clms_orders_edit'                        => '2,',
			'clms_orders_editOrder'                   => '2,',
			'clms_orders_email'                       => '2,',
			'clms_orders_getLineTotal'                => '2,',
			'clms_orders_getSubtotal'                 => '2,',
			'clms_orders_getTotalDue'                 => '2,',
			'clms_orders_makePayments'                => '2,',
			'clms_orders_reprint'                     => '0,2,5,',
			'clms_orders_returnProductTableRowHTML'   => '2,',
			'clms_orders_update'                      => '2,',
			'clms_orders_viewPayments'                => '2,',
			'clms_products_access'                    => '2,',
			'clms_products_create'                    => '2,',
			'clms_products_delete'                    => '2,',
			'clms_products_edit'                      => '2,',
			'clms_updateitem'                         => '2,'
		);

		if ( count( $defaultPermissions ) ) {
			foreach ( $defaultPermissions as $name => $role_ids ) {
				if ( ! permision_setting_exists( $name ) ) {
					add_permision_setting( $name, $role_ids );
				}
			}
		}

		// Fix name of any settings		
		$defaultSettings = array(
			'ftsmbp_currency_type'         => 'ftsmbp_clms_currency_type',
			'ftsmbp_sales_tax'             => 'ftsmbp_clms_sales_tax',
			'ftsmbp_invoice_company_name'  => 'ftsmbp_clms_invoice_company_name',
			'ftsmbp_invoice_address'       => 'ftsmbp_clms_invoice_address',
			'ftsmbp_invoice_city'          => 'ftsmbp_clms_invoice_city',
			'ftsmbp_invoice_state'         => 'ftsmbp_clms_invoice_state',
			'ftsmbp_invoice_zip'           => 'ftsmbp_clms_invoice_zip',
			'ftsmbp_invoice_phone_number'  => 'ftsmbp_clms_invoice_phone_number',
			'ftsmbp_invoice_fax'           => 'ftsmbp_clms_invoice_fax',
			'ftsmbp_invoice_email_address' => 'ftsmbp_clms_invoice_email_address',
			'ftsmbp_invoice_website'       => 'ftsmbp_clms_invoice_website'
		);

		if ( count( $defaultSettings ) ) {
			foreach ( $defaultSettings as $name => $newName ) {
				$result = $ftsdb->update( DBTABLEPREFIX . "config", array(
					"name" => $newName
				),
					"name = :name", array(
						":name" => $name
					)
				);
			}
		}

		// Add any new settings
		$defaultSettings = array(
			'ftsmbp_clms_citystateziptext_type' => '0',
		);

		if ( count( $defaultSettings ) ) {
			foreach ( $defaultSettings as $name => $value ) {
				if ( ! config_value_exists( $name ) ) {
					add_config_value( $name, $value );
				}
			}
		}

		// Add our new columns to the clients table
		if ( $this->prefix < '2.14.07.11' ) {
			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "clients` ADD `user_id` mediumint(8) DEFAULT 1 NOT NULL AFTER `id`";
			$result = $ftsdb->run( $sql );
		}

		// Add icons if necessary
		foreach ( array_merge( (array) $clmsUserMenuItems, (array) $clmsAdminMenuItems ) as $key => $menuItemArray ) {
			$result = $ftsdb->update( DBTABLEPREFIX . 'menu_items', array(
				'icon' => $menuItemArray['icon']
			),
				"link = :link", array(
					":link" => $menuItemArray['link']
				)
			);
		}
	}

	//====================================
	// Get Dropdown Array hook
	//====================================
	public function getDropdownArray( $arguments = array() ) {
		global $ftsdb;
		extract( (array) $arguments ); // Extract our arguments into variables

		$returnArray = array();

		$myClients = getMyClientIDs();
		// Prep our IN clause data
		$preparedInClause = $ftsdb->prepareInClauseVariable( $myClients );
		$selectBindData   = $preparedInClause['data'];

		if ( $type == "citystateziptexttype" ) {
			$returnArray = $returnArray + $this->CITYSTATEZIP_TYPE; // Preserve numberic keys by not using array_merge
		} else if ( $type == "clientcategories" ) {
			$result = $ftsdb->select( DBTABLEPREFIX . "categories", "type='3' ORDER BY name", array(), 'id, name' );

			if ( $result ) {
				foreach ( $result as $row ) {
					$returnArray[ $row['id'] ] = $row['name'];
				}
				$result = null;
			}
		} else if ( $type == "clients" ) {
			$result = $ftsdb->select( DBTABLEPREFIX . "clients", "id IN (" . $preparedInClause['binds'] . ") ORDER BY last_name", $selectBindData, 'id, first_name, last_name' );

			if ( $result ) {
				foreach ( $result as $row ) {
					$returnArray[ $row['id'] ] = $row['last_name'] . ", " . $row['first_name'];
				}
				$result = null;
			}
		} else if ( $type == "clientsCompany" ) {
			$result = $ftsdb->select( DBTABLEPREFIX . "clients", "id IN (" . $preparedInClause['binds'] . ") ORDER BY company", $selectBindData, 'id, company' );

			if ( $result ) {
				foreach ( $result as $row ) {
					$returnArray[ $row['id'] ] = $row['company'];
				}
				$result = null;
			}
		} else if ( $type == "currencies" ) {
			$returnArray = array_merge( $returnArray, $this->FTS_CURRENCIES );
		} else if ( $type == "paymenttypes" ) {
			$returnArray = $returnArray + $this->FTS_PAYMENTTYPES;     // Preserve numberic keys by not using array_merge
		} else if ( $type == "products" ) {
			$result = $ftsdb->select( DBTABLEPREFIX . "products", "1 ORDER BY name ASC", array(), 'id, name' );

			if ( $result ) {
				foreach ( $result as $row ) {
					$returnArray[ $row['id'] ] = $row['name'];
				}
				$result = null;
			}
		} else if ( $type == "productswithprice" ) {
			$result = $ftsdb->select( DBTABLEPREFIX . "products", "1 ORDER BY name ASC", array(), 'id, name, price, profit, shipping' );

			if ( $result ) {
				foreach ( $result as $row ) {
					$returnArray[ $row['id'] ] = $row['name'] . " - " . formatCurrency( $row['price'] + $row['profit'] + $row['shipping'] );
				}
				$result = null;
			}
		}

		return $returnArray;
	}

	//====================================
	// Create Dropdown hook
	//====================================
	public function createDropdown( $arguments = array() ) {
		extract( (array) $arguments ); // Extract our arguments into variables

		$dropdown      = "";
		$dropdownItems = $this->getDropdownArray( $type );
		if ( is_array( $dropdownItems ) && count( $dropdownItems ) ) {
			foreach ( $dropdownItems as $key => $value ) {
				$dropdown .= "<option value=\"" . $key . "\"" . testSelected( $key, $currentSelection ) . ">" . $value . "</option>";
			}
		}

		return $dropdown;
	}

	//====================================
	// Show Page hook
	//====================================
	public function showPage( $arguments = array() ) {
		global $ftsdb, $page, $clmsMenus, $actual_action, $actual_id, $actual_startsWith;

		extract( (array) $arguments ); // Extract our arguments into variables
		$page_content = $JQueryReadyScripts = '';
		$this->prepMenus();

		// Cycle through our pages and handle the content
		if ( $module_page == 'appointments' ) {
			$page->setTemplateVar( 'PageTitle', "Appointments" );
			$page->addBreadCrumb( "Appointments", $clmsMenus['APPOINTMENTS']['link'] );

			if ( user_access( 'clms_appointments_access' ) ) {
				$currentDate = ( isset( $_GET['date'] ) ) ? keeptasafe( $_GET['date'] ) : time();

				//=================================================
				// Show Appointments for Chosen Date
				//=================================================
				if ( $actual_action == "viewdate" ) {
					// Add breadcrumb
					$page->addBreadCrumb( "View Appointments for " . makeDate( $currentDate ), "" );

					$page_content = '		
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicon glyphicon-calendar"></i> ' . __( 'View Appointments for ' ) . makeDate( $currentDate ) . '</h3>
							</div>
							<div class="box-content">
								<div id="updateMeAppointments">
									' . printViewDateTable( $currentDate ) . '
								</div>
								' . ( ( user_access( 'clms_appointments_create' ) ) ? '
								<br /><br />
								' . printNewAppointmentForm( $currentDate ) . '
								' : '' ) . '
							</div>
						</div>';

					$JQueryReadyScripts = returnNewAppointmentFormJQuery( 2 );
				}

				//=================================================
				// Print out the calendar
				//=================================================	
				else {
					$page_content = '		
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicon glyphicon-calendar"></i> ' . __( 'Appointments' ) . '</h3>
								<div class="toolbar">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#currentAppointments" data-toggle="tab"><span>Current Appointments</span></a></li>
										' . ( ( user_access( 'clms_appointments_create' ) ) ? '<li><a href="#createANewAppointment" data-toggle="tab"><span>Create a New Appointment</span></a></li>' : '' ) . '
									</ul>
								</div>
							</div>
							<div class="tab-content">
								<div id="currentAppointments" class="tab-pane active">
									<div id="updateMeAppointments">
										<a href="#appointmentModal" role="button" class="btn btn-default" data-toggle="modal">Add Event</a>
										<div id="calendar"></div>
										
										<div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalTitle" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														<h4 id="appointmentModalTitle" class="modal-title">Modal title</h4>
													</div>
													<div class="modal-body">
														<form class="form-horizontal">
															<div class="control-group">
																<label class="control-label" for="title">Title</label>
																<div class="controls">
																	<input type="text" id="title" placeholder="Title">
																</div>
															</div>
														</form>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														<button type="button" class="btn btn-primary">Save changes</button>
													</div>
												</div><!-- /.modal-content -->
											</div><!-- /.modal-dialog -->
										</div><!-- /.modal -->
									</div>
								</div>
								' . ( ( user_access( 'clms_appointments_create' ) ) ? '
								<div id="createANewAppointment" class="tab-pane">
									' . printNewAppointmentForm( $currentDate ) . '
								</div>
								' : '' ) . '
							</div>
						</div>';

					$JQueryReadyScripts = returnNewAppointmentFormJQuery( 3 );
				}
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'clients' ) {
			$page->setTemplateVar( 'PageTitle', "Clients" );
			$page->addBreadCrumb( "Clients", $clmsMenus['CLIENTS']['link'] );

			if ( user_access( 'clms_clients_access' ) ) {
				if ( $actual_action == "editclient" && isset( $actual_id ) && user_access( 'clms_clients_edit' ) ) {
					// Add breadcrumb
					$page->addBreadCrumb( "Edit Client", "" );

					// Prep our IN clause data
					$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
					$selectBindData   = $preparedInClause['data'];
					$selectBindData   = array_merge( $selectBindData, array(
						":id" => $actual_id
					) );

					$result = $ftsdb->select( DBTABLEPREFIX . "clients", "id = :id AND id IN (" . $preparedInClause['binds'] . ") LIMIT 1", $selectBindData, 'id' );

					if ( ! $result ) {
						$page_content .= '
							<div class="box">
								<div class="box-header">
									<h3><i class="glyphicon glyphicon-warning"></i> ' . __( 'Error' ) . '</h3>
								</div>
								<div class="box-content bold redText">
									<p>' . __( "There was an error while accessing the client's details you are trying to update. You are now being redirected back to the Clients page." ) . '</p>
									<meta http-equiv="refresh" content="5;url=' . $clmsMenus['CLIENTS']['link'] . '">
								</div>
							</div>';
					} else {
						$result = null;

						// Get our module tabs	
						$extraTabs       = callModuleHook( '', 'showPage', array(
							'module_page' => 'clmsEditClientExtraTabs',
							'content'     => $row,
							'section'     => 'tabs'
						) );
						$extraTabContent = callModuleHook( '', 'showPage', array(
							'module_page' => 'clmsEditClientExtraTabs',
							'content'     => $row,
							'section'     => 'content'
						) );
						$extraJQuery     = callModuleHook( '', 'showPage', array(
							'module_page' => 'clmsEditClientExtraTabs',
							'content'     => $row,
							'section'     => 'jQuery'
						) );

						$page_content .= '
							<div class="box tabbable">
								<div class="box-header">
									<h3><i class="glyphicons glyphicons-address-book"></i> ' . __( 'Edit Client' ) . '</h3>
									<div class="toolbar">
										<ul class="nav nav-tabs">
											<li class="active"><a href="#clientDetails" data-toggle="tab"><span>Client Details</span></a></li>
											' . ( ( user_access( 'clms_notes_access' ) ) ? '<li><a href="#notes" data-toggle="tab"><span>Notes</span></a></li>' : '' ) . '
											' . ( ( user_access( 'clms_appointments_access' ) ) ? '<li><a href="#appointments" data-toggle="tab"><span>Appointments</span></a></li>' : '' ) . '
											' . ( ( user_access( 'clms_invoices_access' ) ) ? '<li><a href="#invoices" data-toggle="tab"><span>Invoices</span></a></li>' : '' ) . '
											' . ( ( user_access( 'clms_downloads_access' ) ) ? '<li><a href="#downloads" data-toggle="tab"><span>Downloads</span></a></li>' : '' ) . '
											' . $extraTabs . '
										</ul>
									</div>
								</div>
								<div class="tab-content">
									<div id="clientDetails" class="tab-pane active">
										' . printEditClientForm( $actual_id ) . '
									</div>
									' . ( ( user_access( 'clms_notes_access' ) ) ? '
									<div id="notes" class="tab-pane">
										<div id="updateMeNotes">
											' . printNotesTable( $actual_id ) . '
										</div>
										' . ( ( user_access( 'clms_notes_create' ) ) ? '
										<br /><br />
										' . printNewNoteForm( $actual_id ) . '
										' : '' ) . '
									</div>
									' : '' ) . '
									' . ( ( user_access( 'clms_appointments_access' ) ) ? '
									<div id="appointments" class="tab-pane">
										<div id="updateMeAppointments">
											' . printAppointmentsTable( $actual_id ) . '
										</div>
										' . ( ( user_access( 'clms_appointments_create' ) ) ? '
										<br /><br />
										' . printNewAppointmentForm( time(), $actual_id ) . '
										' : '' ) . '
									</div>
									' : '' ) . '
									' . ( ( user_access( 'clms_invoices_access' ) ) ? '
									<div id="invoices" class="tab-pane">
										<div id="updateMeInvoices">
											' . printInvoicesTable( $actual_id ) . '
										</div>
										' . ( ( user_access( 'clms_invoices_create' ) ) ? '
										<br /><br />
										' . printNewInvoiceForm( $actual_id ) . '
										' : '' ) . '
									</div>
									' : '' ) . '
									' . ( ( user_access( 'clms_downloads_access' ) ) ? '
									<div id="downloads" class="tab-pane">
										<div id="updateMeDownloads">
											' . printDownloadsTable( $actual_id ) . '
										</div>
										' . ( ( user_access( 'clms_downloads_create' ) ) ? '
										<br /><br />
										' . printNewDownloadForm( $actual_id ) . '
										' : '' ) . '
									</div>
									' : '' ) . '
									' . $extraTabContent . '
								</div>
							</div>';

						// Handle our JQuery needs
						$JQueryReadyScripts = returnEditClientFormJQuery( $actual_id ) . $extraJQuery;
						if ( user_access( 'clms_notes_access' ) ) {
							$JQueryReadyScripts .= returnNotesTableJQuery( $actual_id );
						}
						if ( user_access( 'clms_notes_access' ) ) {
							$JQueryReadyScripts .= returnNewNoteFormJQuery( 1 );
						}
						if ( user_access( 'clms_appointments_access' ) ) {
							$JQueryReadyScripts .= returnAppointmentsTableJQuery();
						}
						if ( user_access( 'clms_appointments_access' ) ) {
							$JQueryReadyScripts .= returnNewAppointmentFormJQuery( 1 );
						}
						if ( user_access( 'clms_invoices_access' ) ) {
							$JQueryReadyScripts .= returnInvoicesTableJQuery( $actual_id );
						}
						if ( user_access( 'clms_invoices_create' ) ) {
							$JQueryReadyScripts .= returnNewInvoiceFormJQuery( 1 );
						}
						if ( user_access( 'clms_downloads_access' ) ) {
							$JQueryReadyScripts .= returnDownloadsTableJQuery( $actual_id );
						}
						if ( user_access( 'clms_downloads_create' ) ) {
							$JQueryReadyScripts .= returnNewDownloadFormJQuery( 1 );
						}
					}
				} else {
					//==================================================
					// Print out our clients table
					//==================================================
					$page_content .= '	
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicons glyphicons-address-book"></i> ' . __( 'Clients' ) . '</h3>
								<div class="toolbar">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#currentClients" data-toggle="tab"><span>Current Clients</span></a></li>
										' . ( ( user_access( 'clms_clients_create' ) ) ? '<li><a href="#createANewClient" data-toggle="tab"><span>Create a New Client</span></a></li>' : '' ) . '
									</ul>
								</div>
							</div>
							<div class="tab-content">
								<div id="currentClients" class="tab-pane active">
									<div id="updateMeClients">
										' . printClientsTable( $actual_startsWith ) . '
									</div>
								</div>
								' . ( ( user_access( 'clms_clients_create' ) ) ? '
								<div id="createANewClient" class="tab-pane">
									' . printNewClientForm() . '
								</div>
								' : '' ) . '
							</div>
						</div>';

					// Handle our JQuery needs
					$JQueryReadyScripts = returnClientsTableJQuery() . "$(\"#newClientTabs\").tabs();";
					if ( user_access( 'clms_clients_create' ) ) {
						$JQueryReadyScripts .= returnNewClientFormJQuery( 1 );
					}
				}
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'invoices' ) {
			$page->setTemplateVar( 'PageTitle', "Invoices" );
			$page->addBreadCrumb( "Invoices", $clmsMenus['INVOICES']['link'] );

			if ( ( $actual_action == "viewinvoice" || $actual_action == "paymenthistory" || $actual_action == "emailinvoice" ) && isset( $actual_id ) ) {
				if ( user_access( 'clms_invoices_accessDetails' ) ) {
					// Add breadcrumb
					$page->addBreadCrumb( "Invoice Details", "" );
					$otherVersionLink = ( $actual_style == "printerFriendly" ) ? "<a href=\"" . $clmsMenus['VIEWINVOICE']['link'] . "&id=" . $actual_id . "\">Normal Version</a>" : "<a href=\"" . $clmsMenus['VIEWINVOICE']['link'] . "&id=" . $actual_id . "&style=printerFriendly\" class=\"btn btn-info\"><i class=\"glyphicon glyphicon-print\"></i> Printer Friendly Version</a>";

					$page_content .= '
						<div id="tabs" class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicons glyphicons-table"></i> ' . __( 'Invoices' ) . '</h3>
								<div class="toolbar">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#viewInvoice" data-toggle="tab"><span>View Invoice</span></a></li>
										' . ( ( user_access( 'clms_invoices_viewPayments' ) ) ? '<li><a href="#paymentHistory" data-toggle="tab"><span>Payment History</span></a></li>' : '' ) . '
										' . ( ( user_access( 'clms_invoices_email' ) ) ? '<li><a href="#emailInvoice" data-toggle="tab"><span>Email Invoice</span></a></li>' : '' ) . '
									</ul>
								</div>
							</div>
							<div class="tab-content">
								<div id="viewInvoice" class="tab-pane active">
									<span class="pull-right"> ' . $otherVersionLink . '</span>
									<div id="updateMeViewInvoice">
									' . printInvoice( $actual_id ) . '
									</div>
								</div>
								' . ( ( user_access( 'clms_invoices_viewPayments' ) ) ? '
								<div id="paymentHistory" class="tab-pane">
									<div id="updateMeInvoicePayments">
										' . printInvoicePaymentsTable( $actual_id ) . '
									</div>
									' . ( ( user_access( 'clms_invoices_createPayment' ) ) ? '
									<br /><br />
									' . printMakeInvoicePaymentForm( $actual_id ) . '
									' : '' ) . '
								</div>
								' : '' ) . '
								' . ( ( user_access( 'clms_invoices_email' ) ) ? '
								<div id="emailInvoice" class="tab-pane">
									' . printEmailInvoiceForm( $actual_id ) . '
								</div>
								' : '' ) . '
							</div>
						</div>';

					$JQueryReadyScripts = returnInvoiceJQuery( $actual_id, user_access( 'clms_invoices_editInvoice' ) ) . returnInvoicePaymentsTableJQuery() . returnMakeInvoicePaymentFormJQuery( $actual_id, 1 ) . returnEmailInvoiceFormJQuery() . "var \$tabs = $(\"#tabs\").tabs();";
					$JQueryReadyScripts .= ( user_access( 'clms_invoices_makePayments' ) ) ? returnMakeInvoicePaymentFormJQuery( $actual_id, 1 ) : "";

					// Select proper tab if needed
					$JQueryReadyScripts .= ( $actual_action == "paymenthistory" ) ? "\$tabs.tabs('select', 1);" : "";
					$JQueryReadyScripts .= ( $actual_action == "emailinvoice" ) ? "\$tabs.tabs('select', 2);" : "";
				} else {
					$page_content = notAuthorizedNotice();
				}
			} else {
				if ( user_access( 'clms_invoices_access' ) ) {
					$page_content = '	
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class=glyphicons glyphicons-table"></i> ' . __( 'Invoices' ) . '</h3>
							</div>
							<div class="box-content">
								' . printInvoicesTable( '', 0 ) . '
							</div>
						</div>';

					$JQueryReadyScripts = returnInvoicesTableJQuery( '', 0 );
				} else {
					$page_content = notAuthorizedNotice();
				}
			}
		} elseif ( $module_page == 'mydownloads' ) {
			$page->setTemplateVar( 'PageTitle', "My Downloads" );
			$page->addBreadCrumb( "My Downloads", $clmsMenus['MYDOWNLOADS']['link'] );

			if ( user_access( 'clms_mydownloads_access' ) ) {
				$page_content = '
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicon glyphicon-download"></i> ' . __( 'My Downloads' ) . '</h3>
						</div>
						<div class="box-content">
							' . printDownloadsTable( $_SESSION['userid'], 0 ) . '
						</div>
					</div>';

				$JQueryReadyScripts = returnDownloadsTableJQuery( $_SESSION['userid'], 0 );
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'myinvoices' ) {
			$page->setTemplateVar( 'PageTitle', "My Invoices" );
			$page->addBreadCrumb( "My Invoices", $clmsMenus['MYINVOICES']['link'] );

			if ( user_access( 'clms_myinvoices_access' ) ) {
				$page_content = '	
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicons glyphicons-table"></i> ' . __( 'My Invoices' ) . '</h3>
						</div>
						<div class="box-content">
							' . printInvoicesTable( $_SESSION['userid'], 0 ) . '
						</div>
					</div>';

				$JQueryReadyScripts = returnInvoicesTableJQuery( $_SESSION['userid'], 0 );
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'mynotes' ) {
			$page->setTemplateVar( 'PageTitle', "My Notes" );
			$page->addBreadCrumb( "My Notes", $clmsMenus['MYNOTES']['link'] );

			if ( user_access( 'clms_mynotes_access' ) ) {
				$page_content = '	
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicons glyphicons-notes"></i> ' . __( 'My Notes' ) . '</h3>
						</div>
						<div class="box-content">
							' . printNotesTable( $_SESSION['userid'], 0 ) . '
						</div>
					</div>';

				$JQueryReadyScripts = returnNotesTableJQuery( $_SESSION['userid'], 0 );
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'notes' ) {
			$page->setTemplateVar( 'PageTitle', "Notes" );
			$page->addBreadCrumb( "Notes", $clmsMenus['NOTES']['link'] );

			if ( user_access( 'clms_notes_access' ) ) {
				$page_content = '	
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicons glyphicons-notes"></i> ' . __( 'Notes' ) . '</h3>
						</div>
						<div class="box-content">
							' . printNewNoteForm() . '
						</div>
					</div>';

				$JQueryReadyScripts = returnNewNoteFormJQuery();
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'products' ) {
			$page->setTemplateVar( 'PageTitle', "Products" );
			$page->addBreadCrumb( "Products", $clmsMenus['PRODUCTS']['link'] );

			if ( user_access( 'clms_products_access' ) ) {
				$page_content = '
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicons glyphicons-shopping-cart"></i> ' . __( 'Products' ) . '</h3>
							<div class="toolbar">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#currentProducts" data-toggle="tab"><span>' . __( 'Current Products' ) . '</span></a></li>
									' . ( ( user_access( 'clms_products_create' ) ) ? '<li><a href="#createANewProduct" data-toggle="tab"><span>' . __( 'Create a New Product' ) . '</span></a></li>' : '' ) . '
								</ul>
							</div>
						</div>
						<div class="tab-content">
							<div id="currentProducts" class="tab-pane active">
								<div id="updateMeProducts">
									' . printProductsTable() . '
								</div>
							</div>
							' . ( ( user_access( 'clms_products_create' ) ) ? '
							<div id="createANewProduct" class="tab-pane">
								' . printNewProductForm() . '
							</div>
							' : '' ) . '
						</div>
					</div>';

				$JQueryReadyScripts .= returnProductsTableJQuery();
				if ( user_access( 'clms_products_create' ) ) {
					$JQueryReadyScripts .= returnNewProductFormJQuery( 1 );
				}
			} else {
				$page_content = notAuthorizedNotice();
			}
		}

		// Attach our content
		$page->setTemplateVar( 'PageContent', $page_content );
		$page->setTemplateVar( "JQueryReadyScript", $JQueryReadyScripts );
	}

	//====================================
	// Handle Page Result hook
	//====================================
	public function handlePageResult( $arguments ) {
		global $page, $actual_action, $actual_id, $clmsMenus;

		extract( (array) $arguments ); // Extract our arguments into variables
		$page_content = $JQueryReadyScripts = '';
		$this->prepMenus();

		// Cycle through our pages and handle the content

		// Return our content
		return $page_content;
	}

	//====================================
	// Show Page hook
	//====================================
	public function handleAJAX( $arguments = array() ) {
		global $ftsdb, $page, $mbp_config, $clmsMenus, $actual_id, $actual_action, $actual_value,
		       $actual_type, $actual_showButtons, $actual_showClient, $actual_prefix, $item, $table;

		extract( (array) $arguments ); // Extract our arguments into variables
		$this->prepMenus();
		$noIncludes = 1;

		include( BASEPATH . "/modules/$this->prefix/ajax.php" );
	}

	//====================================
	// Settings Page
	// ---
	// Show Page hook
	//====================================
	public function settingsPage( $arguments = array() ) {
		global $mbp_config;
		extract( (array) $arguments ); // Extract our arguments into variables

		$content = "";

		// Let modules alter our system settings tab fields	
		$formFields = apply_filters( 'form_fields_clms_settings', array(
			'ftsmbp_clms_serial'                  => array(
				'text' => 'Serial',
				'type' => 'text',
			),
			'ftsmbp_clms_currency_type'           => array(
				'text'    => 'System Currency',
				'type'    => 'select',
				'options' => getDropdownArray( 'currencies' ),
			),
			'ftsmbp_clms_citystateziptext_type'   => array(
				'text'    => 'System Address Region',
				'type'    => 'select',
				'options' => getDropdownArray( 'citystateziptexttype' ),
			),
			'ftsmbp_clms_sales_tax'               => array(
				'text' => 'Sales Tax',
				'type' => 'text',
			),
			array(
				'text' => 'Multiple User Settings',
				'type' => 'separator',
			),
			'ftsmbp_clms_only_access_own_clients' => array(
				'text'          => 'Users See Only Their Clients',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			),
			array(
				'text' => 'Invoice Settings',
				'type' => 'separator',
			),
			'ftsmbp_clms_invoice_company_name'    => array(
				'text' => 'Company Name',
				'type' => 'text',
			),
			'ftsmbp_clms_invoice_address'         => array(
				'text' => 'Address',
				'type' => 'text',
			),
			'ftsmbp_clms_invoice_city'            => array(
				'text' => TXT_CITY,
				'type' => 'text',
			),
			'ftsmbp_clms_invoice_state'           => array(
				'text' => TXT_STATE,
				'type' => 'text',
			),
			'ftsmbp_clms_invoice_zip'             => array(
				'text' => TXT_ZIP,
				'type' => 'text',
			),
			'ftsmbp_clms_invoice_phone_number'    => array(
				'text' => 'Phone Number',
				'type' => 'text',
			),
			'ftsmbp_clms_invoice_fax'             => array(
				'text' => 'Fax',
				'type' => 'text',
			),
			'ftsmbp_clms_invoice_email_address'   => array(
				'text' => 'Email Address',
				'type' => 'text',
			),
			'ftsmbp_clms_invoice_website'         => array(
				'text' => 'Website',
				'type' => 'text',
			),
		) );

		if ( $section == 'tabs' ) {
			$content = '
				<li><a href="#clmsSettings" data-toggle="tab"><span>' . __( 'CLMS Settings' ) . '</span></a></li>';
		} elseif ( $section == 'content' ) {
			$content = '
				<div id="clmsSettings" class="tab-pane">
					' . makeFormFieldset( 'CLMS Settings', $formFields, $mbp_config, 0 ) . '
				</div>';
		}

		return $content;
	}

	//====================================
	// Settings Page
	// ---
	// Handle Page Result hook
	//====================================
	public function settingsPage_submit( $arguments = array() ) {
		extract( (array) $arguments ); // Extract our arguments into variables

		// posted variables are in $'content

		// Handle checkboxes, unchecked boxes are not posted so we check for this and mark them in the DB as such
		if ( ! isset( $_POST['ftsmbp_clms_only_access_own_clients'] ) ) {
			update_config_value( 'ftsmbp_clms_only_access_own_clients', 0 );
		}
	}

	//====================================
	// Graphs Page
	// ---
	// Show Page hook
	//====================================
	public function graphsPage( $arguments = array() ) {
		extract( (array) $arguments ); // Extract our arguments into variables

		$content = "";

		if ( $section == 'links' ) {
			$content = '
				<li><a href="" id="' . $this->prefix . '_graphs_invoicedVsPaid">Invoiced vs Paid</a></li>
				<li><a href="" id="' . $this->prefix . '_graphs_invoicesByStatus">Invoices by Status</a></li>
				<li><a href="" id="' . $this->prefix . '_graphs_invoicesByClient">Invoices by Client Category</a></li>';
		} elseif ( $section == 'jQuery' ) {
			$content = '';
		} elseif ( $section == 'graphs' ) {
			include( BASEPATH . "/modules/$this->prefix/graphs.php" );
		}

		return $content;
	}

	//====================================
	// Reports Page
	// ---
	// Show Page hook
	//====================================
	public function reportsPage( $arguments = array() ) {
		global $menuvar;

		extract( (array) $arguments ); // Extract our arguments into variables

		$content = "";

		if ( $section == 'links' ) {
			$content = "
				<li><a href=\"" . $menuvar['VIEWREPORT'] . "&prefix=" . $this->prefix . "&report=invoiceAccountsAging\">Invoice Accounts Aging</a></li>
				<li><a href=\"" . $menuvar['VIEWREPORT'] . "&prefix=" . $this->prefix . "&report=serialNumbers\">Serial Numbers</a></li>
				<li><a href=\"" . $menuvar['VIEWREPORT'] . "&prefix=" . $this->prefix . "&report=clientDetails\">Client Details</a></li>
				<li><a href=\"" . $menuvar['VIEWREPORT'] . "&prefix=" . $this->prefix . "&report=invoices\">Invoices</a></li>
				<li><a href=\"" . $menuvar['VIEWREPORT'] . "&prefix=" . $this->prefix . "&report=invoicePayments\">Invoice Payments</a></li>";
		} elseif ( $section == 'reports' ) {
			switch ( $report ) {
				case 'invoiceAccountsAging':
					$content = printInvoiceAccountsAgingReport();
					if ( $subsection == 'jQuery' ) {
						$content = returnInvoiceAccountsAgingReportJQuery();
					}
					break;
				case 'clientDetails':
					$content = printClientDetailsReport();
					if ( $subsection == 'jQuery' ) {
						$content = returnClientDetailsReportJQuery();
					}
					break;
				case 'invoicePayments':
					$content = printInvoicePaymentsReport();
					if ( $subsection == 'jQuery' ) {
						$content = returnInvoicePaymentsReportJQuery();
					}
					break;
				case 'invoices':
					$content = printInvoicesReport();
					if ( $subsection == 'jQuery' ) {
						$content = returnInvoicesReportJQuery();
					}
					break;
				case 'serialNumbers':
					$content = printSerialNumbersReport();
					if ( $subsection == 'jQuery' ) {
						$content = returnSerialNumbersReportJQuery();
					}
					break;
			}
		}

		return $content;
	}

	//====================================
	// Dashboard Page
	// ---
	// Show Page hook
	//====================================
	public function dashboardPage( $arguments = array() ) {
		global $menuvar;

		extract( (array) $arguments ); // Extract our arguments into variables

		$content = "";

		if ( user_access( 'clms_dashboard_access' ) ) {
			if ( $section == 'content' ) {
				$content .= printAppointmentsForTodayTable() . "
					<div class=\"row\">
						<div class=\"col-sm-6\">
							" . printLargestInvoicesTable() . "
						</div>
						<div class=\"col-sm-6\">
							" . printHighestPayingClientsTable( 5 ) . "
						</div>
					</div>";
			} elseif ( $section == 'jQuery' ) {
				$content = returnAppointmentsForTodayTableJQuery() . returnLargestInvoicesTableJQuery() . returnHighestPayingClientsTableJQuery();
			}
		}

		return $content;
	}
}