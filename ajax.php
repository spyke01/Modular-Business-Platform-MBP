<?php
/***************************************************************************
 *                               ajax.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/


/* Define our Paths */
namespace App;

define( 'ABSPATH', dirname( __FILE__ ) . '/' );
define( 'BASEPATH', rtrim( ABSPATH, '/' ) );

include BASEPATH . '/includes/header.php';
//$ftsdb->profile = 1; // Uncomment for debugging purposes

$actual_id          = intval( $_GET['id'] );
$actual_action      = keepsafe( $_REQUEST['action'] );
$actual_dataset     = keepsafe( $_REQUEST['dataset'] );
$actual_prefix      = keepsafe( $_REQUEST['prefix'] );
$actual_showButtons = intval( $_GET['showButtons'] );
$actual_showClient  = intval( $_GET['showClient'] );
$actual_type        = keepsafe( $_GET['type'] );
$actual_value       = keepsafe( $_GET['value'] );
$item               = keepsafe( $_GET['item'] );
$table              = keepsafe( $_GET['table'] );

// Log this page load
if ( isset( $_SESSION['userid'] ) ) {
	$message = 'Loading Action: ' . $actual_action;
	addLogEvent( [
		'type'     => LOG_TYPE_PAGE,
		'message'  => $message,
		'assoc_id' => $_SESSION['userid'],
	] );
}

//================================================
// Main updater and get functions
//================================================
// Update an item in a DB table
if ( $actual_action == "updateitem" && user_access( $actual_action ) ) {
	$updateto = $_REQUEST['value'];
	$updateto = ( $table == "notes" && $item == "text" ) ? preg_replace( '/\<br(\s*)?\/?\>/i', "\n", $updateto ) : $updateto;
	$updateto = ( $item == "datetimestamp" || $item == "date_ordered" || $item == "date_shipped" ) ? strtotime( keeptasafe( $updateto ) ) : keeptasafe( $updateto );

	// Client admins can only modify certain tables
	if ( $_SESSION['user_level'] == SYSTEM_ADMIN || ( $_SESSION['user_level'] == APPLICATION_ADMIN && ( $table != "config" || $table != "products" || $table != "users" ) ) ) {
		$table = ( $table == "users" ) ? USERSDBTABLEPREFIX . $table : DBTABLEPREFIX . $table;

		//==================================================
		// Module hook for pre update changes
		//==================================================
		callModuleHook( '',
			'handleAJAX',
			[
				'section' => 'before',
			] );

		$result = $ftsdb->update( $table,
			[
				$item => $updateto,
			],
			"id = :id",
			[
				":id" => $actual_id,
			]
		);

		if ( $item == "datetimestamp" || $item == "date_ordered" || $item == "date_shipped" ) {
			$result = ( trim( $updateto ) != "" ) ? makeDateTime( $updateto ) : "";
			echo $result;
		} elseif ( $item == "discount" ) {
			echo formatCurrency( $updateto );
		} elseif ( $item == "note" ) {
			echo ajaxnl2br( $updateto );
		} else {
			echo stripslashes( $updateto );
		}
	}
} // Get an item from a DB table
elseif ( $actual_action == "getitem" && user_access( $actual_action ) ) {
	// Client admins can only modify certain tables
	if ( $_SESSION['user_level'] == SYSTEM_ADMIN || ( $_SESSION['user_level'] == APPLICATION_ADMIN && ( $table != "config" || $table != "products" || $table != "users" ) ) ) {
		$table = ( $table == "users" ) ? USERSDBTABLEPREFIX . $table : DBTABLEPREFIX . $table;

		$result = $ftsdb->select( $table,
			"id = :id",
			[
				":id" => $actual_id,
			],
			$item );
		if ( $result ) {
			foreach ( $result as $row ) {
				if ( $item == "datetimestamp" || $item == "date_ordered" || $item == "date_shipped" ) {
					$returnVar = ( trim( $row[ $item ] ) != "" ) ? makeShortDateTime( $row[ $item ] ) : "";
					echo $returnVar;
				} elseif ( $item == "note" ) {
					echo $row[ $item ];
				} else {
					echo bbcode( $row[ $item ] );
				}
			}
			$result = null;
		}
	}
} // Delete a row from a DB table
elseif ( $actual_action == "deleteitem" && user_access( $actual_action ) ) {
	$errorCount = 0;

	// Client admins can only modify certain tables
	if ( $_SESSION['user_level'] == SYSTEM_ADMIN || ( $_SESSION['user_level'] == APPLICATION_ADMIN && ( $table != "config" || $table != "products" || $table != "users" ) ) ) {
		//==================================================
		// Module hook for pre deletion changes
		//==================================================		
		callModuleHook( '',
			'handleAJAX',
			[
				'section' => 'before',
			] );

		// When we delete a menu we delete all of it's items
		if ( $table == "menus" ) {
			$result     = $ftsdb->delete( DBTABLEPREFIX . "menu_items",
				"menu_id = :id",
				[
					":id" => $actual_id,
				] );
			$errorCount += ( $result ) ? 0 : 1;
		}

		$table = ( $table == "users" ) ? USERSDBTABLEPREFIX . $table : DBTABLEPREFIX . $table;

		// Delete actual table row
		$result     = $ftsdb->delete( $table,
			"id = :id",
			[
				":id" => $actual_id,
			] );
		$errorCount += ( $result ) ? 0 : 1;

		$success = ( $errorCount == 0 ) ? 1 : 0;

		echo $success;
	}
}

