<?php
/** Kurdish
  * converter routines
  *
  * @addtogroup Language
  */

require_once( dirname(__FILE__).'/../LanguageConverter.php' );
require_once( dirname(__FILE__).'/LanguageKu_ku.php' );

class KuConverter extends LanguageConverter {
	var $mArabicToLatin = array(
		'ب' => 'b', 'ج' => 'c', 'چ' => 'ç', 'د' => 'd', 'ف' => 'f', 'گ' => 'g', 'ھ' => 'h',
		'ہ' => 'h', 'ه' => 'h', 'ح' => 'h', 'ژ' => 'j', 'ك' => 'k', 'ک' => 'k', 'ل' => 'l',
		'م' => 'm', 'ن' => 'n', 'پ' => 'p', 'ق' => 'q', 'ر' => 'r', 'س' => 's', 'ش' => 'ş',
		'ت' => 't', 'ڤ' => 'v', 'خ' => 'x', 'غ' => 'x', 'ز' => 'z', 

		/* Doppel- und Halbvokale */
		'ڵ' => 'll', #ll
		'ڕ'  => 'rr', #rr
		'ا'  => 'a',
		# 'ئێ' => 'ê', # Anfangs-e
		'ە'  => 'e',
		'ه‌'  => 'e', # mit einem non-joiner
		'ه‌‌'  => 'e', # mit zwei non-joinern
		'ة'  => 'e',
		'ێ' => 'ê',
		'ي'  => 'î',
		'ی'  => 'î',
		'ۆ'  => 'o',
		'و'  => 'w',
		'ئ'  => '', # initiales hemze soll verschluckt werden
		'،'  => ',', 
		'ع'  => '\'', # ayn
		'؟'  => '?',
	);

	var $mLatinToArabic = array(
		'b' => 'ب', 'c' => 'ج', 'ç' => 'چ', 'd' => 'د', 'f' => 'ف', 'g' => 'گ',
		'h' => 'ه', 'j' => 'ژ', 'k' => 'ک', 'l' => 'ل',
		'm' => 'م', 'n' => 'ن', 'p' => 'پ', 'q' => 'ق', 'r' => 'ر', 's' => 'س', 'ş' => 'ش',
		't' => 'ت', 'v' => 'ڤ',
                'x' => 'خ', 'y' => 'ی', 'z' => 'ز',


		'B' => 'ب', 'C' => 'ج', 'Ç' => 'چ', 'D' => 'د', 'F' => 'ف', 'G' => 'گ', 'H' => 'ھ',
		'H' => 'ہ', 'H' => 'ه', 'H' => 'ح', 'J' => 'ژ', 'K' => 'ك', 'K' => 'ک', 'L' => 'ل',
		'M' => 'م', 'N' => 'ن', 'P' => 'پ', 'Q' => 'ق', 'R' => 'ر', 'S' => 'س', 'Ş' => 'ش',
		'T' => 'ت', 'V' => 'ڤ', 'W' => 'و', 'X' => 'خ',
		'Y' => 'ی', 'Z' => 'ز',

		/* Doppelkonsonanten */
		# 'll' => 'ڵ', # wenn es geht, doppel-l und l getrennt zu behandeln
		# 'rr' => 'ڕ', # selbiges für doppel-r

		/* Einzelne Großbuchstaben */
		//' C' => 'ج',

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
		'A'  => 'ئا',
		'E'  => 'ئە',
		'Ê'  => 'ئێ',
		'Î'  => 'ئی',
		'O'  => 'ئۆ',
		'U'  => 'ئو',
		'Û'  => 'ئوو',
		' A' => 'ئا ',
		' E' => 'ئە ',
		' Ê' => 'ئێ ',
		' Î' => 'ئی ',
		' O' => 'ئۆ ',
		' U' => 'ئو ',
		' Û' => 'ئوو ',
		# eyn erstmal deaktivieren, einfache Anführungsstriche sind einfach zu häufig, um sie als eyn zu interpretieren
		# '\'' => 'ع', 

	);

	function loadDefaultTables() {
		$this->mTables = array(
			'ku-latn' => new ReplacementArray( $this->mArabicToLatin ),
			'ku-arab' => new ReplacementArray( $this->mLatinToArabic ),
			'ku'      => new ReplacementArray()
		);
	}


