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

require_once( __DIR__ . '/../LanguageConverter.php' );
require_once( __DIR__ . '/LanguageSr_ec.php' );
require_once( __DIR__ . '/LanguageSr_el.php' );

/**
 * There are two levels of conversion for Serbian: the script level
 * (Cyrillics <-> Latin), and the variant level (ekavian
 * <->iyekavian). The two are orthogonal. So we really only need two
 * dictionaries: one for Cyrillics and Latin, and one for ekavian and
 * iyekavian.
 *
 * @ingroup Language
 */
class SrConverter extends LanguageConverter {
	var $mToLatin = array(
		'а' => 'a', 'б' => 'b',  'в' => 'v', 'г' => 'g',  'д' => 'd',
		'ђ' => 'đ', 'е' => 'e',  'ж' => 'ž', 'з' => 'z',  'и' => 'i',
		'ј' => 'j', 'к' => 'k',  'л' => 'l', 'љ' => 'lj', 'м' => 'm',
		'н' => 'n', 'њ' => 'nj', 'о' => 'o', 'п' => 'p',  'р' => 'r',
		'с' => 's', 'т' => 't',  'ћ' => 'ć', 'у' => 'u',  'ф' => 'f',
		'х' => 'h', 'ц' => 'c',  'ч' => 'č', 'џ' => 'dž', 'ш' => 'š',

		'А' => 'A', 'Б' => 'B',  'В' => 'V', 'Г' => 'G',  'Д' => 'D',
		'Ђ' => 'Đ', 'Е' => 'E',  'Ж' => 'Ž', 'З' => 'Z',  'И' => 'I',
		'Ј' => 'J', 'К' => 'K',  'Л' => 'L', 'Љ' => 'Lj', 'М' => 'M',
		'Н' => 'N', 'Њ' => 'Nj', 'О' => 'O', 'П' => 'P',  'Р' => 'R',
		'С' => 'S', 'Т' => 'T',  'Ћ' => 'Ć', 'У' => 'U',  'Ф' => 'F',
		'Х' => 'H', 'Ц' => 'C',  'Ч' => 'Č', 'Џ' => 'Dž', 'Ш' => 'Š',
	);

