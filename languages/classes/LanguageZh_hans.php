<?php
/**
 * Simplified Chinese specific code.
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
 * @file
 * @ingroup Language
 */

/**
 * Simplified Chinese
 *
 * @ingroup Language
 */
class LanguageZh_hans extends Language {

	/**
	 * @return bool
	 */
	function hasWordBreaks() {
		return false;
	}

	/**
	 * Eventually this should be a word segmentation;
	 * for now just treat each character as a word.
	 * @todo FIXME: Only do this for Han characters...
	 *
	 * @param $string string
	 *
	 * @return string
	 */
	function segmentByWord( $string ) {
		$reg = "/([\\xc0-\\xff][\\x80-\\xbf]*)/";
		$s = self::insertSpace( $string, $reg );
		return $s;
	}

	/**
	 * @param $s
	 * @return string
	 */
	function normalizeForSearch( $s ) {
		wfProfileIn( __METHOD__ );

		// Double-width roman characters
		$s = parent::normalizeForSearch( $s );
		$s = trim( $s );
		$s = $this->segmentByWord( $s );

		wfProfileOut( __METHOD__ );
		return $s;
	}

	/**
	 * Takes a number of seconds and turns it into a text using values such as hours and minutes.
	 *
	 * @since 1.21
	 *
	 * @param integer $seconds The amount of seconds.
	 * @param array $chosenIntervals The intervals to enable.
	 *
	 * @return string
	 */
	public function formatDuration( $seconds, array $chosenIntervals = array() ) {
		if ( empty( $chosenIntervals ) ) {
			$chosenIntervals = array( 'centuries', 'years', 'days', 'hours', 'minutes', 'seconds' );
		}

		$intervals = $this->getDurationIntervals( $seconds, $chosenIntervals );

		$segments = array();

		foreach ( $intervals as $intervalName => $intervalValue ) {
			// Messages: duration-seconds, duration-minutes, duration-hours, duration-days, duration-weeks,
			// duration-years, duration-decades, duration-centuries, duration-millennia
			$message = wfMessage( 'duration-' . $intervalName )->numParams( $intervalValue );
			$segments[] = $message->inLanguage( $this )->escaped();
		}

		return implode( '', $segments );
	}
}
