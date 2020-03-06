<?php
/***************************************************************************
 *                               pages.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=========================================================
// Gets a page name from an id
//=========================================================
function getPageTitleFromID( $pageID ) {
	return getDatabaseItem( 'pages', 'title', $pageID );
}

//=========================================================
// Gets a page's content from an id
//=========================================================
function getPageContentFromID( $pageID ) {
	return getDatabaseItem( 'pages', 'content', $pageID );
}

//=================================================
// Print the Pages Table
//=================================================
function printPagesTable() {
	global $ftsdb, $cmsMenus, $mbp_config;

	$result = $ftsdb->select( DBTABLEPREFIX . "pages", "1 ORDER BY title ASC" );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "pagesTable" );

	// Create table title
	$table->addNewRow( array(
		array(
			'data'    => '<i class="glyphicons glyphicons-pen"></i> ' . __( 'Pages' ),
			"colspan" => "4"
		)
	), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Title" ),
			array( 'type' => 'th', 'data' => "Slug" ),
			array( 'type' => 'th', 'data' => "Page Title" ),
			array( 'type' => 'th', 'data' => "" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no pages in the system.",
				"colspan" => "4"
			)
		), "pagesTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$finalColumn = ( user_access( 'cms_pages_edit' ) ) ? "<a href=\"" . $cmsMenus['PAGES']['link'] . "&action=editpage&id=" . $row['id'] . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-edit\"></i></a> " : "";
			$finalColumn .= ( user_access( 'cms_pages_delete' ) ) ? createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "pages", "page" ) : "";

			$table->addNewRow(
				array(
					array( 'data' => '<div id="edit-pages-' . $row['id'] . '_title">' . $row['title'] . '</div>' ),
					array( 'data' => '<div id="edit-pages-' . $row['id'] . '_slug">' . $row['slug'] . '</div>' ),
					array( 'data' => '<div id="edit-pages-' . $row['id'] . '_page_title">' . $row['page_title'] . '</div>' ),
					array( 'data' => '<span class="btn-group">' . $finalColumn . '</span>', 'class' => 'center' )
				), $row['id'] . "_row", ""
			);
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"pagesTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to allow 
// in-place editing and table sorting
//=================================================
function returnPagesTableJQuery() {
	global $ftsdb, $cmsMenus, $mbp_config;

	$JQueryReadyScripts = "
			$('#pagesTable').tablesorter({ widgets: ['zebra'], headers: { 3: { sorter: false } } });";

	// Only allow modification of rows if we have permission
	if ( user_access( 'cms_pages_edit' ) ) {
		$JQueryReadyScripts = "
			var fields = $(\"#pagesTable div[id^='edit-pages-']\").map(function() { return this.id; }).get();
			addEditable( fields );";
	}

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to add a new page
//=================================================
function printNewPageForm() {
	global $cmsMenus, $mbp_config;

	$formFields = apply_filters( 'form_fields_cms_pages_new', array(
		'title'       => array(
			'text'  => 'Title',
			'type'  => 'text',
			'class' => 'required',
		),
		'slug'        => array(
			'text' => 'Slug',
			'type' => 'text',
		),
		'page_title'  => array(
			'text'  => 'Page Title',
			'type'  => 'text',
			'class' => 'required',
		),
		'keywords'    => array(
			'text' => 'Keywords',
			'type' => 'text',
		),
		'description' => array(
			'text' => 'Description',
			'type' => 'text',
		),
		'pagecontent' => array(
			'text'      => 'Page Content',
			'type'      => 'textarea',
			'class'     => 'tinymce',
			'showLabel' => 0,
		),
	) );

	return makeForm( 'newPage', il( $cmsMenus['PAGES']['link'] ), 'New Page', 'Create Page', $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new page form
//=================================================
function returnNewPageFormJQuery( $reprintTable = 0, $allowModification = 1 ) {
	$table = ( $reprintTable == 0 ) ? '' : 'pagesTable';
	$url   = SITE_URL . "/ajax.php?action=createPage&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification;

	return makeFormJQuery( 'newPage', $url, $table, 'page', '', '', '', 1 );
}

//=================================================
// Create a form to edit a page
//=================================================
function printEditPageForm( $pageID ) {
	global $ftsdb, $cmsMenus, $mbp_config;

	$result = $ftsdb->select( DBTABLEPREFIX . "pages", "id = :id LIMIT 1", array(
		":id" => $pageID
	) );

	if ( $result && count( $result ) == 0 ) {
		$page_content = "<span class=\"center\">There was an error while accessing the page's details you are trying to update. You are being redirected to the main page.</span>
						<meta http-equiv=\"refresh\" content=\"5;url=" . il( $cmsMenus['PAGES']['link'] ) . "\">";
	} else {
		$row                = $result[0];
		$row['pagecontent'] = $row['content'];

		$formFields = apply_filters( 'form_fields_cms_pages_edit', array(
			'title'       => array(
				'text'  => 'Title',
				'type'  => 'text',
				'class' => 'required',
			),
			'slug'        => array(
				'text' => 'Slug',
				'type' => 'text',
			),
			'page_title'  => array(
				'text'  => 'Page Title',
				'type'  => 'text',
				'class' => 'required',
			),
			'keywords'    => array(
				'text' => 'Keywords',
				'type' => 'text',
			),
			'description' => array(
				'text' => 'Description',
				'type' => 'text',
			),
			'pagecontent' => array(
				'text'      => 'Page Content',
				'type'      => 'textarea',
				'class'     => 'tinymce',
				'showLabel' => 0,
			),
		) );

		return makeForm( 'editPage', il( $cmsMenus['PAGES']['link'] . "&action=editpage&id=" . $pageID ), 'Edit Page', 'Update Page', $formFields, $row, 1 );
		$result = null;
	}

	return $content;
}

//=================================================
// Returns the JQuery functions used to run the 
// edit page form
//=================================================
function returnEditPageFormJQuery( $pageID ) {
	return makeFormJQuery( 'editPage', SITE_URL . "/ajax.php?action=updatePage&id=" . $pageID );
}