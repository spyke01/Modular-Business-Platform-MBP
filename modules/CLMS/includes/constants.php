<?php
/***************************************************************************
 *                               constants.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/



define( 'STATUS_INVOICE_AWAITING_PAYMENT', 0 );
define( 'STATUS_INVOICE_PAID', 1 );
define( 'STATUS_INVOICE_VOID', 2 );

define( 'STATUS_INVOICE_AWAITING_PAYMENT_STATUS_TXT', 'Awaiting Payment' );
define( 'STATUS_INVOICE_PAID_STATUS_TXT', 'Paid' );
define( 'STATUS_INVOICE_VOID_STATUS_TXT', 'Void' );

//============================
// Set the City, State, & Zip Text
//============================
if ( ! defined( 'TXT_CITY' ) ) {
	if ( $mbp_config['ftsmbp_clms_citystateziptext_type'] == 1 ) {
		// For England
		define( 'TXT_CITY', 'Town' );
		define( 'TXT_STATE', 'County' );
		define( 'TXT_ZIP', 'Zipcode' );
	} else {
		// For the US
		define( 'TXT_CITY', 'City' );
		define( 'TXT_STATE', 'State' );
		define( 'TXT_ZIP', 'Zip Code' );
	}
}