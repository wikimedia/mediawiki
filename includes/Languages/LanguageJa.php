<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;

/**
 * Japanese (日本語)
 *
 * @ingroup Languages
 */
class LanguageJa extends Language {

	/** Space string for hiragana - unicode range U3040-309f */
	private const WORD_SEG_HIRAGANA = '(?:\xe3(?:\x81[\x80-\xbf]|\x82[\x80-\x9f]))';

	/** Space string for katakana - unicode range U30a0-30ff */
	private const WORD_SEG_KATAKANA = '(?:\xe3(?:\x82[\xa0-\xbf]|\x83[\x80-\xbf]))';

	/** Space string for kanji - unicode range U3200-9999 = \xe3\x88\x80-\xe9\xa6\x99 */
	private const WORD_SEG_KANJI =
		'(?:\xe3[\x88-\xbf][\x80-\xbf]|[\xe4-\xe8][\x80-\xbf]{2}|\xe9[\x80-\xa5][\x80-\xbf]|\xe9\xa6[\x80-\x99])';

	private const WORD_SEGMENTATION_REGEX =
		'/(' . self::WORD_SEG_HIRAGANA . '+|' . self::WORD_SEG_KATAKANA . '+|' . self::WORD_SEG_KANJI . '+)/';

	/** @inheritDoc */
	public function segmentByWord( $string ) {
		return self::insertSpace( $string, self::WORD_SEGMENTATION_REGEX );
	}

	/**
	 * Italic is not appropriate for Japanese script.
	 * Unfortunately, most browsers do not recognise this, and render `<em>` as italic.
	 *
	 * @param string $text
	 * @return string
	 */
	public function emphasize( $text ) {
		return $text;
	}
}
