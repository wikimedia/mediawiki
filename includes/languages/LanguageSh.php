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

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Serbo-Croatian (srpskohrvatski / српскохрватски)
 *
 * @ingroup Languages
 */
class LanguageSh extends Language {
	/**
	 * Cases: genitiv, dativ, akuzativ, vokativ, instrumental, lokativ
	 *
	 * @inheritDoc
	 */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );

		if ( !isset( $grammarForms['sh'] ) ) {
			return $word;
		}

		if ( isset( $grammarForms['sh'][$case][$word] ) ) {
			return $grammarForms['sh'][$case][$word];
		}

		# if the word is not supported (i.e. there's no entry for any case),
		# use a descriptive declension for it
		$isWordSupported = false;
		foreach ( $grammarForms['sh'] as $caseForms ) {
			if ( isset( $caseForms[$word] ) ) {
				$isWordSupported = true;
				break;
			}
		}

		# descriptive declension for unknown projects
		if ( !$isWordSupported && isset( $grammarForms['sh'][$case]['projekt'] ) ) {
			return $grammarForms['sh'][$case]['projekt'] . ' ' . $word;
		}

		# this will return the original value for 'nominativ' (nominative)
		return $word;
	}
}
