<?php

// Changes before 4.14.06.02
if ( ! config_value_exists( 'ftsmbp_icon' ) ) {
	add_config_value( 'ftsmbp_icon', SITE_URL . '/favicon.ico' );
}