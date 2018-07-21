<?php 
/***************************************************************************
 *                               email.php
 *                            -------------------
 *   begin                : Saturday, July 10, 2014
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=========================================================
// Returns a header for emails
//=========================================================
/**
 * @return string
 */
function returnEmailHeader() {
	global $mbp_config;
	
	return "<img src=\"" . returnHttpLinks($mbp_config['ftsss_store_url']) . "/images/logo.png\" alt=\"" . $mbp_config['ftsss_store_name'] . "\" /><br />
		Phone: " . $mbp_config['ftsss_phone_number'] . "<br />
		Fax: " . $mbp_config['ftsss_fax'] . "<br />
		Website: " . returnHttpLinks($mbp_config['ftsss_store_url']) . "<br /><br />";
}	

//=========================================================
// Sends an email message using the supplied values
//=========================================================
/***
 *
 * */
function emailMessage( $emailAddress, $subject, $message, $from = '' ) {
	global $mbp_config;

	$from = ( empty( $from ) ) ? $mbp_config['ftsmbp_system_email_address'] : $from;
    $mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
    
    // Limit the rate at which we are sending the same email
    if ( sentEmailRecently( $emailAddress, $subject, $message ) ) {
	    // We've sent this very recently so don't do it again
		
		// Log the failure
		/*
		// This is disabled since we would have immens log files if someone is spamming these messages
		addLogEvent( array(
			'type' => LOG_TYPE_EMAIL_SEND_FAIL,
			'message' => "Sent Email to '$emailAddress' Subject: '$subject' Response: 'Sent the same message within last 5 minutes'",
		) );
		*/
		return 0;    
    } else {
	    // Add it to the sent email list
	    logEmailMessage( $emailAddress, $subject, $message );
    }

    switch($mbp_config['ftsmbp_email_protocol']){
        case 'smtp':

            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->SMTPAuth = true; // enable SMTP authentication
            $mail->Host = $mbp_config['ftsmbp_email_server'] ?: '';
            $mail->Port = $mbp_config['ftsmbp_email_port'] ?: '';
            $mail->Username = $mbp_config['ftsmbp_email_username'] ?: '';
            $mail->Password = $mbp_config['ftsmbp_email_password'] ?: '';
            
            if ( $mbp_config['ftsmbp_email_ssl'] )
            	$mail->SMTPSecure = "ssl";            
            
            break;

        case 'built-in':
        default:
            break;
    }

    //Typical mail data
    try {
	    $mail->SetFrom($from);
	    
	    // Handle multiple email addresses
	    $emailAddress = str_replace( array( ',', ';' ), ';', $emailAddress );
	    $emailAddresses = explode( ';', $emailAddress );
	    
	    foreach ( $emailAddresses as $emailAddress ) {
	    	$mail->AddAddress($emailAddress);
	    }
	
	    $mail->Subject = $subject;
	    $mail->Body = $message;
	    $mail->isHTML(true);
	    $mail->Send();
    
		// Log it
		addLogEvent( array(
			'type' => LOG_TYPE_EMAIL_SEND_SUCCESS,
			'message' => "Sent Email to '$emailAddress' Subject: '$subject' Response: 'Success'",
		) );
		return 1;
	} catch (phpmailerException $e) {
		//Pretty error messages from PHPMailer
		
		// Log the failure
		addLogEvent( array(
			'type' => LOG_TYPE_EMAIL_SEND_FAIL,
			'message' => "Sent Email to '$emailAddress' Subject: '$subject' Response: '" . $e->getMessage() . "'",
		) );
		return 0;
	} catch (Exception $e) {
		//Boring error messages from anything else!
		
		// Log the failure
		addLogEvent( array(
			'type' => LOG_TYPE_EMAIL_SEND_FAIL,
			'message' => "Sent Email to '$emailAddress' Subject: '$subject' Response: '" . $e->getMessage() . "'",
		) );
		return 0;
	}
}

