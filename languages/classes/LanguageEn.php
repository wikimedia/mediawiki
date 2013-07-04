<?php
/**
 * English specific code.
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

require_once __DIR__ . '/../LanguageConverter.php';

class EnConverter extends LanguageConverter {

	function loadDefaultTables() {
		$this->mTables = array(
			'en' => new ReplacementArray(),
			'en-x-piglatin' => new ReplacementArray(),
		);
	}

	/**
	 *  It translates text into Pig Latin.
	 *
	 * @param $text string
	 * @param $toVariant string
	 *
	 * @throws MWException
	 * @return string
	 */
	function translate( $text, $toVariant ) {
		if ( $toVariant === 'en-x-piglatin' ) {
			return English2PigLatin( $text );
		} else {
			return $text;
		}
	}

}

/**
 * English
 *
 * @ingroup Language
 */
class LanguageEn extends Language {

	function __construct() {
		global $wgHooks;

		parent::__construct();

		$this->mConverter = new EnConverter( $this, 'en', array( 'en', 'en-x-piglatin' ) );
		$wgHooks['PageContentSaveComplete'][] = $this->mConverter;
	}

}

// http://chys.info/eng/piglatin

/* This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://sam.zoy.org/wtfpl/COPYING for more details. */

/* IMPORTANT: The encoding of this PHP source must be UTF-8.
 *
 * Perl-Compatible Regular Expression library has perfect support for Unicode;
 * but PHP string functions do not as of 5.2.8. So we have to do them ourselves.
 */


$GLOBALS['special_consonants'] = 'çðñþ';
$GLOBALS['special_vowels'] = 'àáâãäåæèéêëìíîïòóôõöøùúûüýÿ';

$GLOBALS['utf8_upper_array'] = array (
    'ç'=>'Ç', 'ð'=>'Ð', 'ñ'=>'Ñ', 'þ'=>'Þ', 'à'=>'À', 'á'=>'Á', 'â'=>'Â', 'ã'=>'Ã',
    'ä'=>'Ä', 'å'=>'Å', 'æ'=>'Æ', 'è'=>'È', 'é'=>'É', 'ê'=>'Ê', 'ë'=>'Ë', 'ì'=>'Ì',
    'í'=>'Í', 'î'=>'Î', 'ï'=>'Ï', 'ò'=>'Ò', 'ó'=>'Ó', 'ô'=>'Ô', 'õ'=>'Õ', 'ö'=>'Ö',
    'ø'=>'Ø', 'ù'=>'Ù', 'ú'=>'Ú', 'û'=>'Û', 'ü'=>'Ü', 'ý'=>'Ý', 'ÿ'=>'Ÿ',
    'a'=>'A', 'b'=>'B', 'c'=>'C', 'd'=>'D', 'e'=>'E', 'f'=>'F', 'g'=>'G', 'h'=>'H',
    'i'=>'I', 'j'=>'J', 'k'=>'K', 'l'=>'L', 'm'=>'M', 'n'=>'N', 'o'=>'O', 'p'=>'P',
    'q'=>'Q', 'r'=>'R', 's'=>'S', 't'=>'T', 'u'=>'U', 'v'=>'V', 'w'=>'W', 'x'=>'X',
    'y'=>'Y', 'z'=>'Z',
);
$GLOBALS['utf8_lower_array'] = array (
    'Ç'=>'ç', 'Ð'=>'ð', 'Ñ'=>'ñ', 'Þ'=>'þ', 'À'=>'à', 'Á'=>'á', 'Â'=>'â', 'Ã'=>'ã',
    'Ä'=>'ä', 'Å'=>'å', 'Æ'=>'æ', 'È'=>'è', 'É'=>'é', 'Ê'=>'ê', 'Ë'=>'ë', 'Ì'=>'ì',
    'Í'=>'í', 'Î'=>'î', 'Ï'=>'ï', 'Ò'=>'ò', 'Ó'=>'ó', 'Ô'=>'ô', 'Õ'=>'õ', 'Ö'=>'ö',
    'Ø'=>'ø', 'Ù'=>'ù', 'Ú'=>'ú', 'Û'=>'û', 'Ü'=>'ü', 'Ý'=>'ý', 'Ÿ'=>'ÿ',
    'A'=>'a', 'B'=>'b', 'C'=>'c', 'D'=>'d', 'E'=>'e', 'F'=>'f', 'G'=>'g', 'H'=>'h',
    'I'=>'i', 'J'=>'j', 'K'=>'k', 'L'=>'l', 'M'=>'m', 'N'=>'n', 'O'=>'o', 'P'=>'p',
    'Q'=>'q', 'R'=>'r', 'S'=>'s', 'T'=>'t', 'U'=>'u', 'V'=>'v', 'W'=>'w', 'X'=>'x',
    'Y'=>'y', 'Z'=>'z',
);
/* UTF-8 is "self-segregating". So using strtr is safe. */
function utf8_toupper($s) { return strtr($s,$GLOBALS['utf8_upper_array']); }
function utf8_tolower($s) { return strtr($s,$GLOBALS['utf8_lower_array']); }
function utf8_ucfirst($s) {
    return preg_replace_callback('/^./u', create_function('$m','return utf8_toupper($m[0]);'), $s);
}

function English2PigLatin($s) {
/* Consonant-only words are also caught by the regexp, for efficiency purpose.
 * (Otherwise they are attempted many times, starting from each letter)
 * This function handles all letters present in ISO8859-1 correctly.
 */
    global $special_vowels, $special_consonants;
    $vowels = "aeiou$special_vowels";
    $consonants = "bcdfghj-np-tvwxz$special_consonants";
    $letters = "a-z$special_vowels$special_consonants";
    return preg_replace_callback(
        "/([$vowels]|(qu|[y$consonants](['’]?[$consonants])*))((['’]?[$letters])*)/iu",
        'English2PigLatin_Callback', $s);
}

function English2PigLatin_Callback($m) {
/* $m[0] = Whole word
 * $m[2] = Leading consonants, if any
 * $m[4] = Trailing letters (following first vowel or leading consonants)
 */
    if ($m[2]) {
        if (!$m[4])
            return $m[0];
        $r = "{$m[4]}{$m[2]}ay";
    } else
        $r = "{$m[0]}way";
    if (@ctype_upper($m[0][1]))
        $r = utf8_toupper($r);
    elseif (ctype_upper($m[0][0]))
        $r = utf8_ucfirst(utf8_tolower($r));
    return $r;
}
