<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;

/**
 * Turkish (Türkçe)
 *
 * The Turkish language, like other Turkic languages, distinguishes
 * a dotted letter 'i' from a dotless letter 'ı' (U+0131 LATIN SMALL LETTER DOTLESS I).
 * In these languages, each has an equivalent uppercase mapping:
 * ı (U+0131 LATIN SMALL LETTER DOTLESS I) -> I (U+0049 LATIN CAPITAL LETTER I),
 * i (U+0069 LATIN SMALL LETTER I) -> İ (U+0130 LATIN CAPITAL LETTER I WITH DOT ABOVE).
 *
 * Unicode CaseFolding.txt defines these mappings as type 'T', which means that
 * they are only for the Turkic languages, tr and az. PHP ignores these mappings,
 * so we have to override the ucfirst and lcfirst methods.
 *
 * See https://en.wikipedia.org/wiki/Dotted_and_dotless_I and T30040
 *
 * @ingroup Languages
 */
class LanguageTr extends Language {

	private const UC = [ 'I', 'İ' ];
	private const LC = [ 'ı', 'i' ];

	/** @inheritDoc */
	public function ucfirst( $str ) {
		$first = mb_substr( $str, 0, 1 );
		if ( in_array( $first, self::LC ) ) {
			$first = str_replace( self::LC, self::UC, $first );
			return $first . mb_substr( $str, 1 );
		}
		return parent::ucfirst( $str );
	}

	/** @inheritDoc */
	public function lcfirst( $str ) {
		$first = mb_substr( $str, 0, 1 );
		if ( in_array( $first, self::UC ) ) {
			$first = str_replace( self::UC, self::LC, $first );
			return $first . mb_substr( $str, 1 );
		}
		return parent::lcfirst( $str );
	}

}
