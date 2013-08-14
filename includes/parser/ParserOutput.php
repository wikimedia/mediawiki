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
		$mTimestamp,                  # Timestamp of the revision
		$mTOCEnabled = true;          # Whether TOC should be shown, can't override __NOTOC__
		private $mIndexPolicy = '';       # 'index' or 'noindex'?  Any other value will result in no change.
		private $mAccessedOptions = array(); # List of ParserOptions (stored in the keys)
		private $mSecondaryDataUpdates = array(); # List of DataUpdate, used to save info from the page somewhere else.
		private $mExtensionData = array(); # extra data used by extensions
		private $mLimitReportData = array(); # Parser limit report data
		private $mParseStartTime = array(); # Timestamps for getTimeSinceStart()

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
		wfProfileIn( __METHOD__ );
		$text = $this->mText;
		if ( $this->mEditSectionTokens ) {
			$text = preg_replace_callback( ParserOutput::EDITSECTION_REGEX,
				array( &$this, 'replaceEditSectionLinksCallback' ), $text );
		} else {
			$text = preg_replace( ParserOutput::EDITSECTION_REGEX, '', $text );
		}

		// If you have an old cached version of this class - sorry, you can't disable the TOC
		if ( isset( $this->mTOCEnabled ) && $this->mTOCEnabled ) {
			$text = str_replace( array( Parser::TOC_START, Parser::TOC_END ), '', $text );
		} else {
			$text = preg_replace(
				'#'. preg_quote( Parser::TOC_START ) . '.*?' . preg_quote( Parser::TOC_END ) . '#s',
				'',
				$text
			);
		}
		wfProfileOut( __METHOD__ );
		return $text;
	}

	/**
	 * callback used by getText to replace editsection tokens
	 * @private
	 * @param $m
	 * @throws MWException
	 * @return mixed
	 */
	function replaceEditSectionLinksCallback( $m ) {
		global $wgOut, $wgLang;
		$args = array(
			htmlspecialchars_decode( $m[1] ),
			htmlspecialchars_decode( $m[2] ),
			isset( $m[4] ) ? $m[3] : null,
		);
		$args[0] = Title::newFromText( $args[0] );
		if ( !is_object( $args[0] ) ) {
			throw new MWException( "Bad parser output text." );
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
	function getLimitReportData()        { return $this->mLimitReportData; }
	function getTOCEnabled()             { return $this->mTOCEnabled; }

	function setText( $text )            { return wfSetVar( $this->mText, $text ); }
	function setLanguageLinks( $ll )     { return wfSetVar( $this->mLanguageLinks, $ll ); }
	function setCategoryLinks( $cl )     { return wfSetVar( $this->mCategories, $cl ); }

	function setTitleText( $t )          { return wfSetVar( $this->mTitleText, $t ); }
	function setSections( $toc )         { return wfSetVar( $this->mSections, $toc ); }
	function setEditSectionTokens( $t )  { return wfSetVar( $this->mEditSectionTokens, $t ); }
	function setIndexPolicy( $policy )   { return wfSetVar( $this->mIndexPolicy, $policy ); }
	function setTOCHTML( $tochtml )      { return wfSetVar( $this->mTOCHTML, $tochtml ); }
	function setTimestamp( $timestamp )  { return wfSetVar( $this->mTimestamp, $timestamp ); }
	function setTOCEnabled( $flag )      { return wfSetVar( $this->mTOCEnabled, $flag ); }

	function addCategory( $c, $sort )    { $this->mCategories[$c] = $sort; }
	function addLanguageLink( $t )       { $this->mLanguageLinks[] = $t; }
	function addWarning( $s )            { $this->mWarnings[$s] = 1; }

	function addOutputHook( $hook, $data = false ) {
		$this->mOutputHooks[] = array( $hook, $data );
	}

	function setNewSection( $value ) {
		$this->mNewSection = (bool)$value;
	}
	function hideNewSection( $value ) {
		$this->mHideNewSection = (bool)$value;
	}
	function getHideNewSection() {
		return (bool)$this->mHideNewSection;
	}
	function getNewSection() {
		return (bool)$this->mNewSection;
	}

	/**
	 * Checks, if a url is pointing to the own server
	 *
	 * @param string $internal the server to check against
	 * @param string $url the url to check
	 * @return bool
	 */
	static function isLinkInternal( $internal, $url ) {
		return (bool)preg_match( '/^' .
			# If server is proto relative, check also for http/https links
			( substr( $internal, 0, 2 ) === '//' ? '(?:https?:)?' : '' ) .
			preg_quote( $internal, '/' ) .
			# check for query/path/anchor or end of link in each case
			'(?:[\?\/\#]|$)/i',
			$url
		);
	}

	function addExternalLink( $url ) {
		# We don't register links pointing to our own server, unless... :-)
		global $wgServer, $wgRegisterInternalExternals;

		$registerExternalLink = true;
		if ( !$wgRegisterInternalExternals ) {
			$registerExternalLink = !self::isLinkInternal( $wgServer, $url );
		}
		if ( $registerExternalLink ) {
			$this->mExternalLinks[$url] = 1;
		}
	}

	/**
	 * Record a local or interwiki inline link for saving in future link tables.
	 *
	 * @param $title Title object
	 * @param $id Mixed: optional known page_id so we can skip the lookup
	 */
	function addLink( Title $title, $id = null ) {
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
		} elseif ( $ns == NS_SPECIAL ) {
			// We don't record Special: links currently
			// It might actually be wise to, but we'd need to do some normalization.
			return;
		} elseif ( $dbk === '' ) {
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
	 * @param string $name Title dbKey
	 * @param string $timestamp MW timestamp of file creation (or false if non-existing)
	 * @param string $sha1 base 36 SHA-1 of file (or false if non-existing)
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
		if ( $prefix == '' ) {
			throw new MWException( 'Non-interwiki link passed, internal parser error.' );
		}
		if ( !isset( $this->mInterwikiLinks[$prefix] ) ) {
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
		$this->mModules = array_merge( $this->mModules, (array)$modules );
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
	 * @param string $text desired title text
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
		if ( $t === '' ) {
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
	 * Set a property to be stored in the page_props database table.
	 *
	 * page_props is a key value store indexed by the page ID. This allows
	 * the parser to set a property on a page which can then be quickly
	 * retrieved given the page ID or via a DB join when given the page
	 * title.
	 *
	 * setProperty() is thus used to propagate properties from the parsed
	 * page to request contexts other than a page view of the currently parsed
	 * article.
	 *
	 * Some applications examples:
	 *
	 *   * To implement hidden categories, hiding pages from category listings
	 *     by storing a property.
	 *
	 *   * Overriding the displayed article title.
	 *   @see ParserOutput::setDisplayTitle()
	 *
	 *   * To implement image tagging, for example displaying an icon on an
	 *     image thumbnail to indicate that it is listed for deletion on
	 *     Wikimedia Commons.
	 *     This is not actually implemented, yet but would be pretty cool.
	 *
	 * @note: Do not use setProperty() to set a property which is only used
	 * in a context where the ParserOutput object itself is already available,
	 * for example a normal page view. There is no need to save such a property
	 * in the database since it the text is already parsed. You can just hook
	 * OutputPageParserOutput and get your data out of the ParserOutput object.
	 *
	 * If you are writing an extension where you want to set a property in the
	 * parser which is used by an OutputPageParserOutput hook, you have to
	 * associate the extension data directly with the ParserOutput object.
	 * Since MediaWiki 1.21, you can use setExtensionData() to do this:
	 *
	 * @par Example:
	 * @code
	 *    $parser->getOutput()->setExtensionData( 'my_ext_foo', '...' );
	 * @endcode
	 *
	 * And then later, in OutputPageParserOutput or similar:
	 *
	 * @par Example:
	 * @code
	 *    $output->getExtensionData( 'my_ext_foo' );
	 * @endcode
	 *
	 * In MediaWiki 1.20 and older, you have to use a custom member variable
	 * within the ParserOutput object:
	 *
	 * @par Example:
	 * @code
	 *    $parser->getOutput()->my_ext_foo = '...';
	 * @endcode
	 *
	 */
	public function setProperty( $name, $value ) {
		$this->mProperties[$name] = $value;
	}

	public function getProperty( $name ) {
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
	 * @note: Avoid using this method directly, use ContentHandler::getSecondaryDataUpdates() instead! The content
	 *        handler may provide additional update objects.
	 *
	 * @since 1.20
	 *
	 * @param $title Title The title of the page we're updating. If not given, a title object will be created
	 *                      based on $this->getTitleText()
	 * @param $recursive Boolean: queue jobs for recursive updates?
	 *
	 * @return Array. An array of instances of DataUpdate
	 */
	public function getSecondaryDataUpdates( Title $title = null, $recursive = true ) {
		if ( is_null( $title ) ) {
			$title = Title::newFromText( $this->getTitleText() );
		}

		$linksUpdate = new LinksUpdate( $title, $this, $recursive );

		return array_merge( $this->mSecondaryDataUpdates, array( $linksUpdate ) );
	}

	/**
	 * Attaches arbitrary data to this ParserObject. This can be used to store some information in
	 * the ParserOutput object for later use during page output. The data will be cached along with
	 * the ParserOutput object, but unlike data set using setProperty(), it is not recorded in the
	 * database.
	 *
	 * This method is provided to overcome the unsafe practice of attaching extra information to a
	 * ParserObject by directly assigning member variables.
	 *
	 * To use setExtensionData() to pass extension information from a hook inside the parser to a
	 * hook in the page output, use this in the parser hook:
	 *
	 * @par Example:
	 * @code
	 *    $parser->getOutput()->setExtensionData( 'my_ext_foo', '...' );
	 * @endcode
	 *
	 * And then later, in OutputPageParserOutput or similar:
	 *
	 * @par Example:
	 * @code
	 *    $output->getExtensionData( 'my_ext_foo' );
	 * @endcode
	 *
	 * In MediaWiki 1.20 and older, you have to use a custom member variable
	 * within the ParserOutput object:
	 *
	 * @par Example:
	 * @code
	 *    $parser->getOutput()->my_ext_foo = '...';
	 * @endcode
	 *
	 * @since 1.21
	 *
	 * @param string $key The key for accessing the data. Extensions should take care to avoid
	 *               conflicts in naming keys. It is suggested to use the extension's name as a
	 *               prefix.
	 *
	 * @param mixed $value The value to set. Setting a value to null is equivalent to removing
	 *              the value.
	 */
	public function setExtensionData( $key, $value ) {
		if ( $value === null ) {
			unset( $this->mExtensionData[$key] );
		} else {
			$this->mExtensionData[$key] = $value;
		}
	}

	/**
	 * Gets extensions data previously attached to this ParserOutput using setExtensionData().
	 * Typically, such data would be set while parsing the page, e.g. by a parser function.
	 *
	 * @since 1.21
	 *
	 * @param string $key The key to look up.
	 *
	 * @return mixed The value previously set for the given key using setExtensionData( $key ),
	 *         or null if no value was set for this key.
	 */
	public function getExtensionData( $key ) {
		if ( isset( $this->mExtensionData[$key] ) ) {
			return $this->mExtensionData[$key];
		}

		return null;
	}

	private static function getTimes( $clock = null ) {
		$ret = array();
		if ( !$clock || $clock === 'wall' ) {
			$ret['wall'] = microtime( true );
		}
		if ( ( !$clock || $clock === 'cpu' ) && function_exists( 'getrusage' ) ) {
			$ru = getrusage();
			$ret['cpu'] = $ru['ru_utime.tv_sec'] + $ru['ru_utime.tv_usec'] / 1e6;
			$ret['cpu'] += $ru['ru_stime.tv_sec'] + $ru['ru_stime.tv_usec'] / 1e6;
		}
		return $ret;
	}

	/**
	 * Resets the parse start timestamps for future calls to getTimeSinceStart()
	 * @since 1.22
	 */
	function resetParseStartTime() {
		$this->mParseStartTime = self::getTimes();
	}

	/**
	 * Returns the time since resetParseStartTime() was last called
	 *
	 * Clocks available are:
	 *  - wall: Wall clock time
	 *  - cpu: CPU time (requires getrusage)
	 *
	 * @since 1.22
	 * @param string $clock
	 * @return float|null
	 */
	function getTimeSinceStart( $clock ) {
		if ( !isset( $this->mParseStartTime[$clock] ) ) {
			return null;
		}

		$end = self::getTimes( $clock );
		return $end[$clock] - $this->mParseStartTime[$clock];
	}

	/**
	 * Sets parser limit report data for a key
	 *
	 * The key is used as the prefix for various messages used for formatting:
	 *  - $key: The label for the field in the limit report
	 *  - $key-value-text: Message used to format the value in the "NewPP limit
	 *      report" HTML comment. If missing, uses $key-format.
	 *  - $key-value-html: Message used to format the value in the preview
	 *      limit report table. If missing, uses $key-format.
	 *  - $key-value: Message used to format the value. If missing, uses "$1".
	 *
	 * Note that all values are interpreted as wikitext, and so should be
	 * encoded with htmlspecialchars() as necessary, but should avoid complex
	 * HTML for sanity of display in the "NewPP limit report" comment.
	 *
	 * @since 1.22
	 * @param string $key Message key
	 * @param mixed $value Appropriate for Message::params()
	 */
	function setLimitReportData( $key, $value ) {
		$this->mLimitReportData[$key] = $value;
	}
}
