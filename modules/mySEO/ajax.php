<?php
//error_reporting(E_ALL);
// Cycle through our AJAX calls and handle the content
if ( $actual_action == 'updateitem' && user_access( 'mySEO_updateitem' ) ) {
	if ( $section == 'before' ) {
	}
} elseif ( $actual_action == 'deleteitem' && user_access( 'mySEO_deleteitem' ) ) {
	if ( $section == 'before' ) {
		// Delete and associated foreign items
		if ( $table == "seo_clients" ) {
			$result     = $ftsdb->delete( DBTABLEPREFIX . "seo_clients_tasks", 'client_id = :client_id', array(
					':client_id' => $actual_id,
				)
			);
			$errorCount += ( $result ) ? 0 : 1;
		}
	}
}

//================================================
// Don't show our tour until the next time we say to
//================================================
elseif ( $actual_action == "showedMySEOTour" && user_access( 'view_tour' ) ) {
	add_config_value( 'shown_myseo_tour', 1 );
}

//================================================
// Add our seo_clients to the database
//================================================
elseif ( $actual_action == "createSEOClient" && user_access( 'mySEO_seo_clients_create' ) ) {
	if ( ! canHaveMultipleSEOClients() && getSEOClientCount() > 0 ) {
		echo return_error_alert( 'Your license only allows for 1 SEO client. To add additional clients you need to upgrade to a paid license. <a href="https://www.fasttracksites.com/product/license-renewal/">Click here to purchase a new license.</a>' );
	} else {
		$created_on    = date( "Y-m-d H:i:s" );
		$name          = keeptasafe( $_POST['name'] );
		$url           = keeptasafe( $_POST['url'] );
		$email_address = keeptasafe( $_POST['email_address'] );
		$phone         = keeptasafe( $_POST['phone'] );
		$status        = 3;

		$result   = $ftsdb->insert( DBTABLEPREFIX . 'seo_clients', array(
			"name"          => $name,
			"url"           => $url,
			"email_address" => $email_address,
			"phone"         => $phone,
			"status"        => $status,
			"created_on"    => $created_on,
		) );
		$clientID = $ftsdb->lastInsertId();

		$content = ( $result ) ? '	<span class="greenText bold">Successfully created website!</span>' : '	<span class="redText bold">Failed to create website!!!</span>';

		switch ( keepsafe( $_GET['reprinttable'] ) ) {
			case 1:
				$finalColumn = '';

				// Only show these links if we aren't deleting the site
				if ( user_access( 'mySEO_seo_clients_edit' ) ) {
					$finalColumn .= '<a href="' . $mySEOMenus['SEOCLIENTS']['link'] . '&amp;action=editSEOClient&amp;id=' . $clientID . '"  class="btn btn-default"><i class="glyphicon glyphicon-edit"></i></a> ';
				}
				if ( user_access( 'mySEO_seo_clients_delete' ) ) {
					$finalColumn .= createDeleteLinkWithImage( $clientID, $clientID . '_row', 'seo_clients', 'website' );
				}

				$tableHTML = '
					<tr class="even" id="' . $clientID . '_row">
						<td>' . $name . '</td>
						<td>' . $url . '</td>
						<td>' . returnSEOClientStatusLabel( $status ) . '</td>
						<td>' . getWebsiteOptimizationPointsProgressBar( $clientID ) . '</td>
						<td class="center"><span  class="btn-group">' . $finalColumn . '</span></td>
					</tr>';

				echo $tableHTML;
				break;
			default:
				echo $content;
				break;
		}
	}
}

//================================================
// Update our seo_clients in the database
//================================================
elseif ( $actual_action == "editSEOClient" && user_access( 'mySEO_seo_clients_edit' ) ) {
	$name          = keeptasafe( $_POST['name'] );
	$url           = keeptasafe( $_POST['url'] );
	$email_address = keeptasafe( $_POST['email_address'] );
	$phone         = keeptasafe( $_POST['phone'] );

	$result = $ftsdb->update( DBTABLEPREFIX . "seo_clients", array(
		"name"          => $name,
		"url"           => $url,
		"email_address" => $email_address,
		"phone"         => $phone,
	),
		"id = :id", array(
			":id" => $actual_id
		)
	);

	$content = ( $result ) ? '	<span class="greenText bold">Successfully updated website!</span>' : '	<span class="redText bold">Failed to update website!!!</span>';

	echo $content;
}

