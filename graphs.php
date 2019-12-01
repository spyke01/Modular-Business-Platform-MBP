<?php 
/***************************************************************************
 *                               graphs.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/

if (user_access('graphs_access')) {
	//==================================================
	// Print out our graphs table
	//==================================================	
	// Get our module item	
	$extraGraphLinks = callModuleHook('', 'graphsPage', array(
		'section' => 'links'
	));	
	$extraGraphJQuery = callModuleHook('', 'graphsPage', array(
		'section' => 'jQuery'
	));	
	
	$page_content .= '
		<div class="box tabbable">
			<div class="box-header">
				<h3><i class="glyphicons glyphicons-stats"></i> ' . __('Graphs') . '</h3>
				<div class="toolbar">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#builtinGraphs" data-toggle="tab"><span>' . __('Built-in Graphs') . '</span></a></li>
					</ul>
				</div>
			</div>
			<div class="tab-content">
				<div id="builtinGraphs" class="tab-pane active">
					<ul id="graphLinks">
						' . $extraGraphLinks . '
					</ul>
					<div id="builtinGraphResponse"></div>
					<h3 id="graphTitle"></h3>
					<canvas id="graphsChart" width="400" height="400"></canvas>
				</div>
			</div>
		</div>';
			
	// Handle our JQuery needs
	$JQueryReadyScripts = $extraGraphJQuery . returnNewGraphFormJQuery(1);
	
	$page->setTemplateVar('PageContent', $page_content);
	$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);
} else {
	$page->setTemplateVar('PageContent', notAuthorizedNotice());
}