<?php
// Cycle through our AJAX calls and handle the content
if ( $actual_action == 'updateitem' && user_access( 'snts_updateitem' ) ) {
	if ( $section == 'before' ) {
	}
} elseif ( $actual_action == 'deleteitem' && user_access( 'snts_deleteitem' ) ) {
	if ( $section == 'before' ) {
	}
}

//================================================
// Create a serial number 
//================================================
elseif ( $actual_action == "createSerial" && user_access( 'snts_serials_create' ) ) {
	$errors        = 0;
	$extraFields   = $extraValues = '';
	$cat_id        = intval( $_POST['cat_id'] );
	$serial        = keeptasafe( $_POST['serial'] );
	$description   = keeptasafe( $_POST['description'] );
	$location      = keeptasafe( $_POST['location'] );
	$owner         = keeptasafe( $_POST['owner'] );
	$added_by      = keeptasafe( $_POST['added_by'] );
	$datetimestamp = strtotime( keeptasafe( $_POST['datetimestamp'] ) );
	$expires       = strtotime( keeptasafe( $_POST['expires'] ) );

	//print_r($_POST);
	$serialData = array(
		"cat_id"        => $cat_id,
		"serial"        => $serial,
		"description"   => $description,
		"location"      => $location,
		"owner"         => $owner,
		"added_by"      => $added_by,
		"datetimestamp" => $datetimestamp,
		"expires"       => $expires,
	);

	if ( isModuleActivated( 'CLMS' ) && $mbp_config['ftsmbp_snts_useClientAsOwner'] ) {
		$client_id               = intval( $_POST['client_id'] );
		$serialData['client_id'] = $client_id;
	}

	$result   = $ftsdb->insert( DBTABLEPREFIX . 'serials', $serialData );
	$serialID = $ftsdb->lastInsertId();
	if ( ! $result ) {
		$errors ++;
	}

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully added serial number!</span>" : "	<span class=\"redText bold\">Failed to add serial number!!!</span>";

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$finalColumn = ( user_access( 'snts_serials_edit' ) ) ? "<a href=\"" . $sntsMenus['SERIALS']['link'] . "&action=editserial&id=" . $serialID . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-edit\"></i></a> " : "";
			$finalColumn .= ( user_access( 'snts_serials_delete' ) ) ? createDeleteLinkWithImage( $serialID, $serialID . "_row", "serials", "serial" ) : "";

			$tableHTML = '
				<tr class="greenRow" id="' . $serialID . '_row">
					<td>' . $serial . '</td>
					<td>' . getCatNameByID( $cat_id ) . '</td>
					<td>' . $location . '</td>
					<td>' . ( ( isModuleActivated( 'CLMS' ) ) ? getClientNameFromID( $client_id ) : $owner ) . '</td>
					<td>' . $added_by . '</td>
					<td>' . makeShortDate( $datetimestamp, 0 ) . '</td>
					<td>' . makeShortDate( $expires, 0 ) . '</td>
					<td class="center"><span class="btn-group">' . $finalColumn . '</span></td>
				</tr>';

			echo $tableHTML;
			break;
		default:
			echo $content;
			break;
	}
}

//================================================
// Create a serial number 
//================================================
elseif ( $actual_action == "updateSerial" && user_access( 'snts_serials_edit' ) ) {
	$errors        = 0;
	$extraFields   = $extraValues = '';
	$cat_id        = intval( $_POST['cat_id'] );
	$serial        = keeptasafe( $_POST['serial'] );
	$description   = keeptasafe( $_POST['description'] );
	$location      = keeptasafe( $_POST['location'] );
	$owner         = keeptasafe( $_POST['owner'] );
	$added_by      = keeptasafe( $_POST['added_by'] );
	$datetimestamp = strtotime( keeptasafe( $_POST['datetimestamp'] ) );
	$expires       = strtotime( keeptasafe( $_POST['expires'] ) );

	//print_r($_POST);
	$serialData = array(
		"cat_id"        => $cat_id,
		"serial"        => $serial,
		"description"   => $description,
		"location"      => $location,
		"owner"         => $owner,
		"added_by"      => $added_by,
		"datetimestamp" => $datetimestamp,
		"expires"       => $expires,
	);

	if ( isModuleActivated( 'CLMS' ) && $mbp_config['ftsmbp_snts_useClientAsOwner'] ) {
		$client_id               = intval( $_POST['client_id'] );
		$serialData['client_id'] = $client_id;
	}

	$result   = $ftsdb->update( DBTABLEPREFIX . "serials", $serialData,
		"id = :id", array(
			":id" => $actual_id
		)
	);
	$serialID = $ftsdb->lastInsertId();
	if ( ! $result ) {
		$errors ++;
	}

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully updated serial number!</span>" : "	<span class=\"redText bold\">Failed to update serial number!!!</span>";

	echo $content;
}

//================================================
// Search our serials
//================================================
elseif ( $actual_action == "searchSerials" && user_access( $actual_action ) ) {
	echo printSerialsTables( '', $_POST );
}

//================================================
// Generate a CSV of our serials
//================================================
elseif ( $actual_action == "generateSerialsCSV" && user_access( 'snts_serials_generate_csv' ) ) {
	global $ftsdb;

	header( "Content-type: application/octet-stream" );
	header( "Content-Disposition: attachment; filename=\"serials.csv\"" );

	// Variables
	$columns = $data = "";

	// Prep Data
	$result = $ftsdb->select( "`" . DBTABLEPREFIX . "serials` s LEFT JOIN `" . DBTABLEPREFIX . "categories` c ON c.id = s.cat_id", "1 ORDER BY c.name, s.datetimestamp DESC", array(), 's.*, c.name' );

	// Add our data
	if ( $result ) {
		foreach ( $result as $row ) {
			$columnRow = $dataRow = "";

			// Prep our data
			$row['datetimestamp'] = makeShortDate( $row['datetimestamp'] );
			$row['category']      = $row['name'];
			unset( $row['cat_id'], $row['name'] );

			if ( isModuleActivated( 'CLMS' ) && $mbp_config['ftsmbp_snts_useClientAsOwner'] ) {
				$row['client'] = getClientNameFromID( $row['client_id'] );
			}
			unset( $row['client_id'] );

			foreach ( $row as $field => $value ) {
				if ( ! is_numeric( $field ) ) {
					$columnRow .= ',"' . str_replace( '"', '""', $field ) . '"';
					$dataRow   .= ',"' . str_replace( '"', '""', $value ) . '"';
				}
			}
			$columns = ltrim( $columnRow, ',' ) . " \n";
			$data    .= ltrim( $dataRow, ',' ) . " \n";
		}
		$result = null;
	}

	$data = $columns . $data;
	echo $data;
}