<?php
/***************************************************************************
 *                               products.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=================================================
// Print the Products Table
//=================================================
function printProductsTable() {
	global $ftsdb, $clmsMenus, $mbp_config;

	$result = $ftsdb->select( DBTABLEPREFIX . "products", "1 ORDER BY name ASC" );

	// Create our new table
	$table = new tableClass( '', '', '', "table table-striped table-bordered tablesorter", "productsTable" );

	// Create table title
	$table->addNewRow( array(
		array(
			'data'    => '<i class="glyphicons glyphicons-shopping-cart"></i> ' . __( 'Products' ),
			"colspan" => "6"
		)
	), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Name" ),
			array( 'type' => 'th', 'data' => "Price" ),
			array( 'type' => 'th', 'data' => "Profit" ),
			array( 'type' => 'th', 'data' => "Shipping Cost" ),
			array( 'type' => 'th', 'data' => "Total Cost" ),
			array( 'type' => 'th', 'data' => "" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no products in the system.",
				"colspan" => "6"
			)
		), "productsTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$finalColumn = ( user_access( 'clms_products_delete' ) ) ? createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "products", "product" ) : "";

			$table->addNewRow(
				array(
					array( 'data' => '<div id="edit-products-' . $row['id'] . '_name">' . $row['name'] . '</div>' ),
					array( 'data' => '<div id="edit-products-' . $row['id'] . '_price">' . formatCurrency( $row['price'] ) . '</div>' ),
					array( 'data' => '<div id="edit-products-' . $row['id'] . '_profit">' . formatCurrency( $row['profit'] ) . '</div>' ),
					array( 'data' => '<div id="edit-products-' . $row['id'] . '_shipping">' . formatCurrency( $row['shipping'] ) . '</div>' ),
					array( 'data' => formatCurrency( $row['price'] + $row['profit'] + $row['shipping'] ) ),
					array( 'data' => $finalColumn, 'class' => 'center' )
				), $row['id'] . "_row", ""
			);
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"productsTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to allow 
// in-place editing and table sorting
//=================================================
function returnProductsTableJQuery() {
	global $ftsdb, $clmsMenus, $mbp_config;

	$JQueryReadyScripts = "
			$('#productsTable').tablesorter({ widgets: ['zebra'], headers: { 5: { sorter: false } } });";


	// Only allow modification of rows if we have permission
	if ( user_access( 'clms_products_edit' ) ) {
		$JQueryReadyScripts = "
			var fields = $(\"#productsTable div[id^='edit-products-']\").map(function() { return this.id; }).get();
			addEditable( fields );";
	}

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to add new category
//=================================================
function printNewProductForm() {
	global $clmsMenus, $mbp_config;

	$formFields = apply_filters( 'form_fields_clms_products_new', array(
		'name'     => array(
			'text'  => 'Product Name',
			'type'  => 'text',
			'class' => 'required',
		),
		'price'    => array(
			'text'    => 'Price',
			'type'    => 'text',
			'prepend' => '$',
		),
		'profit'   => array(
			'text'    => 'Profit',
			'type'    => 'text',
			'prepend' => '$',
		),
		'shipping' => array(
			'text'    => 'Shipping Cost',
			'type'    => 'text',
			'prepend' => '$',
		),
	) );

	return makeForm( 'newProduct', il( $clmsMenus['PRODUCTS']['link'] ), 'New Product', 'Create Product', $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new order form
//=================================================
function returnNewProductFormJQuery( $reprintTable = 0, $allowModification = 1 ) {
	$table = ( $reprintTable == 0 ) ? '' : 'productsTable';
	$url   = SITE_URL . "/ajax.php?action=createProduct&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification;

	return makeFormJQuery( 'newProduct', $url, $table, 'product', '', '', '', 1 );
}