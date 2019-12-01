<?php
/***************************************************************************
 *                               widgets.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/

if ( user_access( 'widgets_access' ) ) {
	$page_content .= '
		<div class="row">
			<div class="col-sm-8">
				<div id="available-widgets" class="box widgets-holder-wrap ui-droppable">
					<div class="box-header">
						<div class="sidebar-name-arrow">&nbsp;</div>
						<h3><i class="glyphicons glyphicons-cogwheels"></i> ' . __( 'Available Widgets' ) . '</h3>
					</div>
					<div class="box-content widget-holder">
						<div id="widget-list">
							' . listAvailableWidgets() . '						
						</div>
					</div>
				</div>
			</div>
			<div id="widgets-rightCol" class="col-sm-4">
				' . listWidgetAreas() . '
			</div>
		</div>';

	// Handle our JQuery needs
	$JQueryReadyScripts = returnWidgetsTableJQuery();

	$page->setTemplateVar( 'PageContent', $page_content );
	$page->setTemplateVar( "JQueryReadyScript", $JQueryReadyScripts );
} else {
	$page->setTemplateVar( 'PageContent', notAuthorizedNotice() );
}