<?php
/** Kazakh (Қазақша)
  * converter routines
  *
  * @addtogroup Language
  */

require_once( dirname(__FILE__).'/../LanguageConverter.php' );
require_once( dirname(__FILE__).'/LanguageKk_cyrl.php' );

class KkConverter extends LanguageConverter {
	var $mLatinToCyrillic = array(
		'YA' => 'Я', 'Ya' => 'Я', 'ya' => 'я', 'YE' => 'Е', 'Ye' => 'У', 'ye' => 'е',
		'YO' => 'Ё', 'Yo' => 'Ё', 'yo' => 'ё', 'YU' => 'Ю', 'Yu' => 'Ю', 'yu' => 'ю',
		'YW' => 'Ю', 'Yw' => 'Ю', 'yw' => 'ю',

		'bʺ' => 'бъ', 'dʺ' => 'дъ', 'fʺ' => 'фъ', 'gʺ' => 'гъ', 'kʺ' => 'къ', 'lʺ' => 'лъ',
		'mʺ' => 'мъ', 'nʺ' => 'нъ', 'pʺ' => 'пъ', 'rʺ' => 'ръ', 'sʺ' => 'съ', 'tʺ' => 'тъ',
		'vʺ' => 'въ', 'zʺ' => 'зъ',
	 /* 'jʺ' => 'жъ', 'cʺ' => 'цъ', 'çʺ' => 'чъ', 'şʺ' => 'шъ', */

		'ŞÇʹ'=> 'ЩЬ', 'Şçʹ'=> 'Щь',  'Bʺ' => 'БЪ', 'Dʺ' => 'ДЪ', 'Fʺ' => 'ФЪ', 'Gʺ' => 'ГЪ', 'Kʺ' => 'КЪ', 'Lʺ' => 'ЛЪ',
		'Mʺ' => 'МЪ', 'Nʺ' => 'НЪ', 'Pʺ' => 'ПЪ', 'Rʺ' => 'РЪ', 'Sʺ' => 'СЪ', 'Tʺ' => 'ТЪ',
		'Vʺ' => 'ВЪ', 'Zʺ' => 'ЗЪ',
	 /* 'Jʺ' => 'ЖЪ', 'Cʺ' => 'ЦЪ', 'Çʺ' => 'ЧЪ', 'Şʺ' => 'ШЪ', */

		'şçʹ'=> 'щь', 'bʹ' => 'бь', 'dʹ' => 'дь', 'fʹ' => 'фь', 'gʹ' => 'гь', 'kʹ' => 'кь', 'lʹ' => 'ль',
		'mʹ' => 'мь', 'nʹ' => 'нь', 'pʹ' => 'пь', 'rʹ' => 'рь', 'sʹ' => 'сь', 'tʹ' => 'ть',
		'vʹ' => 'вь', 'zʹ' => 'зь', 'jʹ' => 'жь', 'cʹ' => 'ць', 'çʹ' => 'чь', 'şʹ' => 'шь',

		'Bʹ' => 'БЬ', 'Dʹ' => 'ДЬ', 'Fʹ' => 'ФЬ', 'Gʹ' => 'ГЬ', 'Kʹ' => 'КЬ', 'Lʹ' => 'ЛЬ',
		'Mʹ' => 'МЬ', 'Nʹ' => 'НЬ', 'Pʹ' => 'ПЬ', 'Rʹ' => 'РЬ', 'Sʹ' => 'СЬ', 'Tʹ' => 'ТЬ',
		'Vʹ' => 'ВЬ', 'Zʹ' => 'ЗЬ', 'Jʹ' => 'ЖЬ', 'Cʹ' => 'ЦЬ', 'Çʹ' => 'ЧЬ', 'Şʹ' => 'ШЬ',

		'ŞÇ' => 'Щ', 'Şç' => 'Щ', 'şç' => 'щ',

		'a' => 'а', 'ä' => 'ә', 'b' => 'б', 'c' => 'ц', 'ç' => 'ч', 'd' => 'д', 'e' => 'е',
		'é' => 'э', 'f' => 'ф', 'g' => 'г', 'ğ' => 'ғ', 'h' => 'һ', 'i' => 'і', 'ı' => 'ы',
		'ï' => 'и', 'j' => 'ж', 'k' => 'к', 'l' => 'л', 'm' => 'м', 'n' => 'н', 'ñ' => 'ң',
		'o' => 'о', 'ö' => 'ө', 'p' => 'п', 'q' => 'қ', 'r' => 'р', 's' => 'с', 'ş' => 'ш',
		't' => 'т', 'u' => 'ұ', 'ü' => 'ү', 'v' => 'в', 'w' => 'у', 'x' => 'х', 'ý' => 'й',
		'z' => 'з',

		'A' => 'А', 'Ä' => 'Ә', 'B' => 'Б', 'C' => 'Ц', 'Ç' => 'Ч', 'D' => 'Д', 'E' => 'Е',
		'É' => 'Э', 'F' => 'Ф', 'G' => 'Г', 'Ğ' => 'Ғ', 'H' => 'Һ', 'İ' => 'І', 'I' => 'Ы',
		'Ï' => 'И', 'J' => 'Ж', 'K' => 'К', 'L' => 'Л', 'M' => 'М', 'N' => 'Н', 'Ñ' => 'Ң',
		'O' => 'О', 'Ö' => 'Ө', 'P' => 'П', 'Q' => 'Қ', 'R' => 'Р', 'S' => 'С', 'Ş' => 'Ш',
		'T' => 'Т', 'U' => 'Ұ', 'Ü' => 'Ү', 'V' => 'В', 'W' => 'У', 'Ý' => 'Й', 'X' => 'Х',
		'Z' => 'З'
	);