//================================================
// Return the seo tasks tree view data for a client
//================================================
elseif ( $actual_action == "returnSEOTasksTreeViewData" && user_access( 'mySEO_tasks_access' ) ) {
	global $mySEOCats;

	$treeItems  = array();
	$mainCatIds = $childGroupIds = $weights = array();

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

		$treeItems[] = returnSEOTasksMenuItems( $actual_id, $catID, $childGroupIds, 0, 0 );
	}
	//var_export($treeItems);

	echo json_encode( $treeItems );
}

//================================================
// Get the details of a cat
//================================================
elseif ( $actual_action == "getCatDetails" && user_access( 'mySEO_tasks_access' ) ) {
	$catDetails = array(
		'title'       => $mySEOCats[ $actual_id ]['name'],
		'description' => $mySEOCats[ $actual_id ]['description'],
	);

	echo json_encode( $catDetails );
}

//================================================
// Returns the tasks for a website
//================================================
elseif ( $actual_action == "getSEOTasksJSON" && user_access( $actual_action ) ) {
	$returnVar = array(
		'tasks' => array(),
	);
	$catID     = intval( $_GET['catID'] );

	if ( isset( $mySEOTasks[ $catID ] ) ) {
		$tasks        = $mySEOTasks[ $catID ];
		$taskIDsInCat = array();

		// Add a status code for everything
		foreach ( $tasks as $taskID => $taskData ) {
			$tasks[ $taskID ]['status'] = 'OPEN';
			$taskIDsInCat[]             = $taskID;
		}

		// Look at our tasks and pull statuses and notes
		if ( count( $taskIDsInCat ) > 0 ) {
			$preparedInClause            = $ftsdb->prepareInClauseVariable( $taskIDsInCat );
			$selectBindData              = $preparedInClause['data'];
			$selectBindData[':clientID'] = $actual_id;

			// Only pull task data for the tasks in this cat
			$result = $ftsdb->select( DBTABLEPREFIX . "seo_clients_tasks", "client_id = :clientID AND todo_id IN (" . $preparedInClause['binds'] . ")", $selectBindData );
			if ( $result ) {
				foreach ( $result as $row ) {
					$tasks[ $row['todo_id'] ]['status'] = strtoupper( $TASK_STATUS[ $row['status'] ] );
					$tasks[ $row['todo_id'] ]['notes']  = $row['notes'];
				}
			}
			$result = null;
		}

		$returnVar['tasks'] = $tasks;
	}

	echo json_encode( $returnVar );
}

//================================================
// Get the number of open tasks in a cat
//================================================
elseif ( $actual_action == "getOpenTasksForCat" && user_access( 'mySEO_tasks_access' ) ) {
	$catID = intval( $_GET['catID'] );

	echo getOpenTasksForCat( $actual_id, $catID );
}

//================================================
// Save the note for a task
//================================================
elseif ( $actual_action == "saveTaskNotes" && user_access( 'mySEO_tasks_save' ) ) {
	$taskID = intval( $_GET['taskID'] );
	$notes  = keeptasafe( $_POST['notes'] );

	$result = saveSEOTaskNotes( $actual_id, $taskID, $notes );

	echo $result;
}

//================================================
// Update the status of a task
//================================================
elseif ( $actual_action == "updateTaskStatus" && user_access( 'mySEO_tasks_save' ) ) {
	$taskID = intval( $_GET['taskID'] );
	$status = keepsafe( $_GET['status'] );

	$result = updateSEOTaskStatus( $actual_id, $taskID, $status );

	echo $result;
}

//================================================
// Print the SEO Tasks Report
//================================================
elseif ( $actual_action == "showMySEOTasksReport" && user_access( 'mySEO_reports_view' ) ) {
	$clientID = intval( $_POST['clientID'] );
	$date     = keepsafe( $_POST['date'] );
	$type     = intval( $_POST['type'] );

	echo printMySEOTasksReport( $clientID, $date, $type );
}