//================================================
// Handles server-side processing for data tables
//================================================
elseif ( $actual_action == "dataTables" && user_access( $actual_action . '_' . $actual_dataset ) ) {
	$returnVar = '';
	global $tableColumns;

	// TODO: This section is not currently used, we need to change the function to use the new DatTables style of array
	/*
	$columns = [
		[ 'db' => 'first_name', 'dt' => 0 ],
		[ 'db' => 'last_name',  'dt' => 1 ],
		[ 'db' => 'position',   'dt' => 2 ],
		[ 'db' => 'office',     'dt' => 3 ],
		[
			'db'        => 'start_date',
			'dt'        => 4,
			'formatter' => function( $d, $row ) {
				return date( 'jS M y', strtotime($d));
			}
		],
		[
			'db'        => 'salary',
			'dt'        => 5,
			'formatter' => function( $d, $row ) {
				return '$'.number_format($d);
			}
		)
	);
	 */

	switch ( $actual_dataset ) {
		case 'latestLogEntriesReport':
			$returnVar = returnDataTablesJSON( array_keys( $tableColumns['table_log_events_report'] ), DBTABLEPREFIX . 'logging' );
			break;
		case 'userDetailsReport':
			$returnVar = returnDataTablesJSON( array_keys( $tableColumns['table_users_report'] ), USERSDBTABLEPREFIX . 'users' );
			break;
	}
	echo $returnVar;
}

//================================================
// Add a category in the database
//================================================
elseif ( $actual_action == "createCategory" && user_access( $actual_action ) ) {
	$name                = keeptasafe( $_POST['name'] );
	$type                = keeptasafe( $_POST['type'] );
	$color               = keeptasafe( $_POST['color'] );
	$tags                = keeptasafe( $_POST['tags'] );
	$categorypermissions = keeparraysafe( $_POST['categorypermissions'] );

	if ( count( $categorypermissions ) ) {
		$role_ids = str_replace( "\'", '', implode( ',', array_keys( $categorypermissions ) ) ) . ',';
	}

	$result     = $ftsdb->insert( DBTABLEPREFIX . 'categories',
		[
			"name"     => $name,
			'type'     => $type,
			"color"    => $color,
			"tags"     => $tags,
			"role_ids" => $role_ids,
		] );
	$categoryID = $ftsdb->lastInsertId();

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully created category!</span>" : "	<span class=\"redText bold\">Failed to create category!!!</span>";

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$buttonData = ( $actual_showButtons == 1 ) ? '<a href="' . il( $menuvar['CATEGORIES'] . '&action=editcategory&id=' . $categoryID ) . '" class="btn btn-default"><i class="glyphicon glyphicon-edit"></i></a> ' : '';
			$buttonData .= ( $actual_showButtons == 1 ) ? createDeleteLinkWithImage( $categoryID, 'cat_' . $categoryID, 'categories', 'category' ) : '';

			$listHTML = '
				<li id="cat_' . $categoryID . '">
					<div>
						<span class="btn-group">' . $buttonData . '</span>
						' . $name . '
					</div>
				</li>';

			echo $listHTML;
			break;
		default:
			echo $content;
			break;
	}
}

//================================================
// Update our category in the database
//================================================
elseif ( $actual_action == "updateCategory" && user_access( $actual_action ) ) {
	// Sanitize			
	$role_ids            = '';
	$name                = keeptasafe( $_POST['name'] );
	$type                = keeptasafe( $_POST['type'] );
	$color               = keeptasafe( $_POST['color'] );
	$tags                = keeptasafe( $_POST['tags'] );
	$categorypermissions = keeparraysafe( $_POST['categorypermissions'] );

	if ( count( $categorypermissions ) ) {
		$role_ids = str_replace( "\'", '', implode( ',', array_keys( $categorypermissions ) ) ) . ',';
	}

	$result = $ftsdb->update( DBTABLEPREFIX . "categories",
		[
			"name"     => $name,
			'type'     => $type,
			"color"    => $color,
			"tags"     => $tags,
			"role_ids" => $role_ids,
		],
		"id = :id",
		[
			":id" => $actual_id,
		]
	);

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully updated category!</span>" : "	<span class=\"redText bold\">Failed to update category!!!</span>";
	echo $content;
}

//================================================
// Saves the order of our categories to the database
//================================================
elseif ( $actual_action == "saveCategories" && user_access( $actual_action ) ) {
	parse_str( $_POST['categories'], $orderArray );
	//print_r($orderArray);

	$orderCount = [];
	if ( is_array( $orderArray ) && count( $orderArray['cat'] ) ) {
		foreach ( $orderArray['cat'] as $id => $parent ) {
			$parent = ( $parent == 'root' ) ? 0 : $parent;
			$order  = ( ! isset( $orderCount[ $parent ] ) ) ? 1 : $orderCount[ $parent ];

			$result = $ftsdb->update( DBTABLEPREFIX . "categories",
				[
					"order"     => $order,
					"parent_id" => $parent,
				],
				"id = :id",
				[
					":id" => $id,
				]
			);
			//echo "$sql<br />";
			$orderCount[ $parent ] = $order + 1;
		}
	}
}

//================================================
// Send an email template in the database
//================================================
elseif ( $actual_action == "sendEmailTemplate" && user_access( 'email_users_access' ) ) {
	$template_id = keepsafe( $_POST['template_id'] );
	$users       = array_map( 'keepsafe', (array) $_POST['users'] );
	//print_r($_POST);

	$errors = parseAndSendTemplateExists( $template_id, $users );

	$result = '<span class="greenText bold">Your email template has been sent to the selected users. They should receive it shortly, if they fail to receive it have them check their junk mail folder.</span>';

	if ( count( $errors ) > 0 ) {
		$result .= '<div class="redText bold">The following errors occurred when sending your emails:<br />' . implode( '<br />', $errors ) . '</div>';
	}

	echo $result;
}

