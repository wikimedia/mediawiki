<?php
/**
 * Inuktitut specific code.
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

/**
 * Conversion script between Latin and Syllabics for Inuktitut.
 * - Syllabics -> lowercase Latin
 * - lowercase/uppercase Latin -> Syllabics
 *
 *
 * Based on:
 *   - http://commons.wikimedia.org/wiki/Image:Inuktitut.png
 *   - LanguageSr.php
 *
 * @ingroup Language
 */
class IuConverter extends LanguageConverter {

	protected $mDoContentConvert;
	var $mToLatin = array(
		'ᐦ' => 'h',   'ᐃ' => 'i',    'ᐄ' => 'ii',    'ᐅ' => 'u',    'ᐆ' => 'uu',    'ᐊ' => 'a',    'ᐋ' => 'aa',
		'ᑉ' => 'p',   'ᐱ' => 'pi',   'ᐲ' => 'pii',   'ᐳ' => 'pu',   'ᐴ' => 'puu',   'ᐸ' => 'pa',   'ᐹ' => 'paa',
		'ᑦ' => 't',   'ᑎ' => 'ti',   'ᑏ' => 'tii',   'ᑐ' => 'tu',   'ᑑ' => 'tuu',   'ᑕ' => 'ta',   'ᑖ' => 'taa',
		'ᒃ' => 'k',   'ᑭ' => 'ki',   'ᑮ' => 'kii',   'ᑯ' => 'ku',   'ᑰ' => 'kuu',   'ᑲ' => 'ka',   'ᑳ' => 'kaa',
		'ᖅᒃ' => 'qq', 'ᖅᑭ' => 'qqi', 'ᖅᑮ' => 'qqii', 'ᖅᑯ' => 'qqu', 'ᖅᑰ' => 'ᖅqquu', 'ᖅᑲ' => 'qqa', 'ᖅᑳ' => 'qqaa',
		'ᒡ' => 'g',   'ᒋ' => 'gi',   'ᒌ' => 'gii',   'ᒍ' => 'gu',   'ᒎ' => 'guu',   'ᒐ' => 'ga',   'ᒑ' => 'gaa',
		'ᒻ' => 'm',   'ᒥ' => 'mi',   'ᒦ' => 'mii',   'ᒧ' => 'mu',   'ᒨ' => 'muu',   'ᒪ' => 'ma',   'ᒫ' => 'maa',
		'ᓐ' => 'n',   'ᓂ' => 'ni',  'ᓃ' => 'nii',   'ᓄ' => 'nu',   'ᓅ' => 'nuu',   'ᓇ' => 'na',   'ᓈ' => 'naa',
		'ᔅ' => 's',   'ᓯ' => 'si',   'ᓰ' => 'sii',   'ᓱ' => 'su',   'ᓲ' => 'suu',   'ᓴ' => 'sa',   'ᓵ' => 'saa',
		'ᓪ' => 'l',   'ᓕ' => 'li',  'ᓖ' => 'lii',   'ᓗ' => 'lu',   'ᓘ' => 'luu',   'ᓚ' => 'la',   'ᓛ' => 'laa',
		'ᔾ' => 'j',   'ᔨ' => 'ji',   'ᔩ' => 'jii',   'ᔪ' => 'ju',   'ᔫ' => 'juu',   'ᔭ' => 'ja',   'ᔮ' => 'jaa',
		'ᕝ' => 'v',   'ᕕ' => 'vi',   'ᕖ' => 'vii',   'ᕗ' => 'vu',   'ᕘ' => 'vuu',   'ᕙ' => 'va',   'ᕚ' => 'vaa',
		'ᕐ' => 'r',   'ᕆ' => 'ri',   'ᕇ' => 'rii',   'ᕈ' => 'ru',   'ᕉ' => 'ruu',   'ᕋ' => 'ra',   'ᕌ' => 'raa',
		'ᖅ' => 'q',   'ᕿ' => 'qi',   'ᖀ' => 'qii',   'ᖁ' => 'qu',   'ᖂ' => 'quu',   'ᖃ' => 'qa',   'ᖄ' => 'qaa',
		'ᖕ' => 'ng',  'ᖏ' => 'ngi',  'ᖐ' => 'ngii',  'ᖑ' => 'ngu',  'ᖒ' => 'nguu',  'ᖓ' => 'nga',  'ᖔ' => 'ngaa',
		'ᖖ' => 'nng', 'ᙱ' => 'nngi', 'ᙲ' => 'nngii', 'ᙳ' => 'nngu', 'ᙴ' => 'nnguu', 'ᙵ' => 'nnga', 'ᙶ' => 'nngaa',
		'ᖦ' => 'ɫ',   'ᖠ' => 'ɫi',    'ᖡ' => 'ɫii',   'ᖢ' => 'ɫu',    'ᖣ' => 'ɫuu',   'ᖤ' => 'ɫa',    'ᖥ' => 'ɫaa',
	);