//================================================
// Print PDF of SEO Tasks Report
//================================================
elseif ( $actual_action == "printMySEOTasksReportPDF" && user_access( 'mySEO_reports_view' ) ) {
	require BASEPATH . '/modules/mySEO/includes/classes/pdfcrowd.php';

	$clientID   = intval( $_GET['clientID'] );
	$client     = getSEOClient( $clientID );
	$clientName = $client['name'];
	$date       = keepsafe( $_GET['date'] );
	$type       = intval( $_GET['type'] );
	$debugMe    = 0;

	try {
		// create an API client instance
		$client = new Pdfcrowd( $mbp_config['ftsmbp_mySEO_pdf_pdf_crowd_username'], $mbp_config['ftsmbp_mySEO_pdf_pdf_crowd_api_key'] );

		// Get the page code
		ob_start();

		$page->setTemplateVar( 'Theme', $mbp_config['ftsmbp_theme'] );
		$page->setTemplateVar( 'Template', 'printerFriendlyTemplate.php' );
		$page->setTemplateVar( 'PageContent', printMySEOTasksReport( $clientID, $date, $type ) );
		include( BASEPATH . '/themes/' . $page->getTemplateVar( 'Theme' ) . '/' . $page->getTemplateVar( 'Template' ) );

		$reportHTML = ob_get_contents();
		ob_end_clean();

		if ( $debugMe ) {
			echo $reportHTML;
		} else {
			// convert a web page and store the generated PDF into a $pdf variable
			$pdf = $client->convertHtml( $reportHTML );

			// set HTTP response headers
			header( "Content-Type: application/pdf" );
			header( "Cache-Control: no-cache" );
			header( "Accept-Ranges: none" );
			header( "Content-Disposition: attachment; filename=\"seoTasksReport.pdf\"" );

			// send the generated PDF
			echo $pdf;
		}
	} catch ( PdfcrowdException $why ) {
		echo "Pdfcrowd Error: " . $why;
	}
}

//================================================
// Print PDF of SEO Tasks Report using the free version of PDFCrowd
// TO-DO: utilize a token system to block access to other client's reports. This would prevent unauthorized access since this is a public facing interface 
//================================================
elseif ( $actual_action == "printMySEOTasksReportPDFFreeVersion" ) {
	$clientID   = intval( $_GET['clientID'] );
	$client     = getSEOClient( $clientID );
	$clientName = $client['name'];
	$date       = keepsafe( $_GET['date'] );
	$type       = intval( $_GET['type'] );

	// Get the page code
	ob_start();

	$page->setTemplateVar( 'Theme', $mbp_config['ftsmbp_theme'] );
	$page->setTemplateVar( 'Template', 'printerFriendlyTemplate.php' );
	$page->setTemplateVar( 'PageContent', printMySEOTasksReport( $clientID, $date, $type ) . '<div class="hidden"><a href="//pdfcrowd.com/url_to_pdf/?width=8.5in&height=11in&pdf_name=seoTasksReport-' . $clientName . '.pdf" id="pdfCrowdLink">Get the PDFCrowd File</a></div>' );
	$page->setTemplateVar( "JQueryReadyScript", "$('#pdfCrowdLink').click(function () {
		window.location = $(this).attr('href');
	}).trigger('click');" );
	include( BASEPATH . '/themes/' . $page->getTemplateVar( 'Theme' ) . '/' . $page->getTemplateVar( 'Template' ) );

	$reportHTML = ob_get_contents();
	ob_end_clean();

	echo $reportHTML;
}

