<?php
// Cycle through our AJAX calls and handle the content
if ( $actual_action == 'updateitem' && user_access( 'impersonation_updateitem' ) ) {
	if ( $section == 'before' ) {

	}
} elseif ( $actual_action == 'deleteitem' && user_access( 'impersonation_deleteitem' ) ) {
	if ( $section == 'before' ) {
	}
}