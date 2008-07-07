<?php

/**
 * Compatibility functions
 *
 * We more or less support PHP 5.0.x and up.
 * Re-implementations of newer functions or functions in non-standard
 * PHP extensions may be included here.
 */
if( !function_exists('iconv') ) {
	# iconv support is not in the default configuration and so may not be present.
	# Assume will only ever use utf-8 and iso-8859-1.
	# This will *not* work in all circumstances.
	function iconv( $from, $to, $string ) {
		if(strcasecmp( $from, $to ) == 0) return $string;
		if(strcasecmp( $from, 'utf-8' ) == 0) return utf8_decode( $string );
		if(strcasecmp( $to, 'utf-8' ) == 0) return utf8_encode( $string );
		return $string;
	}
}

# UTF-8 substr function based on a PHP manual comment
if ( !function_exists( 'mb_substr' ) ) {
	function mb_substr( $str, $start ) {
		$ar = array();
		preg_match_all( '/./us', $str, $ar );

		if( func_num_args() >= 3 ) {
			$end = func_get_arg( 2 );
			return join( '', array_slice( $ar[0], $start, $end ) );
		} else {
			return join( '', array_slice( $ar[0], $start ) );
		}
	}
}

if ( !function_exists( 'mb_strlen' ) ) {
	/**
	 * Fallback implementation of mb_strlen, hardcoded to UTF-8.
	 * @param string $str
	 * @param string $enc optional encoding; ignored
	 * @return int
	 */
	function mb_strlen( $str, $enc="" ) {
		$counts = count_chars( $str );
		$total = 0;

		// Count ASCII bytes
		for( $i = 0; $i < 0x80; $i++ ) {
			$total += $counts[$i];
		}

		// Count multibyte sequence heads
		for( $i = 0xc0; $i < 0xff; $i++ ) {
			$total += $counts[$i];
		}
		return $total;
	}
}

if ( !function_exists( 'array_diff_key' ) ) {
	/**
	 * Exists in PHP 5.1.0+
	 * Not quite compatible, two-argument version only
	 * Null values will cause problems due to this use of isset()
	 */
	function array_diff_key( $left, $right ) {
		$result = $left;
		foreach ( $left as $key => $unused ) {
			if ( isset( $right[$key] ) ) {
				unset( $result[$key] );
			}
		}
		return $result;
	}
}
