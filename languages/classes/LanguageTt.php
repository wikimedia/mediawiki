<?php
/**
 * Tatar (Татарча/Tatarça) specific code.
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

require_once( dirname(__FILE__).'/../LanguageConverter.php' );
require_once( dirname(__FILE__).'/LanguageTt_cyrl.php' );

define( 'TT_C_UC', 'АӘБВГҒДЕЁЖҖЗИЙКҚЛМНҢОӨПРСТУҰҮФХҺЦЧШЩЪЫІЬЭЮЯ' ); # Tatar Cyrillic uppercase
define( 'TT_C_LC', 'аәбвгғдеёжҗзийкқлмнңоөпрстуұүфхһцчшщъыіьэюя' ); # Tatar Cyrillic lowercase
define( 'TT_L_UC', 'AÄBCÇDEÉFGĞHIİÏJKLMNÑOÖPQRSŞTUÜVWXYÝZ' ); # Tatar Latin uppercase
define( 'TT_L_LC', 'aäbcçdeéfgğhıiïjklmnñoöpqrsştuüvwxyýz' ); # Tatar Latin lowercase
define( 'H_HAMZA', 'ٴ' ); # U+0674 ARABIC LETTER HIGH HAMZA

/**
 * Tatar (Татарча/Tatarça) converter routines
 *
 * @ingroup Language
 */
class TtConverter extends LanguageConverter {

	function __construct( $langobj, $maincode,
								$variants=array(),
								$variantfallbacks=array(),
								$markup=array(),
								$flags = array()) {
		parent::__construct( $langobj, $maincode,
			$variants, $variantfallbacks, $markup, $flags );

		// No point delaying this since they're in code.
		// Waiting until loadDefaultTables() means they never get loaded
		// when the tables themselves are loaded from cache.
		$this->loadRegs();
	}

	function loadDefaultTables() {
		// require( dirname(__FILE__)."/../../includes/TtConversion.php" );
		// Placeholder for future implementing. Remove variables declarations
		// after generating TtConversion.php
		$tt2Cyrl = array();
		$tt2Latn = array();
		$tt2Arab = array();
		$tt2KZ   = array();
		$tt2TR   = array();
		$tt2CN   = array();

		$this->mTables = array(
			'tt-cyrl' => new ReplacementArray( $tt2Cyrl ),
			'tt-latn' => new ReplacementArray( $tt2Latn ),
			'tt-arab' => new ReplacementArray( $tt2Arab ),
			'tt-kz'   => new ReplacementArray( array_merge($tt2Cyrl, $tt2KZ) ),
			'tt-tr'   => new ReplacementArray( array_merge($tt2Latn, $tt2TR) ),
			'tt-cn'   => new ReplacementArray( array_merge($tt2Arab, $tt2CN) ),
			'tt'      => new ReplacementArray()
		);
	}

	function postLoadTables() {
		$this->mTables['tt-kz']->merge( $this->mTables['tt-cyrl'] );
		$this->mTables['tt-tr']->merge( $this->mTables['tt-latn'] );
		$this->mTables['tt-cn']->merge( $this->mTables['tt-arab'] );
	}

