<?php
/***************************************************************************
 *                               widgets.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


//=================================================
//Check for orphaned widgets and move them
//=================================================
function checkWidgetAreas() {
	global $available_widgetAreas;

	$widgetAreas          = array( 'wa-unassigned' => 'Unassigned' );
	$widgetAreas_dbNames  = ":unassigned";
	$widgetAreas_dbValues = array( ":unassigned" => 'wa-unassigned' );

	$filteredWidgetAreas = apply_filters( 'widget_areas', $available_widgetAreas );

	foreach ( $filteredWidgetAreas as $area => $name ) {
		$widgetAreas[ 'wa-' . $area ]   = $name;
		$widgetAreas_dbNames            .= ",:$area";
		$widgetAreas_dbValues[":$area"] = "wa-$area";
	}

	// Assign widgets in any unknown widgets to the unassigned category 
	/*
	$result = $ftsdb->update(DBTABLEPREFIX . "widgets", array(
			"area" => 'wa-unassigned'
		), 
		"area NOT IN ($widgetAreas_dbNames)", $widgetAreas_dbValues
	);
	*/

	// Update our widget areas in the DB
	add_config_value( 'ftsmbp_widget_areas', serialize( $widgetAreas ) );
}

//=================================================
// Allows us to register a widget
//=================================================
function registerWidget( $widgetClass ) {
	global $available_widgets;

	$available_widgets[ $widgetClass ] = new $widgetClass();
}

//=================================================
// Gets the next instance number for an item
//=================================================
function getNextWidgetInstanceNumber( $name ) {
	global $ftsdb;

	$instance_number = 1;

	$result = $ftsdb->select( DBTABLEPREFIX . "widgets",
		"widget_id LIKE :widget_id ORDER BY `id` DESC LIMIT 1",
		array(
			":widget_id" => $name . '%',
		),
		'widget_id' );

	if ( $result ) {
		foreach ( $result as $row ) {
			$instance_number = preg_replace( "/[^0-9]/", "", $row['widget_id'] ) + 1;
		}
		$result = null;
	}

	return $instance_number;
}

//=================================================
// Lists all registered widgets
//=================================================
function listAvailableWidgets() {
	global $available_widgets;
	$list = '';

	foreach ( $available_widgets as $class => $instance ) {
		$list .= returnWidgetBlock( $class, '__i__' );
	}

	return $list;
}

//=================================================
// Lists all widgets for a specific area
//=================================================
function listWidgetsByArea( $area ) {
	global $ftsdb, $available_widgets;
	$list = '';

	$result = $ftsdb->select( DBTABLEPREFIX . "widgets",
		"area = :area ORDER BY `order`",
		array(
			":area" => $area,
		) );

	if ( $result ) {
		foreach ( $result as $row ) {
			$list .= returnWidgetBlock( $row['type'], preg_replace( "/[^0-9]/", "", $row['widget_id'] ), $row['settings'] );
		}
		$result = null;
	}

	return $list;
}

