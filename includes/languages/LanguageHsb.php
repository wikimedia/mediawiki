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
 * Upper Sorbian (Hornjoserbsce)
 *
 * @ingroup Languages
 */
class LanguageHsb extends Language {

	/** @inheritDoc */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['hsb'][$case][$word] ) ) {
			return $grammarForms['hsb'][$case][$word];
		}

		switch ( $case ) {
			case 'instrumental': # instrumental
				$word = 'z ' . $word;
				break;
			case 'lokatiw': # lokatiw
				$word = 'wo ' . $word;
				break;
		}

		# this will return the original value for 'nominatiw' (nominativ) and
		# all undefined case values.
		return $word;
	}
}