	var $mCyrillicToLatin = array(
		'а' => 'a',  'ә' => 'ä',  'б' => 'b',  'в' => 'v',  'г' => 'g',  'ғ' => 'ğ',
		'д' => 'd',  'е' => 'e',  'ё' => 'yo', 'ж' => 'j',  'з' => 'z',  'и' => 'ï',
		'й' => 'ý',  'к' => 'k',  'қ' => 'q',  'л' => 'l',  'м' => 'm',  'н' => 'n',
		'ң' => 'ñ',  'о' => 'o',  'ө' => 'ö',  'п' => 'p',  'р' => 'r',  'с' => 's',
		'т' => 't',  'у' => 'w',  'ұ' => 'u',  'ү' => 'ü',  'ф' => 'f',  'х' => 'x',
		'һ' => 'h',  'ц' => 'c',  'ч' => 'ç',  'ш' => 'ş',  'щ' => 'şç', 'ъ' => 'ʺ',
		'ы' => 'ı',  'ь' => 'ʹ',  'і' => 'i',  'э' => 'é',  'ю' => 'yw', 'я' => 'ya',

		'А' => 'A',  'Ә' => 'Ä',  'Б' => 'B',  'В' => 'V',  'Г' => 'G',  'Ғ' => 'Ğ',
		'Д' => 'D',  'Е' => 'E',  'Ё' => 'Yo', 'Ж' => 'J',  'З' => 'Z',  'И' => 'Ï',
		'Й' => 'Ý',  'К' => 'K',  'Қ' => 'Q',  'Л' => 'L',  'М' => 'M',  'Н' => 'N',
		'Ң' => 'Ñ',  'О' => 'O',  'Ө' => 'Ö',  'П' => 'P',  'Р' => 'R',  'С' => 'S',
		'Т' => 'T',  'У' => 'W',  'Ұ' => 'U',  'Ү' => 'Ü',  'Ф' => 'F',  'Х' => 'X',
		'Һ' => 'H',  'Ц' => 'C',  'Ч' => 'Ç',  'Ш' => 'Ş',  'Щ' => 'Şç', 'Ъ' => 'ʺ',
		'Ы' => 'I',  'Ь' => 'ʹ',  'І' => 'İ',  'Э' => 'É',  'Ю' => 'Yw', 'Я' => 'Ya'
	);