//================================================
// Test code for admins
//================================================
elseif ( $actual_action == 'test5' && user_access( 'mySEO_test5' ) ) {
	// Categories
	$json        = file_get_contents( "http://upcity.com/opus/configurations?siteId=5735" );
	$jsonDecoded = json_decode( $json );
	//print_r( $jsonDecoded );
	$sortedCatsArray = array();

	foreach ( $jsonDecoded as $id => $itemObject ) {
		$sortedCatsArray[ $itemObject->id ] = $itemObject;
	}
	ksort( $sortedCatsArray );
	//print_r( $sortedArray );
	/*
	foreach( $sortedCatsArray as $groupId => $itemObject ) {
		echo "
			Title: $itemObject->title<br />
			Description: $itemObject->description<br />
			How To: $itemObject->howToComplete<br />
			Effort: $itemObject->estimatedMinutesToComplete<br />
			Impact: $itemObject->impactPoints<br />
			Group ID: $groupId<br /><br />";
	}
	*/
	echo '$mySEOCats = array(';

	foreach ( $sortedCatsArray as $groupId => $itemObject ) {
		echo "\n	'$itemObject->id' => array(
		'name' => '" . str_replace( "'", "\'", trim( $itemObject->name ) ) . "',
		'parentID' => '$itemObject->parentId',
		'description' => '" . str_replace( "'", "\'", trim( $itemObject->information ) ) . "',		
		'weight' => '$itemObject->weight',				
	),";
	}

	echo "\n" . ');';

	// Tasks
	$json        = file_get_contents( "http://upcity.com/opus/tasks?siteId=5735" );
	$jsonDecoded = json_decode( $json );
	//print_r( $jsonDecoded );
	$sortedTasksArray = array();

	foreach ( $jsonDecoded as $id => $itemObject ) {
		$sortedTasksArray[ $itemObject->groupId ][] = $itemObject;
	}
	ksort( $sortedTasksArray );

	foreach ( $sortedTasksArray as $groupId => $items ) {
		ksort( $sortedTasksArray[ $groupId ] );
	}
	//print_r( $sortedArray );
	/*
	foreach( $sortedTasksArray as $groupId => $itemObject ) {
		echo "
			Title: $itemObject->title<br />
			Description: $itemObject->description<br />
			How To: $itemObject->howToComplete<br />
			Effort: $itemObject->estimatedMinutesToComplete<br />
			Impact: $itemObject->impactPoints<br />
			Group ID: $groupId<br />
			Group Name: " . $sortedCatsArray[$groupId]->name . "<br /><br />";
	}
	*/
	$id = 1;
	echo "\n\n" . '$mySEOTasks = array(';

	foreach ( $sortedTasksArray as $groupId => $items ) {
		echo "\n	'$groupId' => array(";
		// We need to sort the keys so that we can change them
		$tempArray = $items;
		usort( $tempArray, 'cmpWeight' );

		foreach ( $tempArray as $key => $itemObject ) {
			echo "
		'$id' => array(
			'title' => '" . str_replace( "'", "\'", trim( $itemObject->title ) ) . "',
			'description' => '" . str_replace( "'", "\'", trim( $itemObject->description ) ) . "',
			'howTo' => '" . str_replace( "'", "\'", trim( $itemObject->howToComplete ) ) . "',
			'effort' => '$itemObject->estimatedMinutesToComplete',
			'impact' => '$itemObject->impactPoints',	
			'weight' => '$itemObject->weight',			
		),";
			$id ++;
		}
		echo "\n	),";
	}

	echo "\n);";
}

//================================================
// Test code for admins
//================================================
elseif ( $actual_action == 'test6' && user_access( 'mySEO_test6' ) ) {
	/*
	$catID = 148;
	$itemToRepeat = $mySEOTasks['148']['283'];
	$repetitions = 80;
	$id = 283;
	$weight = 1;
	$array = '';
	
	for ( $i=0; $i<$repetitions; $i++ ) {
		echo "adding $i<br />";
		$array .= "
		'$id' => array(
			'title' => '" . $itemToRepeat['title'] . "',
			'description' => '" . $itemToRepeat['description'] . "',
			'howTo' => '" . $itemToRepeat['howTo'] . "',
			'effort' => '" . $itemToRepeat['effort'] . "',
			'impact' => '" . $itemToRepeat['impact'] . "',	
			'weight' => '$weight',			
		),";
		$result = $ftsdb->insert( DBTABLEPREFIX . 'seo_tasks', array(
			"id" => $id,
			"cat_id" => $catID,
			"title" => $itemToRepeat['title'],
			"description" => $itemToRepeat['description'],
			"how_to" => $itemToRepeat['howTo'],
			"effort" => $itemToRepeat['effort'],
			"impact" => $itemToRepeat['impact'],
			"weight" => $weight,
		) );
		$id++;
		$weight++;
	}
	
	echo $array;
	*/

	/*
	foreach ( $mySEOTasks as $catID => $tasks ) {
		foreach ( $tasks as $key => $task ) {
			//echo "adding " . $task['title'] . "<br />";
			$result = $ftsdb->insert( DBTABLEPREFIX . 'seo_tasks', array(
				"id" => $id,
				"cat_id" => $catID,
				"title" => $task['title'],
				"description" => $task['description'],
				"how_to" => $task['howTo'],
				"effort" => $task['effort'],
				"impact" => $task['impact'],
				"weight" => $task['weight'],
			) );
		}
	}
	*/
}

