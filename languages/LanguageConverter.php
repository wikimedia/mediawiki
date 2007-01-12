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
	var $mTitleFromFlag = false;
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
	function __construct($langobj, $maincode,
								$variants=array(),
								$variantfallbacks=array(),
								$markup=array(),
								$flags = array()) {
		$this->mLangObj = $langobj;
		$this->mMainLanguageCode = $maincode;
		$this->mVariants = $variants;
		$this->mVariantFallbacks = $variantfallbacks;
		$this->mCacheKey = wfMemcKey( 'conversiontables', $maincode );
		$m = array('begin'=>'-{', 'flagsep'=>'|', 'codesep'=>':',
				   'varsep'=>';', 'end'=>'}-');
		$this->mMarkup = array_merge($m, $markup);
		$f = array('A'=>'A', 'T'=>'T', 'R' => 'R');
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
     * @private
	*/
	function getVariantFallback($v) {
		return $this->mVariantFallbacks[$v];
	}


	/**
	 * get preferred language variants.
	 * @param boolean $fromUser Get it from $wgUser's preferences
     * @return string the preferred language code
     * @access public
	*/
	function getPreferredVariant( $fromUser = true ) {
		global $wgUser, $wgRequest, $wgVariantArticlePath, $wgDefaultLanguageVariant;

		if($this->mPreferredVariant)
			return $this->mPreferredVariant;

		// see if the preference is set in the request
		$req = $wgRequest->getText( 'variant' );
		if( in_array( $req, $this->mVariants ) ) {
			$this->mPreferredVariant = $req;
			return $req;
		}

		// check the syntax /code/ArticleTitle
		if($wgVariantArticlePath!=false && isset($_SERVER['SCRIPT_NAME'])){
			// Note: SCRIPT_NAME probably won't hold the correct value if PHP is run as CGI
			// (it will hold path to php.cgi binary), and might not exist on some very old PHP installations
			$scriptBase = basename( $_SERVER['SCRIPT_NAME'] );
			if(in_array($scriptBase,$this->mVariants)){
				$this->mPreferredVariant = $scriptBase;
				return $this->mPreferredVariant;
			}
		}

		// get language variant preference from logged in users
		// Don't call this on stub objects because that causes infinite 
		// recursion during initialisation
		if( $fromUser && $wgUser->isLoggedIn() )  {
			$this->mPreferredVariant = $wgUser->getOption('variant');
			return $this->mPreferredVariant;
		}

		// see if default variant is globaly set
		if($wgDefaultLanguageVariant != false  &&  in_array( $wgDefaultLanguageVariant, $this->mVariants )){
			$this->mPreferredVariant = $wgDefaultLanguageVariant;
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
				$zh = strstr($header, $pv.'-');
				if($zh) {
					$pv = substr($zh,0,5);
				}
			}
			// don't try to return bad variant
			if(in_array( $pv, $this->mVariants ))
				return $pv;
		}

		return $this->mMainLanguageCode;

	}

	/**
     * dictionary-based conversion
     *
     * @param string $text the text to be converted
     * @param string $toVariant the target language code
     * @return string the converted text
     * @private
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

		// this one is needed when the text is inside an html markup
		$htmlfix = '|<[^>]+$|^[^<>]*>';

		// disable convert to variants between <code></code> tags
		$codefix = '<code>.+?<\/code>|';
		// disable convertsion of <script type="text/javascript"> ... </script>
		$scriptfix = '<script.*?>.*?<\/script>|';

		$reg = '/'.$codefix . $scriptfix . '<[^>]+>|&[a-zA-Z#][a-z0-9]+;' . $marker . $htmlfix . '/s';
	
		$matches = preg_split($reg, $text, -1, PREG_SPLIT_OFFSET_CAPTURE);

		$m = array_shift($matches);

		$ret = $this->translate($m[0], $toVariant);
		$mstart = $m[1]+strlen($m[0]);
		foreach($matches as $m) {
			$ret .= substr($text, $mstart, $m[1]-$mstart);
			$ret .= $this->translate($m[0], $toVariant);
			$mstart = $m[1] + strlen($m[0]);
		}
		wfProfileOut( $fname );
		return $ret;
	}

	/**
	 * Translate a string to a variant
	 * Doesn't process markup or do any of that other stuff, for that use convert()
	 *
	 * @param string $text Text to convert
	 * @param string $variant Variant language code
	 * @return string Translated text
	 */
	function translate( $text, $variant ) {
		wfProfileIn( __METHOD__ );
		if( !$this->mTablesLoaded )
			$this->loadTables();
		$text = $this->mTables[$variant]->replace( $text );
		wfProfileOut( __METHOD__ );
		return $text;
	}

	/**
     * convert text to all supported variants
     *
     * @param string $text the text to be converted
     * @return array of string
     * @public
     */
	function autoConvertToAllVariants($text) {
		$fname="LanguageConverter::autoConvertToAllVariants";
		wfProfileIn( $fname );
		if( !$this->mTablesLoaded )
			$this->loadTables();

		$ret = array();
		foreach($this->mVariants as $variant) {
			$ret[$variant] = $this->translate($text, $variant);
		}

		wfProfileOut( $fname );
		return $ret;
	}

	/**
     * convert link text to all supported variants
     *
     * @param string $text the text to be converted
     * @return array of string
     * @public
     */
	function convertLinkToAllVariants($text) {
		if( !$this->mTablesLoaded )
			$this->loadTables();

		$ret = array();
		$tarray = explode($this->mMarkup['begin'], $text);
		$tfirst = array_shift($tarray);

		foreach($this->mVariants as $variant)
			$ret[$variant] = $this->translate($tfirst,$variant);

		foreach($tarray as $txt) {
			$marked = explode($this->mMarkup['end'], $txt, 2);

			foreach($this->mVariants as $variant){
				$ret[$variant] .= $this->mMarkup['begin'].$marked[0].$this->mMarkup['end'];
				if(array_key_exists(1, $marked))
					$ret[$variant] .= $this->translate($marked[1],$variant);
			}
			
		}

		return $ret;
	}


	/**
	 * Convert text using a parser object for context
	 */
	function parserConvert( $text, &$parser ) {
		global $wgDisableLangConversion;
		/* don't do anything if this is the conversion table */
		if ( $parser->mTitle->getNamespace() == NS_MEDIAWIKI &&
				 strpos($parser->mTitle->getText(), "Conversiontable") !== false ) 
		{
			return $text;
		}

		if($wgDisableLangConversion)
			return $text;

		$text = $this->convert( $text );
		$parser->mOutput->setTitleText( $this->mTitleDisplay );
		return $text;
	}

	/**
	 *  Parse flags with syntax -{FLAG| ... }-
	 *
	 */
	function parseFlags($marked){
			$flags = array();

			// process flag only if the flag is valid
			if(strlen($marked) < 2 || !(in_array($marked[0],$this->mFlags) && $marked[1]=='|' ) )
				return array($marked,array());

			$tt = explode($this->mMarkup['flagsep'], $marked, 2);

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
				$rules = $marked;

			if( !in_array('R',$flags) ){
				//FIXME: may cause trouble here...
				//strip &nbsp; since it interferes with the parsing, plus,
				//all spaces should be stripped in this tag anyway.
				$rules = str_replace('&nbsp;', '', $rules);
			}

			return array($rules,$flags);
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
		$mw =& MagicWord::get( 'notitleconvert'   );
		if( $mw->matchAndRemove( $text ) )
			$this->mDoTitleConvert = false;

		$mw =& MagicWord::get( 'nocontentconvert'   );
		if( $mw->matchAndRemove( $text ) ) {
			$this->mDoContentConvert = false;
		}

		// no conversion if redirecting
		$mw =& MagicWord::get( 'redirect'   );
		if( $mw->matchStart( $text ))
			return $text;

		if( $isTitle ) {

			// use the title from the T flag if any
			if($this->mTitleFromFlag){
				$this->mTitleFromFlag = false;
				return $this->mTitleDisplay;
			}

			// check for __NOTC__ tag
			if( !$this->mDoTitleConvert ) {
				$this->mTitleDisplay = $text;
				return $text;
			}

			global $wgRequest;
			$isredir = $wgRequest->getText( 'redirect', 'yes' );
			$action = $wgRequest->getText( 'action' );
			if ( $isredir == 'no' || $action == 'edit' ) {
				return $text;
			}
			else {
				$this->mTitleDisplay = $this->convert($text);
				return $this->mTitleDisplay;
			}
		}

		$plang = $this->getPreferredVariant();
		if( isset( $this->mVariantFallbacks[$plang] ) ) {
			$fallback = $this->mVariantFallbacks[$plang];
		} else {
			$fallback = $this->mMainLanguageCode;
		}

		$tarray = explode($this->mMarkup['begin'], $text);
		$tfirst = array_shift($tarray);
		if($this->mDoContentConvert) 
			$text = $this->autoConvert($tfirst);
		else
			$text = $tfirst;
		foreach($tarray as $txt) {	
			$marked = explode($this->mMarkup['end'], $txt, 2);

			// strip the flags from syntax like -{T| ... }-
			list($rules,$flags) = $this->parseFlags($marked[0]);

			// proces R flag: output raw content of -{ ... }-
			if( in_array('R',$flags) ){
				$disp = $rules;
			} else if( $this->mDoContentConvert){
				// parse the contents -{ ... }- 
				$carray = $this->parseManualRule($rules, $flags);

				$disp = '';
				if(array_key_exists($plang, $carray)) {
					$disp = $carray[$plang];
				} else if(array_key_exists($fallback, $carray)) {
					$disp = $carray[$fallback];
				}
			} else{
				// if we don't do content convert, still strip the -{}- tags
				$disp = $rules;
				$flags = array();
			}

			if($disp) {
				// use syntax -{T|zh:TitleZh;zh-tw:TitleTw}- for custom conversion in title
				if(in_array('T',  $flags)){
					$this->mTitleFromFlag = true;
					$this->mTitleDisplay = $disp;
				}
				else
					$text .= $disp;

				// use syntax -{A|zh:WordZh;zh-tw:WordTw}- to introduce a custom mapping between
				// words WordZh and WordTw in the whole text 
				if(in_array('A', $flags)) {

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
							$this->mTables[$vto]->setPair($carray[$vfrom], $carray[$vto]);
						}
					}
				}
			}
			else {
				$text .= $marked[0];
			}
			if(array_key_exists(1, $marked)){
				if( $this->mDoContentConvert )
					$text .= $this->autoConvert($marked[1]);
				else
					$text .= $marked[1];
			}
		}

		return $text;
	}

	/**
	 * parse the manually marked conversion rule
	 * @param string $rule the text of the rule
	 * @return array of the translation in each variant
	 * @private
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
		global $wgDisableLangConversion;
		$linkBatch = new LinkBatch();

		$ns=NS_MAIN;

		if(is_object($nt))
			$ns = $nt->getNamespace();

		$variants = $this->autoConvertToAllVariants($link);
		if($variants == false) //give up
			return;

		$titles = array();

		foreach( $variants as $v ) {
			if($v != $link){
				$varnt = Title::newFromText( $v, $ns );
				if(!is_null($varnt)){
					$linkBatch->addObj($varnt);
					$titles[]=$varnt;
				}
			}
		}

		// fetch all variants in single query
		$linkBatch->execute();

		foreach( $titles as $varnt ) {
			if( $varnt->getArticleID() > 0 ) {
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
     * @private
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
     * @private
     */
	function unlockCache() {
		global $wgMemc;
		$wgMemc->delete($this->mCacheKey . "lock");
	}


	/**
     * Load default conversion tables
     * This method must be implemented in derived class
     *
     * @private
     */
	function loadDefaultTables() {
		$name = get_class($this);
		wfDie("Must implement loadDefaultTables() method in class $name");
	}

	/**
     * load conversion tables either from the cache or the disk
     * @private
     */
	function loadTables($fromcache=true) {
		global $wgMemc;
		if( $this->mTablesLoaded )
			return;
		wfProfileIn( __METHOD__ );
		$this->mTablesLoaded = true;
		$this->mTables = false;
		if($fromcache) {
			wfProfileIn( __METHOD__.'-cache' );
			$this->mTables = $wgMemc->get( $this->mCacheKey );
			wfProfileOut( __METHOD__.'-cache' );
		}
		if ( !$this->mTables || !isset( $this->mTables['VERSION 2'] ) ) {
			wfProfileIn( __METHOD__.'-recache' );
			// not in cache, or we need a fresh reload.
			// we will first load the default tables
			// then update them using things in MediaWiki:Zhconversiontable/*
			$this->loadDefaultTables();
			foreach($this->mVariants as $var) {
				$cached = $this->parseCachedTable($var);
				$this->mTables[$var]->mergeArray($cached);
			}

			$this->postLoadTables();
			$this->mTables['VERSION 2'] = true;

			if($this->lockCache()) {
				$wgMemc->set($this->mCacheKey, $this->mTables, 43200);
				$this->unlockCache();
			}
			wfProfileOut( __METHOD__.'-recache' );
		}
		wfProfileOut( __METHOD__ );
	}

    /**
     * Hook for post processig after conversion tables are loaded
     *
     */
	function postLoadTables() {}

    /**
     * Reload the conversion tables
     *
     * @private
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
     * @private
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
				$ret[Language::ucfirst($k)] = Language::ucfirst($v);
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
	function markNoConversion($text, $noParse=false) {
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
     * @private
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

	/** 
	 * Armour rendered math against conversion
	 * Wrap math into rawoutput -{R| math }- syntax
	 */
 	function armourMath($text){ 
		$ret = $this->mMarkup['begin'] . 'R|' . $text . $this->mMarkup['end'];
		return $ret;
	}


}

?>
