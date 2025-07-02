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
 * @file MniConverter.php
 * @author Nokib Sarkar
 * @author Haoreima
 */

use MediaWiki\Language\ReplacementArray;

/**
 * Meitei specific converter routines.
 *
 * @ingroup Languages
 */
class MniConverter extends LanguageConverterSpecific {
	private const O = 'ꯑ';
	private const OO = 'ꯑꯣ';
	private const U = 'ꯎ';
	private const EE = 'ꯑꯤ';
	private const YA = 'ꯌ';
	private const Y_ = 'য';
	private const WA = 'ꯋ';
	private const BA = 'ꯕ';
	private const NA_ = 'ꯟ';
	private const NA = 'ꯅ';
	private const DIACRITIC_AA = 'ꯥ';
	private const HALANTA = '꯭';
	private const SKIP = '';
	private const PERIOD = '꯫';
	private const PA_ = 'ꯞ';
	private const DIACRITICS_WITH_O = [
		'ꯣ' => 'ো',
		'ꯤ' => 'ী',
		'ꯥ' => 'া',
		'ꯦ' => 'ে',
		'ꯧ' => 'ৌ',
		'ꯩ' => 'ৈ',
		'ꯪ' => 'ং',
	];
	private const CONJUGATE_WITH_O = [
		'ꯑꯣ' => 'ও',
		'ꯑꯤ' => 'ঈ',
		'ꯑꯥ' => 'আ',
		'ꯑꯦ' => 'এ',
		'ꯑꯧ' => 'ঔ',
		'ꯑꯩ' => 'ঐ',
		'ꯑꯪ' => 'অং',
	];
	private const NOT_WEIRD_AFTER_NA_ = [ 'ꯇ', 'ꯊ', 'ꯗ', 'ꯙ', 'ꯟ', 'ꯕ', 'ꯌ', 'ꯁ' ];
	private const NUMERALS = [
		'꯰' => '০',
		'꯱' => '১',
		'꯲' => '২',
		'꯳' => '৩',
		'꯴' => '৪',
		'꯵' => '৫',
		'꯶' => '৬',
		'꯷' => '৭',
		'꯸' => '৮',
		'꯹' => '৯',
	];
	private const HALANTA_CONSONANTS = [
		'ꯟ' => 'ন্',
		'ꯛ' => 'ক্',
		'ꯝ' => 'ম্',
		'ꯡ' => 'ং',
		'ꯜ' => 'ল্',
		'ꯠ' => 'ৎ',
		'ꯞ' => 'প্',
	];
	private const HALANTA_CONSONANTS_TO_NORMAL = [
		'ꯟ' => 'ন',
		'ꯛ' => 'ক',
		'ꯝ' => 'ম',
		'ꯡ' => 'ং',
		'ꯜ' => 'ল',
		'ꯠ' => 'ৎ',
		'ꯞ' => 'প',
	];
	private const NON_WORD_CHARACTER_PATTERN = "/[\s꯫\p{P}<>=\-\|$+^~]+?/u";
	private const CONSONANTS = self::HALANTA_CONSONANTS + [
		'ꯀ' => 'ক',
		'ꯈ' => 'খ',
		'ꯒ' => 'গ',
		'ꯘ' => 'ঘ',
		'ꯉ' => 'ঙ',
		'ꯆ' => 'চ',
		'ꯖ' => 'জ',
		'ꯓ' => 'ঝ',
		'ꯇ' => 'ত',
		'ꯊ' => 'থ',
		'ꯗ' => 'দ',
		'ꯙ' => 'ধ',
		'ꯅ' => 'ন',
		'ꯄ' => 'প',
		'ꯐ' => 'ফ',
		'ꯕ' => 'ব',
		'ꯚ' => 'ভ',
		'ꯃ' => 'ম',
		'ꯌ' => 'য়',
		'ꯔ' => 'র',
		'ꯂ' => 'ল',
		'ꯋ' => 'ৱ',
		'ꫩ' => 'শ',
		'ꫪ' => 'ষ',
		'ꯁ' => 'স',
		'ꯍ' => 'হ',
	];
	private const VOWELS = [
		'ꯑ' => 'অ',
		'ꯏ' => 'ই',
		'ꯎ' => 'উ',
		'ꯢ' => 'ই',
		'ꯨ' => 'ু',
	];
	private const MTEI_TO_BENG_MAP_EXTRA = [
		'꯫' => '।',
		'꯭' => '্',
	];
	private const MTEI_TO_BENG_MAP =
		self::VOWELS +
		self::DIACRITICS_WITH_O +
		self::CONJUGATE_WITH_O +
		self::CONSONANTS +
		self::NUMERALS +
		self::MTEI_TO_BENG_MAP_EXTRA;

	private function isBeginning( int $position, string $text ): bool {
		$at_first = $position === 0;
		return $at_first || preg_match( self::NON_WORD_CHARACTER_PATTERN, $text[$position - 1] );
	}

	private function isEndOfWord( string $char ): bool {
		if ( $char === self::PERIOD ) {
			return true;
		}
		$status = preg_match( self::NON_WORD_CHARACTER_PATTERN, $char, $matches );
		return count( $matches ) > 0;
	}

