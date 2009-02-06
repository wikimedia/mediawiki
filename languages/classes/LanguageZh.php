<?php

require_once( dirname(__FILE__).'/../LanguageConverter.php' );
require_once( dirname(__FILE__).'/LanguageZh_hans.php' );

/**
 * @ingroup Language
 */
class ZhConverter extends LanguageConverter {

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
			'zh'      => '原文',
			'zh-hans' => '简体',
			'zh-hant' => '繁體',
			'zh-cn'   => '大陆',
			'zh-tw'   => '台灣',
			'zh-hk'   => '香港',
			'zh-mo'   => '澳門',
			'zh-sg'   => '新加坡',
			'zh-my'   => '大马',
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
		$this->mNamespaceTables['zh-hans'] = array(
			'Media'          => '媒体',
			'Special'        => '特殊',
			'Talk'           => '讨论',
			'User'           => '用户',
			'User Talk'           => '用户讨论',
			$nsproject
					=> isset($projecttable[$nsproject]) ? 
						$projecttable[$nsproject] : $nsproject,
			$nsproject . ' talk'
					=> isset($projecttable[$nsproject]) ?
						$projecttable[$nsproject] . '讨论' : $nsproject . '讨论',
			'File'           => '文件',
			'File talk'      => '文件讨论',
			'MediaWiki'      => 'MediaWiki',
			'MediaWiki talk' => 'MediaWiki讨论',
			'Template'       => '模板',
			'Template talk'  => '模板讨论',
			'Help'           => '帮助',
			'Help talk'      => '帮助讨论',
			'Category'       => '分类',
			'Category talk'  => '分类讨论',
		);
		$this->mNamespaceTables['zh-hant'] = array_merge($this->mNamespaceTables['zh-hans']);
		$this->mNamespaceTables['zh-hant']['File'] = '檔案';
		$this->mNamespaceTables['zh-hant']['File talk'] = '檔案討論';
		$this->mNamespaceTables['zh'] = array_merge($this->mNamespaceTables['zh-hans']);
		$this->mNamespaceTables['zh-cn'] = array_merge($this->mNamespaceTables['zh-hans']);
		$this->mNamespaceTables['zh-hk'] = array_merge($this->mNamespaceTables['zh-hant']);
		$this->mNamespaceTables['zh-mo'] = array_merge($this->mNamespaceTables['zh-hant']);
		$this->mNamespaceTables['zh-my'] = array_merge($this->mNamespaceTables['zh-hans']);
		$this->mNamespaceTables['zh-sg'] = array_merge($this->mNamespaceTables['zh-hans']);
		$this->mNamespaceTables['zh-tw'] = array_merge($this->mNamespaceTables['zh-hant']);
	}

	function loadDefaultTables() {
		require( dirname(__FILE__)."/../../includes/ZhConversion.php" );
		$this->mTables = array(
			'zh-hans' => new ReplacementArray( $zh2Hans ),
			'zh-hant' => new ReplacementArray( $zh2Hant ),
			'zh-cn'   => new ReplacementArray( array_merge($zh2Hans, $zh2CN) ),
			'zh-hk'   => new ReplacementArray( array_merge($zh2Hant, $zh2HK) ),
			'zh-mo'   => new ReplacementArray( array_merge($zh2Hant, $zh2HK) ),
			'zh-my'   => new ReplacementArray( array_merge($zh2Hans, $zh2SG) ),
			'zh-sg'   => new ReplacementArray( array_merge($zh2Hans, $zh2SG) ),
			'zh-tw'   => new ReplacementArray( array_merge($zh2Hant, $zh2TW) ),
			'zh'      => new ReplacementArray
		);
	}

	function postLoadTables() {
		$this->mTables['zh-cn']->merge( $this->mTables['zh-hans'] );
		$this->mTables['zh-hk']->merge( $this->mTables['zh-hant'] );
		$this->mTables['zh-mo']->merge( $this->mTables['zh-hant'] );
		$this->mTables['zh-my']->merge( $this->mTables['zh-hans'] );
		$this->mTables['zh-sg']->merge( $this->mTables['zh-hans'] );
		$this->mTables['zh-tw']->merge( $this->mTables['zh-hant'] );
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

/**
 * class that handles both Traditional and Simplified Chinese
 * right now it only distinguish zh_hans, zh_hant, zh_cn, zh_tw, zh_sg and zh_hk.
 *
 * @ingroup Language
 */
class LanguageZh extends LanguageZh_hans {

	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = array('zh','zh-hans','zh-hant','zh-cn','zh-hk','zh-mo','zh-my','zh-sg','zh-tw');
		$variantfallbacks = array(
			'zh'      => array('zh-hans','zh-hant','zh-cn','zh-tw','zh-hk','zh-sg','zh-mo','zh-my'),
			'zh-hans' => array('zh-cn','zh-sg','zh-my'),
			'zh-hant' => array('zh-tw','zh-hk','zh-mo'),
			'zh-cn'   => array('zh-hans','zh-sg','zh-my'),
			'zh-sg'   => array('zh-hans','zh-cn','zh-my'),
			'zh-my'   => array('zh-hans','zh-sg','zh-cn'),
			'zh-tw'   => array('zh-hant','zh-hk','zh-mo'),
			'zh-hk'   => array('zh-hant','zh-mo','zh-tw'),
			'zh-mo'   => array('zh-hant','zh-hk','zh-tw'),
		);
		$ml=array(
			'zh'      => 'disable',
			'zh-hans' => 'unidirectional',
			'zh-hant' => 'unidirectional',
		);

		$this->mConverter = new ZhConverter( $this, 'zh',
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

        //always convert to zh-hans before indexing. it should be
		//better to use zh-hans for search, since conversion from
		//Traditional to Simplified is less ambiguous than the
		//other way around

		$t = $this->mConverter->autoConvert($t, 'zh-hans');
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

