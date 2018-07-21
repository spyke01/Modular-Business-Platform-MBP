<?php
/***************************************************************************
 *                               TRS.php
 *                            -------------------
 *   begin                : Wednesday, November 26, 2008
 *   copyright            : (C) 2008 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/


class TRS {
	//====================================
	// Basic Module information
	//====================================
	public $name = "Taggable Report System";
	public $description = "Adds the FTS TRS capabilities to your system.";
	public $developer = "Paden Clayton";
	public $version = "2.15.07.29";
	public $updateRequestURL = '';
	private $prefix = "TRS";

	//===============================================================
	// Our class constructor
	//===============================================================
	public function __construct() {
		global $page, $mbp_config;

		$myFolder = SITE_URL . "/modules/$this->prefix/";

		// Add stylsheet files
		$page->addStyle( $myFolder . 'stylesheets/main.css' );

		// Add script files
		$page->addScript( $myFolder . 'javascripts/formToWizard.js' );
		$page->addScript( $myFolder . 'javascripts/functions.js' );

		// Set our updateRequestURL
		$this->updateRequestURL = 'https://www.fasttracksites.com/versions/serialChecker.php?response=json&app=module_' . $this->prefix . '&serial=' . $mbp_config['ftsmbp_trs_serial'];
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
		if ( count( $trsUserMenuItems ) ) {
			foreach ( $trsUserMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'] );
				}
			}
		}
		if ( count( $trsAdminMenuItems ) ) {
			foreach ( $trsAdminMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'] );
				}
			}
		}

		// Create database tables	
		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "taggable_reports` (
				`id` mediumint(8) NOT NULL auto_increment,
				`user_id` mediumint(8) NOT NULL,
				`name` varchar(255) DEFAULT NULL,
				`description` varchar(255) DEFAULT NULL,
				`fields` longtext,
				`template` longtext,
				`datetimestamp` int(25) DEFAULT NULL,
				PRIMARY KEY  (id),
				KEY `user_id` (`user_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );

		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "tagged_reports` (
				`id` mediumint(8) NOT NULL auto_increment,
				`client_id` mediumint(8) NOT NULL,
				`report_id` mediumint(8) NOT NULL,
				`name` varchar(255) DEFAULT NULL,
				`report` longtext,
				`datetimestamp` int(25) DEFAULT NULL,
				PRIMARY KEY  (id),
				KEY `client_id` (`client_id`),
				KEY `report_id` (`report_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
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
			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "taggable_reports`
					CHANGE `name` `name` varchar(255) DEFAULT NULL,
					CHANGE `description` `description` varchar(255) DEFAULT NULL,
					CHANGE `fields` `fields` longtext,
					CHANGE `template` `template` longtext,
					CHANGE `datetimestamp` `datetimestamp` int(25) DEFAULT NULL,
					ADD INDEX (`user_id`),
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
			$result = $ftsdb->run( $sql );

			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "tagged_reports`
					CHANGE `name` `name` varchar(255) DEFAULT NULL,
					CHANGE `report` `report` longtext,
					CHANGE `datetimestamp` `datetimestamp` int(25) DEFAULT NULL,
					ADD INDEX (`client_id`),
					ADD INDEX (`report_id`),
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
			$result = $ftsdb->run( $sql );
		}
	}

	//====================================
	// Return Includes hook
	//====================================
	public function returnIncludes() {
		global $mbp_config;

		return BASEPATH . '/modules/TRS/includes/constants.php;' . BASEPATH . '/modules/TRS/includes/functions.php;' . BASEPATH . '/modules/TRS/includes/menu.php;';
	}

	//===============================================================
	// Prep Settings hook
	//===============================================================
	public function prepSettings() {
		global $page, $trsUserMenuItems, $trsAdminMenuItems, $trsMenus;

	}

	//===============================================================
	// Check DB Settings hook
	//===============================================================
	public function checkDBSettings() {
		$defaultPermissions = array(
			'trs_mytaggedreports_access'  => '0,2,5,6',
			'trs_mytaggedreports_delete'  => '2,',
			'trs_report_templates_access' => '2,',
			'trs_report_templates_delete' => '2,',
		);

		if ( count( $defaultPermissions ) ) {
			foreach ( $defaultPermissions as $name => $role_ids ) {
				if ( ! permision_setting_exists( $name ) ) {
					add_permision_setting( $name, $role_ids );
				}
			}
		}

		$defaultSettings = array();

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
		global $page, $trsUserMenuItems, $trsAdminMenuItems, $trsMenus;
		$myFolder = "modules/$this->prefix/";

		if ( count( $trsUserMenuItems ) ) {
			foreach ( $trsUserMenuItems as $key => $menuItemArray ) {
				$trsMenus[ $key ] = $menuItemArray;
			}
		}
		if ( count( $trsAdminMenuItems ) ) {
			foreach ( $trsAdminMenuItems as $key => $menuItemArray ) {
				$trsMenus[ $key ] = $menuItemArray;
			}
		}
	}

	//====================================
	// Get Dropdown Array hook
	//====================================
	public function getDropdownArray( $arguments = array() ) {
		global $ftsdb, $page, $trsUserMenuItems, $trsAdminMenuItems, $trsMenus, $TAGGABLE_FIELD_TYPES;
		extract( (array) $arguments ); // Extract our arguments into variables

		$returnArray = array();

		// Prep our IN clause data
		$preparedInClause = $ftsdb->prepareInClauseVariable( getMyUserIDs() );
		$selectBindData   = $preparedInClause['data'];

		$dropdown = "";

		if ( $type == "taggableReports" ) {
			$result = $ftsdb->select( DBTABLEPREFIX . "taggable_reports", "user_id IN (" . $preparedInClause['binds'] . ") ORDER BY name", $selectBindData, 'id, name' );

			if ( $result ) {
				foreach ( $result as $row ) {
					$returnArray[ $row['id'] ] = $row['name'];
				}
				$result = null;
			}
		} else if ( $type == "taggableReportFieldTypes" ) {
			$returnArray = $returnArray + $TAGGABLE_FIELD_TYPES; // Preserve numberic keys by not using array_merge
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
		global $page, $trsMenus, $actual_action, $actual_id, $actual_startsWith;

		extract( (array) $arguments ); // Extract our arguments into variables
		$page_content = $JQueryReadyScripts = '';
		$this->prepMenus();

		// Cycle through our pages and handle the content
		if ( $module_page == 'mytaggedreports' ) {
			$page->setTemplateVar( 'PageTitle', 'My Tagged Reports' );
			$page->addBreadCrumb( 'My Tagged Reports', $trsMenus['MYTAGGEDREPORTS']['link'] );

			if ( user_access( 'trs_tagged_reports_access' ) ) {
				$page_content = '	
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicon glyphicon-cog"></i> ' . __( 'My Reports' ) . '</h3>
						</div>
						<div class="box-content">
							<div id="currentReports">
								' . printTaggedReportsTable( $_SESSION['userid'] ) . '
							</div>
						</div>
					</div>';

				$JQueryReadyScripts .= returnTaggedReportsTableJQuery();
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'taggablereports' ) {
			$page->setTemplateVar( 'PageTitle', 'Taggable Reports' );
			$page->addBreadCrumb( 'Taggable Reports', $trsMenus['TAGGABLEREPORTS']['link'] );

			if ( user_access( 'trs_report_templates_access' ) ) {
				$page_content = '	
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicon glyphicon-cog"></i> ' . __( 'Taggable Reports' ) . '</h3>
							<div class="toolbar">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#currentReports" data-toggle="tab"><span>Current Reports</span></a></li>
									' . ( ( user_access( 'trs_report_templates_create' ) ) ? '<li><a href="#createANewTaggableReport" data-toggle="tab"><span>Create a New Taggable Report</span></a></li>' : '' ) . '
								</ul>
							</div>
						</div>
						<div class="tab-content">
							<div id="currentReports" class="tab-pane active">
								<div id="updateMeTaggableReports">
									' . printTaggableReportsTable() . '
								</div>
							</div>
							' . ( ( user_access( 'trs_report_templates_create' ) ) ? '
							<div id="createANewTaggableReport" class="tab-pane">
								' . printNewTaggableReportForm() . '
							</div>
							' : '' ) . '
						</div>
					</div>';

				$JQueryReadyScripts .= returnTaggableReportsTableJQuery();
				if ( user_access( 'trs_report_templates_create' ) ) {
					$JQueryReadyScripts .= returnNewTaggableReportFormJQuery( 1 );
				}
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'viewtaggedreport' && isset( $actual_id ) ) {
			$page->setTemplateVar( 'PageTitle', 'View Tagged Report' );
			$page->addBreadCrumb( 'View Tagged Report', '' );

			if ( user_access( 'trs_tagged_reports_access' ) ) {
				$page_content = '
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicon glyphicon-cog"></i> ' . __( 'View Tagged Report' ) . '</h3>
							<div class="toolbar">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#viewReport" data-toggle="tab"><span>' . __( 'Report' ) . '</span></a></li>
								</ul>
							</div>
						</div>
						<div class="tab-content">
							<div id="viewReport" class="tab-pane active">
								' . printViewTaggedReport( $actual_id ) . '
							</div>
						</div>
					</div>';
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'clmsEditClientExtraTabs' ) {
			if ( user_access( 'trs_tagged_reports_access' ) ) {
				if ( $section == 'tabs' ) {
					$page_content = '
						<li><a href="#trsReports" data-toggle="tab"><span>Reports</span></a></li>';
				} elseif ( $section == 'content' ) {
					$page_content = '
						<div id="trsReports" class="tab-pane">
							<div id="updateMeTaggableReports">
								' . printTaggedReportsTable( $content['id'] ) . '
							</div>
							' . ( ( user_access( 'trs_tagged_reports_create' ) ) ? '
							<br /><br />
							<div id="createANewTaggedReport">
								' . printNewTaggedReportForm( $content['id'] ) . '
							</div>
							' : '' ) . '
						</div>';
				} elseif ( $section == 'jQuery' ) {
					$page_content = returnTaggedReportsTableJQuery();
					if ( user_access( 'trs_tagged_reports_create' ) ) {
						$page_content .= returnNewTaggedReportFormJQuery( 1 );
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
	public function handlePageResult( $searchVars ) {
		global $_SESSION, $trsMenus, $trsMenus;

		$page_content = $JQueryReadyScripts = '';
		$this->prepMenus();

		// Cycle through our pages and handle the content

		// Attach our content
		$page->setTemplateVar( 'PageContent', $page_content );
		$page->setTemplateVar( "JQueryReadyScript", $JQueryReadyScripts );
	}

	//====================================
	// Show Page hook
	//====================================
	public function handleAJAX( $arguments = array() ) {
		global $ftsdb, $page, $mbp_config, $trsMenus, $actual_id, $actual_action, $actual_value,
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
		$formFields = apply_filters( 'form_fields_trs_settings', array(
			'ftsmbp_trs_serial' => array(
				'text' => 'Serial',
				'type' => 'text',
			),
		) );

		if ( $section == 'tabs' ) {
			$content = '
				<li><a href="#trsSettings" data-toggle="tab"><span>' . __( 'TRS Settings' ) . '</span></a></li>';
		} elseif ( $section == 'content' ) {
			$content = '
				<div id="trsSettings" class="tab-pane">
					' . makeFormFieldset( 'TRS Settings', $formFields, $mbp_config, 0 ) . '
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
	}

	//====================================
	// Graphs Page
	// ---
	// Show Page hook
	//====================================
	public function graphsPage( $arguments = array() ) {
		global $page, $menuvar, $trsUserMenuItems, $trsAdminMenuItems, $trsMenus;
		extract( (array) $arguments ); // Extract our arguments into variables

		$content = "";

		if ( $section == 'links' ) {
		} elseif ( $section == 'jQuery' ) {
		} elseif ( $section == 'graphs' ) {
		}

		return $content;
	}

	//====================================
	// Reports Page
	// ---
	// Show Page hook
	//====================================
	public function reportsPage( $arguments = array() ) {
		global $page, $menuvar, $trsMenus;

		extract( (array) $arguments ); // Extract our arguments into variables

		$content = "";

		if ( $section == 'links' ) {
		} elseif ( $section == 'reports' ) {
		}

		return $content;
	}

	//====================================
	// Dashboard Page
	// ---
	// Show Page hook
	//====================================
	public function dashboardPage( $arguments = array() ) {
		global $page, $menuvar, $trsMenus;

		extract( (array) $arguments ); // Extract our arguments into variables

		$content = "";

		return $content;
	}
}

?>