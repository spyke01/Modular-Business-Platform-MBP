<?php
/***************************************************************************
 *                               taggableReports.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=================================================
// Print the Tagged Report
//=================================================
function printViewTaggedReport( $reportID ) {
	global $ftsdb, $menuvar, $trsMenus, $mbp_config;
	$taggedReport = '';

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];
	$selectBindData   = array_merge( $selectBindData, array(
		":id" => $reportID
	) );

	$result = $ftsdb->select( DBTABLEPREFIX . "reports", "id = :id AND client_id IN (" . $preparedInClause['binds'] . ")", $selectBindData, 'report' );

	// Add our data
	if ( ! $result ) {
		$taggedReport = 'This report does not exist or you do not have access to it.';
	} else {
		foreach ( $result as $row ) {
			$taggedReport = $row['report'];
		}
		$result = null;
	}

	// Return the table's HTML
	return $taggedReport;
}

//=================================================
// Print the Taggable Reports Table
//=================================================
function printTaggableReportsTable() {
	global $ftsdb, $menuvar, $trsMenus, $mbp_config;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyUserIDs() );
	$selectBindData   = $preparedInClause['data'];

	// Pull our taggable reports
	$result = $ftsdb->select( DBTABLEPREFIX . "taggable_reports", "user_id IN (" . $preparedInClause['binds'] . ") ORDER BY name", $selectBindData );
	//echo $sql;

	$numRows = ( $result ) ? count( $result ) : 0;

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "reportsTable" );

	// Create table title
	$table->addNewRow( array(
		array(
			'data'    => "Taggable Reports (" . $numRows . ")",
			"colspan" => "8"
		)
	), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => 'Name' ),
			array( 'type' => 'th', 'data' => 'Description' ),
			array( 'type' => 'th', 'data' => 'Created By' ),
			array( 'type' => 'th', 'data' => 'Created On' ),
			array( 'type' => 'th', 'data' => "" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "You currently have no taggable reports. Please create one.",
				"colspan" => "8"
			)
		), "reportsTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$finalColumn = ( user_access( 'trs_report_templates_edit' ) ) ? "<a href=\"" . $trsMenus['TAGGABLEREPORTS']['link'] . "&action=edittaggablereport&id=" . $row['id'] . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-edit\"></i></a> " : "";
			$finalColumn .= ( user_access( 'trs_report_templates_delete' ) ) ? createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "taggable_reports", "taggable report" ) : "";

			// Build our final column array
			$rowDataArray = array(
				array( 'data' => '<div id="edit-taggable_reports-' . $row['id'] . '_name">' . $row['name'] . '</div>' ),
				array( 'data' => '<div id="edit-taggable_reports-' . $row['id'] . '_description">' . $row['description'] . '</div>' ),
				array( 'data' => getUsernameFromID( $row['user_id'] ) ),
				array( 'data' => makeShortDateTime( $row['datetimestamp'] ) ),
				array( 'data' => '<span class="btn-group">' . $finalColumn . '</span>', 'class' => 'center' )
			);

			$table->addNewRow( $rowDataArray, $row['id'] . "_row", "" );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"reportsTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to run the 
// reports table
//=================================================
function returnTaggableReportsTableJQuery() {
	global $ftsdb;

	$JQueryReadyScripts = "
			$('#reportsTable').tablesorter({ widgets: ['zebra'], headers: { 7: { sorter: false } } });";

	// Only allow modification of rows if we have permission
	if ( user_access( 'trs_report_templates_edit' ) ) {
		$JQueryReadyScripts = "
			var fields = $(\"#reportsTable div[id^='edit-taggable_reports-']\").map(function() { return this.id; }).get();
			addEditable( fields );";
	}

	return $JQueryReadyScripts;
}

//=================================================
// Print the Tagged Reports Table
//=================================================
function printTaggedReportsTable( $clientID = '' ) {
	global $ftsdb, $menuvar, $trsMenus, $mbp_config;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];
	$selectBindData   = array_merge( $selectBindData, array(
		":client_id" => $clientID
	) );

	// Pull our taggable reports
	$result = $ftsdb->select( DBTABLEPREFIX . "tagged_reports", "client_id IN (" . $preparedInClause['binds'] . ")" . ( ( $clientID != '' ) ? " AND client_id = :client_id" : '' ) . " ORDER BY name ASC", $selectBindData );
	//echo $sql;

	$numRows = ( $result ) ? count( $result ) : 0;

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "reportsTable" );

	// Create table title
	$table->addNewRow( array(
		array(
			'data'    => "Tagged Reports (" . $numRows . ")",
			"colspan" => "8"
		)
	), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => 'Name' ),
			array( 'type' => 'th', 'data' => 'Created On' ),
			array( 'type' => 'th', 'data' => "" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "You currently have no tagged reports. Please create one.",
				"colspan" => "8"
			)
		), "reportsTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$finalColumn = ( user_access( 'trs_tagged_reports_edit' ) ) ? "<a href=\"" . $trsMenus['TAGGEDREPORTS']['link'] . "&action=edittaggablereport&id=" . $row['id'] . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-edit\"></i></a> " : "";
			$finalColumn .= ( user_access( 'trs_tagged_reports_delete' ) ) ? createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "taggable_reports", "taggable report" ) : "";

			// Build our final column array
			$rowDataArray = array(
				array( 'data' => "<a href=\"" . $trsMenus['VIEWTAGGEDREPORT']['link'] . "&id=" . $row['id'] . "\">" . $row['name'] . "</a>" ),
				array( 'data' => makeShortDateTime( $row['datetimestamp'] ) ),
				array( 'data' => '<span class="btn-group">' . $finalColumn . '</span>', 'class' => 'center' )
			);

			$table->addNewRow( $rowDataArray, $row['id'] . "_row", "" );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"reportsTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to run the 
// reports table
//=================================================
function returnTaggedReportsTableJQuery() {
	$JQueryReadyScripts = "
			$('#reportsTable').tablesorter({ widgets: ['zebra'], headers: { 7: { sorter: false } } });";

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to add new reports
//=================================================
function printNewTaggableReportForm( $clientID = '' ) {
	global $menuvar, $mbp_config;

	$formFields_reportInfo = apply_filters( 'form_fields_taggable_report_form_create_report_info', array(
		'name'        => array(
			'text'        => 'Name',
			'placeholder' => 'Report Name',
			'type'        => 'text',
			'class'       => 'required',
		),
		'description' => array(
			'text'        => 'Description',
			'placeholder' => 'Report Description',
			'type'        => 'text',
			'class'       => 'required',
		),
	) );


	$content .= '
			<form name="newReportForm" id="newReportForm" action="' . $trsMenus['TAGGABLEREPORTS']['link'] . '" method="post" class="inputForm wizardForm form-horizontal" onsubmit="return false;">
				' . makeFormFieldset( 'Report Info', $formFields_reportInfo ) . '
				<fieldset id="fsFields">
					<legend>Fields</legend>
					<div>Here you can add any custom fields you want to be filled in for your report. These fields will be avilable as tags.</div>
					<div id="reportFields">
						<div class="form-group">
							<input name="field_names[1]" id="field_name_1" type="text" size="30" placeholder="Name" /> 
							' . createDropdown( "taggableReportFieldTypes", "field_types[1]", "text", "" ) . '
							<input name="field_values[1]" id="field_val_1" type="text" size="30" placeholder="Default Value" />
							<a class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove"></i></a>
						</div>
						<div class="form-group">
							<input name="field_names[1]" id="field_name_1" type="text" size="30" placeholder="Name" /> 
							' . createDropdown( "taggableReportFieldTypes", "field_types[1]", "text", "" ) . '
							<input name="field_values[1]" id="field_val_1" type="text" size="30" placeholder="Default Value" />
							<a class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove"></i></a>
						</div>
					</div>
					<div class="form-group"><a class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Add Field</a></div>
				</fieldset>
				<fieldset>
					<legend>Report Template</legend>
					<div class="row">
						<div class="col-md-6"><textarea name="text" id="text" cols="45" rows="10" class="reportTemplate" placeholder="Create Your Taggable Report Here"></textarea></div>
						<div class="col-md-6">
							<strong>Tags</strong><br />
							<p id="availableTags">tags</p>
						</div>
					</div>
					<br class="clear" />
					<input type="submit" id="btnCreateReport" class="btn btn-success pull-right" value="Create Report" />
				</fieldset>
			</form>
			<div id="newReportResponse"></div>';

	return $content;
}

//=================================================
// Returns the JQuery functions used to run the 
// new report form
//=================================================
function returnNewTaggableReportFormJQuery( $reprintTable = 0, $allowModification = 1 ) {
	$extraJQuery = ( $reprintTable == 0 ) ? "
					// Update the proper div with the returned data
					$('#newReportResponse').html(data);
					$('#newReportResponse').effect('highlight',{},500);"
		: "
					// Clear the default row
					$('#reportsTableDefaultRow').remove();
					// Update the table with the new row
					$('#reportsTable > tbody:last').append(data);
					$('#reportsTableUpdateNotice').html('" . tableUpdateNoticeHTML() . "');
					// Show a success message
					$('#newReportResponse').html(returnSuccessMessage('report'));";

	$JQueryReadyScripts = "
		$('#newReportForm').formToWizard({ submitButton: 'btnCreateReport' });
		var v = jQuery(\"#newReportForm\").validate({
			errorElement: \"div\",
			errorClass: \"validation-advice\",
			submitHandler: function(form) {			
				$('#newReportResponse').html('" . progressSpinnerHTML() . "');
				jQuery.post('" . SITE_URL . "/ajax.php?action=createReport&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification . "', $('#newReportForm').serialize(), function(data) {
					" . $extraJQuery . "
				});
			}
		});
		$('#fsFields a.next').click(function() {
			$('#availableTags').html('" . progressSpinnerHTML() . "');
			jQuery.post('" . SITE_URL . "/ajax.php?action=createReportShowTags', $('#newReportForm').serialize(), function(data) {
				$('#availableTags').html(data);
				$('#availableTags').effect('highlight',{},500);
			});
			
		});
		$('#clearFormButton').click(function () {
			bootbox.confirm('Are you sure you want to clear this form?', function(result) {
				if ( result == true ) {
					$('#newReportForm').clearForm();
				}
			});
		});";

	return $JQueryReadyScripts;
}