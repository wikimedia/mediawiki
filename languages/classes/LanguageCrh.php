<?php
/**
 * Crimean Tatar (Qırımtatarca) specific code.
 *
 * Adapted from https://crh.wikipedia.org/wiki/Qullan%C4%B1c%C4%B1:Don_Alessandro/Translit
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
 * Crimean Tatar (Qırımtatarca) converter routines
 *
 * @ingroup Language
 */
class CrhConverter extends LanguageConverter {
	// Defines working character ranges
	const WORD_BEGINS = '\r\s\"\'\(\)\-<>\[\]\/.,:;!?';
	const WORD_ENDS = '\r\s\"\'\(\)\-<>\[\]\/.,:;!?';

	// Cyrillic
	const C_UC = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ'; # Crimean Tatar Cyrillic uppercase
	const C_LC = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя'; # Crimean Tatar Cyrillic lowercase
	const C_CONS_UC = 'БВГДЖЗЙКЛМНПРСТФХЦЧШЩCÑ'; # Crimean Tatar Cyrillic + CÑ uppercase consonants
	const C_CONS_LC = 'бвгджзйклмнпрстфхцчшщcñ'; # Crimean Tatar Cyrillic + CÑ lowercase consonants
	const C_M_CONS = 'бгкмпшcБГКМПШC'; # Crimean Tatar Cyrillic M-type consonants

	# Crimean Tatar Cyrillic + CÑ consonants
	const C_CONS = 'бвгджзйклмнпрстфхцчшщcñБВГДЖЗЙКЛМНПРСТФХЦЧШЩCÑ';

	// Latin
	const L_UC = 'AÂBCÇDEFGĞHIİJKLMNÑOÖPQRSŞTUÜVYZ'; # Crimean Tatar Latin uppercase
	const L_LC = 'aâbcçdefgğhıijklmnñoöpqrsştuüvyz'; # Crimean Tatar Latin lowercase
	const L_N_CONS_UC = 'ÇNRSTZ'; # Crimean Tatar Latin N-type upper case consonants
	const L_N_CONS_LC = 'çnrstz'; # Crimean Tatar Latin N-type lower case consonants
	const L_N_CONS = 'çnrstzÇNRSTZ'; # Crimean Tatar Latin N-type consonants
	const L_M_CONS = 'bcgkmpşBCGKMPŞ'; # Crimean Tatar Latin M-type consonants
	const L_CONS_UC = 'BCÇDFGHJKLMNÑPRSŞTVZ'; # Crimean Tatar Latin uppercase consonants
	const L_CONS_LC = 'bcçdfghjklmnñprsştvz'; # Crimean Tatar Latin lowercase consonants
	const L_CONS = 'bcçdfghjklmnñprsştvzBCÇDFGHJKLMNÑPRSŞTVZ'; # Crimean Tatar Latin consonants
	const L_VOW_UC = 'AÂEIİOÖUÜ'; # Crimean Tatar Latin uppercase vowels
	const L_VOW = 'aâeıioöuüAÂEIİOÖUÜ'; # Crimean Tatar Latin vowels
	const L_F_UC = 'EİÖÜ'; # Crimean Tatar Latin uppercase front vowels
	const L_F = 'eiöüEİÖÜ'; # Crimean Tatar Latin front vowels

	public $mCyrillicToLatin = [

		## these are independent of location in the word, but have
		## to go first so other transforms don't bleed them
		'гъ' => 'ğ', 'Гъ' => 'Ğ', 'ГЪ' => 'Ğ',
		'къ' => 'q', 'Къ' => 'Q', 'КЪ' => 'Q',
		'нъ' => 'ñ', 'Нъ' => 'Ñ', 'НЪ' => 'Ñ',
		'дж' => 'c', 'Дж' => 'C', 'ДЖ' => 'C',

		'А' => 'A', 'а' => 'a', 'Б' => 'B', 'б' => 'b',
		'В' => 'V', 'в' => 'v', 'Г' => 'G', 'г' => 'g',
		'Д' => 'D', 'д' => 'd', 'Ж' => 'J', 'ж' => 'j',
		'З' => 'Z', 'з' => 'z', 'И' => 'İ', 'и' => 'i',
		'Й' => 'Y', 'й' => 'y', 'К' => 'K', 'к' => 'k',
		'Л' => 'L', 'л' => 'l', 'М' => 'M', 'м' => 'm',
		'Н' => 'N', 'н' => 'n', 'П' => 'P', 'п' => 'p',
		'Р' => 'R', 'р' => 'r', 'С' => 'S', 'с' => 's',
		'Т' => 'T', 'т' => 't', 'Ф' => 'F', 'ф' => 'f',
		'Х' => 'H', 'х' => 'h', 'Ч' => 'Ç', 'ч' => 'ç',
		'Ш' => 'Ş', 'ш' => 'ş', 'Ы' => 'I', 'ы' => 'ı',
		'Э' => 'E', 'э' => 'e', 'Е' => 'E', 'е' => 'e',
		'Я' => 'Â', 'я' => 'â', 'У' => 'U', 'у' => 'u',
		'О' => 'O', 'о' => 'o',

		'Ё' => 'Yo', 'ё' => 'yo', 'Ю' => 'Yu', 'ю' => 'yu',
		'Ц' => 'Ts', 'ц' => 'ts', 'Щ' => 'Şç', 'щ' => 'şç',
		'Ь' => '', 'ь' => '', 'Ъ' => '', 'ъ' => '',

	];

