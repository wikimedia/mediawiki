<?php
/**
 * Parse and evaluate a plural rule
 *
 * @author Niklas Laxstrom
 *
 * @copyright Copyright © 2010, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @file
 */

class CLDRPluralRuleEvaluator {

	public static function evaluate( $number, $rules ) {
		$formIndex = 0;
		if( $rules == null){
			return 0;
		}
		foreach ( $rules as $form => $rule ) {
			$parsedRule = self::parseCLDRRule( $rule, $number );
			if ( eval( "return $parsedRule;" ) ) {
				return 	$formIndex;
			}
			$formIndex++;
		}

		return $formIndex;
	}

	private static function parseCLDRRule( $rule ) {
		$rule = preg_replace( '/\bn\b/', '$number', $rule );
		$rule = preg_replace( '/([^ ]+) mod (\d+)/', 'self::mod(\1,\2)', $rule );
		$rule = preg_replace( '/([^ ]+) is not (\d+)/' , '\1!=\2', $rule );
		$rule = preg_replace( '/([^ ]+) is (\d+)/', '\1==\2', $rule );
		$rule = preg_replace( '/([^ ]+) not in (\d+)\.\.(\d+)/', '!self::in(\1,\2,\3)', $rule );
		$rule = preg_replace( '/([^ ]+) not within (\d+)\.\.(\d+)/', '!self::within(\1,\2,\3)', $rule );
		$rule = preg_replace( '/([^ ]+) in (\d+)\.\.(\d+)/', 'self::in(\1,\2,\3)', $rule );
		$rule = preg_replace( '/([^ ]+) within (\d+)\.\.(\d+)/', 'self::within(\1,\2,\3)', $rule );
		// AND takes precedence over OR
		$andrule = '/([^ ]+) and ([^ ]+)/i';
		while ( preg_match( $andrule, $rule ) ) {
			$rule = preg_replace( $andrule, '(\1&&\2)', $rule );
		}
		$orrule = '/([^ ]+) or ([^ ]+)/i';
		while ( preg_match( $orrule, $rule ) ) {
			$rule = preg_replace( $orrule, '(\1||\2)', $rule );
		}

		return $rule;
	}

	private static function in( $num, $low, $high ) {
		return is_int( $num ) && $num >= $low && $num <= $high;
	}

	private static function within( $num, $low, $high ) {
		return $num >= $low && $num <= $high;
	}

	private static function mod( $num, $mod ) {
		if ( is_int( $num ) ) {
			return (int) fmod( $num, $mod );
		}
		return fmod( $num, $mod );
	}
}
