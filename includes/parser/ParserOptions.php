<?php
/**
 * \brief Options for the PHP parser
 *
 * @file
 * @ingroup Parser
 */

/**
 * \brief Set options of the Parser
 *
 * All member variables are supposed to be private in theory, although in practise this is not the case.
 *
 * @ingroup Parser
 */
class ParserOptions {
	
	/**
	 * Use DateFormatter to format dates
	 */
	var $mUseDynamicDates;
	
	/**
	 * Interlanguage links are removed and returned in an array
	 */
	var $mInterwikiMagic;
	
	/**
	 * Allow external images inline?
	 */
	var $mAllowExternalImages;
	
	/**
	 * If not, any exception?
	 */
	var $mAllowExternalImagesFrom;
	
	/**
	 * If not or it doesn't match, should we check an on-wiki whitelist?
	 */
	var $mEnableImageWhitelist;
	
	/**
	 * Date format index
	 */
	var $mDateFormat = null;
	
	/**
	 * Create "edit section" links?
	 */
	var $mEditSection = true;
	
	/**
	 * Allow inclusion of special pages?
	 */
	var $mAllowSpecialInclusion;
	
	/**
	 * Use tidy to cleanup output HTML?
	 */
	var $mTidy = false;
	
	/**
	 * Which lang to call for PLURAL and GRAMMAR
	 */
	var $mInterfaceMessage = false;
	
	/**
	 * Overrides $mInterfaceMessage with arbitrary language
	 */
	var $mTargetLanguage = null;
	
	/**
	 * Maximum size of template expansions, in bytes
	 */
	var $mMaxIncludeSize;
	
	/**
	 * Maximum number of nodes touched by PPFrame::expand()
	 */
	var $mMaxPPNodeCount;
	
	/**
	 * Maximum recursion depth in PPFrame::expand()
	 */
	var $mMaxPPExpandDepth;
	
	/**
	 * Maximum recursion depth for templates within templates
	 */
	var $mMaxTemplateDepth;
	
	/**
	 * Remove HTML comments. ONLY APPLIES TO PREPROCESS OPERATIONS
	 */
	var $mRemoveComments = true;
	
	/**
	 * Callback for template fetching. Used as first argument to call_user_func().
	 */
	var $mTemplateCallback =
		array( 'Parser', 'statelessFetchTemplate' );
		
	/**
	 * Enable limit report in an HTML comment on output
	 */
	var $mEnableLimitReport = false;
	
	/**
	 * Timestamp used for {{CURRENTDAY}} etc.
	 */
	var $mTimestamp;
	
	/**
	 * Target attribute for external links
	 */
	var $mExternalLinkTarget;
	
	/**
	 * Clean up signature texts? 
	 *
	 * 1) Strip ~~~, ~~~~ and ~~~~~ out of signatures
	 * 2) Substitute all transclusions
	 */
	var $mCleanSignatures;
	
	/**
	 * Transform wiki markup when saving the page?
	 */
	var $mPreSaveTransform = true;

	/**
	 * Automatically number headings?
	 */
	var $mNumberHeadings;
	
	/**
	 * User math preference (as integer). Not used (1.19)
	 */
	var $mMath;
	
	/**
	 * Thumb size preferred by the user.
	 */
	var $mThumbSize;
	
	/**
	 * Maximum article size of an article to be marked as "stub"
	 */
	private $mStubThreshold;
	
	/**
	 * Language object of the User language.
	 */
	var $mUserLang;

	/**
	 * @var User 
	 * Stored user object
	 */
	var $mUser;
	
	/**
	 * Parsing the page for a "preview" operation?
	 */
	var $mIsPreview = false;
	
	/**
	 * Parsing the page for a "preview" operation on a single section?
	 */
	var $mIsSectionPreview = false;
	
	/**
	 * Parsing the printable version of the page?
	 */
	var $mIsPrintable = false;

	/**
	 * Extra key that should be present in the caching key.
	 */
	var $mExtraKey = '';

	/**
	 * Function to be called when an option is accessed.
	 */
	protected $onAccessCallback = null;