	public $mLatinToCyrillic = [
		'Â' => 'Я', 'â' => 'я', 'B' => 'Б', 'b' => 'б',
		'Ç' => 'Ч', 'ç' => 'ч', 'D' => 'Д', 'd' => 'д',
		'F' => 'Ф', 'f' => 'ф', 'G' => 'Г', 'g' => 'г',
		'H' => 'Х', 'h' => 'х', 'I' => 'Ы', 'ı' => 'ы',
		'İ' => 'И', 'i' => 'и', 'J' => 'Ж', 'j' => 'ж',
		'K' => 'К', 'k' => 'к', 'L' => 'Л', 'l' => 'л',
		'M' => 'М', 'm' => 'м', 'N' => 'Н', 'n' => 'н',
		'O' => 'О', 'o' => 'о', 'P' => 'П', 'p' => 'п',
		'R' => 'Р', 'r' => 'р', 'S' => 'С', 's' => 'с',
		'Ş' => 'Ш', 'ş' => 'ш', 'T' => 'Т', 't' => 'т',
		'V' => 'В', 'v' => 'в', 'Z' => 'З', 'z' => 'з',

		'ya' => 'я', 'Ya' => 'Я', 'YA' => 'Я',
		'ye' => 'е', 'YE' => 'Е', 'Ye' => 'Е',

		// hack, hack, hack
		'A' => 'А', 'a' => 'а', 'E' => 'Е', 'e' => 'е',
		'Ö' => 'О', 'ö' => 'о', 'U' => 'У', 'u' => 'у',
		'Ü' => 'У', 'ü' => 'у', 'Y' => 'Й', 'y' => 'й',

		'C' => 'Дж', 'c' => 'дж', 'Ğ' => 'Гъ', 'ğ' => 'гъ',
		'Ñ' => 'Нъ', 'ñ' => 'нъ', 'Q' => 'Къ', 'q' => 'къ',

		];

	public $mExceptions = [];
	public $mCyrl2LatnPatterns = [];
	public $mLatn2CyrlPatterns = [];
	public $mCyrlCleanUpRegexes = [];

	public $mExceptionsLoaded = false;

	function loadDefaultTables() {
		$this->mTables = [
			'crh-latn' => new ReplacementArray( $this->mCyrillicToLatin ),
			'crh-cyrl' => new ReplacementArray( $this->mLatinToCyrillic ),
			'crh' => new ReplacementArray()
		];
	}

	function postLoadTables() {
		$this->loadExceptions();
	}

	function loadExceptions() {
		if ( $this->mExceptionsLoaded ) {
			return;
		}

		$this->mExceptionsLoaded = true;
		$crhExceptions = new MediaWiki\Languages\Data\CrhExceptions();
		list( $this->mExceptions, $this->mCyrl2LatnPatterns, $this->mLatn2CyrlPatterns,
			$this->mCyrlCleanUpRegexes ) = $crhExceptions->loadExceptions( self::L_LC . self::C_LC,
			self::L_UC . self::C_UC );
	}

