<?php
/**
 * Parse and evaluate a plural rule.
 *
 * UTS #35 Revision 33
 * http://www.unicode.org/reports/tr35/tr35-33/tr35-numbers.html#Language_Plural_Rules
 *
 * @author Niklas Laxström, Tim Starling
 *
 * @copyright Copyright © 2010-2012, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0
 * or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 *
 * @file
 * @since 1.20
 */

class CLDRPluralRuleEvaluator {
	/**
	 * Evaluate a number against a set of plural rules. If a rule passes,
	 * return the index of plural rule.
	 *
	 * @param int $number The number to be evaluated against the rules
	 * @param array $rules The associative array of plural rules in pluralform => rule format.
	 * @return int The index of the plural form which passed the evaluation
	 */
	public static function evaluate( $number, array $rules ) {
		$rules = self::compile( $rules );
		return self::evaluateCompiled( $number, $rules );
	}

	/**
	 * Convert a set of rules to a compiled form which is optimised for
	 * fast evaluation. The result will be an array of strings, and may be cached.
	 *
	 * @param array $rules The rules to compile
	 * @return array An array of compile rules.
	 */
	public static function compile( array $rules ) {
		// We can't use array_map() for this because it generates a warning if
		// there is an exception.
		foreach ( $rules as &$rule ) {
			$rule = CLDRPluralRuleConverter::convert( $rule );
		}
		return $rules;
	}

	/**
	 * Evaluate a compiled set of rules returned by compile(). Do not allow
	 * the user to edit the compiled form, or else PHP errors may result.
	 *
	 * @param string $number The number to be evaluated against the rules, in English, or it
	 *   may be a type convertible to string.
	 * @param array $rules The associative array of plural rules in pluralform => rule format.
	 * @return int The index of the plural form which passed the evaluation
	 */
	public static function evaluateCompiled( $number, array $rules ) {
		// Calculate the values of the operand symbols
		$number = strval( $number );
		if ( !preg_match( '/^ -? ( ([0-9]+) (?: \. ([0-9]+) )? )$/x', $number, $m ) ) {
			wfDebug( __METHOD__ . ": invalid number input, returning 'other'\n" );
			return count( $rules );
		}
		if ( !isset( $m[3] ) ) {
			$operandSymbols = array(
				'n' => intval( $m[1] ),
				'i' => intval( $m[1] ),
				'v' => 0,
				'w' => 0,
				'f' => 0,
				't' => 0
			);
		} else {
			$absValStr = $m[1];
			$intStr = $m[2];
			$fracStr = $m[3];
			$operandSymbols = array(
				'n' => floatval( $absValStr ),
				'i' => intval( $intStr ),
				'v' => strlen( $fracStr ),
				'w' => strlen( rtrim( $fracStr, '0' ) ),
				'f' => intval( $fracStr ),
				't' => intval( rtrim( $fracStr, '0' ) ),
			);
		}

		// The compiled form is RPN, with tokens strictly delimited by
		// spaces, so this is a simple RPN evaluator.
		foreach ( $rules as $i => $rule ) {
			$stack = array();
			$zero = ord( '0' );
			$nine = ord( '9' );
			foreach ( StringUtils::explode( ' ', $rule ) as $token ) {
				$ord = ord( $token );
				if ( isset( $operandSymbols[$token] ) ) {
					$stack[] = $operandSymbols[$token];
				} elseif ( $ord >= $zero && $ord <= $nine ) {
					$stack[] = intval( $token );
				} else {
					$right = array_pop( $stack );
					$left = array_pop( $stack );
					$result = self::doOperation( $token, $left, $right );
					$stack[] = $result;
				}
			}
			if ( $stack[0] ) {
				return $i;
			}
		}
		// None of the provided rules match. The number belongs to category
		// 'other', which comes last.
		return count( $rules );
	}

	/**
	 * Do a single operation
	 *
	 * @param string $token The token string
	 * @param mixed $left The left operand. If it is an object, its state may be destroyed.
	 * @param mixed $right The right operand
	 * @throws CLDRPluralRuleError
	 * @return mixed The operation result
	 */
	private static function doOperation( $token, $left, $right ) {
		if ( in_array( $token, array( 'in', 'not-in', 'within', 'not-within' ) ) ) {
			if ( !( $right instanceof CLDRPluralRuleEvaluator_Range ) ) {
				$right = new CLDRPluralRuleEvaluator_Range( $right );
			}
		}
		switch ( $token ) {
			case 'or':
				return $left || $right;
			case 'and':
				return $left && $right;
			case 'is':
				return $left == $right;
			case 'is-not':
				return $left != $right;
			case 'in':
				return $right->isNumberIn( $left );
			case 'not-in':
				return !$right->isNumberIn( $left );
			case 'within':
				return $right->isNumberWithin( $left );
			case 'not-within':
				return !$right->isNumberWithin( $left );
			case 'mod':
				if ( is_int( $left ) ) {
					return (int)fmod( $left, $right );
				}
				return fmod( $left, $right );
			case ',':
				if ( $left instanceof CLDRPluralRuleEvaluator_Range ) {
					$range = $left;
				} else {
					$range = new CLDRPluralRuleEvaluator_Range( $left );
				}
				$range->add( $right );
				return $range;
			case '..':
				return new CLDRPluralRuleEvaluator_Range( $left, $right );
			default:
				throw new CLDRPluralRuleError( "Invalid RPN token" );
		}
	}
}
