<?php
require_once( "LanguageZh_cn.php");
require_once( "LanguageZh_tw.php");
require_once( "LanguageZh_sg.php");
require_once( "LanguageZh_hk.php");

/*
   hook to refresh the cache of conversion tables when 
   MediaWiki:zhconversiontable* is updated
*/
function zhOnArticleSaveComplete($article, $user, $text, $summary, $isminor, $iswatch, $section) {
	$titleobj = $article->getTitle();
	if($titleobj->getNamespace() == NS_MEDIAWIKI) { 
		global $wgContLang; // should be an LanguageZh.
		if(get_class($wgContLang) != 'languagezh')	
			return true;

		$title = $titleobj->getDBkey();
		$t = explode('/', $title, 3);
		$c = count($t);
		if( $c > 1 && $t[0] == 'Zhconversiontable' ) {
			if(in_array($t[1], array('zh-cn', 'zh-tw', 'zh-sg', 'zh-hk'))) {
				$wgContLang->reloadTables();
			}
		}
	}
	return true;
}

$wgHooks['ArticleSaveComplete'][] = 'zhOnArticleSaveComplete';

/* class that handles both Traditional and Simplified Chinese
   right now it only distinguish zh_cn and zh_tw (actuall, zh_cn and
   non-zh_cn), will add support for zh_sg, zh_hk, etc, later.
*/
class LanguageZh extends LanguageZh_cn {
	
	var $mZhLanguageCode=false;
	var $mTables=false; //the mapping tables
	var $mTablesLoaded = false;
	var $mCacheKey;
	var $mDoTitleConvert = true, $mDoContentConvert = true;
	function LanguageZh() {
		global $wgDBname;
		$this->mCacheKey = $wgDBname . ":zhtables";
	}

	// a write lock
	function lockCache() {
		global $wgMemc;
		$success = false;
		for($i=0; $i<30; $i++) {
			if($success = $wgMemc->add($this->mCacheKey . "lock", 1, 10))
				break;
			sleep(1);
		}
		return $success;		
	}

	function unlockCache() {
		global $wgMemc;
		$wgMemc->delete($this->mCacheKey . "lock");
	}

	function updateTable($code, $table) {
		global $wgMemc;
		if(!$this->mTablesLoaded)
			$this->loadTables();

		$this->mTables[$code] = array_merge($this->mTables[$code], $table);
		if($this->lockCache()) {
			$wgMemc->delete($this->mCacheKey);
			$wgMemc->set($this->mCacheKey, $this->mTables, 43200);
			$this->unlockCache();
		}
	}

	function reloadTables() {
		if($this->mTables)
			unset($this->mTables);
		$this->mTablesLoaded = false;
		$this->loadTables(false);
	}

	// load conversion tables either from the cache or the disk
	function loadTables($fromcache=true) {
		global $wgMemc;
		if( $this->mTablesLoaded )
			return;
		$this->mTablesLoaded = true;
		if($fromcache) {
			$this->mTables = $wgMemc->get( $this->mCacheKey );
			if( !empty( $this->mTables ) ) //all done
				return;
		}
		// not in cache, or we need a fresh reload. 
		// we will first load the tables from file
		// then update them using things in MediaWiki:Zhconversiontable/*
		global $wgMessageCache;
		require( "includes/ZhConversion.php" );
		$this->mTables = array();
		$this->mTables['zh-cn'] = $zh2CN;
		$this->mTables['zh-tw'] = $zh2TW;
		$this->mTables['zh-sg'] = $zh2SG;
		$this->mTables['zh-hk'] = $zh2HK;

		$cached = $this->parseCachedTable('zh-cn');
		$this->mTables['zh-cn'] = array_merge($this->mTables['zh-cn'], $cached);

		$cached = $this->parseCachedTable('zh-tw');
		$this->mTables['zh-tw'] = array_merge($this->mTables['zh-tw'], $cached);

		$cached = $this->parseCachedTable('zh-sg');
		$this->mTables['zh-sg'] = array_merge($this->mTables['zh-sg'], $cached);

		$cached = $this->parseCachedTable('zh-hk');
		$this->mTables['zh-hk'] = array_merge($this->mTables['zh-hk'], $cached);

		if($this->lockCache()) {
			$wgMemc->set($this->mCacheKey, $this->mTables, 43200);
			$this->unlockCache();
		}
	}
	
