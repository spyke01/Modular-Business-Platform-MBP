<?php
/***************************************************************************
 *                               settings.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/


if ( $_SESSION['user_level'] == SYSTEM_ADMIN ) {
	// Handle updating system variables in the database
	if ( isset( $_POST['submit'] ) ) {
		foreach ( $_POST as $name => $value ) {
			if ( $name != "submit" ) {
				if ( $name == "ftsmbp_active" ) {
					if ( $value == '' ) {
						$value = 0;
					} else {
						$value = 1;
					}
				}

				// Delete our setting and recreate it
				add_config_value( $name, $value );
			}
		}

		// Call our module settings updates	
		callModuleHook( '',
			'settingsPage_submit',
			[
				'content' => $_POST,
			] );

		// Handle checkboxes, unchecked boxes are not posted so we check for this and mark them in the DB as such
		$togglesAndChecks = [
			'ftsmbp_active',
			'ftsmbp_automatic_updates',
			'ftsmbp_cron_show_log',
			'ftsmbp_cron_use_flood_control',
			'ftsmbp_enable_account_creation_alert',
			'ftsmbp_enable_account_updated_alert',
			'ftsmbp_enable_logging',
			'ftsmbp_enable_public_account_creation',
			'ftsmbp_mod_rewrite',
			'ftsmbp_require_account_activation',
			'ftsmbp_use_html_suffix',
			'ftsmbp_use_https',
			'ftsmbp_email_ssl',
		];
		foreach ( $togglesAndChecks as $key ) {
			if ( ! isset( $_POST[ $key ] ) ) {
				update_config_value( $key, 0 );
			}
		}

		// Handle HTTPS
		if ( isset( $_POST['ftsmbp_use_https'] ) ) {
			// Make sure the site url is in https
			$site_url = "https://" . str_replace( [ "http://", "https://" ], '', get_config_value( 'ftsmbp_site_url' ) );
			update_config_value( 'ftsmbp_site_url', $site_url );
		} else {
			// Make sure the site url is in http
			$site_url = "http://" . str_replace( [ "http://", "https://" ], '', get_config_value( 'ftsmbp_site_url' ) );
			update_config_value( 'ftsmbp_site_url', $site_url );
		}

		// Pull the curent variables since we just updated the database
		load_config_values();

		unset( $_POST['submit'] );
	}

	// Get our module tabs	
	$extraTabs       = callModuleHook( '',
		'settingsPage',
		[
			'section' => 'tabs',
		] );
	$extraTabContent = callModuleHook( '',
		'settingsPage',
		[
			'section' => 'content',
		] );
	$extraTabJQuery  = callModuleHook( '',
		'settingsPage',
		[
			'section' => 'jquery',
		] );

	// Get our timezone stuff solved
	$timezone_format   = 'Y-m-d G:i:s ';
	$timeZoneHelpBlock = sprintf( __( '<abbr title="Coordinated Universal Time">UTC</abbr> time is <code>%s</code><br />' ), date_i18n( $timezone_format, false, 'gmt' ) );
	$tzstring          = get_config_value( 'ftsmbp_time_zone' );
	$check_zone_info   = true;

	if ( is_numeric( $tzstring ) ) {
		$check_zone_info = false;
		$tzstring        = formatTimeZoneString( $tzstring );
		update_config_value( 'ftsmbp_time_zone', $tzstring );
	}

	if ( $check_zone_info && $tzstring ) {
		$timeZoneHelpBlock .= sprintf( __( 'Local time is <code>%1$s</code><br />' ), date_i18n( $timezone_format ) );

		// Set TZ so localtime works.
		date_default_timezone_set( $tzstring );
		$now = localtime( time(), true );
		if ( $now['tm_isdst'] ) {
			$timeZoneHelpBlock .= __( 'This timezone is currently in daylight saving time.' );
		} else {
			$timeZoneHelpBlock .= __( 'This timezone is currently in standard time.' );
		}

		$timeZoneHelpBlock .= '<br />';

		$allowed_zones = timezone_identifiers_list();

		if ( in_array( $tzstring, $allowed_zones ) ) {
			$found                   = false;
			$date_time_zone_selected = new DateTimeZone( $tzstring );
			$tz_offset               = timezone_offset_get( $date_time_zone_selected, date_create() );
			$right_now               = time();
			foreach ( timezone_transitions_get( $date_time_zone_selected ) as $tr ) {
				if ( $tr['ts'] > $right_now ) {
					$found = true;
					break;
				}
			}

			if ( $found ) {
				echo ' ';
				$message = $tr['isdst'] ?
					__( 'Daylight saving time begins on: <code>%s</code>.<br />' ) :
					__( 'Standard time begins on: <code>%s</code>.<br />' );
				// Add the difference between the current offset and the new offset to ts to get the correct transition time from date_i18n().
				$timeZoneHelpBlock .= sprintf( $message, trim( date_i18n( $timezone_format, $tr['ts'] + ( $tz_offset - $tr['offset'] ) ) ) );
			} else {
				$timeZoneHelpBlock .= __( 'This timezone does not observe daylight saving time.' );
			}
		}
		// Set back to UTC.
		date_default_timezone_set( 'UTC' );
	}

	// Let modules alter our system settings tab fields	
	$formFields = apply_filters( 'form_fields_settings',
		[
			'ftsmbp_active'                         => [
				'text'          => 'Active',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			],
			'ftsmbp_inactive_msg'                   => [
				'text' => 'Inactive Message',
				'type' => 'textarea',
			],
			'ftsmbp_time_zone'                      => [
				'text'       => 'System Time Zone',
				'type'       => 'select',
				'options'    => getDropdownArray( 'timezone' ),
				'help_block' => $timeZoneHelpBlock,
			],
			'ftsmbp_system_email_address'           => [
				'text' => 'System Email Address',
				'type' => 'text',
			],
			'ftsmbp_site_name'                      => [
				'text' => 'Site Name',
				'type' => 'text',
			],
			'ftsmbp_logo'                           => [
				'text'            => 'Logo',
				'type'            => 'text',
				'addAppendButton' => true,
				'appendButton'    => '<button data-input-id="ftsmbp_logo" type="button" class="btn btn-success file-manager-linked">Upload New Image</button>',
			],
			'ftsmbp_icon'                           => [
				'text' => 'Favicon',
				'type' => 'text',
			],
			'ftsmbp_copyright'                      => [
				'text'       => 'Copyright Text',
				'type'       => 'text',
				'help_block' => 'You can use %current_year% to always keep this up to date.',
			],
			'ftsmbp_automatic_updates'              => [
				'text'          => 'Enable Automatic Updates?',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			],
			'ftsmbp_mod_rewrite'                    => [
				'text'          => 'SEO Friendly URLs',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			],
			'ftsmbp_use_https'                      => [
				'text'          => 'Use HTTPS',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			],
			'ftsmbp_enable_public_account_creation' => [
				'text'          => 'Anyone Can Create an Account',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			],
			'ftsmbp_require_account_activation'     => [
				'text'          => 'Require Account Activation',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			],
			'ftsmbp_enable_logging'                 => [
				'text'          => 'Enable Logging',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			],
			'ftsmbp_logging_prune'                  => [
				'text'    => 'Prune Logs Older Than',
				'type'    => 'select',
				'options' => getDropdownArray( 'pruning' ),
			],
			[
				'text' => 'Content Settings',
				'type' => 'separator',
			],
			'ftsmbp_content_dashboard'              => [
				'text'  => 'Dashboard Text',
				'type'  => 'textarea',
				'class' => 'tinymce',
			],
			'ftsmbp_analytics_code'                 => [
				'text' => 'Analytics Code',
				'type' => 'textarea',
			],
			[
				'text' => 'Email Alerts',
				'type' => 'separator',
			],
			'ftsmbp_enable_account_creation_alert'  => [
				'text'          => 'Send New Account Email to New Users',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			],
			'ftsmbp_enable_account_updated_alert'   => [
				'text'          => 'Send Account Updated Email to Users',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			],
			'ftsmbp_email_protocol'                 => [
				'text'    => 'Email Protocol',
				'type'    => 'select',
				'options' => [
					''         => '--Select One--',
					'built-in' => 'Built-in',
					'smtp'     => 'SMTP',
				],
			],
			'ftsmbp_email_server'                   => [
				'text' => 'Server',
				'type' => 'text',
			],
			'ftsmbp_email_username'                 => [
				'text' => 'Username',
				'type' => 'text',
			],
			'ftsmbp_email_password'                 => [
				'text' => 'Password',
				'type' => 'text',
			],
			'ftsmbp_email_port'                     => [
				'text' => 'Port',
				'type' => 'text',
			],
			'ftsmbp_email_ssl'                      => [
				'text'          => 'Is SSL?',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			],
			[
				'text' => 'Cron Jobs',
				'type' => 'separator',
			],
			'ftsmbp_cron_use_flood_control'         => [
				'text'          => 'Use Flood Timer',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			],
			'ftsmbp_cron_show_log'                  => [
				'text'          => 'Show Log During Run',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
			],
		] );

	// Give our template the values
	$page_content .= '
				<form action="' . il( $menuvar['SETTINGS'] ) . '" method="post" class="inputForm">
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicon glyphicon-cog"></i> ' . __( 'Settings' ) . '</h3>
							<div class="toolbar">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#systemSettings" data-toggle="tab"><span>' . __( 'System Settings' ) . '</span></a></li>
									<li><a href="#modules" data-toggle="tab"><span>' . __( 'Modules' ) . '</span></a></li>
									<li><a href="#appInfo" data-toggle="tab"><span>' . __( 'App Info' ) . '</span></a></li>
									' . $extraTabs . '
								</ul>
							</div>
						</div>
						<div class="tab-content form-horizontal">
							<div id="systemSettings" class="tab-pane active">
								' . makeFormFieldset( 'System Settings', $formFields, $mbp_config, 0 ) . '
							</div>
							<div id="modules" class="tab-pane">
								<h2>Modules</h2>
								<div id="modulesTableHolder">
									' . printModulesTable() . '
								</div>
							</div>
							<div id="appInfo" class="tab-pane">
								' . returnAppInfoBlock() . '
							</div>
							' . $extraTabContent . '
							<div class="clear center"><input type="submit" name="submit" class="btn btn-primary" value="Update Settings" /></div>
						</div>
					</div>
				</form>';

	$JQueryReadyScripts .= "
        var emailFields = ['ftsmbp_email_server', 'ftsmbp_email_username', 'ftsmbp_email_password', 'ftsmbp_email_port', 'ftsmbp_email_ssl'];

        if ($('#ftsmbp_email_protocol').val() != 'smtp') {
            $.each( emailFields, function( key, value ) {
                $('#' + value).closest('.form-group').hide();
            });
        }

	    $('#ftsmbp_email_protocol').on('change', function(){
	        if ($(this).val() == 'built-in') {
                $.each( emailFields, function( key, value ) {
					$('#' + value).closest('.form-group').hide();
                });
	        } else if($(this).val() == 'smtp') {
                $.each( emailFields, function( key, value ) {
					$('#' + value).closest('.form-group').show();
                });
	        }
	    });
	" . $extraTabJQuery;

	$page->setTemplateVar( 'PageContent', $page_content );
	$page->setTemplateVar( "JQueryReadyScript", $JQueryReadyScripts );
} else {
	$page->setTemplateVar( 'PageContent', notAuthorizedNotice() );
}