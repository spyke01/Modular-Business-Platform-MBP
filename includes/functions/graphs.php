<?php 
/***************************************************************************
 *                               graphs.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/

	
//=================================================
// Create a form to run a custom graph
//=================================================
function printNewGraphForm() {
	global $menuvar, $mbp_config;
	
	$formFields = apply_filters( 'form_fields_graphs_new', array(
		array(
			'text' => '1. Choose Graph',
			'type' => 'separator',
		),
		'selectedGraph' => array(
			'text' => 'Graph',
			'type' => 'select',
			'options' => getDropdownArray("graphs"),
			'default' => 'invoicedVsPaid',
			'class' => 'required',
		),
		array(
			'text' => '2. Choose Date Range',
			'type' => 'separator',
		),
		'daterange' => array(
			'text' => 'Date Range',
			'type' => 'select',
			'options' => getDropdownArray('daterange'),
			'default' => 'allTime',
			'class' => 'required',
		),
		'start_date' => array(
			'text' => 'Start Date',
			'type' => 'text',
		),
		'stop_date' => array(
			'text' => 'Stop Date',
			'type' => 'text',
		),
		array(
			'text' => '3. Choose Graph Type',
			'type' => 'separator',
		),
		'graphType' => array(
			'text' => 'Graph Type',
			'type' => 'select',
			'options' => getDropdownArray('graphtypes'),
			'default' => 'column',
			'class' => 'required',
		),
	));
	
	$content = makeForm('newGraph', il($menuvar['GRAPHS']), 'Generate a Custom Graph', 'Generate a Graph', $formFields, array(), 1);
		
	return $content;
}

//=================================================
// Returns the JQuery functions used to run the 
// new graph form
//=================================================
function returnNewGraphFormJQuery($reprintGraph = 0) {
	// $reprintGraph isn't used
	$JQueryReadyScripts = "
		$('#start_date').datepicker({
			showButtonPanel: true
		});
		$('#stop_date').datepicker({
			showButtonPanel: true
		});
		" . makeFormJQuery('newGraph', 'graphit.php');
	
	return $JQueryReadyScripts;
}