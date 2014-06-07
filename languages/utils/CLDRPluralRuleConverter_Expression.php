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
 * An expression object, representing a region of the input string (for error
 * messages), the RPN notation used to evaluate it, and the result type for
 * validation.
 */
class CLDRPluralRuleConverter_Expression extends CLDRPluralRuleConverter_Fragment {
	/** @var string */
	public $type;

	/** @var string */
	public $rpn;

	function __construct( $parser, $type, $rpn, $pos, $length ) {
		parent::__construct( $parser, $pos, $length );
		$this->type = $type;
		$this->rpn = $rpn;
	}

	public function isType( $type ) {
		if ( $type === 'range' && ( $this->type === 'range' || $this->type === 'number' ) ) {
			return true;
		}
		if ( $type === $this->type ) {
			return true;
		}
		return false;
	}
}
