<?php
/**
 * Generic providers for the MediaWiki PHPUnit test suite
 *
 * @author Antoine Musso
 * @copyright Copyright © 2011, Antoine Musso
 * @file
 */

/** */
class MediaWikiProvide {

	/* provide an array of numbers from 1 up to @param $num */
	private static function createProviderUpTo( $num ) {
		$ret = array();
		for( $i=1; $i<=$num;$i++ ) {
			$ret[] = array( $i );
		}
		return $ret;
	}

	/* array of months numbers (as an integer) */
	public static function Months() {
		return self::createProviderUpTo( 12 );
	}

	/* array of days numbers (as an integer) */
	public static function Days() {
		return self::createProviderUpTo( 31 );
	}

	public static function DaysMonths() {
		$ret = array();

		$months = self::Months();
		$days   = self::Days();
		foreach( $months as $month) {
			foreach( $days as $day ) {
				$ret[] = array( $day[0], $month[0] );
			}
		}
		return $ret;
	}
}
