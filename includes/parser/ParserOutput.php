<?php

/**
 * Output of the PHP parser.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Parser
 */
class ParserOutput extends CacheTime {
	var $mText,                       # The output text
		$mLanguageLinks,              # List of the full text of language links, in the order they appear
		$mCategories,                 # Map of category names to sort keys
		$mTitleText,                  # title text of the chosen language variant
		$mLinks = array(),            # 2-D map of NS/DBK to ID for the links in the document. ID=zero for broken.
		$mTemplates = array(),        # 2-D map of NS/DBK to ID for the template references. ID=zero for broken.
		$mTemplateIds = array(),      # 2-D map of NS/DBK to rev ID for the template references. ID=zero for broken.
		$mImages = array(),           # DB keys of the images used, in the array key only
		$mFileSearchOptions = array(), # DB keys of the images used mapped to sha1 and MW timestamp
		$mExternalLinks = array(),    # External link URLs, in the key only
		$mInterwikiLinks = array(),   # 2-D map of prefix/DBK (in keys only) for the inline interwiki links in the document.
		$mNewSection = false,         # Show a new section link?
		$mHideNewSection = false,     # Hide the new section link?
		$mNoGallery = false,          # No gallery on category page? (__NOGALLERY__)
		$mHeadItems = array(),        # Items to put in the <head> section
		$mModules = array(),          # Modules to be loaded by the resource loader
		$mModuleScripts = array(),    # Modules of which only the JS will be loaded by the resource loader
		$mModuleStyles = array(),     # Modules of which only the CSSS will be loaded by the resource loader
		$mModuleMessages = array(),   # Modules of which only the messages will be loaded by the resource loader
		$mOutputHooks = array(),      # Hook tags as per $wgParserOutputHooks
		$mWarnings = array(),         # Warning text to be returned to the user. Wikitext formatted, in the key only
		$mSections = array(),         # Table of contents
		$mEditSectionTokens = false,  # prefix/suffix markers if edit sections were output as tokens
		$mProperties = array(),       # Name/value pairs to be cached in the DB
		$mTOCHTML = '',               # HTML of the TOC
		$mTimestamp;                  # Timestamp of the revision
		private $mIndexPolicy = '';       # 'index' or 'noindex'?  Any other value will result in no change.
		private $mAccessedOptions = array(); # List of ParserOptions (stored in the keys)
		private $mSecondaryDataUpdates = array(); # List of instances of SecondaryDataObject(), used to cause some information extracted from the page in a custom place.

	const EDITSECTION_REGEX = '#<(?:mw:)?editsection page="(.*?)" section="(.*?)"(?:/>|>(.*?)(</(?:mw:)?editsection>))#';

	function __construct( $text = '', $languageLinks = array(), $categoryLinks = array(),
		$containsOldMagic = false, $titletext = '' )
	{
		$this->mText = $text;
		$this->mLanguageLinks = $languageLinks;
		$this->mCategories = $categoryLinks;
		$this->mContainsOldMagic = $containsOldMagic;
		$this->mTitleText = $titletext;
	}

	function getText() {
		if ( $this->mEditSectionTokens ) {
			return preg_replace_callback( ParserOutput::EDITSECTION_REGEX,
				array( &$this, 'replaceEditSectionLinksCallback' ), $this->mText );
		}
		return preg_replace( ParserOutput::EDITSECTION_REGEX, '', $this->mText );
	}

	/**
	 * callback used by getText to replace editsection tokens
	 * @private
	 * @return mixed
	 */
	function replaceEditSectionLinksCallback( $m ) {
		global $wgOut, $wgLang;
		$args = array(
			htmlspecialchars_decode($m[1]),
			htmlspecialchars_decode($m[2]),
			isset($m[4]) ? $m[3] : null,
		);
		$args[0] = Title::newFromText( $args[0] );
		if ( !is_object($args[0]) ) {
			throw new MWException("Bad parser output text.");
		}
		$args[] = $wgLang->getCode();
		$skin = $wgOut->getSkin();
		return call_user_func_array( array( $skin, 'doEditSectionLink' ), $args );
	}

	function &getLanguageLinks()         { return $this->mLanguageLinks; }
	function getInterwikiLinks()         { return $this->mInterwikiLinks; }
	function getCategoryLinks()          { return array_keys( $this->mCategories ); }
	function &getCategories()            { return $this->mCategories; }
	function getTitleText()              { return $this->mTitleText; }
	function getSections()               { return $this->mSections; }
	function getEditSectionTokens()      { return $this->mEditSectionTokens; }
	function &getLinks()                 { return $this->mLinks; }
	function &getTemplates()             { return $this->mTemplates; }
	function &getTemplateIds()           { return $this->mTemplateIds; }
	function &getImages()                { return $this->mImages; }
	function &getFileSearchOptions()     { return $this->mFileSearchOptions; }
	function &getExternalLinks()         { return $this->mExternalLinks; }
	function getNoGallery()              { return $this->mNoGallery; }
	function getHeadItems()              { return $this->mHeadItems; }
	function getModules()                { return $this->mModules; }
	function getModuleScripts()          { return $this->mModuleScripts; }
	function getModuleStyles()           { return $this->mModuleStyles; }
	function getModuleMessages()         { return $this->mModuleMessages; }
	function getOutputHooks()            { return (array)$this->mOutputHooks; }
	function getWarnings()               { return array_keys( $this->mWarnings ); }
	function getIndexPolicy()            { return $this->mIndexPolicy; }
	function getTOCHTML()                { return $this->mTOCHTML; }
	function getTimestamp()              { return $this->mTimestamp; }

