<?php 
/***************************************************************************
 *                               notifications.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


 

/**
 * Returns the translated and filtered text for a notification type.
 * 
 * @access public
 * @param mixed $type
 * @return void
 */
function returnUserNotificationTypeText( $type ) {
	global $NOTIFICATION_TYPES;
	
	return apply_filters( 'returnUserNotificationTypeText', __( $NOTIFICATION_TYPES[$type] ), $type );
}
 
/**
 * Adds a notification in the database.
 * 
 * @param mixed $dataArray		The notification data
 * @return int					The id of the notification
 */
function addUserNotification( $dataArray ) {
	global $ftsdb;
	
	// Make sure we have a created date and time
	if ( !isset( $dataArray['created'] ) ) {
		$dataArray['created'] = mysqldatetime();
	}
	
	$result = $ftsdb->insert( DBTABLEPREFIX . 'notifications', $dataArray );
	
	return $ftsdb->lastInsertId();
}

/**
 * Deletes a notification from the database.
 * 
 * @param mixed $id				The ID of the notification
 * @return void
 */
function deleteUserNotification( $id ) {
	global $ftsdb; 
	
	$result = $ftsdb->delete( DBTABLEPREFIX . 'notifications', "id = :id", array(
		":id" => $id
	) );
}

/**
 * Returns user notifications.
 *
 * @param mixed $userID			The ID of the user
 * @param string $type			The type of select query
 * @return array				The notifications data
 */
function getUserNotifications( $userID, $type = 'all' ) {
    global $ftsdb;

    switch($type){
        case 'unread':
            $where = 'user_id = :user_id AND `read` = 0';
            break;

        case 'all':
        default:
            $where = 'user_id = :user_id';
            break;
    }

    $results = $ftsdb->select( DBTABLEPREFIX . "notifications", $where . " ORDER BY created DESC", array(
        ":user_id" => $userID,
    ) );

    return $results;
}

/**
 * Returns user notifications count.
 *
 * @param mixed $userID			The ID of the user
 * @return array				The count of unread notifications
 */
function getUnreadUserNotificationsCount( $userID ) {
    global $ftsdb;

    $results = $ftsdb->select( DBTABLEPREFIX . "notifications", "user_id = :user_id AND `read` = 0", array(
        ":user_id" => $userID
    ), 'COUNT(id) as totalCount');

    $numRows = ($results) ? $results[0]['totalCount'] : 0;

    return $numRows;
}

/**
 * Returns the value of a notification in the database.
 * 
 * @param mixed $id				The ID of the notification
 * @return array				The notification data
 */
function getUserNotification( $id ) {
    global $ftsdb;
	$data = array();
	
	$results = $ftsdb->select( DBTABLEPREFIX . "notifications", "id = :id", array(
		":id" => $id,
	) );
	if ( count( $results ) == 0 ) {
		$data = $results[0];
	}
	$results = NULL;
			
	return $data;
}

/**
 * Updates a notification in the database.
 * 
 * @param mixed $id				The ID of the notification
 * @param mixed $dataArray		The notification data
 * @return mixed				The result from the db call execution
 */
function updateUserNotification( $id, $dataArray ) {
	global $ftsdb;
	
	$result = $ftsdb->update( DBTABLEPREFIX . 'notifications', $dataArray, "id = :id", array(
			":id" => $id
		)
	);
	
	return $result;
}

/**
 * Mark all notifications as read.
 *
 * @param mixed $userID			User id
 * @return mixed				The result from the db call execution
 */
function markAllUserNotificationsAsRead( $userID ) {
    global $ftsdb;

    $result = $ftsdb->update( DBTABLEPREFIX . 'notifications', array('read' => 1), "user_id = :user_id", array(
        ":user_id" => $userID
    ));

    return $result;
}


/**
 * Mark notification as read.
 *
 * @param mixed $userID			    User id
 * @param mixed $notificationID		Notification id
 * @return mixed				    The result from the db call execution
 */