	// Do not convert content on talk pages
	function parserConvert( $text, &$parser ){
		if(is_object($parser->getTitle() ) && $parser->getTitle()->isTalkPage())
			$this->mDoContentConvert=false;
		else 
			$this->mDoContentConvert=true;

		return parent::parserConvert($text, $parser );
	}

	/*
	 * A function wrapper:
	 *   - if there is no selected variant, leave the link 
	 *     names as they were
	 *   - do not try to find variants for usernames
	 */
	function findVariantLink( &$link, &$nt ) {
		// check for user namespace
		if(is_object($nt)){
			$ns = $nt->getNamespace();
			if($ns==NS_USER || $ns==NS_USER_TALK)
				return;
		}

		$oldlink=$link;
		parent::findVariantLink($link,$nt);
		if($this->getPreferredVariant()==$this->mMainLanguageCode)
			$link=$oldlink;
	}

	/*
	 * We want our external link captions to be converted in variants,
	 * so we return the original text instead -{$text}-, except for URLs
	 */
	function markNoConversion($text, $noParse=false) {
		if($noParse || preg_match("/^https?:\/\/|ftp:\/\/|irc:\/\//",$text))
		    return parent::markNoConversion($text);
		return $text;
	}

	/*
	 * An ugly function wrapper for parsing Image titles
	 * (to prevent image name conversion)
	 */
	function autoConvert($text, $toVariant=false) {
		global $wgTitle;
		if(is_object($wgTitle) && $wgTitle->getNameSpace()==NS_IMAGE){ 
			$imagename = $wgTitle->getNsText();
			if(preg_match("/^$imagename:/",$text)) return $text;
		}
		return parent::autoConvert($text,$toVariant);
	}

	/**
	 *  It translates text into variant, specials:
	 *    - ommiting roman numbers
	 */
	function translate($text, $toVariant){
		$breaks = '[^\w\x80-\xff]';

		/* From Kazakh interface, maybe we need it later
		 *
		// regexp for roman numbers
		$roman = 'M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})';
		$roman = '';

		$reg = '/^'.$roman.'$|^'.$roman.$breaks.'|'.$breaks.$roman.'$|'.$breaks.$roman.$breaks.'/';

		$matches = preg_split($reg, $text, -1, PREG_SPLIT_OFFSET_CAPTURE);

		$m = array_shift($matches);
		if( !isset( $this->mTables[$toVariant] ) ) {
			throw new MWException( "Broken variant table: " . implode( ',', array_keys( $this->mTables ) ) );
		}
		$ret = $this->mTables[$toVariant]->replace( $m[0] );
		$mstart = $m[1]+strlen($m[0]);
		foreach($matches as $m) {
			$ret .= substr($text, $mstart, $m[1]-$mstart);
			$ret .= parent::translate($m[0], $toVariant);
			$mstart = $m[1] + strlen($m[0]);
		}

		return $ret;
		*/

		if( !isset( $this->mTables[$toVariant] ) ) {
			throw new MWException( "Broken variant table: " . implode( ',', array_keys( $this->mTables ) ) );
		}

		return parent::translate( $text, $toVariant );
	}
}

class LanguageKu extends LanguageKu_ku {

	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = array( 'ku', 'ku-arab', 'ku-latn' );
		$variantfallbacks = array(
			'ku'      => 'ku-latn',
			'ku-arab' => 'ku-latn',
			'ku-latn' => 'ku-arab',
		);

		$this->mConverter = new KuConverter( $this, 'ku', $variants, $variantfallbacks );
		$wgHooks['ArticleSaveComplete'][] = $this->mConverter;
	}

/*   From Kazakh interface, not needed for the moment

	function convertGrammar( $word, $case ) {
		$fname="LanguageKu::convertGrammar";
		wfProfileIn( $fname );

		//always convert to ku-latn before convertGrammar
		$w1 = $word;
		$word = $this->mConverter->autoConvert( $word, 'ku-latn' );
		$w2 = $word;
		$word = parent::convertGrammar( $word, $case );
		//restore encoding
		if( $w1 != $w2 ) {
			$word = $this->mConverter->translate( $word, 'ku-latn' );
		}
		wfProfileOut( $fname );
		return $word;
	}
*/
}


