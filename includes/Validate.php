<?php
/**
 * Validate - A bunch of static methods used to validate user input
 * Split from Special:Preferences in 1.14 for more general use
 */
class Validate {
	
	/**
	 * Given an inputed integer, determine if it's within a given 
	 * range. If not, return the upper or lower boundary, whichever
	 * was crossed.
	 * @param int $val A user inputted integer
	 * @param int $min The lower limit
	 * @param int $max The upper limit
	 * @return int
	 */
	function int( &$val, $min=0, $max=0x7fffffff ) {
		$val = intval($val);
		$val = min($val, $max);
		$val = max($val, $min);
		return $val;
	}

	/**
	 * Given an inputed float, determine if it's within a given
	 * range. If not, return the upper or lower boundary, whichever
	 * was crossed.
	 * @param float $val User inputed value
	 * @param float $min Lower limit
	 * @param float $max Uppser limit
	 * @return float
	 */
	public static function float( &$val, $min, $max=0x7fffffff ) {
		$val = floatval( $val );
		$val = min( $val, $max );
		$val = max( $val, $min );
		return( $val );
	}

	/**
	 * Like int(), only return null if it's not a string
	 * @see Validate::int()
	 * @param int $val User given integer
	 * @param int $min Lower limit
	 * @param int $max Upper limit
	 * @return mixed [int or null]
	 */
	public static function intOrNull( &$val, $min=0, $max=0x7fffffff ) {
		$val = trim($val);
		if($val === '') {
			return null;
		} else {
			return self :: int( $val, $min, $max );
		}
	}

	/**
	 * Given a date string, validate to see if it's an acceptable type. If
	 * not, return an acceptable one.
	 * @param string $val User inputed date
	 * @return string
	 */
	public static function dateFormat( $val ) {
		global $wgLang, $wgContLang;
		if ( $val !== false && (
			in_array( $val, (array)$wgLang->getDatePreferences() ) ||
			in_array( $val, (array)$wgContLang->getDatePreferences() ) ) )
		{
			return $val;
		} else {
			return $wgLang->getDefaultDateFormat();
		}
	}

	/**
	 * Used to validate the user inputed timezone before saving it as
	 * 'timecorrection', will return '00:00' if fed bogus data.
	 * Note: It's not a 100% correct implementation timezone-wise, it will
	 * accept stuff like '14:30',
	 * @param string $s the user input
	 * @return string
	 */
	public static function timeZone( $s ) {
		if ( $s !== '' ) {
			if ( strpos( $s, ':' ) ) {
				# HH:MM
				$array = explode( ':' , $s );
				$hour = intval( $array[0] );
				$minute = intval( $array[1] );
			} else {
				$minute = intval( $s * 60 );
				$hour = intval( $minute / 60 );
				$minute = abs( $minute ) % 60;
			}
			# Max is +14:00 and min is -12:00, see:
			# http://en.wikipedia.org/wiki/Timezone
			$hour = min( $hour, 14 );
			$hour = max( $hour, -12 );
			$minute = min( $minute, 59 );
			$minute = max( $minute, 0 );
			$s = sprintf( "%02d:%02d", $hour, $minute );
		}
		return $s;
	}
}