function markUserNotificationAsRead( $userID, $notificationID ) {
    global $ftsdb;

    $result = $ftsdb->update( DBTABLEPREFIX . 'notifications', array('read' => 1), "id = :id AND user_id = :user_id", array(
        ":user_id" => $userID,
        ":id" => $notificationID,
    ));

    return $result;
}

/**
 * pruneUserNotifications function.
 * 
 * Cleans up the notifications database to minimize clutter and unneeded usage
 *
 * @return void
 */
function pruneUserNotifications() {
	global $ftsdb, $mbp_config; 
	
	if ( $mbp_config['ftsmbp_notification_prune'] == 0 )
		return; 

	$result = $ftsdb->delete( DBTABLEPREFIX . 'notifications', "created <= DATE_SUB( CURDATE( ) , INTERVAL :months MONTH )", array(
		":months" => $mbp_config['ftsmbp_notification_prune']
	) );	
}

//=================================================
// Print the Notifications Table
//=================================================
function printUserNotificationsTable($userID) {
    global $ftsdb;

    // Create our new table
    $table = new tableClass('', '', '', "table table-striped table-bordered tablesorter", "notificationsTable");

    // Create table title
    $table->addNewRow(array(array('data' => 'Unread Notifications: <span class="notifications-count">' . getUnreadUserNotificationsCount($userID) . '</span>', 'colspan' => '8')), '', 'title1', 'thead');

    // Create column headers
    $table->addNewRow(
        array(
            array('type' => 'th', 'data' => 'Type'),
            array('type' => 'th', 'data' => 'Message'),
            array('type' => 'th', 'data' => 'Date'),
            array('type' => 'th', 'data' => 'Link'),
            array('type' => 'th', 'data' => 'Mark as read')
        ), '', 'title2', 'thead'
    );

    $results = getUserNotifications($userID);

    // Add our data
    if (!$results) {
        $table->addNewRow(array(array('data' => "You currently have no notifications.", "colspan" => "8")), "notificationsTableDefaultRow", "greenRow");
    } else {
        foreach ($results as $row) {

            $default = returnUserNotificationTypeText($row['type']);

            $notifArr = array(
                'title' => isset($default['title']) ? strtoupper($default['title']) : '',
                'icon' => !empty($row['icon']) ? $row['icon'] : $default['icon'],
                'textClass' => isset($default['colorClass']) ? 'text-' . $default['colorClass'] : '',
                'bgClass' => isset($default['colorClass']) ? 'bg-' . $default['colorClass'] : '',
                'message' => !empty($row['message']) ? $row['message'] : '',
                'created' => !empty($row['created']) ? $row['created'] : '',
                'link' => !empty($row['link']) ? '<a href="' .  $row['link'] . '">View</a>' : '',
                'readClass' => ($row['read']) ? 'read' : 'unread',
                'button' => (!$row['read']) ? '<a class="btn btn-default mark-read" href="' . SITE_URL . '/ajax.php?action=markUserNotificationAsRead&id=' . $row['id'] . '"><i class="glyphicons glyphicons-ok"></i></a>' : '',
            );

            // Build our final column array
            $rowDataArray = array(
                array( 'data' => '<div class="notification-icon ' . $notifArr['icon'] . ' ' . $notifArr['bgClass'] . '"></div><div class="notification-title ' . $notifArr['textClass'] . '">' . $notifArr['title'] . '</div>' ),
                array( 'data' => $row['message'] ),
                array( 'data' => $notifArr['created'] ),
                array( 'data' => $notifArr['link'], 'class' => 'center' ),
                array( 'data' => '<span class="btn-group">' . $notifArr['button'] . '</span>'),
            );

            $table->addNewRow($rowDataArray, $row['id'] . "_row", $notifArr['readClass']);
        }
        $results = NULL;
    }

    // Return the table's HTML
    return $table->returnTableHTML() . "
			<div id=\"notificationsTableUpdateNotice\"></div>";
}




