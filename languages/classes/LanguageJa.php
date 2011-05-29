<?php

/**
 * Japanese (日本語)
 *
 * @ingroup Language
 */
class LanguageJa extends Language {

	/**
	 * @param $string string
	 * @return string
	 */
	function segmentByWord( $string ) {
		// Strip known punctuation ?
		// $s = preg_replace( '/\xe3\x80[\x80-\xbf]/', '', $s ); # U3000-303f

		// Space strings of like hiragana/katakana/kanji
		$hiragana = '(?:\xe3(?:\x81[\x80-\xbf]|\x82[\x80-\x9f]))'; # U3040-309f
		$katakana = '(?:\xe3(?:\x82[\xa0-\xbf]|\x83[\x80-\xbf]))'; # U30a0-30ff
		$kanji = '(?:\xe3[\x88-\xbf][\x80-\xbf]'
			. '|[\xe4-\xe8][\x80-\xbf]{2}'
			. '|\xe9[\x80-\xa5][\x80-\xbf]'
			. '|\xe9\xa6[\x80-\x99])';
			# U3200-9999 = \xe3\x88\x80-\xe9\xa6\x99
		$reg = "/({$hiragana}+|{$katakana}+|{$kanji}+)/";
		$s = self::insertSpace( $string, $reg );
		return $s;
	}

	/**
	 * Italic is not appropriate for Japanese script
	 * Unfortunately most browsers do not recognise this, and render <em> as italic
	 *
	 * @param $text string
	 * @return string
	 */
	function emphasize( $text ) {
		return $text;
	}
}
