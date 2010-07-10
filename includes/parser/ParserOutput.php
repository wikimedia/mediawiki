<?php
/**
 * @todo document
 * @ingroup Parser
 */
class ParserOutput
{
	var $mText,                       # The output text
		$mLanguageLinks,              # List of the full text of language links, in the order they appear
		$mCategories,                 # Map of category names to sort keys
		$mContainsOldMagic,           # Boolean variable indicating if the input contained variables like {{CURRENTDAY}}
		$mTitleText,                  # title text of the chosen language variant
		$mCacheTime = '',             # Time when this object was generated, or -1 for uncacheable. Used in ParserCache.
		$mCacheExpiry = null,         # Seconds after which the object should expire, use 0 for uncachable. Used in ParserCache.
		$mVersion = Parser::VERSION,  # Compatibility check
		$mLinks = array(),            # 2-D map of NS/DBK to ID for the links in the document. ID=zero for broken.
		$mTemplates = array(),        # 2-D map of NS/DBK to ID for the template references. ID=zero for broken.
		$mTemplateIds = array(),      # 2-D map of NS/DBK to rev ID for the template references. ID=zero for broken.
		$mImages = array(),           # DB keys of the images used, in the array key only
		$mExternalLinks = array(),    # External link URLs, in the key only
		$mInterwikiLinks = array(),   # 2-D map of prefix/DBK (in keys only) for the inline interwiki links in the document.
		$mNewSection = false,         # Show a new section link?
		$mHideNewSection = false,     # Hide the new section link?
		$mNoGallery = false,          # No gallery on category page? (__NOGALLERY__)
		$mHeadItems = array(),        # Items to put in the <head> section
		$mOutputHooks = array(),      # Hook tags as per $wgParserOutputHooks
		$mWarnings = array(),         # Warning text to be returned to the user. Wikitext formatted, in the key only
		$mSections = array(),         # Table of contents
		$mProperties = array(),       # Name/value pairs to be cached in the DB
		$mTOCHTML = '';	              # HTML of the TOC
	private $mIndexPolicy = '';	      # 'index' or 'noindex'?  Any other value will result in no change.

	function ParserOutput( $text = '', $languageLinks = array(), $categoryLinks = array(),
		$containsOldMagic = false, $titletext = '' )
	{
		$this->mText = $text;
		$this->mLanguageLinks = $languageLinks;
		$this->mCategories = $categoryLinks;
		$this->mContainsOldMagic = $containsOldMagic;
		$this->mTitleText = $titletext;
	}

	function getText()                   { return $this->mText; }
	function &getLanguageLinks()         { return $this->mLanguageLinks; }
	function getInterwikiLinks()         { return $this->mInterwikiLinks; }
	function getCategoryLinks()          { return array_keys( $this->mCategories ); }
	function &getCategories()            { return $this->mCategories; }
	function getCacheTime()              { return $this->mCacheTime; }
	function getTitleText()              { return $this->mTitleText; }
	function getSections()               { return $this->mSections; }
	function &getLinks()                 { return $this->mLinks; }
	function &getTemplates()             { return $this->mTemplates; }
	function &getImages()                { return $this->mImages; }
	function &getExternalLinks()         { return $this->mExternalLinks; }
	function getNoGallery()              { return $this->mNoGallery; }
	function getHeadItems()              { return $this->mHeadItems; }
	function getSubtitle()               { return $this->mSubtitle; }
	function getOutputHooks()            { return (array)$this->mOutputHooks; }
	function getWarnings()               { return array_keys( $this->mWarnings ); }
	function getIndexPolicy()            { return $this->mIndexPolicy; }
	function getTOCHTML()                { return $this->mTOCHTML; }

	function containsOldMagic()          { return $this->mContainsOldMagic; }
	function setText( $text )            { return wfSetVar( $this->mText, $text ); }
	function setLanguageLinks( $ll )     { return wfSetVar( $this->mLanguageLinks, $ll ); }
	function setCategoryLinks( $cl )     { return wfSetVar( $this->mCategories, $cl ); }
	function setContainsOldMagic( $com ) { return wfSetVar( $this->mContainsOldMagic, $com ); }