//================================================
// Add an email template in the database
//================================================
elseif ( $actual_action == "createEmailTemplate" && user_access( 'email_templates_create' ) ) {
	$name        = keeptasafe( $_POST['name'] );
	$template_id = str_replace( ' ', '-', strtolower( $_POST['name'] ) );
	$subject     = keeptasafe( $_POST['subject'] );
	$message     = $_POST['message'];
	$added_by    = $_SESSION['username'];

	$result          = addEmailTemplate( $template_id, $name, $subject, $message, $added_by );
	$emailTemplateID = $ftsdb->lastInsertId();

	$content = ( $result ) ? '	<span class="greenText bold">Successfully created email template!</span>' : '	<span class="redText bold">Failed to create email template!!!</span>';

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		/* TODO: Need to handle additional columns here
			We may just want to reprint the entire table to avoid this mess */
		case 1:
			$finalCol = '';
			if ( $actual_showButtons == 1 ) {
				$finalCol = ( user_access( 'email_templates_edit' ) ) ? '<a href="' . il( $menuvar['EMAILUSERS'] . '&action=editEmailTemplate&id=' . $emailTemplateID ) . '" class="btn btn-default"><i class="glyphicon glyphicon-edit"></i></a> ' : '';
				$finalCol .= ( user_access( 'email_templates_delete' ) ) ? createDeleteLinkWithImage( $emailTemplateID, $emailTemplateID . "_row", "email_templates", "email template" ) : '';
			}

			$listHTML = '
				<tr class="even" id="' . $userID . '_row">
					<td>' . $name . '</td>
					<td>' . $subject . '</td>
					<td>' . $added_by . '</td>
					<td class="center"><span class="btn-group">' . $finalCol . '</span></td>
				</tr>';

			echo $listHTML;
			break;
		default:
			echo $content;
			break;
	}
}

//================================================
// Update our email template in the database
//================================================
elseif ( $actual_action == "editEmailTemplate" && user_access( 'email_templates_edit' ) ) {
	// Sanitize			
	$name    = keeptasafe( $_POST['name'] );
	$subject = keeptasafe( $_POST['subject'] );
	$message = $_POST['message'];

	$result = $ftsdb->update( DBTABLEPREFIX . "email_templates",
		[
			"name"    => $name,
			"subject" => $subject,
			"message" => $message,
		],
		"id = :id",
		[
			":id" => $actual_id,
		]
	);
	//echo $sql;

	$content = ( $result ) ? '	<span class="greenText bold">Successfully updated email template!</span>' : '	<span class="redText bold">Failed to update email template!!!</span>';
	echo $content;
}

//================================================
// Add a menu in the database
//================================================
elseif ( $actual_action == "createMenu" && user_access( $actual_action ) ) {
	$name     = keeptasafe( $_POST['name'] );
	$added_by = keeptasafe( $_SESSION['username'] );

	$result     = addMenu( $name, $added_by );
	$menuItemID = $ftsdb->lastInsertId();

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully created menu!</span>" : "	<span class=\"redText bold\">Failed to create menu!!!</span>";

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			echo printMenusTable();
			break;
		default:
			echo $content;
			break;
	}
}

//================================================
// Add a menu item in the database
//================================================
elseif ( $actual_action == "createMenuItem" && user_access( $actual_action ) ) {
	$text                = keeptasafe( $_POST['text'] );
	$menu_id             = intval( $_POST['menu_id'] );
	$icon                = keeptasafe( $_POST['icon'] );
	$rel                 = keeptasafe( $_POST['rel'] );
	$link                = keeptasafe( $_POST['link'] );
	$added_by            = $_SESSION['username'];
	$menuitempermissions = keeparraysafe( $_POST['menuitempermissions'] );

	$result     = addMenuItem( $menu_id, $text, $link, $added_by, '', $menuitempermissions, $icon, $rel );
	$menuItemID = $ftsdb->lastInsertId();

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully created menu item!</span>" : "	<span class=\"redText bold\">Failed to create menu item!!!</span>";

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$buttonData = ( $actual_showButtons == 1 ) ? '<a href="' . il( $menuvar['MENUS'] . '&action=editmenuitem&id=' . $menuItemID ) . '" class="btn btn-default"><i class="glyphicon glyphicon-edit"></i></a> ' : '';
			$buttonData .= ( $actual_showButtons == 1 ) ? createDeleteLinkWithImage( $menuItemID, 'page_' . $menuItemID, 'menu_items', 'menu item' ) : '';

			$listHTML = '
				<li id="page_' . $menuItemID . '">
					<div>
						<span class="btn-group">' . $buttonData . '</span>
						' . $text . '
					</div>
				</li>';

			echo $listHTML;
			break;
		default:
			echo $content;
			break;
	}
}

//================================================
// Update our menu item in the database
//================================================
elseif ( $actual_action == "updateMenuItem" && user_access( $actual_action ) ) {
	// Sanitize			
	$role_ids            = '';
	$text                = keeptasafe( $_POST['text'] );
	$menu_id             = intval( $_POST['menu_id'] );
	$icon                = keeptasafe( $_POST['icon'] );
	$rel                 = keeptasafe( $_POST['rel'] );
	$link                = keeptasafe( $_POST['link'] );
	$menuitempermissions = keeparraysafe( $_POST['menuitempermissions'] );

	if ( count( $menuitempermissions ) ) {
		$role_ids = str_replace( [ "\'", "'" ], '', implode( ',', array_keys( $menuitempermissions ) ) ) . ',';
	}

	$result = $ftsdb->update( DBTABLEPREFIX . "menu_items",
		[
			"text"     => $text,
			"menu_id"  => $menu_id,
			"icon"     => $icon,
			"rel"      => $rel,
			"link"     => $link,
			"role_ids" => $role_ids,
		],
		"id = :id",
		[
			":id" => $actual_id,
		]
	);
	//echo $sql;

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully updated menu item!</span>" : "	<span class=\"redText bold\">Failed to update menu item!!!</span>";
	echo $content;
}

