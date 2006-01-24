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
	var $mFlags;
	var $mUcfirst = false;
	/**
     * Constructor
	 *
     * @param string $maincode the main language code of this language
     * @param array $variants the supported variants of this language
     * @param array $variantfallback the fallback language of each variant
     * @param array $markup array defining the markup used for manual conversion
	 * @param array $flags array defining the custom strings that maps to the flags
     * @access public
     */
	function LanguageConverter($langobj, $maincode,
								$variants=array(),
								$variantfallbacks=array(),
								$markup=array(),
								$flags = array()) {
		global $wgDBname;
		$this->mLangObj = $langobj;
		$this->mMainLanguageCode = $maincode;
		$this->mVariants = $variants;
		$this->mVariantFallbacks = $variantfallbacks;
		$this->mCacheKey = $wgDBname . ":conversiontables";
		$m = array('begin'=>'-{', 'flagsep'=>'|', 'codesep'=>':',
				   'varsep'=>';', 'end'=>'}-');
		$this->mMarkup = array_merge($m, $markup);
		$f = array('A'=>'A', 'T'=>'T');
		$this->mFlags = array_merge($f, $flags);
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
			return $this->mPreferredVariant;
		}

		# FIXME rewrite code for parsing http header. The current code
		# is written specific for detecting zh- variants
		if( !$this->mPreferredVariant ) {
			// see if some supported language variant is set in the
			// http header, but we don't set the mPreferredVariant
			// variable in case this is called before the user's
			// preference is loaded
			$pv=$this->mMainLanguageCode;
			if(array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {
				$header = str_replace( '_', '-', strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]));
				$zh = strstr($header, 'zh-');
				if($zh) {
					$pv = substr($zh,0,5);
				}
			}
			return $pv;
		}
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

		/* we convert everything except:
		   1. html markups (anything between < and >)
		   2. html entities
		   3. place holders created by the parser
		*/
		global $wgParser;
		if (isset($wgParser))
			$marker = '|' . $wgParser->UniqPrefix() . '[\-a-zA-Z0-9]+';
		else
			$marker = "";
		$reg = '/<[^>]+>|&[a-z#][a-z0-9]+;' . $marker . '/';
		$matches = preg_split($reg, $text, -1, PREG_SPLIT_OFFSET_CAPTURE);


		$m = array_shift($matches);
		$ret = strtr($m[0], $this->mTables[$toVariant]);
		$mstart = $m[1]+strlen($m[0]);
		foreach($matches as $m) {
			$ret .= substr($text, $mstart, $m[1]-$mstart);
			$ret .= strtr($m[0], $this->mTables[$toVariant]);
			$mstart = $m[1] + strlen($m[0]);
		}
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
		global $wgTitle;

		/* don't do anything if this is the conversion table */
		if($wgTitle->getNamespace() == NS_MEDIAWIKI &&
		   strpos($wgTitle->getText(), "Conversiontable")!==false)
			return $text;

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

		$plang = $this->getPreferredVariant();
		$fallback = $this->mVariantFallbacks[$plang];

		$tarray = explode($this->mMarkup['begin'], $text);
		$tfirst = array_shift($tarray);
		$text = $this->autoConvert($tfirst);
		foreach($tarray as $txt) {
			$marked = explode($this->mMarkup['end'], $txt);
			$flags = array();
			$tt = explode($this->mMarkup['flagsep'], $marked[0], 2);

			if(sizeof($tt) == 2) {
				$f = explode($this->mMarkup['varsep'], $tt[0]);
				foreach($f as $ff) {
					$ff = trim($ff);
					if(array_key_exists($ff, $this->mFlags) &&
						!array_key_exists($this->mFlags[$ff], $flags))
						$flags[] = $this->mFlags[$ff];
				}
				$rules = $tt[1];
			}
			else
				$rules = $marked[0];

#FIXME: may cause trouble here...
			//strip &nbsp; since it interferes with the parsing, plus,
			//all spaces should be stripped in this tag anyway.
			$rules = str_replace('&nbsp;', '', $rules);

			$carray = $this->parseManualRule($rules, $flags);
			$disp = '';
			if(array_key_exists($plang, $carray))
				$disp = $carray[$plang];
			else if(array_key_exists($fallback, $carray))
				$disp = $carray[$fallback];
			if($disp) {
				if(in_array('T',  $flags))
					$this->mTitleDisplay = $disp;
				else
					$text .= $disp;

				if(in_array('A', $flags)) {
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
			else {
				$text .= $marked[0];
			}
			if(array_key_exists(1, $marked))
				$text .= $this->autoConvert($marked[1]);
		}

		return $text;
	}

	/**
	 * parse the manually marked conversion rule
	 * @param string $rule the text of the rule
	 * @return array of the translation in each variant
	 * @access private
	 */
	function parseManualRule($rules, $flags=array()) {

		$choice = explode($this->mMarkup['varsep'], $rules);
		$carray = array();
		if(sizeof($choice) == 1) {
			/* a single choice */
			foreach($this->mVariants as $v)
				$carray[$v] = $choice[0];
		}
		else {
			foreach($choice as $c) {
				$v = explode($this->mMarkup['codesep'], $c);
				if(sizeof($v) != 2) // syntax error, skip
					continue;
				$carray[trim($v[0])] = trim($v[1]);
			}
		}
		return $carray;
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
		$ns=0;
		if(is_object($nt))
			$ns = $nt->getNamespace();
		if( $count > 50 && $ns != NS_CATEGORY )
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
				if( !$wgDisableLangConversion )
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
		wfDie("Must implement loadDefaultTables() method in class $name");
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
		$blocks = explode($this->mMarkup['begin'], $txt);
		array_shift($blocks);
		$ret = array();
		foreach($blocks as $block) {
			$mappings = explode($this->mMarkup['end'], $block, 2);
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

		if ($this->mUcfirst) {
			foreach ($ret as $k => $v) {
				$ret[LanguageUtf8::ucfirst($k)] = LanguageUtf8::ucfirst($v);
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
		# don't mark if already marked
		if(strpos($text, $this->mMarkup['begin']) ||
 		   strpos($text, $this->mMarkup['end']))
			return $text;

		$ret = $this->mMarkup['begin'] . $text . $this->mMarkup['end'];
		return $ret;
	}

	/**
	 * convert the sorting key for category links. this should make different
	 * keys that are variants of each other map to the same key
	*/
	function convertCategoryKey( $key ) {
		return $key;
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
