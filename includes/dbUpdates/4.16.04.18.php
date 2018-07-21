<?php

// Changes on 4.16.04.18
if ( get_config_value( 'ftsmbp_theme' ) == 'bootstrap' ) {
	// We have a new default theme 'Modern', everyone has to switch to this by default since we have removed the base bootstrap theme
	update_config_value( 'ftsmbp_theme', 'modern' );
	
	// Copy theme values from default
	add_config_value( 'ftsmbp_theme_modern_settings_backgroundPattern', get_config_value( 'ftsmbp_theme_bootstrap_settings_backgroundPattern' ) );
	add_config_value( 'ftsmbp_theme_modern_settings_contentColorScheme', get_config_value( 'ftsmbp_theme_bootstrap_settings_contentColorScheme' ) );
	add_config_value( 'ftsmbp_theme_modern_settings_menuColorScheme', get_config_value( 'ftsmbp_theme_bootstrap_settings_menuColorScheme' ) );
	add_config_value( 'ftsmbp_theme_modern_settings_sidebarColorScheme', get_config_value( 'ftsmbp_theme_bootstrap_settings_sidebarColorScheme' ) );
}