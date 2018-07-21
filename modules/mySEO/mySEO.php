<?php
/***************************************************************************
 *                               mySEO.php
 *                            -------------------
 *   begin                : Wednesday, November 26, 2008
 *   copyright            : (C) 2008 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/


class mySEO {
	//====================================
	// Basic Module information
	//====================================
	public $name = "My SEO Management Software";
	public $description = "Adds a SEO Management Console to your system.";
	public $developer = "Paden Clayton";
	public $version = "1.17.07.12";
	private $prefix = "mySEO";

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
		global $page;
		$myFolder = SITE_URL . "/modules/$this->prefix/";

		// Add stylsheet files
		$page->addStyle( $myFolder . 'style.css' );

		// Add script files
		$page->addScript( $myFolder . 'javascripts/functions.js' );
	}

	//====================================
	// Install hook
	//====================================
	public function install() {
		global $ftsdb, $mySEOTasks, $mbp_config;

		installModule( $this->prefix, $this->name, $this->description, $this->developer, $this->version );

		include_once( BASEPATH . '/modules/' . $this->prefix . '/includes/menu.php' );
		include_once( BASEPATH . '/modules/' . $this->prefix . '/includes/constants.php' );

		// Add Menus
		if ( count( $mySEOUserMenuItems ) > 0 ) {
			foreach ( $mySEOUserMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'], $menuItemArray['icon'] );
				}
			}
		}
		if ( count( $mySEOAdminMenuItems ) > 0 ) {
			foreach ( $mySEOAdminMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'], $menuItemArray['icon'] );
				}
			}
		}

		// Create our tables
		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "seo_clients` (
				`id` int(11) NOT NULL auto_increment,
				`name` varchar(255) DEFAULT NULL,
				`url` varchar(255) DEFAULT NULL,
				`email_address` varchar(255) DEFAULT NULL,
				`phone` varchar(255) DEFAULT NULL,
				`created_on` DATETIME,
				`status` tinyint(1) NOT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  AUTO_INCREMENT=1;";
		$result = $ftsdb->run( $sql );

		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "seo_tasks` (
				`id` int(11) NOT NULL auto_increment,
				`cat_id` mediumint(8) NOT NULL,
				`title` varchar(255) DEFAULT NULL,
				`description` longtext,
				`how_to` longtext,
				`effort` smallint(3) NOT NULL,
				`impact` smallint(3) NOT NULL,
				`weight` smallint(3) NOT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  AUTO_INCREMENT=1;";
		$result = $ftsdb->run( $sql );

		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "seo_clients_tasks` (
				`id` int(11) NOT NULL auto_increment,
				`client_id` mediumint(8) NOT NULL,
				`todo_id` mediumint(8) NOT NULL,
				`status` tinyint(1) NOT NULL DEFAULT 1,
				`notes` longtext,
				`date` DATETIME,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  AUTO_INCREMENT=1;";
		$result = $ftsdb->run( $sql );

		// Fill the tables from our constants
		if ( count( $mySEOTasks ) ) {
			foreach ( $mySEOTasks as $catID => $tasks ) {
				foreach ( $tasks as $key => $task ) {
					//echo "adding " . $task['title'] . "<br />";
					$result = $ftsdb->insert( DBTABLEPREFIX . 'seo_tasks', array(
						"cat_id"      => $catID,
						"title"       => $task['title'],
						"description" => $task['description'],
						"how_to"      => $task['howTo'],
						"effort"      => $task['effort'],
						"impact"      => $task['impact'],
						"weight"      => $task['weight'],
					) );
				}
			}
		}

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
		updateModule( $this->prefix, $this->name, $this->description, $this->developer, $this->version );

		// Check our permissions and config items
		callModuleHook( $this->prefix, 'checkDBSettings' );
	}

	//====================================
	// Return Includes hook
	//====================================
	public function returnIncludes() {
		global $mbp_config;

		return BASEPATH . '/modules/mySEO/includes/constants.php;' . BASEPATH . '/modules/mySEO/includes/functions.php;' . BASEPATH . '/modules/mySEO/includes/menu.php;';
	}

	//===============================================================
	// Prep Settings hook
	//===============================================================
	public function prepSettings() {
		global $page, $mySEOUserMenuItems, $mySEOAdminMenuItems, $mySEOMenus;
	}

	//===============================================================
	// Check DB Settings hook
	//===============================================================
	public function checkDBSettings() {
		global $ftsdb;

		$defaultPermissions = array(
			'mySEO_mySEO_access'             => '2,',
			'mySEO_mySEO_create'             => '0,2,9',
			'mySEO_mySEO_delete'             => '2,',
			'mySEO_mySEO_access_all_clients' => '2,',
		);

		if ( count( $defaultPermissions ) ) {
			foreach ( $defaultPermissions as $name => $role_ids ) {
				if ( ! permision_setting_exists( $name ) ) {
					add_permision_setting( $name, $role_ids );
				}
			}
		}

		// Add any new settings
		$defaultSettings = array(
			'ftsmbp_mySEO_citystateziptext_type'  => '0',
			'ftsmbp_mySEO_report_company_name'    => '',
			'ftsmbp_mySEO_report_address'         => '',
			'ftsmbp_mySEO_report_city'            => '',
			'ftsmbp_mySEO_report_state'           => '',
			'ftsmbp_mySEO_report_zip'             => '',
			'ftsmbp_mySEO_report_phone_number'    => '',
			'ftsmbp_mySEO_report_fax'             => '',
			'ftsmbp_mySEO_report_email_address'   => '',
			'ftsmbp_mySEO_report_website'         => '',
			'ftsmbp_mySEO_pdf_use_free_version'   => '1',
			'ftsmbp_mySEO_pdf_pdf_crowd_username' => '',
			'ftsmbp_mySEO_pdf_pdf_crowd_api_key'  => '',
		);

		if ( count( $defaultSettings ) ) {
			foreach ( $defaultSettings as $name => $value ) {
				if ( ! config_value_exists( $name ) ) {
					add_config_value( $name, $value );
				}
			}
		}
	}

	//====================================
	// Prep Menus hook
	//====================================
	public function prepMenus() {
		global $page, $mySEOUserMenuItems, $mySEOAdminMenuItems, $mySEOMenus;
		$myFolder = "modules/$this->prefix/";

		if ( count( $mySEOUserMenuItems ) ) {
			foreach ( $mySEOUserMenuItems as $key => $menuItemArray ) {
				$mySEOMenus[ $key ] = $menuItemArray;
			}
		}
		if ( count( $mySEOAdminMenuItems ) ) {
			foreach ( $mySEOAdminMenuItems as $key => $menuItemArray ) {
				$mySEOMenus[ $key ] = $menuItemArray;
			}
		}
	}

	//====================================
	// Get Dropdown Array hook
	//====================================
	public function getDropdownArray( $arguments = array() ) {
		global $ftsdb, $page, $mySEOMenus, $TASK_REPORT_TYPES;
		extract( (array) $arguments ); // Extract our arguments into variables

		$returnArray = array();

		if ( $type == "citystateziptexttype" ) {
			$returnArray = $returnArray + $this->CITYSTATEZIP_TYPE; // Preserve numberic keys by not using array_merge
		} elseif ( $type == "typeOfSEOTasksReports" ) {
			$returnArray = array_merge( $returnArray, $TASK_REPORT_TYPES );
		} else if ( $type == "seo_clients" ) {
			$result = $ftsdb->select( DBTABLEPREFIX . "seo_clients", "status = 3 ORDER BY url", array(), 'id, url' );

			if ( $result ) {
				foreach ( $result as $row ) {
					$returnArray[ $row['id'] ] = $row['url'];
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
		global $ftsdb, $page, $mySEOMenus, $actual_action, $actual_id, $actual_startsWith;
		//ini_set('display_errors', '1');

		extract( $arguments ); // Extract our arguments into variables
		$page_content = $JQueryReadyScripts = '';
		$this->prepMenus();

		// Cycle through our pages and handle the content
		if ( $module_page == 'seo_clients' ) {
			$page->setTemplateVar( "PageTitle", 'SEO Clients' );
			$page->addBreadCrumb( 'SEO Clients', $mySEOMenus['SEOCLIENTS']['link'] );

			if ( user_access( 'mySEO_seo_clients_access' ) ) {
				//==================================================
				// Handle editing seo_clients
				//==================================================	
				if ( $actual_action == "editSEOClient" && isset( $actual_id ) && user_access( 'mySEO_seo_clients_edit' ) ) {
					// Add breadcrumb
					$page->addBreadCrumb( "Edit SEO Client", "" );

					$page_content = '	
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicons glyphicons-globe"></i> ' . __( 'Edit SEO Client' ) . '</h3>
								<div class="toolbar">
									<ul class="nav nav-pills">
										<li class="active"><a href="#clientDetails" data-toggle="tab"><span>' . __( 'Client Details' ) . '</span></a></li>
									</ul>
								</div>
							</div>
							<div class="tab-content">
								<div id="clientDetails" class="tab-pane active">
									' . printEditSEOClientForm( $actual_id ) . '
								</div>
							</div>
						</div>';

					// Handle our JQuery needs
					$JQueryReadyScripts = returnEditSEOClientFormJQuery( $actual_id );

				} elseif ( $actual_action == "clientSEODashboard" && isset( $actual_id ) && user_access( 'mySEO_seo_clients_tasks' ) ) {
					//==================================================
					// Handle client tasks
					//==================================================
					// Pull the client name so we can reference it
					$client     = getSEOClient( $actual_id );
					$clientName = $client['name'];

					// Add breadcrumb
					$page->addBreadCrumb( "Client SEO Dashboard $clientName", "" );

					$page_content = '	
						<div class="box">
							<div class="box-header">
								' . printSEOClientSEOTasksStatusFilter( $actual_id ) . '
								<h3><i class="glyphicon glyphicon-tasks"></i> ' . __( 'Client SEO Dashboard - ' ) . $clientName . '</h3>
							</div>
							<div class="box-content">
								<div class="row">
									<div class="col-sm-4">
										' . printSEOClientSEOTasksTreeview() . '
									</div>
									<div class="col-sm-8">
										' . printSEOClientSEOTasks( $actual_id, 0 ) . '
									</div>
								</div>
							</div>
						</div>';

					// Handle our JQuery needs
					$JQueryReadyScripts = returnSEOClientSEOTasksJQuery( $actual_id ) . returnSEOClientSEOTasksTreeviewJQuery( $actual_id );

				} else {
					//==================================================
					// Print out our seo_clients table
					//==================================================
					$page_content = displayMySEOWelcomeScreen() . displayMySEOSettingsAlert() . '	
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicons glyphicons-globe"></i> ' . __( 'SEO Clients' ) . '</h3>
								<div class="toolbar">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#currentSEOClients" data-toggle="tab"><span>' . __( 'Current SEO Clients' ) . '</span></a></li>
										' . ( ( user_access( 'mySEO_seo_clients_create' ) ) ? '<li><a href="#addAClient" data-toggle="tab"><span>' . __( 'Add a Client' ) . '</span></a></li>' : '' ) . '
									</ul>
								</div>
							</div>
							<div class="tab-content">
								<div id="currentSEOClients" class="tab-pane active">
									<p>Listed below are your current clients. From this page you can manage your clients, get a quick overview of their optimization level and access reporting.<p>
									<br />
									<div id="updateMeSEOClients">
										' . printSEOClientsTable() . '
									</div>
								</div>
								' . ( ( user_access( 'mySEO_seo_clients_create' ) ) ? '
								<div id="addAClient" class="tab-pane">
									' . (
							( ! canHaveMultipleSEOClients() && getSEOClientCount() > 0 )
								? return_error_alert( 'Your license only allows for 1 SEO client. To add additional clients you need to upgrade to a paid license. <a href="https://www.fasttracksites.com/product/license-renewal/">Click here to purchase a new license.</a>' )
								: printNewSEOClientForm()
							) . '
								</div>
								' : '' ) . '
							</div>
						</div>';


					$JQueryReadyScripts .= returnSEOClientsTableJQuery();
					if ( user_access( 'mySEO_seo_clients_create' ) ) {
						$JQueryReadyScripts .= returnNewSEOClientFormJQuery( 1 );
					}
				}
			} else {
				$page_content = notAuthorizedNotice();
			}
		}

		// Attach our content
		$page->setTemplateVar( "PageContent", $page_content );
		$page->setTemplateVar( "JQueryReadyScript", $JQueryReadyScripts );
	}

	//====================================
	// Handle Page Result hook
	//====================================
	public function handlePageResult( $arguments ) {
		global $page, $actual_action, $actual_id, $mySEOMenus;

		extract( $arguments ); // Extract our arguments into variables
		$page_content = $JQueryReadyScripts = '';
		$this->prepMenus();

		// Return our content
		return $page_content;
	}

	//====================================
	// Show Page hook
	//====================================
	public function handleAJAX( $arguments = array() ) {
		global $ftsdb, $page, $mbp_config, $mySEOMenus, $actual_id, $actual_action, $actual_value,
		       $actual_type, $actual_showButtons, $actual_showClient, $actual_prefix, $item, $table,
		       $TASK_STATUS, $mySEOCats, $mySEOTasks;

		extract( $arguments ); // Extract our arguments into variables
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
		extract( $arguments ); // Extract our arguments into variables

		$content = "";

		// Let modules alter our system settings tab fields	
		$formFields = apply_filters( 'form_fields_mySEO_settings', array(
			'ftsmbp_mySEO_serial'                 => array(
				'text' => 'Serial Number',
				'type' => 'text',
			),
			'ftsmbp_mySEO_citystateziptext_type'  => array(
				'text'    => 'System Address Region',
				'type'    => 'select',
				'options' => getDropdownArray( 'citystateziptexttype' ),
			),
			array(
				'text' => 'Report Settings',
				'type' => 'separator',
			),
			'ftsmbp_mySEO_report_company_name'    => array(
				'text' => 'Company Name',
				'type' => 'text',
			),
			'ftsmbp_mySEO_report_address'         => array(
				'text' => 'Address',
				'type' => 'text',
			),
			'ftsmbp_mySEO_report_city'            => array(
				'text' => TXT_CITY,
				'type' => 'text',
			),
			'ftsmbp_mySEO_report_state'           => array(
				'text' => TXT_STATE,
				'type' => 'text',
			),
			'ftsmbp_mySEO_report_zip'             => array(
				'text' => TXT_ZIP,
				'type' => 'text',
			),
			'ftsmbp_mySEO_report_phone_number'    => array(
				'text' => 'Phone Number',
				'type' => 'text',
			),
			'ftsmbp_mySEO_report_fax'             => array(
				'text' => 'Fax',
				'type' => 'text',
			),
			'ftsmbp_mySEO_report_email_address'   => array(
				'text' => 'Email Address',
				'type' => 'text',
			),
			'ftsmbp_mySEO_report_website'         => array(
				'text' => 'Website',
				'type' => 'text',
			),
			array(
				'text' => 'PDF Settings',
				'type' => 'separator',
			),
			array(
				'value' => '<span class="help-block">We utilize <a href="http://pdfcrowd.com">PDFCrowd</a> to create great looking PDF reports. They offer a free version with a watermark and a paid version.</span>',
				'type'  => 'html',
			),
			'ftsmbp_mySEO_pdf_use_free_version'   => array(
				'text'          => 'Use Free Version',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			),
			'ftsmbp_mySEO_pdf_pdf_crowd_username' => array(
				'text' => 'PDFCrowd Username',
				'type' => 'text',
			),
			'ftsmbp_mySEO_pdf_pdf_crowd_api_key'  => array(
				'text' => 'PDFCrowd API Key',
				'type' => 'text',
			),
		) );

		if ( $section == 'tabs' ) {
			$content = '
				<li><a href="#mySEOSettings" data-toggle="tab"><span>' . __( 'MySEO Settings' ) . '</span></a></li>';
		} elseif ( $section == 'content' ) {
			$content = '
				<div id="mySEOSettings" class="tab-pane">
					' . makeFormFieldset( 'MySEO Settings', $formFields, $mbp_config, 0 ) . '
				</div>';
		} elseif ( $section == 'jquery' ) {
			$content = "
				 // Handle the pdf crowd toggle button and trigger it right off the bat so we are handling the current value
		        $('input[name=\"ftsmbp_mySEO_pdf_use_free_version\"]').on('switchChange.bootstrapSwitch', function(event, state) {
		        	if ( state ) {
			            $('#ftsmbp_mySEO_pdf_pdf_crowd_username').closest('.form-group').hide();
		                $('#ftsmbp_mySEO_pdf_pdf_crowd_api_key').closest('.form-group').hide();
		            } else {
		                $('#ftsmbp_mySEO_pdf_pdf_crowd_username').closest('.form-group').show();
		                $('#ftsmbp_mySEO_pdf_pdf_crowd_api_key').closest('.form-group').show();
			        }
				}).trigger('switchChange.bootstrapSwitch');";
		}

		return $content;
	}

	//====================================
	// Settings Page
	// ---
	// Handle Page Result hook
	//====================================
	public function settingsPage_submit( $arguments = array() ) {
		extract( $arguments ); // Extract our arguments into variables

		// posted variables are in $'content

		// Handle checkboxes, unchecked boxes are not posted so we check for this and mark them in the DB as such
		$togglesAndChecks = array(
			'ftsmbp_mySEO_pdf_use_free_version',
		);
		foreach ( $togglesAndChecks as $key ) {
			if ( ! isset( $_POST[ $key ] ) ) {
				update_config_value( $key, 0 );
			}
		}
	}

	//====================================
	// cronTasks hook
	//====================================
	public function cronTasks( $arguments = array() ) {
		global $page;
		extract( $arguments ); // Extract our arguments into variables

		$log = "";

		// Run our individual cron functions

		return $log;
	}

	//====================================
	// Graphs Page
	// ---
	// Show Page hook
	//====================================
	public function graphsPage( $arguments = array() ) {
		global $page, $menuvar, $mySEOUserMenuItems, $mySEOAdminMenuItems, $mySEOMenus;
		extract( $arguments ); // Extract our arguments into variables

		$content = "";

		if ( $section == 'links' ) {
		} elseif ( $section == 'jQuery' ) {
		} elseif ( $section == 'graphs' ) {
			//include(BASEPATH . "/modules/$this->prefix/graphs.php");	
		}

		return $content;
	}

	//====================================
	// Reports Page
	// ---
	// Show Page hook
	//====================================
	public function reportsPage( $arguments = array() ) {
		global $page, $menuvar, $mySEOMenus, $actual_id;

		extract( $arguments ); // Extract our arguments into variables

		$content = "";

		if ( $section == 'links' ) {
			$content = '
				' . ( ( user_access( 'mySEO_reports_mySEOTasks_access' ) ) ? '<li><a href="' . $menuvar['VIEWREPORT'] . '&prefix=' . $this->prefix . '&report=mySEOTasks">MySEO Tasks Report</a></li>' : '' );
		} elseif ( $section == 'reports' ) {
			switch ( $report ) {
				case 'mySEOTasks':
					if ( ! user_access( 'mySEO_reports_mySEOTasks_access' ) ) {
						break;
					}

					$content = displayMySEOSettingsAlert() . printMySEOTasksReportForm( $actual_id );
					if ( $subsection == 'jQuery' ) {
						$content = returnMySEOTasksReportFormJQuery();
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
		global $page, $menuvar, $mySEOMenus;

		extract( $arguments ); // Extract our arguments into variables

		$content = "";

		if ( user_access( 'mySEO_dashboard_access' ) ) {
		}

		return $content;
	}
}