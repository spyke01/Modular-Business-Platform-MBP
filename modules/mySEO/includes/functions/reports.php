<?php
/***************************************************************************
 *                               reports.php
 *                            -------------------
 *   begin                : Saturday, Sept 18, 2013
 *   copyright            : (C) 2013 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/




//=================================================
// Returns the full HTML for a printerfriendly 
// version of the tasks report, this is then used 
// with PDFCrowd
//=================================================
function returnMySEOTasksReportPDFHTML( $clientID = '', $date = '', $type = 1 ) {
	global $mbp_config;

	ob_start();

	$page2 = new pageClass; //initialize our page
	$page2->setTemplateVar( 'Theme', $mbp_config['ftsmbp_theme'] );
	$page2->setTemplateVar( 'Template', 'printerFriendlyTemplate.php' );
	$page2->setTemplateVar( 'PageContent', printMySEOTasksReport( $clientID, $date, $type ) );
	include( BASEPATH . '/themes/' . $page2->getTemplateVar( 'Theme' ) . '/' . $page2->getTemplateVar( 'Template' ) );
	$page2 = null;

	$reportHTML = ob_get_contents();
	ob_end_clean();

	return $reportHTML;
}


//=================================================
// Print the SEO Clients SEO Tasks Report
//
// Types
// -----------
// 1 - Past 30 Days
// 2 - Single Day
// 3 - All Time
//=================================================
function printMySEOTasksReportForm( $clientID = '', $date = '', $type = 1 ) {
	global $ftsdb, $mySEOMenus, $mbp_config, $mySEOCats, $childGroupIds, $weights, $TASK_STATUS, $TASK_STATUS_COLOR;

	$returnVar = '
			<form name="mySEOTasksReportForm" id="mySEOTasksReportForm" action="" method="post" class="inputForm" onsubmit="return false;">
				<fieldset>
					<legend id="mySEOTasksReportLegend">
						<div class="pull-right form-inline">
							<input type="submit" name="submit" class="btn btn-primary" value="Show Report" />
							<input type="button" name="printTasksReportPDFButtton" id="printTasksReportPDFButtton" class="btn btn-info" value="Print PDF" /> 
						</div>
						' . getFormItemFromArray( array(
			'text'         => 'Website',
			'name'         => 'clientID',
			'type'         => 'select',
			'options'      => getDropdownArray( 'seo_clients' ),
			'showLabel'    => 0,
			'class'        => 'required',
			'currentValue' => $clientID
		) ) . '
						' . getFormItemFromArray( array(
			'text'         => 'Date',
			'name'         => 'date',
			'type'         => 'text',
			'size'         => 20,
			'showLabel'    => 0,
			'class'        => 'required',
			'currentValue' => date( 'Y-m-d' )
		) ) . '
						' . getFormItemFromArray( array(
			'text'      => 'Type of Report',
			'name'      => 'type',
			'type'      => 'select',
			'options'   => getDropdownArray( 'typeOfSEOTasksReports' ),
			'showLabel' => 0,
			'class'     => 'required',
		) ) . '
					</legend>
					<div>
						<div id="mySEOTasksReportResponse">
						</div>
					</div>
				</fieldset>
			</form>';

	return $returnVar;
}

//=================================================
// Returns the JQuery functions used to run the 
// seo tasks report
//=================================================
function returnMySEOTasksReportFormJQuery() {
	global $mbp_config;

	$JQueryReadyScripts = "	
		$('#date').datepicker({
			showButtonPanel: true,
			dateFormat: 'yy-mm-dd'
		});
		
		// Print PDF button			
		jQuery(\"#printTasksReportPDFButtton\").click(function() {	
			" . ( ( $mbp_config['ftsmbp_mySEO_pdf_use_free_version'] ) ? "
			window.open( '" . SITE_URL . "/ajax.php?action=printMySEOTasksReportPDFFreeVersion&clientID=' +  $('#clientID').val() + '&date=' +  $('#date').val() + '&type=' +  $('#type').val() );
			" : "
			window.open( '" . SITE_URL . "/ajax.php?action=printMySEOTasksReportPDF&clientID=' +  $('#clientID').val() + '&date=' +  $('#date').val() + '&type=' +  $('#type').val() );
			" ) . "
		});
		
		" . makeFormJQuery( 'mySEOTasksReport', SITE_URL . '/ajax.php?action=showMySEOTasksReport' );

	return $JQueryReadyScripts;
}

//=================================================
// Print the SEO Clients SEO Tasks Report
//
// Types
// -----------
// 0 - Past 30 Days
// 1 - Single Day
// 2 - All Time
//=================================================
function printMySEOTasksReport( $clientID = '', $date = '', $type = 0 ) {
	global $ftsdb, $mySEOMenus, $mbp_config, $mySEOCats;

	$lastCatID        = '';
	$websiteDataArray = getSEOClient( $clientID );

	$returnVar = '	
		<div class="pull-right">
			' . $websiteDataArray['name'] . '<br />
			' . $websiteDataArray['url'] . '
		</div>
		' . $mbp_config['ftsmbp_mySEO_report_company_name'] . '<br />
		' . $mbp_config['ftsmbp_mySEO_report_address'] . '<br />
		' . $mbp_config['ftsmbp_mySEO_report_city'] . ', ' . $mbp_config['ftsmbp_mySEO_report_state'] . ' ' . $mbp_config['ftsmbp_mySEO_report_zip'] . '<br />
		<br />	
		<h1>SEO Tasks Report</h1>
		<div id="tasks" class="filter_completed reporting">';

	// Pull completed tasks based on report type
	$extraSQL = ( $type == 1 ) ? ' AND swt.date = :date' : '';
	if ( $type == 0 ) {
		$date     = date( 'Y-m-d', strtotime( '-30 days' ) );
		$extraSQL = ' AND swt.date >= :date';
	}
	$result = $ftsdb->select(
		'`' . DBTABLEPREFIX . 'seo_clients_tasks` swt LEFT JOIN `' . DBTABLEPREFIX . 'seo_tasks` st ON swt.todo_id = st.id',
		"swt.client_id = :clientID AND swt.status = 2" . $extraSQL . " ORDER BY st.cat_id, swt.todo_id",
		array(
			':clientID' => $clientID,
			':date'     => $date,
		),
		'swt.*, st.cat_id, st.title, st.description'
	);

	if ( $result ) {
		foreach ( $result as $row ) {
			if ( $lastCatID != $row['cat_id'] ) {
				$returnVar .= '<h2>' . $mySEOCats[ $row['cat_id'] ]['name'] . '</h2>';
			}

			$returnVar .= '		
				<div class="task clearfix state_completed">
					<div class="taskwrap"> 
						<div class="pull-right">Completed: ' . $row['date'] . '</div>
						' . $row['title'] . '
						<div class="taskfunctions_container"> 
							<div class="taskpopover taskfunction taskfunction_about"> 
								<h4>About Task</h4> 
								<div>' . $row['description'] . '</div>
								' . ( ( ! empty( $row['notes'] ) ) ? '
								<h4>Notes</h4> 
								<div><p>' . nl2br( $row['notes'] ) . '</p></div>
								' : '' ) . '
							</div> 
						</div>
					</div>
				</div>';

			$lastCatID = $row['cat_id'];
		}
	}
	$result = null;

	$returnVar .= '
		</div>';

	return $returnVar;
}

//=================================================
// Returns the JQuery functions used to run the 
// seo tasks report
//=================================================
function returnMySEOTasksReportJQuery( $clientID ) {
	$JQueryReadyScripts = "";

	return $JQueryReadyScripts;
}