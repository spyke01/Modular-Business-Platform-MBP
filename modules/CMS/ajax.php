<?php
// Cycle through our AJAX calls and handle the content
if ( $actual_action == 'updateitem' && user_access( 'cms_updateitem' ) ) {
	if ( $section == 'before' ) {

	}
} elseif ( $actual_action == 'deleteitem' && user_access( 'cms_deleteitem' ) ) {
	if ( $section == 'before' ) {
		// Delete any associated foreign items	
	}
}

//================================================
// Add a menu item in the database
//================================================
elseif ( $actual_action == "createMenuItem" && user_access( $actual_action ) ) {
	global $menuItemID, $cmsMenus;

	$page_id = keepsafe( $_POST['page_id'] );

	if ( ! empty( $page_id ) && stristr( $page_id, 'cms' ) !== false ) {
		$page_id = str_replace( 'cms', '', $page_id );
		if ( $page_id == 'testimonials' ) {
			$link = $cmsMenus['VIEWTESTIMONIALS']['link'];
		} else {
			$pageSlug = getDatabaseItem( 'pages', 'slug', $page_id );
			$link     = $cmsMenus['VIEWPAGE']['link'] . "&page=$pageSlug";
		}

		$result = $ftsdb->update( DBTABLEPREFIX . "menu_items", array(
			"link" => $link
		),
			"id = :id", array(
				":id" => $menuItemID
			)
		);
	}
}

//================================================
// Update our menu item in the database
//================================================
elseif ( $actual_action == "updateMenuItem" && user_access( $actual_action ) ) {
	global $menuItemID, $cmsMenus;

	$page_id = keepsafe( $_POST['page_id'] );

	if ( ! empty( $page_id ) && stristr( $page_id, 'cms' ) !== false ) {
		$page_id = str_replace( 'cms', '', $page_id );
		if ( $page_id == 'testimonials' ) {
			$link = $cmsMenus['VIEWTESTIMONIALS']['link'];
		} else {
			$pageSlug = getDatabaseItem( 'pages', 'slug', $page_id );
			$link     = $cmsMenus['VIEWPAGE']['link'] . "&page=$pageSlug";
		}

		$result = $ftsdb->update( DBTABLEPREFIX . "menu_items", array(
			"link" => $link
		),
			"id = :id", array(
				":id" => $actual_id
			)
		);
	}
}

//================================================
// Update our pages in the database
//================================================
elseif ( $actual_action == 'createPage' && user_access( 'cms_pages_create' ) ) {
	$title       = keeptasafe( $_POST['title'] );
	$slug        = keepsafe( str_replace( ' ', '-', strtolower( $_POST['slug'] ) ) );
	$page_title  = keeptasafe( $_POST['page_title'] );
	$keywords    = keeptasafe( $_POST['keywords'] );
	$description = keeptasafe( $_POST['description'] );
	$content     = $_POST['pagecontent'];

	$result = $ftsdb->insert( DBTABLEPREFIX . 'pages', array(
		"title"       => $title,
		"slug"        => $slug,
		"page_title"  => $page_title,
		"keywords"    => $keywords,
		"description" => $description,
		"content"     => $content,
	) );
	$pageID = $ftsdb->lastInsertId();

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully created page (" . $title . ")!</span>" : "	<span class=\"redText bold\">Failed to create page (" . $title . ")!!!</span>";

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$finalColumnData = ( user_access( 'cms_pages_edit' ) ) ? "<a href=\"" . $cmsMenus['PAGES']['link'] . "&action=editpage&id=" . $pageID . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-edit\"></i></a> " : "";
			$finalColumnData .= ( user_access( 'cms_pages_delete' ) ) ? createDeleteLinkWithImage( $pageID, $pageID . "_row", "pages", "page" ) : "";

			$tableHTML = "
				<tr class=\"even\" id=\"" . $pageID . "_row\">
					<td>" . $title . "</td>
					<td>" . $slug . "</td>
					<td>" . $page_title . "</td>
					<td class=\"center\">" . $finalColumnData . "</td>
				</tr>";

			echo $tableHTML;
			break;
		default:
			echo $content;
			break;
	}
}

//================================================
// Update our page in the database
//================================================
elseif ( $actual_action == "updatePage" && user_access( $actual_action ) ) {
	$title       = keeptasafe( $_POST['title'] );
	$slug        = keepsafe( str_replace( ' ', '-', strtolower( $_POST['slug'] ) ) );
	$page_title  = keeptasafe( $_POST['page_title'] );
	$keywords    = keeptasafe( $_POST['keywords'] );
	$description = keeptasafe( $_POST['description'] );
	$content     = $_POST['pagecontent'];

	// Update page in DB
	$result = $ftsdb->update( DBTABLEPREFIX . "pages", array(
		"title"       => $title,
		"slug"        => $slug,
		"page_title"  => $page_title,
		"keywords"    => $keywords,
		"description" => $description,
		"content"     => $content,
	),
		"id = :id", array(
			":id" => $actual_id
		)
	);

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully updated page!</span>" : "	<span class=\"redText bold\">Failed to update page!!!</span>";

	echo $content;
}