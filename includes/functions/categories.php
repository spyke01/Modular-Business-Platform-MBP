<?php 
/***************************************************************************
 *                               categories.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


	
//=========================================================
// Checks if we have access to a category
//=========================================================
 function category_access($id, $account = NULL, $reset = FALSE) {
	global $ftsdb;
		
	static $perm = array();
	$perms = array();
	
	if ($reset) {
		$perm = array();
	}
	
	// If a user isn't logged in their user level should be marked as anonymous
	$userID = (session_is_registered('userid')) ? $_SESSION['userid'] : 'anonymous';
	$userLevel = (session_is_registered('userid')) ? $_SESSION['user_level'] : ANONYMOUS_ACCESS;
	
	// System Admins have all privileges:
	if ($userLevel == SYSTEM_ADMIN) {
		return TRUE;
	}
		
	// To reduce the number of SQL queries, we cache the user's permissions
	// in a static variable.
	if (!isset($perm[$id])) {
		$result = $ftsdb->select(DBTABLEPREFIX . "categories", "role_ids LIKE :userLevel", array(
			":userLevel" => '%' . $userLevel . ',%'
		), 'id');
		
		if ($result) {
			foreach ($result as $row) {
				$perms[$row['id']] = '';
			}	
			$result = NULL;
		}
		
		$perm[$userID] = $perms;
	}
	
	//print_r($perm);
	return isset($perm[$userID][$id]);		
}

//=================================================
// Returns Category Name from the ID
//=================================================
function getCatNameByID($catID) {
	return getDatabaseItem('categories', 'name', $catID);
}

//=================================================
// Returns Category color from the ID
//=================================================
function getCatColorByID($catID) {
	return getDatabaseItem('categories', 'color', $catID);
}

//=========================================================
// Gets a list of TicketCategoryIDs we have access to
//=========================================================
function getCategoryIDs() {
	global $ftsdb;
		
	$categoryIDs = "";		

	// For master clients and account executives
	$extraSQL = ($_SESSION['user_level'] == SYSTEM_ADMIN) ? "1" : "role_ids LIKE :userLevel";
	$result = $ftsdb->select(DBTABLEPREFIX . "categories", $extraSQL . " ORDER BY name", array(
		":userLevel" => '%' . $userLevel . ',%'
	), 'id');
	
	if ($result) {
		foreach ($result as $row) {
			$categoryIDs .= ", " . $row['id'];
		}	
		$result = NULL;
	}
	// Remove first ", "
	$categoryIDs = substr($categoryIDs, 2);

	
	return $categoryIDs;
}

//=================================================
// Add a category to the DB
//=================================================
function addCategory($menu_id = "", $text = "", $link = "", $added_by = "", $prefix = "", $permissions = "") {
	global $ftsdb;
	
	if (count($menuitempermissions)) {
		$role_ids = str_replace("\'", '', implode(',', array_keys($permissions))) . ',';
	}
	
	$result = $ftsdb->insert(DBTABLEPREFIX . 'categories', array(
		"text" => $text,
		"menu_id" => $menu_id,
		"link" => $link,
		"added_by" => $added_by,
		"prefix" => $prefix,
		"role_ids" => $role_ids,
		"order" => '999',
	));
	
	return $result;		
}

//=================================================
// Print the Categories Table
//=================================================
function printCategoriesTable() {
	global $ftsdb, $menuvar, $mbp_config, $CATEGORY_TYPE;
	$returnVar = "";
	$categoryTypes = apply_filters( 'constants_category_type', $CATEGORY_TYPE );
	
	foreach ( $categoryTypes as $type => $name ) {
		$categories = array();
		$typeID = strtolower(str_replace(' ', '', $name));
		$returnVar .= "		
			<div id=\"type_" . $typeID . "\">	
				<h2>$name Categories</h2>
				<ol class=\"sortableList\" id=\"" . $typeID . "\">";
							
		$result2 = $ftsdb->select(DBTABLEPREFIX . "categories", "type = :type ORDER BY `order`", array(
			":type" => $type
		));
		
		if ($result2) {
			foreach ($result2 as $row2) {
				$categories[$row2['parent_id']][$row2['id']] = array(
					'id' => $row2['id'],
					'name' => $row2['name'],
					'disableNesting' => ($type == 2) ? 0 : 1
				);
			}
			$result2 = NULL;
		}
		//print_r($categories);
		
		// Add our data
		if (count($categories) == 0) {
			$returnVar .= "<li id=\"categoriesListDefaultItem" . $typeID . "\"><div>There are no categories of this type in the system, please add one using the form.</div></li>";
		} else {
			$returnVar .= returnCategoryHTML($categories, 0);
		}
		$returnVar .= "
				</ol>
			</div>";
	}
	
	// Return the table's HTML
	return $returnVar;
}

//=================================================
// Returns the JQuery functions used to allow 
// in-place editing and table sorting
//=================================================
function returnCategoriesTableJQuery() {
	global $menuvar, $mbp_config, $CATEGORY_TYPE;	
	
	$JQueryReadyScripts = "";
	$categoryTypes = apply_filters( 'constants_category_type', $CATEGORY_TYPE );
				
	foreach ( $categoryTypes as $type => $name ) {
		$categories = array();
		$typeID = strtolower(str_replace(' ', '', $name));
		$JQueryReadyScripts .= "
			$('#" . $typeID . "').nestedSortable({
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
					$.post('" . SITE_URL . "/ajax.php?action=saveCategories', { categories: $('#" . $typeID . "').nestedSortable('serialize') } );
				}
			});";
	}
	
	return $JQueryReadyScripts;
}

//=================================================
// Allows us to cycle through menus quickly
//=================================================
function returnCategoryHTML($inputArray, $parentID) {
	global $menuvar, $mbp_config;
	$returnVar = "";
	
	foreach ($inputArray[$parentID] as $order => $categoryArray) {
		$returnVar .= '
				<li id="cat_' . $categoryArray['id'] . '"' . (($categoryArray['disableNesting']) ? ' class="no-nest"' : '') . '>
					<div>
						<span class="btn-group"><a href="' . il($menuvar['CATEGORIES'] . '&action=editcategory&id=' . $categoryArray['id']) . '"  class="btn btn-default"><i class="glyphicon glyphicon-edit"></i></a> ' . createDeleteLinkWithImage($categoryArray['id'], 'cat_' . $categoryArray['id'], 'categories', 'category') . '</span>
						' . $categoryArray['name'] . '
					</div>
					' . ((isset($inputArray[$categoryArray['id']])) ? '<ol>' . returnCategoryHTML($inputArray, $categoryArray['id']) . '</ol>' : '') . '
				</li>';
	}
	
	return $returnVar;
}

//=================================================
// Create a form to add new category
//=================================================
function printNewCategoryForm() {
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
	$table->addNewRow(array(array('data' => "User Roles that can see this category", "colspan" => "11")), '', 'title1', 'thead');
	
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
		array_push($rowDataArray, array('data' => "<input type=\"checkbox\" name=\"categorypermissions['" . $id . "']\" value=\"1\" />"));
	}
	
	$table->addNewRow($rowDataArray, $row['id'] . "_row", "");
	
	$formFields = apply_filters( 'form_fields_categories_new', array(
		'name' => array(
			'text' => 'Category Name',
			'type' => 'text',
			'class' => 'required',
		),
		'type' => array(
			'text' => 'Type',
			'type' => 'select',
			'options' => getDropdownArray('categorytypes'),
		),
		'color' => array(
			'text' => 'Color',
			'type' => 'colorpicker'
		),
		'tags' => array(
			'text' => 'Tags',
			'type' => 'text',
		),
		'permissions' => array(
			'type' => 'html',
			'value' => $table->returnTableHTML()
		),
	));
	
	return makeForm('newCategory', il($menuvar['CATEGORIES']), 'New Category', 'Create Category', $formFields, array(), 1);
}

//=================================================
// Returns the JQuery functions used to run the 
// new order form
//=================================================
function returnNewCategoryFormJQuery($reprintTable = 0, $allowModification = 1) {
	$customSuccessFunction = ($reprintTable == 0) ? "" : "
					// Build our menuID
					type = $('#type option:selected').text().replace(' ', '').toLowerCase();
					// Clear the default row
					$('#categoriesListDefaultItem' + type).remove();
					// Update the table with the new row
					$('#' + type).append(data);
					// Show a success message
					$('#newCategoryResponse').html(returnSuccessMessage('category'));";
	
	return makeFormJQuery('newCategory', SITE_URL . "/ajax.php?action=createCategory&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification, '', 'category', '', $customSuccessFunction);
}

//=================================================
// Create a form to edit an  category
//=================================================
function printEditCategoryForm($catID) {
	global $ftsdb, $menuvar, $mbp_config;
	$roles = array();
	
	$result = $ftsdb->select(DBTABLEPREFIX . "roles", "1 ORDER BY id");
		
	if ($result) {
		foreach ($result as $row) {
			$roles[$row['id']] = $row['name'];
		}	
		$result = NULL;
	}
	

	$result = $ftsdb->select(DBTABLEPREFIX . "categories", "id = :id LIMIT 1", array(
		":id" => $catID
	));
	
	if ($result && count($result) == 0) {
		$content = "<span class=\"center\">There was an error while accessing the category's details you are trying to update. You are being redirected to the main page.</span>
						<meta http-equiv=\"refresh\" content=\"5;url=" . il($menuvar['CATEGORIES']) . "\">";	
	} else {
		$row = $result[0];
	
		// Create our new table
		$table = new tableClass('', '', '', "table table-striped table-bordered tablesorter", "editUserRolePermissionsTable");
		
		// Create table title
		$table->addNewRow(array(array('data' => "User Roles that can see this category", "colspan" => "11")), '', 'title1', 'thead');
		
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
			array_push($rowDataArray, array('data' => "<input type=\"checkbox\" name=\"categorypermissions['" . $id . "']\" value=\"1\"" . testChecked(true, ((strpos($row['role_ids'] . ',', $id . ',') === false) ? false : true) ) . " />"));
		}
		
		$table->addNewRow($rowDataArray, $row['id'] . "_row", "");
	
		$formFields = apply_filters( 'form_fields_categories_edit', array(
			'name' => array(
				'text' => 'Category Name',
				'type' => 'text',
				'class' => 'required',
			),
			'type' => array(
				'text' => 'Type',
				'type' => 'select',
				'options' => getDropdownArray('categorytypes'),
			),
			'color' => array(
				'text' => 'Color',
				'type' => 'colorpicker'
			),
			'tags' => array(
				'text' => 'Tags',
				'type' => 'text',
			),
			'permissions' => array(
				'type' => 'html',
				'value' => $table->returnTableHTML()
			),
		));
		
		$content = makeForm('editCategory', il($menuvar['CATEGORIES'] . "&action=editcategory&id=" . $catID), 'Edit Category', 'Update Category', $formFields, $row, 1);
		
		$result = NULL;
	}
	
	return $content;
}

//=================================================
// Returns the JQuery functions used to run the 
// edit  form
//=================================================
function returnEditCategoryFormJQuery($catID) {
	return makeFormJQuery('editCategory', SITE_URL . "/ajax.php?action=updateCategory&id=" . $catID);
}