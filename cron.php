<?php
/***************************************************************************
 *                               cron.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/


/* Define our Paths */
define( 'IN_CRON', true );
define( 'ABSPATH', dirname( __FILE__ ) . '/' );
define( 'BASEPATH', rtrim( ABSPATH, '/' ) );

include BASEPATH . '/includes/header.php';

$floodControlTimer = get_config_value( 'ftsmbp_cron_flood_control_timer' );
$useFloodControl   = get_config_value( 'ftsmbp_cron_use_flood_control', 0 );
$showLog           = get_config_value( 'ftsmbp_cron_show_log', 0 );
$log               = '';

if ( $floodControlTimer <= time() || ! $useFloodControl ) {
	// Log this event
	$logID = addLogEvent( [
		'type'    => LOG_TYPE_CRON,
		'message' => 'Running cron',
		'start'   => time(),
	] );

	// Determine what we are doing
	if ( ! isset( $_REQUEST['action'] ) ) {
		// Rebuild sitemap.xml
		$log .= callModuleHook( '', 'sitemapPage' ); // sets the proper variables so we can now get our sitemap list
		$log .= rebuildSitemapXML();
		pruneLogs();

		// Do any module specific cron tasks
		$log .= callModuleHook( '', 'cronTasks' );

		// Run automatic updates if turned on
		if ( isset( $mbp_config['ftsmbp_automatic_updates'] ) && $mbp_config['ftsmbp_automatic_updates'] == ACTIVE && A_LICENSE != 'FREE_VERSION' ) {
			$log .= checkForMBPUpdates();
			$log .= checkForModuleUpdates();
			$log .= checkForThemeUpdates();

			// Run our DB updates by rerunning cron with the updateMBPDatabase action
			$fts_http->request( $mbp_config['ftsmbp_site_url'] . '/cron.php?action=updateMBPDatabase' );
		}
	} elseif ( $_REQUEST['action'] == 'updateMBPDatabase' ) {
		$log = updateMBPDatabase();
	} elseif ( $_REQUEST['action'] == 'runJob' ) {
		// Do any module specific cron tasks
		$log = callModuleHook( '',
			'cronTasks',
			[
				'job' => $_REQUEST['job'],
			] );
	}

	if ( $showLog ) {
		echo $log;
	}

	// Add our flood control
	add_config_value( 'ftsmbp_cron_flood_control_timer', strtotime( '+2 minutes' ) );

	// Mark that we are done
	updateLogEvent( $logID, [ 'stop' => time() ] );
} else {
	echo 'Flood Control: Please try again in 2 minutes.';
}