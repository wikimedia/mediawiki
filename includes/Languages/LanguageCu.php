<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Old Church Slavonic (Ѩзыкъ словѣньскъ)
 *
 * @ingroup Languages
 */
class LanguageCu extends Language {
	/** @inheritDoc */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );

		if ( isset( $grammarForms['сu'][$case][$word] ) ) {
			return $grammarForms['сu'][$case][$word];
		}

		# These rules are not perfect, but they are currently only used for
		# site names, so it doesn't matter if they are wrong sometimes.
		# Just add a special case for your site name if necessary.

		# join and array_slice instead mb_substr
		$ar = [];
		preg_match_all( '/./us', $word, $ar );
		if ( !preg_match( "/[a-zA-Z_]/u", $word ) ) {
			switch ( $case ) {
				case 'genitive': # родительный падеж
					// if ( ( implode( '', array_slice( $ar[0], -4 ) ) == 'вики' )
					//	|| ( implode( '', array_slice( $ar[0], -4 ) ) == 'Вики' )
					// ) {
					// }

					if ( implode( '', array_slice( $ar[0], -2 ) ) == 'ї' ) {
						return implode( '', array_slice( $ar[0], 0, -2 ) ) . 'їѩ';
					}
					break;
				case 'accusative': # винительный падеж
					# stub
					break;
			}
		}

		return $word;
	}
}
