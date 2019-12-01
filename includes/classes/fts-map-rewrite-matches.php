<?php
/***************************************************************************
 *                               fts-map-rewrite-matches.php
 *                            -------------------
 *   begin                : Tuseday, October 31, 2008
 *   copyright            : (C) 2008 Paden Clayton
 *
 *
 ***************************************************************************/


class FTS_MapRewriteMatches {
	/**
	 * store for matches
	 *
	 * @access private
	 * @var array
	 */
	private $_matches;

	/**
	 * store for mapping result
	 *
	 * @access public
	 * @var string
	 */
	public $output;

	/**
	 * subject to perform mapping on (query string containing $matches[] references
	 *
	 * @access private
	 * @var string
	 */
	private $_subject;

	/**
	 * regexp pattern to match $matches[] references
	 *
	 * @var string
	 */
	public $_pattern = '(\$matches\[[1-9]+[0-9]*\])'; // magic number

	/**
	 * constructor
	 *
	 * @param string $subject subject if regex
	 * @param array  $matches data to use in map
	 */
	public function __construct( $subject, $matches ) {
		$this->_subject = $subject;
		$this->_matches = $matches;
		$this->output   = $this->_map();
	}

	/**
	 * do the actual mapping
	 *
	 * @access private
	 * @return string
	 */
	private function _map() {
		$callback = array( $this, 'callback' );

		return preg_replace_callback( $this->_pattern, $callback, $this->_subject );
	}

	/**
	 * Substitute substring matches in subject.
	 *
	 * static helper function to ease use
	 *
	 * @static
	 * @access public
	 *
	 * @param string $subject subject
	 * @param array  $matches data used for substitution
	 *
	 * @return string
	 */
	public static function apply( $subject, $matches ) {
		$oSelf = new FTS_MapRewriteMatches( $subject, $matches );

		return $oSelf->output;
	}

	/**
	 * preg_replace_callback hook
	 *
	 * @access public
	 *
	 * @param  array $matches preg_replace regexp matches
	 *
	 * @return string
	 */
	public function callback( $matches ) {
		$index = intval( substr( $matches[0], 9, - 1 ) );

		return ( isset( $this->_matches[ $index ] ) ? urlencode( $this->_matches[ $index ] ) : '' );
	}
}