	/** setCacheTime() sets the timestamp expressing when the page has been rendered. 
	 * This doesn not control expiry, see updateCacheExpiry() for that!
	*/
	function setCacheTime( $t )          { return wfSetVar( $this->mCacheTime, $t ); } 
	function setTitleText( $t )          { return wfSetVar( $this->mTitleText, $t ); }
	function setSections( $toc )         { return wfSetVar( $this->mSections, $toc ); }
	function setIndexPolicy( $policy )   { return wfSetVar( $this->mIndexPolicy, $policy ); }
	function setTOCHTML( $tochtml )      { return wfSetVar( $this->mTOCHTML, $tochtml ); }


	/** Sets the number of seconds after which this object should expire.
	* This value is used with the ParserCache.
	* If called with a value greater than the value provided at any previous call, 
	* the new call has no effect. The value returned by getCacheExpiry is smaller
	* or equal to the smallest number that was provided as an argument to 
	* updateCacheExpiry().
	*/
	function updateCacheExpiry( $seconds ) { 
		$seconds = (int)$seconds;

		if ( $this->mCacheExpiry === null || $this->mCacheExpiry > $seconds ) 
			$this->mCacheExpiry = $seconds; 

		// hack: set old-style marker for uncacheable entries.
		if ( $this->mCacheExpiry !== null && $this->mCacheExpiry <= 0 ) 
			$this->mCacheTime = -1;
	}

	/** Returns the number of seconds after which this object should expire.
	* This method is used by ParserCache to determine how long the ParserOutput can be cached.
	* The timestamp of expiry can be calculated by adding getCacheExpiry() to getCacheTime().
	* The value returned by getCacheExpiry is smaller or equal to the smallest number 
	* that was provided to a call of updateCacheExpiry(), and smaller or equal to the
	* value of $wgParserCacheExpireTime.
	*/
	function getCacheExpiry() { 
		global $wgParserCacheExpireTime;

		if ( $this->mCacheTime < 0 ) return 0; // old-style marker for "not cachable"

		$expire = $this->mCacheExpiry; 

		if ( $expire === null ) 
			$expire = $wgParserCacheExpireTime;
		else
			$expire = min( $expire, $wgParserCacheExpireTime );

		if( $this->containsOldMagic() ) { //compatibility hack
			$expire = min( $expire, 3600 ); # 1 hour
		} 

		if ( $expire <= 0 ) return 0; // not cachable
		else return $expire;
	}

	function isCacheable() { 
		return $this->getCacheExpiry() > 0;
	}

	function addCategory( $c, $sort )    { $this->mCategories[$c] = $sort; }
	function addLanguageLink( $t )       { $this->mLanguageLinks[] = $t; }
	function addWarning( $s )            { $this->mWarnings[$s] = 1; }

	function addOutputHook( $hook, $data = false ) {
		$this->mOutputHooks[] = array( $hook, $data );
	}

	function setNewSection( $value ) {
		$this->mNewSection = (bool)$value;
	}
	function hideNewSection ( $value ) {
		$this->mHideNewSection = (bool)$value;
	}
	function getHideNewSection () {
		return (bool)$this->mHideNewSection;
	}
	function getNewSection() {
		return (bool)$this->mNewSection;
	}

	function addExternalLink( $url ) {
		# We don't register links pointing to our own server, unless... :-)
		global $wgServer, $wgRegisterInternalExternals;
		if( $wgRegisterInternalExternals or stripos($url,$wgServer.'/')!==0)
			$this->mExternalLinks[$url] = 1; 
	}