	var $mCyrillicToArabic = array(
		'ла' => 'لا',  'лА' => 'لا',  'ЛА' => 'لا',  'Ла' => 'لا',

		'а' => 'ا',  'ә' => 'ٵ',  'б' => 'ب',  'в' => 'ۆ',  'г' => 'گ',  'ғ' => 'ع',
		'д' => 'د',  'е' => 'ە',  'ё' => 'يو', 'ж' => 'ج',  'з' => 'ز',  'и' => 'ي',
		'й' => 'ي',  'к' => 'ك',  'қ' => 'ق',  'л' => 'ل',  'м' => 'م',  'н' => 'ن',
		'ң' => 'ڭ',  'о' => 'و',  'ө' => 'ٶ',  'п' => 'پ',  'р' => 'ر',  'с' => 'س',
		'т' => 'ت',  'у' => 'ۋ',  'ұ' => 'ۇ',  'ү' => 'ٷ',  'ф' => 'ف',  'х' => 'ح',
		'һ' => 'ھ',  'ц' => 'تس',  'ч' => 'چ',  'ш' => 'ش',  'щ' => 'شش', 'ъ' => 'ي',
		'ы' => 'ى',  'ь' => 'ي',  'і' => 'ٸ',  'э' => 'ە',  'ю' => 'يۋ', 'я' => 'يا',

		'А' => 'ا',  'Ә' => 'ٵ',  'Б' => 'ب',  'В' => 'ۆ',  'Г' => 'گ',  'Ғ' => 'ع',
		'Д' => 'د',  'Е' => 'ە',  'Ё' => 'يو',  'Ж' => 'ج',  'З' => 'ز',  'И' => 'ي',
		'Й' => 'ي',  'К' => 'ك',  'Қ' => 'ق',  'Л' => 'ل',  'М' => 'م',  'Н' => 'ن',
		'Ң' => 'ڭ',  'О' => 'و',  'Ө' => 'ٶ',  'П' => 'پ',  'Р' => 'ر',  'С' => 'س',
		'Т' => 'ت',  'У' => 'ۋ',  'Ұ' => 'ۇ',  'Ү' => 'ٷ',  'Ф' => 'ف',  'Х' => 'ح',
		'Һ' => 'ھ',  'Ц' => 'تس',  'Ч' => 'چ',  'Ш' => 'ش',  'Щ' => 'شش', 'Ъ' => 'ي',
		'Ы' => 'ى',  'Ь' => 'ي',  'І' => 'ٸ',  'Э' => 'ە',  'Ю' => 'يۋ', 'Я' => 'يا',

		'?' => '؟',
		'%' => '٪',
		',' => '،',
		';' => '؛'
	);

	var $mLatinToArabic = array(
		'la' => 'لا',  'lA' => 'لا',  'LA' => 'لا',  'La' => 'لا',

		'a' => 'ا',  'ä' => 'ٵ',  'b' => 'ب',  'v' => 'ۆ',  'g' => 'گ',  'ğ' => 'ع',
		'd' => 'د',  'e' => 'ە',  'yo' => 'يو', 'j' => 'ج',  'z' => 'ز',  'ï' => 'ي',
		'ý' => 'ي',  'k' => 'ك',  'q' => 'ق',  'l' => 'ل',  'm' => 'م',  'n' => 'ن',
		'ñ' => 'ڭ',  'o' => 'و',  'ö' => 'ٶ',  'p' => 'پ',  'r' => 'ر',  's' => 'س',
		't' => 'ت',  'w' => 'ۋ',  'u' => 'ۇ',  'ü' => 'ٷ',  'f' => 'ف',  'x' => 'ح',
		'h' => 'ھ',  'c' => 'تس',  'ç' => 'چ',  'ş' => 'ش',  'şş' => 'شش', '″' => 'ي',
		'ı' => 'ى',  '′' => 'ي',  'i' => 'ٸ',  'é' => 'ە',

		'A' => 'ا',  'Ä' => 'ٵ',  'B' => 'ب',  'V' => 'ۆ',  'G' => 'گ',  'Ğ' => 'ع',
		'D' => 'د',  'E' => 'ە',  'YO' => 'يو',  'J' => 'ج',  'Z' => 'ز',  'Ï' => 'ي',
		'Ý' => 'ي',  'K' => 'ك',  'Q' => 'ق',  'L' => 'ل',  'M' => 'م',  'N' => 'ن',
		'Ñ' => 'ڭ',  'O' => 'و',  'Ö' => 'ٶ',  'P' => 'پ',  'R' => 'ر',  'S' => 'س',
		'T' => 'ت',  'W' => 'ۋ',  'U' => 'ۇ',  'Ü' => 'ٷ',  'F' => 'ف',  'X' => 'ح',
		'H' => 'ھ',  'C' => 'تس',  'Ç' => 'چ',  'Ş' => 'ش',  'ŞŞ' => 'شش', '″' => 'ي',
		'I' => 'ى',  '′' => 'ي',  'İ' => 'ٸ',  'É' => 'ە',

		'?' => '؟',
		'%' => '٪',
		',' => '،',
		';' => '؛'
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'kk-cyrl' => new ReplacementArray( $this->mLatinToCyrillic ),
			'kk-latn' => new ReplacementArray( $this->mCyrillicToLatin ),
			'kk-arab' => new ReplacementArray( array_merge($this->mCyrillicToArabic, $this->mLatinToArabic) ),
			'kk-kz' => new ReplacementArray( $this->mLatinToCyrillic ),
			'kk-tr' => new ReplacementArray( $this->mCyrillicToLatin ),
			'kk-cn' => new ReplacementArray( array_merge($this->mCyrillicToArabic, $this->mLatinToArabic) ),
			'kk'    => new ReplacementArray()
		);
	}

	function postLoadTables() {
		$this->mTables['kk-kz']->merge( $this->mTables['kk-cyrl'] );
		$this->mTables['kk-tr']->merge( $this->mTables['kk-latn'] );
		$this->mTables['kk-cn']->merge( $this->mTables['kk-arab'] );
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

		// regexp for roman numbers
		$roman = 'M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})';

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
	}

}

