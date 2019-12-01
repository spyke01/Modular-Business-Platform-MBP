<?php 
/***************************************************************************
 *                               notifications.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/



if ($_SESSION['user_level'] == SYSTEM_ADMIN || $_SESSION['user_level'] == CLIENT_ADMIN) {

    $unreadNotificationsCount = getUnreadUserNotificationsCount($_SESSION['userid']);

    //==================================================
    // Print out our notifications table
    //==================================================
    $page_content .= '
                <div class="box tabbable">
                    <div class="box-header">
                        <h3><i class="fa fa-bullhorn"></i> ' . __('Notifications') . '</h3>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            ' . (($unreadNotificationsCount > 0) ? '<button type="button" class="btn btn-primary mark-all-read mb-20" data-href="' . SITE_URL . '/ajax.php?action=markAllUserNotificationsAsRead">Mark all as read</button>' : '') . '
                            ' . printUserNotificationsTable($_SESSION['userid']) . '
                        </div>
                    </div>
                </div>';

	$page->setTemplateVar('PageContent', $page_content);
} else {
	$page->setTemplateVar('PageContent', notAuthorizedNotice());
}