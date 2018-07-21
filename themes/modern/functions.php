<?php
$theme_folder_modern = "modern";

// Available Backgrounds
$backgroundOptionsArray = array(
	'transparent' => 'Transparent',
	'carbonFiber' => 'Carbon Fiber',
	'chalkboard' => 'Chalkboard',
	'denimBlack' => 'Denim (Black)',
	'denimBlue' => 'Denim (Blue)',
	'diamonds' => 'Diamonds',
	'diceWhite' => 'Dice (White)',
	'diceWhiteSmall' => 'Dice (White) (Small)',
	'dirtBlack' => 'Dirt (Black)',
	'dirtGrey' => 'Dirt (Grey)',
	'dirtLightGrey' => 'Dirt (Light Grey)',
	'hashtag' => 'Hash Tag',
	'interlockingBlocks' => 'Interlocking Blocks',
	'leatherBlack' => 'Leather (Black)',
	'leatherBlackDot' => 'Leather (Black Dot)',
	'leatherWhite' => 'Leather (White)',
	'leftDiagBlack' => 'Left Diagonal (Black)',
	'leftDiagWhite' => 'Left Diagonal (White)',
	'leftDiagWhiteNoise' => 'Left Diagonal (White Noise)',
	'LinenBlack' => 'Linen (Black)',
	'LinenBlack2' => 'Linen (Black #2)',
	'randomGreyVariations' => 'Random Grey Variations',
	'rightDiagOrange' => 'Right Diagonal (Orange)',
	'slate' => 'Slate',
	'verticalStripesGrey' => 'Vertical Stripes (Grey)',
	'woodBlack' => 'Wood (Black)',
	'woodCherry' => 'Wood (Cherry)',
	'woodGrey' => 'Wood (Grey)',
	'x' => 'X (Transparent)',
	'xGreyBlue' => 'X (Grey Blue)',
	'xRed' => 'X (Red)',
);

// Available Color Schemes
$colorSchemesArray = array(
	'Web 2.0 Colors' => array(
		'default' => 'Default',
		'black' => 'Black',
		'blue' => 'Blue',
		'green' => 'Green',
		//'grey' => 'Grey',
		'orange' => 'Orange',
		'pink' => 'Pink',
		'red' => 'Red',
		'yellow' => 'Yellow',
	),
	'Flat Colors' => array(
		'alizarin' => 'Alizarin',
		'amethyst' => 'Amethyst',
		'asbestos' => 'Asbestos',
		'belize-hole' => 'Belize Hole',
		'carrot' => 'Carrot',
		'clouds' => 'Clouds',
		'concrete' => 'Concrete',
		'emerald' => 'Emerald',
		'green-sea' => 'Green Sea',
		'midnight-blue' => 'Midnight Blue',
		'nephritis' => 'Nephritis',
		'orange_new' => 'Orange',
		'pumpkin' => 'Pumpkin',
		'peter-river' => 'Peter River',
		'pomegranate' => 'Pomegranate',
		'silver' => 'Silver',
		'sun-flower' => 'Sunflower',
		'turquoise' => 'Turquoise',
		'wisteria' => 'Wisteria',
		'wet-asphalt' => 'Wet Asphalt',
	),
);
$menuColorSchemesArray = array_merge( $colorSchemesArray, array( 'navbar-inverse' => 'Black' ) );

// login Themes
$loginThemesArray = array(
	'boxed' => 'Boxed',
	'tape' => 'Tape',
	'transparent' => 'Transparent',
);

