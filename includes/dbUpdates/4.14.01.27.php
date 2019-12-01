<?php

// Changes for 4.14.01.27
add_config_value( 'ftsmbp_enable_account_creation_alert', 1 );
add_config_value( 'ftsmbp_enable_account_updated_alert', 1 );

add_config_value(
	'ftsmbp_email_account_new',
	'<p>Your account on %site_title% has been created. Your login details are below:</p>
	<p>&nbsp;</p>
	<p><strong>Username:</strong> %username%</p>
	<p><strong>Password:</strong> %password%</p>
	<p>&nbsp;</p>
	<p>You can now log into your new account using <a href="http://tagsite_url">this link</a></p>'
);
add_config_value(
	'ftsmbp_email_account_update',
	'<p>Your account on %site_title% has been updated. Your login details are below:</p>
	<p>&nbsp;</p>
	<p><strong>Username:</strong> %username%</p>
	<p><strong>Password:</strong> %password%</p>
	<p>&nbsp;</p>
	<p>You can log into your account using <a href="http://tagsite_url">this link</a></p>'
);