	/**
	 * A function wrapper:
	 *   - if there is no selected variant, leave the link
	 *     names as they were
	 *   - do not try to find variants for usernames
	 *
	 * @param string &$link
	 * @param Title &$nt
	 * @param bool $ignoreOtherCond
	 */
	function findVariantLink( &$link, &$nt, $ignoreOtherCond = false ) {
		// check for user namespace
		if ( is_object( $nt ) ) {
			$ns = $nt->getNamespace();
			if ( $ns == NS_USER || $ns == NS_USER_TALK ) {
				return;
			}
		}

		$oldlink = $link;
		parent::findVariantLink( $link, $nt, $ignoreOtherCond );
		if ( $this->getPreferredVariant() == $this->mMainLanguageCode ) {
			$link = $oldlink;
		}
	}

	/**
	 *  It translates text into variant, specials:
	 *    - ommiting roman numbers
	 *
	 * @param string $text
	 * @param bool $toVariant
	 *
	 * @throws MWException
	 * @return string
	 */
	function translate( $text, $toVariant ) {
		$letters = '';
		switch ( $toVariant ) {
			case 'crh-cyrl':
				$letters = self::L_UC . self::L_LC . "\'";
				break;
			case 'crh-latn':
				$letters = self::C_UC . self::C_LC . "";
				break;
			default:
				return $text;
				break;
		}

		if ( !$this->mTablesLoaded ) {
			$this->loadTables();
		}

		if ( !isset( $this->mTables[$toVariant] ) ) {
			throw new MWException( "Broken variant table: " . implode( ',', array_keys( $this->mTables ) ) );
		}

		// check for roman numbers like VII, XIX...
		$roman = '/^M{0,3}(C[DM]|D{0,1}C{0,3})(X[LC]|L{0,1}X{0,3})(I[VX]|V{0,1}I{0,3})$/u';

		# match any sub-string of the relevant letters and convert it
		$matches = preg_split( '/(\b|^)[^' . $letters . ']+(\b|$)/u',
			$text, -1, PREG_SPLIT_OFFSET_CAPTURE );
		$mstart = 0;
		$ret = '';
		foreach ( $matches as $m ) {
			# copy over the non-matching bit
			$ret .= substr( $text, $mstart, $m[1] - $mstart );
			# skip certain classes of strings

			if ( array_key_exists( $m[0], $this->mExceptions ) ) {
				# if it's an exception, just copy down the right answer
				$ret .= $this->mExceptions[$m[0]];
			} elseif ( ! $m[0] || # empty strings
					 preg_match( $roman, $m[0] ) ||	# roman numerals
					 preg_match( '/[^' . $letters . ']/', $m[0] ) # mixed orthography
					) {
				$ret .= $m[0];
			} else {
				# convert according to the rules
				$token = $this->regsConverter( $m[0], $toVariant );
				$ret .= parent::translate( $token, $toVariant );
			}
			$mstart = $m[1] + strlen( $m[0] );
		}

		# pick up stray quote marks
		switch ( $toVariant ) {
			case 'crh-cyrl':
				$ret = strtr( $ret, [ '“' => '«', '”' => '»', ] );
				$ret = $this->regsConverter( $ret, 'cyrl-cleanup' );
				break;
			case 'crh-latn':
				$ret = strtr( $ret, [ '«' => '"', '»' => '"', ] );
				break;
		}

		return $ret;
	}

	private function regsConverter( $text, $toVariant ) {
		if ( $text == '' ) return $text;

		$pat = [];
		$rep = [];
		switch ( $toVariant ) {
			case 'crh-latn':
				foreach ( $this->mCyrl2LatnPatterns as $pat => $rep ) {
					$text = preg_replace( $pat, $rep, $text );
				}
				return $text;
			case 'crh-cyrl':
				foreach ( $this->mLatn2CyrlPatterns as $pat => $rep ) {
					$text = preg_replace( $pat, $rep, $text );
				}
				return $text;
			case 'cyrl-cleanup':
				foreach ( $this->mCyrlCleanUpRegexes as $pat => $rep ) {
					$text = preg_replace( $pat, $rep, $text );
				}
				return $text;
			default:
				return $text;
		}
	}

}

/**
 * Crimean Tatar (Qırımtatarca)
 *
 * @ingroup Language
 */
class LanguageCrh extends Language {

	function __construct() {
		parent::__construct();

		$variants = [ 'crh', 'crh-cyrl', 'crh-latn' ];
		$variantfallbacks = [
			'crh' => 'crh-latn',
			'crh-cyrl' => 'crh-latn',
			'crh-latn' => 'crh-cyrl',
		];

		$this->mConverter = new CrhConverter( $this, 'crh', $variants, $variantfallbacks );
	}
}