// Our theme config
$theme_config_modern = array(
	'menus' => array(
		'top' => 'Top Menu'
	),
	'settings' => array(
		array(
			'text' => 'Main Page Settings',
			'type' => 'separator'
		),
		'backgroundColor' => array(
			'text' => 'Background Color',
			'type' => 'colorpicker'
		),
		'backgroundPattern' => array(
			'text' => 'Background Pattern',
			'type' => 'selectWithPreview',
			'default' => 'diceWhiteSmall',
			'options' => $backgroundOptionsArray
		),
		'backgroundImage' => array(
			'text' => 'Background Image',
			'type' => 'text',
			'addAppendButton' => true,
			'appendButton' => '<button data-input-id="ftsmbp_theme_' . $theme_folder_modern . '_settings_backgroundImage" type="button" class="btn btn-success file-manager-linked">Upload New Image</button>',
		),
		'contentColorScheme' => array(
			'text' => 'Color Scheme (Content)',
			'type' => 'selectWithPreview',
			'default' => 'red',
			'options' => $colorSchemesArray
		),
		'menuColorScheme' => array(
			'text' => 'Color Scheme (Menu)',
			'type' => 'selectWithPreview',
			'default' => 'blue',
			'options' => $menuColorSchemesArray
		),
		'menuLogoColorScheme' => array(
			'text' => 'Color Scheme (Menu Logo BG)',
			'type' => 'selectWithPreview',
			'default' => 'blue',
			'options' => $menuColorSchemesArray
		),
		'sidebarColorScheme' => array(
			'text' => 'Color Scheme (Sidebar)',
			'type' => 'selectWithPreview',
			'default' => 'black',
			'options' => $colorSchemesArray
		),
		'sidebarTitlesColorScheme' => array(
			'text' => 'Color Scheme (Sidebar Titles)',
			'type' => 'selectWithPreview',
			'default' => 'red',
			'options' => $colorSchemesArray
		),
		'buttonStyle' => array(
			'text' => 'Button Style',
			'type' => 'select',
			'default' => 'normal',
			'options' => array(
				'normal' => 'Normal',
				'flat' => 'Flat',
			)
		),
		'textColorForSidebarLinks' => array(
			'text' => 'Text Color (Sidebar Links)',
			'type' => 'colorpicker',
			'default' => '#808b9c',
		),
		'textColorForSidebarLinksHover' => array(
			'text' => 'Text Color (Sidebar Links) - Hover',
			'type' => 'colorpicker',
			'default' => '#ffffff',
		),
		'textColorForTabLinks' => array(
			'text' => 'Text Color (Tab Links)',
			'type' => 'colorpicker',
			'default' => '#777777',
		),
		'textColorForTabLinksHover' => array(
			'text' => 'Text Color (Tab Links) - Hover',
			'type' => 'colorpicker',
			'default' => '#555555',
		),
		'textColorForActiveTabLink' => array(
			'text' => 'Text Color (Active Tab Link)',
			'type' => 'colorpicker',
			'default' => '#ffffff',
		),
		'textColorForActiveTabLinkHover' => array(
			'text' => 'Text Color (Active Tab Link) - Hover',
			'type' => 'colorpicker',
			'default' => '#ffffff',
		),
		'customCSS' => array(
			'text' => 'Custom CSS',
			'type' => 'textarea',
			'default' => '',
		),
		array(
			'text' => 'Login and Create Account/Forgot Password Page Settings',
			'type' => 'separator'
		),
		'backgroundColorForLoginPage' => array(
			'text' => 'Background Color',
			'type' => 'colorpicker'
		),
		'backgroundPatternForLoginPage' => array(
			'text' => 'Background Pattern',
			'type' => 'selectWithPreview',
			'default' => 'diceWhiteSmall',
			'options' => $backgroundOptionsArray
		),
		'backgroundImageForLoginPage' => array(
			'text' => 'Background Image',
			'type' => 'text',
			'addAppendButton' => true,
			'appendButton' => '<button data-input-id="ftsmbp_theme_' . $theme_folder_modern . '_settings_backgroundImageForLoginPage" type="button" class="btn btn-success file-manager-linked">Upload New Image</button>',
		),
		'bodyStyleThemeForLoginPage' => array(
			'text' => 'Body Style Theme',
			'type' => 'select',
			'default' => 'boxed',
			'options' => $loginThemesArray
		),
		'textColorForLoginPage' => array(
			'text' => 'Text Color',
			'type' => 'colorpicker',
			'default' => '#444444',
		),
		'textShadowColorForLoginPage' => array(
			'text' => 'Text Shadow Color',
			'type' => 'colorpicker',
			'default' => '#ffffff',
		),
		'customCSSForLoginPage' => array(
			'text' => 'Custom CSS',
			'type' => 'textarea',
			'default' => '',
		),
	/*
		'setting1' => array(
			'text' => 'Setting 1',
			'type' => 'checkbox',
			'value' => '1',
			'default' => '1',
		),
		'setting2' => array(
			'text' => 'Yes',
			'type' => 'radio',
			'group' => 'group1',
			'value' => '1',
			'default' => '1',
		),
		'setting22' => array(
			'text' => 'No',
			'type' => 'radio',
			'group' => 'group1',
			'value' => '1',
		),
		'setting3' => array(
			'text' => 'Setting 3',
			'type' => 'textarea',
			'default' => 'default textarea',
		),
		'sep1' => array(
			'text' => 'Setting 3',
			'type' => 'separator',
		),
		'setting4' => 'Setting 4',
		'setting5' => array(
			'text' => 'Setting 5',
			'type' => 'select',
			'options' => array(
				'op1' => 'Option 1',
				'op2' => 'Option 2',
			),
		),
	*/
	),
	'widget_areas' => array(
		//'header' => 'Header',
		'leftCol' => 'Left Column',
		'leftColAboveMenu' => 'Left Column: Above Menu',
		/*
		'rightCol' => 'Right Column',
		'footerLeftCol' => 'Footer Left Column',
		'footerMiddleCol' => 'Footer Middle Column',
		'footerRightCol' => 'Footer Right Column',
		'headerHomepage' => 'Header (Homepage)',
		'leftColHomepage' => 'Left Column (Homepage)',
		'rightColHomepage' => 'Right Column (Homepage)',
		'footerLeftColHomepage' => 'Footer Left Column (Homepage)',
		'footerMiddleColHomepage' => 'Footer Middle Column (Homepage)',
		'footerRightColHomepage' => 'Footer Right Column (Homepage)',*/
	),
);

// Register our configuration options
theme_register_options($theme_folder_modern, $theme_config_modern);

// Add our code to show the preview of the background
function modern_showSelectionPreview( $selectionID, $value ) {
	// Dropdown for background patterns
	if ( $selectionID == 'ftsmbp_theme_modern_settings_backgroundPattern' ) {
		echo '$("#' . $selectionID . 'Preview img").hide();
			$("#' . $selectionID . 'Preview").addClass("' . $value . '");';
	}
	
	// Dropdown for color options
	$selectsWithPreviews = array(
		'ftsmbp_theme_modern_settings_backgroundPattern',
		'ftsmbp_theme_modern_settings_backgroundPatternForLoginPage',
		'ftsmbp_theme_modern_settings_contentColorScheme',
		'ftsmbp_theme_modern_settings_menuColorScheme',
		'ftsmbp_theme_modern_settings_menuLogoColorScheme',
		'ftsmbp_theme_modern_settings_sidebarColorScheme',
		'ftsmbp_theme_modern_settings_sidebarTitlesColorScheme',
	);
	
	if ( in_array( $selectionID, $selectsWithPreviews ) ) {
		echo '$("#' . $selectionID . 'Preview img").hide();
			$("#' . $selectionID . 'Preview").addClass("color ' . $value . '");';
	}
}
add_action( 'showSelectionPreview', 'modern_showSelectionPreview', 10, 2 );