	/*
		parse the conversion table stored in the cache 

		the tables should be in blocks of the following form:

			-{
				word => word ;
				word => word ;
				...
			}-
		
		to make the tables more manageable, subpages are allowed
		and will be parsed recursively if $recursive=true

	*/
	function parseCachedTable($code, $subpage='', $recursive=true) {
		global $wgMessageCache;
		static $parsed = array();

		if(!is_object($wgMessageCache))
			return array();

		$key = 'zhconversiontable/'.$code;
		if($subpage)
			$key .= '/' . $subpage;

		if(array_key_exists($key, $parsed))
			return array();
	

		$txt = $wgMessageCache->get( $key, true, true, true );

		// get all subpage links of the form
		// [[MediaWiki:Zhconversiontable/zh-xx/...|...]]
		$linkhead = $this->getNsText(NS_MEDIAWIKI) . ':Zhconversiontable';
		$subs = explode('[[', $txt);
		$sublinks = array();
		foreach( $subs as $sub ) {
			$link = explode(']]', $sub, 2);
			if(count($link) != 2)
				continue;
			$b = explode('|', $link[0]);
			$b = explode('/', trim($b[0]), 3);
			if(count($b)==3)
				$sublink = $b[2];
			else	
				$sublink = '';

			if($b[0] == $linkhead && $b[1] == $code) {
				$sublinks[] = $sublink;
			}
		}


		// parse the mappings in this page
		$blocks = explode('-{', $txt);
		array_shift($blocks);
		$ret = array();	
		foreach($blocks as $block) {
			$mappings = explode('}-', $block, 2);
			$stripped = str_replace(array("'", '"', '*','#'), '', $mappings[0]);
			$table = explode( ';', $stripped );
			foreach( $table as $t ) {
				$m = explode( '=>', $t );
				if( count( $m ) != 2)
					continue;
				$ret[trim($m[0])] = trim($m[1]);
			}
		}
		$parsed[$key] = true;


		// recursively parse the subpages
		if($recursive) {
			foreach($sublinks as $link) {
				$s = $this->parseCachedTable($code, $link, $recursive);
				$ret = array_merge($ret, $s);
			}
		}
		return $ret;
	}