/* class that handles Cyrillic, Latin and Arabic scripts for Kazakh
   right now it only distinguish kk_cyrl, kk_latn, kk_arab, kk_kz, kk_tr and kk_cn.
*/
class LanguageKk extends LanguageKk_cyrl {

	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = array( 'kk', 'kk-cyrl', 'kk-latn', 'kk-arab', 'kk-kz', 'kk-tr', 'kk-cn' );
		$variantfallbacks = array(
			'kk'		=> 'kk-kz',
			'kk-cyrl'	=> 'kk',
			'kk-latn'	=> 'kk',
			'kk-arab'	=> 'kk',
			'kk-kz'		=> 'kk-cyrl',
			'kk-tr'		=> 'kk-latn',
			'kk-cn'		=> 'kk-arab'
		);

		$this->mConverter = new KkConverter( $this, 'kk', $variants, $variantfallbacks );

		$wgHooks['ArticleSaveComplete'][] = $this->mConverter;
	}

	function convertGrammar( $word, $case ) {
		$fname="LanguageKk::convertGrammar";
		wfProfileIn( $fname );

		switch ( $this->getPreferredVariant() ) {
			case 'kk-cn':
			case 'kk-arab':
				$word = parent::convertGrammar( $word, $case, $variant='kk-arab' );
				break;
			case 'kk-tr':
			case 'kk-latn':
				$word = parent::convertGrammar( $word, $case, $variant='kk-latn' );
				break;
			case 'kk-kz':
			case 'kk-cyrl':
			case 'kk':
				$word = parent::convertGrammar( $word, $case, $variant='kk-cyrl' );
				break;
			default: #do nothing
		}

		wfProfileOut( $fname );
		return $word;
	}

	/*
	 * It fixes issue ucfirst with transforming 'i' to 'İ'
	 * 
	 */
	function ucfirst ( $string ) {
		if ( ($this->getPreferredVariant() == 'kk-tr' || $this->getPreferredVariant() == 'kk-latn') && $string[0] == 'i' ) {
			$string = 'İ' . substr( $string, 1 );
		} else {
			$string = parent::ucfirst( $string );
		}
		return $string;
	}

	/*
	 * It fixes issue for lcfirst with transforming 'I' to 'ı'
	 * 
	 */
	function lcfirst ( $string ) {
		if ( ($this->getPreferredVariant() == 'kk-tr' || $this->getPreferredVariant() == 'kk-latn') && $string[0] == 'I' ) {
			$string = 'ı' . substr( $string, 1 );
		} else {
			$string = parent::lcfirst( $string );
		}
		return $string;
	}

}


