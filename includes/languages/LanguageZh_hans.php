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
// phpcs:ignoreFile Squiz.Classes.ValidClassName.NotCamelCaps

use MediaWiki\Language\Language;

/**
 * Simplified Chinese
 *
 * @ingroup Languages
 */
class LanguageZh_hans extends Language {

	private const WORD_SEGMENTATION_REGEX = '/([\xc0-\xff][\x80-\xbf]*)/';

	/** @inheritDoc */
	public function hasWordBreaks() {
		return false;
	}

	/**
	 * @todo FIXME: Only do this for Han characters...
	 *
	 * @inheritDoc
	 */
	public function segmentByWord( $string ) {
		return self::insertSpace( $string, self::WORD_SEGMENTATION_REGEX );
	}

	public function normalizeForSearch( $s ) {
		// Double-width roman characters
		$s = parent::normalizeForSearch( $s );
		$s = trim( $s );
		return $this->segmentByWord( $s );
	}

	public function formatDuration( $seconds, array $chosenIntervals = [] ) {
		if ( !$chosenIntervals ) {
			$chosenIntervals = [ 'centuries', 'years', 'days', 'hours', 'minutes', 'seconds' ];
		}

		$intervals = $this->getDurationIntervals( $seconds, $chosenIntervals );

		$segments = [];

		foreach ( $intervals as $intervalName => $intervalValue ) {
			// Messages: duration-seconds, duration-minutes, duration-hours, duration-days, duration-weeks,
			// duration-years, duration-decades, duration-centuries, duration-millennia
			$message = wfMessage( 'duration-' . $intervalName )->numParams( $intervalValue );
			$segments[] = $message->inLanguage( $this )->escaped();
		}

		return implode( '', $segments );
	}
}
