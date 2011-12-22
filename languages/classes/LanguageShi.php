<?php
/**
  * @addtogroup Language
  */

/*
* Conversion script between Latin and Tifinagh for Tachelhit.
* - Tifinagh -> lowercase Latin
* - lowercase/uppercase Latin -> Tifinagh
*
*
* Based on:
*   - http://en.wikipedia.org/wiki/Shilha_language
*   - LanguageSr.php
*
* @ingroup Language
*/
require_once( dirname( __FILE__ ) . '/../LanguageConverter.php' );

/**
 *
 */
class ShiConverter extends LanguageConverter {

	protected $mDoContentConvert;

	var $mToLatin = array(
		'ⴰ' => 'a', 'ⴱ' => 'b', 'ⴳ' => 'g', 'ⴷ' => 'd', 'ⴹ' => 'ḍ', 'ⴻ' => 'e',
		'ⴼ' => 'f', 'ⴽ' => 'k', 'ⵀ' => 'h', 'ⵃ' => 'ḥ', 'ⵄ' => 'ε', 'ⵅ' => 'x',
		'ⵇ' => 'q', 'ⵉ' => 'i', 'ⵊ' => 'j',  'ⵍ' => 'l', 'ⵎ' => 'm', 'ⵏ' => 'n',
		'ⵓ' => 'u', 'ⵔ' => 'r', 'ⵕ' => 'ṛ', 'ⵖ' => 'γ', 'ⵙ' => 's', 'ⵚ' => 'ṣ',
		'ⵛ' => 'š', 'ⵜ' => 't', 'ⵟ' => 'ṭ', 'ⵡ' => 'w', 'ⵢ' => 'y', 'ⵣ' => 'z',
		'ⵥ' => 'ẓ', 'ⵯ' => 'ʷ', 'ⵖ' => 'ɣ', 'ⵠ' => 'v', 'ⵒ' => 'p',
	);

	var $mUpperToLowerCaseLatin = array(
		'A' => 'a',	'B' => 'b',	'C' => 'c',	'D' => 'd',	'E' => 'e',
		'F' => 'f',	'G' => 'g',	'H' => 'h',	'I' => 'i',	'J' => 'j',
		'K' => 'k',	'L' => 'l',	'M' => 'm',	'N' => 'n',	'O' => 'o',
		'P' => 'p',	'Q' => 'q',	'R' => 'r',	'S' => 's',	'T' => 't',
		'U' => 'u',	'V' => 'v',	'W' => 'w',	'X' => 'x',	'Y' => 'y',
		'Z' => 'z', 'Ɣ' => 'ɣ',
	);

	var $mToTifinagh = array(
		'a' => 'ⴰ', 'b' => 'ⴱ', 'g' => 'ⴳ', 'd' => 'ⴷ', 'ḍ' => 'ⴹ', 'e' => 'ⴻ',
		'f' => 'ⴼ', 'k' => 'ⴽ', 'h' => 'ⵀ', 'ḥ' => 'ⵃ', 'ε' => 'ⵄ', 'x' => 'ⵅ',
		'q' => 'ⵇ', 'i' => 'ⵉ', 'j' => 'ⵊ',  'l' => 'ⵍ', 'm' => 'ⵎ', 'n' => 'ⵏ',
		'u' => 'ⵓ', 'r' => 'ⵔ', 'ṛ' => 'ⵕ', 'γ' => 'ⵖ', 's' => 'ⵙ', 'ṣ' => 'ⵚ',
		'š' => 'ⵛ', 't' => 'ⵜ', 'ṭ' => 'ⵟ', 'w' => 'ⵡ', 'y' => 'ⵢ', 'z' => 'ⵣ',
		'ẓ' => 'ⵥ', 'ʷ' => 'ⵯ', 'ɣ' => 'ⵖ', 'v' => 'ⵠ', 'p' => 'ⵒ',
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'lowercase' => new ReplacementArray( $this->mUpperToLowerCaseLatin ),
			'shi-tfng' => new ReplacementArray( $this->mToTifinagh ),
			'shi-latn' => new ReplacementArray( $this->mToLatin ),
			'shi'    => new ReplacementArray()
		);
	}

	/**
	 * rules should be defined as -{Tifinagh | Latin-} -or-
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
	 * @param $toVariant string
	 *
	 * @return string
	 */
	function translate( $text, $toVariant ) {
		// If $text is empty or only includes spaces, do nothing
		// Otherwise translate it
		if ( trim( $text ) ) {
			$this->loadTables();
			// To Tifinagh, first translate uppercase to lowercase Latin
			if( $toVariant == 'shi-tfng' ) {
				$text = $this->mTables['lowercase']->replace( $text );
			}
			$text = $this->mTables[$toVariant]->replace( $text );
		}
		return $text;
	}
}

/**
 * Tachelhit
 *
 * @ingroup Language
 */
class LanguageShi extends Language {
	function __construct() {
		global $wgHooks;

		parent::__construct();

		$variants = array( 'shi', 'shi-tfng', 'shi-latn' );
		$variantfallbacks = array(
			'shi'    => 'shi-tfng',
			'shi-tfng' => 'shi',
			'shi-latn' => 'shi',
		);

		$flags = array();
		$this->mConverter = new ShiConverter( $this, 'shi', $variants, $variantfallbacks, $flags );
		$wgHooks['ArticleSaveComplete'][] = $this->mConverter;
	}
}
