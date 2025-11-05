<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Chinese-specific code.
 *
 * This handles both Traditional and Simplified Chinese.
 * Right now, we distinguish `zh_hans`, `zh_hant`, `zh_cn`, `zh_tw`, `zh_sg`,
 * and `zh_hk`.
 *
 * @ingroup Languages
 */
class LanguageZh extends LanguageZh_hans {
	/**
	 * Add a formfeed character between each non-ASCII character, so that
	 * "word-level" diffs will effectively operate on a character level. The FF
	 * characters are stripped out by unsegmentForDiff().
	 *
	 * We use FF because it is the least used character that is matched by
	 * PCRE's \s class.
	 *
	 * In the unlikely event that an FF character appears in the input, it will
	 * be displayed in the diff as a replacement character.
	 *
	 * @param string $text
	 * @return string
	 */
	public function segmentForDiff( $text ) {
		$text = str_replace( "\x0c", "\u{FFFD}", $text );
		return preg_replace( '/[\xc0-\xff][\x80-\xbf]*/', "\x0c$0", $text );
	}

	/** @inheritDoc */
	public function unsegmentForDiff( $text ) {
		return str_replace( "\x0c", '', $text );
	}

	/** @inheritDoc */
	protected function getSearchIndexVariant() {
		return 'zh-hans';
	}

	/** @inheritDoc */
	public function convertForSearchResult( $termsArray ) {
		$terms = implode( '|', $termsArray );
		$terms = self::convertDoubleWidth( $terms );
		$terms = implode( '|', $this->getConverterInternal()->autoConvertToAllVariants( $terms ) );
		return array_unique( explode( '|', $terms ) );
	}
}