	function loadRegs() {

		$this->mCyrl2Latn = array(
			## Punctuation
			'/№/u' => 'No.',
			## Е after vowels
			'/([АӘЕЁИОӨҰҮЭЮЯЪЬ])Е/u' => '$1YE',
			'/([АӘЕЁИОӨҰҮЭЮЯЪЬ])е/ui' => '$1ye',
			## leading ЁЮЯЩ
			'/^Ё(['.TT_C_UC.']|$)/u' => 'YO$1', '/^Ё(['.TT_C_LC.']|$)/u' => 'Yo$1',
			'/^Ю(['.TT_C_UC.']|$)/u' => 'YU$1', '/^Ю(['.TT_C_LC.']|$)/u' => 'Yu$1',
			'/^Я(['.TT_C_UC.']|$)/u' => 'YA$1', '/^Я(['.TT_C_LC.']|$)/u' => 'Ya$1',
			'/^Щ(['.TT_C_UC.']|$)/u' => 'ŞÇ$1', '/^Щ(['.TT_C_LC.']|$)/u' => 'Şç$1',
			## other ЁЮЯ
			'/Ё/u' => 'YO', '/ё/u' => 'yo',
			'/Ю/u' => 'YU', '/ю/u' => 'yu',
			'/Я/u' => 'YA', '/я/u' => 'ya',
			'/Щ/u' => 'ŞÇ', '/щ/u' => 'şç',
			'/Ц/u' => 'Ts', '/ц/u' => 'ts',
			## soft and hard signs
			'/[ъЪ]/u' => 'ʺ', '/[ьЬ]/u' => 'ʹ',
			## other characters
			'/А/u' => 'A', '/а/u' => 'a', '/Ә/u' => 'Ä', '/ә/u' => 'ä',
			'/Б/u' => 'B', '/б/u' => 'b', '/В/u' => 'V', '/в/u' => 'v',
			'/Г/u' => 'G', '/г/u' => 'g', '/Ғ/u' => 'Ğ', '/ғ/u' => 'ğ',
			'/Д/u' => 'D', '/д/u' => 'd', '/Е/u' => 'E', '/е/u' => 'e',
			'/Ж/u' => 'J', '/ж/u' => 'j', '/З/u' => 'Z', '/з/u' => 'z',
			'/И/u' => 'İ', '/и/u' => 'i', '/Й/u' => 'Y', '/й/u' => 'y',
			'/К/u' => 'K', '/к/u' => 'k', '/Қ/u' => 'Q', '/қ/u' => 'q',
			'/Л/u' => 'L', '/л/u' => 'l', '/М/u' => 'M', '/м/u' => 'm',
			'/Н/u' => 'N', '/н/u' => 'n', '/Ң/u' => 'Ñ', '/ң/u' => 'ñ',
			'/О/u' => 'O', '/о/u' => 'o', '/Ө/u' => 'Ö', '/ө/u' => 'ö',
			'/П/u' => 'P', '/п/u' => 'p', '/Р/u' => 'R', '/р/u' => 'r',
			'/С/u' => 'S', '/с/u' => 's', '/Т/u' => 'T', '/т/u' => 't',
			'/У/u' => 'U', '/у/u' => 'u', '/Ұ/u' => 'U', '/ұ/u' => 'u',
			'/Ү/u' => 'Ü', '/ү/u' => 'ü', '/Ф/u' => 'F', '/ф/u' => 'f',
			'/Х/u' => 'X', '/х/u' => 'x', '/Һ/u' => 'H', '/һ/u' => 'h',
						      '/Ч/u' => 'Ç', '/ч/u' => 'ç',
			'/Ш/u' => 'Ş', '/ш/u' => 'ş', '/Ы/u' => 'I', '/ы/u' => 'ı',
			'/І/u' => 'İ', '/і/u' => 'i', '/Э/u' => 'E', '/э/u' => 'e',
			'/Җ/u' => 'C', '/җ/u' => 'c',
		);

		$this->mLatn2Cyrl = array(
			## Punctuation
			'/#|No\./' => '№',
			## Şç
			'/ŞÇʹ/u'=> 'ЩЬ', '/Şçʹ/u'=> 'Щь', '/Şçʹ/u'=> 'Щь',
			'/Ş[Çç]/u' => 'Щ', '/şç/u' => 'щ',
			## soft and hard signs
			'/(['.TT_L_UC.'])ʺ(['.TT_L_UC.'])/u' => '$1Ъ$2',
			'/ʺ(['.TT_L_LC.'])/u' => 'ъ$1',
			'/(['.TT_L_UC.'])ʹ(['.TT_L_UC.'])/u' => '$1Ь$2',
			'/ʹ(['.TT_L_LC.'])/u' => 'ь$1',
			'/ʺ/u' => 'ъ',
			'/ʹ/u' => 'ь',
			## Ye Yo Yu Ya.
			'/Y[Ee]/u' => 'Е', '/ye/u' => 'е',
			'/Y[Oo]/u' => 'Ё', '/yo/u' => 'ё',
			'/Y[UWuw]/u' => 'Ю', '/y[uw]/u' => 'ю',
			'/Y[Aa]/u' => 'Я', '/ya/u' => 'я',
			## other characters
			'/A/u' => 'А', '/a/u' => 'а', '/Ä/u' => 'Ә', '/ä/u' => 'ә',
			'/B/u' => 'Б', '/b/u' => 'б', '/C/u' => 'Җ', '/c/u' => 'җ',
			'/Ç/u' => 'Ч', '/ç/u' => 'ч', '/D/u' => 'Д', '/d/u' => 'д',
			'/E/u' => 'Е', '/e/u' => 'е', '/É/u' => 'Э', '/é/u' => 'э',
			'/F/u' => 'Ф', '/f/u' => 'ф', '/G/u' => 'Г', '/g/u' => 'г',
			'/Ğ/u' => 'Ғ', '/ğ/u' => 'ғ', '/H/u' => 'Һ', '/h/u' => 'һ',
			'/I/u' => 'Ы', '/ı/u' => 'ы', '/İ/u' => 'И', '/i/u' => 'и',
			'/Ï/u' => 'И', '/ï/u' => 'и', '/J/u' => 'Ж', '/j/u' => 'ж',
			'/K/u' => 'К', '/k/u' => 'к', '/L/u' => 'Л', '/l/u' => 'л',
			'/M/u' => 'М', '/m/u' => 'м', '/N/u' => 'Н', '/n/u' => 'н',
			'/Ñ/u' => 'Ң', '/ñ/u' => 'ң', '/O/u' => 'О', '/o/u' => 'о',
			'/Ö/u' => 'Ө', '/ö/u' => 'ө', '/P/u' => 'П', '/p/u' => 'п',
			'/Q/u' => 'Қ', '/q/u' => 'қ', '/R/u' => 'Р', '/r/u' => 'р',
			'/S/u' => 'С', '/s/u' => 'с', '/Ş/u' => 'Ш', '/ş/u' => 'ш',
			'/T/u' => 'Т', '/t/u' => 'т', '/U/u' => 'У', '/u/u' => 'у',
			'/Ü/u' => 'Ү', '/ü/u' => 'ү', '/V/u' => 'В', '/v/u' => 'в',
			'/W/u' => 'У', '/w/u' => 'у', '/Ý/u' => 'Й', '/ý/u' => 'й',
			'/X/u' => 'Х', '/x/u' => 'х', '/Z/u' => 'З', '/z/u' => 'з',
						      '/Y/u' => 'Й', '/y/u' => 'й',
		);

		$this->mCyLa2Arab = array(
			## Punctuation -> Arabic
			'/#|№|No\./u' => '؀', # &#x0600;
			'/\,/' => '،', # &#x060C;
			'/;/'  => '؛', # &#x061B;
			'/\?/' => '؟', # &#x061F;
			'/%/'  => '٪', # &#x066A;
			'/\*/' => '٭', # &#x066D;
			## Digits -> Arabic
			'/0/' => '۰', # &#x06F0;
			'/1/' => '۱', # &#x06F1;
			'/2/' => '۲', # &#x06F2;
			'/3/' => '۳', # &#x06F3;
			'/4/' => '۴', # &#x06F4;
			'/5/' => '۵', # &#x06F5;
			'/6/' => '۶', # &#x06F6;
			'/7/' => '۷', # &#x06F7;
			'/8/' => '۸', # &#x06F8;
			'/9/' => '۹', # &#x06F9;
			## Cyrillic -> Arabic
			'/Аллаһ/ui' => 'ﷲ',
			'/([АӘЕЁИОӨҰҮЭЮЯЪЬ])е/ui' => '$1يە',
			'/[еэ]/ui' => 'ە', '/[ъь]/ui' => '',
			'/[аә]/ui' => 'ا', '/[оө]/ui' => 'و', '/[ұү]/ui' => 'ۇ', '/[ыі]/ui' => 'ى',
			'/[и]/ui' => 'ىي', '/ё/ui' => 'يو', '/ю/ui' => 'يۋ', '/я/ui' => 'يا', '/[й]/ui' => 'ي',
			'/ц/ui' => 'تس', '/щ/ui' => 'شش',
			'/һ/ui' => 'ح', '/ч/ui' => 'تش',
			#'/һ/ui' => 'ھ', '/ч/ui' => 'چ',
			'/б/ui' => 'ب', '/в/ui' => 'ۆ', '/г/ui' => 'گ', '/ғ/ui' => 'ع',
			'/д/ui' => 'د', '/ж/ui' => 'ج', '/з/ui' => 'ز', '/к/ui' => 'ك',
			'/қ/ui' => 'ق', '/л/ui' => 'ل', '/м/ui' => 'م', '/н/ui' => 'ن',
			'/ң/ui' => 'ڭ', '/п/ui' => 'پ', '/р/ui' => 'ر', '/с/ui' => 'س',
			'/т/ui' => 'ت', '/у/ui' => 'ۋ', '/ф/ui' => 'ف', '/х/ui' => 'ح',
			'/ш/ui' => 'ش',
			## Latin -> Arabic // commented for now...
			/*'/Allah/ui' => 'ﷲ',
			'/[eé]/ui' => 'ە', '/[yý]/ui' => 'ي', '/[ʺʹ]/ui' => '',
			'/[aä]/ui' => 'ا', '/[oö]/ui' => 'و', '/[uü]/ui' => 'ۇ',
			'/[ï]/ui' => 'ىي', '/[ıIiİ]/u' => 'ى',
			'/c/ui' => 'تس',
			'/ç/ui' => 'تش', '/h/ui' => 'ح',
			#'/ç/ui' => 'چ', '/h/ui' => 'ھ',
			'/b/ui' => 'ب','/d/ui' => 'د',
			'/f/ui' => 'ف', '/g/ui' => 'گ', '/ğ/ui' => 'ع',
			'/j/ui' => 'ج', '/k/ui' => 'ك', '/l/ui' => 'ل', '/m/ui' => 'م',
			'/n/ui' => 'ن', '/ñ/ui' => 'ڭ', '/p/ui' => 'پ', '/q/ui' => 'ق',
			'/r/ui' => 'ر', '/s/ui' => 'س', '/ş/ui' => 'ش', '/t/ui' => 'ت',
			'/v/ui' => 'ۆ', '/w/ui' => 'ۋ', '/x/ui' => 'ح', '/z/ui' => 'ز',*/
		);
	}

