<?php

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
	var $mSkin;                      # Reference to the preferred skin
	var $mDateFormat;                # Date format index
	var $mEditSection;               # Create "edit section" links
	var $mNumberHeadings;            # Automatically number headings
	var $mAllowSpecialInclusion;     # Allow inclusion of special pages
	var $mTidy;                      # Ask for tidy cleanup
	var $mInterfaceMessage;          # Which lang to call for PLURAL and GRAMMAR
	var $mTargetLanguage;            # Overrides above setting with arbitrary language
	var $mMaxIncludeSize;            # Maximum size of template expansions, in bytes
	var $mMaxPPNodeCount;            # Maximum number of nodes touched by PPFrame::expand()
	var $mMaxPPExpandDepth;          # Maximum recursion depth in PPFrame::expand()
	var $mMaxTemplateDepth;          # Maximum recursion depth for templates within templates
	var $mRemoveComments;            # Remove HTML comments. ONLY APPLIES TO PREPROCESS OPERATIONS
	var $mTemplateCallback;          # Callback for template fetching
	var $mEnableLimitReport;         # Enable limit report in an HTML comment on output
	var $mTimestamp;                 # Timestamp used for {{CURRENTDAY}} etc.
	var $mExternalLinkTarget;        # Target attribute for external links
	var $mMath;                      # User math preference (as integer)
	var $mUserLang;                  # Language code of the User language.
	var $mThumbSize;                 # Thumb size preferred by the user.

	var $mUser;                      # Stored user object, just used to initialise the skin
	var $mIsPreview;                 # Parsing the page for a "preview" operation
	var $mIsSectionPreview;          # Parsing the page for a "preview" operation on a single section
	var $mIsPrintable;               # Parsing the printable version of the page
	
	function getUseDynamicDates()               { return $this->mUseDynamicDates; }
	function getInterwikiMagic()                { return $this->mInterwikiMagic; }
	function getAllowExternalImages()           { return $this->mAllowExternalImages; }
	function getAllowExternalImagesFrom()       { return $this->mAllowExternalImagesFrom; }
	function getEnableImageWhitelist()          { return $this->mEnableImageWhitelist; }
	function getEditSection()                   { return $this->mEditSection; }
	function getNumberHeadings()                { return $this->mNumberHeadings; }
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
	function getMath()                          { return $this->mMath; }
	function getThumbSize()                     { return $this->mThumbSize; }
	
	function getIsPreview()                     { return $this->mIsPreview; }
	function getIsSectionPreview()              { return $this->mIsSectionPreview; }
	function getIsPrintable()                   { return $this->mIsPrintable; }

	function getSkin() {
		if ( !isset( $this->mSkin ) ) {
			$this->mSkin = $this->mUser->getSkin();
		}
		return $this->mSkin;
	}

	function getDateFormat() {
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

	# You shouldn't use this. Really. $parser->getFunctionLang() is all you need.
	# Using this fragments the cache and is discouraged. Yes, {{int: }} uses this,
	# producing inconsistent tables (Bug 14404).
	function getUserLang() {
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
	function setThumbSize()                     { return wfSetVar( $this->mThumbSize, $x ); }
	
	function setIsPreview( $x )                 { return wfSetVar( $this->mIsPreview, $x ); }
	function setIsSectionPreview( $x )          { return wfSetVar( $this->mIsSectionPreview, $x ); }
	function setIsPrintable( $x )               { return wfSetVar( $this->mIsPrintable, $x ); }

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
		$this->mSkin = null; # Deferred
		$this->mDateFormat = null; # Deferred
		$this->mEditSection = true;
		$this->mNumberHeadings = $user->getOption( 'numberheadings' );
		$this->mAllowSpecialInclusion = $wgAllowSpecialInclusion;
		$this->mTidy = false;
		$this->mInterfaceMessage = false;
		$this->mTargetLanguage = null; // default depends on InterfaceMessage setting
		$this->mMaxIncludeSize = $wgMaxArticleSize * 1024;
		$this->mMaxPPNodeCount = $wgMaxPPNodeCount;
		$this->mMaxPPExpandDepth = $wgMaxPPExpandDepth;
		$this->mMaxTemplateDepth = $wgMaxTemplateDepth;
		$this->mRemoveComments = true;
		$this->mTemplateCallback = array( 'Parser', 'statelessFetchTemplate' );
		$this->mEnableLimitReport = false;
		$this->mCleanSignatures = $wgCleanSignatures;
		$this->mExternalLinkTarget = $wgExternalLinkTarget;
		$this->mMath = $user->getOption( 'math' );
		$this->mUserLang = $wgLang->getCode();
		$this->mThumbSize = $user->getOption( 'thumbsize' );
		
		$this->mIsPreview = false;
		$this->mIsSectionPreview = false;
		$this->mIsPrintable = false;

		wfProfileOut( __METHOD__ );
	}
}
