<?php
/***************************************************************************
 *                               seoTasks.php
 *                            -------------------
 *   begin                : Saturday, Sept 18, 2013
 *   copyright            : (C) 2013 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//==================================================
// Returns the max number of optimization points for a site
//==================================================
function getMaxWebsiteOptimizationPoints() {
	global $ftsdb;
	$optimizationPoints = 0;

	$result = $ftsdb->select( DBTABLEPREFIX . 'seo_tasks', '1', array(), 'SUM(impact) AS points' );

	if ( $result ) {
		$optimizationPoints = $result[0]['points'];
		$result             = null;
	}

	return $optimizationPoints;
}

//==================================================
// Returns the optimization points for a site
//==================================================
function getWebsiteOptimizationPoints( $clientID ) {
	global $ftsdb;
	$optimizationPoints = 0;

	$result = $ftsdb->select( DBTABLEPREFIX . 'seo_tasks', 'id IN ((SELECT todo_id FROM `' . DBTABLEPREFIX . 'seo_clients_tasks` WHERE client_id = :clientID))', array(
		":clientID" => $clientID
	), 'SUM(impact) AS points' );

	if ( $result ) {
		$optimizationPoints = $result[0]['points'];
		$result             = null;
	}

	return $optimizationPoints;
}

//==================================================
// Returns the optimization points progress bar for a site
//==================================================
function getWebsiteOptimizationPointsProgressBar( $clientID ) {
	global $ftsdb;
	$optimizationPoints = getWebsiteOptimizationPoints( $clientID );
	$maxPoints          = getMaxWebsiteOptimizationPoints();
	$percentComplete    = number_format( $optimizationPoints / $maxPoints * 100, 2 );

	return '
		<div class="progress">
			<div class="progress-bar" role="progressbar" aria-valuenow="' . $optimizationPoints . '" aria-valuemin="0" aria-valuemax="' . $maxPoints . '" style="min-width: 4em; width: ' . $percentComplete . '%;">
				' . $percentComplete . '%
			</div>
		</div>';
}

//==================================================
// Returns the number of open tasks for a category
//==================================================
function getOpenTasksForCat( $clientID, $catID ) {
	global $ftsdb, $mySEOTasks;
	$tasks      = (array) $mySEOTasks[ $catID ];
	$numOfTasks = count( $tasks );

	if ( $numOfTasks > 0 ) {
		$preparedInClause            = $ftsdb->prepareInClauseVariable( array_keys( $tasks ) );
		$selectBindData              = $preparedInClause['data'];
		$selectBindData[':clientID'] = $clientID;

		// Only pull task data for the tasks in this cat
		$result = $ftsdb->select(
			DBTABLEPREFIX . 'seo_clients_tasks',
			'client_id = :clientID AND status != 1 AND todo_id IN (' . $preparedInClause['binds'] . ')',
			$selectBindData,
			'COUNT(id) AS completedTasks'
		);

		if ( $result ) {
			$numOfTasks = $numOfTasks - $result[0]['completedTasks'];
			$result     = null;
		}
	}

	return $numOfTasks;
}

//==================================================
// Updates the notes for a task
//==================================================
function saveSEOTaskNotes( $clientID, $taskID, $notes ) {
	global $ftsdb;

	$results = $ftsdb->select( DBTABLEPREFIX . "seo_clients_tasks", "client_id = :clientID AND todo_id = :todoID", array(
		":clientID" => $clientID,
		":todoID"   => $taskID,
	) );
	if ( $results && count( $results ) > 0 ) {
		// entry exits
		$result2 = $ftsdb->update( DBTABLEPREFIX . "seo_clients_tasks", array(
			"notes" => $notes,
		),
			"client_id = :clientID AND todo_id = :todoID", array(
				":clientID" => $clientID,
				":todoID"   => $taskID,
			)
		);
	} else {
		$result2 = $ftsdb->insert( DBTABLEPREFIX . 'seo_clients_tasks', array(
			"client_id" => $clientID,
			"todo_id"   => $taskID,
			"notes"     => $notes,
			"date"      => date( "Y-m-d H:i:s" ),
		) );
	}
	$results = null;

	return $result2;
}

//==================================================
// Updates the status of a task
//==================================================
function updateSEOTaskStatus( $clientID, $taskID, $status ) {
	global $ftsdb;

	$results = $ftsdb->select( DBTABLEPREFIX . "seo_clients_tasks", "client_id = :clientID AND todo_id = :todoID", array(
		":clientID" => $clientID,
		":todoID"   => $taskID,
	) );
	if ( $results && count( $results ) > 0 ) {
		// entry exits
		$result2 = $ftsdb->update( DBTABLEPREFIX . "seo_clients_tasks", array(
			"status" => $status,
		),
			"client_id = :clientID AND todo_id = :todoID", array(
				":clientID" => $clientID,
				":todoID"   => $taskID,
			)
		);
	} else {
		$result2 = $ftsdb->insert( DBTABLEPREFIX . 'seo_clients_tasks', array(
			"client_id" => $clientID,
			"todo_id"   => $taskID,
			"status"    => $status,
			"date"      => date( "Y-m-d H:i:s" ),
		) );
	}
	$results = null;

	return $result2;
}

//==================================================
// Returns the menu items for SEO tasks
//==================================================
function returnSEOTasksMenuItems( $clientID, $catID, $childGroupIds = array(), $isSubmenu = 0, $html = 1 ) {
	global $mySEOCats, $mySEOTasks;

	$returnVar = $children = '';

	// Do we have a good ID?
	if ( isset( $mySEOCats[ $catID ] ) ) {
		// Count
		$countHTML = ( $mySEOCats[ $catID ]['parentID'] != - 1 && isset( $mySEOTasks[ $catID ] ) && count( $mySEOTasks[ $catID ] ) > 0 ) ? ' <em class="count"><small>(' . getOpenTasksForCat( $clientID, $catID ) . ' Open)</small></em>' : '';

		// print main item
		if ( count( $childGroupIds[ $catID ] ) > 0 ) {
			foreach ( $childGroupIds[ $catID ] as $childCatID ) {
				$child = returnSEOTasksMenuItems( $clientID, $childCatID, $childGroupIds, 1, $html );

				if ( $html ) {
					$children .= $child;
				} else {
					$children[] = $child;
				}
			}

			if ( $html ) {
				$returnVar = '
					<li class="dropdown' . ( ( $isSubmenu ) ? '-submenu' : '' ) . '">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="cat' . $catID . '">' . $mySEOCats[ $catID ]['name'] . $countHTML . '</a>
						<ul class="dropdown-menu">
							' . $children . '
						</ul>
					</li>';
			} else {
				$returnVar = array(
					'text'  => $mySEOCats[ $catID ]['name'] . $countHTML,
					'catID' => $catID,
					'nodes' => $children
				);
			}
		} else {
			if ( $html ) {
				$returnVar = '
				<li><a href="#" id="cat' . $catID . '">' . $mySEOCats[ $catID ]['name'] . $countHTML . '</a></li>';
			} else {
				$returnVar = array(
					'text'  => $mySEOCats[ $catID ]['name'] . $countHTML,
					'catID' => $catID,
				);
			}
		}
	}

	return $returnVar;
}

//=================================================
// Print the Websites SEO Tasks Menu Tree View
//=================================================
function printSEOClientSEOTasksTreeview() {
	return '<div id="clientSEOTasksTree"></div>';
}

//=================================================
// Handles the Websites SEO Tasks Menu Tree View AJAX
//=================================================
function returnSEOClientSEOTasksTreeviewJQuery( $clientID ) {
	$JQueryReadyScripts = "generateSEOTasksTreeView( $clientID, 'clientSEOTasksTree' );";

	return $JQueryReadyScripts;
}

//=================================================
// Print the Websites SEO Tasks status filters
//=================================================
function printSEOClientSEOTasksStatusFilter( $clientID ) {
	global $TASK_STATUS, $TASK_STATUS_COLOR;

	$statusItems = '';

	// Build status links
	foreach ( $TASK_STATUS as $code => $name ) {
		$statusItems .= '
			<li><a href="#" id="status_' . $code . '">' . $name . ' <span class="label ' . $TASK_STATUS_COLOR[ $code ] . ' pull-right"></span></a></li>';
	}

	return '
		<div class="btn-group pull-right">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Status Filter <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" id="statusFilters">
					' . $statusItems . '
			</ul>
		</div>';
}

//=================================================
// Print the Websites SEO Tasks
//=================================================
function printSEOClientSEOTasks( $clientID, $showMenu = 1 ) {
	global $ftsdb, $mySEOMenus, $mbp_config, $mySEOCats, $childGroupIds, $weights, $TASK_STATUS, $TASK_STATUS_COLOR;

	$mainCatIds = $childGroupIds = $weights = array();
	$menuItems  = $returnVar = $statusItems = '';

	// Build category links
	foreach ( $mySEOCats as $catID => $catData ) {
		$childGroupIds[ $catID ] = array();
		$weights[ $catID ]       = $catData['weight'];
	}
	foreach ( $mySEOCats as $catID => $catData ) {
		if ( $catData['parentID'] != - 1 ) {
			$childGroupIds[ $catData['parentID'] ][] = $catID;
		} else {
			$mainCatIds[] = $catID;
		}
	}

	foreach ( $mainCatIds as $catID ) {
		// We need to sort the keys so that we can change them
		usort( $childGroupIds[ $catID ], 'cmpWeightForChildGroupIds' );
		//print_r($childGroupIds);

		$menuItems .= returnSEOTasksMenuItems( $clientID, $catID, $childGroupIds );
	}

	// Build status links
	foreach ( $TASK_STATUS as $code => $name ) {
		$statusItems .= '
			<li><a href="#" id="status_' . $code . '">' . $name . ' <span class="label ' . $TASK_STATUS_COLOR[ $code ] . ' pull-right"></span></a></li>';
	}

	if ( $showMenu ) {
		$returnVar .= '
			<nav class="navbar navbar-inverse" role="navigation" id="taskNav">
				<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
					
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav" id="catItems">
							' . $menuItems . '
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Status</a>
								<ul class="dropdown-menu" id="statusFilters">
									' . $statusItems . '
								</ul>
							</li>
						</ul>
					</div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->
			</nav>';
	}

	$returnVar .= '
		<h2 data-bind="html: catTitle"></h2>
		<div data-bind="html: catDescription"></div>
		<div id="tasks" class="filter_open" data-bind="foreach: tasks" data-bind="visible: tasks.length > 0">
			<div data-bind="attr: { id: id, class: classes }">
				<div class="taskwrap"> 
					<a class="taskcheckbox" title="Select Task Status" href="#" data-bind="attr: { id: id, \'data-icon\': icon }"> <span>Select Task Status</span> </a> 
					<div style="display:none;" class="taskpopover taskstatus"> 
						<a class="iconcomplete" data-icon="M" href="#" data-state="COMPLETED">Completed</a> 
						<a class="iconhold" data-icon="3" href="#" data-state="SKIPPED">On Hold</a> 
						<a class="iconreject" data-icon="V" href="#" data-state="REJECTED">Rejected</a> 
						<a class="iconopen" data-icon="1" href="#" data-state="OPEN">Open</a>
					</div> 
					<div class="pointsandstatus"> 
						<ul class="taskpoints">  
							<li class="hint"><b data-bind="text: effort"></b><small> Min</small>Effort</li> 
							<li class="impactpoints hint"><b data-bind="text: impact"></b><small> Pts</small>Impact</li> 
						</ul> 
					</div> 
					<p data-bind="html: title"></p> 
					<ul class="taskfunctions"> 
						<li><a data-icon="r" href="#" data-function="about">About</a></li> 
						<li><a data-icon="s" href="#" data-function="how_to_complete">How To</a></li> 
						<li><a data-icon="t" href="#" data-function="notes">Notes</a></li>  
					</ul> 
					<div class="taskfunctions_container"> 
						<div style="display:none;" class="taskpopover taskfunction taskfunction_about"> 
							<a class="closepopover" data-icon="V" title="Close" href="#"><span>Close</span></a> 
							<h4>About Task</h4> 
							<div data-bind="html: description"></div>
						</div> 
						<div style="display:none;" class="taskpopover taskfunction taskfunction_how_to_complete"> 
							<a class="closepopover" data-icon="V" title="Close" href="#"><span>Close</span></a> 
							<h4>How To Complete</h4> 
							<div data-bind="html: howto"></div>
						</div> 
						<div style="display:none;" class="taskpopover taskfunction taskfunction_notes"> 
							<a class="closepopover" data-icon="V" title="Close" href="#"><span>Close</span></a> 
							<h4>Notes</h4> 
							<p><textarea name="notes" data-bind="text: notes" class="form-control"></textarea></p> 
							<p><button class="btn btn-info" data-bind="click: $parent.saveNote">Save</button></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<span data-bind="visible: showNoTasksText">' . return_error_alert( 'No tasks here! If you are using a filter, then it might be hiding some tasks.' ) . '</span>';

	return $returnVar;
}

//=================================================
// Returns the JQuery functions used to run the 
// seo tasks
//=================================================
function returnSEOClientSEOTasksJQuery( $clientID ) {
	$JQueryReadyScripts = "
		seoClientTasksObj = new seoTasks('$clientID');
		ko.applyBindings(seoClientTasksObj);";

	return $JQueryReadyScripts;
}