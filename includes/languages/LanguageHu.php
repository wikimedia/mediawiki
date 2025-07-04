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
 * Hungarian localisation for MediaWiki
 *
 * @ingroup Languages
 */
class LanguageHu extends Language {

	/** @inheritDoc */
	public function convertGrammar( $word, $case ) {
		$grammarForms = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms[$this->getCode()][$case][$word] ) ) {
			return $grammarForms[$this->getCode()][$case][$word];
		}

		switch ( $case ) {
			case 'rol':
				return $word . 'ról';
			case 'ba':
				return $word . 'ba';
			case 'k':
				return $word . 'k';
		}
		return '';
	}
}