//================================================
// Saves the order of our menu items to the database
//================================================
elseif ( $actual_action == "saveMenuItems" && user_access( $actual_action ) ) {
	parse_str( $_POST['menuItems'], $orderArray );
	//print_r($orderArray);

	$orderCount = [];
	if ( is_array( $orderArray ) && count( $orderArray['item'] ) ) {
		foreach ( $orderArray['item'] as $id => $parent ) {
			$parent = ( $parent == 'root' ) ? 0 : $parent;
			$order  = ( ! isset( $orderCount[ $parent ] ) ) ? 1 : $orderCount[ $parent ];
			$result = $ftsdb->update( DBTABLEPREFIX . "menu_items",
				[
					"order"     => $order,
					"parent_id" => $parent,
				],
				"id = :id",
				[
					":id" => $id,
				]
			);
			//echo "$sql<br />";
			$orderCount[ $parent ] = $order + 1;
		}
	}
}

//================================================
// Add our users to the database
//================================================
elseif ( $actual_action == "createUser" && user_access( 'users_create' ) ) {
	if ( ! canHaveMultipleUsers() && getUserCount() > 0 ) {
		echo return_error_alert( 'Your license only allows for 1 user account. To add additional users you need to upgrade to a paid license. <a href="https://www.fasttracksites.com/product/license-renewal/">Click here to purchase a new license.</a>' );
	} else {
		$datetimestamp = time();
		$first_name    = keeptasafe( $_POST['first_name'] );
		$last_name     = keeptasafe( $_POST['last_name'] );
		$email_address = sanitize_email( $_POST['email_address'] );
		$username      = keepsafe( $_POST['username'] );
		$password      = keeptasafe( $_POST['password'] );
		$password2     = keeptasafe( $_POST['password2'] );
		$company       = keeptasafe( $_POST['company'] );
		$title         = keeptasafe( $_POST['title'] );
		$website       = keeptasafe( $_POST['website'] );
		$phone_number  = keeptasafe( $_POST['phone_number'] );
		$facebook      = keeptasafe( $_POST['facebook'] );
		$twitter       = keeptasafe( $_POST['twitter'] );
		$google_plus   = keeptasafe( $_POST['google_plus'] );
		$pinterest     = keeptasafe( $_POST['pinterest'] );
		$instagram     = keeptasafe( $_POST['instagram'] );
		$linkedin      = keeptasafe( $_POST['linkedin'] );
		$user_level    = keeptasafe( $_POST['user_level'] );
		$send_msg      = intval( $_POST['send_msg'] );

		if ( $password == $password2 ) {
			$result = $ftsdb->insert( USERSDBTABLEPREFIX . 'users',
				[
					"first_name"    => $first_name,
					"last_name"     => $last_name,
					"email_address" => $email_address,
					"username"      => $username,
					"password"      => md5( $password ),
					"company"       => $company,
					"title"         => $title,
					"website"       => $website,
					"phone_number"  => $phone_number,
					"facebook"      => $facebook,
					"twitter"       => $twitter,
					"google_plus"   => $google_plus,
					"pinterest"     => $pinterest,
					"instagram"     => $instagram,
					"linkedin"      => $linkedin,
					"signup_date"   => mysqldatetime(),
					"user_level"    => $user_level,
				] );
			$userID = $ftsdb->lastInsertId();

			if ( $send_msg ) {
				$dataArray = [
					'site_title' => $mbp_config['ftsmbp_site_name'],
					'site_url'   => site_url(),
					'username'   => $username,
					'password'   => $password,
				];
				$success   = emailMessage( $email_address,
					parseForTagsFromArray( getEmailTemplateSubjectFromID( 'mbp-account-created' ), $dataArray ),
					parseForTagsFromArray( getEmailTemplateMessageFromID( 'mbp-account-created' ), $dataArray )
				);
			}

			$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully created user (" . $last_name . ", " . $first_name . ")!</span>" : "	<span class=\"redText bold\">Failed to create user (" . $last_name . ", " . $first_name . ")!!!</span>";
		} else {
			$content = "<span class=\"redText bold\">The passwords you supplied do not match. Please fix this.</span>";
		}

		switch ( keepsafe( $_GET['reprinttable'] ) ) {
			/* TODO: Need to handle additional columns here
				We may just want to reprint the entire table to avoid this mess */
			case 1:
				$finalColumnData = ( $actual_showButtons == 1 ) ? '<a href="' . $menuvar['USERS'] . '&amp;action=edituser&amp;id=' . $userID . '"  class="btn btn-default"><i class="glyphicon glyphicon-edit"></i></a> ' . createDeleteLinkWithImage( $userID, $userID . '_row', 'users', 'user' ) : '';

				$tableHTML = '
					<tr class="even" id="' . $userID . '_row">
						<td>' . $username . '</td>
						<td>' . $email_address . '</td>
						<td>' . $first_name . ' ' . $last_name . '</td>
						<td>' . makeDate( $datetimestamp ) . '</td>
						<td>' . getUserlevelFromID( $userID ) . '</td>
						<td class="center"><span  class="btn-group">' . $finalColumnData . '</span></td>
					</tr>';

				echo $tableHTML;
				break;
			default:
				echo $content;
				break;
		}
	}
}

