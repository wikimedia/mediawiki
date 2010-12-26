<?php
/**
 * Options for the PHP parser
 *
 * @file
 * @ingroup Parser
 */

/**
 * Set options of the Parser
 * @todo document
 * @ingroup Parser
 */
class ParserOptions {
	# All variables are supposed to be private in theory, although in practise this is not the case.
	var $mUseDynamicDates;           # Use DateFormatter to format dates
	var $mInterwikiMagic;            # Interlanguage links are removed and returned in an array
	var $mAllowExternalImages;       # Allow external images inline
	var $mAllowExternalImagesFrom;   # If not, any exception?
	var $mEnableImageWhitelist;      # If not or it doesn't match, should we check an on-wiki whitelist?
	var $mSkin = null;               # Reference to the preferred skin
	var $mDateFormat = null;         # Date format index
	var $mEditSection = true;        # Create "edit section" links
	var $mAllowSpecialInclusion;     # Allow inclusion of special pages
	var $mTidy = false;              # Ask for tidy cleanup
	var $mInterfaceMessage = false;  # Which lang to call for PLURAL and GRAMMAR
	var $mTargetLanguage = null;     # Overrides above setting with arbitrary language
	var $mMaxIncludeSize;            # Maximum size of template expansions, in bytes
	var $mMaxPPNodeCount;            # Maximum number of nodes touched by PPFrame::expand()
	var $mMaxPPExpandDepth;          # Maximum recursion depth in PPFrame::expand()
	var $mMaxTemplateDepth;          # Maximum recursion depth for templates within templates
	var $mRemoveComments = true;     # Remove HTML comments. ONLY APPLIES TO PREPROCESS OPERATIONS
	var $mTemplateCallback =         # Callback for template fetching
		array( 'Parser', 'statelessFetchTemplate' );
	var $mEnableLimitReport = false; # Enable limit report in an HTML comment on output
	var $mTimestamp;                 # Timestamp used for {{CURRENTDAY}} etc.
	var $mExternalLinkTarget;        # Target attribute for external links
	var $mCleanSignatures;           #

	var $mNumberHeadings;            # Automatically number headings
	var $mMath;                      # User math preference (as integer)
	var $mThumbSize;                 # Thumb size preferred by the user.
	var $mUserLang;                  # Language code of the User language.

	var $mUser;                      # Stored user object
	var $mIsPreview = false;         # Parsing the page for a "preview" operation
	var $mIsSectionPreview = false;  # Parsing the page for a "preview" operation on a single section
	var $mIsPrintable = false;       # Parsing the printable version of the page

	var $mExtraKey = '';             # Extra key that should be present in the caching key.

	protected $onAccessCallback = null;

