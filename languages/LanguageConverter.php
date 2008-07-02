<?php

/**
  * @ingroup Language
  *
  * @author Zhengzhu Feng <zhengzhu@gmail.com>
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
  * @maintainers fdcn <fdcn64@gmail.com>, shinjiman <shinjiman@gmail.com>
  */

class LanguageConverter {
	var $mPreferredVariant='';
	var $mMainLanguageCode;
	var $mVariants, $mVariantFallbacks, $mVariantNames;
	var $mTablesLoaded = false;
	var $mTables;
	var $mTitleDisplay='';
	var $mDoTitleConvert=true, $mDoContentConvert=true;
	var $mManualLevel; // 'bidirectional' 'unidirectional' 'disable' for each variants
	var $mManualCodeError='<span style="color: red;">code error!</span>';
	var $mTitleFromFlag = false;
	var $mCacheKey;
	var $mLangObj;
	var $mMarkup;
	var $mFlags;
	var $mUcfirst = false;

	const CACHE_VERSION_KEY = 'VERSION 6';

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
								$flags = array(),
								$manualLevel = array() ) {
		$this->mLangObj = $langobj;
		$this->mMainLanguageCode = $maincode;
		$this->mVariants = $variants;
		$this->mVariantFallbacks = $variantfallbacks;
		global $wgLanguageNames;
		$this->mVariantNames = $wgLanguageNames;
		$this->mCacheKey = wfMemcKey( 'conversiontables', $maincode );
		$m = array(
			'begin'=>'-{', 
			'flagsep'=>'|',
			'unidsep'=>'=>', //for unidirectional conversion
			'codesep'=>':',
			'varsep'=>';',
			'end'=>'}-'
		);
		$this->mMarkup = array_merge($m, $markup);
		$f = array( 
			// 'S' show converted text
			// '+' add rules for alltext
			// 'E' the gave flags is error
			// these flags above are reserved for program
			'A'=>'A',       // add rule for convert code (all text convert)
			'T'=>'T',       // title convert
			'R'=>'R',       // raw content
			'D'=>'D',       // convert description (subclass implement)
			'-'=>'-',       // remove convert (not implement)
			'H'=>'H',       // add rule for convert code (but no display in placed code )
			'N'=>'N'        // current variant name
		);
		$this->mFlags = array_merge($f, $flags);
		foreach( $this->mVariants as $v)
			$this->mManualLevel[$v]=array_key_exists($v,$manualLevel)
								?$manualLevel[$v]
								:'bidirectional';
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
	 * will define zh-hans and zh-hant, but less so for zh-sg or zh-hk.
	 * when zh-sg is preferred but not defined, we will pick zh-hans
	 * in this case. right now this is only used by zh.
	 *
	 * @param string $v the language code of the variant
	 * @return string array the code of the fallback language or false if there is no fallback
	 * @private
	 */
	function getVariantFallbacks($v) {
		if( isset( $this->mVariantFallbacks[$v] ) ) {
			return $this->mVariantFallbacks[$v];
		}
		return $this->mMainLanguageCode;
	}

	/**
	 * check if variants array in convert array
	 *
	 * @param string $variant Variant language code
	 * @param array $carray convert array
	 * @param string $text Text to convert
	 * @return string Translated text
	 * @private
	 */
	function getTextInCArray($variants,$carray){
		if(is_string($variants)){ $variants=array($variants); }
		if(!is_array($variants)) return false;
		foreach ($variants as $variant){
			if(array_key_exists($variant, $carray)){
				return $carray[$variant];
			}
		}
		return false;
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
		if (isset($wgParser) && $wgParser->UniqPrefix()!='')
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
		if ( $parser->getTitle()->getNamespace() == NS_MEDIAWIKI &&
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

		// for multi-FLAGs
		if(strlen($marked) < 2 )
			return array($marked,array('R'));

		$tt = explode($this->mMarkup['flagsep'], $marked, 2);

		if(count($tt) == 2) {
			$f = explode($this->mMarkup['varsep'], $tt[0]);
			foreach($f as $ff) {
				$ff = trim($ff);
				if(array_key_exists($ff, $this->mFlags) &&
							!in_array($this->mFlags[$ff], $flags))
					$flags[] = $this->mFlags[$ff];
			}
			$rules = $tt[1];
		} else {
			$rules = $marked;
		}

		if( !in_array('R',$flags) ){
			//FIXME: may cause trouble here...
			//strip &nbsp; since it interferes with the parsing, plus,
			//all spaces should be stripped in this tag anyway.
			$rules = str_replace('&nbsp;', '', $rules);
			$rules = str_replace('=&gt;','=>',$rules);
		}

		//check flags
		if( in_array('R',$flags) ){
			$flags = array('R');// remove other flags
		} elseif ( in_array('N',$flags) ){
			$flags = array('N');// remove other flags
		} elseif ( in_array('-',$flags) ){
			$flags = array('-');// remove other flags
		} elseif (count($flags)==1 && $flags[0]=='T'){
			$flags[]='H'; 
		} elseif ( in_array('H',$flags) ){
			// replace A flag, and remove other flags except T
			$temp=array('+','H');
			if(in_array('T',$flags)) $temp[] = 'T';
			if(in_array('D',$flags)) $temp[] = 'D';
			$flags = $temp;
		} else {
			if ( in_array('A',$flags)) {
				$flags[]='+';
				$flags[]='S';
			}
			if ( in_array('D',$flags) )
				$flags=array_diff($flags,array('S'));
		}
		if ( count($flags)==0 )
			$flags = array('S');

		return array($rules,$flags);
	}

	function getRulesDesc($bidtable,$unidtable){
		$text='';
		foreach($bidtable as $k => $v)
			$text .= $this->mVariantNames[$k].':'.$v.';';
		foreach($unidtable as $k => $a)
			foreach($a as $from=>$to)
				$text.=$from.'⇒'.$this->mVariantNames[$k].':'.$to.';';
		return $text;
	}

	/**
	 * parse the manually marked conversion rule
	 * @param string $rule the text of the rule
	 * @return array of the translation in each variant
	 * @private
	 */
	function getConvTableFromRules($rules,$flags=array()) {
		$bidtable = array();
		$unidtable = array();
		$choice = explode($this->mMarkup['varsep'], $rules );
		foreach($choice as $c) {
			$v = explode($this->mMarkup['codesep'], $c);
			if(count($v) != 2) 
				continue;// syntax error, skip
			$to=trim($v[1]);
			$v=trim($v[0]);
			$u = explode($this->mMarkup['unidsep'], $v);
			if(count($u) == 1) {
				$bidtable[$v] = $to;
			} else if(count($u) == 2){
				$from=trim($u[0]);$v=trim($u[1]);
				if( array_key_exists($v,$unidtable) && !is_array($unidtable[$v]) )
					$unidtable[$v]=array($from=>$to);
				else
					$unidtable[$v][$from]=$to;
			}
			// syntax error, pass
		}
		return array($bidtable,$unidtable);
	}

	/**
	 *  get display text on markup -{...}-
	 * @param string $rules the original code
	 * @param array $flags FLAGs
	 * @param array $bidtable bidirectional convert table
	 * @param string $unidtable unidirectional convert table
	 * @param string $variant the current variant
	 * @param bool $$doConvert if do convert
	 * @private
	 */
	function getRulesDisplay($rules,$flags,
							$bidtable,$unidtable,
							$variant=false,$doConvert=true){
		if(!$variant) $variant = $this->getPreferredVariant();
		$is_mc_disable = $this->mManualLevel[$variant]=='disable';

		if( in_array('R',$flags) ) {
			// if we don't do content convert, still strip the -{}- tags
			$disp = $rules;
		} elseif ( in_array('N',$flags) ){
			// proces N flag: output current variant name
			$disp = $this->mVariantNames[trim($rules)];
		} elseif ( in_array('D',$flags) ){
			// proces D flag: output rules description
			$disp = $this->getRulesDesc($bidtable,$unidtable);
		} elseif ( in_array('H',$flags) || in_array('-',$flags) ) {
			// proces H,- flag or T only: output nothing
			$disp = '';
		} elseif ( in_array('S',$flags) ){
			// the text converted 
			if($doConvert){
				// display current variant in bidirectional array
				$disp = $this->getTextInCArray($variant,$bidtable);
				// or display current variant in fallbacks
				if(!$disp)
					$disp = $this->getTextInCArray($this->getVariantFallbacks($variant),$bidtable);
				// or display current variant in unidirectional array
				if(!$disp && array_key_exists($variant,$unidtable)){
					$disp = array_values($unidtable[$variant]);
					$disp = $disp[0];
				}
				// or display frist text under disable manual convert
				if(!$disp && $is_mc_disable) {
					if(count($bidtable)>0){
						$disp = array_values($bidtable);
						$disp = $disp[0];
					} else {
						$disp = array_values($unidtable);
						$disp = array_values($disp[0]);
						$disp = $disp[0];
					}
				}
			} else {// no convert
				$disp = $rules;
			}
		} elseif ( in_array('T',$flags) ) {
			// proces T flag : output nothing
			$disp = '';
		}
		else
			$disp= $this->mManualCodeError;

		return $disp;
	}

	function applyManualFlag($flags,$bidtable,$unidtable,$variant=false){
		if(!$variant) $variant = $this->getPreferredVariant();

		$is_title_flag = in_array('T',  $flags);
		// use syntax -{T|zh:TitleZh;zh-tw:TitleTw}- for custom conversion in title
		if($is_title_flag){
			$this->mTitleFromFlag = true;
			$this->mTitleDisplay =  $this->getRulesDisplay($rules,array('S'),
												$bidtable,$unidtable,
												$variant,
												$this->mDoTitleConvert);
		}

		if($this->mManualLevel[$variant]=='disable') return;

		$is_remove_flag = !$is_title_flag && in_array('-', $flags);
		$is_add_flag = !$is_remove_flag && in_array('+', $flags);
		$is_bidMC = $this->mManualLevel[$variant]=='bidirectional';
		$is_unidMC = $this->mManualLevel[$variant]=='unidirectional';
		$vmarked=array();

		foreach($this->mVariants as $v) {
			/* for bidirectional array
				fill in the missing variants, if any,
				with fallbacks */
			if($is_bidMC && !array_key_exists($v, $bidtable)) {
				$vf = $this->getTextInCArray($this->getVariantFallbacks($v),$bidtable);
				if($vf) $bidtable[$v] = $vf;
			}
			if($is_bidMC && array_key_exists($v,$bidtable)){
				foreach($vmarked as $vo){
					// use syntax:
					//  -{A|zh:WordZh;zh-tw:WordTw}- or -{+|zh:WordZh;zh-tw:WordTw}- 
					// to introduce a custom mapping between
					// words WordZh and WordTw in the whole text 
					if($is_add_flag){
						$this->mTables[$v]->setPair($bidtable[$vo], $bidtable[$v]);
						$this->mTables[$vo]->setPair($bidtable[$v], $bidtable[$vo]);
					}
					// use syntax -{-|zh:WordZh;zh-tw:WordTw}- to remove a conversion
					// words WordZh and WordTw in the whole text 
					if($is_remove_flag){
						$this->mTables[$v]->removePair($bidtable[$vo]);
						$this->mTables[$vo]->removePair($bidtable[$v]);
					}
				}
				$vmarked[]=$v;
			}
			/*for unidirectional array
				fill to convert tables */
			if($is_unidMC && array_key_exists($v,$unidtable)){
				if($is_add_flag)$this->mTables[$v]->mergeArray($unidtable[$v]);
				if($is_remove_flag)$this->mTables[$v]->removeArray($unidtable[$v]);
			}
		}
	}

	/**
	 *  Parse rules and flags
	 * @private
	 */
	function parseRules($rules,$flags,$variant=false){
		if(!$variant) $variant = $this->getPreferredVariant();

		list($bidtable,$unidtable) = $this->getConvTableFromRules($rules, $flags);
		if(count($bidtable)==0 && count($unidtable)==0
			&& !in_array('N',$flags) && !in_array('T',$flags) )
				$flags = array('R');
		$disp = $this->getRulesDisplay($rules,$flags,
									$bidtable,$unidtable,
									$variant,
									$this->mDoContentConvert);
		$this->applyManualFlag($flags,$bidtable,$unidtable);

		return $disp;
	}
	
	function convertTitle($text){
		// check for __NOTC__ tag
		if( !$this->mDoTitleConvert ) {
			$this->mTitleDisplay = $text;
			return $text;
		}

		// use the title from the T flag if any
		if($this->mTitleFromFlag){
			$this->mTitleFromFlag = false;
			return $this->mTitleDisplay;
		}

		global $wgRequest;
		$isredir = $wgRequest->getText( 'redirect', 'yes' );
		$action = $wgRequest->getText( 'action' );
		if ( $isredir == 'no' || $action == 'edit' ) {
			return $text;
		} else {
			$this->mTitleDisplay = $this->convert($text);
			return $this->mTitleDisplay;
		}
	}

	/**
	 * convert text to different variants of a language. the automatic
	 * conversion is done in autoConvert(). here we parse the text
	 * marked with -{}-, which specifies special conversions of the
	 * text that can not be accomplished in autoConvert()
	 *
	 * syntax of the markup:
	 * -{code1:text1;code2:text2;...}-  or
	 * -{flags|code1:text1;code2:text2;...}-  or
	 * -{text}- in which case no conversion should take place for text
	 *
	 * @param string $text text to be converted
	 * @param bool $isTitle whether this conversion is for the article title
	 * @return string converted text
	 * @access public
	 */
	function convert( $text , $isTitle=false) {

		$mw =& MagicWord::get( 'notitleconvert' );
		if( $mw->matchAndRemove( $text ) )
			$this->mDoTitleConvert = false;
		$mw =& MagicWord::get( 'nocontentconvert' );
		if( $mw->matchAndRemove( $text ) ) {
			$this->mDoContentConvert = false;
		}

		// no conversion if redirecting
		$mw =& MagicWord::get( 'redirect' );
		if( $mw->matchStart( $text ))
			return $text;

		// for title convertion
		if ($isTitle) return $this->convertTitle($text);

		$plang = $this->getPreferredVariant();

		$tarray = explode($this->mMarkup['begin'], $text);
		$tfirst = array_shift($tarray);
		if($this->mDoContentConvert) 
			$text = $this->autoConvert($tfirst,$plang);
		else
			$text = $tfirst;
		foreach($tarray as $txt) {
			$marked = explode($this->mMarkup['end'], $txt, 2);

			// strip the flags from syntax like -{T| ... }-
			list($rules,$flags) = $this->parseFlags($marked[0]);

			$text .= $this->parseRules($rules,$flags,$plang);

			if(array_key_exists(1, $marked)){
				if( $this->mDoContentConvert )
					$text .= $this->autoConvert($marked[1],$plang);
				else
					$text .= $marked[1];
			}
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
		if ( !$this->mTables || !isset( $this->mTables[self::CACHE_VERSION_KEY] ) ) {
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
			$this->mTables[self::CACHE_VERSION_KEY] = true;

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

		$ret = $this->mMarkup['begin'] .'R|'. $text . $this->mMarkup['end'];
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
	function OnArticleSaveComplete($article, $user, $text, $summary, $isminor, $iswatch, $section, $flags, $revision) {
		$titleobj = $article->getTitle();
		if($titleobj->getNamespace() == NS_MEDIAWIKI) {
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
