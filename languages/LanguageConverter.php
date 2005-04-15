<?php
/**
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Zhengzhu Feng <zhengzhu@gmail.com>
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
  */

class LanguageConverter {
	var $mPreferredVariant='';
	var $mMainLanguageCode;
	var $mVariants, $mVariantFallbacks;
	var $mTablesLoaded = false;
	var $mTables;
	var $mTitleDisplay='';
	var $mDoTitleConvert=true, $mDoContentConvert=true;
	var $mCacheKey;
	var $mLangObj;
	var $mMarkup;
	/**
     * Constructor
	 *
     * @param string $maincode the main language code of this language
     * @param array $variants the supported variants of this language
     * @param array $variantfallback the fallback language of each variant
     * @param array $markup array defining the markup used for manual conversion
     * @access public
     */
	function LanguageConverter($langobj, $maincode, 
								$variants=array(), 
								$variantfallbacks=array(), 
								$markup=array('begin'=>'-{',
											  'codesep'=>':',
											  'varsep'=>';',
											  'end'=>'}-')) {
		global $wgDBname;
		$this->mLangObj = $langobj;
		$this->mMainLanguageCode = $maincode;
		$this->mVariants = $variants;		
		$this->mVariantFallbacks = $variantfallbacks;
		$this->mCacheKey = $wgDBname . ":conversiontables";
		$this->mMarkup = $markup;
	}

	/**
     * @access public
     */
	function getVariants() { 
		return $this->mVariants;
	}

	/**
	 * in case some variant is not defined in the markup, we need
	 * to have some fallback. for example, in zh, normally people
	 * will define zh-cn and zh-tw, but less so for zh-sg or zh-hk.
	 * when zh-sg is preferred but not defined, we will pick zh-cn
	 * in this case. right now this is only used by zh.
	 *	
	 * @param string $v the language code of the variant
	 * @return string the code of the fallback language or false if there is no fallback
     * @access private
	*/
	function getVariantFallback($v) {
		return $this->mVariantFallbacks[$v];
	}

	
	/** 
     * get preferred language variants.
     * @return string the preferred language code
     * @access public
	*/
	function getPreferredVariant() {
		global $wgUser, $wgRequest;
		
		if($this->mPreferredVariant)
			return $this->mPreferredVariant;

		// see if the preference is set in the request
		$req = $wgRequest->getText( 'variant' );
		if( in_array( $req, $this->mVariants ) ) {
			$this->mPreferredVariant = $req;
			return $req;
		}

		// get language variant preference from logged in users 
		if(is_object($wgUser) && $wgUser->isLoggedIn() )  {
			$this->mPreferredVariant = $wgUser->getOption('variant');
		}

		# FIXME rewrite code for parsing http header. The current code
		# is written specific for detecting zh- variants
		if( !$this->mPreferredVariant ) {
			// see if some zh- variant is set in the http header,
			$this->mPreferredVariant=$this->mMainLanguageCode;
			if(array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {
				$header = str_replace( '_', '-', strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]));
				$zh = strstr($header, 'zh-');
				if($zh) {
					$this->mPreferredVariant = substr($zh,0,5);
				}
			}
		}

