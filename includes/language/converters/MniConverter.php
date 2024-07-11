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
/**
 * Meitei specific converter routines.
 *
 * @ingroup Languages
 */
class MniConverter extends LanguageConverterSpecific {
	private $O = 'ꯑ';
	private $OO = 'ꯑꯣ';
	private $U = 'ꯎ';
	private $EE = 'ꯑꯤ';
	private $YA = 'ꯌ';
	private $Y_ = 'য';
	private $WA = 'ꯋ';
	private $BA = 'ꯕ';
	private $NA_ = 'ꯟ';
	private $NA = 'ꯅ';
	private $DIACRITIC_AA = 'ꯥ';
	private $HALANTA = '꯭';
	private $SKIP = '';
	private $PERIOD = '꯫';
	private $PA_ = 'ꯞ';
	private $DIACRITICS_WITH_O = [
		'ꯣ' => 'ো',
		'ꯤ' => 'ী',
		'ꯥ' => 'া',
		'ꯦ' => 'ে',
		'ꯧ' => 'ৌ',
		'ꯩ' => 'ৈ',
		'ꯪ' => 'ং',
	];
	private $CONJUGATE_WITH_O = [
		'ꯑꯣ' => 'ও',
		'ꯑꯤ' => 'ঈ',
		'ꯑꯥ' => 'আ',
		'ꯑꯦ' => 'এ',
		'ꯑꯧ' => 'ঔ',
		'ꯑꯩ' => 'ঐ',
		'ꯑꯪ' => 'অং',
	];
	private $NOT_WEIRD_AFTER_NA_ = [ 'ꯇ', 'ꯊ', 'ꯗ', 'ꯙ', 'ꯟ', 'ꯕ', 'ꯌ', 'ꯁ' ];
	private $NUMERALS = [
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
	private $HALANTA_CONSONANTS = [
		'ꯟ' => 'ন্',
		'ꯛ' => 'ক্',
		'ꯝ' => 'ম্',
		'ꯡ' => 'ং',
		'ꯜ' => 'ল্',
		'ꯠ' => 'ৎ',
		'ꯞ' => 'প্',
	];
	private $HALANTA_CONSONANTS_TO_NORMAL = [
		'ꯟ' => 'ন',
		'ꯛ' => 'ক',
		'ꯝ' => 'ম',
		'ꯡ' => 'ং',
		'ꯜ' => 'ল',
		'ꯠ' => 'ৎ',
		'ꯞ' => 'প',
	];
	private $NON_WORD_CHARACTER_PATTERN = "/[\s꯫\p{P}<>=\-\|$+^~]+?/u";
	private $CONSONANTS = [
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
	private $VOWELS = [
		'ꯑ' => 'অ',
		'ꯏ' => 'ই',
		'ꯎ' => 'উ',
		'ꯢ' => 'ই',
		'ꯨ' => 'ু',
	];
	private $MTEI_TO_BENG_MAP = [
		'꯫' => '।',
		'꯭' => '্',
	];

	public function __construct( $_ ) {
		parent::__construct( $_ );
		$this->VOWELS += $this->DIACRITICS_WITH_O + $this->CONJUGATE_WITH_O;
		$this->CONSONANTS += $this->HALANTA_CONSONANTS;
		$this->MTEI_TO_BENG_MAP += $this->VOWELS + $this->CONSONANTS;
		$this->MTEI_TO_BENG_MAP += $this->NUMERALS;
	}

	private function isBeginning( $position, $text ) {
		$at_first = $position === 0;
		return $at_first || preg_match( $this->NON_WORD_CHARACTER_PATTERN, $text[$position - 1] );
	}

	private function isEndOfWord( $char ) {
		if ( $char === $this->PERIOD ) {
			return true;
		}
		$status = preg_match( $this->NON_WORD_CHARACTER_PATTERN, $char, $matches );
		return count( $matches ) > 0;
	}

	private function mteiToBengali( $text ) {
		$chars = mb_str_split( $text );
		$l = count( $chars );
		$i = 0;
		while ( $i < $l ) {
			$char = $chars[$i];
			if (
				$char === $this->O &&
				$i + 1 < $l &&
				array_key_exists( $chars[ $i + 1 ], $this->DIACRITICS_WITH_O )
			) {
				/**
				 * We have only 3 true vowels,
				 * ꯑ(a), ꯏ(i), ꯎ (u)
				 * Others are just extension from "a" by mixing with diacritics
				 */
				yield $this->CONJUGATE_WITH_O[$char . $chars[ $i + 1 ]];
				$i += 1;
			} elseif (
				$char === $this->HALANTA &&
				$i > 0 &&
				array_key_exists( $chars[ $i - 1 ], $this->HALANTA_CONSONANTS )
			) {
				// Remove halanta if the consonant has halanta already
				yield $this->SKIP;
			} elseif (
				array_key_exists( $char, $this->HALANTA_CONSONANTS ) &&
				( $i === $l - 1 || ( $i + 1 < $l &&
					$this->isEndOfWord( $chars[ $i + 1 ] )
				) )
			) {
				// Remove halanta if this is the last character of the word
				yield $this->HALANTA_CONSONANTS_TO_NORMAL[$char];
			} elseif ( $char === $this->YA &&
				$i > 0 && $chars[ $i - 1 ] === $this->HALANTA ) {
				// য + ্ = য়
				yield $this->Y_;
			} elseif (
				$char === $this->WA &&
				$i - 2 >= 0 && $chars[ $i - 1 ] === $this->HALANTA &&
				array_key_exists( $chars[ $i - 2 ], $this->CONSONANTS )
			) {
				// ব + ্ + র = ব্র
				yield $this->CONSONANTS[$this->BA];
			} elseif (
				$char === $this->PA_ && $i + 1 < $l && $chars[ $i + 1 ] === 'ꯀ'
			) {
				// do not conjugate with halanta if it's followed by "ক"
				yield $this->HALANTA_CONSONANTS_TO_NORMAL[$char];
			} elseif (
				$char === $this->NA_ &&
				$i + 1 < $l &&
				!in_array( $chars[ $i + 1 ], $this->NOT_WEIRD_AFTER_NA_ ) &&
				array_key_exists( $chars[ $i + 1 ], $this->CONSONANTS )
			) {
				/**
				 * ন্ / ণ্ + any consonant
				 * (except, ট, ঠ, ড, ঢ, , ত, থ, দ, ধ, ন, ব, য, য়) = weird
				 * Any consonant + ্ + ন =  maybe ok
				 */
				yield $this->MTEI_TO_BENG_MAP[$this->NA];
				$i += 1;
				continue;
			} elseif ( $char === $this->U && !$this->isBeginning( $i, $text ) ) {
				// উ/ঊ in the middle of words are often replaced by ও
				yield $this->MTEI_TO_BENG_MAP[$this->OO];
			} elseif ( $char === $this->O &&
				$i + 2 < $l && $chars[$i + 1] === $this->EE[0] && $chars[ $i + 2 ] === $this->EE[1] ) {
				/**
				 * Instead of হাঈবা, people love to use হায়বা.
				 * But this is only in the case when ee or ya is
				 * in the middle of the words,
				 * never to do it if it's in the beginning.
				 */
				yield $this->MTEI_TO_BENG_MAP[$this->YA];
			} elseif (
				!array_key_exists( $char, $this->HALANTA_CONSONANTS ) &&
				array_key_exists( $char, $this->CONSONANTS ) &&
				( $i === $l - 1 || ( $i + 1 < $l &&
					$this->isEndOfWord( $chars[ $i + 1 ] )
				) )
			) {
				// Consonants without halantas should end with diacritics of aa sound everytime.
				yield $this->MTEI_TO_BENG_MAP[$char] . $this->MTEI_TO_BENG_MAP[$this->DIACRITIC_AA];
			} else {
				yield (
					array_key_exists( $char, $this->MTEI_TO_BENG_MAP ) ?
					$this->MTEI_TO_BENG_MAP[$char] : $char
				);
			}
			$i += 1;
		}
	}

	public function transliterate( $text ) {
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