//=================================================
// Return a widget block based on parameters
//=================================================
function returnWidgetBlock( $type, $instanceNumber, $settings = '' ) {
	global $available_widgets;
	$widgetBlock = '';

	if ( isset( $available_widgets[ $type ] ) ) {
		$instance         = $available_widgets[ $type ];
		$instance->number = $instanceNumber;
		$instance_number  = ( is_numeric( $instanceNumber ) ) ? $instanceNumber : getNextWidgetInstanceNumber( $instance->name );

		$widgetBlock .= '
			<div class="widget ui-draggable" id="widget-' . $instance->name . '-' . $instance->number . '">
				<div class="widget-top">
					<div class="widget-title-action">
						<a href="#available-widgets" class="widget-action hide-if-no-js"></a>
					</div>
					<div class="widget-title"><h4>' . $instance->title . '<span class="in-widget-title"></span></h4></div>
				</div>
				
				<div class="widget-inside">
					<form method="post" action="">
						<input type="hidden" value="' . $instance->name . '-' . $instance->number . '" class="widget-id" name="widget-id" />
						<input type="hidden" value="' . $type . '" class="class" name="class" />
						<input type="hidden" value="' . $instance_number . '" class="instance_number" name="instance_number" />
						<input type="hidden" value="' . ( ( $settings == '' ) ? 'yes' : '' ) . '" class="create" name="create" />
						<div class="widget-content">
							' . $instance->form( $settings ) . '
						</div>
					
						<div class="widget-control-actions">
							<div class="pull-left">
								<a href="#remove" class="widget-control-remove">Delete</a> | <a href="#close" class="widget-control-close">Close</a>
							</div>
							<div class="pull-right">
								<span class="ajax-feedback"><span></span> ' . progressSpinnerHTML() . '</span>
								<input type="submit" value="Save" class="btn btn-primary widget-control-save" name="savewidget">
							</div>
							<br class="clear">
						</div>
					</form>
				</div>
				
				<div class="widget-description">' . $instance->description . '</div>
			</div>';
	}

	return $widgetBlock;
}

//=================================================
// Returns the JQuery functions used to run the 
// widgets table
//=================================================
function returnWidgetsTableJQuery() {
	$JQueryReadyScripts = '		
		mbpWidgets.init();';

	return $JQueryReadyScripts;
}

//=================================================
// Lists all widgets areas
//=================================================
function listWidgetAreas() {
	global $mbp_config;

	$list        = '';
	$x           = 1;
	$widgetAreas = unserialize( $mbp_config['ftsmbp_widget_areas'] );
	ksort( $widgetAreas );

	foreach ( $widgetAreas as $area => $name ) {
		$list .= '
			<div class="box' . ( ( $x > 1 ) ? ' closed' : '' ) . '">
				<div class="box-header">
					<div class="sidebar-name-arrow">&nbsp;</div>
					<h3>' . __( $name ) . '</h3>
				</div>
				<div class="box-content widgets-sortables ui-sortable" style="min-height: 98px;" id="' . $area . '">
					' . listWidgetsByArea( $area ) . '
				</div>
			</div>';
		$x ++;
	}

	return $list;
}

//=================================================
// Lists all widgets for a specific area
//=================================================
function displayWidgetsByArea( $area ) {
	global $ftsdb, $available_widgets, $actual_page_id, $page, $mbp_config;
	$list = '';

	$result = $ftsdb->select( DBTABLEPREFIX . "widgets",
		"area = :area ORDER BY `order`",
		array(
			":area" => $area,
		) );

	if ( $result ) {
		foreach ( $result as $row ) {
			if ( isset( $available_widgets[ $row['type'] ] ) ) {
				$instance = $available_widgets[ $row['type'] ];
				//var_dump(get_object_vars($instance));					
				//echo "$actual_page_id " . (string) isset($instance->displayInAdminSection) . " " . $instance->displayInAdminSection . " " . $page->getTemplateVar('Theme')  . " " . $mbp_config['ftsmbp_theme'] . "<br />";
				if ( $actual_page_id == 'admin' && ( isset( $instance->displayInAdminSection ) && $instance->displayInAdminSection == 0 ) && $page->getTemplateVar( 'Theme' ) == $mbp_config['ftsmbp_theme'] ) {
					continue;
				}

				$list .= returnWidgetHTML( $row['type'], $row['widget_id'], $row['settings'] );
			}
		}
		$result = null;
	}

	return $list;
}

//=================================================
// Return a widget block based on parameters
//=================================================
function returnWidgetHTML( $type, $id, $settings = '' ) {
	global $available_widgets;
	$widgetBlock = '';

	if ( isset( $available_widgets[ $type ] ) ) {
		$instance = $available_widgets[ $type ];

		$widgetBlock .= '
			<div class="widget widget-' . $instance->name . '" id="' . $id . '">
				' . $instance->display( $settings ) . '
			</div>';
	}

	return $widgetBlock;
}