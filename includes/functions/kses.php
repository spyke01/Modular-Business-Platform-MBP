<?php
// We are condiering the move to HTMLPurifier but for now we have some of KSES in place until we transition
// Let's see how long this takes 
// Start: 08/07/2013

// Ensure that these variables are added to the global namespace
// (e.g. if using namespaces / autoload in the current PHP environment).
global $allowedentitynames;

$allowedentitynames = array(
	'nbsp',    'iexcl',  'cent',    'pound',  'curren', 'yen',
	'brvbar',  'sect',   'uml',     'copy',   'ordf',   'laquo',
	'not',     'shy',    'reg',     'macr',   'deg',    'plusmn',
	'acute',   'micro',  'para',    'middot', 'cedil',  'ordm',
	'raquo',   'iquest', 'Agrave',  'Aacute', 'Acirc',  'Atilde',
	'Auml',    'Aring',  'AElig',   'Ccedil', 'Egrave', 'Eacute',
	'Ecirc',   'Euml',   'Igrave',  'Iacute', 'Icirc',  'Iuml',
	'ETH',     'Ntilde', 'Ograve',  'Oacute', 'Ocirc',  'Otilde',
	'Ouml',    'times',  'Oslash',  'Ugrave', 'Uacute', 'Ucirc',
	'Uuml',    'Yacute', 'THORN',   'szlig',  'agrave', 'aacute',
	'acirc',   'atilde', 'auml',    'aring',  'aelig',  'ccedil',
	'egrave',  'eacute', 'ecirc',   'euml',   'igrave', 'iacute',
	'icirc',   'iuml',   'eth',     'ntilde', 'ograve', 'oacute',
	'ocirc',   'otilde', 'ouml',    'divide', 'oslash', 'ugrave',
	'uacute',  'ucirc',  'uuml',    'yacute', 'thorn',  'yuml',
	'quot',    'amp',    'lt',      'gt',     'apos',   'OElig',
	'oelig',   'Scaron', 'scaron',  'Yuml',   'circ',   'tilde',
	'ensp',    'emsp',   'thinsp',  'zwnj',   'zwj',    'lrm',
	'rlm',     'ndash',  'mdash',   'lsquo',  'rsquo',  'sbquo',
	'ldquo',   'rdquo',  'bdquo',   'dagger', 'Dagger', 'permil',
	'lsaquo',  'rsaquo', 'euro',    'fnof',   'Alpha',  'Beta',
	'Gamma',   'Delta',  'Epsilon', 'Zeta',   'Eta',    'Theta',
	'Iota',    'Kappa',  'Lambda',  'Mu',     'Nu',     'Xi',
	'Omicron', 'Pi',     'Rho',     'Sigma',  'Tau',    'Upsilon',
	'Phi',     'Chi',    'Psi',     'Omega',  'alpha',  'beta',
	'gamma',   'delta',  'epsilon', 'zeta',   'eta',    'theta',
	'iota',    'kappa',  'lambda',  'mu',     'nu',     'xi',
	'omicron', 'pi',     'rho',     'sigmaf', 'sigma',  'tau',
	'upsilon', 'phi',    'chi',     'psi',    'omega',  'thetasym',
	'upsih',   'piv',    'bull',    'hellip', 'prime',  'Prime',
	'oline',   'frasl',  'weierp',  'image',  'real',   'trade',
	'alefsym', 'larr',   'uarr',    'rarr',   'darr',   'harr',
	'crarr',   'lArr',   'uArr',    'rArr',   'dArr',   'hArr',
	'forall',  'part',   'exist',   'empty',  'nabla',  'isin',
	'notin',   'ni',     'prod',    'sum',    'minus',  'lowast',
	'radic',   'prop',   'infin',   'ang',    'and',    'or',
	'cap',     'cup',    'int',     'sim',    'cong',   'asymp',
	'ne',      'equiv',  'le',      'ge',     'sub',    'sup',
	'nsub',    'sube',   'supe',    'oplus',  'otimes', 'perp',
	'sdot',    'lceil',  'rceil',   'lfloor', 'rfloor', 'lang',
	'rang',    'loz',    'spades',  'clubs',  'hearts', 'diams',
	'sup1',    'sup2',   'sup3',    'frac14', 'frac12', 'frac34',
	'there4',
);

