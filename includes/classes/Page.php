<?php
/***************************************************************************
 *                               Page.php
 *                            -------------------
 *   begin                : Tuesday, August 15, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/


class Page {
	public $breadCrumbTrail = [];
	public $menus = [];
	public $scripts = [];
	public $styles = [];
	public $templateVars = [];

	/**
	 * Set a template variable.
	 *
	 * @param string $varName
	 * @param string $varValue
	 */
	public function setTemplateVar( $varName, $varValue ) {
		$this->templateVars[ $varName ] = $varValue;
	}

	/**
	 * Retrieve a template variable.
	 *
	 * @param string $varName
	 *
	 * @return mixed
	 */
	public function getTemplateVar( $varName ) {
		return $this->templateVars[ $varName ];
	}

	/**
	 * Print a template variable.
	 *
	 * @param string $varName
	 */
	public function printTemplateVar( $varName ) {
		echo $this->templateVars[ $varName ];
	}

	/**
	 * Add breadcrumbs to our trail.
	 *
	 * @param string $name
	 * @param string $link
	 * @param string $icon
	 */
	public function addBreadCrumb( $name, $link = '', $icon = '' ) {
		$this->breadCrumbTrail[] = array(
			'name' => $name,
			'link' => $this->makeNavURL( $link ),
			'icon' => $icon,
		);
	}

	/**
	 * Determine whether the link is internal or external. An internal link should be the id in the DB.
	 *
	 * @param string $link
	 *
	 * @return mixed|string
	 */
	public function makeNavURL( $link ) {
		return il( $link );
	}

	/**
	 * Add scripts to our theme.
	 *
	 * @param string $URL
	 */
	public function addScript( $URL ) {
		$this->scripts[ $URL ] = 1;
	}

	/**
	 * Add stylesheets to our theme.
	 *
	 * @param string $URL
	 */
	public function addStyle( $URL ) {
		$this->styles[ $URL ] = 1;
	}

	/**
	 * Add our menu item to the array.
	 *
	 * @param string $menu
	 * @param string $label
	 * @param string $page
	 * @param string $class
	 * @param string $id
	 * @param int    $parent
	 * @param string $icon
	 * @param string $rel
	 */
	public function makeMenuItem( $menu, $label, $page, $class = '', $id = '', $parent = 0, $icon = '', $rel = '' ) {
		if ( ! array_key_exists( $menu, $this->menus ) ) {
			$this->menus[ $menu ] = [];
		}
		if ( ! array_key_exists( $parent, $this->menus[ $menu ] ) ) {
			$this->menus[ $menu ][ $parent ] = [];
		}

		$this->menus[ $menu ][ $parent ][ $label ] = array(
			'id'    => $id,
			'value' => ( $class == 'nav-header' ) ? $page : $this->makeNavURL( $page ),
			'class' => $class,
			'icon'  => $icon,
			'rel'   => $rel,
		);
	}

	/**
	 * Remove a menu item from the array.
	 *
	 * @param string $menu
	 * @param string $label
	 * @param int    $parent
	 */
	public function removeMenuItem( $menu, $label, $parent = 0 ) {
		unset( $this->menus[ $menu ][ $parent ][ $label ] );
	}

	/**
	 * Print our menus on the page, it also allows for customization of what type of what type of tag to use.
	 *
	 * @param string $menu         'top', 'left', 'bottom'
	 * @param string $tag          'a', 'ul', 'ol'
	 * @param string $seperator    text that goes between links ie <br />
	 * @param string $tagClass     name of a class that is added to each tag
	 * @param string $tagBodyID
	 * @param string $tagBodyClass name of class that is added to UL or OL
	 * @param string $headerItem   text or other item that will be at top of menu
	 * @param int    $parent
	 * @param int    $bootstrapVersion
	 */
	public function printMenu( $menu, $tag, $seperator = "", $tagClass = "", $tagBodyID = "", $tagBodyClass = "", $headerItem = "", $parent = 0, $bootstrapVersion = 0 ) {
		// This is for backwards compatability
		echo $this->returnMenu( $menu, $tag, $seperator, $tagClass, $tagBodyID, $tagBodyClass, $headerItem, $parent, $bootstrapVersion );
	}

	/**
	 * Return our menus on the page, it also allows for customization of what type of what type of tag to use.
	 *
	 * @param string $menu         'top', 'left', 'bottom'
	 * @param string $tag          'a', 'ul', 'ol'
	 * @param string $seperator    text that goes between links ie <br />
	 * @param string $tagClass     name of a class that is added to each tag
	 * @param string $tagBodyID
	 * @param string $tagBodyClass name of class that is added to UL or OL
	 * @param string $headerItem   text or other item that will be at top of menu
	 * @param int    $parent
	 * @param int    $bootstrapVersion
	 *
	 * @return string
	 */
	public function returnMenu( $menu, $tag, $seperator = "", $tagClass = "", $tagBodyID = "", $tagBodyClass = "", $headerItem = "", $parent = 0, $bootstrapVersion = 0 ) {
		$menuHTML = "";
		$classTag = ( ! empty( $tagBodyClass ) ) ? " class=\"" . $tagBodyClass . "\"" : "";
		$idTag    = ( ! empty( $tagBodyID ) ) ? " id=\"" . $tagBodyID . "\"" : "";
		// $currentPage = basename( $_SERVER['REQUEST_URI'] );

		// Print opening tag
		$menuHTML .= ( $tag != "a" ) ? "<" . $tag . $idTag . $classTag . ">" : "";
		$menuHTML .= ( ! empty( $headerItem ) ) ? "\n						" . $headerItem : "";

		// Get our menu items
		$menuHTML .= $this->printMenuItems( $menu, $tag, $seperator, $tagClass, $parent, $bootstrapVersion );

		// Print closing tag
		$menuHTML .= ( $tag != "a" ) ? "\n					</" . $tag . ">\n" : "";

		return $menuHTML;
	}

	/**
	 * Return our menu items. Done as separate function so it can be called on themes that are different.
	 *
	 * @param string $menu
	 * @param string $parentTag
	 * @param string $seperator
	 * @param string $tagClass
	 * @param int    $parent
	 * @param int    $bootstrapVersion
	 * @param int    $subMenuLevel
	 *
	 * @return string
	 */
	public function printMenuItems( $menu, $parentTag, $seperator = "", $tagClass = "", $parent = 0, $bootstrapVersion = 0, $subMenuLevel = 0 ) {
		$doneonce    = 0;
		$menuHTML    = "";
		$currentPage = basename( $_SERVER['REQUEST_URI'] );

		if ( is_array( $this->menus[ $menu ][ $parent ] ) && count( $this->menus[ $menu ][ $parent ] ) ) {
			foreach ( $this->menus[ $menu ][ $parent ] as $label => $settingsArray ) {
				if ( $doneonce == "1" && ! empty( $seperator ) && $parentTag == "a" ) {
					echo $seperator;
				} // do separators only for a's
				$linkParams = $submenu = $submenuHolderClass = '';
				$link       = ( ! empty( $settingsArray['value'] ) ) ? $settingsArray['value'] : '#';

				// Prep any submenus
				if ( $settingsArray['id'] != '' && isset( $this->menus[ $menu ][ $settingsArray['id'] ] ) ) {
					$subMenuLevel ++;
					$submenuClass = '';

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

					$submenu = "\n" . '<ul class="' . $submenuClass . '">' . $this->printMenuItems( $menu, 'ul', '', '', $settingsArray['id'], $bootstrapVersion, $subMenuLevel ) . '</ul>';
				}
				$linkParams .= ( ! empty( $settingsArray['rel'] ) ) ? ' rel="' . $settingsArray['rel'] . '"' : '';

				// Handle the wrapper parameters
				$classTag = ( ! empty( $tagClass ) ) ? $tagClass : '';
				$classTag = ( ! empty( $settingsArray['class'] ) ) ? $settingsArray['class'] : $classTag;
				$classTag .= ( $currentPage == $link ) ? " active" : "";
				$classTag .= $submenuHolderClass;

				// Wrap it
				$classTag = ( ! empty( $classTag ) ) ? ' class="' . trim( $classTag ) . '"' : '';

				// Handle icons
				$icon = ( ! empty( $settingsArray['icon'] ) ) ? '<i class="' . trim( $settingsArray['icon'] ) . '"></i> ' : '';

				// Build the actual item
				$menuItem = '<a href="' . $link . '" ' . $linkParams . '><span>' . $icon . $label . '</span></a>' . $submenu;

				// Wrap the item
				$menuHTML .= ( $parentTag == "ul" || $parentTag == "ol" ) ? "\n" . '<li' . $classTag . '>' . $menuItem . '</li>' : $menuItem;

				$doneonce = "1";
			}
		}

		return $menuHTML;
	}

	/**
	 * Print our sidebar.
	 *
	 * @param string $tagId
	 * @param string $tagClass name of a class that is added to the sidebar ul
	 * @param string $linkLabelPrefix
	 * @param string $linkPrefix
	 * @param int    $iconOutsideSpan
	 * @param int    $parent
	 * @param int    $bootstrapVersion
	 * @param int    $subMenuLevel
	 */
	public function printSidebar( $tagId, $tagClass = '', $linkLabelPrefix = '', $linkPrefix = '', $iconOutsideSpan = 0, $parent = 0, $bootstrapVersion = 0, $subMenuLevel = 0 ) {
		// This is for backwards compatability
		echo $this->returnSidebar( $tagId, $tagClass, $linkLabelPrefix, $linkPrefix, $iconOutsideSpan, $parent, $bootstrapVersion, $subMenuLevel );
	}

	/**
	 * Return our sidebar.
	 *
	 * @param string $tagId
	 * @param string $tagClass
	 * @param string $linkLabelPrefix
	 * @param string $linkPrefix
	 * @param int    $iconOutsideSpan
	 * @param int    $parent
	 * @param int    $bootstrapVersion
	 * @param int    $subMenuLevel
	 *
	 * @return string
	 */
	public function returnSidebar( $tagId, $tagClass = '', $linkLabelPrefix = '', $linkPrefix = '', $iconOutsideSpan = 0, $parent = 0, $bootstrapVersion = 0, $subMenuLevel = 0 ) {
		$sidebarHTML = "";
		$classTag    = ( ! empty( $tagClass ) ) ? ' class="' . $tagClass . '"' : '';
		$idTag       = ( ! empty( $tagId ) ) ? ' id="' . $tagId . '"' : '';
		// $currentPage = basename( $_SERVER['REQUEST_URI'] );

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

	/**
	 * Return out sidebar items. Done as separate function so it can be called on themes that are different.
	 *
	 * @param string $tagId
	 * @param string $tagClass
	 * @param string $linkLabelPrefix
	 * @param string $linkPrefix
	 * @param int    $iconOutsideSpan
	 * @param int    $parent
	 * @param int    $bootstrapVersion
	 * @param int    $subMenuLevel
	 *
	 * @return string
	 */
	public function printSidebarItems( $tagId, $tagClass = '', $linkLabelPrefix = '', $linkPrefix = '', $iconOutsideSpan = 0, $parent = 0, $bootstrapVersion = 0, $subMenuLevel = 0 ) {
		$sidebarHTML = "";
		$currentPage = basename( $_SERVER['REQUEST_URI'] );
		//print_r($this->menus);

		// Print sidebar menu if its active
		if ( $this->templateVars['sidebar_active'] == ACTIVE && count( (array) $this->menus['sidebar'][ $parent ] ) ) {
			foreach ( $this->menus['sidebar'][ $parent ] as $label => $settingsArray ) {
				$linkParams = $submenu = $submenuHolderClass = '';
				$link       = ( ! empty( $settingsArray['value'] ) ) ? $settingsArray['value'] : '#';

				// Prep any submenus
				if ( $settingsArray['id'] != '' && isset( $this->menus['sidebar'][ $settingsArray['id'] ] ) ) {
					$subMenuLevel ++;
					$submenuClass = '';

					if ( $bootstrapVersion ) {
						// Bootstrap v3 with smartmenus plugin
						$submenuClass = 'dropdown-menu';
					} else {
						$submenuHolderClass = ' mm-dropdown';
					}

					$submenu = "\n" . '<ul class="' . $submenuClass . '">' . $this->printSidebarItems( $tagId, $tagClass, $linkLabelPrefix, $linkPrefix, $iconOutsideSpan, $settingsArray['id'], $bootstrapVersion, $subMenuLevel ) . '</ul>';
				}
				$linkParams .= ( ! empty( $settingsArray['rel'] ) ) ? ' rel="' . $settingsArray['rel'] . '"' : '';

				$activeTab = ( $currentPage == $settingsArray['value'] ) ? " active" : "";
				$class     = ( ! empty( $settingsArray['class'] ) || ! empty( $activeTab ) || ! empty( $submenuHolderClass ) ) ? ' class="' . $settingsArray['class'] . $activeTab . $submenuHolderClass . '"' : '';

				// Handle icons
				$icon = ( ! empty( $settingsArray['icon'] ) ) ? '<i class="' . trim( $settingsArray['icon'] ) . '"></i> ' : '';

				// Some themes need the icon outside of the spane
				$span = ( $iconOutsideSpan ) ? $icon . '<span>' . $linkLabelPrefix . $label . '</span>' : '<span>' . $linkLabelPrefix . $icon . $label . '</span>';

				// Put it together
				$link        = ( ! empty( $settingsArray['value'] ) ) ? $linkPrefix . '<a href="' . $link . '" ' . $linkParams . '>' . $span . '</a>' : $icon . $label;
				$sidebarHTML .= '
					<li' . $class . '>' . $link . $submenu . '</li>';
			}
		}

		// Print closing tag
		return $sidebarHTML;
	}

	/**
	 * Print our breadcrumbs on the page, it also allows for customization of what type of what type of tag to use.
	 *
	 * @param string $tag
	 * @param string $seperator     text that goes between links ie <br />
	 * @param string $tagClass      name of a class that is added to each tag
	 * @param string $tagBodyID
	 * @param string $tagBodyClass  name of class that is added to UL or OL
	 * @param int    $seperatorInLI Should it be before or after the closing li?
	 */
	public function printBreadCrumbs( $tag, $seperator = "", $tagClass = "", $tagBodyID = "", $tagBodyClass = "", $seperatorInLI = 0 ) {
		$breadCrumbHTML = "";
		$classTag       = ( ! empty( $tagBodyClass ) ) ? " class=\"" . $tagBodyClass . "\"" : "";
		$idTag          = ( ! empty( $tagBodyID ) ) ? " id=\"" . $tagBodyID . "\"" : "";

		// Print opening tag
		$breadCrumbHTML .= ( $tag != "a" ) ? "<" . $tag . $idTag . $classTag . ">" : "";

		// Print our bread crumbs
		if ( is_array( $this->breadCrumbTrail ) ) {
			foreach ( $this->breadCrumbTrail as $arrayCount => $dataArray ) {
				// Don't print the html if the variable is empty
				$classTag = ( ! empty( $tagClass ) ) ? ' class="' . $tagClass . '"' : '';

				// Prep our seperator
				$seperator = ( $arrayCount < ( count( $this->breadCrumbTrail ) - 1 ) && ! empty( $seperator ) ) ? $seperator : "";

				// If we are using a list then wrap it with li tags
				$breadCrumbHTML .= ( $tag == "ul" || $tag == "ol" ) ? "\n						<li" . $classTag . ">" : "";
				if ( ! empty( $dataArray['icon'] ) ) {
					$breadCrumbHTML .= '<i class="' . $dataArray['icon'] . '"></i> ';
				}
				$breadCrumbHTML .= ( ! empty( $dataArray['link'] ) && $arrayCount < ( count( $this->breadCrumbTrail ) - 1 ) ) ? "<a href=\"" . $dataArray['link'] . "\"><span>" . $dataArray['name'] . "</span></a>" : $dataArray['name'];
				if ( $seperatorInLI == 1 ) {
					$breadCrumbHTML .= $seperator;
				}
				$breadCrumbHTML .= ( $tag == "ul" || $tag == "ol" ) ? "</li>" : "";
				if ( $seperatorInLI == 0 ) {
					$breadCrumbHTML .= $seperator;
				}
			}
		}

		// Print closing tag
		$breadCrumbHTML .= ( $tag != "a" ) ? "\n						</" . $tag . ">" : "";

		echo $breadCrumbHTML;
	}

	/**
	 * Print our script files.
	 */
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

	/**
	 * Print our stylesheet files.
	 */
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