<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Ruben Vardanyan (Me@RubenVardanyan.com)
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Armenian (Հայերեն)
 *
 * @ingroup Languages
 */
class LanguageHy extends Language {

	/** @inheritDoc */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['hy'][$case][$word] ) ) {
			return $grammarForms['hy'][$case][$word];
		}

		# These rules are not perfect, but they are currently only used for site names so it doesn't
		# matter if they are wrong sometimes. Just add a special case for your site name if necessary.

		# join and array_slice instead mb_substr
		$ar = [];
		preg_match_all( '/./us', $word, $ar );
		if ( !preg_match( "/[a-zA-Z_]/u", $word ) ) {
			switch ( $case ) {
				case 'genitive': # սեռական հոլով
					if ( implode( '', array_slice( $ar[0], -1 ) ) == 'ա' ) {
						$word = implode( '', array_slice( $ar[0], 0, -1 ) ) . 'այի';
					} elseif ( implode( '', array_slice( $ar[0], -1 ) ) == 'ո' ) {
						$word = implode( '', array_slice( $ar[0], 0, -1 ) ) . 'ոյի';
					} elseif ( implode( '', array_slice( $ar[0], -4 ) ) == 'գիրք' ) {
						$word = implode( '', array_slice( $ar[0], 0, -4 ) ) . 'գրքի';
					} else {
						$word .= 'ի';
					}
					break;
				case 'dative':  # Տրական հոլով
					# stub
					break;
				case 'accusative': # Հայցական հոլով
					# stub
					break;
				case 'instrumental':
					# stub
					break;
				case 'prepositional':
					# stub
					break;
			}
		}
		return $word;
	}
}
