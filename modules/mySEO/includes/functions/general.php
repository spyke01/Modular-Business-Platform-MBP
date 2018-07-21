<?php
/***************************************************************************
 *                               general.php
 *                            -------------------
 *   begin                : Saturday, Sept 18, 2013
 *   copyright            : (C) 2013 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



function cmpWeight( $a, $b ) {
	return strcmp( $a->weight, $b->weight );
}

function cmpWeightForChildGroupIds( $a, $b ) {
	global $weights;

	return strcmp( $weights[ $a ], $weights[ $b ] );
}

function displayMySEOWelcomeScreen() {
	$returnVar = '';

	if ( user_access( 'view_tour' ) && get_config_value( 'shown_myseo_tour' ) == 0 ) {
		$returnVar = '
			<div id="mySEOTour" class="jumbotron">
				<h1>Welcome to the My SEO Management System Dashboard</h1>
				<p>To familiarize yourself with this system please watch the training video by clicking the link below.</p>
				<p>
					<a href="https://youtu.be/5pBcWShmROk" role="button" class="btn btn-success btn-lg" target="_blank">Watch our Training Video</a>
					<a role="button" class="btn btn-primary btn-lg" id="hideMySEOWelcomeBanner">I\'ve got this, don\'t remind me again</a>
				</p>
			</div>';
	}

	return $returnVar;
}

function displayMySEOSettingsAlert() {
	global $mbp_config, $menuvar;

	if ( empty( $mbp_config['ftsmbp_mySEO_report_company_name'] ) ) {
		return return_error_alert( 'You need to configure your report settings for the My SEO Module. Please <a href="' . $menuvar['SETTINGS'] . '">click here</a> and click the <strong>MySEO Settings</strong> tab to enter your information.' );
	}
}