/**
 * logEmailMessage function.
 * Log that an email message was sent out
 *
 * @param string $emailAddress
 * @param string $subject
 * @param string $message
 * @return mixed
 */
function logEmailMessage( $emailAddress, $subject, $message ) {
	global $ftsdb;
	
	$result = $ftsdb->insert(DBTABLEPREFIX . 'email_logs', array(
		"sent" => mysqldatetime(),
		"email_address" => $emailAddress,
		"subject" => $subject,
		"message" => $message,
	));
	
	return $result;		
}

/**
 * sentEmailRecently function.
 * Checks if an email was sent in the last 5 minutes
 * NOTE: We aren't checking the message just the email and subject
 *@param $message
 * @param $subject
 * @param $emailAddress
 * @param string $subject
 * @param string $message
 * @return boolean
 */
function sentEmailRecently( $emailAddress, $subject, $message ) {
	global $ftsdb; 
	
	$exists = false;
	$results = $ftsdb->select( DBTABLEPREFIX . "email_logs", 'sent >= :sent AND email_address = :email_address AND subject = :subject', array(
		":sent" => mysqldatetime( strtotime( '-5 minutes' ) ),
		":email_address" => $emailAddress,
		":subject" => $subject,
	) );
	if ( $results && count( $results ) > 0 ) { $exists = true; }
	$results = NULL;	
	
	return $exists;
}

//==================================================
// Returns an array of the email template data
//==================================================
/**
 * @param $template_id
 * @return array|void
 */
function getEmailTemplate($template_id ) {
	if ( is_numeric( $template_id ) ) {
		// id column
		return getDatabaseArray( 'email_templates', $template_id );
	} else {
		// template_id column
		return getDatabaseArray( 'email_templates', $template_id, '', 'template_id' );
	}
}

//=========================================================
// Gets an email template's subject from a templateID or template_id
//=========================================================
function getEmailTemplateSubjectFromID( $template_id ) {		
	if ( is_numeric( $template_id ) ) {
		// id column
		return getDatabaseItem( 'email_templates', 'subject', $template_id );
	} else {
		// template_id column
		return getDatabaseItem( 'email_templates', 'subject', $template_id, '', 'template_id' );
	}
}

//=========================================================
// Gets an email template's message from a templateID or template_id
//=========================================================
function getEmailTemplateMessageFromID( $template_id ) {	
	if ( is_numeric( $template_id ) ) {
		// id column
		return getDatabaseItem( 'email_templates', 'message', $template_id );
	} else {
		// template_id column
		return getDatabaseItem( 'email_templates', 'message', $template_id, '', 'template_id' );
	}
}

//=================================================
// Add an email template to the DB
//=================================================
function addEmailTemplate( $template_id = '', $name = '', $subject = '', $message = '', $added_by = '', $prefix = '' ) {
	global $ftsdb;
	
	$result = $ftsdb->insert(DBTABLEPREFIX . 'email_templates', array(
		"template_id" => $template_id,
		"name" => $name,
		"subject" => $subject,
		"message" => $message,
		"added_by" => $added_by,
		"prefix" => $prefix,
	));
	
	return $result;		
}

//==================================================
// Checks if an email template is in the database
//==================================================
function emailTemplateExists( $template_id ) {
	global $ftsdb; 
	
	$exists = 0;
	$results = $ftsdb->select( DBTABLEPREFIX . "email_templates", 'template_id = :template_id', array(
		":template_id" => $template_id,
	) );
	if ( $results && count( $results ) > 0 ) { $exists = 1; }
	$results = NULL;	
	
	return $exists;
}

/**
 * Parse an email template for tags and then send it.
 * 
 * @access public
 * @param mixed $template_id If numeric its the DB id, if a string then its the template_id field
 * @param array $users An array of the users to send the email template to
 * @return void
 */
