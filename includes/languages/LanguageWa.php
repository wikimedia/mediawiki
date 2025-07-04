<?php
/**
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
 * @file
 */

use MediaWiki\Language\Language;

/**
 * Walloon (Walon)
 *
 * NOTE: cweri après "NOTE:" po des racsegnes so des ratournaedjes
 * k' i gn a.
 *
 * @ingroup Languages
 */
class LanguageWa extends Language {

	/**
	 * Dates in Walloon are "1î d' <monthname>" for 1st of the month,
	 * "<day> di <monthname>" for months starting by a consoun, and
	 * "<day> d' <monthname>" for months starting with a vowel
	 *
	 * @inheritDoc
	 */
	public function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		$datePreference = $this->dateFormat( $format );
		if ( $datePreference == 'ISO 8601' || $datePreference == 'walloon short' ) {
			return parent::date( $ts, $adj, $format, $timecorrection );
		}

		$ts = wfTimestamp( TS_MW, $ts );
		if ( $adj ) {
			$ts = $this->userAdjust( $ts, $timecorrection );
		}

		# Walloon 'dmy' format
		$m = (int)substr( $ts, 4, 2 );
		$n = (int)substr( $ts, 6, 2 );
		if ( $n == 1 ) {
			$d = "1î d' " . $this->getMonthName( $m ) .
				" " . substr( $ts, 0, 4 );
		} elseif ( $n == 2 || $n == 3 || $n == 20 || $n == 22 || $n == 23 ) {
			$d = $n . " d' " . $this->getMonthName( $m ) .
				" " . substr( $ts, 0, 4 );
		} elseif ( $m == 4 || $m == 8 || $m == 10 ) {
			$d = $n . " d' " . $this->getMonthName( $m ) .
				" " . substr( $ts, 0, 4 );
		} else {
			$d = $n . " di " . $this->getMonthName( $m ) .
				" " . substr( $ts, 0, 4 );
		}
		return $d;
	}

	/** @inheritDoc */
	public function timeanddate( $ts, $adj = false, $format = true, $tc = false ) {
		$datePreference = $this->dateFormat( $format );
		if ( $datePreference == 'ISO 8601' || $datePreference == 'walloon short' ) {
			return parent::timeanddate( $ts, $adj, $format, $tc );
		}

		# Walloon 'dmy' format
		return $this->date( $ts, $adj, $format, $tc ) . ' a ' .
			$this->time( $ts, $adj, $format, $tc );
	}
}
