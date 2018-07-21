<?php 
/***************************************************************************
 *                               menus.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=================================================
// Add a menu to the DB
//=================================================
function addMenu($name = "", $added_by = "", $prefix = "") {
	global $ftsdb;
	
	$result = $ftsdb->insert(DBTABLEPREFIX . 'menus', array(
		"name" => $name,
		"added_by" => $added_by,
		"prefix" => $prefix,
	));
	
	return $result;		
}

//=================================================
// Add a menu item to the DB
//=================================================
function addMenuItem($menu_id = "", $text = "", $link = "", $added_by = "", $prefix = "", $permissions = array(), $icon = "", $rel = "") {
	global $ftsdb;
	
	$role_ids = '';	
	if ( is_array( $permissions ) )
		$role_ids = str_replace( array( "\'", "'" ), '', implode( ',', array_keys( $permissions ) ) ) . ',';
	else
		$role_ids = $permissions;
	
	$result = $ftsdb->insert(DBTABLEPREFIX . 'menu_items', array(
		"text" => $text,
		"menu_id" => $menu_id,
		"icon" => $icon,
		"link" => $link,
		"added_by" => $added_by,
		"prefix" => $prefix,
		"rel" => $rel,
		"role_ids" => $role_ids,
		"order" => '999',
	));
	
	return $result;		
}


/**
 * Adds the menu items to our page instance.
 * 
 * @since 4.14.07.09
 * 
 * @access public
 * @return void
 */
function addMenusToPage() {
	global $ftsdb, $page, $menuvar; 
	
	// Top Menu		
	// The Top Menu hasn't always been in place so the id could be different for systems before the update
	addMenuItemsToPage( "(SELECT `id` FROM `" . DBTABLEPREFIX . "menus` WHERE name = 'Top Menu' AND added_by = 'System')", "top" );
	
	if ( $_SESSION['user_level'] == SYSTEM_ADMIN ) {
		$page->makeMenuItem( "top", "Configure", $menuvar['SETTINGS'] );
	}		
	
	// Footer Menu	
	// The Footer Menu hasn't always been in place so the id could be different for systems before the update	
	addMenuItemsToPage( "(SELECT `id` FROM `" . DBTABLEPREFIX . "menus` WHERE name = 'Footers Menu' AND added_by = 'System')", "footer" );
	
	// User Options Menu
	$page->makeMenuItem( "sidebar", "User Menu", "", "nav-header" );		
	if ( isset( $_SESSION['userid'] ) ) {		
		addMenuItemsToPage( "1", "sidebar" );
		$page->makeMenuItem( "sidebar", "Logout", $menuvar['LOGOUT'], '', '', 0, 'glyphicon glyphicon-off' );
	} else {
		$page->makeMenuItem( "sidebar", "Login", $menuvar['LOGIN'], '', '', 0, 'glyphicon glyphicon-lock' );
	}
	
	// Admin Options Menu			
	if ( isset( $_SESSION['userid'] ) ) {
		// Make sure we actually have some items for this menu, otherwise hide the title		
		$extraSQL_menuSelect = ( $_SESSION['user_level'] != SYSTEM_ADMIN ) ? " AND (role_ids LIKE '-1,%' OR role_ids LIKE :userLevel)" : "";
		$result = $ftsdb->select(DBTABLEPREFIX . "menu_items", "menu_id = '2'" . $extraSQL_menuSelect . " ORDER BY `order`", array(
			":userLevel" => '%' . $_SESSION['user_level'] . ',%'
		));			
		
		if ( $result ) {
			$page->makeMenuItem( "sidebar", "Admin Menu", "", "nav-header" );	
			foreach ( $result as $row ) {
				$page->makeMenuItem( "sidebar", $row['text'], $row['link'], '', $row['id'], $row['parent_id'], $row['icon'], $row['rel'] );
			}	
			$result = NULL;
		}
	}
}

