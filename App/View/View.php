<?php

namespace App\View;

use App\Support\Registry;

class View {
	protected $page_content = '';
	protected $jquery_ready_scripts = '';

	/**
	 * Add content to be displayed on the page during render
	 *
	 * @access public
	 *
	 * @param string $content
	 *
	 * @return void
	 */
	public function addContent( $content ) {
		$this->page_content .= $content;
	}

	/**
	 * Add code to the JQuery Ready action of the theme
	 *
	 * @access public
	 *
	 * @param string $JQueryReadyScripts
	 *
	 * @return void
	 */
	public function addJQueryReadyScript( $JQueryReadyScripts ) {
		$this->jquery_ready_scripts .= $JQueryReadyScripts;
	}

	/**
	 * Add a view.
	 *
	 * This function allows us to store our views as a standard PHP file and then include the content
	 *
	 * @access public
	 *
	 * @param mixed $name The name of the view file without the extension (MyView not MyView.php)
	 * @param array $data (default: [])
	 *
	 * @return void
	 */
	public function addView( $name, $data = [] ) {
		// Look for the view with the name
		$path = Registry::get( 'viewfilefinder' )->find( $name );

		if ( file_exists( $path ) ) {
			$data = $this->parseData( $data );

			$obLevel = ob_get_level();

			ob_start();

			extract( $data, EXTR_SKIP );

			include $path;

			$this->addContent( ltrim( ob_get_clean() ) );
		}
	}

	/**
	 * Returns a view.
	 *
	 * This function allows us to pull our views into another one
	 *
	 * @access public
	 *
	 * @param mixed $name The name of the view file without the extension (MyView not MyView.php)
	 * @param array $data (default: [])
	 *
	 * @return string
	 */
	public function returnView( $name, $data = [] ) {
		// Look for the view with the name
		$path      = Registry::get( 'viewfilefinder' )->find( $name );
		$returnVar = '';

		if ( file_exists( $path ) ) {
			$data = $this->parseData( $data );

			$obLevel = ob_get_level();

			ob_start();

			extract( $data, EXTR_SKIP );

			include $path;

			$returnVar = ltrim( ob_get_clean() );
		}

		return $returnVar;
	}

	/**
	 * Parse the given data into a raw array.
	 *
	 * @param mixed $data
	 *
	 * @return array
	 */
	protected function parseData( $data ) {
		return $data instanceof Arrayable ? $data->to[] : $data;
	}

	/**
	 * Attach the content to the Page so it can be rendered
	 *
	 * @access public
	 * @return void
	 */
	public function render() {
		$page = Registry::get( 'Page' );

		// Attach our content
		$page->setTemplateVar( 'PageContent', $this->page_content );
		$page->setTemplateVar( 'JQueryReadyScript', $this->jquery_ready_scripts );
	}

}