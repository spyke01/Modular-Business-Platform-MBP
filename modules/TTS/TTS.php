<?php
/***************************************************************************
 *                               TTS.php
 *                            -------------------
 *   begin                : Wednesday, November 26, 2008
 *   copyright            : (C) 2008 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/


class TTS {
	//====================================
	// Basic Module information
	//====================================
	public $name = "Trouble Ticket System";
	public $description = "Adds the FTS TTS capabilities to your system.";
	public $developer = "Paden Clayton";
	public $version = "2.17.07.12";
	public $updateRequestURL = '';
	private $prefix = "TTS";

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
		$this->updateRequestURL = 'https://www.fasttracksites.com/versions/serialChecker.php?response=json&app=module_' . $this->prefix . '&serial=' . $mbp_config['ftsmbp_tts_serial'];
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
		if ( count( $ttsUserMenuItems ) ) {
			foreach ( $ttsUserMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'] );
				}
			}
		}
		if ( count( $ttsAdminMenuItems ) ) {
			foreach ( $ttsAdminMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'] );
				}
			}
		}

		// Create database tables	
		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "entries` (
				`id` mediumint(8) NOT NULL auto_increment,
				`ticket_id` mediumint(8) NOT NULL DEFAULT 0,
				`user_id` mediumint(8) NOT NULL,
				`is_client` tinyint(1) DEFAULT NULL,
				`text` text,
				`datetimestamp` int(25) DEFAULT NULL,
				PRIMARY KEY  (id),
				KEY `ticket_id` (`ticket_id`),
				KEY `user_id` (`user_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );

		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "tickets` (
				`id` mediumint(8) NOT NULL auto_increment,
				`cat_id` mediumint(8) NOT NULL,
				`user_id` mediumint(8) NOT NULL,
				`client_id` mediumint(8) NOT NULL,
				`tech_id` mediumint(8) NOT NULL,
				`title` char(50) NOT NULL DEFAULT '',
				`datetimestamp` int(25) DEFAULT NULL,
				`status` int(1) NOT NULL DEFAULT 0,
				PRIMARY KEY  (id),
				KEY `cat_id` (`cat_id`),
				KEY `user_id` (`user_id`),
				KEY `client_id` (`client_id`),
				KEY `tech_id` (`tech_id`)
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
			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "entries`
				CHANGE `is_client` `is_client` tinyint(1) DEFAULT NULL,
				CHANGE `text` `text` text,
				CHANGE `datetimestamp` `datetimestamp` int(25) DEFAULT NULL,
				ADD INDEX (`ticket_id`),
				ADD INDEX (`user_id`),
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
			$result = $ftsdb->run( $sql );

			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "tickets`
				CHANGE `datetimestamp` `datetimestamp` int(25) DEFAULT NULL,
				ADD INDEX (`cat_id`),
				ADD INDEX (`user_id`),
				ADD INDEX (`client_id`),
				ADD INDEX (`tech_id`),
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
			$result = $ftsdb->run( $sql );
		}
	}

	//====================================
	// Return Includes hook
	//====================================
	public function returnIncludes() {
		global $mbp_config;

		$languageidentifier = ( ! empty( $mbp_config['ftsmbp_tts_language'] ) ) ? $mbp_config['ftsmbp_tts_language'] : 'en';

		return BASEPATH . '/modules/TTS/includes/constants.php;' . BASEPATH . '/modules/TTS/includes/functions.php;' . BASEPATH . '/modules/TTS/includes/menu.php;' . BASEPATH . '/modules/TTS/includes/languages/' . $languageidentifier . '.php;';
	}

	//===============================================================
	// Prep Settings hook
	//===============================================================
	public function prepSettings() {
		global $page, $ttsUserMenuItems, $ttsAdminMenuItems, $ttsMenus, $TICKET_STATUS, $LANG;

		$TICKET_STATUS[0] = $LANG['OPEN'];
		$TICKET_STATUS[1] = $LANG['CLOSED'];
		$TICKET_STATUS[2] = $LANG['ON_HOLD'];
	}

	//===============================================================
	// Check DB Settings hook
	//===============================================================
	public function checkDBSettings() {
		global $ttsUserMenuItems, $ttsAdminMenuItems, $mbp_config, $ftsdb;

		$defaultPermissions = array(
			'tts_tickets_access'         => '2,',
			'tts_tickets_create'         => '0,2,5,6',
			'tts_tickets_search'         => '2,',
			'tts_tickets_search_by_user' => '2,',
			'tts_mytickets_access'       => '0,2,5,6',
			'tts_tickets_create_entries' => '0,2,5,6',
			'tts_tickets_create_entries' => '0,2,5,6',
			'tts_tickets_delete'         => '2,',
			'tts_tickets_change_user'    => '2,',
			'tts_tickets_change_client'  => '2,',
			'tts_tickets_change_status'  => '2,',
			'tts_tickets_change_tech'    => '2,',
		);

		if ( count( $defaultPermissions ) ) {
			foreach ( $defaultPermissions as $name => $role_ids ) {
				if ( ! permision_setting_exists( $name ) ) {
					add_permision_setting( $name, $role_ids );
				}
			}
		}

		$defaultSettings = array(
			'ftsmbp_tts_language'                  => 'en',
			'ftsmbp_tts_sendUpdateNoticeToClients' => '1',
			'ftsmbp_tts_sendUpdateNoticeToTechs'   => '1',
			'ftsmbp_tts_new_ticket_email'          => 'A new trouble ticket has been created by *NAME*, you can view this ticket by visiting *VIEW_TICKET_URL*.<br /><br /><strong>Ticket Text:</strong><br />*LAST_ENTRY*',
			'ftsmbp_tts_ticket_update_email'       => 'A new entry has been posted on your trouble ticket by *NAME*, you can view this update by visiting *VIEW_TICKET_URL*.<br /><br /><strong>Last Update:</strong><br />*LAST_ENTRY*',
		);

		if ( count( $defaultSettings ) ) {
			foreach ( $defaultSettings as $name => $value ) {
				if ( ! config_value_exists( $name ) ) {
					add_config_value( $name, $value );
				}
			}
		}

		// Add icons if necessary
		foreach ( array_merge( (array) $ttsUserMenuItems, (array) $ttsAdminMenuItems ) as $key => $menuItemArray ) {
			$result = $ftsdb->update( DBTABLEPREFIX . 'menu_items', array(
				'icon' => $menuItemArray['icon']
			),
				"link = :link", array(
					":link" => $menuItemArray['link']
				)
			);
		}

		// Copy all of our current email templates into the email templates table
		$templatesToCopy = array(
			array(
				'template_id' => 'tts-ticket-created',
				'name'        => 'TTS: New Ticket Alert',
				'subject'     => 'New %site_name% Trouble Ticket #%id%',
				'message'     => $mbp_config['ftsmbp_tts_new_ticket_email'],
			),
			array(
				'template_id' => 'tts-ticket-updated',
				'name'        => 'TTS: Ticket Updated Alert',
				'subject'     => 'Your %site_name% Trouble Ticket #%id% has been updated (%status%)',
				'message'     => $mbp_config['ftsmbp_tts_ticket_update_email'],
			),
		);

		foreach ( $templatesToCopy as $templateData ) {
			if ( ! emailTemplateExists( $templateData['template_id'] ) ) {
				addEmailTemplate( $templateData['template_id'], $templateData['name'], $templateData['subject'], $templateData['message'], 'Module', $this->prefix );
			}
		}
	}

	//====================================
	// Prep Menus hook
	//====================================
	public function prepMenus() {
		global $page, $ttsUserMenuItems, $ttsAdminMenuItems, $ttsMenus, $LANG;
		$myFolder = "modules/$this->prefix/";

		if ( count( $ttsUserMenuItems ) ) {
			foreach ( $ttsUserMenuItems as $key => $menuItemArray ) {
				$ttsMenus[ $key ] = $menuItemArray;
			}
		}
		if ( count( $ttsAdminMenuItems ) ) {
			foreach ( $ttsAdminMenuItems as $key => $menuItemArray ) {
				$ttsMenus[ $key ] = $menuItemArray;
			}
		}
	}

	//====================================
	// Get Dropdown Array hook
	//====================================
	public function getDropdownArray( $arguments = array() ) {
		global $ftsdb, $page, $ttsUserMenuItems, $ttsAdminMenuItems, $ttsMenus, $LANG, $TICKET_STATUS, $LANGUAGES;
		extract( (array) $arguments ); // Extract our arguments into variables

		$returnArray = array();

		if ( $type == "ticketcategories" ) {
			$result = $ftsdb->select( DBTABLEPREFIX . "categories", "type='5' ORDER BY name", array(), 'id, name' );

			if ( $result ) {
				foreach ( $result as $row ) {
					$returnArray[ $row['id'] ] = $row['name'];
				}
				$result = null;
			}
		} else if ( $type == "ticketstatus" ) {
			$returnArray = $returnArray + $TICKET_STATUS; // Preserve numberic keys by not using array_merge
		} else if ( $type == "languages" ) {
			$returnArray = $returnArray + $LANGUAGES; // Preserve numberic keys by not using array_merge
		} else if ( $type == "techs" ) {
			$result = $ftsdb->select( USERSDBTABLEPREFIX . "users", "user_level = '" . APPLICATION_ADMIN . "' OR user_level = '" . SYSTEM_ADMIN . "' ORDER BY last_name", array(), 'id, email_address, first_name, last_name' );

			if ( $result ) {
				foreach ( $result as $row ) {
					$returnArray[ $row['id'] ] = $row['last_name'] . ', ' . $row['first_name'] . ' (' . $row['email_address'] . ')';
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
		global $page, $actual_action, $actual_id, $ttsMenus, $LANG;

		extract( (array) $arguments ); // Extract our arguments into variables
		$page_content = $JQueryReadyScripts = '';
		$this->prepMenus();
		$actual_startsWith = keepsafe( $_GET['startsWith'] );

		// Cycle through our pages and handle the content
		if ( $module_page == 'mytickets' ) {
			$page->setTemplateVar( 'PageTitle', $LANG['MY_TICKETS'] );
			$page->addBreadCrumb( $LANG['MY_TICKETS'], $ttsMenus['MYTICKETS']['link'] );
			$currentStatus = $_GET['status'];

			if ( user_access( 'tts_mytickets_access' ) ) {
				$page_content = '	
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicons glyphicons-flag"></i> ' . __( 'My Tickets' ) . '</h3>
							<div class="toolbar">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#currentTickets" data-toggle="tab"><span>' . $LANG['TABS_CURRENT_TICKETS'] . '</span></a></li>
									' . ( ( user_access( 'tts_tickets_create' ) ) ? '<li><a href="#createANewTicket" data-toggle="tab"><span>' . $LANG['TABS_CREATE_A_NEW_TICKET'] . '</span></a></li>' : '' ) . '
								</ul>
							</div>
						</div>
						<div class="tab-content">
							<div id="currentTickets" class="tab-pane active">
								<div class="btn-group">
									<a href="' . $ttsMenus['MYTICKETS']['link'] . '" class="btn btn-default' . ( ( ! isset( $_GET['status'] ) ) ? ' active' : '' ) . '">' . $LANG['FORMTITLES_ALL_TICKETS'] . '</a> 
									<a href="' . $ttsMenus['MYTICKETS']['link'] . '&status=0" class="btn btn-default' . ( ( isset( $_GET['status'] ) && $currentStatus == 0 ) ? ' active' : '' ) . '">' . $LANG['FORMTITLES_OPEN_TICKETS'] . '</a>
									<a href="' . $ttsMenus['MYTICKETS']['link'] . '&status=1" class="btn btn-default' . ( ( $currentStatus == 1 ) ? ' active' : '' ) . '">' . $LANG['FORMTITLES_CLOSED_TICKETS'] . '</a> 
									<a href="' . $ttsMenus['MYTICKETS']['link'] . '&status=2" class="btn btn-default' . ( ( $currentStatus == 2 ) ? ' active' : '' ) . '">' . $LANG['FORMTITLES_ON_HOLD_TICKETS'] . '</a>
								</div>
								<br /><br />
								<div id="updateMeTickets">
									' . printTicketsTable( array(
						'user_id' => $_SESSION['userid'],
						'status'  => $currentStatus
					) ) . '
								</div>
							</div>
							' . ( ( user_access( 'tts_tickets_create' ) ) ? '
							<div id="createANewTicket" class="tab-pane">
								' . printNewTicketForm() . '
							</div>
							' : '' ) . '
						</div>
					</div>';

				$JQueryReadyScripts .= returnTicketsTableJQuery();
				if ( user_access( 'tts_tickets_create' ) ) {
					$JQueryReadyScripts .= returnNewTicketFormJQuery( 1 );
				}
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'tickets' ) {
			$page->setTemplateVar( 'PageTitle', $LANG['TICKETS'] );
			$page->addBreadCrumb( $LANG['TICKETS'], $ttsMenus['TICKETS']['link'] );
			$currentStatus = $_GET['status'];

			if ( user_access( 'tts_tickets_access' ) ) {
				// Get search values
				$search_ticketID     = intval( $_GET['id'] );
				$search_ticketTitle  = keeptasafe( $_GET['title'] );
				$search_ticketUserID = intval( $_GET['user_id'] );
				$search_ticketTechID = intval( $_GET['tech_id'] );

				$searchValues = "&id=" . $search_ticketID . "&title=" . $search_ticketTitle . "&user_id=" . $search_ticketUserID . "&tech_id=" . $search_ticketTechID;

				$page_content = '	
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicons glyphicons-flag"></i> ' . __( 'Tickets' ) . '</h3>
							<div class="toolbar">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#currentTickets" data-toggle="tab"><span>' . $LANG['TABS_CURRENT_TICKETS'] . '</span></a></li>
									' . ( ( user_access( 'tts_tickets_create' ) ) ? '<li><a href="#createANewTicket" data-toggle="tab"><span>' . $LANG['TABS_CREATE_A_NEW_TICKET'] . '</span></a></li>' : '' ) . '
								</ul>
							</div>
						</div>
						<div class="tab-content">
							<div id="currentTickets" class="tab-pane active">
								' . ( ( user_access( 'tts_tickets_search' ) ) ? '
								' . printSearchTicketsTable( $_GET ) . '
								<br />
								' : '' ) . '
								<div class="btn-group">
									<a href="' . $ttsMenus['TICKETS']['link'] . $searchValues . '" class="btn btn-default' . ( ( ! isset( $_GET['status'] ) ) ? ' active' : '' ) . '">' . $LANG['FORMTITLES_ALL_TICKETS'] . '</a> 
									<a href="' . $ttsMenus['TICKETS']['link'] . $searchValues . '&status=0" class="btn btn-default' . ( ( isset( $_GET['status'] ) && $currentStatus == 0 ) ? ' active' : '' ) . '">' . $LANG['FORMTITLES_OPEN_TICKETS'] . '</a>
									<a href="' . $ttsMenus['TICKETS']['link'] . $searchValues . '&status=1" class="btn btn-default' . ( ( $currentStatus == 1 ) ? ' active' : '' ) . '">' . $LANG['FORMTITLES_CLOSED_TICKETS'] . '</a> 
									<a href="' . $ttsMenus['TICKETS']['link'] . $searchValues . '&status=2" class="btn btn-default' . ( ( $currentStatus == 2 ) ? ' active' : '' ) . '">' . $LANG['FORMTITLES_ON_HOLD_TICKETS'] . '</a>
								</div>
								<br /><br />
								<div id="updateMeTickets">
									' . printTicketsTable( $_GET ) . '
								</div>
							</div>
							' . ( ( user_access( 'tts_tickets_create' ) ) ? '
							<div id="createANewTicket" class="tab-pane">
								' . printNewTicketForm() . '
							</div>
							' : '' ) . '
						</div>
					</div>';

				if ( user_access( 'tts_tickets_search' ) ) {
					$JQueryReadyScripts .= returnSearchTicketsTableJQuery();
				}
				$JQueryReadyScripts .= returnTicketsTableJQuery();
				if ( user_access( 'tts_tickets_create' ) ) {
					$JQueryReadyScripts .= returnNewTicketFormJQuery( 1 );
				}
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'viewticket' && isset( $actual_id ) ) {
			$page->setTemplateVar( 'PageTitle', __( 'View Ticket' ) );
			$page->addBreadCrumb( __( 'View Ticket' ), $ttsMenus['TICKETS']['link'] );

			if ( user_access( 'tts_tickets_view_access' ) ) {
				$page_content = '
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicons glyphicons-flag"></i> ' . __( 'View Ticket' ) . '</h3>
							<div class="toolbar">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#viewTicket" data-toggle="tab"><span>' . __( 'Ticket Details' ) . '</span></a></li>
								</ul>
							</div>
						</div>
						<div class="tab-content">
							<div id="viewTicket" class="tab-pane active">
								<div class="row">
									<div class="col-xs-12 col-md-8">
										<div id="updateMeViewTicket">
											' . printViewTicketEntriesTable( $actual_id ) . '
										</div>		
										' . ( ( user_access( 'tts_tickets_create_entries' ) ) ? '
										<br /><br />
										' . printNewTicketEntryForm( $actual_id ) . '
										' : '' ) . '						
									</div>
									<div class="col-xs-12 col-sm-4">
										' . printViewTicketDetailsTable( $actual_id ) . '
									</div>
								</div>
							</div>
						</div>
					</div>';

				$JQueryReadyScripts .= returnViewTicketTableJQuery();
				if ( user_access( 'tts_tickets_create_entries' ) ) {
					$JQueryReadyScripts .= returnNewTicketEntryFormJQuery( $actual_id, 1 );
				}
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'clmsEditClientExtraTabs' ) {
			if ( user_access( 'tts_tickets_access' ) ) {
				if ( $section == 'tabs' ) {
					$page_content = '
						<li><a href="#tickets" data-toggle="tab"><span>Tickets</span></a></li>';
				} elseif ( $section == 'content' ) {
					$page_content = '
						<div id="tickets" class="tab-pane">
							<div id="updateMeTickets">
								' . printTicketsTable( array( 'client_id' => $content['id'] ) ) . '
							</div>
							' . ( ( user_access( 'tts_tickets_create' ) ) ? '
							<br /><br />
							<div id="createANewTicket">
								' . printNewTicketForm( $content['id'] ) . '
							</div>
							' : '' ) . '
						</div>';
				} elseif ( $section == 'jQuery' ) {
					$page_content = returnTicketsTableJQuery();
					if ( user_access( 'tts_tickets_create' ) ) {
						$page_content .= returnNewTicketFormJQuery( 1 );
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
		global $_SESSION, $ttsMenus, $ttsMenus;

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
		global $ftsdb, $page, $mbp_config, $ttsMenus, $actual_id, $actual_action, $actual_value,
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
		$formFields = apply_filters( 'form_fields_tts_settings', array(
			'ftsmbp_tts_serial'                    => array(
				'text' => 'Serial',
				'type' => 'text',
			),
			'ftsmbp_tts_language'                  => array(
				'text'    => 'System Language',
				'type'    => 'select',
				'options' => getDropdownArray( 'languages' ),
			),
			'ftsmbp_tts_sendUpdateNoticeToClients' => array(
				'text'          => 'Send Update Notice to Clients',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => 1,
			),
			'ftsmbp_tts_sendUpdateNoticeToClients' => array(
				'text'          => 'Send Update Notice to Techs',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => 1,
			),
		) );

		if ( $section == 'tabs' ) {
			$content = '
				<li><a href="#ttsSettings" data-toggle="tab"><span>' . __( 'TTS Settings' ) . '</span></a></li>';
		} elseif ( $section == 'content' ) {
			$content = '
				<div id="ttsSettings" class="tab-pane">
					' . makeFormFieldset( 'TTS Settings', $formFields, $mbp_config, 0 ) . '
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
		if ( ! isset( $_POST['ftsmbp_tts_sendUpdateNoticeToClients'] ) ) {
			update_config_value( 'ftsmbp_tts_sendUpdateNoticeToClients', 0 );
		}
		if ( ! isset( $_POST['ftsmbp_tts_sendUpdateNoticeToTechs'] ) ) {
			update_config_value( 'ftsmbp_tts_sendUpdateNoticeToTechs', 0 );
		}
	}

	//====================================
	// Graphs Page
	// ---
	// Show Page hook
	//====================================
	public function graphsPage( $arguments = array() ) {
		global $page, $menuvar, $ttsUserMenuItems, $ttsAdminMenuItems, $ttsMenus, $LANG;
		extract( (array) $arguments ); // Extract our arguments into variables

		$content = '';

		if ( $section == 'links' ) {
			$content = '
				<li><a href="" id="' . $this->prefix . '_graphs_ticketsByStatus">' . __( 'Tickets by Status' ) . '</a></li>
				<li><a href="" id="' . $this->prefix . '_graphs_ticketsByProblemCategory">' . __( 'Tickets by Problem Category' ) . '</a></li>';
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
		global $page, $menuvar, $ttsMenus, $LANG;

		extract( (array) $arguments ); // Extract our arguments into variables

		$content = "";

		if ( $section == 'links' ) {
			$content = "
				<li><a href=\"" . $menuvar['VIEWREPORT'] . "&prefix=" . $this->prefix . "&report=ticketEntries\">" . __( 'Ticket Entries' ) . "</a></li>
				<li><a href=\"" . $menuvar['VIEWREPORT'] . "&prefix=" . $this->prefix . "&report=tickets\">" . __( 'Tickets' ) . "</a></li>";
		} elseif ( $section == 'reports' ) {
			switch ( $report ) {
				case 'ticketEntries':
					$content = printTicketEntriesReport();
					if ( $subsection == 'jQuery' ) {
						$content = returnTicketEntriesReportJQuery();
					}
					break;
				case 'tickets':
					$content = printTicketsReport();
					if ( $subsection == 'jQuery' ) {
						$content = returnTicketsReportJQuery();
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
		global $ftsdb, $page, $menuvar, $ttsMenus, $LANG;

		extract( (array) $arguments ); // Extract our arguments into variables

		$content = "";

		if ( user_access( 'tts_dashboard_access' ) ) {
			if ( $section == 'content' ) {
				$content .= '
					<br /><br />
					<div class="row">
						<div class="col-sm-6">
							<div class="row">
								<div class="col-sm-6">
									<div id="gauge_totalTickets" class="sz1"></div>
								</div>
								<div class="col-sm-6">
									<div id="gauge_openTickets" class="sz0"></div>
									<div id="gauge_onHoldTickets" class="sz0"></div>
									<div id="gauge_closedTickets" class="sz0"></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<h3>Tickets By Category</h3>
							<canvas id="dashboardChart_tts_02" width="400" height="200"></canvas>
						</div>
					</div>';
			} elseif ( $section == 'jQuery' ) {
				$content = '
					TTS_guages_dashboard();
					
					var dashboardChartTTSCTX2 = $("#dashboardChart_tts_02").get(0).getContext("2d");
					var dashboardChartTTS2 = new Chart(dashboardChartTTSCTX2);
					TTS_graphs_ticketsByProblemCategory( dashboardChartTTSCTX2 );';
			}
		}

		return $content;
	}
}