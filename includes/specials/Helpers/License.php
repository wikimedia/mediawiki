<?php
/**
 * License selector for use on Special:Upload.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license GPL-2.0-or-later
 */

/**
 * A License class for use on Special:Upload (represents a single type of license).
 */
class License {
	public string $template;
	public string $text;

	public function __construct( string $str ) {
		$str = $this->parse( $str );
		[ $this->template, $this->text ] = $this->split( $str );
	}

	/**
	 * @param string $str
	 * @return string
	 */
	protected function parse( $str ) {
		return $str;
	}

	/**
	 * @param string $str
	 * @return string[] Array with [template, text]
	 */
	protected function split( $str ) {
		[ $text, $template ] = explode( '|', strrev( $str ), 2 );
		return [ strrev( $template ), strrev( $text ) ];
	}
}