	function getUseDynamicDates()               { return $this->mUseDynamicDates; }
	function getInterwikiMagic()                { return $this->mInterwikiMagic; }
	function getAllowExternalImages()           { return $this->mAllowExternalImages; }
	function getAllowExternalImagesFrom()       { return $this->mAllowExternalImagesFrom; }
	function getEnableImageWhitelist()          { return $this->mEnableImageWhitelist; }
	function getEditSection()                   { $this->optionUsed('editsection');
												  return $this->mEditSection; }
	function getNumberHeadings()                { $this->optionUsed('numberheadings');
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
	function getMath()                          { $this->optionUsed('math');
												  return $this->mMath; }
	function getThumbSize()                     { $this->optionUsed('thumbsize');
												  return $this->mThumbSize; }

	function getIsPreview()                     { return $this->mIsPreview; }
	function getIsSectionPreview()              { return $this->mIsSectionPreview; }
	function getIsPrintable()                   { $this->optionUsed('printable');
												  return $this->mIsPrintable; }
	function getUser()                          { return $this->mUser; }

	function getSkin( $title = null ) {
		if ( !isset( $this->mSkin ) ) {
			$this->mSkin = $this->mUser->getSkin( $title );
		}
		return $this->mSkin;
	}

	function getDateFormat() {
		$this->optionUsed('dateformat');
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
	 */
	function getUserLang() {
		$this->optionUsed('userlang');
		return $this->mUserLang;
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
	function setTidy( $x )                      { return wfSetVar( $this->mTidy, $x); }
	function setSkin( $x )                      { $this->mSkin = $x; }
	function setInterfaceMessage( $x )          { return wfSetVar( $this->mInterfaceMessage, $x); }
	function setTargetLanguage( $x )            { return wfSetVar( $this->mTargetLanguage, $x); }
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
	function setUserLang( $x )                  { return wfSetVar( $this->mUserLang, $x ); }
	function setThumbSize( $x )                 { return wfSetVar( $this->mThumbSize, $x ); }

	function setIsPreview( $x )                 { return wfSetVar( $this->mIsPreview, $x ); }
	function setIsSectionPreview( $x )          { return wfSetVar( $this->mIsSectionPreview, $x ); }
	function setIsPrintable( $x )               { return wfSetVar( $this->mIsPrintable, $x ); }

	/**
	 * Extra key that should be present in the parser cache key.
	 */
	function addExtraKey( $key ) {
		$this->mExtraKey .= '!' . $key;
	}

	function __construct( $user = null ) {
		$this->initialiseFromUser( $user );
	}

	/**
	 * Get parser options
	 *
	 * @param $user User object
	 * @return ParserOptions object
	 */
	static function newFromUser( $user ) {
		return new ParserOptions( $user );
	}

	/** Get user options */
	function initialiseFromUser( $userInput ) {
		global $wgUseDynamicDates, $wgInterwikiMagic, $wgAllowExternalImages;
		global $wgAllowExternalImagesFrom, $wgEnableImageWhitelist, $wgAllowSpecialInclusion, $wgMaxArticleSize;
		global $wgMaxPPNodeCount, $wgMaxTemplateDepth, $wgMaxPPExpandDepth, $wgCleanSignatures;
		global $wgExternalLinkTarget, $wgLang;

		wfProfileIn( __METHOD__ );

		if ( !$userInput ) {
			global $wgUser;
			if ( isset( $wgUser ) ) {
				$user = $wgUser;
			} else {
				$user = new User;
			}
		} else {
			$user =& $userInput;
		}

		$this->mUser = $user;

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

		$this->mNumberHeadings = $user->getOption( 'numberheadings' );
		$this->mMath = $user->getOption( 'math' );
		$this->mThumbSize = $user->getOption( 'thumbsize' );
		$this->mUserLang = $wgLang->getCode();

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
	 * @return \string Page rendering hash
	 */
	public function optionsHash( $forOptions ) {
		global $wgContLang, $wgRenderHashAppend;

		$confstr = '';

		if ( in_array( 'math', $forOptions ) )
			$confstr .= $this->mMath;
		else
			$confstr .= '*';


		// Space assigned for the stubthreshold but unused
		// since it disables the parser cache, its value will always
		// be 0 when this function is called by parsercache.
		// The conditional is here to avoid a confusing 0
		if ( in_array( 'stubthreshold', $forOptions ) )
			$confstr .= '!0' ;
		else
			$confstr .= '!*' ;

		if ( in_array( 'dateformat', $forOptions ) )
			$confstr .= '!' . $this->getDateFormat();

		if ( in_array( 'numberheadings', $forOptions ) )
			$confstr .= '!' . ( $this->mNumberHeadings ? '1' : '' );
		else
			$confstr .= '!*';

		if ( in_array( 'userlang', $forOptions ) )
			$confstr .= '!' . $this->mUserLang;
		else
			$confstr .= '!*';

		if ( in_array( 'thumbsize', $forOptions ) )
			$confstr .= '!' . $this->mThumbSize;
		else
			$confstr .= '!*';

		// add in language specific options, if any
		// FIXME: This is just a way of retrieving the url/user preferred variant
		$confstr .= $wgContLang->getExtraHashOptions();

		// Since the skin could be overloading link(), it should be
		// included here but in practice, none of our skins do that.
		// $confstr .= "!" . $this->mSkin->getSkinName();

		$confstr .= $wgRenderHashAppend;

		if ( !$this->mEditSection && in_array( 'editsection', $forOptions ) )
			$confstr .= '!edit=0';
		if (  $this->mIsPrintable && in_array( 'printable', $forOptions ) )
			$confstr .= '!printable=1';

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