//================================================
// Update our users in the database
//================================================
elseif ( $actual_action == "editUser" && user_access( 'users_edit' ) ) {
	$first_name    = keeptasafe( $_POST['first_name'] );
	$last_name     = keeptasafe( $_POST['last_name'] );
	$email_address = sanitize_email( $_POST['email_address'] );
	$username      = keepsafe( $_POST['username'] );
	$password      = keeptasafe( $_POST['password'] );
	$password2     = keeptasafe( $_POST['password2'] );
	$company       = keeptasafe( $_POST['company'] );
	$title         = keeptasafe( $_POST['title'] );
	$website       = keeptasafe( $_POST['website'] );
	$phone_number  = keeptasafe( $_POST['phone_number'] );
	$facebook      = keeptasafe( $_POST['facebook'] );
	$twitter       = keeptasafe( $_POST['twitter'] );
	$google_plus   = keeptasafe( $_POST['google_plus'] );
	$pinterest     = keeptasafe( $_POST['pinterest'] );
	$instagram     = keeptasafe( $_POST['instagram'] );
	$linkedin      = keeptasafe( $_POST['linkedin'] );
	$user_level    = keeptasafe( $_POST['user_level'] );
	$send_msg      = intval( $_POST['send_msg'] );

	if ( canAccessUser( $actual_id ) ) {
		$currentUserData = getUser( $actual_id );

		if ( $password == $password2 ) {
			$userData = [
				"first_name"    => $first_name,
				"last_name"     => $last_name,
				"email_address" => $email_address,
				"username"      => $username,
				"company"       => $company,
				"title"         => $title,
				"website"       => $website,
				"phone_number"  => $phone_number,
				"facebook"      => $facebook,
				"twitter"       => $twitter,
				"google_plus"   => $google_plus,
				"pinterest"     => $pinterest,
				"instagram"     => $instagram,
				"linkedin"      => $linkedin,
				"user_level"    => $user_level,
			];

			if ( $password != "" ) {
				$userData['password'] = md5( $password );
			}

			// Check for a changed username			
			if ( $currentUserData['username'] != $username && username_exists( $username ) ) {
				echo return_error_alert( 'The supplied username is already in use. Please choose a different username and try again.' );
				exit( 0 );
			}

			// Handle additional permission checks
			if ( ! user_access( 'users_edit_user_level' ) || ( $actual_id == $_SESSION['userid'] && $_SESSION['user_level'] != SYSTEM_ADMIN ) ) {
				unset( $userData['user_level'] );
			}

			$result = $ftsdb->update( USERSDBTABLEPREFIX . "users",
				$userData,
				"id = :id",
				[
					":id" => $actual_id,
				]
			);

			if ( $send_msg ) {
				if ( empty( $password ) ) {
					$password = apply_filters(
						'user_updated_password_text',
						'The password has remained unchanged - If you have forgotten your password or would like to reset it simply <a href="' . il( $menuvar['FORGOTPASSWORD'] ) . '">' . __( 'click here' ) . '</a>'
					);
				}
				$dataArray = [
					'site_title' => $mbp_config['ftsmbp_site_name'],
					'site_url'   => site_url(),
					'username'   => $username,
					'password'   => $password,
				];
				$success   = emailMessage( $email_address,
					parseForTagsFromArray( getEmailTemplateSubjectFromID( 'mbp-account-updated' ), $dataArray ),
					parseForTagsFromArray( getEmailTemplateMessageFromID( 'mbp-account-updated' ), $dataArray )
				);
			}

			$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully updated user (" . $last_name . ", " . $first_name . ")!</span>" : "	<span class=\"redText bold\">Failed to update user (" . keeptasafe( $last_name . ", " . $first_name ) . ")!!!</span>";
		} else {
			$content = "<span class=\"redText bold\">The passwords you supplied do not match. Please fix this.</span>";
		}
	} else {
		$content = return_error_alert( notAuthorizedNotice() );
	}

	echo $content;
}

//================================================
// Update our password in the database
//================================================
elseif ( $actual_action == "generatePassword" && user_access( $actual_action ) ) {
	echo generateStrongPassword();
}

//================================================
// Update our password in the database
//================================================
elseif ( $actual_action == "changePassword" && user_access( $actual_action ) ) {
	$password  = keeptasafe( $_POST['password'] );
	$password2 = keeptasafe( $_POST['password2'] );
	if ( empty( $password ) || empty( $password2 ) ) {
		$content = "<span class=\"redText bold\">Please enter a password.</span>";
	} elseif ( $password == $password2 ) {

		$result = $ftsdb->update( USERSDBTABLEPREFIX . "users",
			[
				"password" => md5( $password ),
			],
			"id = :id",
			[
				":id" => $_SESSION['userid'],
			]
		);

		$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully updated your password!</span>" : "	<span class=\"redText bold\">Failed to update your password!!!</span>";
	} else {
		$content = "<span class=\"redText bold\">The passwords you supplied do not match. Please fix this.</span>";
	}

	echo $content;
}

//================================================
// Add our users to the database
//================================================
elseif ( $actual_action == "createAccount" && $mbp_config['ftsmbp_enable_public_account_creation'] ) {
	$datetimestamp    = time();
	$email_address    = sanitize_email( $_POST['email_address'] );
	$username         = keepsafe( $_POST['username'] );
	$password         = keeptasafe( $_POST['password'] );
	$password2        = keeptasafe( $_POST['password2'] );
	$user_level       = USER;
	$active           = 1;
	$token_activation = '';
	if ( $mbp_config['ftsmbp_require_account_activation'] ) {
		$active           = 0;
		$token_activation = uniqid( 'ta', true );
	}

	if ( ! username_exists( $username ) ) {
		if ( $password == $password2 ) {
			$result = $ftsdb->insert( USERSDBTABLEPREFIX . 'users',
				[
					"email_address"    => $email_address,
					"username"         => $username,
					"password"         => md5( $password ),
					"signup_date"      => mysqldatetime(),
					"user_level"       => $user_level,
					"active"           => $active,
					"token_activation" => $token_activation,
				] );
			$userID = $ftsdb->lastInsertId();


			if ( $result ) {
				if ( $mbp_config['ftsmbp_require_account_activation'] ) {
					// Send account activation email
					$message     = 'Thank you for creating an account at ' . $mbp_config['ftsmbp_site_name'] . ', in order to login you must first activate your account. Please click the link below to activate your account.
						<br /><br />
						<a href="' . il( $menuvar['ACTIVATEACCOUNT'] . '&token=' . $token_activation, 1 ) . '">' . __( 'Activate My Account' ) . '</a>';
					$emailResult = emailMessage( $email_address, $mbp_config['ftsmbp_site_name'] . ': Please Activate Your Account', $message );
					if ( ! $emailResult ) {
						echo 'Failed to send activation email!!';
					}

					$content = '<span class="greenText bold">Successfully created account! Please click the activation link in the email we sent to your email address to activate your account.</span>';
				} else {
					if ( get_config_value( 'ftsmbp_enable_account_creation_alert' ) ) {
						$dataArray = [
							'site_title' => $mbp_config['ftsmbp_site_name'],
							'site_url'   => site_url(),
							'username'   => $username,
							'password'   => $password,
						];
						$success   = emailMessage( $email_address,
							parseForTagsFromArray( getEmailTemplateSubjectFromID( 'mbp-account-created' ), $dataArray ),
							parseForTagsFromArray( getEmailTemplateMessageFromID( 'mbp-account-created' ), $dataArray )
						);
					}

					$content = '<span class="greenText bold">Successfully created account! Please <a href="' . il( $menuvar['LOGIN'] ) . '">' . __( 'Login' ) . '</a>.</span>';
				}
			} else {
				$content = '<span class="redText bold">Failed to create account!!!</span>';
			}
		} else {
			$content = '<span class="redText bold">The passwords you supplied do not match. Please fix this.</span>';
		}
	} else {
		$content = '<span class="redText bold">This username has already been taken.</span>';
	}

	echo $content;
}

