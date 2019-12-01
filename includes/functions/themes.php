<?php
/***************************************************************************
 *                               themes.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


//=========================================================
// Handle creating our theme config variables
//=========================================================
function theme_register_options( $theme_folder, $theme_config = [] ) {
	global $ftsdb, $available_widgetAreas;

	$defaultThemeSettings = [];

	// Prep our settings array
	if ( is_array( $theme_config ) && count( $theme_config ) > 0 ) {
		// Handle Menus
		if ( isset( $theme_config['menus'] ) ) {
			foreach ( $theme_config['menus'] as $name => $text ) {
				$defaultThemeSettings[ 'ftsmbp_theme_' . $theme_folder . '_menus_' . $name ] = '';
			}
		}

		// Handle Settings
		if ( isset( $theme_config['settings'] ) ) {
			foreach ( $theme_config['settings'] as $name => $settingInfo ) {
				// if settingInfo is not an array then its the name
				// if it is then we may have a default value for this setting				
				$defaultThemeSettings[ 'ftsmbp_theme_' . $theme_folder . '_settings_' . $name ] = ( ( is_array( $settingInfo ) && isset( $settingInfo['default'] ) ) ? $settingInfo['default'] : '' );
			}
		}

		// Check widget areas	
		if ( isset( $theme_config['widget_areas'] ) ) {
			$available_widgetAreas = array_merge( $available_widgetAreas, (array) $theme_config['widget_areas'] );
		}
	}

	// Add any new settings	
	if ( count( $defaultThemeSettings ) ) {
		foreach ( $defaultThemeSettings as $name => $value ) {
			if ( ! config_value_exists( $name ) ) {
				add_config_value( $name, $value );
			}
		}
	}
}

//=========================================================
// Print the Themes table
//=========================================================
function printThemesTable() {
	global $menuvar, $mbp_config;

	$content       = $currenttheme = '';
	$sub_dir_names = [];
	$currenttheme  = $mbp_config['ftsmbp_theme'];

	// Themes available to all users
	$stylepath     = BASEPATH . "/themes";
	$sub_dir_names = getDirNames( $stylepath, 'installer,jquery,modules' );
	ksort( $sub_dir_names ); //sort by name

	//==================================================
	// Build our table
	//==================================================
	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "themesTable" );

	// Create table title
	$table->addNewRow( [ [ 'data' => "Available Themes", "colspan" => "5" ] ], '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		[
			[ 'type' => 'th', 'data' => "Preview" ],
			[ 'type' => 'th', 'data' => "Name", 'class' => 'hidden-sm' ],
			[ 'type' => 'th', 'data' => "Author", 'class' => 'visible-lg' ],
			[ 'type' => 'th', 'data' => "Version", 'class' => 'visible-lg' ],
			[ 'type' => 'th', 'data' => "Active" ],
		],
		'',
		'title2',
		'thead'
	);

	// Add our data
	if ( empty( $sub_dir_names ) ) {
		$table->addNewRow( [ [ 'data' => "There are no themes in the system.", "colspan" => "5" ] ], "themesTableTableDefaultRow", "greenRow" );
	} else {
		$x = 1; //reset the variable we use for our row colors

		foreach ( $sub_dir_names as $name => $nothing ) {
			// Only show themes that have a themedetails file	
			if ( file_exists( $stylepath . '/' . $name . '/themedetails.php' ) ) {
				include( $stylepath . '/' . $name . '/themedetails.php' );

				$table->addNewRow( [
					[ 'data' => "<img src=\"" . ( ( is_file( $stylepath . '/' . $name . '/preview.png' ) ) ? SITE_URL . '/' . $stylepath . '/' . $name . "/preview.png" : "images/nopreview.png" ) . "\" alt=\"Preview\" />", 'class' => 'center' ],
					[ 'data' => $themeName, 'class' => 'hidden-sm' ],
					[ 'data' => $themeAuthor, 'class' => 'visible-lg' ],
					[ 'data' => $themeVersion, 'class' => 'visible-lg' ],
					[ 'data' => ( ( $currenttheme == $name ) ? '<a class="btn btn-primary" onClick="return false;">Current Theme</a>' : '<a  class="btn btn-default" href="' . il( $menuvar['THEMES'] . '&action=change_theme&theme=' . $name ) . '">Activate Theme</a>' ), 'class' => 'center' ],
				],
					"",
					"row" . $x );

				$x = ( $x == 2 ) ? 1 : 2;
			}
		}
	}

	unset( $sub_dir_names );

	return $table->returnTableHTML();
}

//=========================================================
// Print the Theme Settings table
//=========================================================
function printThemeSettingsTable( $theme, $folder = '' ) {
	global $menuvar, $mbp_config;

	$namePrefix         = str_replace( '/', '_', $folder );
	$content            = 'This theme has no settings available';
	$themeFunctionsFile = BASEPATH . '/themes/' . $folder . $theme . '/functions.php';

	if ( is_file( $themeFunctionsFile ) ) {
		include_once( $themeFunctionsFile );
		$themeConfigArrayName = 'theme_config_' . $theme;
		global $$themeConfigArrayName;

		if ( is_array( ${$themeConfigArrayName} ) && isset( ${$themeConfigArrayName}['settings'] ) && count( ${$themeConfigArrayName}['settings'] ) > 0 ) {
			// Ok we actually have settings so lets prefix them and then build our
			$formFields = apply_filters(
				'form_fields_theme_settings_' . $namePrefix . $theme,
				addPrefixToFormFields(
					'ftsmbp_theme_' . $namePrefix . $theme . '_settings_',
					${$themeConfigArrayName}['settings'] )
			);
			$content    = makeForm( '', il( $menuvar['THEMES'] . "&action=update_theme_settings&theme=" . $theme . "&folder=" . $folder ), $theme . ' - Theme Settings', 'Update Settings', $formFields, $mbp_config );
		}
	}

	// Actually send something back
	return $content;
}