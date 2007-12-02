<?php
/**
  * @addtogroup Language
  */
require_once( dirname(__FILE__).'/../LanguageConverter.php' );
require_once( dirname(__FILE__).'/LanguageZh_hans.php' );

class ZhConverter extends LanguageConverter {
	function loadDefaultTables() {
		require( dirname(__FILE__)."/../../includes/ZhConversion.php" );
		$this->mTables = array(
			'zh-hans' => new ReplacementArray( $zh2Hans ),
			'zh-hant' => new ReplacementArray( $zh2Hant ),
			'zh-cn'   => new ReplacementArray( array_merge($zh2Hans, $zh2CN) ),
			'zh-tw'   => new ReplacementArray( array_merge($zh2Hans, $zh2TW) ),
			'zh-sg'   => new ReplacementArray( array_merge($zh2Hans, $zh2SG) ),
			'zh-hk'   => new ReplacementArray( array_merge($zh2Hant, $zh2HK) ),
			'zh'      => new ReplacementArray
		);
	}

	function postLoadTables() {
		$this->mTables['zh-cn']->merge( $this->mTables['zh-hans'] );
		$this->mTables['zh-tw']->merge( $this->mTables['zh-hant'] );
		$this->mTables['zh-sg']->merge( $this->mTables['zh-hans'] );
		$this->mTables['zh-hk']->merge( $this->mTables['zh-hant'] );
	}

	/* there shouldn't be any latin text in Chinese conversion, so no need
	   to mark anything.
	   $noParse is there for compatibility with LanguageConvert::markNoConversion
    */
	function markNoConversion($text, $noParse = false) {
		return $text;
	}

	function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'zh' );
	}
}


/* class that handles both Traditional and Simplified Chinese
   right now it only distinguish zh_hans, zh_hant, zh_cn, zh_tw, zh_sg and zh_hk.
*/
class LanguageZh extends LanguageZh_hans {

	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = array('zh', 'zh-hans', 'zh-hant', 'zh-cn', 'zh-tw', 'zh-sg', 'zh-hk');
		$variantfallbacks = array(
			'zh'      => 'zh-hans',
			'zh-hans' => 'zh-cn',
			'zh-hant' => 'zh-tw',
			'zh-cn'   => 'zh-hans',
			'zh-sg'   => 'zh-hans',
			'zh-tw'   => 'zh-hant',
			'zh-hk'   => 'zh-hant'
		);

		$this->mConverter = new ZhConverter( $this, 'zh', $variants, $variantfallbacks );

		$wgHooks['ArticleSaveComplete'][] = $this->mConverter;
	}


	# this should give much better diff info
	function segmentForDiff( $text ) {
		return preg_replace(
			"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
			"' ' .\"$1\"", $text);
	}

	function unsegmentForDiff( $text ) {
		return preg_replace(
			"/ ([\\xc0-\\xff][\\x80-\\xbf]*)/e",
			"\"$1\"", $text);
	}

	// word segmentation
	function stripForSearch( $string ) {
		$fname="LanguageZh::stripForSearch";
		wfProfileIn( $fname );

		// eventually this should be a word segmentation
		// for now just treat each character as a word
		$t = preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
				"' ' .\"$1\"", $string);

        //always convert to zh-hans before indexing. it should be
		//better to use zh-hans for search, since conversion from
		//Traditional to Simplified is less ambiguous than the
		//other way around

		$t = $this->mConverter->autoConvert($t, 'zh-hans');
		$t = parent::stripForSearch( $t );
		wfProfileOut( $fname );
		return $t;

	}

	function convertForSearchResult( $termsArray ) {
		$terms = implode( '|', $termsArray );
		$terms = implode( '|', $this->mConverter->autoConvertToAllVariants( $terms ) );
		$ret = array_unique( explode('|', $terms) );
		return $ret;
	}

}
