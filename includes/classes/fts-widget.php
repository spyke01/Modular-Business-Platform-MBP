<?php
/***************************************************************************
 *                               fts-widget.php
 *                            -------------------
 *   begin                : Thursday, August 23, 2012
 *   copyright            : (C) 2012 Paden Clayton
 *
 *
 ***************************************************************************/


class FTS_Widget {
	var $name;                // Base name for this widget type
	var $title;
	var $description;
	var $number = false;    // Unique ID number of the current instance.
	var $id = false;        // Unique ID string of the current instance (id_base-number)

	//===============================================================
	// PHP5 constructor 
	//===============================================================
	function __construct( $name, $title, $description = '' ) {
		$this->name        = $name;
		$this->title       = $title;
		$this->description = $description;
	}

	//===============================================================
	// This function will display the actual widget
	//===============================================================
	function display( $settings ) {
	}

	//===============================================================
	// This function will display the widget settings form
	//===============================================================
	function form( $settings ) {
	}

	//===============================================================
	// Returns a field name for the widget
	//===============================================================
	function get_field_name( $field_name ) {
		return 'settings[' . $field_name . ']';
	}

	//===============================================================
	// Returns a field id for the widget
	//===============================================================
	function get_field_id( $field_name ) {
		return 'settings-' . $field_name;
	}
}