function parseAndSendTemplateExists( $template_id, $users ) {
	global $ftsdb; 
	$errors = array();
	$debug = 0;
	
	if ( is_numeric( $template_id ) ) {
		// id column
		$templateData = getDatabaseArray( 'email_templates', $template_id );
	} else {
		// template_id column
		$templateData = getDatabaseArray( 'email_templates', $template_id, '', 'template_id' );
	}
	
	$tags = getBuiltinTagsArray();	
	
	/**
	 * Filter the tags.
	 *
	 * @since 4.16.03.09
	 *
	 * @param string $tags 		 Our current tags array.
	 */
	$tags = apply_filters( 'parseAndSendTemplateExists_tags', $tags );
	
	
	if ( $debug ) { print_r( $users ); }
	foreach ( (array)$users as $user ) {		
		if ( $user == 'all' ) {
			// All users
			if ( $debug ) { echo "Emailing all users - <br />"; }
			$results = $ftsdb->select( USERSDBTABLEPREFIX . "users", '1' );
			if ( $results && count( $results ) > 0 ) {
				foreach ( $results as $row ) {
					$userData = prefixArray( $row );
					
					// Send the email
					$emailAddress = $row['email_address'];
					$subject = parseForTagsFromArray( $templateData['subject'], array_merge( $tags, $userData ), 0 );
					$message = parseForTagsFromArray( $templateData['message'], array_merge( $tags, $userData ), 0 );
					
					if ( $debug ) {
						echo "Emailing $emailAddress<br />";
					} elseif ( !emailMessage( $emailAddress, $subject, $message ) ) {
						$errors[] = "Failed to send email to $emailAddress .";
					}
				}
			}
			$results = NULL;
			
			// If we just emailed everyone then don't continue
			break;
		} elseif ( substr( $user, 0, 2 ) == 'ul' ) {
			// User Level
			if ( $debug ) { echo "Emailing user level - $user<br />"; }
			$results = $ftsdb->select( USERSDBTABLEPREFIX . "users", 'user_level = :user_level', array(
				":user_level" => ltrim( $user, 'ul_' ),
			)  );
			if ( $results && count( $results ) > 0 ) {
				foreach ( $results as $row ) {
					$userData = prefixArray( $row, 'user_' );
					
					// Send the email
					$emailAddress = $row['email_address'];
					$subject = parseForTagsFromArray( $templateData['subject'], array_merge( $tags, $userData ), 0 );
					$message = parseForTagsFromArray( $templateData['message'], array_merge( $tags, $userData ), 0 );
					
					if ( $debug ) {
						echo "Emailing $emailAddress<br />";
					} elseif ( !emailMessage( $emailAddress, $subject, $message ) ) {
						$errors[] = "Failed to send email to $emailAddress .";
					}
				}
			}
			$results = NULL;
		} elseif ( substr( $user, 0, 2 ) == 'u_' ) {
			// User
			if ( $debug ) { echo "Emailing user - $user<br />"; }
			$results = $ftsdb->select( USERSDBTABLEPREFIX . "users", 'id = :id LIMIT 1', array(
				":id" => ltrim( $user, 'u_' ),
			)  );
			if ( $results && count( $results ) > 0 ) {
				foreach ( $results as $row ) {
					$userData = prefixArray( $row, 'user_' );
					
					// Send the email
					$emailAddress = $row['email_address'];
					$subject = parseForTagsFromArray( $templateData['subject'], array_merge( $tags, $userData ), 0 );
					$message = parseForTagsFromArray( $templateData['message'], array_merge( $tags, $userData ), 0 );
					
					if ( $debug ) {
						echo "Emailing $emailAddress<br />";
					} elseif ( !emailMessage( $emailAddress, $subject, $message ) ) {
						$errors[] = "Failed to send email to $emailAddress .";
					}
				}
			}
			$results = NULL;
		} else {
			// This is a module's custom user level so let them handle it
			$errors = array_merge( $errors, callModuleHook('', 'handleParseAndSendEmailTemplate', array(
				'user' => $user,
				'templateData' => $templateData,
			), 1, 'array' ) );
		}
	}
	
	return $errors;
}