//==================================================
// Checks if a menu item is in the database
//==================================================
function menu_item_exists( $menu_id, $text, $link, $added_by, $prefix = '' ) {
	global $ftsdb; 
	
	$exists = 0;
	$where = "text = :text AND menu_id = :menu_id AND link = :link AND added_by = :added_by";
	$data = array(
		":text" => $text,
		":menu_id" => $menu_id,
		":link" => $link,
		":added_by" => $added_by,
	);
	if ( !empty( $prefix ) ) {
		$where .= ' AND prefix = :prefix';
		$data['prefix'] = $prefix;
	}
	$results = $ftsdb->select( DBTABLEPREFIX . "menu_items", $where, $data );
	if ( $results && count( $results ) > 0 ) { $exists = 1; }
	$results = NULL;
	
	return $exists;
}

//=================================================
// Add a menu item to the nav bar
//=================================================
function addMenuItemsToPage($menu_id = "", $menu_name = "") {
	global $ftsdb, $page;
	
	// $menu_id is not secured, if you choose to use variables within it then it needs to be secured
	$extraSQL_menuSelect = ( $_SESSION['user_level'] != SYSTEM_ADMIN ) ? " AND (role_ids LIKE '-1,%' OR role_ids LIKE :userLevel)" : "";
	$result = $ftsdb->select(DBTABLEPREFIX . "menu_items", "menu_id IN ($menu_id)" . $extraSQL_menuSelect . " ORDER BY `order`", array(
		":userLevel" => '%' . $_SESSION['user_level'] . ',%'
	));
	
	if ($result) {
		foreach ($result as $row) {
			//echo "$menu_name, {$row['text']}, {$row['link']}, '', {$row['id']}, {$row['parent_id']}, {$row['icon']}<br />";
			$page->makeMenuItem( $menu_name, $row['text'], $row['link'], '', $row['id'], $row['parent_id'], $row['icon'], $row['rel'] );
		}	
		$result = NULL;
	}		
}

//=================================================
// Remove module menus from the DB
//=================================================
function removeMenusByPrefix($prefix) {
	global $ftsdb;
	
	$result = $ftsdb->delete(DBTABLEPREFIX . 'menus', "prefix = :prefix", array(
		":prefix" => $prefix
	));
	
	return $result;		
}

//=================================================
// Remove module menu items from the DB
//=================================================
function removeMenuItemsByPrefix($prefix) {
	global $ftsdb;
	
	$result = $ftsdb->delete(DBTABLEPREFIX . 'menu_items', "prefix = :prefix", array(
		":prefix" => $prefix
	));
	
	return $result;		
}

//=================================================
// Print the Menus Table
//=================================================
function printMenusTable() {
	global $ftsdb, $menuvar, $mbp_config;
	$returnVar = "";
	
	$result = $ftsdb->select(DBTABLEPREFIX . "menus", "1 ORDER BY name");
	// Add our data
	if (!$result) {
		$returnVar .= "There are no menus in the system.";
	} else {
		foreach ($result as $row) {
			$menuItems = array();
			$menuID = strtolower(str_replace(' ', '', $row['name']));
			$menuName = $row['name'];
			if ( $row['added_by'] != 'System' && $row['added_by'] != 'Module' ) {
				$menuName = '<span id="edit-menus-' . $row['id'] . '_name">' . $row['name'] . '</span> ' . createDeleteLinkWithImage($row['id'], "menu_" . $row['id'], "menus", "menu and all its items");
			}
			$returnVar .= "		
				<div id=\"menu_" . $row['id'] . "\">	
					<h2>" . $menuName . "</h2>
					<ol class=\"sortableList\" id=\"" . $menuID . "\">";
								
			$result2 = $ftsdb->select(DBTABLEPREFIX . "menu_items", "menu_id = :menu_id ORDER BY `order`", array(
				":menu_id" => intval($row['id'])
			));
			
			if ($result2) {
				foreach ($result2 as $row2) {
					$menuItems[$row2['parent_id']][$row2['id']] = array(
						'id' => $row2['id'],
						'added_by' => $row2['added_by'],
						'text' => $row2['text']
					);
				}
				$result2 = NULL;
			}
			
			// Add our data
			if (count($menuItems) == 0) {
				$returnVar .= "<li id=\"menusListDefaultItem_" . $menuID . "\"><div>There are no items for this menu, please add one using the form.</div></li>";
			} else {
				$returnVar .= returnMenuItemHTML($menuItems, 0);
			}
			$returnVar .= "
					</ol>
					<div id=\"menuUpdateNotice\"></div>
				</div>";
		}
		$result = NULL;
	}
	
	// Return the table's HTML
	return $returnVar;
}

