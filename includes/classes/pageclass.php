<?php
/***************************************************************************
 *                               pageclass.php
 *                            -------------------
 *   begin                : Tuesday, August 15, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   website              : http://www.fasttracksites.com
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/

 
class pageClass {
	public $templateVars = array();
	public $menus = array();
	public $breadCrumbTrail = array();
	
	//===============================================================
	// This function will me used for setting our template variables 
	//===============================================================
	public function setTemplateVar($varname, $varvalue) {
		$this->templateVars[$varname] = $varvalue;
	}
	
	//===============================================================
	// This function will me used for retreiving our template variables 
	//===============================================================
	public function getTemplateVar($varname) {
		return $this->templateVars[$varname];
	}
	
	//===============================================================
	// This function will me used for printing our template variables 
	//===============================================================
	public function printTemplateVar($varname) {
		echo $this->templateVars[$varname];
	}
	
	//===============================================================
	// This function will allow us to add breadcrumbs to our trail
	//===============================================================
	public function addBreadCrumb( $name, $link = '', $icon = '' ) {
		$this->breadCrumbTrail[] = array( 
			'name' => $name, 
			'link' => $this->makeNavURL($link),
			'icon' => $icon, 
		);
	}
	
	//===============================================================
	// This function will allow us to add scripts to our theme
	//===============================================================
	public function addScript($URL) {
		$this->scripts[$URL] = 1;
	}
	
	//===============================================================
	// This function will allow us to add stylesheets to our theme
	//===============================================================
	public function addStyle($URL) {
		$this->styles[$URL] = 1;
	}
	
	//===============================================================
	// This function adds our menu item to the array. 
	//===============================================================
	public function makeMenuItem( $menu, $label, $page, $class = '', $id = '', $parent = 0, $icon = '', $rel = '' ) {
		if (!array_key_exists($menu, $this->menus)) $this->menus[$menu] = array();
		if (!array_key_exists($parent, $this->menus[$menu])) $this->menus[$menu][$parent] = array();
	
		$this->menus[$menu][$parent][$label] = array(
			'id' => $id,
			'value' => ( $class == 'nav-header' ) ? $page : $this->makeNavURL($page),
			'class' => $class,
			'icon' => $icon,
			'rel' => $rel,
		);
	}
	
	//===============================================================
	// This function removes a menu item from the array. 
	//===============================================================
	public function removeMenuItem($menu, $label, $parent = 0) {
		unset($this->menus[$menu][$parent][$label]);
	}
	
	//===============================================================
	// This function determines wether the link is internal or 
	// external. An internal link should be the id in the DB. 
	//===============================================================
	public function makeNavURL($link) {
		return il($link);
	}
	
	//===============================================================
	// This function prints our menus on the page, it also allows 
	// for customization of what type of what type of tag to use 
	//
	// $menu = top, left, bottom
	// $tag = a, ul, ol
	// $seperator = text that goes between links ie <br />
	// $tagClass = name of a class that is added to each tag
	// $tagBodyClass = name of class that is added to UL or OL
	// $headeritem = text or other item that will be at top of menu
	//===============================================================
	public function printMenu( $menu, $tag, $seperator = "", $tagClass = "", $tagBodyID = "", $tagBodyClass = "", $headerItem = "", $parent = 0, $bootstrapVersion = 0 ) {
		// This is for backwards compatability
		echo $this->returnMenu( $menu, $tag, $seperator, $tagClass, $tagBodyID, $tagBodyClass, $headerItem, $parent, $bootstrapVersion );
	}	
	
	public function returnMenu( $menu, $tag, $seperator = "", $tagClass = "", $tagBodyID = "", $tagBodyClass = "", $headerItem = "", $parent = 0, $bootstrapVersion = 0 ) {
		$doneonce = 0;
		$menuHTML = "";
		$classTag = (!empty($tagBodyClass)) ? " class=\"" . $tagBodyClass . "\"" : "";
		$idTag = (!empty($tagBodyID)) ? " id=\"" . $tagBodyID . "\"" : "";
		$currentPage = basename($_SERVER['REQUEST_URI']);
		
		// Print opening tag
		$menuHTML .= ($tag != "a") ? "<" . $tag . $idTag . $classTag . ">" : "";
		$menuHTML .= (!empty($headerItem)) ? "\n						" . $headerItem : "";
		
		// Get our menu items
		$menuHTML .= $this->printMenuItems( $menu, $tag, $seperator, $tagClass, $parent, $bootstrapVersion );
		
		// Print closing tag
		$menuHTML .= ($tag != "a") ? "\n					</" . $tag . ">\n" : "";
		
		return $menuHTML;
	}	
	
	//===============================================================
	// This function returns out menu items. Done as separate 
	// function so it can be called on themes that are different.
	//===============================================================
	public function printMenuItems( $menu, $parentTag, $seperator = "", $tagClass = "", $parent = 0, $bootstrapVersion = 0, $subMenuLevel = 0 ) {
		$doneonce = 0;
		$menuHTML = "";
		$currentPage = basename($_SERVER['REQUEST_URI']);
		
		if (is_array($this->menus[$menu][$parent]) && count($this->menus[$menu][$parent])) {
			foreach ($this->menus[$menu][$parent] as $label => $settingsArray) {
				if ($doneonce == "1" && !empty($seperator) && $parentTag == "a") { echo $seperator; } // do seperators only for a's
				$hasSubmenu = 0;
				$linkParams = $submenu = $submenuHolderClass = '';
				$link = ( !empty( $settingsArray['value'] ) ) ? $settingsArray['value'] : '#';
				
				// Prep any submenus
				if ( $settingsArray['id'] != '' && isset( $this->menus[$menu][$settingsArray['id']] ) ) {
					$hasSubmenu = 1;
					$subMenuLevel++;
					
					if ( $bootstrapVersion ) {
						/*
						// Bootsrap v2
						if ( $subMenuLevel == 1 ) {
							$submenuHolderClass = ' dropdown';
							$label .= ' <b class="caret"></b>';
							$linkParams = ' class="dropdown-toggle" data-toggle="dropdown"';
						}
						else {
							$submenuHolderClass = ' dropdown-submenu';
						}
						$submenuClass = 'dropdown-menu';
						*/
						// Bootstrap v3 with smartmenus plugin
						$submenuClass = 'dropdown-menu';
					}
					
					$submenu = "\n" . '<ul class="' . $submenuClass . '">' . $this->printMenuItems($menu, 'ul', '', '', $settingsArray['id'], $bootstrapVersion, $subMenuLevel) . '</ul>';
				}
				$linkParams .= ( !empty( $settingsArray['rel'] ) ) ? ' rel="' . $settingsArray['rel'] . '"' : '';
				
				// Handle the wrapper parameters
				$classTag = ( !empty( $tagClass ) ) ? $tagClass : '';
				$classTag = ( !empty( $settingsArray['class'] ) ) ? $settingsArray['class'] : $classTag;				
				$classTag .= ( $currentPage == $link ) ? " active" : "";
				$classTag .= $submenuHolderClass;
				
				// Wrap it
				$classTag = ( !empty( $classTag ) ) ? ' class="' . trim( $classTag ) . '"' : '';
				
				// Handle icons
				$icon = ( !empty( $settingsArray['icon'] ) ) ? '<i class="' . trim( $settingsArray['icon'] ) . '"></i> ' : '';
				
				// Build the actual item
				$menuItem = '<a href="' . $link . '"' . $linkParams . '><span>' . $icon . $label . '</span></a>' . $submenu;
				
				// Wrap the item
				$menuHTML .= ( $parentTag == "ul" || $parentTag == "ol" ) ? "\n" . '<li' . $classTag . '>' . $menuItem . '</li>' : $menuItem;
				
				$doneonce = "1";
			}
		}
		
		return $menuHTML;
	}	
	
	//===============================================================
	// This function prints our sidebar 
	//
	// $tagClass = name of a class that is added to the sidebar ul
	//===============================================================
	public function printSidebar( $tagId, $tagClass = '', $linkLabelPrefix = '', $linkPrefix = '', $iconOutsideSpan = 0, $parent = 0, $bootstrapVersion = 0, $subMenuLevel = 0 ) {
		// This is for backwards compatability
		echo $this->returnSidebar( $tagId, $tagClass, $linkLabelPrefix, $linkPrefix, $iconOutsideSpan, $parent, $bootstrapVersion, $subMenuLevel );
	}	
	
	public function returnSidebar( $tagId, $tagClass = '', $linkLabelPrefix = '', $linkPrefix = '', $iconOutsideSpan = 0, $parent = 0, $bootstrapVersion = 0, $subMenuLevel = 0 ) {
		$sidebarHTML = "";
		$classTag = ( !empty( $tagClass ) ) ? ' class="' . $tagClass . '"' : '';
		$idTag = ( !empty( $tagId ) ) ? ' id="' . $tagId . '"' : '';
		$currentPage = basename($_SERVER['REQUEST_URI']);
		
		// Print opening tag
		$sidebarHTML .= '
			<ul' . $idTag . $classTag . '>';
		
		// Get our menu items
		$sidebarHTML .= $this->printSidebarItems( $tagId, $tagClass, $linkLabelPrefix, $linkPrefix, $iconOutsideSpan, $parent, $bootstrapVersion, $subMenuLevel );
		
		// Print closing tag
		$sidebarHTML .= '
				<!--<li class="titleFooter"></li>-->
			</ul>';
		
		return $sidebarHTML;
	}	
	
	//===============================================================
	// This function returns out sidebar items. Done as separate 
	// function so it can be called on themes that are different.
	//===============================================================
	public function printSidebarItems( $tagId, $tagClass = '', $linkLabelPrefix = '', $linkPrefix = '', $iconOutsideSpan = 0, $parent = 0, $bootstrapVersion = 0, $subMenuLevel = 0 ) {		
		$sidebarHTML = "";
		$currentPage = basename($_SERVER['REQUEST_URI']);
		//print_r($this->menus);
		
		// Print sidebar menu if its active
		if ( $this->templateVars['sidebar_active'] == ACTIVE && count( $this->menus['sidebar'][$parent] ) ) {
			foreach ( $this->menus['sidebar'][$parent] as $label => $settingsArray ) {
				$hasSubmenu = 0;
				$linkParams = $submenu = $submenuHolderClass = '';
				$link = ( !empty( $settingsArray['value'] ) ) ? $settingsArray['value'] : '#';
				
				// Prep any submenus
				if ( $settingsArray['id'] != '' && isset( $this->menus['sidebar'][$settingsArray['id']] ) ) {
					$hasSubmenu = 1;
					$subMenuLevel++;
					
					if ( $bootstrapVersion ) {
						// Bootstrap v3 with smartmenus plugin
						$submenuClass = 'dropdown-menu';
					} else {						
						$submenuHolderClass = ' mm-dropdown';
					}
					
					$submenu = "\n" . '<ul class="' . $submenuClass . '">' . $this->printSidebarItems( $tagId, $tagClass, $linkLabelPrefix, $linkPrefix, $iconOutsideSpan, $settingsArray['id'], $bootstrapVersion, $subMenuLevel ) . '</ul>';
				}
				$linkParams .= ( !empty( $settingsArray['rel'] ) ) ? ' rel="' . $settingsArray['rel'] . '"' : '';
				
				$activeTab = ( $currentPage == $settingsArray['value'] ) ? " active" : "";
				$class = ( !empty( $settingsArray['class'] ) || !empty( $activeTab ) || !empty( $submenuHolderClass ) ) ? ' class="' . $settingsArray['class'] . $activeTab . $submenuHolderClass . '"' : '';
			
				// Handle icons
				$icon = ( !empty( $settingsArray['icon'] ) ) ? '<i class="' . trim( $settingsArray['icon'] ) . '"></i> ' : '';
				
				// Some themes need the icon outside of the spane
				$span = ( $iconOutsideSpan ) ? $icon . '<span>' . $linkLabelPrefix . $label . '</span>' : '<span>' . $linkLabelPrefix . $icon . $label . '</span>';
				
				// Put it together
				$link = ( !empty( $settingsArray['value'] ) ) ? $linkPrefix . '<a href="' . $link . '"' . $linkParams . '>' . $span . '</a>' : $icon . $label;
				$sidebarHTML .= '
					<li' . $class . '>' . $link . $submenu . '</li>';
			}
		}
		
		// Print closing tag
		return $sidebarHTML;
	}	
	
	//===============================================================
	// This function prints our breadcrumbs on the page, it also allows 
	// for customization of what type of what type of tag to use 
	//
	// $menu = top, left, bottom
	// $tag = a, ul, ol
	// $seperator = text that goes between links ie <br />
	// $tagClass = name of a class that is added to each tag
	// $tagBodyClass = name of class that is added to UL or OL
	// $seperatorInLI = Should it be before or after the closing li?
	//===============================================================
	public function printBreadCrumbs($tag, $seperator = "", $tagClass = "", $tagBodyID = "", $tagBodyClass = "", $seperatorInLI = 0) {
		$breadCrumbHTML = "";
		$classTag = (!empty($tagBodyClass)) ? " class=\"" . $tagBodyClass . "\"" : "";
		$idTag = (!empty($tagBodyID)) ? " id=\"" . $tagBodyID . "\"" : "";
		
		// Print opening tag
		$breadCrumbHTML .= ($tag != "a") ? "<" . $tag . $idTag . $classTag . ">" : ""; 
		
		// Print our bread crumbs
		if (is_array($this->breadCrumbTrail)) {
			foreach ($this->breadCrumbTrail as $arrayCount => $dataArray) {			
				// Don't print the html if the variable is empty
				$classTag = ( !empty( $tagClass ) ) ? ' class="' . $tagClass . '"' : '';
				
				// Prep our seperator
				$seperator = ($arrayCount < (count($this->breadCrumbTrail) - 1) && !empty($seperator)) ? $seperator : "";
				
				// If we are using a list then wrap it with li tags
				$breadCrumbHTML .= ($tag == "ul" || $tag == "ol") ? "\n						<li" . $classTag . ">" : "";
				if ( !empty($dataArray['icon']) ) $breadCrumbHTML .= '<i class="' . $dataArray['icon'] . '"></i> ';
				$breadCrumbHTML .= ( !empty( $dataArray['link'] ) && $arrayCount < ( count( $this->breadCrumbTrail ) - 1) ) ? "<a href=\"" . $dataArray['link'] . "\"><span>" . $dataArray['name'] . "</span></a>" : $dataArray['name'];
				if ( $seperatorInLI == 1 ) $breadCrumbHTML .= $seperator;
				$breadCrumbHTML .= ($tag == "ul" || $tag == "ol") ? "</li>" : "";
				if ( $seperatorInLI == 0 ) $breadCrumbHTML .= $seperator;
			}
		}
		
		// Print closing tag
		$breadCrumbHTML .= ($tag != "a") ? "\n						</" . $tag . ">" : "";
		
		echo $breadCrumbHTML;
	}
	
	//===============================================================
	// This function prints our script files
	//===============================================================
	public function printScripts() {
		$scriptHTML = '';
		
		// Print our bread crumbs
		if ( is_array( $this->scripts ) ) {
			foreach ( $this->scripts as $script => $nothing ) {
				$scriptHTML .= '			<script type="text/javascript" src="' . $script . '"></script>';
			}
		}
		
		echo $scriptHTML;
	}
	
	//===============================================================
	// This function prints our stylesheet files
	//===============================================================
	public function printStyles() {
		$styleHTML = '';
		
		// Print our bread crumbs
		if ( is_array( $this->styles ) ) {
			foreach ( $this->styles as $stylesheet => $nothing ) {
				$styleHTML .= '			<link rel="stylesheet" type="text/css" href="' . $stylesheet . '" />';
			}
		}
		
		echo $styleHTML;
	}
}