/**
 * Shows all available tags for email templates.
 * 
 * @access public
 * @return void
 */
function displayEmailTemplateTags() {
	$availableTags = getBuiltinTagsArray() + getDatabaseArray( 'users', $_SESSION['userid'], "user_" ); // getBuiltinTagsArray is included already but we want it at the front
	unset( $availableTags['user_password'] ); // Don't include the users encrypted password for security reasons
	$availableTags = apply_filters( 'available_email_template_tags', $availableTags );
	$emailTempalateTags = '';
	
	foreach ( $availableTags as $tag => $value ) {
		$emailTempalateTags .= '
			<dt>%' . strtolower( $tag ) . '%</dt>
			<dd><em>ex.</em> ' . $value . '</dd>';
	}
	
	return '<dl class="dl-horizontal">' . $emailTempalateTags . '</dl>';
}


//=================================================
// Print the Email Templates Table
//=================================================
function printEmailTemplatesTable() {
	global $ftsdb, $menuvar, $mbp_config, $tableColumns;
	
	$result = $ftsdb->select(DBTABLEPREFIX . "email_templates", $extraSQL . "1 ORDER BY name DESC");
	
	// Prep our table columns
	$columns = apply_filters( 'table_email_templates_columns', $tableColumns['table_email_templates'] );
	$numOfColumns = count( $columns );
	
	// Create our new table
	$table = new tableClass('', '', '', "table table-striped table-bordered tablesorter", "emailTemplatesTable");
	
	// Create table title
	$table->addNewRow( array( array( 'data' => "Current Email Templates (" . count($result) . ")", "colspan" => $numOfColumns ) ), '', 'title1', 'thead');
	
	// Create column headers	
	$table->addNewRow( $table->generateTableColumns( $columns ), '', 'title2', 'thead' );
						
	// Add our data
	if ( !$result ) {
		$table->addNewRow( array( array( 'data' => "There are no email templates in the system.", "colspan" => $numOfColumns ) ), "emailTemplatesTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {	
			$rowData = array();
			
			foreach ( $columns as $column_name => $column_display_name ) {
				switch ( $column_name ) {
					case 'name':
						$rowData[] = array( 'data' => $row['name'] );
						break;
					case 'subject':
						$rowData[] = array( 'data' => $row['subject'] );
						break;
					case 'added_by':
						$rowData[] = array( 'data' => $row['added_by'] );
						break;
					case 'final':
						$finalCol  = (user_access('email_templates_edit')) ? '<a href="' . il( $menuvar['EMAILUSERS'] . '&action=editEmailTemplate&id=' . $row['id'] ) . '" class="btn btn-default"><i class="glyphicon glyphicon-edit"></i></a> ' : '';
						if ( user_access('email_templates_delete') && $row['added_by'] != 'System' && empty( $row['prefix'] ) ) {
							$finalCol .= createDeleteLinkWithImage($row['id'], $row['id'] . "_row", "email_templates", "email template");	
						}		
						
						$rowData[] = array( 
							'data' => '<span class="btn-group">' . $finalCol . '</span>', 
							'class' => 'center'
						);
						break;
					default:
						$rowData[] = apply_filters( 'table_email_templates_custom_column', '', $column_name, $row['id'] );
				}
			}			
			$table->addNewRow( $rowData, $row['id'] . '_row', '' );
		}
		$result = NULL;
	}
	
	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"emailTemplatesTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to run the 
// email templates table
//=================================================
function returnEmailTemplatesTableJQuery() {							
	$JQueryReadyScripts = "
			$('#emailTemplatesTable').tablesorter({ widgets: ['zebra'], headers: { 5: { sorter: false } } });";
	
	return $JQueryReadyScripts;
}

//=================================================
// Create a form to add new email templates
//=================================================
function printNewEmailTemplateForm() {
	global $menuvar, $mbp_config;
	
	$formFields = apply_filters( 'form_fields_email_templates_new', array(
		'name' => array(
			'text' => 'Name (Internal Use)',
			'type' => 'text',
			'class' => 'required',
		),
		'subject' => array(
			'text' => 'Subject',
			'type' => 'text',
			'class' => 'required',
		),
		'message' => array(
			'text' => 'Message',
			'type' => 'textarea',
			'class' => 'tinymce required',
		),
	));
	
	return makeForm('newEmailTemplate', il( $menuvar['EMAILUSERS'] ), 'New Email Template', 'Create Email Template', $formFields, array(), 1);
}

//=================================================
// Returns the JQuery functions used to run the 
// new email template form
//=================================================
function returnNewEmailTemplateFormJQuery($reprintTable = 0, $allowModification = 1) {		
	$table = ($reprintTable == 0) ? '' : 'emailTemplatesTable';
	return makeFormJQuery('newEmailTemplate', SITE_URL . "/ajax.php?action=createEmailTemplate&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification, $table, 'email template');
}

//=================================================
// Create a form to edit email templates
//=================================================
function printEditEmailTemplateForm($templateID) {
	global $ftsdb, $menuvar, $mbp_config;
	
	$result = $ftsdb->select(DBTABLEPREFIX . "email_templates", "id = :id LIMIT 1", array(
		":id" => $templateID
	));
	
	if ($result && count($result) == 0) {
		$page_content = "<span class=\"center\">There was an error while accessing the email template's details you are trying to update. You are being redirected to the main page.</span>
						<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['EMAILUSERS'] . "\">";	
	} else {
		$row = $result[0];
	
		$formFields = apply_filters( 'form_fields_email_templates_edit', array(
			'name' => array(
				'text' => 'Name (Internal Use)',
				'type' => 'text',
				'class' => 'required',
			),
			'template_id' => array(
				'text' => 'Template ID (Internal Use)',
				'type' => 'readonly',
			),
			'subject' => array(
				'text' => 'Subject',
				'type' => 'text',
				'class' => 'required',
			),
			'message' => array(
				'text' => 'Message',
				'type' => 'textarea',
				'class' => 'tinymce required',
			),
		));
		
		$content = makeForm('editEmailTemplate', il( $menuvar['EMAILUSERS'] ), '<i class="glyphicon glyphicon-email template"></i> Edit Email Template', 'Update Email Template', $formFields, $row, 1);
		
		$result = NULL;
	}
		
	return $content;
}

//=================================================
// Returns the JQuery functions used to run the 
// edit email template form
//=================================================
function returnEditEmailTemplateFormJQuery($templateID) {		
	return makeFormJQuery('editEmailTemplate', SITE_URL . "/ajax.php?action=editEmailTemplate&id=" . $templateID);
}

//=================================================
// Create a form to send an email template
//=================================================
function printSendEmailTemplateForm() {
	global $menuvar, $mbp_config;
	
	$formFields = apply_filters( 'form_fields_email_templates_send', array(
		'template_id' => array(
			'text' => 'Template',
			'type' => 'select',
			'options' => getDropdownArray('email_templates'),
			'class' => 'required',
		),
		'users[]' => array(
			'text' => 'Users',
			'type' => 'select',
			'multiple' => 'true',
			'options' => getDropdownArray( 'email_users', 0 ),
			'class' => 'select2 required',
			'default' => 'all',
		),
	));
	
	return makeForm('sendEmailTemplate', il( $menuvar['EMAILUSERS'] ), 'Send Email to User(s)', 'Send Email', $formFields, array(), 1);
}

//=================================================
// Returns the JQuery functions used to run the 
// send email template form
//=================================================
function returnSendEmailTemplateFormJQuery() {		
	return makeFormJQuery('sendEmailTemplate', SITE_URL . "/ajax.php?action=sendEmailTemplate", '', 'email template');
}