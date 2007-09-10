<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
require_once( dirname(__FILE__).'/../LanguageConverter.php' );
require_once( dirname(__FILE__).'/LanguageZh_cn.php' );

class ZhConverter extends LanguageConverter {
	function loadDefaultTables() {
		require( "includes/ZhConversion.php" );
		$this->mTables = array();
		$this->mTables['zh-cn'] = $zh2CN;
		$this->mTables['zh-tw'] = $zh2TW;
		$this->mTables['zh-sg'] = array_merge($zh2CN, $zh2SG);
		$this->mTables['zh-hk'] = array_merge($zh2TW, $zh2HK);
		$this->mTables['zh'] = array();
	}

	function postLoadTables() {
		$this->mTables['zh-sg'] = array_merge($this->mTables['zh-cn'], $this->mTables['zh-sg']);
		$this->mTables['zh-hk'] = array_merge($this->mTables['zh-tw'], $this->mTables['zh-hk']);
    }

	/* there shouldn't be any latin text in Chinese conversion, so no need
	   to mark anything
    */
	function markNoConversion($text) {
		return $text;
	}

	function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'zh-cn' );
	}
}


/* class that handles both Traditional and Simplified Chinese
   right now it only distinguish zh_cn, zh_tw, zh_sg and zh_hk.
*/
class LanguageZh extends LanguageZh_cn {

	function __construct() {
		global $wgHooks;
		parent::__construct();
		$this->mConverter = new ZhConverter($this, 'zh',
                                            array('zh', 'zh-cn', 'zh-tw', 'zh-sg', 'zh-hk'),
											array('zh'=>'zh-cn',
												  'zh-cn'=>'zh-sg',
												  'zh-sg'=>'zh-cn',
												  'zh-tw'=>'zh-hk',
												  'zh-hk'=>'zh-tw'));
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

        //always convert to zh-cn before indexing. it should be
		//better to use zh-cn for search, since conversion from
		//Traditional to Simplified is less ambiguous than the
		//other way around

		$t = $this->mConverter->autoConvert($t, 'zh-cn');
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
?>