//================================================
// Allow us to reset a password
//================================================
elseif ( $actual_action == "forgotPassword" ) {
	$email_address        = sanitize_email( $_POST['email_address'] );
	$username             = keepsafe( $_POST['username'] );
	$token_password_reset = uniqid( 'ta', true );

	// Let's reset the password
	$result = $ftsdb->select( USERSDBTABLEPREFIX . "users",
		'email_address = :email_address OR username = :username',
		[
			':email_address' => $email_address,
			':username'      => $username,
		] );
	if ( $result && count( $result ) == 1 ) {
		$row    = $result[0];
		$result = null;

		// Check if we already did a password reset recently
		if ( $row['token_date'] >= mysqldatetime( strtotime( '-5 minutes' ) ) ) {
			// We've done this recently, make them wait
			$content = return_error_alert( 'We\'ve sent a password reset email within the last few minutes. Please check your email and try again later if you do not receive the password reset instructions.' );
		} else {

			// Add token to user
			$result = $ftsdb->update( DBTABLEPREFIX . "users",
				[
					"token_password_reset" => $token_password_reset,
					"token_date"           => mysqldatetime(),
				],
				"id = :id",
				[
					":id" => $row['id'],
				]
			);

			// Send reset password email
			$message     = 'Someone requested that the password be reset for the following account:
	
				<a href="' . site_url() . '">' . $mbp_config['ftsmbp_site_name'] . '</a>
				
				Username: ' . $row['username'] . '
				
				If this was a mistake, just ignore this email and nothing will happen.
				
				To reset your password, visit the following address:
	
				<a href="' . il( $menuvar['FORGOTPASSWORD'] . '&token=' . $token_password_reset, 1 ) . '">' . __( 'Reset My Password' ) . '</a>';
			$emailResult = emailMessage( $row['email_address'], $mbp_config['ftsmbp_site_name'] . ': Password Reset Request', nl2br( $message ) );

			$content = '<span class="greenText bold">Please click the password reset link in the email we sent to your email address to activate your finish the password reset process.</span>';
			if ( ! $emailResult ) {
				$content .= return_error_alert( 'Failed to send password reset email!!' );
			}
		}
	} else {
		$content = return_warning_alert( 'No account exists for this username or email address.' );
	}

	echo $content;
}

//================================================
// Allow us to reset a password
//================================================
elseif ( $actual_action == "resetPassword" ) {
	$password  = keeptasafe( $_POST['password'] );
	$password2 = keeptasafe( $_POST['password2'] );
	$token     = keepsafe( $_POST['token'] );

	$result = $ftsdb->select( USERSDBTABLEPREFIX . "users",
		'token_password_reset = :token_password_reset',
		[
			':token_password_reset' => $token,
		] );
	if ( $result && count( $result ) == 1 ) {
		if ( $password == $password2 ) {
			$row    = $result[0];
			$result = null;

			// Remove token from DB and activate
			$result = $ftsdb->update( DBTABLEPREFIX . "users",
				[
					"token_password_reset" => '',
					"password"             => md5( $password ),
				],
				"id = :id",
				[
					":id" => $row['id'],
				]
			);

			$content = '<span class="greenText bold">Successfully updated password! Please <a href="' . il( $menuvar['LOGIN'] ) . '">' . __( 'Login' ) . '</a>.</span>';
		} else {
			$content = return_warning_alert( 'The passwords you supplied do not match. Please fix this.' );
		}
	} else {
		$content = return_warning_alert( 'No account exists for this password reset token.' );
	}

	echo $content;
}

//================================================
// Search our user table
//================================================
elseif ( $actual_action == "searchUsers" && user_access( $actual_action ) ) {
	echo printUsersTable( $_POST, "" );
}

//================================================
// Update our user permissions in the database
//================================================
elseif ( $actual_action == "editUserRolePermissions" && user_access( $actual_action ) ) {
	$permissions = [];
	$extraSQL    = '';
	$errors      = 0;

	foreach ( $_POST as $field => $value ) {
		if ( is_numeric( $field ) && count( $value ) ) {
			$permissions[ $field ] = str_replace( [ "\'", "'" ], '', implode( ',', array_keys( $value ) ) ) . ',';
		}
	}

	// Remove all permissions
	$result = $ftsdb->update( DBTABLEPREFIX . "permissions",
		[
			"role_ids" => '',
		],
		'1' );

	foreach ( $permissions as $permID => $roleIDs ) {
		// Set our permission
		$result = $ftsdb->update( DBTABLEPREFIX . "permissions",
			[
				"role_ids" => $roleIDs,
			],
			"id = :id",
			[
				":id" => $permID,
			]
		);
		$errors += ( $result ) ? 0 : 1;
		//echo $sql . "<br />";
	}

	$content = ( $errors == 0 ) ? "	<span class=\"greenText bold\">Successfully updated permissions!</span>" : "	<span class=\"redText bold\">Failed to update permissions!!!</span>";
	echo $content;
}

