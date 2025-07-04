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
 * Irish (Gaeilge)
 *
 * @ingroup Languages
 */
class LanguageGa extends Language {
	/** @inheritDoc */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['ga'][$case][$word] ) ) {
			return $grammarForms['ga'][$case][$word];
		}

		switch ( $case ) {
			case 'ainmlae':
				switch ( $word ) {
					case 'an Domhnach':
						$word = 'Dé Domhnaigh';
						break;
					case 'an Luan':
						$word = 'Dé Luain';
						break;
					case 'an Mháirt':
						$word = 'Dé Mháirt';
						break;
					case 'an Chéadaoin':
						$word = 'Dé Chéadaoin';
						break;
					case 'an Déardaoin':
						$word = 'Déardaoin';
						break;
					case 'an Aoine':
						$word = 'Dé hAoine';
						break;
					case 'an Satharn':
						$word = 'Dé Sathairn';
						break;
				}
		}
		return $word;
	}

}
