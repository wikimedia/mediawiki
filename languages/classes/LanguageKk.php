<?php
/**
 * Kazakh (Қазақша) specific code.
 *
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
 * @ingroup Language
 */

/**
 * class that handles Cyrillic, Latin and Arabic scripts for Kazakh
 * right now it only distinguish kk_cyrl, kk_latn, kk_arab and kk_kz, kk_tr, kk_cn.
 *
 * @ingroup Language
 */
class LanguageKk extends LanguageKk_cyrl {
	/**
	 * It fixes issue with ucfirst for transforming 'i' to 'İ'
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public function ucfirst( $string ) {
		if ( substr( $string, 0, 1 ) === 'i' ) {
			$variant = $this->getPreferredVariant();
			if ( $variant == 'kk-latn' || $variant == 'kk-tr' ) {
				return 'İ' . substr( $string, 1 );
			}
		}
		return parent::ucfirst( $string );
	}

	/**
	 * It fixes issue with  lcfirst for transforming 'I' to 'ı'
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public function lcfirst( $string ) {
		if ( substr( $string, 0, 1 ) === 'I' ) {
			$variant = $this->getPreferredVariant();
			if ( $variant == 'kk-latn' || $variant == 'kk-tr' ) {
				return 'ı' . substr( $string, 1 );
			}
		}
		return parent::lcfirst( $string );
	}

	/**
	 * @param string $word
	 * @param string $case
	 * @return string
	 */
	public function convertGrammar( $word, $case ) {
		$variant = $this->getPreferredVariant();
		switch ( $variant ) {
			case 'kk-arab':
			case 'kk-cn':
				$word = parent::convertGrammarKk_arab( $word, $case );
				break;
			case 'kk-latn':
			case 'kk-tr':
				$word = parent::convertGrammarKk_latn( $word, $case );
				break;
			case 'kk-cyrl':
			case 'kk-kz':
			case 'kk':
			default:
				$word = parent::convertGrammarKk_cyrl( $word, $case );
		}

		return $word;
	}
}