	function getUseDynamicDates()               { return $this->mUseDynamicDates; }
	function getInterwikiMagic()                { return $this->mInterwikiMagic; }
	function getAllowExternalImages()           { return $this->mAllowExternalImages; }
	function getAllowExternalImagesFrom()       { return $this->mAllowExternalImagesFrom; }
	function getEnableImageWhitelist()          { return $this->mEnableImageWhitelist; }
	function getEditSection()                   { return $this->mEditSection; }
	function getNumberHeadings()                { $this->optionUsed( 'numberheadings' );
												  return $this->mNumberHeadings; }
	function getAllowSpecialInclusion()         { return $this->mAllowSpecialInclusion; }
	function getTidy()                          { return $this->mTidy; }
	function getInterfaceMessage()              { return $this->mInterfaceMessage; }
	function getTargetLanguage()                { return $this->mTargetLanguage; }
	function getMaxIncludeSize()                { return $this->mMaxIncludeSize; }
	function getMaxPPNodeCount()                { return $this->mMaxPPNodeCount; }
	function getMaxPPExpandDepth()              { return $this->mMaxPPExpandDepth; }
	function getMaxTemplateDepth()              { return $this->mMaxTemplateDepth; }
	function getRemoveComments()                { return $this->mRemoveComments; }
	function getTemplateCallback()              { return $this->mTemplateCallback; }
	function getEnableLimitReport()             { return $this->mEnableLimitReport; }
	function getCleanSignatures()               { return $this->mCleanSignatures; }
	function getExternalLinkTarget()            { return $this->mExternalLinkTarget; }
	function getMath()                          { $this->optionUsed( 'math' );
												  return $this->mMath; }
	function getThumbSize()                     { $this->optionUsed( 'thumbsize' );
												  return $this->mThumbSize; }
	function getStubThreshold()                 { $this->optionUsed( 'stubthreshold' );
												  return $this->mStubThreshold; }

	function getIsPreview()                     { return $this->mIsPreview; }
	function getIsSectionPreview()              { return $this->mIsSectionPreview; }
	function getIsPrintable()                   { $this->optionUsed( 'printable' );
												  return $this->mIsPrintable; }
	function getUser()                          { return $this->mUser; }
	function getPreSaveTransform()              { return $this->mPreSaveTransform; }

	/**
	 * @param $title Title
	 * @return Skin
	 * @deprecated since 1.18 Use Linker::* instead
	 */
	function getSkin( $title = null ) {
		wfDeprecated( __METHOD__, '1.18' );
		return new DummyLinker;
	}

	function getDateFormat() {
		$this->optionUsed( 'dateformat' );
		if ( !isset( $this->mDateFormat ) ) {
			$this->mDateFormat = $this->mUser->getDatePreference();
		}
		return $this->mDateFormat;
	}

	function getTimestamp() {
		if ( !isset( $this->mTimestamp ) ) {
			$this->mTimestamp = wfTimestampNow();
		}
		return $this->mTimestamp;
	}

	/**
	 * You shouldn't use this. Really. $parser->getFunctionLang() is all you need.
	 * Using this fragments the cache and is discouraged. Yes, {{int: }} uses this,
	 * producing inconsistent tables (Bug 14404).
	 *
	 * @return Language object
	 * @since 1.19
	 */
	function getUserLangObj() {
		$this->optionUsed( 'userlang' );
		return $this->mUserLang;
	}

	/**
	 * Same as getUserLangObj() but returns a string instead.
	 *
	 * @return String   Language code
	 * @since 1.17
	 */
	function getUserLang() {
		return $this->getUserLangObj()->getCode();
	}

	function setUseDynamicDates( $x )           { return wfSetVar( $this->mUseDynamicDates, $x ); }
	function setInterwikiMagic( $x )            { return wfSetVar( $this->mInterwikiMagic, $x ); }
	function setAllowExternalImages( $x )       { return wfSetVar( $this->mAllowExternalImages, $x ); }
	function setAllowExternalImagesFrom( $x )   { return wfSetVar( $this->mAllowExternalImagesFrom, $x ); }
	function setEnableImageWhitelist( $x )      { return wfSetVar( $this->mEnableImageWhitelist, $x ); }
	function setDateFormat( $x )                { return wfSetVar( $this->mDateFormat, $x ); }
	function setEditSection( $x )               { return wfSetVar( $this->mEditSection, $x ); }
	function setNumberHeadings( $x )            { return wfSetVar( $this->mNumberHeadings, $x ); }
	function setAllowSpecialInclusion( $x )     { return wfSetVar( $this->mAllowSpecialInclusion, $x ); }
	function setTidy( $x )                      { return wfSetVar( $this->mTidy, $x ); }