/**
 * Converts and fixes HTML entities.
 *
 * This function normalizes HTML entities. It will convert "AT&T" to the correct
 * "AT&amp;T", "&#00058;" to "&#58;", "&#XYZZY;" to "&amp;#XYZZY;" and so on.
 *
 * @since 4.13.08.08
 *
 * @param string $string Content to normalize entities
 * @return string Content with normalized entities
 */
function fts_kses_normalize_entities($string) {
	// Disarm all entities by converting & to &amp;

	$string = str_replace('&', '&amp;', $string);

	// Change back the allowed entities in our entity whitelist

	$string = preg_replace_callback('/&amp;([A-Za-z]{2,8}[0-9]{0,2});/', 'fts_kses_named_entities', $string);
	$string = preg_replace_callback('/&amp;#(0*[0-9]{1,7});/', 'fts_kses_normalize_entities2', $string);
	$string = preg_replace_callback('/&amp;#[Xx](0*[0-9A-Fa-f]{1,6});/', 'fts_kses_normalize_entities3', $string);

	return $string;
}

/**
 * Callback for fts_kses_normalize_entities() regular expression.
 *
 * This function only accepts valid named entity references, which are finite,
 * case-sensitive, and highly scrutinized by HTML and XML validators.
 *
 * @since 4.13.08.08
 *
 * @param array $matches preg_replace_callback() matches array
 * @return string Correctly encoded entity
 */
function fts_kses_named_entities($matches) {
	global $allowedentitynames;

	if ( empty($matches[1]) )
		return '';

	$i = $matches[1];
	return ( ! in_array($i, $allowedentitynames) ) ? "&amp;$i;" : "&$i;";
}

/**
 * Callback for fts_kses_normalize_entities() regular expression.
 *
 * This function helps fts_kses_normalize_entities() to only accept 16-bit values
 * and nothing more for &#number; entities.
 *
 * @since 4.13.08.08
 *
 * @param array $matches preg_replace_callback() matches array
 * @return string Correctly encoded entity
 */
function fts_kses_normalize_entities2($matches) {
	if ( empty($matches[1]) )
		return '';

	$i = $matches[1];
	if (valid_unicode($i)) {
		$i = str_pad(ltrim($i,'0'), 3, '0', STR_PAD_LEFT);
		$i = "&#$i;";
	} else {
		$i = "&amp;#$i;";
	}

	return $i;
}

/**
 * Callback for fts_kses_normalize_entities() for regular expression.
 *
 * This function helps fts_kses_normalize_entities() to only accept valid Unicode
 * numeric entities in hex form.
 *
 * @since 4.13.08.08
 *
 * @param array $matches preg_replace_callback() matches array
 * @return string Correctly encoded entity
 */
function fts_kses_normalize_entities3($matches) {
	if ( empty($matches[1]) )
		return '';

	$hexchars = $matches[1];
	return ( ( ! valid_unicode(hexdec($hexchars)) ) ? "&amp;#x$hexchars;" : '&#x'.ltrim($hexchars,'0').';' );
}

/**
 * Helper function to determine if a Unicode value is valid.
 *
 * @since 4.13.08.08
 *
 * @param int $i Unicode value
 * @return bool True if the value was a valid Unicode number
 */
function valid_unicode($i) {
	return ( $i == 0x9 || $i == 0xa || $i == 0xd ||
			($i >= 0x20 && $i <= 0xd7ff) ||
			($i >= 0xe000 && $i <= 0xfffd) ||
			($i >= 0x10000 && $i <= 0x10ffff) );
}