<?php
/***************************************************************************
 *                               CMS.php
 *                            -------------------
 *   begin                : Wednesday, November 26, 2008
 *   copyright            : (C) 2008 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/


class CMS {
	//====================================
	// Basic Module information
	//====================================
	public $name = "Content Management System";
	public $description = "Adds the FTS CMS capabilities to your system.";
	public $developer = "Paden Clayton";
	public $version = "2.17.07.12";
	public $updateRequestURL = '';
	private $prefix = "CMS";

	//===============================================================
	// Our class constructor
	//===============================================================
	public function __construct() {
		global $page, $mbp_config;
		$myFolder = "/modules/$this->prefix/";

		// Add stylsheet files
		//$page->addStyle($myFolder . 'style.css');

		// Add script files
		$page->addScript( $myFolder . 'javascripts/functions.js' );

		// Set our updateRequestURL
		$this->updateRequestURL = 'https://www.fasttracksites.com/versions/serialChecker.php?response=json&app=module_' . $this->prefix . '&serial=' . $mbp_config['ftsmbp_cms_serial'];
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
		if ( count( $cmsUserMenuItems ) ) {
			foreach ( $cmsUserMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '1', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'] );
				}
			}
		}
		if ( count( $cmsAdminMenuItems ) ) {
			foreach ( $cmsAdminMenuItems as $text => $menuItemArray ) {
				if ( ! menu_item_exists( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix ) ) {
					addMenuItem( '2', $menuItemArray['text'], $menuItemArray['link'], 'Module', $this->prefix, $menuItemArray['permissions'] );
				}
			}
		}

		// Create database tables
		$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "pages` (
				`id` mediumint(8) NOT NULL auto_increment,
				`title` varchar(250) NOT NULL DEFAULT '',
				`slug` varchar(250) DEFAULT NULL,
				`page_title` varchar(250) DEFAULT NULL,
				`keywords` text,
				`description` text,
				`content` text,
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
			$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "pages`
				CHANGE `slug` `slug` varchar(250) DEFAULT NULL,
				CHANGE `page_title` `page_title` varchar(250) DEFAULT NULL,
				CHANGE `keywords` `keywords` text,
				CHANGE `description` `description` text,
				CHANGE `content` `content` text,
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
			$result = $ftsdb->run( $sql );
		}
	}

	//====================================
	// Return Includes hook
	//====================================
	public function returnIncludes() {
		return BASEPATH . '/modules/CMS/includes/constants.php;' . BASEPATH . '/modules/CMS/includes/functions.php;' . BASEPATH . '/modules/CMS/includes/menu.php;';
	}

	//===============================================================
	// Prep Settings hook
	//===============================================================
	public function prepSettings() {
		global $mbp_config;
	}

	//====================================
	// Prep Menus hook
	//====================================
	public function prepMenus() {
		global $page, $cmsUserMenuItems, $cmsAdminMenuItems, $cmsMenus;
		$myFolder = "modules/$this->prefix/";

		if ( count( $cmsUserMenuItems ) ) {
			foreach ( $cmsUserMenuItems as $key => $menuItemArray ) {
				$cmsMenus[ $key ] = $menuItemArray;
			}
		}
		if ( count( $cmsAdminMenuItems ) ) {
			foreach ( $cmsAdminMenuItems as $key => $menuItemArray ) {
				$cmsMenus[ $key ] = $menuItemArray;
			}
		}
	}

	//===============================================================
	// Check DB Settings hook
	//===============================================================
	public function checkDBSettings() {
		global $cmsUserMenuItems, $cmsAdminMenuItems, $ftsdb;

		$defaultPermissions = array(// 'clms_appointments_access' => '2,',
		);

		if ( count( $defaultPermissions ) ) {
			foreach ( $defaultPermissions as $name => $role_ids ) {
				if ( ! permision_setting_exists( $name ) ) {
					add_permision_setting( $name, $role_ids );
				}
			}
		}

		$defaultSettings = array(//'ftsmbp_snts_useClientAsOwner' => '1'
		);

		if ( count( $defaultSettings ) ) {
			foreach ( $defaultSettings as $name => $value ) {
				if ( ! config_value_exists( $name ) ) {
					add_config_value( $name, $value );
				}
			}
		}

		// Check our rewrites are in the DB		
		$defaultRewrites = array(
			'page/([A-Za-z0-9-_]+)/?$'     => 'index.php?p=module&prefix=CMS&module_page=viewPage&page=$matches[1]',
			'page/([A-Za-z0-9-_]+).html?$' => 'index.php?p=module&prefix=CMS&module_page=viewPage&page=$matches[1]',
		);

		if ( count( $defaultRewrites ) ) {
			foreach ( $defaultRewrites as $match => $query ) {
				if ( ! url_rewrite_exists( $name ) ) {
					add_url_rewrite( $match, $query, 'Module', $this->prefix );
				}
			}
		}

		// Add icons if necessary
		foreach ( array_merge( (array) $cmsUserMenuItems, (array) $cmsAdminMenuItems ) as $key => $menuItemArray ) {
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
	// Module Rewrite Info hook
	//====================================
	public function moduleRewriteInfo( $arguments = array() ) {
		extract( (array) $arguments ); // Extract our arguments into variables
		$returnArray = array();

		if ( $queryArray['module_page'] == 'viewPage' ) {
			$returnArray = array(
				'rewriteArray'  => array( 'page' ),
				'linkPrefix'    => 'page/',
				'useHTMLSuffix' => 1
			);
		}

		return $returnArray;
	}

	//====================================
	// Get Dropdown Array hook
	//====================================
	public function getDropdownArray( $arguments = array() ) {
		global $ftsdb;
		extract( (array) $arguments ); // Extract our arguments into variables

		$returnArray = array();

		if ( $type == "pages" ) {
			$result = $ftsdb->select( DBTABLEPREFIX . "pages", "1 ORDER BY title", array(), 'id, title' );

			if ( $result ) {
				foreach ( $result as $row ) {
					$returnArray[ 'cms' . $row['id'] ] = $row['title'];
				}
				$result                         = null;
				$returnArray['cmstestimonials'] = 'Testimonials';
			}
		} elseif ( $type == "cmsslugs" ) {
			$result = $ftsdb->select( DBTABLEPREFIX . "pages", "1 ORDER BY title", array(), 'id, slug, title' );

			if ( $result ) {
				foreach ( $result as $row ) {
					$returnArray[ $row['slug'] ] = $row['title'];
				}
				$result                      = null;
				$returnArray['testimonials'] = 'Testimonials';
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

			// Wrap specific results
			if ( $type == "pages" ) {
				$dropdown = '
					<optgroup label="CMS Pages">
						' . $dropdown . '
					</optgroup>';
			}
		}

		return $dropdown;
	}

	//====================================
	// Show Page hook
	//====================================
	public function showPage( $arguments = array() ) {
		global $ftsdb, $page, $actual_action, $actual_id, $actual_page, $cmsMenus, $mbp_config, $fts_http;

		extract( (array) $arguments ); // Extract our arguments into variables
		$page_content = $JQueryReadyScripts = '';
		$this->prepMenus();

		// Cycle through our pages and handle the content
		if ( $module_page == 'pages' ) {
			$page->setTemplateVar( 'PageTitle', "Pages" );
			$page->addBreadCrumb( "Pages", $cmsMenus['PAGES']['link'] );

			if ( user_access( 'cms_pages_access' ) ) {
				$currentDate = ( isset( $_GET['date'] ) ) ? keeptasafe( $_GET['date'] ) : time();

				//=================================================
				// Edit the Page
				//=================================================
				if ( $actual_action == "editpage" && isset( $actual_id ) && user_access( 'cms_pages_edit' ) ) {
					// Add breadcrumb
					$page->addBreadCrumb( "Edit Page", "" );

					$page_content .= '
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicons glyphicons-pen"></i> ' . __( 'Edit Page' ) . '</h3>
							</div>
							<div class="box-content">
								' . printEditPageForm( $actual_id ) . '
							</div>
						</div>';

					// Handle our JQuery needs
					$JQueryReadyScripts = returnEditPageFormJQuery( $actual_id );
				}

				//=================================================
				// Print out the table
				//=================================================	
				else {
					$page_content = '		
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicons glyphicons-pen"></i> ' . __( 'Pages' ) . '</h3>
								<div class="toolbar">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#currentPages" data-toggle="tab"><span>Current Pages</span></a></li>
										' . ( ( user_access( 'cms_pages_create' ) ) ? '<li><a href="#createANewPage" data-toggle="tab"><span>Create a New Page</span></a></li>' : '' ) . '
									</ul>
								</div>
							</div>
							<div class="tab-content">
								<div id="currentPages" class="tab-pane active">
									<div id="updateMePages">
										' . printPagesTable() . '
									</div>
								</div>
								' . ( ( user_access( 'cms_pages_create' ) ) ? '
								<div id="createANewPage" class="tab-pane">
									' . printNewPageForm() . '
								</div>
								' : '' ) . '
							</div>
						</div>';

					// Add JQuery			
					$JQueryReadyScripts = returnPagesTableJQuery();
					if ( user_access( 'cms_pages_create' ) ) {
						$JQueryReadyScripts .= returnNewPageFormJQuery( 1 );
					}
				}
			} else {
				$page_content = notAuthorizedNotice();
			}
		} elseif ( $module_page == 'sitemap' ) {
			$page->setTemplateVar( 'PageTitle', "Sitemap" );
			$page->addBreadCrumb( "Sitemap", '' );

			// set the content to be our list of links
			callModuleHook( '', 'sitemapPage' ); // sets the proper variables so we can now get our sitemap list
			$page_content = returnSitemapList();
		} elseif ( $module_page == 'testimonials' ) {
			$page->setTemplateVar( 'PageTitle', "Testimonials" );
			$page->addBreadCrumb( "Testimonials", '' );

			$testimonials = $fts_http->request( $mbp_config['ftsmbp_cms_testimonials_url'] );
			$page_content .= '	
				<div class="box">
					<div class="box-header">
						<h3>' . __( 'Testimonials' ) . '</h3>
					</div>
					<div class="box-content">
						' . $testimonials . '
					</div>
				</div>';
		} elseif ( $module_page == 'viewPage' ) {
			// Get page info for this slug
			if ( ! empty( $actual_page ) ) {
				$result = $ftsdb->select( DBTABLEPREFIX . "pages", "slug = :slug LIMIT 1", array(
					":slug" => $actual_page
				) );

				if ( $result ) {
					foreach ( $result as $row ) {
						$page->setTemplateVar( 'PageTitle', $row['page_title'] );
						$page->setTemplateVar( "PageKeywords", $row['keywords'] );
						$page->setTemplateVar( "PageDescription", $row['description'] );
						$page->addBreadCrumb( $row['title'], il( 'index.php?p=module&prefix=CMS&module_page=viewPage&page=' . $row['slug'] ) );

						$page_content .= "	
							<div class=\"box\">
								<div class=\"box-header\">
									<h3>" . $row['title'] . "</h3>
								</div>
								<div class=\"box-content\">
									" . $row['content'] . "
								</div>
							</div>";
					}
					$result = null;
				}
			}
		}

		// Attach our centent
		$page->setTemplateVar( 'PageContent', $page_content );
		$page->setTemplateVar( "JQueryReadyScript", $JQueryReadyScripts );
	}

	//====================================
	// Handle Page Result hook
	//====================================
	public function handlePageResult( $searchVars ) {
		global $cmsMenus, $cmsMenus;

		$page_content = $JQueryReadyScripts = '';
		$this->prepMenus();

		// Cycle through our pages and handle the content

		// Attach our centent
		$page->setTemplateVar( 'PageContent', $page_content );
		$page->setTemplateVar( "JQueryReadyScript", $JQueryReadyScripts );
	}

	//====================================
	// Show Page hook
	//====================================
	public function handleAJAX( $arguments = array() ) {
		global $ftsdb, $page, $mbp_config, $cmsMenus, $actual_id, $actual_action, $actual_value,
		       $actual_type, $actual_showButtons, $actual_showClient, $actual_prefix, $item, $table;

		extract( (array) $arguments ); // Extract our arguments into variables
		$this->prepMenus();
		$noIncludes = 1;

		include( "modules/$this->prefix/ajax.php" );
	}

	//====================================
	// Home Page hook
	//====================================
	public function homePage() {
		global $actual_page, $page, $mbp_config;

		// Set the landing page as the homepage
		if ( $mbp_config['ftsmbp_cms_controlHomepage'] ) {
			$actual_page = $mbp_config['ftsmbp_cms_homepage'];

			callModuleHook( 'CMS', 'showPage', array(
				'module_page' => 'viewPage'
			) );
		}
	}

	//====================================
	// Change Page Template hook
	//====================================
	public function changePageTemplate() {
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
		$formFields = apply_filters( 'form_fields_cms_settings', array(
			'ftsmbp_cms_serial'           => array(
				'text' => 'Serial',
				'type' => 'text',
			),
			'ftsmbp_cms_controlHomepage'  => array(
				'text'          => 'Take over Homepage?',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			),
			'ftsmbp_cms_homepage'         => array(
				'text'    => 'Homepage',
				'type'    => 'select',
				'options' => getDropdownArray( 'cmsslugs' ),
			),
			'ftsmbp_cms_testimonials_url' => array(
				'text' => 'Testimonials URL (Pulls into Page)',
				'type' => 'text',
			),
		) );

		if ( $section == 'tabs' ) {
			$content = '
				<li><a href="#cmsSettings" data-toggle="tab"><span>' . __( 'CMS Settings' ) . '</span></a></li>';
		} elseif ( $section == 'content' ) {
			$content = '
				<div id="cmsSettings" class="tab-pane">
					' . makeFormFieldset( 'CMS Settings', $formFields, $mbp_config, 0 ) . '
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
		if ( ! isset( $_POST['ftsmbp_cms_controlHomepage'] ) ) {
			update_config_value( 'ftsmbp_cms_controlHomepage', 0 );
		}
	}

	//====================================
	// Sitemap Page hook
	//====================================
	public function sitemapPage( $arguments = array() ) {
		global $ftsdb, $page, $cmsMenus;

		extract( (array) $arguments ); // Extract our arguments into variables

		$sitemapLinks = array( 'Pages' => array() );

		$result = $ftsdb->select( DBTABLEPREFIX . "pages", "1 ORDER BY title", array(), 'id, title, slug' );

		if ( $result ) {
			foreach ( $result as $row ) {
				$sitemapLinks['Pages'][] = array(
					'link' => il( $cmsMenus['VIEWPAGE']['link'] . "&page=" . $row['slug'], 1 ),
					'name' => $row['title'],
				);
			}
			$result = null;
		}

		// Add the items to the page var
		$currentSitemapLinks = $page->getTemplateVar( "SitemapLinks" );
		if ( is_array( $currentSitemapLinks ) && count( $currentSitemapLinks ) > 0 ) {
			$sitemapLinks = array_merge( $sitemapLinks, $currentSitemapLinks );
		}
		$page->setTemplateVar( "SitemapLinks", $sitemapLinks );

		//return $sitemapLinks;
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
		global $menuvar;

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
		global $menuvar;

		extract( (array) $arguments ); // Extract our arguments into variables

		$content = "";

		if ( user_access( 'cms_dashboard_access' ) ) {
			if ( $section == 'content' ) {
			} elseif ( $section == 'jQuery' ) {
			}
		}

		return $content;
	}
}