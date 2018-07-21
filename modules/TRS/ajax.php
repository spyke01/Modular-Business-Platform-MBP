<?php
// Cycle through our AJAX calls and handle the content
if ( $actual_action == 'updateitem' && user_access( 'trs_updateitem' ) ) {
	if ( $section == 'before' ) {
	}
} elseif ( $actual_action == 'deleteitem' && user_access( 'trs_deleteitem' ) ) {
	if ( $section == 'before' ) {
		// Delete and associated foreign items
		if ( $table == "reports" ) {
			// Delete Report Entries
			$result     = $ftsdb->delete( DBTABLEPREFIX . 'entries', "report_id = :report_id", array(
				":report_id" => $actual_id
			) );
			$errorCount += ( $result ) ? 0 : 1;
		}
	}
}

//================================================
// Show the tags based on the fields selected
//================================================
elseif ( $actual_action == "createReportShowTags" && user_access( 'trs_report_templates_create' ) ) {
	$tags         = '';
	$names        = array_merge( $_POST['field_names'], array(
		'client_id',
		'client_user_id',
		'client_cat_id',
		'client_username',
		'client_first_name',
		'client_last_name',
		'client_title',
		'client_company',
		'client_street1',
		'client_street2',
		'client_city',
		'client_state',
		'client_zip',
		'client_daytime_phone',
		'client_nighttime_phone',
		'client_cell_phone',
		'client_email_address',
		'client_website',
		'client_preffered_client',
		'client_found_us_through',
	) );
	$patterns     = array( '/[^a-zA-Z_\d\s:]+/', '/\s/' );
	$replacements = array( '', '_' );

	foreach ( $names as $key => $val ) {
		$tagName = strtolower( preg_replace( $patterns, $replacements, keeptasafe( $val ) ) );

		if ( ! empty( $tagName ) ) {
			$tags .= '{' . $tagName . '}<br />';
		}
	}

	echo $tags;
}

//================================================
// Create a report 
//================================================
elseif ( $actual_action == "createReport" && user_access( 'trs_report_templates_create' ) ) {
	$errors        = 0;
	$datetimestamp = time();
	$user_id       = $_SESSION['userid'];
	$name          = keeptasafe( $_POST['name'] );
	$description   = keeptasafe( $_POST['description'] );
	$template      = keeptasafe( $_POST['template'] );
	$username      = getUsernameFromID( $user_id );

	$fields = array();
	foreach ( $_POST['field_names'] as $key => $value ) {
		$fields[ $key ] = array(
			'name' => $value,
			'type' => keeptasafe( $_POST['field_types'][ $key ] ),
			'type' => keeptasafe( $_POST['field_values'][ $key ] ),
		);
	}
	$fields = json_encode( $fields );

	$result   = $ftsdb->insert( DBTABLEPREFIX . 'taggable_reports', array(
		"user_id"       => $user_id,
		"name"          => $name,
		"description"   => $description,
		"fields"        => $fields,
		"template"      => $template,
		"datetimestamp" => $datetimestamp,
	) );
	$reportID = $ftsdb->lastInsertId();
	if ( ! $result ) {
		$errors ++;
	}

	$content = ( $errors > 0 ) ? "	<span class=\"greenText bold\">Successfully created report template.</span>" : "	<span class=\"redText bold\">There was an error when creating your report.</span>";

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$finalColumnData = ( user_access( 'trs_report_templates_edit' ) ) ? "<a href=\"" . $trsMenus['TAGGABLEREPORTS']['link'] . "&action=edittaggablereport&id=" . $reportID . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-edit\"></i></a> " : "";
			$finalColumnData .= ( user_access( 'trs_report_templates_delete' ) ) ? createDeleteLinkWithImage( $reportID, $reportID . "_row", "taggable_reports", "taggable report" ) : "";

			$tableHTML = '
				<tr class="greenRow" id="' . $reportID . '_row">
					<td>' . $name . '</td>
					<td>' . $description . '</td>
					<td>' . $username . '</td>
					<td>' . makeShortDateTime( $datetimestamp ) . '</td>
					<td class="center"><span class="btn-group">' . $finalColumn . '</span></td>
				</tr>';

			echo $tableHTML;
			break;
		default:
			echo $content;
			break;
	}

	// Send report created email
	sendReportEMail( $reportID, $user_id, 0 );
}