//=================================================
// Returns the JQuery functions used to allow 
// in-place editing and table sorting
//=================================================
function returnMenusTableJQuery() {
	global $ftsdb, $menuvar, $mbp_config;	
	
	$JQueryReadyScripts = "";	
			
	// Allow us to edit the names of non system or module menus
	$JQueryReadyScripts = "
		var fields = $(\"#currentMenus span[id^='edit-menus-']\").map(function() { return this.id; }).get();
		options = {
			loadurl: SITE_URL + '/ajax.php?action=getitem&table=menus&item=name',
		};
		addEditable( fields, options );";
				
	$result = $ftsdb->select(DBTABLEPREFIX . "menus", "1 ORDER BY name");
	
	if ($result) {
		foreach ($result as $row) {				
			$menuID = strtolower(str_replace(' ', '', $row['name']));
			$JQueryReadyScripts .= "
				$('#" . $menuID . "').nestedSortable({
					disableNesting: 'no-nest',
					forcePlaceholderSize: true,
					handle: 'div',
					helper:	'clone',
					items: 'li',
					maxLevels: 3,
					opacity: .6,
					placeholder: 'placeholder',
					revert: 250,
					tabSize: 25,
					tolerance: 'pointer',
					toleranceElement: '> div',
					update: function(event, ui) {
						$.post('" . SITE_URL . "/ajax.php?action=saveMenuItems', { menuItems: $('#" . $menuID . "').nestedSortable('serialize') } );	
						/*
						$('#menuUpdateNotice').html('" . progressSpinnerHTML() . "');
						jQuery.post('" . SITE_URL . "/ajax.php?action=saveMenuItems', { menuItems: $('#" . $menuID . "').nestedSortable('serialize') }, function(data) {
							$('#menuUpdateNotice').html(data);
							$('#menuUpdateNotice').effect('highlight',{},500);
						});
						*/
					}
				});";
		
		}
		$result = NULL;
	}
	
	
	return $JQueryReadyScripts;
}

//=================================================
// Allows us to cycle through menus quickly
//=================================================
function returnMenuItemHTML($menuArray, $parentID) {
	global $menuvar, $mbp_config;
	$returnVar = "";
	
	if ( is_array( $menuArray[$parentID] ) ) {
		foreach ($menuArray[$parentID] as $order => $menuItemArray) {
			$returnVar .= '
					<li id="item_' . $menuItemArray['id'] . '">
						<div>
							<span class="btn-group"><a href="' . il($menuvar['MENUS'] . '&action=editmenuitem&id=' . $menuItemArray['id']) . '"  class="btn btn-default"><i class="glyphicon glyphicon-edit"></i></a> ' . (($menuItemArray['added_by'] != 'System' && $menuItemArray['added_by'] != 'Module') ? createDeleteLinkWithImage($menuItemArray['id'], 'menu_' . $menuItemArray['id'], 'menu_items', 'menu item') : '') . '</span>
							' . $menuItemArray['text'] . '
						</div>
						' . ((isset($menuArray[$menuItemArray['id']])) ? '<ol>' . returnMenuItemHTML($menuArray, $menuItemArray['id']) . '</ol>' : '') . '
					</li>';
		}
	}
	
	return $returnVar;
}

//=================================================
// Create a form to add new category
//=================================================
function printNewMenuForm() {
	global $menuvar, $mbp_config;
	
	$formFields = apply_filters( 'form_fields_menus_new', array(
		'name' => array(
			'text' => 'Menu Name',
			'type' => 'text',
			'class' => 'required',
		)
	));
	
	return makeForm('newMenu', il($menuvar['MENUS']), 'Add a Menu', 'Create Menu', $formFields, array(), 1);
}

//=================================================
// Returns the JQuery functions used to run the 
// new order form
//=================================================
function returnNewMenuFormJQuery($reprintTable = 0, $allowModification = 1) {
	$customSuccessFunction = ($reprintTable == 0) ? "" : "
					// Replace our menu list
					$('#updateMeMenus').html(data);
					" . returnMenusTableJQuery() . "
					// Show a success message
					$('#newMenuResponse').html(returnSuccessMessage('menu'));";
					
	return makeFormJQuery('newMenu', SITE_URL . "/ajax.php?action=createMenu&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification, '', 'category', '', $customSuccessFunction);
}

//=================================================
// Create a form to add new category
//=================================================
function printNewMenuItemForm() {
	global $ftsdb, $menuvar, $mbp_config;
	$roles = array();
	
	$result = $ftsdb->select(DBTABLEPREFIX . "roles", "1 ORDER BY id");
		
	if ($result) {
		foreach ($result as $row) {
			$roles[$row['id']] = $row['name'];
		}	
		$result = NULL;
	}
	
	// Create our new table
	$table = new tableClass('', '', '', "table table-striped table-bordered tablesorter", "editUserRolePermissionsTable");
	
	// Create table title
	$table->addNewRow(array(array('data' => "User Roles that can see this category", "colspan" => "20")), '', 'title1', 'thead');
	
	// Create column headers
	$headerDataArray = array();
	
	// Add our role names
	foreach ($roles as $id => $name) {
		array_push($headerDataArray, array('type' => 'th', 'data' => $name));
	}
	
	$table->addNewRow($headerDataArray, '', 'title2', 'thead');						
	
	// Add our checkboxes
	$rowDataArray = array();
	
	// Add our role checkboxes
	foreach ($roles as $id => $name) {
		array_push($rowDataArray, array('data' => "<input type=\"checkbox\" name=\"menuitempermissions['" . $id . "']\" value=\"1\" />"));
	}
	
	$table->addNewRow($rowDataArray, $row['id'] . "_row", "");

	$formFields = apply_filters( 'form_fields_menu_items_new', array(
		'text' => array(
			'text' => 'Menu Item Name',
			'type' => 'text',
			'class' => 'required',
		),
		'menu_id' => array(
			'text' => 'Menu',
			'type' => 'select',
			'options' => getDropdownArray('menus'),
			'class' => 'required',
		),
		'icon' => array(
			'text' => 'Icon',
			'type' => 'iconpicker',
		),
		'rel' => array(
			'text' => 'Rel',
			'type' => 'select',
			'options' => getDropdownArray('linkRelList'),
		),
		'link' => array(
			'text' => 'Link',
			'type' => 'text',
		),
		array(
			'type' => 'html',
			'value' => '-or-'
		),
		'page_id' => array(
			'text' => 'Page',
			'type' => 'select',
			'options' => getDropdownArray('pages'),
		),
		'permissions' => array(
			'type' => 'html',
			'value' => $table->returnTableHTML()
		),
	));
	
	return makeForm('newMenuItem', il($menuvar['MENUS']), 'Add a Menu Item', 'Add Menu Item', $formFields, array(), 1);
}

//=================================================
// Returns the JQuery functions used to run the 
// new order form
//=================================================
function returnNewMenuItemFormJQuery($reprintTable = 0, $allowModification = 1) {
	$customSuccessFunction = ($reprintTable == 0) ? "" : "
					// Build our menuID
					menuID = $('#menu_id option:selected').text().replace(' ', '').toLowerCase();
					// Clear the default row
					$('#menusListDefaultItem_' + menuID).remove();
					// Update the table with the new row
					$('#' + menuID).append(data);
					// Show a success message
					$('#newMenuItemResponse').html(returnSuccessMessage('menu item'));";
					
	return makeFormJQuery('newMenuItem', SITE_URL . "/ajax.php?action=createMenuItem&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification, '', 'category', '', $customSuccessFunction);
}

//=================================================
// Create a form to edit an  category
//=================================================
function printEditMenuItemForm($menuItemID) {
	global $ftsdb, $menuvar, $mbp_config;
	
	$roles = array();
	
	$result = $ftsdb->select(DBTABLEPREFIX . "roles", "1 ORDER BY id");
		
	if ($result) {
		foreach ($result as $row) {
			$roles[$row['id']] = $row['name'];
		}	
		$result = NULL;
	}		

	$result = $ftsdb->select(DBTABLEPREFIX . "menu_items", "id = :id LIMIT 1", array(
		":id" => $menuItemID
	));
	
	if ($result && count($result) == 0) {
		$content = "<span class=\"center\">There was an error while accessing the menu tem's details you are trying to update. You are being redirected to the main page.</span>
						<meta http-equiv=\"refresh\" content=\"5;url=" . il($menuvar['MENUS']) . "\">";	
	} else {
		$row = $result[0];
		$addedBy = ($row['added_by'] != "Module") ? $row['prefix'] . " " . $row['added_by'] : $row['added_by'];	
	
		// Create our new table
		$table = new tableClass('', '', '', "table table-striped table-bordered tablesorter", "editUserRolePermissionsTable");
		
		// Create table title
		$table->addNewRow(array(array('data' => "User Roles that can see this menu item", "colspan" => "20")), '', 'title1', 'thead');
		
		// Create column headers
		$headerDataArray = array();
		
		// Add our role names
		foreach ($roles as $id => $name) {
			array_push($headerDataArray, array('type' => 'th', 'data' => $name));
		}
		
		$table->addNewRow($headerDataArray, '', 'title2', 'thead');						
		
		// Add our checkboxes
		$rowDataArray = array();
		
		// Add our role checkboxes
		foreach ($roles as $id => $name) {
			array_push($rowDataArray, array('data' => "<input type=\"checkbox\" name=\"menuitempermissions['" . $id . "']\" value=\"1\"" . testChecked(true, ((strpos($row['role_ids'] . ',', $id . ',') === false) ? false : true) ) . " />"));
		}
		
		$table->addNewRow($rowDataArray, $row['id'] . "_row", "");
		
		$formFields = apply_filters( 'form_fields_menu_items_edit', array(
			'text' => array(
				'text' => 'Menu Item Name',
				'type' => 'text',
				'class' => 'required',
			),
			'menu_id' => array(
				'text' => 'Menu',
				'type' => 'select',
				'options' => getDropdownArray('menus'),
				'class' => 'required',
			),
			'icon' => array(
				'text' => 'Icon',
				'type' => 'iconpicker',
			),
			'rel' => array(
				'text' => 'Rel',
				'type' => 'select',
				'options' => getDropdownArray('linkRelList'),
			),
			'link' => array(
				'text' => 'Link',
				'type' => 'text',
			),
			array(
				'type' => 'html',
				'value' => '-or-'
			),
			'page_id' => array(
				'text' => 'Page',
				'type' => 'select',
				'options' => getDropdownArray('pages'),
			),
			'permissions' => array(
				'type' => 'html',
				'value' => $table->returnTableHTML()
			),
		));
		
		$content .= makeForm('editMenuItem', il($menuvar['MENUS'] . "&action=editmenuitem&id=" . $menuItemID), 'Edit Menu Item', 'Update Menu Item', $formFields, $row, 1);
			
		$result = NULL;
	}
	
	return $content;
}

//=================================================
// Returns the JQuery functions used to run the 
// edit  form
//=================================================
function returnEditMenuItemFormJQuery($menuItemID) {
	return makeFormJQuery('editMenuItem', SITE_URL . "/ajax.php?action=updateMenuItem&id=" . $menuItemID);
}