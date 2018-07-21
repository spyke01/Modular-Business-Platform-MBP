<?php

// Changes before 4.15.04.27

// Make sure the language setting is in place
if ( !config_value_exists('ftsmbp_language') )
	add_config_value('ftsmbp_language', 'en');
if ( !config_value_exists('ftsmbp_use_https') )
	add_config_value('ftsmbp_use_https', 0);