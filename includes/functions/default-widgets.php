<?php 
/***************************************************************************
 *                               default-widgets.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



class FTS_Widget_Menu extends FTS_Widget {

	function __construct() {
		parent::__construct('menu', 'Menu', 'This widget displays a menu you select.');
	}

	function display( $settings ) {
		global $ftsdb;
		
		$currentSettings = json_decode($settings);
		
		$widget = '
			<h3 class="widget-title">' . $currentSettings->title . '</h3>
			<div class="widget-content">
				<ul>';
				
			$result = $ftsdb->select(DBTABLEPREFIX . "menu_items", "menu_id = :menu_id ORDER BY `order`", array(
				":menu_id" => $currentSettings->menu
			));
			
			if ($result) {
				foreach ($result as $row) {
					$widget .= '<li><a href="' . il($row['link']) . '">' . $row['text'] . '</a></li>';
				}
				$result = NULL;
			}
			
		$widget .= '
				</ul>
			</div>';
			
		return $widget;
	}

	function form( $settings = '' ) {
		global $ftsdb;
		
		$currentSettings = json_decode($settings);
		
		$form = '
			<p><label for="' . $this->get_field_id('title') . '">Title</label> <input type="text" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" value="' . $currentSettings->title . '" /></p>
			<p>
				<label for="' . $this->get_field_id('menu') . '">Select Menu:</label>
				<select id="' . $this->get_field_id('menu') . '" name="' . $this->get_field_name('menu') . '">';
				
			$result = $ftsdb->select(DBTABLEPREFIX . "menus", "1 ORDER BY name", array(), 'id, name');
			
			if ($result) {
				foreach ($result as $row) {
					$form .= '<option value="' . $row['id'] . '"' . testSelected($row['id'], $currentSettings->menu) . '>' . $row['name'] . '</option>';
				}
				$result = NULL;
			}
			
		$form .= '
				</select>
			</p>';
			
		return $form;
	}
}

class FTS_Widget_Text extends FTS_Widget {

	function __construct() {
		parent::__construct('text-html', 'Text / HTML', 'This widget allows you to input a block of text or HTML.');
	}

	function display( $settings ) {
		$currentSettings = json_decode($settings);
		
		$widget = '
			<h3 class="widget-title">' . $currentSettings->title . '</h3>
			<div class="widget-content">
				' . nl2br($currentSettings->text) . '
			</div>';
			
		return $widget;
	}

	function form( $settings = '' ) {
		$currentSettings = json_decode($settings);
		
		return '
			<p><label for="' . $this->get_field_id('title') . '">Title</label> <input type="text" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" value="' . $currentSettings->title . '" /></p>
			<textarea rows="16" cols="20" id="' . $this->get_field_id('text') . '" name="' . $this->get_field_name('text') . '">' . $currentSettings->text . '</textarea>';
	}
}

// Register all of our widgets
registerWidget('FTS_Widget_Menu');
registerWidget('FTS_Widget_Text');