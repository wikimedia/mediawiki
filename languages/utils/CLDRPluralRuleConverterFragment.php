<?php
/**
 * @author Niklas Laxström, Tim Starling
 *
 * @copyright Copyright © 2010-2012, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 *
 * @file
 * @since 1.20
 */

/**
 * Helper for CLDRPluralRuleConverter.
 * The base class for operators and expressions, describing a region of the input string.
 */
class CLDRPluralRuleConverterFragment {
	public $parser, $pos, $length, $end;

	function __construct( $parser, $pos, $length ) {
		$this->parser = $parser;
		$this->pos = $pos;
		$this->length = $length;
		$this->end = $pos + $length;
	}

	public function error( $message ) {
		$text = $this->getText();
		throw new CLDRPluralRuleError( "$message at position " . ( $this->pos + 1 ) . ": \"$text\"" );
	}

	public function getText() {
		return substr( $this->parser->rule, $this->pos, $this->length );
	}
}