	/* rules should be defined as -{ekavian | iyekavian-} -or-
		-{code:text | code:text | ...}-
		update: delete all rule parsing because it's not used
				currently, and just produces a couple of bugs
	*/
	function parseManualRule($rule, $flags=array()) {
		if(in_array('T',$flags)){
			return parent::parseManualRule($rule, $flags);
		}

		// otherwise ignore all formatting
		foreach($this->mVariants as $v) {
			$carray[$v] = $rule;
		}

		return $carray;
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
	 *  - if there is no selected variant, leave the link
	 *    names as they were
	 *  - do not try to find variants for usernames
	 */
	function findVariantLink( &$link, &$nt, $ignoreOtherCond = false ) {
		// check for user namespace
		if(is_object($nt)){
			$ns = $nt->getNamespace();
			if($ns==NS_USER || $ns==NS_USER_TALK)
				return;
		}

		$oldlink=$link;
		parent::findVariantLink( $link, $nt, $ignoreOtherCond );
		if( $this->getPreferredVariant()==$this->mMainLanguageCode )
			$link=$oldlink;
	}

	/*
	 * An ugly function wrapper for parsing Image titles
	 * (to prevent image name conversion)
	 */
	function autoConvert($text, $toVariant=false) {
		global $wgTitle;
		if(is_object($wgTitle) && $wgTitle->getNameSpace()==NS_FILE){
			$imagename = $wgTitle->getNsText();
			if(preg_match("/^$imagename:/",$text)) return $text;
		}
		return parent::autoConvert($text,$toVariant);
	}

	/**
	 *  It translates text into variant
	 */
	function translate( $text, $toVariant ){
		global $wgContLanguageCode;
		$text = parent::translate( $text, $toVariant );

		$letters = '';
		switch( $toVariant ) {
			case 'tt-cyrl':
			case 'tt-kz':
				$letters = TT_L_UC . TT_L_LC . 'ʺʹ#0123456789';
				$wgContLanguageCode = 'tt';
				break;
			case 'tt-latn':
			case 'tt-tr':
				$letters = TT_C_UC . TT_C_LC . '№0123456789';
				$wgContLanguageCode = 'tt-Latn';
				break;
			case 'tt-arab':
			case 'tt-cn':
				$letters = TT_C_UC.TT_C_LC./*TT_L_UC.TT_L_LC.'ʺʹ'.*/',;\?%\*№0123456789';
				$wgContLanguageCode = 'tt-Arab';
				break;
			default:
				$wgContLanguageCode = 'tt';
				return $text;
		}
		// disable conversion variables like $1, $2...
		$varsfix = '\$[0-9]';

		$matches = preg_split( '/' . $varsfix . '[^' . $letters . ']+/u', $text, -1, PREG_SPLIT_OFFSET_CAPTURE);
		$mstart = 0;
		$ret = '';
		foreach( $matches as $m ) {
			$ret .= substr( $text, $mstart, $m[1]-$mstart );
			$ret .= $this->regsConverter( $m[0], $toVariant );
			$mstart = $m[1] + strlen($m[0]);
		}
		return $ret;
	}

	function regsConverter( $text, $toVariant ) {
		if ($text == '') return $text;

		$pat = array();
		$rep = array();
		switch( $toVariant ) {
			case 'tt-arab':
			case 'tt-cn':
				$letters = TT_C_LC.TT_C_UC/*.TT_L_LC.TT_L_UC*/;
				$front = 'әөүіӘӨҮІ'/*.'äöüiÄÖÜİ'*/;
				$excludes = 'еэгғкқЕЭГҒКҚ'/*.'eégğkqEÉGĞKQ'*/;
				// split text to words
				$matches = preg_split( '/[\b\s\-\.:]+/', $text, -1, PREG_SPLIT_OFFSET_CAPTURE);
				$mstart = 0;
				$ret = '';
				foreach( $matches as $m ) {
					$ret .= substr( $text, $mstart, $m[1] - $mstart );
					// is matched the word to front vowels?
					// exclude a words matched to е, э, г, к, к, қ,
					// them should be without hamza
					if ( preg_match('/['.$front.']/u', $m[0]) && !preg_match('/['.$excludes.']/u', $m[0]) ) {
						$ret .= preg_replace('/['.$letters.']+/u', H_HAMZA.'$0', $m[0]);
					} else {
						$ret .= $m[0];
					}
					$mstart = $m[1] + strlen($m[0]);
				}
				$text =& $ret;
				foreach( $this->mCyLa2Arab as $pat => $rep ) {
					$text = preg_replace( $pat, $rep, $text );
				}
				return $text;
				break;
			case 'tt-latn':
			case 'tt-tr':
				foreach( $this->mCyrl2Latn as $pat => $rep ) {
					$text = preg_replace( $pat, $rep, $text );
				}
				return $text;
				break;
			case 'tt-cyrl':
			case 'tt-kz':
				foreach( $this->mLatn2Cyrl as $pat => $rep ) {
					$text = preg_replace( $pat, $rep, $text );
				}
				return $text;
				break;
			default:
				return $text;
		}
	}

	/*
	 * We want our external link captions to be converted in variants,
	 * so we return the original text instead -{$text}-, except for URLs
	 */
	function markNoConversion( $text, $noParse=false ) {
		if( $noParse || preg_match( "/^https?:\/\/|ftp:\/\/|irc:\/\//", $text ) )
			return parent::markNoConversion( $text );
		return $text;
	}

	function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'tt' );
	}

}

