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
 * Evaluator helper class representing a range list.
 */
class CLDRPluralRuleEvaluatorRange {
	/**
	 * The parts
	 *
	 * @var array
	 */
	public $parts = array();

	/**
	 * Initialize a new instance of CLDRPluralRuleEvaluatorRange
	 *
	 * @param int $start The start of the range
	 * @param int|bool $end The end of the range, or false if the range is not bounded.
	 */
	function __construct( $start, $end = false ) {
		if ( $end === false ) {
			$this->parts[] = $start;
		} else {
			$this->parts[] = array( $start, $end );
		}
	}

	/**
	 * Determine if the given number is inside the range.
	 *
	 * @param int $number The number to check
	 * @param bool $integerConstraint If true, also asserts the number is an integer;
	 *   otherwise, number simply has to be inside the range.
	 * @return bool True if the number is inside the range; otherwise, false.
	 */
	function isNumberIn( $number, $integerConstraint = true ) {
		foreach ( $this->parts as $part ) {
			if ( is_array( $part ) ) {
				if ( ( !$integerConstraint || floor( $number ) === (float)$number )
					&& $number >= $part[0] && $number <= $part[1]
				) {
					return true;
				}
			} else {
				if ( $number == $part ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Readable alias for isNumberIn( $number, false ), and the implementation
	 * of the "within" operator.
	 *
	 * @param int $number The number to check
	 * @return bool True if the number is inside the range; otherwise, false.
	 */
	function isNumberWithin( $number ) {
		return $this->isNumberIn( $number, false );
	}

	/**
	 * Add another part to this range.
	 *
	 * @param CLDRPluralRuleEvaluatorRange|int $other The part to add, either
	 *   a range object itself or a single number.
	 */
	function add( $other ) {
		if ( $other instanceof self ) {
			$this->parts = array_merge( $this->parts, $other->parts );
		} else {
			$this->parts[] = $other;
		}
	}

	/**
	 * Returns the string representation of the rule evaluator range.
	 * The purpose of this method is to help debugging.
	 *
	 * @return string The string representation of the rule evaluator range
	 */
	function __toString() {
		$s = 'Range(';
		foreach ( $this->parts as $i => $part ) {
			if ( $i ) {
				$s .= ', ';
			}
			if ( is_array( $part ) ) {
				$s .= $part[0] . '..' . $part[1];
			} else {
				$s .= $part;
			}
		}
		$s .= ')';

		return $s;
	}
}
