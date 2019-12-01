<?php 
/***************************************************************************
 *                               graphit.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/

 
/* Define our Paths */
define('ABSPATH', dirname(__FILE__) . '/');
define('BASEPATH', rtrim(ABSPATH, '/'));

include BASEPATH . '/includes/header.php';

if ( user_access('graphs_access') ) {
	$selectedGraph = keepsafe($_REQUEST['selectedGraph']);
	$graphType = keepsafe($_REQUEST['graphType']);
	$daterange = keepsafe($_REQUEST['daterange']);
	$start_date = keepsafe($_REQUEST['start_date']);
	$stop_date = keepsafe($_REQUEST['stop_date']);
	$currentTime = time();
	$startDate = "";
	$endDate = "";
	$graphSuffix = "";
	
	if ($daterange == "today") {
		$startDate = strtotime("today");
		$endDate = strtotime("+1 day");
		$graphSuffix = "Today";
	} elseif ($daterange == "thisWeek") {
		$startDate = strtotime(date("Y").'W'.date('W')."0");
		$endDate = strtotime(date("Y").'W'.date('W')."7");
		$graphSuffix = "This Week";
	} elseif ($daterange == "thisMonth") {
		$startDate = strtotime(makeMonth($currentTime) . " 1, " . makeYear($currentTime));
		$endDate = makeXMonthsFromCurrentMonthAsTimestamp(1);
		$graphSuffix = "This Month";
	} elseif ($daterange == "thisYear") {
		$startDate = strtotime("Jan 1, " . makeYear($currentTime));
		$endDate = strtotime("Jan 1, " . (makeYear($currentTime) + 1));
		$graphSuffix = "This Year";
	} elseif ($daterange == "allTime") {
		$graphSuffix = "All Time";
	} else {
		$startDate = strtotime($start_date);
		$endDate = strtotime($stop_date);
		$graphSuffix = "Between " . $start_date . " and " . $stop_date;
	}

	// Declare our basic variables that we will use
	$graphType = ($graphType == "") ? "column" : $graphType;
	$firstSeriesData = array();
	$secondSeriesData = array();
	$dataTitles = array();
	
	// Call any module graphs
	$extraReportJQuery = callModuleHook('', 'graphsPage', array(
		'section' => 'graphs'
	));
}