	/** @deprecated in 1.19; will be removed in 1.20 */
	function setSkin( $x )                      { wfDeprecated( __METHOD__, '1.19' ); }
	function setInterfaceMessage( $x )          { return wfSetVar( $this->mInterfaceMessage, $x ); }
	function setTargetLanguage( $x )            { return wfSetVar( $this->mTargetLanguage, $x, true ); }
	function setMaxIncludeSize( $x )            { return wfSetVar( $this->mMaxIncludeSize, $x ); }
	function setMaxPPNodeCount( $x )            { return wfSetVar( $this->mMaxPPNodeCount, $x ); }
	function setMaxTemplateDepth( $x )          { return wfSetVar( $this->mMaxTemplateDepth, $x ); }
	function setRemoveComments( $x )            { return wfSetVar( $this->mRemoveComments, $x ); }
	function setTemplateCallback( $x )          { return wfSetVar( $this->mTemplateCallback, $x ); }
	function enableLimitReport( $x = true )     { return wfSetVar( $this->mEnableLimitReport, $x ); }
	function setTimestamp( $x )                 { return wfSetVar( $this->mTimestamp, $x ); }
	function setCleanSignatures( $x )           { return wfSetVar( $this->mCleanSignatures, $x ); }
	function setExternalLinkTarget( $x )        { return wfSetVar( $this->mExternalLinkTarget, $x ); }
	function setMath( $x )                      { return wfSetVar( $this->mMath, $x ); }
	function setUserLang( $x )                  {
		if ( is_string( $x ) ) {
			$x = Language::factory( $x );
		}
		return wfSetVar( $this->mUserLang, $x );
	}
	function setThumbSize( $x )                 { return wfSetVar( $this->mThumbSize, $x ); }
	function setStubThreshold( $x )             { return wfSetVar( $this->mStubThreshold, $x ); }
	function setPreSaveTransform( $x )          { return wfSetVar( $this->mPreSaveTransform, $x ); }

	function setIsPreview( $x )                 { return wfSetVar( $this->mIsPreview, $x ); }
	function setIsSectionPreview( $x )          { return wfSetVar( $this->mIsSectionPreview, $x ); }
	function setIsPrintable( $x )               { return wfSetVar( $this->mIsPrintable, $x ); }

	/**
	 * Extra key that should be present in the parser cache key.
	 */
	function addExtraKey( $key ) {
		$this->mExtraKey .= '!' . $key;
	}

	/**
	 * Constructor
	 * @param $user User object
	 * @param $lang Language object
	 */
	function __construct( $user = null, $lang = null ) {
		if ( $user === null ) {
			global $wgUser;
			if ( $wgUser === null ) {
				$user = new User;
			} else {
				$user = $wgUser;
			}
		}
		if ( $lang === null ) {
			global $wgLang;
			if ( !StubObject::isRealObject( $wgLang ) ) {
				$wgLang->_unstub();
			}
			$lang = $wgLang;
		}
		$this->initialiseFromUser( $user, $lang );
	}

	/**
	 * Get a ParserOptions object from a given user.
	 * Language will be taken from $wgLang.
	 *
	 * @param $user User object
	 * @return ParserOptions object
	 */
	public static function newFromUser( $user ) {
		return new ParserOptions( $user );
	}

	/**
	 * Get a ParserOptions object from a given user and language
	 *
	 * @param $user User object
	 * @param $lang Language object
	 * @return ParserOptions object
	 */
	public static function newFromUserAndLang( User $user, Language $lang ) {
		return new ParserOptions( $user, $lang );
	}

	/**
	 * Get a ParserOptions object from a IContextSource object
	 *
	 * @param $context IContextSource object
	 * @return ParserOptions object
	 */
	public static function newFromContext( IContextSource $context ) {
		return new ParserOptions( $context->getUser(), $context->getLanguage() );
	}