/**
 * class that handles Cyrillic, Latin and Arabic scripts for Kazakh
 * right now it only distinguish tt_cyrl, tt_latn, tt_arab and tt_kz, tt_tr, tt_cn.
 *
 * @ingroup Language
 */
class LanguageTt extends LanguageTt_cyrl {

	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = array( 'tt', 'tt-cyrl', 'tt-latn', 'tt-arab'/*, 'tt-kz', 'tt-tr', 'tt-cn'*/ );
		$variantfallbacks = array(
			'tt'      => 'tt-cyrl',
			'tt-cyrl' => 'tt',
			'tt-latn' => 'tt',
			'tt-arab' => 'tt',
			/*'tt-kz'   => 'tt-cyrl',
			'tt-tr'   => 'tt-latn',
			'tt-cn'   => 'tt-arab'*/
		);

		$this->mConverter = new TtConverter( $this, 'tt', $variants, $variantfallbacks );

		$wgHooks['ArticleSaveComplete'][] = $this->mConverter;
	}

	/**
	 * Work around for right-to-left direction support in tt-arab and tt-cn
	 *
	 * @return bool
	 */
	function isRTL() {
		$variant = $this->getPreferredVariant();
		if ( $variant == 'tt-arab' || $variant == 'tt-cn' ) {
			return true;
		} else {
			return parent::isRTL();
		}
	}

	/*
	 * It fixes issue with ucfirst for transforming 'i' to 'İ'
	 *
	 */
	function ucfirst ( $string ) {
		$variant = $this->getPreferredVariant();
		if ( ($variant == 'tt-latn' || $variant == 'tt-tr') && $string[0] == 'i' ) {
			$string = 'İ' . substr( $string, 1 );
		} else {
			$string = parent::ucfirst( $string );
		}
		return $string;
	}

	/*
	 * It fixes issue with  lcfirst for transforming 'I' to 'ı'
	 *
	 */
	function lcfirst ( $string ) {
		$variant = $this->getPreferredVariant();
		if ( ($variant == 'tt-latn' || $variant == 'tt-tr') && $string[0] == 'I' ) {
			$string = 'ı' . substr( $string, 1 );
		} else {
			$string = parent::lcfirst( $string );
		}
		return $string;
	}

	function convertGrammar( $word, $case ) {
		wfProfileIn( __METHOD__ );

		$variant = $this->getPreferredVariant();
		switch ( $variant ) {
			case 'tt-arab':
			case 'tt-cn':
				$word = parent::convertGrammarTt_arab( $word, $case );
				break;
			case 'tt-latn':
			case 'tt-tr':
				$word = parent::convertGrammarTt_latn( $word, $case );
				break;
			case 'tt-cyrl':
			case 'tt-kz':
			case 'tt':
			default:
				$word = parent::convertGrammarTt_cyrl( $word, $case );
		}

		wfProfileOut( __METHOD__ );
		return $word;
	}
}
