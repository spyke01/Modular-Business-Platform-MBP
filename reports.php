<?php 
/***************************************************************************
 *                               reports.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/

if (user_access('reports_access')) {
	//==================================================
	// Handle editing, adding, and deleting of users
	//==================================================	
	if ($actual_action == "viewreport" && isset($actual_report)) {
		// Add breadcrumb
		$page->addBreadCrumb("View Report", "");
		$reportData = $reportJQuery = "";
		
		// Depending on the report we request lets build the page
		switch ($actual_report) {
			case 'latestLogEntries':
				$reportData = printLatestLogEntriesReport();
				$reportJQuery = returnLatestLogEntriesReportJQuery();
				break;
			case 'userDetails':
				$reportData = printUserDetailsReport();
				$reportJQuery = returnUserDetailsReportJQuery();
				break;
		}
			
		// Get our module items
		$reportData .= callModuleHook($actual_prefix, 'reportsPage', array(
			'section' => 'reports',
			'subsection' => 'content',
			'report' => $actual_report
		));	
		$reportJQuery .= callModuleHook($actual_prefix, 'reportsPage', array(
			'section' => 'reports',
			'subsection' => 'jQuery',
			'report' => $actual_report
		));
		
		// Check to see if we have any report data
		if (empty($reportData)) $reportData = "You did not specify a proper report, please try again.";
		
		
		// Take and send the actual data to the page
		$otherVersionLink = ($actual_style == "printerFriendly") ? "<a href=\"" . il( $menuvar['VIEWREPORT'] ) . "&prefix=" . $actual_prefix . "&report=" . $actual_report . "\">Normal Version</a>" : "<a href=\"" . il( $menuvar['VIEWREPORT']) . "&prefix=" . $actual_prefix . "&report=" . $actual_report . "&style=printerFriendly" . "\" class=\"btn btn-info\"><i class=\"glyphicon glyphicon-print\"></i> Printer Friendly Version</a>";
		
		$page_content .= "
			<div class=\"box tabbable\">
				<div class=\"box-header\">
					<h3>Report Data</h3>
				</div>
				<div class=\"box-content\" id=\"reportData\">
					<span class=\"pull-right\"> " . apply_filters( 'reports_otherVersionLinks', $otherVersionLink, $actual_prefix, $actual_report ) . "</span>
					" . $reportData . "
				</div>
			</div>";
		
		// Handle our JQuery needs
		$JQueryReadyScripts = $reportJQuery;
	} else {		
		//==================================================
		// Print out our reports table
		//==================================================	
		// Get our module items
		$extraReportLinks = callModuleHook('', 'reportsPage', array(
			'section' => 'links'
		));	
		
		$page_content .= '
			<div class="box tabbable">
				<div class="box-header">
					<h3><i class="glyphicons glyphicons-table"></i> ' . __('Reports') . '</h3>
					<div class="toolbar">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#builtinReports" data-toggle="tab"><span>' . __('Built-in Reports') . '</span></a></li>
						</ul>
					</div>
				</div>
				<div class="tab-content">
					<div id="builtinReports" class="tab-pane active">
						<ul>
							<li><a href="' . il( $menuvar['VIEWREPORT'] . '&report=latestLogEntries' ) . '">' . __('Latest Log Entries') . '</a></li>
							<li><a href="' . il( $menuvar['VIEWREPORT'] . '&report=userDetails' ) . '">' . __('User Details') . '</a></li>
							' . $extraReportLinks . '
						</ul>
					</div>
				</div>
			</div>';
				
		// Handle our JQuery needs
		$JQueryReadyScripts = "";
	}
	
	$page->setTemplateVar('PageContent', $page_content);
	$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);
} else {
	$page->setTemplateVar('PageContent', notAuthorizedNotice());
}