		return $this->mPreferredVariant;
	}

	/**
     * dictionary-based conversion
     *
     * @param string $text the text to be converted
     * @param string $toVariant the target language code
     * @return string the converted text
     * @access private
     */
	function autoConvert($text, $toVariant=false) {
		$fname="LanguageConverter::autoConvert";
		wfProfileIn( $fname );

		if(!$this->mTablesLoaded)
			$this->loadTables();

		if(!$toVariant) 
			$toVariant = $this->getPreferredVariant();
		if(!in_array($toVariant, $this->mVariants))
			return $text;

		$ret = '';

		$a = explode('<', $text);
		$a0 = array_shift($a);
		$ret .= strtr($a0, $this->mTables[$toVariant]);
		foreach( $a as $aa ) {
			$b = explode('>', $aa, 2);
			$ret .= '<' . $b[0];
			if(sizeof($b) == 2)
				$ret .= '>' . strtr($b[1], $this->mTables[$toVariant]);
		}

#		/* put back the marker if any */
#		if(!empty($reg)) {
#			$reg = '<'.$reg.'>';
#			$ret = preg_replace('/'.$reg.'/', '${1}', $ret);
#		}
#
		wfProfileOut( $fname );
		return $ret;
	}
    
	/**
     * convert text to all supported variants
     *
     * @param string $text the text to be converted
     * @return array of string
     * @access private
     */
	function autoConvertToAllVariants($text) {
		$fname="LanguageConverter::autoConvertToAllVariants";
		wfProfileIn( $fname );
		if( !$this->mTablesLoaded )
			$this->loadTables();

		$ret = array();
		foreach($this->mVariants as $variant) {
			$ret[$variant] = strtr($text, $this->mTables[$variant]);
		}
		wfProfileOut( $fname );
		return $ret;
	}

	/**
	 * convert text to different variants of a language. the automatic
	 * conversion is done in autoConvert(). here we parse the text 
	 * marked with -{}-, which specifies special conversions of the 
	 * text that can not be accomplished in autoConvert()
	 *
	 * syntax of the markup:
	 * -{code1:text1;code2:text2;...}-  or
	 * -{text}- in which case no conversion should take place for text
     *
     * @param string $text text to be converted
     * @param bool $isTitle whether this conversion is for the article title 
     * @return string converted text
     * @access public
     */
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
			if( !$this->mDoTitleConvert ) {
				$this->mTitleDisplay = $text;
				return $text;
			}
			if( !empty($this->mTitleDisplay))
				return $this->mTitleDisplay;

			global $wgRequest;
			$isredir = $wgRequest->getText( 'redirect', 'yes' );
			$action = $wgRequest->getText( 'action' );
			if ( $isredir == 'no' || $action == 'edit' ) {
				return $text;
			}
			else {
				$this->mTitleDisplay = $this->autoConvert($text);
				return $this->mTitleDisplay;
			}
		}

		if( !$this->mDoContentConvert )
			return $text;

		$search = array('/('.UNIQ_PREFIX.'-[a-zA-Z0-9]+)/', //nowiki marker
					'/(&[a-z#][a-z0-9]+;)/', //html entities
                        );
		$replace = $this->mMarkup['begin'].'${1}'.$this->mMarkup['end'];

		$text = preg_replace($search, $replace, $text);

		$plang = $this->getPreferredVariant();
		$fallback = $this->mVariantFallbacks[$plang];
		$tarray = explode($this->mMarkup['begin'], $text);
		$tfirst = array_shift($tarray);
		$text = $this->autoConvert($tfirst);
		foreach($tarray as $txt) {
			$marked = explode($this->mMarkup['end'], $txt);
		
			//strip &nbsp; since it interferes with the parsing, plus,
			//all spaces should be stripped in this tag anyway.
			$marked[0] = str_replace('&nbsp;', '', $marked[0]);

			/* see if this conversion has special meaning
			   # for article title:
				 -{T|zh-cn:foo;zh-tw:bar}-
			   # convert all occurence of foo/bar in this article:
				 -{A|zh-cn:foo;zh-tw:bar}-
			*/
			$flag = '';
			$choice = false;
			$tt = explode("|", $marked[0], 2);
			if(sizeof($tt) == 2) {
				$flag = trim($tt[0]);
				$choice = explode(";", $tt[1]);
			}

			if(!$choice) {
				$choice = explode($this->mMarkup['varsep'], $marked[0]);
			}
			$disp = '';
			$carray = array();
			if(!array_key_exists(1, $choice)) {
				/* a single choice */
				$disp = $choice[0];

				/* fill the carray if the conversion is for the whole article*/
				if($flag == 'A') {
					foreach($this->mVariants as $v) {
						$carray[$v] = $disp;
					}
				}
			} 
			else {
				foreach($choice as $c) {
					$v = explode($this->mMarkup['codesep'], $c);
					if(sizeof($v) != 2) // syntax error, skip
						continue;
					$carray[trim($v[0])] = trim($v[1]);
				}
				if(array_key_exists($plang, $carray))
					$disp = $carray[$plang];
				else if(array_key_exists($fallback, $carray))
					$disp = $carray[$fallback];
			}
			if(empty($disp)) { // syntax error
				$text .= $marked[0];
			}
			else {	
				if($flag == 'T') // for title only
					$this->mTitleDisplay = $disp;
				else {
					$text .= $disp;
					if($flag == 'A') {
						/* modify the conversion table for this session*/

						/* fill in the missing variants, if any,
						    with fallbacks */ 
						foreach($this->mVariants as $v) {
							if(!array_key_exists($v, $carray)) {
								$vf = $this->getVariantFallback($v);
								if(array_key_exists($vf, $carray))
									$carray[$v] = $carray[$vf];
							}
						}
						foreach($this->mVariants as $vfrom) {
							if(!array_key_exists($vfrom, $carray))
								continue;
							foreach($this->mVariants as $vto) {
								if($vfrom == $vto)
									continue;
								if(!array_key_exists($vto, $carray))
									continue;
								$this->mTables[$vto][$carray[$vfrom]] = $carray[$vto];
							}
						}
					}
				}
			}
			if(array_key_exists(1, $marked))
				$text .= $this->autoConvert($marked[1]);
		}
		
		return $text;
	}


	/**
	 * if a language supports multiple variants, it is
	 * possible that non-existing link in one variant
	 * actually exists in another variant. this function 
	 * tries to find it. See e.g. LanguageZh.php
	 *
	 * @param string $link the name of the link
	 * @param mixed $nt the title object of the link
	 * @return null the input parameters may be modified upon return
     * @access public
	 */
	function findVariantLink( &$link, &$nt ) {
		static $count=0; //used to limit this operation
		static $cache=array();
		global $wgDisableLangConversion;
		$pref = $this->getPreferredVariant();
		if( $count > 50 )
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
				if( !$wgDisableLangConversion && $pref != 'zh' )
					$link = $v;
				break;
			}
		}
	}

    /**
     * returns language specific hash options
     * 
     * @access public
     */
	function getExtraHashOptions() {
		$variant = $this->getPreferredVariant();
		return '!' . $variant ;
	}

    /**
     * get title text as defined in the body of the article text
     *
     * @access public
     */
	function getParsedTitle() {
		return $this->mTitleDisplay;
	}

	/**
     * a write lock to the cache
     *
     * @access private
     */
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

	/**
     * unlock cache
     *
     * @access private
     */
	function unlockCache() {
		global $wgMemc;
		$wgMemc->delete($this->mCacheKey . "lock");
	}


	/**
     * Load default conversion tables
     * This method must be implemented in derived class
     * 
     * @access private
     */
	function loadDefaultTables() {
		$name = get_class($this);
		die("Must implement loadDefaultTables() method in class $name");
	}

	/**
     * load conversion tables either from the cache or the disk
     * @access private
     */
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
		// we will first load the default tables
		// then update them using things in MediaWiki:Zhconversiontable/*
		global $wgMessageCache;
		$this->loadDefaultTables();
		foreach($this->mVariants as $var) {
			$cached = $this->parseCachedTable($var);
			$this->mTables[$var] = array_merge($this->mTables[$var], $cached);
		}

		$this->postLoadTables();
		
		if($this->lockCache()) {
			$wgMemc->set($this->mCacheKey, $this->mTables, 43200);
			$this->unlockCache();
		}
	}
	
    /**
     * Hook for post processig after conversion tables are loaded
     *
     */
	function postLoadTables() {}

	/* deprecated? */
	function updateTablexxxx($code, $table) {
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

    /**
     * Reload the conversion tables
     * 
     * @access private
     */
	function reloadTables() {
		if($this->mTables)
			unset($this->mTables);
		$this->mTablesLoaded = false;
		$this->loadTables(false);
	}


	/**
     * parse the conversion table stored in the cache 
     *
     * the tables should be in blocks of the following form:

     *		-{
     *			word => word ;
     *			word => word ;
     *			...
     *		}-
     *	
     *	to make the tables more manageable, subpages are allowed
     *	and will be parsed recursively if $recursive=true
     *
     * @access private
	 */
	function parseCachedTable($code, $subpage='', $recursive=true) {
		global $wgMessageCache;
		static $parsed = array();

		if(!is_object($wgMessageCache))
			return array();

		$key = 'Conversiontable/'.$code;
		if($subpage)
			$key .= '/' . $subpage;

		if(array_key_exists($key, $parsed))
			return array();
	

		$txt = $wgMessageCache->get( $key, true, true, true );

		// get all subpage links of the form
		// [[MediaWiki:conversiontable/zh-xx/...|...]]
		$linkhead = $this->mLangObj->getNsText(NS_MEDIAWIKI) . ':Conversiontable';
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
				// trim any trailling comments starting with '//'
				$tt = explode('//', $m[1], 2);
				$ret[trim($m[0])] = trim($tt[0]);
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

	/**
	 * Enclose a string with the "no conversion" tag. This is used by
	 * various functions in the Parser
	 * 
	 * @param string $text text to be tagged for no conversion
	 * @return string the tagged text
	*/
	function markNoConversion($text) {
		$ret = $this->mMarkup['begin'] . $text . $this->mMarkup['end'];
	}

	/**
     * hook to refresh the cache of conversion tables when 
     * MediaWiki:conversiontable* is updated
     * @access private
	*/
	function OnArticleSaveComplete($article, $user, $text, $summary, $isminor, $iswatch, $section) {
		$titleobj = $article->getTitle();
		if($titleobj->getNamespace() == NS_MEDIAWIKI) { 
            /*
			global $wgContLang; // should be an LanguageZh.
			if(get_class($wgContLang) != 'languagezh')	
				return true;
            */
			$title = $titleobj->getDBkey();
			$t = explode('/', $title, 3);
			$c = count($t);
			if( $c > 1 && $t[0] == 'Conversiontable' ) {
				if(in_array($t[1], $this->mVariants)) {
					$this->reloadTables();
				}
			}
		}
		return true;
	}
}

?>