	var $mUpperToLowerCaseLatin = array(
		'A' => 'a',	'B' => 'b',	'C' => 'c',	'D' => 'd',	'E' => 'e',
		'F' => 'f',	'G' => 'g',	'H' => 'h',	'I' => 'i',	'J' => 'j',
		'K' => 'k',	'L' => 'l',	'M' => 'm',	'N' => 'n',	'O' => 'o',
		'P' => 'p',	'Q' => 'q',	'R' => 'r',	'S' => 's',	'T' => 't',
		'U' => 'u',	'V' => 'v',	'W' => 'w',	'X' => 'x',	'Y' => 'y',
		'Z' => 'z',
	);

	var $mToSyllabics = array(
		'h' => 'ᐦ',   'i' => 'ᐃ',    'ii' => 'ᐄ',    'u' => 'ᐅ',    'uu' => 'ᐆ',    'a' => 'ᐊ',    'aa' => 'ᐋ',
		'p' => 'ᑉ',   'pi' => 'ᐱ',   'pii' => 'ᐲ',   'pu' => 'ᐳ',   'puu' => 'ᐴ',   'pa' => 'ᐸ',   'paa' => 'ᐹ',
		't' => 'ᑦ',   'ti' => 'ᑎ',   'tii' => 'ᑏ',   'tu' => 'ᑐ',   'tuu' => 'ᑑ',   'ta' => 'ᑕ',   'taa' => 'ᑖ',
		'k' => 'ᒃ',   'ki' => 'ᑭ',   'kii' => 'ᑮ',   'ku' => 'ᑯ',   'kuu' => 'ᑰ',   'ka' => 'ᑲ',   'kaa' => 'ᑳ',
		'g' => 'ᒡ',   'gi' => 'ᒋ',   'gii' => 'ᒌ',   'gu' => 'ᒍ',   'guu' => 'ᒎ',   'ga' => 'ᒐ',   'gaa' => 'ᒑ',
		'm' => 'ᒻ',   'mi' => 'ᒥ',   'mii' => 'ᒦ',   'mu' => 'ᒧ',   'muu' => 'ᒨ',   'ma' => 'ᒪ',   'maa' => 'ᒫ',
		'n' => 'ᓐ',   'ni' => 'ᓂ',   'nii' => 'ᓃ',   'nu' => 'ᓄ',   'nuu' => 'ᓅ',   'na' => 'ᓇ',   'naa' => 'ᓈ',
		's' => 'ᔅ',   'si' => 'ᓯ',   'sii' => 'ᓰ',   'su' => 'ᓱ',   'suu' => 'ᓲ',   'sa' => 'ᓴ',   'saa' => 'ᓵ',
		'l' => 'ᓪ',   'li' => 'ᓕ',   'lii' => 'ᓖ',   'lu' => 'ᓗ',   'luu' => 'ᓘ',   'la' => 'ᓚ',   'laa' => 'ᓛ',
		'j' => 'ᔾ',   'ji' => 'ᔨ',   'jii' => 'ᔩ',   'ju' => 'ᔪ',   'juu' => 'ᔫ',   'ja' => 'ᔭ',   'jaa' => 'ᔮ',
		'v' => 'ᕝ',   'vi' => 'ᕕ',   'vii' => 'ᕖ',   'vu' => 'ᕗ',   'vuu' => 'ᕘ',   'va' => 'ᕙ',   'vaa' => 'ᕚ',
		'r' => 'ᕐ',   'ri' => 'ᕆ',   'rii' => 'ᕇ',   'ru' => 'ᕈ',   'ruu' => 'ᕉ',   'ra' => 'ᕋ',   'raa' => 'ᕌ',
		'qq' => 'ᖅᒃ',  'qqi' => 'ᖅᑭ',  'qqii' => 'ᖅᑮ',  'qqu' => 'ᖅᑯ',  'qquu' => 'ᖅᑰ',  'qqa' => 'ᖅᑲ',  'qqaa' => 'ᖅᑳ',
		'q' => 'ᖅ',   'qi' => 'ᕿ',   'qii' => 'ᖀ',   'qu' => 'ᖁ',   'quu' => 'ᖂ',   'qa' => 'ᖃ',   'qaa' => 'ᖄ',
		'ng' => 'ᖕ',  'ngi' => 'ᖏ',  'ngii' => 'ᖐ',  'ngu' => 'ᖑ',  'nguu' => 'ᖒ',  'nga' => 'ᖓ',  'ngaa' => 'ᖔ',
		'nng' => 'ᖖ', 'nngi' => 'ᙱ', 'nngii' => 'ᙲ', 'nngu' => 'ᙳ', 'nnguu' => 'ᙴ', 'nnga' => 'ᙵ', 'nngaa' => 'ᙶ',
		'ɫ' => 'ᖦ',   'ɫi' => 'ᖠ',    'ɫii' => 'ᖡ',   'ɫu' => 'ᖢ',    'ɫuu' => 'ᖣ',   'ɫa' => 'ᖤ',    'ɫaa' => 'ᖥ',
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'lowercase' => new ReplacementArray( $this->mUpperToLowerCaseLatin ),
			'ike-cans' => new ReplacementArray( $this->mToSyllabics ),
			'ike-latn' => new ReplacementArray( $this->mToLatin ),
			'iu'    => new ReplacementArray()
		);
	}

	/**
	 * rules should be defined as -{Syllabic | Latin-} -or-
	 * -{code:text | code:text | ...}-
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
	 * Do not convert content on talk pages
	 *
	 * @param $text string
	 * @param $parser Parser
	 * @return string
	 */
	function parserConvert( $text, &$parser ) {
		$this->mDoContentConvert = !( is_object( $parser->getTitle() ) && $parser->getTitle()->isTalkPage() );

		return parent::parserConvert( $text, $parser );
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
	 * It translates text into variant
	 *
	 * @param $text string
	 * @param $toVariant bool
	 *
	 * @return string
	 */
	function translate( $text, $toVariant ) {
		// If $text is empty or only includes spaces, do nothing
		// Otherwise translate it
		if ( trim( $text ) ) {
			$this->loadTables();
			// To syllabics, first translate uppercase to lowercase Latin
			if($toVariant == 'ike-cans') {
				$text = $this->mTables['lowercase']->replace( $text );
			}
			$text = $this->mTables[$toVariant]->replace( $text );
		}
		return $text;
	}
}

/**
 * Inuktitut
 *
 * @ingroup Language
 */
class LanguageIu extends Language {
	function __construct() {
		global $wgHooks;

		parent::__construct();

		$variants = array( 'iu', 'ike-cans', 'ike-latn' );
		$variantfallbacks = array(
			'iu'    => 'ike-cans',
			'ike-cans' => 'iu',
			'ike-latn' => 'iu',
		);

		$flags = array();
		$this->mConverter = new IuConverter( $this, 'iu', $variants, $variantfallbacks, $flags );
		$wgHooks['ArticleSaveComplete'][] = $this->mConverter;
	}
}