//================================================
// Installs a module
//================================================
if ( $actual_action == "installModule" && user_access( $actual_action ) ) {
	callModuleHook( $actual_prefix, "install", "", 0 );
}

//================================================
// Uninstalls a module
//================================================
if ( $actual_action == "uninstallModule" && user_access( $actual_action ) ) {
	callModuleHook( $actual_prefix, "uninstall", "", 0 );
}

//================================================
// Activates a module
//================================================
if ( $actual_action == "activateModule" && user_access( $actual_action ) ) {
	callModuleHook( $actual_prefix, "activate", "", 0 );
}

//================================================
// Deactivates a module
//================================================
if ( $actual_action == "deactivateModule" && user_access( $actual_action ) ) {
	callModuleHook( $actual_prefix, "deactivate", "", 0 );
}

//================================================
// Prints a modules status buttons
//================================================
if ( $actual_action == "showModuleStatusButtons" && user_access( $actual_action ) ) {
	echo printModulesStatusButtons( $actual_prefix );
}

//================================================
// Echo a progress spinner
//================================================
elseif ( $actual_action == "showSpinner" && user_access( $actual_action ) ) {
	echo progressSpinnerHTML();
}

//================================================
// Run Our Update for the MBP and Modules
//================================================
elseif ( $actual_action == "performMainUpdate" && user_access( 'perform_update' ) ) {
	// Only allow certain users to update the site	
	if ( user_access( 'runUpdates' ) ) {
		// Do MBP updates	
		$returnVar    = "<strong>Checking for MBP Updates...</strong><br />";
		$updateResult = checkForMBPUpdates();

		if ( $updateResult['status'] == 'error' ) {
			$returnVar .= return_error_alert( $updateResult['message'] ) . "<br />";
		} else {
			$returnVar .= $updateResult['message'] . "<br /><br />";
		}

		// Do module updates	
		$returnVar    .= "<strong>Checking for Module Updates...</strong><br />";
		$updateResult = checkForModuleUpdates();
		if ( count( $updateResult ) ) {
			foreach ( $updateResult as $prefix => $resultArray ) {
				if ( $resultArray['status'] == 'error' ) {
					$returnVar .= "$prefix - ";
				}
				$returnVar .= $resultArray['message'] . "<br />";
			}
		}
		$returnVar .= "<br />";

		// Do theme updates	
		$returnVar    .= "<strong>Checking for Theme Updates...</strong><br />";
		$updateResult = checkForThemeUpdates();
		if ( count( $updateResult ) ) {
			foreach ( $updateResult as $theme => $resultArray ) {
				if ( $resultArray['status'] == 'error' ) {
					$returnVar .= "$theme - ";
				}
				$returnVar .= $resultArray['message'] . "<br />";
			}
		}
		$returnVar .= "<br />";
	} else {
		$returnVar = notAuthorizedNotice();
	}

	echo $returnVar;
}

//================================================
// Run Our DB Update
//================================================
elseif ( $actual_action == "performDBUpdate" && user_access( 'perform_update' ) ) {
	// Only allow certain users to update the site	
	if ( user_access( 'runUpdates' ) ) {
		// Do MBP updates	
		$returnVar    = "<strong>Running Database Updates...</strong><br />";
		$updateResult = updateMBPDatabase();
		$returnVar    .= "<br />";
		$returnVar    .= "<strong>Updates Completed!</strong>";
	} else {
		$returnVar = notAuthorizedNotice();
	}

	echo $returnVar;
}

//================================================
// Notify the User of Changes to the System
//================================================
elseif ( $actual_action == "showUpdateDetails" && user_access( 'perform_update' ) ) {
	echo returnUpdateDetails();
}

//================================================
// Don't show our notification until the next update
//================================================
elseif ( $actual_action == "showedUpdateDetails" && user_access( 'perform_update' ) ) {
	showedUpdatePopup();
}

//================================================
// Echo a progress spinner
//================================================
elseif ( $actual_action == "save-widget" && user_access( $actual_action ) ) {
	//print_r($_POST);
	// Fires on add and save
	$widget_id = keeptasafe( $_POST['widget-id'] );
	$area      = keeptasafe( $_POST['area'] );
	$type      = keeptasafe( $_POST['class'] );
	$settings  = json_encode( $_POST['settings'] );

	if ( isset( $_POST['delete_widget'] ) ) {
		// Add to the DB
		$result = $ftsdb->delete( DBTABLEPREFIX . "widgets",
			"widget_id = :widgetID",
			[
				":widgetID" => $widget_id,
			] );
		//echo "$sql<br />";
	} elseif ( $_POST['create'] == 'yes' ) {
		// Add to the DB
		$result = $ftsdb->insert( DBTABLEPREFIX . 'widgets',
			[
				"widget_id" => $widget_id,
				"area"      => $area,
				'type'      => $type,
				"settings"  => $settings,
			] );
		//echo "$sql<br />";
	} else {
		// Update values
		$result = $ftsdb->update( DBTABLEPREFIX . 'widgets',
			[
				"area"     => $area,
				"settings" => $settings,
			],
			"widget_id = :widgetID",
			[
				":widgetID" => $widget_id,
			]
		);
		//echo "$sql<br />";
	}
}

//================================================
// Echo a progress spinner
//================================================
elseif ( $actual_action == "widgets-order" && user_access( $actual_action ) ) {
	//print_r($_POST);
	// Fires on ordering/ading/moving

	// Cycle through the widgetAreas and update order and area values
	foreach ( $_POST['widgetAreas'] as $area => $widgets ) {
		$order   = 1;
		$widgets = explode( ',', $widgets );

		foreach ( $widgets as $key => $id ) {
			$id = str_replace( 'widget-', '', $id );

			// Update order in db for this widget
			$result = $ftsdb->update( DBTABLEPREFIX . "widgets",
				[
					"area"  => $area,
					"order" => $order,
				],
				"widget_id = :widget_id",
				[
					":widget_id" => $id,
				]
			);

			$order ++;
		}
	}
}

