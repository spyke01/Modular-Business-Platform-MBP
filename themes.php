<?php 
/***************************************************************************
 *                               themes.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/



if (user_access('themes_access')) {
	//==================================================
	// Handle changing themes
	//==================================================	
	if ($actual_action == 'change_theme' && isset($_GET['theme'])) {
		$result = update_config_value( 'ftsmbp_theme', $_GET['theme'] );
		$page->setTemplateVar('Theme', $mbp_config['ftsmbp_theme']);
		
		if ($result) {
			$page_content .= "
				<div class=\"box\">
					<div class=\"box-header\">
						<h3>Theme Changed</h3>
					</div>
					<div class=\"box-content bold greenText\">
						<p>Your theme has been successfully changed. You are now being redirected back to the Themes page.</p>
						<meta http-equiv=\"refresh\" content=\"5;url=" . il( $menuvar['THEMES'] ) . "\">
					</div>
				</div>";	
		} else {
			$page_content .= "
				<div class=\"box\">
					<div class=\"box-header\">
						<h3>Error</h3>
					</div>
					<div class=\"box-content bold redText\">
						<p>There was an error while attempting to change your theme. You are now being redirected back to the Themes page.</p>
						<meta http-equiv=\"refresh\" content=\"5;url=" . il( $menuvar['THEMES'] ) . "\">
					</div>
				</div>";
		}		
	}		
	//==================================================
	// Handle updating theme settings
	//==================================================	
	elseif ($actual_action == 'update_theme_settings' && isset($_POST['submit'])) {
		$theme = keepsafe($_GET['theme']);
		$folder = (!empty($_GET['folder'])) ? keepsafe($_GET['folder']) : '';
		
		$namePrefix = str_replace('/', '_', $folder);	
		$themeFunctionsFile = BASEPATH . '/themes/' . $folder  . $theme . '/functions.php';
	
		if (is_file($themeFunctionsFile)) {
			include_once($themeFunctionsFile);
			$themeConfigArrayName = 'theme_config_' . $theme;
			
			foreach (${$themeConfigArrayName}['settings'] as $name => $settingInfo) {
				// Make our name match the db
				$dbFieldName = 'ftsmbp_theme_' . $namePrefix . $theme . '_settings_' . $name;
				$value = (isset($_POST[$dbFieldName])) ? $_POST[$dbFieldName] : 0;
				
				add_config_value($dbFieldName, $value);
			}
		}
		
		
		$page_content .= "
				<div class=\"box\">
					<div class=\"box-header\">
						<h3>Settings Saved</h3>
					</div>
					<div class=\"box-content bold greenText\">
						<p>Your settings for the $theme theme have been successfully saved. You are now being redirected back to the Themes page.</p>
						<meta http-equiv=\"refresh\" content=\"5;url=" . il( $menuvar['THEMES'] ) . "\">
					</div>
				</div>";
	} else {
		//==================================================
		// Print our table
		//==================================================
		$extraTabs = $extraTabContent = '';
	
		// Get our module tabs	
		$extraTabs = callModuleHook('', 'themesPage', array(
			'section' => 'tabs'
		));	
		$extraTabContent = callModuleHook('', 'themesPage', array(
			'section' => 'content'
		));	
		
		$page_content .= '			
				<div class="box tabbable">
					<div class="box-header">
						<h3><i class="glyphicon glyphicon-tint"></i> ' . __('Themes') . '</h3>
						<div class="toolbar">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#availableThemes" data-toggle="tab"><span>' . __('Available Themes') . '</span></a></li>
								<li><a href="#' . $mbp_config['ftsmbp_theme'] . 'Settings" data-toggle="tab"><span>' . __('Theme Options') . ' - ' . $mbp_config['ftsmbp_theme'] . '</span></a></li>
								' . $extraTabs . '
							</ul>
						</div>
					</div>
					<div class="tab-content">
						<div id="availableThemes" class="tab-pane active">
							<h2>' . __('Available Themes') . '</h2>
							' . printThemesTable() . '
						</div>
						<div id="' . $mbp_config['ftsmbp_theme'] . 'Settings" class="tab-pane">
							' . printThemeSettingsTable($mbp_config['ftsmbp_theme']) . '
						</div>
						' . $extraTabContent . '
					</form>
				</div>
			</div>';
	}
	$page->setTemplateVar('PageContent', $page_content);
	//$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);
} else {
	$page->setTemplateVar('PageContent', notAuthorizedNotice());
}