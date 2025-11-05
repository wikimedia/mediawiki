<?php
/**
 * @license GPL-2.0-or-later
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
