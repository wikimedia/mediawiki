<?php
/**
 * Serbian (Српски / Srpski) specific code.
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
 * There are two levels of conversion for Serbian: the script level
 * (Cyrillics <-> Latin), and the variant level (ekavian
 * <->iyekavian). The two are orthogonal. So we really only need two
 * dictionaries: one for Cyrillics and Latin, and one for ekavian and
 * iyekavian.
 *
 * @ingroup Language
 */
class SrConverter extends LanguageConverterSpecific {

	/**
	 * @var string[]
	 */
	public $mToLatin = [
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
		'ђ' => 'đ', 'е' => 'e', 'ж' => 'ž', 'з' => 'z', 'и' => 'i',
		'ј' => 'j', 'к' => 'k', 'л' => 'l', 'љ' => 'lj', 'м' => 'm',
		'н' => 'n', 'њ' => 'nj', 'о' => 'o', 'п' => 'p', 'р' => 'r',
		'с' => 's', 'т' => 't', 'ћ' => 'ć', 'у' => 'u', 'ф' => 'f',
		'х' => 'h', 'ц' => 'c', 'ч' => 'č', 'џ' => 'dž', 'ш' => 'š',

		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
		'Ђ' => 'Đ', 'Е' => 'E', 'Ж' => 'Ž', 'З' => 'Z', 'И' => 'I',
		'Ј' => 'J', 'К' => 'K', 'Л' => 'L', 'Љ' => 'Lj', 'М' => 'M',
		'Н' => 'N', 'Њ' => 'Nj', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
		'С' => 'S', 'Т' => 'T', 'Ћ' => 'Ć', 'У' => 'U', 'Ф' => 'F',
		'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Č', 'Џ' => 'Dž', 'Ш' => 'Š',
	];

	/**
	 * @var string[]
	 */
	public $mToCyrillics = [
		'a' => 'а', 'b' => 'б', 'c' => 'ц', 'č' => 'ч', 'ć' => 'ћ',
		'd' => 'д', 'dž' => 'џ', 'đ' => 'ђ', 'e' => 'е', 'f' => 'ф',
		'g' => 'г', 'h' => 'х', 'i' => 'и', 'j' => 'ј', 'k' => 'к',
		'l' => 'л', 'lj' => 'љ', 'm' => 'м', 'n' => 'н', 'nj' => 'њ',
		'o' => 'о', 'p' => 'п', 'r' => 'р', 's' => 'с', 'š' => 'ш',
		't' => 'т', 'u' => 'у', 'v' => 'в', 'z' => 'з', 'ž' => 'ж',

		'A' => 'А', 'B' => 'Б', 'C' => 'Ц', 'Č' => 'Ч', 'Ć' => 'Ћ',
		'D' => 'Д', 'Dž' => 'Џ', 'Đ' => 'Ђ', 'E' => 'Е', 'F' => 'Ф',
		'G' => 'Г', 'H' => 'Х', 'I' => 'И', 'J' => 'Ј', 'K' => 'К',
		'L' => 'Л', 'LJ' => 'Љ', 'M' => 'М', 'N' => 'Н', 'NJ' => 'Њ',
		'O' => 'О', 'P' => 'П', 'R' => 'Р', 'S' => 'С', 'Š' => 'Ш',
		'T' => 'Т', 'U' => 'У', 'V' => 'В', 'Z' => 'З', 'Ž' => 'Ж',

		'DŽ' => 'Џ', 'd!ž' => 'дж', 'D!ž' => 'Дж', 'D!Ž' => 'ДЖ',
		'Lj' => 'Љ', 'l!j' => 'лј', 'L!j' => 'Лј', 'L!J' => 'ЛЈ',
		'Nj' => 'Њ', 'n!j' => 'нј', 'N!j' => 'Нј', 'N!J' => 'НЈ'
	];

	/**
	 * @param Language $langobj
	 */
	public function __construct( $langobj ) {
		$variants = [ 'sr', 'sr-ec', 'sr-el' ];
		$variantfallbacks = [
			'sr' => 'sr-ec',
			'sr-ec' => 'sr',
			'sr-el' => 'sr',
		];

		$flags = [
			'S' => 'S', 'писмо' => 'S', 'pismo' => 'S',
			'W' => 'W', 'реч' => 'W', 'reč' => 'W', 'ријеч' => 'W', 'riječ' => 'W'
		];
		parent::__construct( $langobj, 'sr', $variants, $variantfallbacks, $flags );
	}

	protected function loadDefaultTables() {
		$this->mTables = [
			'sr-ec' => new ReplacementArray( $this->mToCyrillics ),
			'sr-el' => new ReplacementArray( $this->mToLatin ),
			'sr' => new ReplacementArray()
		];
	}

	/**
	 *  It translates text into variant, specials:
	 *    - ommiting roman numbers
	 *
	 * @param string $text
	 * @param string $toVariant
	 *
	 * @throws MWException
	 * @return string
	 */
	public function translate( $text, $toVariant ) {
		$breaks = '[^\w\x80-\xff]';

		// regexp for roman numbers
		// Lookahead assertion ensures $roman doesn't match the empty string
		$roman = '(?=[MDCLXVI])M{0,4}(C[DM]|D?C{0,3})(X[LC]|L?X{0,3})(I[VX]|V?I{0,3})';

		$reg = '/^' . $roman . '$|^' . $roman . $breaks . '|' . $breaks
			. $roman . '$|' . $breaks . $roman . $breaks . '/';

		$matches = preg_split( $reg, $text, -1, PREG_SPLIT_OFFSET_CAPTURE );

		$m = array_shift( $matches );
		$this->loadTables();
		if ( !isset( $this->mTables[$toVariant] ) ) {
			throw new MWException( "Broken variant table: "
				. implode( ',', array_keys( $this->mTables ) ) );
		}
		$ret = $this->mTables[$toVariant]->replace( $m[0] );
		$mstart = $m[1] + strlen( $m[0] );
		foreach ( $matches as $m ) {
			$ret .= substr( $text, $mstart, $m[1] - $mstart );
			$ret .= parent::translate( $m[0], $toVariant );
			$mstart = $m[1] + strlen( $m[0] );
		}

		return $ret;
	}

	/**
	 * Guess if a text is written in Cyrillic or Latin.
	 * Overrides LanguageConverter::guessVariant()
	 *
	 * @param string $text The text to be checked
	 * @param string $variant Language code of the variant to be checked for
	 * @return bool True if $text appears to be written in $variant
	 *
	 * @author Nikola Smolenski <smolensk@eunet.rs>
	 * @since 1.19
	 */
	public function guessVariant( $text, $variant ) {
		$numCyrillic = preg_match_all( "/[шђчћжШЂЧЋЖ]/u", $text, $dummy );
		$numLatin = preg_match_all( "/[šđčćžŠĐČĆŽ]/u", $text, $dummy );

		if ( $variant == 'sr-ec' ) {
			return $numCyrillic > $numLatin;
		} elseif ( $variant == 'sr-el' ) {
			return $numLatin > $numCyrillic;
		} else {
			return false;
		}
	}

}