	/* 
		get preferred language variants.
	*/
	function getPreferredVariant() {
		global $wgUser, $wgRequest;
		
		if($this->mZhLanguageCode)
			return $this->mZhLanguageCode;

		// see if the preference is set in the request
		$zhreq = $wgRequest->getText( 'variant' );
		if( in_array( $zhreq, $this->getVariants() ) ) {
			$this->mZhLanguageCode = $zhreq;
			return $zhreq;
		}

		// get language variant preference from logged in users 
		if($wgUser->getID()!=0) {
			$this->mZhLanguageCode = $wgUser->getOption('variant');
		}

		if( !$this->mZhLanguageCode ) {
			// see if some zh- variant is set in the http header,
			$this->mZhLanguageCode="zh";
			$header = str_replace( '_', '-', strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]));
			$zh = strstr($header, 'zh-');
			if($zh) {
				$this->mZhLanguageCode = substr($zh,0,5);
			}
		}
		return $this->mZhLanguageCode;
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

	function autoConvert($text, $toVariant=false) {
		$fname="LanguageZh::autoConvert";
		wfProfileIn( $fname );

		if(!$this->mTablesLoaded)
			$this->loadTables();

		if(!$toVariant) 
			$toVariant = $this->getPreferredVariant();
		$ret = '';
		switch( $toVariant ) {
			case 'zh-cn': $ret = strtr($text, $this->mTables['zh-cn']);break;
			case 'zh-tw': $ret = strtr($text, $this->mTables['zh-tw']);break;
			case 'zh-sg': $ret = strtr(strtr($text, $this->mTables['zh-cn']), $this->mTables['zh-sg']);break;
			case 'zh-hk': $ret = strtr(strtr($text, $this->mTables['zh-tw']), $this->mTables['zh-hk']);break;
			default: $ret = $text;
		}
		wfProfileOut( $fname );
		return $ret;
	}
    
	function autoConvertToAllVariants($text) {
		$fname="LanguageZh::autoConvertToAllVariants";
		wfProfileIn( $fname );
		if( !$this->mTablesLoaded )
			$this->loadTables();

		$ret = array();
		$ret['zh-cn'] = strtr($text, $this->mTables['zh-cn']);
		$ret['zh-tw'] = strtr($text, $this->mTables['zh-tw']);
		$ret['zh-sg'] = strtr(strtr($text, $this->mTables['zh-cn']), $this->mTables['zh-sg']);
		$ret['zh-hk'] = strtr(strtr($text, $this->mTables['zh-tw']), $this->mTables['zh-hk']);
		wfProfileOut( $fname );
		return $ret;
	}
    
	# convert text to different variants of a language. the automatic
	# conversion is done in autoConvert(). here we parse the text 
	# marked with -{}-, which specifies special conversions of the 
	# text that can not be accomplished in autoConvert()
	#
	# syntax of the markup:
	# -{code1:text1;code2:text2;...}-  or
	# -{text}- in which case no conversion should take place for text
	function convert( $text , $isTitle=false) {
		global $wgDisableLangConversion;
		if($wgDisableLangConversion)
			return $text; 

		$mw =& MagicWord::get( MAG_NOTITLECONVERT );
		if( $mw->matchAndRemove( $text ) )
			$this->mDoTitleConvert = false;

		$mw =& MagicWord::get( MAG_NOCONTENTCONVERT );
		if( $mw->matchAndRemove( $text ) ) {
			$this->mDoContentConvert = false;
		}

		// no conversion if redirecting
		$mw =& MagicWord::get( MAG_REDIRECT );
		if( $mw->matchStart( $text ))
			return $text;

		if( $isTitle ) {
			if( !$this->mDoTitleConvert )
				return $text;

			global $wgRequest;
			$isredir = $wgRequest->getText( 'redirect', 'yes' );
			$action = $wgRequest->getText( 'action' );
			if ( $isredir == 'no' || $action == 'edit' ) {
				return $text;
			}
			else {
				return $this->autoConvert($text);
			}
		}

		if( !$this->mDoContentConvert )
			return $text;

		$plang = $this->getPreferredVariant();
		$fallback = $this->getVariantFallback($plang);

		$tarray = explode("-{", $text);
		$tfirst = array_shift($tarray);
		$text = $this->autoConvert($tfirst);
		foreach($tarray as $txt) {
			$marked = explode("}-", $txt);
			
			$choice = explode(";", $marked{0});
			if(!array_key_exists(1, $choice)) {
				/* a single choice */
				$text .= $choice{0};
			} else {
				$choice1=false;
				$choice2=false;
				foreach($choice as $c) {
					$v = explode(":", $c);
					if(!array_key_exists(1, $v)) {
						//syntax error in the markup, give up
						break;			
					}
					$code = trim($v{0});
					$content = trim($v{1});
					if($code == $plang) {
						$choice1 = $content;
						break;
					}
					if($code == $fallback)
						$choice2 = $content;
				}
				if ( $choice1 )
					$text .= $choice1;
				elseif ( $choice2 )
					$text .= $choice2;
				else
					$text .= $marked{0};
			}
			if(array_key_exists(1, $marked))
				$text .= $this->autoConvert($marked{1});
		}
		
		return $text;
	}


	function getVariants() {
		return array("zh", "zh-cn", "zh-tw", "zh-sg", "zh-hk");
	}

	function getVariantFallback($v) {
		switch ($v) {
		case 'zh': return 'zh-cn'; break;
		case 'zh-cn': return 'zh-sg'; break;
		case 'zh-sg': return 'zh-cn'; break;
		case 'zh-tw': return 'zh-hk'; break;
		case 'zh-hk': return 'zh-tw'; break;
		}
		return false;
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

		$t = $this->autoConvert($t, 'zh-cn');
		$t = LanguageUtf8::stripForSearch( $t );
		wfProfileOut( $fname );
		return $t;

	}

	function convertForSearchResult( $termsArray ) {
		$terms = implode( '|', $termsArray );
		$terms = implode( '|', $this->autoConvertToAllVariants( $terms ) );
		$ret = array_unique( explode('|', $terms) );
		return $ret;
	}

	function findVariantLink( &$link, &$nt ) {
		static $count=0; //used to limit this operation
		static $cache=array();
		global $wgDisableLangConversion;
		$pref = $this->getPreferredVariant();
		if( $wgDisableLangConversion || $pref == 'zh' || $count > 50)
			return;
		$count++;
		$variants = $this->autoConvertToAllVariants($link);
		if($variants == false) //give up
			return;
		foreach( $variants as $v ) {
			if(isset($cache[$v]))
				continue;
			$cache[$v] = 1;
			$varnt = Title::newFromText( $v );
			if( $varnt && $varnt->getArticleID() > 0 ) {
				$nt = $varnt;
				$link = $v;
				break;
			}
		}
	}

	function getExtraHashOptions() {
		global $wgUser;
		$variant = $this->getPreferredVariant();
		return '!' . $variant ;
	}
}
?>
