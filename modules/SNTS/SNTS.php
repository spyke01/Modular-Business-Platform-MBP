<?php
/***************************************************************************
 *                               SNTS.php
 *                            -------------------
 *   begin                : Wednesday, November 26, 2008
 *   copyright            : (C) 2008 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/


class SNTS {
	//====================================
	// Basic Module information
	//====================================
	public $name = "Serial Number Tracker System";
	public $description = "Adds the FTS SNTS capabilities to your system.";
	public $developer = "Paden Clayton";
	public $version = "2.17.07.12";
	public $updateRequestURL = '';
	private $prefix = "SNTS";

	//===============================================================
	// Our class constructor
	//===============================================================
	public function __construct() {
		global $page, $mbp_config;
		$myFolder = "modules/$this->prefix/";

		// Add stylsheet files
		//$page->addStyle($myFolder . 'style.css');

		// Add script files
		//$page->addScript($myFolder . 'javascripts/functions.js');

		// Set our updateRequestURL
		$this->updateRequestURL = 'https://www.fasttracksites.com/versions/serialChecker.php?response=json&app=module_' . $this->prefix . '&serial=' . $mbp_config['ftsmbp_snts_serial'];
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
		if ( count( $sntsUserMenuItems ) ) {
			foreach ( $sntsUserMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'] );
				}
			}
		}
		if ( count( $sntsAdminMenuItems ) ) {
			foreach ( $sntsAdminMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'] );
				}
			}
		}

		// Create database tables
		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "serials` (
				`id` bigint(16) NOT NULL auto_increment,
				`client_id` mediumint(8) NOT NULL,
				`cat_id` mediumint(8) NOT NULL,
				`serial` varchar(255) NOT NULL,
				`description` text,
				`location` text,
				`owner` text,
				`added_by` varchar(255) DEFAULT NULL,
				`datetimestamp` varchar(255) DEFAULT NULL,
				`expires` varchar(255) DEFAULT NULL,
				PRIMARY KEY  (`id`),
				KEY `client_id` (`client_id`)
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
			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "serials`
				CHANGE `description` `description` text,
				CHANGE `location` `location` text,
				CHANGE `owner` `owner` text,
				CHANGE `added_by` `added_by` varchar(255) DEFAULT NULL,
				CHANGE `datetimestamp` `datetimestamp` varchar(255) DEFAULT NULL,
				ADD INDEX (`client_id`),
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
			$result = $ftsdb->run( $sql );
		}

		// Version 2.14.05.30
		if ( $modules[ $this->prefix ]->version <= '2.14.05.30' ) {
			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "serials` ADD `expires` varchar(255) DEFAULT NULL";
			$result = $ftsdb->run( $sql );
		}
	}

	//====================================
	// Return Includes hook
	//====================================
	public function returnIncludes() {
		return BASEPATH . '/modules/SNTS/includes/constants.php;' . BASEPATH . '/modules/SNTS/includes/functions.php;' . BASEPATH . '/modules/SNTS/includes/menu.php;';
	}

	//====================================
	// Prep Menus hook
	//====================================
	public function prepMenus() {
		global $page, $sntsUserMenuItems, $sntsAdminMenuItems, $sntsMenus;
		$myFolder = "modules/$this->prefix/";

		if ( count( $sntsUserMenuItems ) ) {
			foreach ( $sntsUserMenuItems as $key => $menuItemArray ) {
				$sntsMenus[ $key ] = $menuItemArray;
			}
		}
		if ( count( $sntsAdminMenuItems ) ) {
			foreach ( $sntsAdminMenuItems as $key => $menuItemArray ) {
				$sntsMenus[ $key ] = $menuItemArray;
			}
		}
	}

	//===============================================================
	// Check DB Settings hook
	//===============================================================
	public function checkDBSettings() {
		global $sntsUserMenuItems, $sntsAdminMenuItems, $ftsdb;

		$defaultPermissions = array(// 'clms_appointments_access' => '2,',
		);

		if ( count( $defaultPermissions ) ) {
			foreach ( $defaultPermissions as $name => $role_ids ) {
				if ( ! permision_setting_exists( $name ) ) {
					add_permision_setting( $name, $role_ids );
				}
			}
		}

		$defaultSettings = array(
			'ftsmbp_snts_useClientAsOwner' => '1'
		);

		if ( count( $defaultSettings ) ) {
			foreach ( $defaultSettings as $name => $value ) {
				if ( ! config_value_exists( $name ) ) {
					add_config_value( $name, $value );
				}
			}
		}

		// Add icons if necessary
		foreach ( array_merge( (array) $sntsUserMenuItems, (array) $sntsAdminMenuItems ) as $key => $menuItemArray ) {
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

		if ( $type == "serialcategories" ) {
			$result = $ftsdb->select( DBTABLEPREFIX . "categories", "type='6' ORDER BY name", array(), 'id, name' );

			if ( $result ) {
				foreach ( $result as $row ) {
					$returnArray[ $row['id'] ] = $row['name'];
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
		global $page, $actual_action, $actual_id, $sntsMenus;

		extract( (array) $arguments ); // Extract our arguments into variables
		$page_content = $JQueryReadyScripts = '';
		$this->prepMenus();
		$actual_startsWith = keepsafe( $_GET['startsWith'] );

		// Cycle through our pages and handle the content
		if ( $module_page == 'serials' ) {
			$page->setTemplateVar( 'PageTitle', "Serials" );
			$page->addBreadCrumb( "Serials", $sntsMenus['SERIALS']['link'] );

			if ( user_access( 'snts_serials_access' ) ) {
				if ( $actual_action == "editserial" && isset( $actual_id ) && user_access( 'snts_serials_edit' ) ) {
					// Add breadcrumb
					$page->setTemplateVar( 'PageTitle', "Edit Serial" );
					$page->addBreadCrumb( "Edit Serial", "" );

					$page_content .= '		
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicons glyphicons-keys"></i> ' . __( 'Edit Serial' ) . '</h3>
							</div>
							<div class="box-content">
								<div id="serialDetails">
									<div id="updateMeSerials">
										' . printEditSerialForm( $actual_id ) . '
									</div>
								</div>
							</div>
						</div>';

					// Handle our JQuery needs
					$JQueryReadyScripts = returnEditSerialFormJQuery( $actual_id );
				} elseif ( $actual_action == "importserials" && user_access( 'snts_serials_import' ) ) {
					// Add breadcrumb
					$page->setTemplateVar( 'PageTitle', "Import Serials" );
					$page->addBreadCrumb( "Import Serials", $sntsMenus['SERIALS']['link'] . '&action=importserials' );

					if ( isset( $_POST['submit'] ) ) {
						// Call updating hook
						$page_content = $this->handlePageResult( $arguments );
					} else {
						$page_content .= '		
							<div class="box tabbable">
								<div class="box-header">
									<h3><i class="glyphicons glyphicons-file_import"></i> ' . __( 'Import Serials' ) . '</h3>
								</div>
								<div class="box-content">
									<div id="importSerials">
										' . printImportSerialsForm() . '
									</div>
								</div>
							</div>';

						// Handle our JQuery needs
						$JQueryReadyScripts = returnImportSerialsFormJQuery();
					}
				} else {
					//==================================================
					// Print out our serials table
					//==================================================
					$page_content = '	
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicons glyphicons-keys"></i> ' . __( 'Serials' ) . '</h3>
								<div class="toolbar">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#currentSerials" data-toggle="tab"><span>Current Serials</span></a></li>
										' . ( ( user_access( 'snts_serials_import' ) ) ? '<li><a href="#importSerials" data-toggle="tab"><span>Import Serials</span></a></li>' : '' ) . '
										' . ( ( user_access( 'snts_serials_create' ) ) ? '<li><a href="#addANewSerial" data-toggle="tab"><span>Add a New Serial</span></a></li>' : '' ) . '
									</ul>
								</div>
							</div>
							<div class="tab-content">
								<div id="currentSerials" class="tab-pane active">
									' . printSearchSerialsForm( $_POST ) . '
									' . ( ( user_access( 'snts_serials_generate_csv' ) ) ? '<span class="pull-right"><a href="' . SITE_URL . '/ajax.php?action=generateSerialsCSV" class="btn btn-info"><i class="glyphicons glyphicons-file-export"></i> Export CSV</a></span><br /><br />' : '' ) . '
									<div id="updateMeSerials">
										' . printSerialsTables( '', $_POST ) . '
									</div>
								</div>
								' . ( ( user_access( 'snts_serials_import' ) ) ? '
								<div id="importSerials" class="tab-pane">
									' . printImportSerialsForm() . '
								</div>
								' : '' ) . '
								' . ( ( user_access( 'snts_serials_create' ) ) ? '
								<div id="addANewSerial" class="tab-pane">
									' . printNewSerialForm() . '
								</div>
								' : '' ) . '
							</div>
						</div>';

					$JQueryReadyScripts .= returnSearchSerialsFormJQuery() . returnSerialsTableJQuery();
					if ( user_access( 'snts_serials_import' ) ) {
						$JQueryReadyScripts .= returnImportSerialsFormJQuery();
					}
					if ( user_access( 'snts_serials_create' ) ) {
						$JQueryReadyScripts .= returnNewSerialFormJQuery( 1 );
					}
				}
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'clmsEditClientExtraTabs' && $mbp_config['ftsmbp_snts_useClientAsOwner'] ) {
			if ( user_access( 'tts_tickets_access' ) ) {
				if ( $section == 'tabs' ) {
					$page_content = '
						<li><a href="#serials" data-toggle="tab"><span>Serials</span></a></li>';
				} elseif ( $section == 'content' ) {
					$page_content = '
						<div id="serials" class="tab-pane">
							<div id="updateMeSerials">
								' . printSerialsTables( $content['id'] ) . '
							</div>
							' . ( ( user_access( 'snts_serials_create' ) ) ? '
							<br /><br />
							<div id="addANewSerial">
								' . printNewSerialForm( $content['id'] ) . '
							</div>
							' : '' ) . '
						</div>';
				} elseif ( $section == 'jQuery' ) {
					$page_content = returnSerialsTableJQuery();
					if ( user_access( 'snts_serials_create' ) ) {
						$page_content .= returnNewSerialFormJQuery( 1 );
					}
				}

				return $page_content;
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
		global $ftsdb, $page, $actual_action, $actual_id, $sntsMenus;

		extract( (array) $arguments ); // Extract our arguments into variables
		$page_content = $JQueryReadyScripts = '';
		$this->prepMenus();

		// Cycle through our pages and handle the content
		if ( $module_page == 'serials' ) {
			if ( $actual_action == "importserials" && user_access( 'snts_serials_import' ) ) {
				$uploadedFile = $_FILES['importFile']['tmp_name'];
				$extension    = strrchr( $_FILES['importFile']['name'], '.' );
				$errors       = 0;
				$errorNotice  = '';

				// Make sure we upload a CSV file
				if ( strtolower( $extension ) == ".csv" ) {
					if ( ( $handle = fopen( $uploadedFile, "r" ) ) !== false ) {
						$firstRowDone = 0;
						while ( ( $data = fgetcsv( $handle, 1000, "," ) ) !== false ) {
							if ( $firstRowDone ) {
								// Insert keyword
								$serial        = keeptasafe( $data[0] );
								$description   = keeptasafe( $data[1] );
								$location      = keeptasafe( $data[2] );
								$owner         = keeptasafe( $data[3] );
								$added_by      = keeptasafe( $data[4] );
								$datetimestamp = strtotime( keeptasafe( $data[5] ) );
								$expires       = strtotime( keeptasafe( $data[6] ) );

								if ( ! empty( $data[0] ) ) {
									$result    = $ftsdb->insert( DBTABLEPREFIX . 'serials', array(
										"serial"        => $serial,
										"description"   => $description,
										"location"      => $location,
										"owner"         => $owner,
										"added_by"      => $added_by,
										"datetimestamp" => $datetimestamp,
										"expires"       => $expires,
									) );
									$errors    += ( $result ) ? 0 : 1;
									$keywordID = $ftsdb->lastInsertId();
									if ( ! $result ) {
										$errorNotice .= "<br />$serial";
									}
								}
							}
							$firstRowDone = 1;
						}
						fclose( $handle );
					}
					if ( ! empty( $errorNotice ) ) {
						$errorNotice = "<br /><div class=\"alert alert-danger\"><strong>The following serials failed to import:" . $errorNotice . "</strong></div>";
					}
				} else {
					$errors      = 1;
					$errorNotice = "<br /><div class=\"alert alert-danger\"><strong>The uploaded files was not a CSV file!</strong></div>";
				}

				if ( $errors == 0 ) {
					$page_content .= '
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicons glyphicons-file-import"></i> ' . __( 'Import Serials' ) . '</h3>
							</div>
							<div class="box-content">
								<div id="result">
									<h2>' . __( 'Success' ) . '</h2>
									' . __( 'Your serials were successfully imported. You are being redirected to the main page.' ) . '
									<meta http-equiv="refresh" content="1;url=' . $sntsMenus['SERIALS']['link'] . '">
								</div>
							</div>
						</div>';
				} else {
					$page_content .= '
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicons glyphicons-file-import"></i> ' . __( 'Import Serials' ) . '</h3>
							</div>
							<div class="box-content">
								<div id="result">
									<h2>' . __( 'Failure' ) . '</h2>
									' . __( 'There was an error while importing your serials. You are being redirected to the main page.' ) . '
									' . $errorNotice . '
									<http-equiv="refresh" content="5;url=' . $sntsMenus['SERIALS']['link'] . '">
								</div>
							</div>
						</div>';
				}
			}
		}

		// Return our content
		return $page_content;
	}

	//====================================
	// Show Page hook
	//====================================
	public function handleAJAX( $arguments = array() ) {
		global $ftsdb, $page, $mbp_config, $sntsMenus, $actual_id, $actual_action, $actual_value,
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
		$formFields = apply_filters( 'form_fields_snts_settings', array(
			'ftsmbp_snts_serial'           => array(
				'text' => 'Serial',
				'type' => 'text',
			),
			'ftsmbp_snts_useClientAsOwner' => array(
				'text'          => 'Use Client as Owner',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			),
		) );

		if ( $section == 'tabs' ) {
			$content = '
				<li><a href="#sntsSettings" data-toggle="tab"><span>' . __( 'SNTS Settings' ) . '</span></a></li>';
		} elseif ( $section == 'content' ) {
			$content = '
				<div id="sntsSettings" class="tab-pane">
					' . makeFormFieldset( 'SNTS Settings', $formFields, $mbp_config, 0 ) . '
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
		global $ftsdb;

		extract( (array) $arguments ); // Extract our arguments into variables

		// posted variables are in $'content

		// Handle checkboxes, unchecked boxes are not posted so we check for this and mark them in the DB as such
		if ( ! isset( $_POST['ftsmbp_snts_useClientAsOwner'] ) ) {
			update_config_value( 'ftsmbp_snts_useClientAsOwner', 0 );
		}
	}

	//====================================
	// Graphs Page
	// ---
	// Show Page hook
	//====================================
	public function graphsPage( $arguments = array() ) {
		global $page, $menuvar, $sntsMenus;
		extract( (array) $arguments ); // Extract our arguments into variables

		$content = "";

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
				<li><a href=\"" . $menuvar['VIEWREPORT'] . "&prefix=" . $this->prefix . "&report=serials\">Serials</a></li>";
		} elseif ( $section == 'reports' ) {
			switch ( $report ) {
				case 'serials':
					$content = printSerialsReport();
					if ( $subsection == 'jQuery' ) {
						$content = returnSerialsReportJQuery();
					}
					break;
			}
		}

		return $content;
	}
}