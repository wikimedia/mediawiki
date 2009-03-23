<?php

require_once( dirname(__FILE__).'/../LanguageConverter.php' );
require_once( dirname(__FILE__).'/LanguageZh.php' );

/**
 * @ingroup Language
 */
class GanConverter extends LanguageConverter {

	function __construct($langobj, $maincode,
								$variants=array(),
								$variantfallbacks=array(),
								$markup=array(),
								$flags = array(),
								$manualLevel = array() ) {
		$this->mDescCodeSep = '：';
		$this->mDescVarSep = '；';
		parent::__construct($langobj, $maincode,
									$variants,
									$variantfallbacks,
									$markup,
									$flags,
									$manualLevel);
		$names = array(
			'gan'      => '原文',
			'gan-hans' => '简体',
			'gan-hant' => '繁體',
		);
		$this->mVariantNames = array_merge($this->mVariantNames,$names);
		$this->loadNamespaceTables();
	}
	
	function loadNamespaceTables() {
		global $wgMetaNamespace;
		$nsproject     = $wgMetaNamespace;
		$projecttable  = array(
			'Wikipedia'       => '维基百科',
			'Wikisource'      => '维基文库',
			'Wikinews'        => '维基新闻',
			'Wiktionary'      => '维基词典',
			'Wikibooks'       => '维基教科书',
			'Wikiquote'       => '维基语录',
		);
		$this->mNamespaceTables['gan-hans'] = array(
			'Media'          => '媒体',
			'Special'        => '特殊',
			'Talk'           => '談詑',
			'User'           => '用户',
			'User talk'      => '用户談詑',
			$nsproject
					=> isset($projecttable[$nsproject]) ? 
						$projecttable[$nsproject] : $nsproject,
			$nsproject . ' talk'
					=> isset($projecttable[$nsproject]) ?
						$projecttable[$nsproject] . '談詑' : $nsproject . '談詑',
			'File'           => '文件',
			'File talk'      => '文件談詑',
			'MediaWiki'      => 'MediaWiki',
			'MediaWiki talk' => 'MediaWiki談詑',
			'Template'       => '模板',
			'Template talk'  => '模板談詑',
			'Help'           => '帮助',
			'Help talk'      => '帮助談詑',
			'Category'       => '分类',
			'Category talk'  => '分类談詑',
		);
		$this->mNamespaceTables['gan-hant'] = array_merge($this->mNamespaceTables['gan-hans']);
		$this->mNamespaceTables['gan-hant']['File'] = '檔案';
		$this->mNamespaceTables['gan-hant']['File talk'] = '檔案談詑';
		$this->mNamespaceTables['gan'] = array_merge($this->mNamespaceTables['gan-hans']);
	}

	function loadDefaultTables() {
		require( dirname(__FILE__)."/../../includes/ZhConversion.php" );
		$this->mTables = array(
			'gan-hans' => new ReplacementArray( $zh2Hans ),
			'gan-hant' => new ReplacementArray( $zh2Hant ),
			'gan'      => new ReplacementArray
		);
	}

	/* there shouldn't be any latin text in Chinese conversion, so no need
	   to mark anything.
	   $noParse is there for compatibility with LanguageConvert::markNoConversion
	 */
	function markNoConversion($text, $noParse = false) {
		return $text;
	}

	function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'gan' );
	}
}

/**
 * class that handles both Traditional and Simplified Chinese
 * right now it only distinguish gan_hans, gan_hant.
 *
 * @ingroup Language
 */
class LanguageGan extends LanguageZh {

	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = array('gan','gan-hans','gan-hant');
		$variantfallbacks = array(
			'gan'      => array('gan-hans','gan-hant'),
			'gan-hans' => array('gan'),
			'gan-hant' => array('gan'),
		);
		$ml=array(
			'gan'      => 'disable',
		);

		$this->mConverter = new GanConverter( $this, 'gan',
								$variants, $variantfallbacks,
								array(),array(),
								$ml);

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
		wfProfileIn( __METHOD__ );

		// eventually this should be a word segmentation
		// for now just treat each character as a word
		// @fixme only do this for Han characters...
		$t = preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/",
				" $1", $string);

        //always convert to gan-hans before indexing. it should be
		//better to use gan-hans for search, since conversion from
		//Traditional to Simplified is less ambiguous than the
		//other way around

		$t = $this->mConverter->autoConvert($t, 'gan-hans');
		$t = parent::stripForSearch( $t );
		wfProfileOut( __METHOD__ );
		return $t;

	}

	function convertForSearchResult( $termsArray ) {
		$terms = implode( '|', $termsArray );
		$terms = implode( '|', $this->mConverter->autoConvertToAllVariants( $terms ) );
		$ret = array_unique( explode('|', $terms) );
		return $ret;
	}
}