	function setText( $text )            { return wfSetVar( $this->mText, $text ); }
	function setLanguageLinks( $ll )     { return wfSetVar( $this->mLanguageLinks, $ll ); }
	function setCategoryLinks( $cl )     { return wfSetVar( $this->mCategories, $cl ); }

	function setTitleText( $t )          { return wfSetVar( $this->mTitleText, $t ); }
	function setSections( $toc )         { return wfSetVar( $this->mSections, $toc ); }
	function setEditSectionTokens( $t )  { return wfSetVar( $this->mEditSectionTokens, $t ); }
	function setIndexPolicy( $policy )   { return wfSetVar( $this->mIndexPolicy, $policy ); }
	function setTOCHTML( $tochtml )      { return wfSetVar( $this->mTOCHTML, $tochtml ); }
	function setTimestamp( $timestamp )  { return wfSetVar( $this->mTimestamp, $timestamp ); }

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

	/**
	 * Register a file dependency for this output
	 * @param $name string Title dbKey
	 * @param $timestamp string MW timestamp of file creation (or false if non-existing)
	 * @param $sha1 string base 36 SHA-1 of file (or false if non-existing)
	 * @return void
	 */
	function addImage( $name, $timestamp = null, $sha1 = null ) {
		$this->mImages[$name] = 1;
		if ( $timestamp !== null && $sha1 !== null ) {
			$this->mFileSearchOptions[$name] = array( 'time' => $timestamp, 'sha1' => $sha1 );
		}
	}

	/**
	 * Register a template dependency for this output
	 * @param $title Title
	 * @param $page_id
	 * @param $rev_id
	 * @return void
	 */
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
	 * Add some text to the "<head>".
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

	public function addModules( $modules ) {
		$this->mModules = array_merge( $this->mModules, (array) $modules );
	}

	public function addModuleScripts( $modules ) {
		$this->mModuleScripts = array_merge( $this->mModuleScripts, (array)$modules );
	}

	public function addModuleStyles( $modules ) {
		$this->mModuleStyles = array_merge( $this->mModuleStyles, (array)$modules );
	}

	public function addModuleMessages( $modules ) {
		$this->mModuleMessages = array_merge( $this->mModuleMessages, (array)$modules );
	}

	/**
	 * Copy items from the OutputPage object into this one
	 *
	 * @param $out OutputPage object
	 */
	public function addOutputPageMetadata( OutputPage $out ) {
		$this->addModules( $out->getModules() );
		$this->addModuleScripts( $out->getModuleScripts() );
		$this->addModuleStyles( $out->getModuleStyles() );
		$this->addModuleMessages( $out->getModuleMessages() );

		$this->mHeadItems = array_merge( $this->mHeadItems, $out->getHeadItemsArray() );
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
		$t = $this->getTitleText();
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


	/**
	 * Returns the options from its ParserOptions which have been taken
	 * into account to produce this output or false if not available.
	 * @return mixed Array
	 */
	 public function getUsedOptions() {
		if ( !isset( $this->mAccessedOptions ) ) {
			return array();
		}
		return array_keys( $this->mAccessedOptions );
	 }

	 /**
	  * Callback passed by the Parser to the ParserOptions to keep track of which options are used.
	  * @access private
	  */
	 function recordOption( $option ) {
		 $this->mAccessedOptions[$option] = true;
	 }

	/**
	 * Adds an update job to the output. Any update jobs added to the output will eventually bexecuted in order to
	 * store any secondary information extracted from the page's content.
	 *
	 * @since 1.20
	 *
	 * @param DataUpdate $update
	 */
	public function addSecondaryDataUpdate( DataUpdate $update ) {
		$this->mSecondaryDataUpdates[] = $update;
	}

	/**
	 * Returns any DataUpdate jobs to be executed in order to store secondary information
	 * extracted from the page's content, including a LinksUpdate object for all links stored in
	 * this ParserOutput object.
	 *
	 * @since 1.20
	 *
	 * @param $title Title of the page we're updating. If not given, a title object will be created based on $this->getTitleText()
	 * @param $recursive Boolean: queue jobs for recursive updates?
	 *
	 * @return Array. An array of instances of DataUpdate
	 */
	public function getSecondaryDataUpdates( Title $title = null, $recursive = true ) {
		if ( is_null( $title ) ) {
			$title = Title::newFromText( $this->getTitleText() );
		}

		$linksUpdate = new LinksUpdate( $title, $this, $recursive );

		if ( $this->mSecondaryDataUpdates === array() ) {
			return array( $linksUpdate );
		} else {
			$updates = array_merge( $this->mSecondaryDataUpdates, array( $linksUpdate ) );
		}

		return $updates;
	 }

}
