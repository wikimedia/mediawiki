<?php

/**
 * @ingroup Testing
 *
 * Set of classes to help with test output and such. Right now pretty specific
 * to the parser tests but could be more useful one day :)
 *
 * @todo Fixme: Make this more generic
 */

class AnsiTermColorer {
	function __construct() {
	}

	/**
	 * Return ANSI terminal escape code for changing text attribs/color
	 *
	 * @param $color String: semicolon-separated list of attribute/color codes
	 * @return String
	 */
	public function color( $color ) {
		global $wgCommandLineDarkBg;

		$light = $wgCommandLineDarkBg ? "1;" : "0;";

		return "\x1b[{$light}{$color}m";
	}

	/**
	 * Return ANSI terminal escape code for restoring default text attributes
	 *
	 * @return String
	 */
	public function reset() {
		return $this->color( 0 );
	}
}

/* A colour-less terminal */
class DummyTermColorer {
	public function color( $color ) {
		return '';
	}

	public function reset() {
		return '';
	}
}

