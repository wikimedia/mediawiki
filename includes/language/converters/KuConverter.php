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

/**
 * Kurdish converter routines.
 *
 * @ingroup Languages
 */
class KuConverter extends LanguageConverterSpecific {

	/**
	 * @var string[]
	 */
	public $mArabicToLatin = [
		'ب' => 'b', 'ج' => 'c', 'چ' => 'ç', 'د' => 'd', 'ف' => 'f', 'گ' => 'g', 'ھ' => 'h',
		'ہ' => 'h', 'ه' => 'h', 'ح' => 'h', 'ژ' => 'j', 'ك' => 'k', 'ک' => 'k', 'ل' => 'l',
		'م' => 'm', 'ن' => 'n', 'پ' => 'p', 'ق' => 'q', 'ر' => 'r', 'س' => 's', 'ش' => 'ş',
		'ت' => 't', 'ڤ' => 'v', 'خ' => 'x', 'غ' => 'x', 'ز' => 'z',

		// ک و => ku -- ist richtig
		//  و ك=> ku -- ist auch richtig

		/* Doppel- und Halbvokale */
		'ڵ' => 'll', # ll
		'ڕ' => 'rr', # rr
		'ا' => 'a',
		# 'ئێ' => 'ê', # initial e
		'ە' => 'e',
		'ه‌' => 'e', # with one non-joiner
		'ه‌‌' => 'e', # with two non-joiner
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

	/**
	 * @var string[]
	 */
	public $mLatinToArabic = [
		'b' => 'ب', 'c' => 'ج', 'ç' => 'چ', 'd' => 'د', 'f' => 'ف', 'g' => 'گ',
		'h' => 'ه', 'j' => 'ژ', 'k' => 'ک', 'l' => 'ل',
		'm' => 'م', 'n' => 'ن', 'p' => 'پ', 'q' => 'ق', 'r' => 'ر', 's' => 'س', 'ş' => 'ش',
		't' => 'ت', 'v' => 'ڤ',
		'x' => 'خ', 'y' => 'ی', 'z' => 'ز',

		'B' => 'ب', 'C' => 'ج', 'Ç' => 'چ', 'D' => 'د', 'F' => 'ف', 'G' => 'گ',
		'H' => 'ح', 'J' => 'ژ', 'K' => 'ک', 'L' => 'ل',
		'M' => 'م', 'N' => 'ن', 'P' => 'پ', 'Q' => 'ق', 'R' => 'ر', 'S' => 'س', 'Ş' => 'ش',
		'T' => 'ت', 'V' => 'ڤ', 'W' => 'و', 'X' => 'خ',
		'Y' => 'ی', 'Z' => 'ز',

		/* Doppelkonsonanten */
		# 'll' => 'ڵ', # wenn es geht, doppel-l und l getrennt zu behandeln
		# 'rr' => 'ڕ', # selbiges für doppel-r

		/* Einzelne Großbuchstaben */
		// ' C' => 'ج',

		/* Vowels */
		'a' => 'ا',
		'e' => 'ە',
		'ê' => 'ێ',
		'i' => '',
		'î' => 'ی',
		'o' => 'ۆ',
		'u' => 'و',
		'û' => 'وو',
		'w' => 'و',
		',' => '،',
		'?' => '؟',

		# Try to replace the leading vowel
		' a' => 'ئا ',
		' e' => 'ئە ',
		' ê' => 'ئێ ',
		' î' => 'ئی ',
		' o' => 'ئۆ ',
		' u' => 'ئو ',
		' û' => 'ئوو ',
		'A' => 'ئا',
		'E' => 'ئە',
		'Ê' => 'ئێ',
		'Î' => 'ئی',
		'O' => 'ئۆ',
		'U' => 'ئو',
		'Û' => 'ئوو',
		' A' => 'ئا ',
		' E' => 'ئە ',
		' Ê' => 'ئێ ',
		' Î' => 'ئی ',
		' O' => 'ئۆ ',
		' U' => 'ئو ',
		' Û' => 'ئوو ',
		# eyn erstmal deaktivieren, einfache Anführungsstriche sind einfach zu
		# häufig, um sie als eyn zu interpretieren.
		# '\'' => 'ع',

/*		# deactivated for now, breaks links i.e. in header of Special:Recentchanges :-(
		# digits
		'0' => '٠', # U+0660
		'1' => '١', # U+0661
		'2' => '٢', # U+0662
		'3' => '٣', # U+0663
		'4' => '٤', # U+0664
		'5' => '٥', # U+0665
		'6' => '٦', # U+0666
		'7' => '٧', # U+0667
		'8' => '٨', # U+0668
		'9' => '٩', # U+0669
*/
		];

	/**
	 * Get Main language code.
	 * @since 1.36
	 *
	 * @return string
	 */
	public function getMainCode(): string {
		return 'ku';
	}

	/**
	 * Get supported variants of the language.
	 * @since 1.36
	 *
	 * @return array
	 */
	public function getLanguageVariants(): array {
		return [ 'ku', 'ku-arab', 'ku-latn' ];
	}

	/**
	 * Get language variants fallbacks.
	 * @since 1.36
	 *
	 * @return array
	 */
	public function getVariantsFallbacks(): array {
		return [
			'ku' => 'ku-latn',
			'ku-arab' => 'ku-latn',
			'ku-latn' => 'ku-arab',
		];
	}

	protected function loadDefaultTables() {
		$this->mTables = [
			'ku-latn' => new ReplacementArray( $this->mArabicToLatin ),
			'ku-arab' => new ReplacementArray( $this->mLatinToArabic ),
			'ku' => new ReplacementArray()
		];
	}

	/**
	 *  It translates text into variant, specials:
	 *    - omitting roman numbers
	 *
	 * @param string $text
	 * @param string $toVariant
	 *
	 * @throws MWException
	 * @return string
	 */
	public function translate( $text, $toVariant ) {
		$this->loadTables();
		/* From Kazakh interface, maybe we need it later
		$breaks = '[^\w\x80-\xff]';
		// regexp for roman numbers
		// Lookahead assertion ensures $roman doesn't match the empty string
		$roman = '(?=[MDCLXVI])M{0,4}(C[DM]|D?C{0,3})(X[LC]|L?X{0,3})(I[VX]|V?I{0,3})';
		$roman = '';

		$reg = '/^'.$roman.'$|^'.$roman.$breaks.'|'.$breaks.$roman.'$|'.$breaks.$roman.$breaks.'/';

		$matches = preg_split( $reg, $text, -1, PREG_SPLIT_OFFSET_CAPTURE );

		$m = array_shift( $matches );
		if ( !isset( $this->mTables[$toVariant] ) ) {
			throw new MWException( 'Broken variant table: ' . implode( ',', array_keys( $this->mTables ) ) );
		}
		$ret = $this->mTables[$toVariant]->replace( $m[0] );
		$mstart = $m[1] + strlen( $m[0] );
		foreach ( $matches as $m ) {
			$ret .= substr( $text, $mstart, $m[1] - $mstart );
			$ret .= parent::translate( $m[0], $toVariant );
			$mstart = $m[1] + strlen( $m[0] );
		}

		return $ret;
		*/

		if ( !isset( $this->mTables[$toVariant] ) ) {
			throw new MWException( 'Broken variant table: ' . implode( ',', array_keys( $this->mTables ) ) );
		}

		return parent::translate( $text, $toVariant );
	}
}
