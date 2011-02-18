<?php
/**
 * Generic providers for the MediaWiki PHPUnit test suite
 *
 * @author Ashar Voultoiz
 * @copyright Copyright © 2011, Ashar Voultoiz
 * @file
 */

/** */
class MediaWikiProvide {

	/* provide an array of numbers from 1 up to @param $num */
	private function createProviderUpTo( $num ) {
		$ret = array();
		for( $i=1; $i<=$num;$i++ ) {
			$ret[] = array( $i );
		}
		return $ret;
	}

	/* array of months numbers (as an integer) */
	public function Months() {
		return $this->createProviderUpTo( 12 );
	}

	/* array of days numbers (as an integer) */
	public function Days() {
		return $this->createProviderUpTo( 31 );
	}

	public function DaysMonths() {
		$ret = array();

		$months = $this->Months();
		$days   = $this->Days();
		foreach( $months as $month) {
			foreach( $days as $day ) {
				$ret[] = array( $day[0], $month[0] );
			}
		}
		return $ret;
	}
}
