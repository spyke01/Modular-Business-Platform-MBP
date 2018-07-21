<?php

/***************************************************************************
 *                               FTS.php
 *                            -------------------
 *   begin                : Wednesday, November 26, 2008
 *   copyright            : (C) 2008 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************
 * /
class FTS {
	//====================================
	// Basic Module information
	//====================================
	public $name = "Version Number Tracker System";
	public $description = "Adds the FTS VNTS capabilities to your system.";
	public $developer = "Paden Clayton";
	public $version = "2.17.07.12";
	private $prefix = "FTS";

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
		$this->updateRequestURL = 'https://www.fasttracksites.com/versions/serialChecker.php?response=json&app=module_' . $this->prefix . '&serial=' . $mbp_config['ftsmbp_vnts_serial'];
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
		if ( count( $ftsUserMenuItems ) ) {
			foreach ( $ftsUserMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'] );
				}
			}
		}
		if ( count( $ftsAdminMenuItems ) ) {
			foreach ( $ftsAdminMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'] );
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
		return BASEPATH . '/modules/FTS/includes/constants.php;' . BASEPATH . '/modules/FTS/includes/functions.php;' . BASEPATH . '/modules/FTS/includes/menu.php;';
	}

	//====================================
	// Prep Menus hook
	//====================================
	public function prepMenus() {
		global $page, $ftsUserMenuItems, $ftsAdminMenuItems, $ftsMenus;
		$myFolder = "modules/$this->prefix/";

		if ( count( $ftsUserMenuItems ) ) {
			foreach ( $ftsUserMenuItems as $key => $menuItemArray ) {
				$ftsMenus[ $key ] = $menuItemArray;
			}
		}
		if ( count( $ftsAdminMenuItems ) ) {
			foreach ( $ftsAdminMenuItems as $key => $menuItemArray ) {
				$ftsMenus[ $key ] = $menuItemArray;
			}
		}
	}

	//===============================================================
	// Check DB Settings hook
	//===============================================================
	public function checkDBSettings() {
		global $ftsUserMenuItems, $ftsAdminMenuItems, $ftsdb;

		$defaultPermissions = array(// 'clms_appointments_access' => '2,',
		);

		if ( count( $defaultPermissions ) ) {
			foreach ( $defaultPermissions as $name => $role_ids ) {
				if ( ! permision_setting_exists( $name ) ) {
					add_permision_setting( $name, $role_ids );
				}
			}
		}

		$defaultSettings = array(//'ftsmbp_fts_useClientAsOwner' => '1'
		);

		if ( count( $defaultSettings ) ) {
			foreach ( $defaultSettings as $name => $value ) {
				if ( ! config_value_exists( $name ) ) {
					add_config_value( $name, $value );
				}
			}
		}

		// Add icons if necessary
		foreach ( array_merge( (array) $ftsUserMenuItems, (array) $ftsAdminMenuItems ) as $key => $menuItemArray ) {
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

		if ( $type == "versiontypes" ) {
			$returnArray = $returnArray + array(
					0 => 'Free',
					1 => 'Professional',
				); // Preserve numberic keys by not using array_merge
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
		global $page, $ftsMenus, $actual_action, $actual_id, $actual_startsWith;

		extract( (array) $arguments ); // Extract our arguments into variables
		$page_content = $JQueryReadyScripts = '';
		$this->prepMenus();

		// Cycle through our pages and handle the content
		if ( $module_page == 'callhome' ) {
			$page->setTemplateVar( 'PageTitle', "Call Home" );
			$page->addBreadCrumb( "Call Home", $ftsMenus['CALLHOME']['link'] );

			if ( user_access( 'fts_callhome_access' ) ) {
				$page_content = '
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicons glyphicons-phone-alt"></i> ' . __( 'Call Home' ) . '</h3>
							<div class="toolbar">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#currentInstalls" data-toggle="tab"><span>' . __( 'Current Installs' ) . '</span></a></li>
								</ul>
							</div>
						</div>
						<div class="tab-content">
							<div id="currentInstalls" class="tab-pane active">
								<div id="updateMeInstalls">
									' . printCallHomeTable() . '
								</div>
							</div>
						</div>
					</div>';

				$JQueryReadyScripts .= returnCallHomeTableJQuery();
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'versions' ) {
			$page->setTemplateVar( 'PageTitle', "Versions" );
			$page->addBreadCrumb( 'Versions', $ftsMenus['VERSIONS']['link'] );

			if ( user_access( 'fts_versions_access' ) ) {
				if ( $actual_action == "editversion" && isset( $actual_id ) && user_access( 'fts_versions_edit' ) ) {
					// Add breadcrumb
					$page->setTemplateVar( 'PageTitle', "Edit Version" );
					$page->addBreadCrumb( "Edit Version", "" );

					$page_content .= '		
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicon glyphicon-cog"></i> ' . __( 'Edit Version' ) . '</h3>
							</div>
							<div class="box-content">
								<div id="versionDetails">
									<div id="updateMeVersions">
										' . printEditVersionForm( $actual_id ) . '
									</div>
								</div>
							</div>
						</div>';

					// Handle our JQuery needs
					$JQueryReadyScripts = returnEditVersionFormJQuery( $actual_id );
				} else {
					//==================================================
					// Print out our versions table
					//==================================================
					$page_content = '	
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicon glyphicon-cog"></i> ' . __( 'versions' ) . '</h3>
								<div class="toolbar">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#currentVersions" data-toggle="tab"><span>Current Versions</span></a></li>
										' . ( ( user_access( 'fts_versions_create' ) ) ? '<li><a href="#addANewVersion" data-toggle="tab"><span>Add a New Version</span></a></li>' : '' ) . '
									</ul>
								</div>
							</div>
							<div class="tab-content">
								<div id="currentVersions" class="tab-pane active">
									<div id="updateMeVersions">
										' . printVersionsTable() . '
									</div>
								</div>
								' . ( ( user_access( 'fts_versions_create' ) ) ? '
								<div id="addANewVersion" class="tab-pane">
									' . printNewVersionForm() . '
								</div>
								' : '' ) . '
							</div>
						</div>';

					$JQueryReadyScripts .= returnVersionsTableJQuery();
					if ( user_access( 'fts_versions_create' ) ) {
						$JQueryReadyScripts .= returnNewVersionFormJQuery( 1 );
					}
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
		global $page, $actual_action, $actual_id, $ftsMenus;

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
		global $ftsdb, $page, $mbp_config, $ftsMenus, $actual_id, $actual_action, $actual_value,
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
		$formFields = apply_filters( 'form_fields_vnts_settings', array(
			'ftsmbp_vnts_serial' => array(
				'text' => 'Serial',
				'type' => 'text',
			),
		) );

		if ( $section == 'tabs' ) {
			$content = '
				<li><a href="#vntsSettings" data-toggle="tab"><span>' . __( 'VNTS Settings' ) . '</span></a></li>';
		} elseif ( $section == 'content' ) {
			$content = '
				<div id="vntsSettings" class="tab-pane">
					' . makeFormFieldset( 'VNTS Settings', $formFields, $mbp_config, 0 ) . '
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
	}

	//====================================
	// Graphs Page
	// ---
	// Show Page hook
	//====================================
	public function graphsPage( $arguments = array() ) {
		global $page, $menuvar, $ftsMenus;
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
		} elseif ( $section == 'reports' ) {
		}

		return $content;
	}
}

?>