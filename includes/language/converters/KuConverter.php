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

use MediaWiki\Language\ReplacementArray;

/**
 * Kurdish converter routines.
 *
 * @ingroup Languages
 */
class KuConverter extends LanguageConverterSpecific {

	private const ARABIC_TO_LATIN = [
		'ب' => 'b', 'ج' => 'c', 'چ' => 'ç', 'د' => 'd', 'ف' => 'f', 'گ' => 'g', 'ھ' => 'h',
		'ہ' => 'h', 'ه' => 'h', 'ح' => 'h', 'ژ' => 'j', 'ك' => 'k', 'ک' => 'k', 'ل' => 'l',
		'م' => 'm', 'ن' => 'n', 'پ' => 'p', 'ق' => 'q', 'ر' => 'r', 'س' => 's', 'ش' => 'ş',
		'ت' => 't', 'ڤ' => 'v', 'خ' => 'x', 'غ' => 'x', 'ز' => 'z',

		/* full and semi-vowel */
		'ڵ' => 'll', # ll
		'ڕ' => 'rr', # rr
		'ا' => 'a',
		'ە' => 'e',
		'ه‌' => 'e', # with one non-joiner
		'ه‌‌' => 'e', # with two non-joiners
		'ة' => 'e',
		'ێ' => 'ê',
		'ي' => 'î',
		'ی' => 'î', # U+06CC  db 8c  ARABIC LETTER FARSI YEH
		'ى' => 'î', # U+0649  d9 89  ARABIC LETTER ALEF MAKSURA
		'ۆ' => 'o',
		'و' => 'w',
		'ئ' => '', # initial hemze should not be shown
		'،' => ',',
		'ع' => '\'', # ayn
		'؟' => '?',

		# digits
		'٠' => '0', # U+0660
		'١' => '1', # U+0661
		'٢' => '2', # U+0662
		'٣' => '3', # U+0663
		'٤' => '4', # U+0664
		'٥' => '5', # U+0665
		'٦' => '6', # U+0666
		'٧' => '7', # U+0667
		'٨' => '8', # U+0668
		'٩' => '9', # U+0669
	];

	private const LATIN_TO_ARABIC = [
		'b' => 'ب', 'c' => 'ج', 'ç' => 'چ', 'd' => 'د', 'f' => 'ف', 'g' => 'گ',
		'h' => 'ه', 'j' => 'ژ', 'k' => 'ک', 'l' => 'ل',
		'm' => 'م', 'n' => 'ن', 'p' => 'پ', 'q' => 'ق', 'r' => 'ر', 's' => 'س', 'ş' => 'ش',
		't' => 'ت', 'v' => 'ڤ', 'w' => 'و',
		'x' => 'خ', 'y' => 'ی', 'z' => 'ز',

		'ḧ' => 'ح', 'ẍ' => 'غ',

		'B' => 'ب', 'C' => 'ج', 'Ç' => 'چ', 'D' => 'د', 'F' => 'ف', 'G' => 'گ',
		'H' => 'ح', 'J' => 'ژ', 'K' => 'ک', 'L' => 'ل',
		'M' => 'م', 'N' => 'ن', 'P' => 'پ', 'Q' => 'ق', 'R' => 'ر', 'S' => 'س', 'Ş' => 'ش',
		'T' => 'ت', 'V' => 'ڤ', 'W' => 'و',
		'X' => 'خ', 'Y' => 'ی', 'Z' => 'ز',

		'Ḧ' => 'ح', 'Ẍ' => 'غ',

		',' => '،',
		'?' => '؟'
	];

	private const HEMZE = 'ئ';

	private const LATIN_DIGITS = [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ];

	private const LATIN_TO_ARABIC_VOWELS = [
		// minuscule vowels
		'a' => 'ا',
		'e' => 'ە',
		'ê' => 'ێ',
		'i' => '',
		'î' => 'ی',
		'o' => 'ۆ',
		'u' => 'و',
		'û' => 'وو',
		// capital vowels
		'A' => 'ا',
		'E' => 'ە',
		'Ê' => 'ێ',
		'I' => '',
		'Î' => 'ی',
		'O' => 'ۆ',
		'U' => 'و',
		'Û' => 'وو'
	];

	public function getMainCode(): string {
		return 'ku';
	}

	public function getLanguageVariants(): array {
		return [ 'ku', 'ku-arab', 'ku-latn' ];
	}

	public function getVariantsFallbacks(): array {
		return [
			'ku' => 'ku-latn',
			'ku-arab' => 'ku-latn',
			'ku-latn' => 'ku-arab',
		];
	}

	protected function loadDefaultTables(): array {
		return [
			'ku-latn' => new ReplacementArray( self::ARABIC_TO_LATIN ),
			'ku-arab' => new ReplacementArray(),
			'ku' => new ReplacementArray()
		];
	}

	/** @inheritDoc */
	public function translate( $text, $toVariant ) {
		if ( $toVariant == 'ku-arab' ) {
			$chars = mb_str_split( $text );
			$length = count( $chars );
			$inWord = false;
			$prevWasVowel = false;
			for ( $i = 0; $i < $length; $i++ ) {
				$ch = $chars[$i];
				$isVowel = array_key_exists( $ch, self::LATIN_TO_ARABIC_VOWELS );
				$exists = array_key_exists( $ch, self::LATIN_TO_ARABIC );
				if ( !$exists && !$isVowel && !in_array( $ch, self::LATIN_DIGITS ) ) {
					$inWord = false;
					$prevWasVowel = false;
					continue;
				}
				if ( $isVowel ) {
					if ( !$inWord || $prevWasVowel ) {
						$chars[$i] = self::HEMZE . self::LATIN_TO_ARABIC_VOWELS[$ch];
					} else {
						$chars[$i] = self::LATIN_TO_ARABIC_VOWELS[$ch];
					}
					$prevWasVowel = true;
				} else {
					if ( $exists ) {
						$chars[$i] = self::LATIN_TO_ARABIC[$ch];
					}
					$prevWasVowel = false;
				}
				$inWord = true;
			}
			return implode( '', $chars );
		} else {
			$this->loadTables();

			if ( !isset( $this->mTables[$toVariant] ) ) {
				throw new LogicException( 'Broken variant table: ' . implode( ',', array_keys( $this->mTables ) ) );
			}

			return parent::translate( $text, $toVariant );
		}
	}
}