//================================================
// Returns the JS to show a preview for an item selected in a select field
//================================================
elseif ( $actual_action == "showSelectionPreview" && user_access( $actual_action ) ) {
	do_action( 'showSelectionPreview', keepsafe( $_GET['selectionID'] ), keeptasafe( $_GET['value'] ) );
}

//================================================
// Returns the JSON for the application tours
//================================================
elseif ( $actual_action == "getTourDetailsJSON" && user_access( 'view_tour' ) ) {
	header( 'Content-Type: application/json' );

	switch ( $actual_type ) {
		case 'dashboard':
			echo json_encode( tours_dashboard() );
			break;
	}
}

//================================================
// Don't show our tour until the next time we say to
//================================================
elseif ( $actual_action == "showedTour" && user_access( 'view_tour' ) ) {
	add_config_value( 'shown_tour', 1 );
}

//================================================
// Don't show our theme update notice until the next time we say to
//================================================
elseif ( $actual_action == "showedThemeUpdateNotice" && user_access( 'view_theme_update_notice' ) ) {
	add_config_value( 'shown_theme_update_notice', 1 );
}

//================================================
// Test code
//================================================
elseif ( $actual_action == "test" && user_access( $actual_action ) ) {
	/*
	//global $ftsdb;
	$ftsdb->setErrorCallbackFunction("echo");
	$ftsdb->setProfileCallbackFunction("echo");

	// Enable Profiling
	$ftsdb->profile = 1;
	
	//---------------
	// SELECT
	//---------------
	$results = $result->select(DBTABLEPREFIX . 'clients');
	//var_export($result);
	if ($result) {
		foreach ($result as $row) {
			echo 'id - ' . $row['id']  . '<br />';
		}
	}
	$result = NULL;
	
	$result = $ftsdb->select(DBTABLEPREFIX . 'clients', "first_name = :search", [
		":search" => "%a%"
	];
	//var_export($result);
	if ($result) {
		foreach ($result as $row) {
			echo 'id - ' . $row['id']  . '<br />';
		}
	}
	$result = NULL;
	
	//---------------
	// UPDATE
	//---------------
	$result = $ftsdb->update(DBTABLEPREFIX . 'clients', [
			"Age" => 24
		],
		"FName = :fname AND LName = :lname", [
			":fname" => "Jane",
			":lname" => "Doe"
		]
	);
	if ($result) { echo "Num Rows: $result"; }
	
	$result = $ftsdb->update(DBTABLEPREFIX . 'clients', [
			"Age" => 24
		], "id=1");
	if ($result) { echo "Num Rows: $result"; }
	
	//---------------
	// DELETE
	//---------------
	$result = $ftsdb->delete(DBTABLEPREFIX . 'clients', "LName = :lname", [
		":fname" => "Jane",
		":lname" => "Doe"
	]);
	if ($result) { echo "Num Rows: $result"; }
	
	//---------------
	// INSERT
	//---------------
	$result = $ftsdb->insert(DBTABLEPREFIX . 'clients', [
		"FName" => "John",
		"LName" => "Doe",
		"Age" => 26,
		"Gender" => "male"
	]);
	if ($result) { echo "Num Rows: $result"; }
	
	//---------------
	// RUN
	//---------------
	$sql = '
	CREATE TABLE mytable (
		ID int(11) NOT NULL AUTO_INCREMENT,
		FName varchar(50) NOT NULL,
		LName varchar(50) NOT NULL,
		Age int(11) NOT NULL,
		Gender enum(\'male\',\'female\') NOT NULL,
		PRIMARY KEY (ID)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;
	';
	//$result = $db->run($sql);
	//if ($result) { echo "Num Rows: $result"; }
	
	$ftsdb->profile();
	*/
	/*
	if ( 0 == false ) echo "0 == false<br />";
	if ( 0 === false ) echo "0 === false<br />";
	if ( '0' == false ) echo "'0' == false<br />";
	if ( '0' === false ) echo "'0' === false<br />";
	if ( (string)0 == false ) echo "(string)0 == false<br />";
	*/

	//$items = Category::all();
	//var_export($items);

	//$items = Config::all();
	//var_export($items);

	//$items = EmailLog::all();
	//var_export($items);

	//$items = EmailTemplate::all();
	//var_export($items);

	//$items = Logging::all();
	//var_export($items);

	//$items = Menu::all();
	//var_export($items);

	//$items = MenuItem::all();
	//var_export($items);

	//$items = Module::all();
	//var_export($items);

	//$items = Notification::all();
	//var_export($items);

	//$items = Permission::all();
	//var_export($items);

	//$items = Rewrite::all();
	//var_export($items);

	//$items = Role::all();
	//var_export($items);

	//$items = User::all();
	//var_export($items);

	//$items = Widget::all();
	//var_export($items);
}

//================================================
// Mark all Notifications as read
//================================================
elseif ( $actual_action == "markAllUserNotificationsAsRead" ) {

	$result            = markAllUserNotificationsAsRead( $_SESSION['userid'] );
	$return['success'] = count( $result ) > 0 ? 1 : 0;

	echo json_encode( $return );
}

//================================================
// Mark Notification as read
//================================================
elseif ( $actual_action == "markUserNotificationAsRead" ) {

	$result            = markUserNotificationAsRead( $_SESSION['userid'], $actual_id );
	$return['success'] = count( $result ) > 0 ? 1 : 0;

	echo json_encode( $return );
}

//==================================================
// This will call our modules to perform any AJAX calls they need
//==================================================		
callModuleHook( '', 'handleAJAX' );
$ftsdb->profile();