	private function mteiToBengali( string $text ): iterable {
		$chars = mb_str_split( $text );
		$l = count( $chars );
		$i = 0;
		while ( $i < $l ) {
			$char = $chars[$i];
			if (
				$char === self::O &&
				$i + 1 < $l &&
				array_key_exists( $chars[ $i + 1 ], self::DIACRITICS_WITH_O )
			) {
				/**
				 * We have only 3 true vowels,
				 * ꯑ(a), ꯏ(i), ꯎ (u)
				 * Others are just extension from "a" by mixing with diacritics
				 */
				yield self::CONJUGATE_WITH_O[$char . $chars[ $i + 1 ]];
				$i++;
			} elseif (
				$char === self::HALANTA &&
				$i > 0 &&
				array_key_exists( $chars[ $i - 1 ], self::HALANTA_CONSONANTS )
			) {
				// Remove halanta if the consonant has halanta already
				yield self::SKIP;
			} elseif (
				array_key_exists( $char, self::HALANTA_CONSONANTS ) &&
				( $i === $l - 1 || ( $i + 1 < $l &&
					$this->isEndOfWord( $chars[ $i + 1 ] )
				) )
			) {
				// Remove halanta if this is the last character of the word
				yield self::HALANTA_CONSONANTS_TO_NORMAL[$char];
			} elseif ( $char === self::YA &&
				$i > 0 && $chars[ $i - 1 ] === self::HALANTA ) {
				// য + ্ = য়
				yield self::Y_;
			} elseif (
				$char === self::WA &&
				$i - 2 >= 0 && $chars[ $i - 1 ] === self::HALANTA &&
				array_key_exists( $chars[ $i - 2 ], self::CONSONANTS )
			) {
				// ব + ্ + র = ব্র
				yield self::CONSONANTS[self::BA];
			} elseif (
				$char === self::PA_ && $i + 1 < $l && $chars[ $i + 1 ] === 'ꯀ'
			) {
				// do not conjugate with halanta if it's followed by "ক"
				yield self::HALANTA_CONSONANTS_TO_NORMAL[$char];
			} elseif (
				$char === self::NA_ &&
				$i + 1 < $l &&
				!in_array( $chars[ $i + 1 ], self::NOT_WEIRD_AFTER_NA_ ) &&
				array_key_exists( $chars[ $i + 1 ], self::CONSONANTS )
			) {
				/**
				 * ন্ / ণ্ + any consonant
				 * (except, ট, ঠ, ড, ঢ, , ত, থ, দ, ধ, ন, ব, য, য়) = weird
				 * Any consonant + ্ + ন = maybe ok
				 */
				yield self::MTEI_TO_BENG_MAP[self::NA];
				$i++;
				continue;
			} elseif ( $char === self::U && !$this->isBeginning( $i, $text ) ) {
				// উ/ঊ in the middle of words are often replaced by ও
				yield self::MTEI_TO_BENG_MAP[self::OO];
			} elseif ( $char === self::O &&
				$i + 2 < $l && $chars[$i + 1] === self::EE[0] && $chars[ $i + 2 ] === self::EE[1] ) {
				/**
				 * Instead of হাঈবা, people love to use হায়বা.
				 * But this is only in the case when ee or ya is
				 * in the middle of the words,
				 * never to do it if it's in the beginning.
				 */
				yield self::MTEI_TO_BENG_MAP[self::YA];
			} elseif (
				!array_key_exists( $char, self::HALANTA_CONSONANTS ) &&
				array_key_exists( $char, self::CONSONANTS ) &&
				( $i === $l - 1 || ( $i + 1 < $l &&
					$this->isEndOfWord( $chars[ $i + 1 ] )
				) )
			) {
				// Consonants without halantas should end with diacritics of aa sound everytime.
				yield self::MTEI_TO_BENG_MAP[$char] . self::MTEI_TO_BENG_MAP[self::DIACRITIC_AA];
			} else {
				yield (
					array_key_exists( $char, self::MTEI_TO_BENG_MAP ) ?
					self::MTEI_TO_BENG_MAP[$char] : $char
				);
			}
			$i++;
		}
	}

	public function transliterate( string $text ): string {
		$transliterated = '';
		foreach ( $this->mteiToBengali( $text ) as $char ) {
			$transliterated .= $char;
		}
		return $transliterated;
	}

	public function getMainCode(): string {
		return 'mni';
	}

	public function getLanguageVariants(): array {
		return [ 'mni', 'mni-beng' ];
	}

	public function getVariantsFallbacks(): array {
		return [
			'mni-beng' => 'mni'
		];
	}

	protected function loadDefaultTables(): array {
		return [
			'mni' => new ReplacementArray(),
			'mni-beng' => new ReplacementArray(),
		];
	}

	/**
	 * Transliterates text into Bangla Script. This allows developers to test the language variants
	 * functionality and user interface without having to switch wiki language away from default.
	 * This method also processes custom conversion rules to allow testing these parts of the
	 * language converter as well.
	 *
	 * @param string $text
	 * @param string $toVariant
	 * @return string
	 */
	public function translate( $text, $toVariant ) {
		if ( $toVariant === 'mni-beng' ) {
			return $this->transliterate( $text );
		}
		return $text;
	}
}
