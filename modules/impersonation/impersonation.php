<?php

/***************************************************************************
 *                               impersonation.php
 *                            -------------------
 *   begin                : Monday, December 20, 2016
 *   copyright            : (C) 2016 Paden Clayton
 *
 *
 ***************************************************************************
 */
class impersonation {
	//====================================
	// Basic Module information
	//====================================
	public $name = "User Impersonation Module";
	public $description = "Adds the ability to impersonate a user to your system.";
	public $developer = "Paden Clayton";
	public $version = "2.17.07.24";
	private $prefix = "impersonation";

	//===============================================================
	// Our class constructor
	//===============================================================
	public function __construct() {
		global $page, $mbp_config;
		$myFolder = "modules/$this->prefix/";

		// Add stylesheet files
		//$page->addStyle($myFolder . 'style.css');

		// Add script files
		//$page->addScript($myFolder . 'javascripts/functions.js');

		// Set our updateRequestURL
		$this->updateRequestURL = 'https://www.fasttracksites.com/versions/serialChecker.php?response=json&app=module_' . $this->prefix . '&serial=' . $mbp_config['ftsmbp_impersonation_serial'];
	}

	//====================================
	// Install hook
	//====================================
	public function install() {
		global $ftsdb, $mbp_config;

		installModule( $this->prefix, $this->name, $this->description, $this->developer, $this->version );

		include_once( BASEPATH . '/modules/' . $this->prefix . '/includes/menu.php' );
		include_once( BASEPATH . '/modules/' . $this->prefix . '/includes/constants.php' );

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
		return BASEPATH . '/modules/impersonation/includes/constants.php;' . BASEPATH . '/modules/impersonation/includes/functions.php;' . BASEPATH . '/modules/impersonation/includes/menu.php;';
	}

	//====================================
	// Prep Menus hook
	//====================================

	public function checkDBSettings() {
		global $impersonationUserMenuItems, $impersonationAdminMenuItems, $ftsdb;

		$defaultPermissions = array(
			'impersonation_access' => '2,',
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
		foreach ( array_merge( (array) $impersonationUserMenuItems, (array) $impersonationAdminMenuItems ) as $key => $menuItemArray ) {
			$result = $ftsdb->update( DBTABLEPREFIX . 'menu_items',
				array(
					'icon' => $menuItemArray['icon'],
				),
				"link = :link",
				array(
					":link" => $menuItemArray['link'],
				)
			);
		}
	}

	//===============================================================
	// Check DB Settings hook
	//===============================================================

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
	// Get Dropdown Array hook
	//====================================

	public function getDropdownArray( $arguments = array() ) {
		global $ftsdb;
		extract( (array) $arguments ); // Extract our arguments into variables

		$returnArray = array();

		if ( $type == "impersonate_ids" ) {
			$userLevels      = getDropdownArray( 'userlevel' );
			$impersonate_ids = array();

			foreach ( $userLevels as $user_level => $name ) {
				$usersInThisRole = array();

				$result = $ftsdb->select( USERSDBTABLEPREFIX . "users",
					"user_level = :user_level ORDER BY last_name",
					array(
						":user_level" => $user_level,
					),
					'id, email_address, first_name, last_name' );

				if ( $result ) {
					foreach ( $result as $row ) {
						if ( return_impersonate_access_level_name( $row['user_level'] ) ) {
							$usersInThisRole[ 'users_' . $row['id'] ] = $row['last_name'] . ', ' . $row['first_name'] . ' (' . $row['email_address'] . ')';
						}
					}
					$result = null;
				}

				$impersonate_ids[ $name ] = $usersInThisRole;
			}

			$returnArray = $returnArray + $impersonate_ids; // Preserve numberic keys by not using array_merge
		}

		return $returnArray;
	}

	//====================================
	// Create Dropdown hook
	//====================================

	public function showPage( $arguments = array() ) {
		global $page, $impersonationMenus, $actual_action, $actual_id, $actual_startsWith, $menuvar;

		extract( (array) $arguments ); // Extract our arguments into variables
		$page_content = $JQueryReadyScripts = '';
		$this->prepMenus();

		// Cycle through our pages and handle the content
		if ( $module_page == 'impersonate' ) {
			$page->setTemplateVar( 'PageTitle', "Call Home" );
			$page->addBreadCrumb( "Call Home", $impersonationMenus['CALLHOME']['link'] );

			if ( $actual_action == 'stop' ) {
				perform_stop_impersonation();

				$page_content = return_success_alert( 'You are no longer impersonating another user.  
						You are being redirected to the main page.' );
				$page_content .= '<meta http-equiv="refresh" content="5;url=' . SITE_URL . '">';
			} elseif ( user_access( 'impersonation_access' ) ) {
				if ( isset( $_REQUEST['impersonate_id'] ) ) {
					/*
						We pass an id which looks like prefixID
						We then need to call any impersonateUser methods
						These will handle the permission checks needed
						If we have a $_SESSION['actual_userid'] then it worked
						Print what was returnd
					*/
					do_action( 'perform_impersonation', $_REQUEST['impersonate_id'] );

					if ( empty( $_SESSION['actual_userid'] ) ) {
						$page_content = return_error_alert( 'You were not able to impersonate the user you requested. 
							This can be caused by an invalid impersonation id or by an error in the code of the site. 
							Please try again and if errors continue then contact the webmaster.
							You are being redirected to the main page.' );
						$page_content .= '<meta http-equiv="refresh" content="5;url=' . SITE_URL . '">';
					} else {
						$page_content = return_success_alert( 'You are now impersonating the user you requested (' . $_SESSION['full_name'] . ').  
							You are being redirected to the main page.' );
						$page_content .= '<meta http-equiv="refresh" content="5;url=' . SITE_URL . '">';
					}
				} else {
					$page_content = '
						<div class="box">
							<div class="box-header">
								<h3><i class="glyphicon glyphicon-eye-open"></i> ' . __( 'Impersonate' ) . '</h3>
							</div>
							<div class="tab-content">
								' . returnImpersonationForm() . '
							</div>
						</div>';

					$JQueryReadyScripts .= returnImpersonationFormJQuery();
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
	// Show Page hook
	//====================================

	public function prepMenus() {
		global $page, $impersonationUserMenuItems, $impersonationAdminMenuItems, $impersonationMenus;
		$myFolder = "modules/$this->prefix/";
	}

	//====================================
	// Handle Page Result hook
	//====================================

	public function handlePageResult( $arguments ) {
		global $page, $actual_action, $actual_id, $impersonationMenus;

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
		global $ftsdb, $page, $mbp_config, $impersonationMenus, $actual_id, $actual_action, $actual_value,
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
		$formFields = apply_filters( 'form_fields_impersonation_settings',
			array(
				'ftsmbp_impersonation_serial' => array(
					'text' => 'Serial',
					'type' => 'text',
				),
			) );

		if ( $section == 'tabs' ) {
			$content = '
				<li><a href="#impersonationSettings" data-toggle="tab"><span>' . __( 'Impersonation Settings' ) . '</span></a></li>';
		} elseif ( $section == 'content' ) {
			$content = '
				<div id="impersonationSettings" class="tab-pane">
					' . makeFormFieldset( 'Impersonation Settings', $formFields, $mbp_config, 0 ) . '
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
		global $page, $menuvar, $impersonationMenus;
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