	/**
	 * Record a local or interwiki inline link for saving in future link tables.
	 *
	 * @param $title Title object
	 * @param $id Mixed: optional known page_id so we can skip the lookup
	 */
	function addLink( $title, $id = null ) {
		if ( $title->isExternal() ) {
			// Don't record interwikis in pagelinks
			$this->addInterwikiLink( $title );
			return;
		}
		$ns = $title->getNamespace();
		$dbk = $title->getDBkey();
		if ( $ns == NS_MEDIA ) {
			// Normalize this pseudo-alias if it makes it down here...
			$ns = NS_FILE;
		} elseif( $ns == NS_SPECIAL ) {
			// We don't record Special: links currently
			// It might actually be wise to, but we'd need to do some normalization.
			return;
		} elseif( $dbk === '' ) {
			// Don't record self links -  [[#Foo]]
			return;
		}
		if ( !isset( $this->mLinks[$ns] ) ) {
			$this->mLinks[$ns] = array();
		}
		if ( is_null( $id ) ) {
			$id = $title->getArticleID();
		}
		$this->mLinks[$ns][$dbk] = $id;
	}

	function addImage( $name ) {
		$this->mImages[$name] = 1;
	}

	function addTemplate( $title, $page_id, $rev_id ) {
		$ns = $title->getNamespace();
		$dbk = $title->getDBkey();
		if ( !isset( $this->mTemplates[$ns] ) ) {
			$this->mTemplates[$ns] = array();
		}
		$this->mTemplates[$ns][$dbk] = $page_id;
		if ( !isset( $this->mTemplateIds[$ns] ) ) {
			$this->mTemplateIds[$ns] = array();
		}
		$this->mTemplateIds[$ns][$dbk] = $rev_id; // For versioning
	}
	
	/**
	 * @param $title Title object, must be an interwiki link
	 * @throws MWException if given invalid input
	 */
	function addInterwikiLink( $title ) {
		$prefix = $title->getInterwiki();
		if( $prefix == '' ) {
			throw new MWException( 'Non-interwiki link passed, internal parser error.' );
		}
		if (!isset($this->mInterwikiLinks[$prefix])) {
			$this->mInterwikiLinks[$prefix] = array();
		}
		$this->mInterwikiLinks[$prefix][$title->getDBkey()] = 1;
	}

	/**
	 * Return true if this cached output object predates the global or
	 * per-article cache invalidation timestamps, or if it comes from
	 * an incompatible older version.
	 *
	 * @param $touched String: the affected article's last touched timestamp
	 * @return Boolean
	 */
	public function expired( $touched ) {
		global $wgCacheEpoch;
		return !$this->isCacheable() || // parser says it's uncacheable
		       $this->getCacheTime() < $touched ||
		       $this->getCacheTime() <= $wgCacheEpoch ||
		       $this->getCacheTime() < wfTimestamp( TS_MW, time() - $this->getCacheExpiry() ) || // expiry period has passed
		       !isset( $this->mVersion ) ||
		       version_compare( $this->mVersion, Parser::VERSION, "lt" );
	}

	/**
	 * Add some text to the <head>.
	 * If $tag is set, the section with that tag will only be included once
	 * in a given page.
	 */
	function addHeadItem( $section, $tag = false ) {
		if ( $tag !== false ) {
			$this->mHeadItems[$tag] = $section;
		} else {
			$this->mHeadItems[] = $section;
		}
	}

	/**
	 * Override the title to be used for display
	 * -- this is assumed to have been validated
	 * (check equal normalisation, etc.)
	 *
	 * @param $text String: desired title text
	 */
	public function setDisplayTitle( $text ) {
		$this->setTitleText( $text );
		$this->setProperty( 'displaytitle', $text );
	}

	/**
	 * Get the title to be used for display
	 *
	 * @return String
	 */
	public function getDisplayTitle() {
		$t = $this->getTitleText( );
		if( $t === '' ) {
			return false;
		}
		return $t;
	}

	/**
	 * Fairly generic flag setter thingy.
	 */
	public function setFlag( $flag ) {
		$this->mFlags[$flag] = true;
	}

	public function getFlag( $flag ) {
		return isset( $this->mFlags[$flag] );
	}

	/**
	 * Set a property to be cached in the DB
	 */
	public function setProperty( $name, $value ) {
		$this->mProperties[$name] = $value;
	}

	public function getProperty( $name ){
		return isset( $this->mProperties[$name] ) ? $this->mProperties[$name] : false;
	}

	public function getProperties() {
		if ( !isset( $this->mProperties ) ) {
			$this->mProperties = array();
		}
		return $this->mProperties;
	}
}