	/** 
	 * Get user options 
	 *
	 * @param $user User object
	 * @param $lang Language object
	 */
	private function initialiseFromUser( $user, $lang ) {
		global $wgUseDynamicDates, $wgInterwikiMagic, $wgAllowExternalImages,
			$wgAllowExternalImagesFrom, $wgEnableImageWhitelist, $wgAllowSpecialInclusion,
			$wgMaxArticleSize, $wgMaxPPNodeCount, $wgMaxTemplateDepth, $wgMaxPPExpandDepth,
			$wgCleanSignatures, $wgExternalLinkTarget;

		wfProfileIn( __METHOD__ );

		$this->mUseDynamicDates = $wgUseDynamicDates;
		$this->mInterwikiMagic = $wgInterwikiMagic;
		$this->mAllowExternalImages = $wgAllowExternalImages;
		$this->mAllowExternalImagesFrom = $wgAllowExternalImagesFrom;
		$this->mEnableImageWhitelist = $wgEnableImageWhitelist;
		$this->mAllowSpecialInclusion = $wgAllowSpecialInclusion;
		$this->mMaxIncludeSize = $wgMaxArticleSize * 1024;
		$this->mMaxPPNodeCount = $wgMaxPPNodeCount;
		$this->mMaxPPExpandDepth = $wgMaxPPExpandDepth;
		$this->mMaxTemplateDepth = $wgMaxTemplateDepth;
		$this->mCleanSignatures = $wgCleanSignatures;
		$this->mExternalLinkTarget = $wgExternalLinkTarget;

		$this->mUser = $user;
		$this->mNumberHeadings = $user->getOption( 'numberheadings' );
		$this->mMath = $user->getOption( 'math' );
		$this->mThumbSize = $user->getOption( 'thumbsize' );
		$this->mStubThreshold = $user->getStubThreshold();
		$this->mUserLang = $lang;

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Registers a callback for tracking which ParserOptions which are used.
	 * This is a private API with the parser.
	 */
	function registerWatcher( $callback ) {
		$this->onAccessCallback = $callback;
	}

	/**
	 * Called when an option is accessed.
	 */
	protected function optionUsed( $optionName ) {
		if ( $this->onAccessCallback ) {
			call_user_func( $this->onAccessCallback, $optionName );
		}
	}

	/**
	 * Returns the full array of options that would have been used by
	 * in 1.16.
	 * Used to get the old parser cache entries when available.
	 */
	public static function legacyOptions() {
		global $wgUseDynamicDates;
		$legacyOpts = array( 'math', 'stubthreshold', 'numberheadings', 'userlang', 'thumbsize', 'editsection', 'printable' );
		if ( $wgUseDynamicDates ) {
			$legacyOpts[] = 'dateformat';
		}
		return $legacyOpts;
	}

	/**
	 * Generate a hash string with the values set on these ParserOptions
	 * for the keys given in the array.
	 * This will be used as part of the hash key for the parser cache,
	 * so users sharign the options with vary for the same page share
	 * the same cached data safely.
	 *
	 * Replaces User::getPageRenderingHash()
	 *
	 * Extensions which require it should install 'PageRenderingHash' hook,
	 * which will give them a chance to modify this key based on their own
	 * settings.
	 *
	 * @since 1.17
	 * @param $forOptions Array
	 * @param $title Title: used to get the content language of the page (since r97636)
	 * @return string Page rendering hash
	 */
	public function optionsHash( $forOptions, $title = null ) {
		global $wgRenderHashAppend;

		$confstr = '';

		if ( in_array( 'math', $forOptions ) ) {
			$confstr .= $this->mMath;
		} else {
			$confstr .= '*';
		}


		// Space assigned for the stubthreshold but unused
		// since it disables the parser cache, its value will always
		// be 0 when this function is called by parsercache.
		if ( in_array( 'stubthreshold', $forOptions ) ) {
			$confstr .= '!' . $this->mStubThreshold;
		} else {
			$confstr .= '!*' ;
		}

		if ( in_array( 'dateformat', $forOptions ) ) {
			$confstr .= '!' . $this->getDateFormat();
		}

		if ( in_array( 'numberheadings', $forOptions ) ) {
			$confstr .= '!' . ( $this->mNumberHeadings ? '1' : '' );
		} else {
			$confstr .= '!*';
		}

		if ( in_array( 'userlang', $forOptions ) ) {
			$confstr .= '!' . $this->mUserLang->getCode();
		} else {
			$confstr .= '!*';
		}

		if ( in_array( 'thumbsize', $forOptions ) ) {
			$confstr .= '!' . $this->mThumbSize;
		} else {
			$confstr .= '!*';
		}

		// add in language specific options, if any
		// @todo FIXME: This is just a way of retrieving the url/user preferred variant
		if( !is_null( $title ) ) {
			$confstr .= $title->getPageLanguage()->getExtraHashOptions();
		} else {
			global $wgContLang;
			$confstr .= $wgContLang->getExtraHashOptions();
		}

		$confstr .= $wgRenderHashAppend;

		if ( !in_array( 'editsection', $forOptions ) ) {
			$confstr .= '!*';
		} elseif ( !$this->mEditSection ) {
			$confstr .= '!edit=0';
		}

		if ( $this->mIsPrintable && in_array( 'printable', $forOptions ) ) {
			$confstr .= '!printable=1';
		}

		if ( $this->mExtraKey != '' )
			$confstr .= $this->mExtraKey;

		// Give a chance for extensions to modify the hash, if they have
		// extra options or other effects on the parser cache.
		wfRunHooks( 'PageRenderingHash', array( &$confstr ) );

		// Make it a valid memcached key fragment
		$confstr = str_replace( ' ', '_', $confstr );

		return $confstr;
	}
}
