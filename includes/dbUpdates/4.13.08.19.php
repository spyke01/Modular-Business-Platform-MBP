<?php

// Changes on 4.13.08.19
// We have a new default theme 'Bootstrap', everyone has to switch to this by default due to all the HTML element and extra changes
update_config_value( 'ftsmbp_theme', 'bootstrap' );

// Copy theme values from default
add_config_value( 'ftsmbp_theme_bootstrap_settings_backgroundPattern', get_config_value( 'ftsmbp_theme_default_settings_backgroundPattern' ) );
add_config_value( 'ftsmbp_theme_bootstrap_settings_contentColorScheme', get_config_value( 'ftsmbp_theme_default_settings_contentColorScheme' ) );
add_config_value( 'ftsmbp_theme_bootstrap_settings_menuColorScheme', get_config_value( 'ftsmbp_theme_default_settings_menuColorScheme' ) );
add_config_value( 'ftsmbp_theme_bootstrap_settings_sidebarColorScheme', get_config_value( 'ftsmbp_theme_default_settings_sidebarColorScheme' ) );