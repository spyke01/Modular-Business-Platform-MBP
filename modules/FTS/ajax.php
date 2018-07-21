<?php
// Cycle through our AJAX calls and handle the content
if ( $actual_action == 'updateitem' && user_access( 'fts_updateitem' ) ) {
	if ( $section == 'before' ) {
	}

	// The versions table is in another DB
	if ( $table == 'MBP_versions' ) {
		$result = $ftsdb->update( DBTABLEPREFIX . 'versions', array(
			$item => $_REQUEST['value']
		),
			"id = :id", array(
				":id" => $actual_id
			)
		);
	}
} elseif ( $actual_action == 'deleteitem' && user_access( 'fts_deleteitem' ) ) {
	if ( $section == 'before' ) {
	}

	// The versions table is in another DB
	if ( $table == 'versions' ) {
		$result = $ftsdb->delete( DBTABLEPREFIX . 'versions', "id = :id", array(
			":id" => $actual_id
		) );
	}
}

//================================================
// Create a version number 
//================================================
elseif ( $actual_action == "createVersion" && user_access( 'fts_versions_create' ) ) {
	$errors     = 0;
	$app        = keeptasafe( $_POST['app'] );
	$type       = keeptasafe( $_POST['type'] );
	$version    = keeptasafe( $_POST['version'] );
	$update_url = keeptasafe( $_POST['update_url'] );
	//print_r($_POST);

	$result    = $ftsdb->insert( DBTABLEPREFIX . 'versions', array(
		"app"        => $app,
		'type'       => $type,
		"version"    => $version,
		"update_url" => $update_url
	) );
	$versionID = $ftsdb->lastInsertId();
	if ( ! $result ) {
		$errors ++;
	}

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully added version number!</span>" : "	<span class=\"redText bold\">Failed to add version number!!!</span>";

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$finalColumn = ( user_access( 'fts_versions_edit' ) ) ? "<a href=\"" . $ftsMenus['VERSIONS']['link'] . "&action=editversion&id=" . $versionID . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-edit\"></i></a> " : "";
			$finalColumn .= ( user_access( 'fts_versions_delete' ) ) ? createDeleteLinkWithImage( $versionID, $versionID . "_row", "versions", "version" ) : "";

			$tableHTML = "
				<tr class=\"greenRow\" id=\"" . $versionID . "_row\">
					<td>" . $app . "</td>
					<td>" . ( ( $type == 0 ) ? 'Free' : 'Professional' ) . "</td>
					<td>" . $version . "</td>
					<td>" . $update_url . "</td>
					<td class=\"center\">" . '<span class="btn-group">' . $finalColumn . '</span>' . "</td>
				</tr>";

			echo $tableHTML;
			break;
		default:
			echo $content;
			break;
	}
}

//================================================
// Create a version number 
//================================================
elseif ( $actual_action == "updateVersion" && user_access( 'fts_versions_edit' ) ) {
	$errors     = 0;
	$app        = keeptasafe( $_POST['app'] );
	$type       = keeptasafe( $_POST['type'] );
	$version    = keeptasafe( $_POST['version'] );
	$update_url = keeptasafe( $_POST['update_url'] );
	//print_r($_POST);

	$result    = $ftsdb->update( DBTABLEPREFIX . 'versions', array(
		"app"        => $app,
		'type'       => $type,
		"version"    => $version,
		"update_url" => $update_url,
	),
		"id = :id", array(
			":id" => $actual_id
		)
	);
	$versionID = $ftsdb->lastInsertId();
	if ( ! $result ) {
		$errors ++;
	}

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully updated version number!</span>" : "	<span class=\"redText bold\">Failed to update version number!!!</span>";

	echo $content;
}