	var $mToCyrillics = array(
		'a' => 'а', 'b'  => 'б', 'c' => 'ц', 'č' => 'ч', 'ć'  => 'ћ',
		'd' => 'д', 'dž' => 'џ', 'đ' => 'ђ', 'e' => 'е', 'f'  => 'ф',
		'g' => 'г', 'h'  => 'х', 'i' => 'и', 'j' => 'ј', 'k'  => 'к',
		'l' => 'л', 'lj' => 'љ', 'm' => 'м', 'n' => 'н', 'nj' => 'њ',
		'o' => 'о', 'p'  => 'п', 'r' => 'р', 's' => 'с', 'š'  => 'ш',
		't' => 'т', 'u'  => 'у', 'v' => 'в', 'z' => 'з', 'ž'  => 'ж',

		'A' => 'А', 'B'  => 'Б', 'C' => 'Ц', 'Č' => 'Ч', 'Ć'  => 'Ћ',
		'D' => 'Д', 'Dž' => 'Џ', 'Đ' => 'Ђ', 'E' => 'Е', 'F'  => 'Ф',
		'G' => 'Г', 'H'  => 'Х', 'I' => 'И', 'J' => 'Ј', 'K'  => 'К',
		'L' => 'Л', 'LJ' => 'Љ', 'M' => 'М', 'N' => 'Н', 'NJ' => 'Њ',
		'O' => 'О', 'P'  => 'П', 'R' => 'Р', 'S' => 'С', 'Š'  => 'Ш',
		'T' => 'Т', 'U'  => 'У', 'V' => 'В', 'Z' => 'З', 'Ž'  => 'Ж',

		'DŽ' => 'Џ', 'd!ž' => 'дж', 'D!ž' => 'Дж', 'D!Ž' => 'ДЖ',
		'Lj' => 'Љ', 'l!j' => 'лј', 'L!j' => 'Лј', 'L!J' => 'ЛЈ',
		'Nj' => 'Њ', 'n!j' => 'нј', 'N!j' => 'Нј', 'N!J' => 'НЈ'
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'sr-ec' => new ReplacementArray( $this->mToCyrillics ),
			'sr-el' => new ReplacementArray( $this->mToLatin ),
			'sr'    => new ReplacementArray()
		);
	}

	/**
	 * rules should be defined as -{ekavian | iyekavian-} -or-
	 * -{code:text | code:text | ...}-
	 *
	 * update: delete all rule parsing because it's not used
	 * currently, and just produces a couple of bugs
	 *
	 * @param $rule string
	 * @param $flags array
	 * @return array
	 */
	function parseManualRule( $rule, $flags = array() ) {
		if ( in_array( 'T', $flags ) ) {
			return parent::parseManualRule( $rule, $flags );
		}

		$carray = array();
		// otherwise ignore all formatting
		foreach ( $this->mVariants as $v ) {
			$carray[$v] = $rule;
		}

		return $carray;
	}

	/**
	 * A function wrapper:
	 *   - if there is no selected variant, leave the link
	 *     names as they were
	 *   - do not try to find variants for usernames
	 *
	 * @param $link string
	 * @param $nt Title
	 * @param $ignoreOtherCond bool
	 */
	function findVariantLink( &$link, &$nt, $ignoreOtherCond = false ) {
		// check for user namespace
		if ( is_object( $nt ) ) {
			$ns = $nt->getNamespace();
			if ( $ns == NS_USER || $ns == NS_USER_TALK )
				return;
		}

		$oldlink = $link;
		parent::findVariantLink( $link, $nt, $ignoreOtherCond );
		if ( $this->getPreferredVariant() == $this->mMainLanguageCode )
			$link = $oldlink;
	}

	/**
	 * We want our external link captions to be converted in variants,
	 * so we return the original text instead -{$text}-, except for URLs
	 *
	 * @param $text string
	 * @param $noParse bool
	 *
	 * @return string
	 */
	function markNoConversion( $text, $noParse = false ) {
		if ( $noParse || preg_match( "/^https?:\/\/|ftp:\/\/|irc:\/\//", $text ) )
			return parent::markNoConversion( $text );
		return $text;
	}

	/**
	 * An ugly function wrapper for parsing Image titles
	 * (to prevent image name conversion)
	 *
	 * @param $text string
	 * @param $toVariant bool
	 *
	 * @return string
	 */
	function autoConvert( $text, $toVariant = false ) {
		global $wgTitle;
		if ( is_object( $wgTitle ) && $wgTitle->getNameSpace() == NS_FILE ) {
			$imagename = $wgTitle->getNsText();
			if ( preg_match( "/^$imagename:/", $text ) ) return $text;
		}
		return parent::autoConvert( $text, $toVariant );
	}

	/**
	 *  It translates text into variant, specials:
	 *    - ommiting roman numbers
	 *
	 * @param $text string
	 * @param $toVariant string
	 *
	 * @throws MWException
	 * @return string
	 */
	function translate( $text, $toVariant ) {
		$breaks = '[^\w\x80-\xff]';

		// regexp for roman numbers
		$roman = 'M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})';

		$reg = '/^' . $roman . '$|^' . $roman . $breaks . '|' . $breaks . $roman . '$|' . $breaks . $roman . $breaks . '/';

		$matches = preg_split( $reg, $text, -1, PREG_SPLIT_OFFSET_CAPTURE );

		$m = array_shift( $matches );
		if ( !isset( $this->mTables[$toVariant] ) ) {
			throw new MWException( "Broken variant table: " . implode( ',', array_keys( $this->mTables ) ) );
		}
		$ret = $this->mTables[$toVariant]->replace( $m[0] );
		$mstart = $m[1] + strlen( $m[0] );
		foreach ( $matches as $m ) {
			$ret .= substr( $text, $mstart, $m[1] -$mstart );
			$ret .= parent::translate( $m[0], $toVariant );
			$mstart = $m[1] + strlen( $m[0] );
		}

		return $ret;
	}

	/**
	 * Guess if a text is written in Cyrillic or Latin.
	 * Overrides LanguageConverter::guessVariant()
	 *
	 * @param string  $text The text to be checked
	 * @param string  $variant Language code of the variant to be checked for
	 * @return bool  true if $text appears to be written in $variant
	 *
	 * @author Nikola Smolenski <smolensk@eunet.rs>
	 * @since 1.19
	 */
	public function guessVariant( $text, $variant ) {
		$numCyrillic = preg_match_all("/[шђчћжШЂЧЋЖ]/u", $text, $dummy);
		$numLatin = preg_match_all("/[šđčćžŠĐČĆŽ]/u", $text, $dummy);

		if( $variant == 'sr-ec' ) {
			return (boolean) ($numCyrillic > $numLatin);
		} elseif( $variant == 'sr-el' ) {
			return (boolean) ($numLatin > $numCyrillic);
		} else {
			return false;
		}

	}

}

/**
 * Serbian (Српски / Srpski)
 *
 * @ingroup Language
 */
class LanguageSr extends LanguageSr_ec {
	function __construct() {
		global $wgHooks;

		parent::__construct();

		$variants = array( 'sr', 'sr-ec', 'sr-el' );
		$variantfallbacks = array(
			'sr'    => 'sr-ec',
			'sr-ec' => 'sr',
			'sr-el' => 'sr',
		);

		$flags = array(
			'S' => 'S', 'писмо' => 'S', 'pismo' => 'S',
			'W' => 'W', 'реч'   => 'W', 'reč'   => 'W', 'ријеч' => 'W', 'riječ' => 'W'
		);
		$this->mConverter = new SrConverter( $this, 'sr', $variants, $variantfallbacks, $flags );
		$wgHooks['ArticleSaveComplete'][] = $this->mConverter;
	}

	/**
	 * @param $count int
	 * @param $forms array
	 *
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) {
			return '';
		}

		// If the actual number is not mentioned in the expression, then just two forms are enough:
		// singular for $count == 1
		// plural   for $count != 1
		// For example, "This user belongs to {{PLURAL:$1|one group|several groups}}."
		if ( count( $forms ) === 2 ) {
			return $count == 1 ? $forms[0] : $forms[1];
		}

		// @todo FIXME: CLDR defines 4 plural forms. Form with decimals missing.
		// See http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#ru
		$forms = $this->preConvertPlural( $forms, 3 );

		if ( $count > 10 && floor( ( $count % 100 ) / 10 ) == 1 ) {
			return $forms[2];
		} else {
			switch ( $count % 10 ) {
				case 1:  return $forms[0];
				case 2:
				case 3:
				case 4:  return $forms[1];
				default: return $forms[2];
			}
		}
	}
}
