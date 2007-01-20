<?php

/**
 * Set options of the Parser
 * @todo document
 */
class ParserOptions
{
	# All variables are supposed to be private in theory, although in practise this is not the case.
	var $mUseTeX;                    # Use texvc to expand <math> tags
	var $mUseDynamicDates;           # Use DateFormatter to format dates
	var $mInterwikiMagic;            # Interlanguage links are removed and returned in an array
	var $mAllowExternalImages;       # Allow external images inline
	var $mAllowExternalImagesFrom;   # If not, any exception?
	var $mSkin;                      # Reference to the preferred skin
	var $mDateFormat;                # Date format index
	var $mEditSection;               # Create "edit section" links
	var $mNumberHeadings;            # Automatically number headings
	var $mAllowSpecialInclusion;     # Allow inclusion of special pages
	var $mTidy;                      # Ask for tidy cleanup
	var $mInterfaceMessage;          # Which lang to call for PLURAL and GRAMMAR
	var $mMaxIncludeSize;            # Maximum size of template expansions, in bytes
	var $mRemoveComments;            # Remove HTML comments. ONLY APPLIES TO PREPROCESS OPERATIONS

	var $mUser;                      # Stored user object, just used to initialise the skin

	function getUseTeX()                        { return $this->mUseTeX; }
	function getUseDynamicDates()               { return $this->mUseDynamicDates; }
	function getInterwikiMagic()                { return $this->mInterwikiMagic; }
	function getAllowExternalImages()           { return $this->mAllowExternalImages; }
	function getAllowExternalImagesFrom()       { return $this->mAllowExternalImagesFrom; }
	function getEditSection()                   { return $this->mEditSection; }
	function getNumberHeadings()                { return $this->mNumberHeadings; }
	function getAllowSpecialInclusion()         { return $this->mAllowSpecialInclusion; }
	function getTidy()                          { return $this->mTidy; }
	function getInterfaceMessage()              { return $this->mInterfaceMessage; }
	function getMaxIncludeSize()                { return $this->mMaxIncludeSize; }
	function getRemoveComments()                { return $this->mRemoveComments; }

	function &getSkin() {
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

	function setUseTeX( $x )                    { return wfSetVar( $this->mUseTeX, $x ); }
	function setUseDynamicDates( $x )           { return wfSetVar( $this->mUseDynamicDates, $x ); }
	function setInterwikiMagic( $x )            { return wfSetVar( $this->mInterwikiMagic, $x ); }
	function setAllowExternalImages( $x )       { return wfSetVar( $this->mAllowExternalImages, $x ); }
	function setAllowExternalImagesFrom( $x )   { return wfSetVar( $this->mAllowExternalImagesFrom, $x ); }
	function setDateFormat( $x )                { return wfSetVar( $this->mDateFormat, $x ); }
	function setEditSection( $x )               { return wfSetVar( $this->mEditSection, $x ); }
	function setNumberHeadings( $x )            { return wfSetVar( $this->mNumberHeadings, $x ); }
	function setAllowSpecialInclusion( $x )     { return wfSetVar( $this->mAllowSpecialInclusion, $x ); }
	function setTidy( $x )                      { return wfSetVar( $this->mTidy, $x); }
	function setSkin( $x )                      { $this->mSkin = $x; }
	function setInterfaceMessage( $x )          { return wfSetVar( $this->mInterfaceMessage, $x); }
	function setMaxIncludeSize( $x )            { return wfSetVar( $this->mMaxIncludeSize, $x ); }
	function setRemoveComments( $x )            { return wfSetVar( $this->mRemoveComments, $x ); }

	function __construct( $user = null ) {
		$this->initialiseFromUser( $user );
	}

	/**
	 * Get parser options
	 * @static
	 */
	static function newFromUser( $user ) {
		return new ParserOptions( $user );
	}

	/** Get user options */
	function initialiseFromUser( $userInput ) {
		global $wgUseTeX, $wgUseDynamicDates, $wgInterwikiMagic, $wgAllowExternalImages;
		global $wgAllowExternalImagesFrom, $wgAllowSpecialInclusion, $wgMaxArticleSize;
		$fname = 'ParserOptions::initialiseFromUser';
		wfProfileIn( $fname );
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

		$this->mUseTeX = $wgUseTeX;
		$this->mUseDynamicDates = $wgUseDynamicDates;
		$this->mInterwikiMagic = $wgInterwikiMagic;
		$this->mAllowExternalImages = $wgAllowExternalImages;
		$this->mAllowExternalImagesFrom = $wgAllowExternalImagesFrom;
		$this->mSkin = null; # Deferred
		$this->mDateFormat = null; # Deferred
		$this->mEditSection = true;
		$this->mNumberHeadings = $user->getOption( 'numberheadings' );
		$this->mAllowSpecialInclusion = $wgAllowSpecialInclusion;
		$this->mTidy = false;
		$this->mInterfaceMessage = false;
		$this->mMaxIncludeSize = $wgMaxArticleSize * 1024;
		$this->mRemoveComments = true;
		wfProfileOut( $fname );
	}
}

?>
