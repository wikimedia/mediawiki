<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;

/**
 * Cantonese (粵語)
 *
 * @ingroup Languages
 */
class LanguageYue extends Language {

	private const WORD_SEGMENTATION_REGEX = '/([\xc0-\xff][\x80-\xbf]*)/';

	/** @inheritDoc */
	public function hasWordBreaks() {
		return false;
	}

	/**
	 * Eventually, this should be a word segmentation;
	 * but for now just treat each character as a word.
	 * @todo FIXME: Only do this for Han characters...
	 *
	 * @param string $string
	 * @return string
	 */
	public function segmentByWord( $string ) {
		return self::insertSpace( $string, self::WORD_SEGMENTATION_REGEX );
	}
}
