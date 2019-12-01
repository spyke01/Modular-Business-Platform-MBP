<?php
/***************************************************************************
 *                               functions.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


/*==================================================
 Due to the number of functions we use in the system we have seperated 
 these into seperate files to make it easier to manage and add functions to the system
==================================================*/
include( BASEPATH . '/includes/functions/categories.php' );
include( BASEPATH . '/includes/functions/config.php' );
include( BASEPATH . '/includes/functions/email.php' );
include( BASEPATH . '/includes/functions/filters.php' );
include( BASEPATH . '/includes/functions/forms.php' );
include( BASEPATH . '/includes/functions/general.php' );
include( BASEPATH . '/includes/functions/graphs.php' );
include( BASEPATH . '/includes/functions/helpers.php' );
include( BASEPATH . '/includes/functions/http.php' );
include( BASEPATH . '/includes/functions/kses.php' );
include( BASEPATH . '/includes/functions/language.php' );
include( BASEPATH . '/includes/functions/link-template.php' );
include( BASEPATH . '/includes/functions/logging.php' );
include( BASEPATH . '/includes/functions/notifications.php' );
include( BASEPATH . '/includes/functions/menus.php' );
include( BASEPATH . '/includes/functions/modules.php' );
include( BASEPATH . '/includes/functions/permissions.php' );
include( BASEPATH . '/includes/functions/reports.php' );
include( BASEPATH . '/includes/functions/sanitization.php' );
include( BASEPATH . '/includes/functions/tables.php' );
include( BASEPATH . '/includes/functions/themes.php' );
include( BASEPATH . '/includes/functions/tours.php' );
include( BASEPATH . '/includes/functions/updates.php' );
include( BASEPATH . '/includes/functions/users.php' );
include( BASEPATH . '/includes/functions/widgets.php' );
include( BASEPATH . '/includes/functions/default-actions.php' ); // Utilizes calls inside filters.php
include( BASEPATH . '/includes/functions/default-widgets.php' ); // Utilizes calls inside widgets.php