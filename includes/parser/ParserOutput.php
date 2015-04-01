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
	public $mText,                       # The output text
		$mLanguageLinks,              # List of the full text of language links, in the order they appear
		$mCategories,                 # Map of category names to sort keys
		$mIndicators = array(),       # Page status indicators, usually displayed in top-right corner
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
		$mJsConfigVars = array(),     # JavaScript config variable for mw.config combined with this page
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
	private $mExtensionData = array(); # extra data used by extensions
	private $mLimitReportData = array(); # Parser limit report data
	private $mParseStartTime = array(); # Timestamps for getTimeSinceStart()
	private $mPreventClickjacking = false; # Whether to emit X-Frame-Options: DENY
	private $mFlags = array();        # Generic flags

	const EDITSECTION_REGEX =
		'#<(?:mw:)?editsection page="(.*?)" section="(.*?)"(?:/>|>(.*?)(</(?:mw:)?editsection>))#';

	public function __construct( $text = '', $languageLinks = array(), $categoryLinks = array(),
		$unused = false, $titletext = ''
	) {
		$this->mText = $text;
		$this->mLanguageLinks = $languageLinks;
		$this->mCategories = $categoryLinks;
		$this->mTitleText = $titletext;
	}

	public function getText() {
		$text = $this->mText;
		if ( $this->mEditSectionTokens ) {
			$text = preg_replace_callback(
				ParserOutput::EDITSECTION_REGEX,
				function ( $m ) {
					global $wgOut, $wgLang;
					$editsectionPage = Title::newFromText( htmlspecialchars_decode( $m[1] ) );
					$editsectionSection = htmlspecialchars_decode( $m[2] );
					$editsectionContent = isset( $m[4] ) ? $m[3] : null;

					if ( !is_object( $editsectionPage ) ) {
						throw new MWException( "Bad parser output text." );
					}

					$skin = $wgOut->getSkin();
					return call_user_func_array(
						array( $skin, 'doEditSectionLink' ),
						array( $editsectionPage, $editsectionSection,
							$editsectionContent, $wgLang->getCode() )
					);
				},
				$text
			);
		} else {
			$text = preg_replace( ParserOutput::EDITSECTION_REGEX, '', $text );
		}

		// If you have an old cached version of this class - sorry, you can't disable the TOC
		if ( isset( $this->mTOCEnabled ) && $this->mTOCEnabled ) {
			$text = str_replace( array( Parser::TOC_START, Parser::TOC_END ), '', $text );
		} else {
			$text = preg_replace(
				'#' . preg_quote( Parser::TOC_START ) . '.*?' . preg_quote( Parser::TOC_END ) . '#s',
				'',
				$text
			);
		}
		return $text;
	}

	public function &getLanguageLinks() {
		return $this->mLanguageLinks;
	}

	public function getInterwikiLinks() {
		return $this->mInterwikiLinks;
	}

	public function getCategoryLinks() {
		return array_keys( $this->mCategories );
	}

	public function &getCategories() {
		return $this->mCategories;
	}

	/**
	 * @since 1.25
	 */
	public function getIndicators() {
		return $this->mIndicators;
	}

	public function getTitleText() {
		return $this->mTitleText;
	}

	public function getSections() {
		return $this->mSections;
	}

	public function getEditSectionTokens() {
		return $this->mEditSectionTokens;
	}

	public function &getLinks() {
		return $this->mLinks;
	}

	public function &getTemplates() {
		return $this->mTemplates;
	}

	public function &getTemplateIds() {
		return $this->mTemplateIds;
	}

	public function &getImages() {
		return $this->mImages;
	}

	public function &getFileSearchOptions() {
		return $this->mFileSearchOptions;
	}

	public function &getExternalLinks() {
		return $this->mExternalLinks;
	}

	public function getNoGallery() {
		return $this->mNoGallery;
	}

	public function getHeadItems() {
		return $this->mHeadItems;
	}

	public function getModules() {
		return $this->mModules;
	}

	public function getModuleScripts() {
		return $this->mModuleScripts;
	}

	public function getModuleStyles() {
		return $this->mModuleStyles;
	}

	public function getModuleMessages() {
		return $this->mModuleMessages;
	}

	/** @since 1.23 */
	public function getJsConfigVars() {
		return $this->mJsConfigVars;
	}

	public function getOutputHooks() {
		return (array)$this->mOutputHooks;
	}

	public function getWarnings() {
		return array_keys( $this->mWarnings );
	}

	public function getIndexPolicy() {
		return $this->mIndexPolicy;
	}

	public function getTOCHTML() {
		return $this->mTOCHTML;
	}

	public function getTimestamp() {
		return $this->mTimestamp;
	}

	public function getLimitReportData() {
		return $this->mLimitReportData;
	}

	public function getTOCEnabled() {
		return $this->mTOCEnabled;
	}

	public function setText( $text ) {
		return wfSetVar( $this->mText, $text );
	}

	public function setLanguageLinks( $ll ) {
		return wfSetVar( $this->mLanguageLinks, $ll );
	}

	public function setCategoryLinks( $cl ) {
		return wfSetVar( $this->mCategories, $cl );
	}

	public function setTitleText( $t ) {
		return wfSetVar( $this->mTitleText, $t );
	}

	public function setSections( $toc ) {
		return wfSetVar( $this->mSections, $toc );
	}

	public function setEditSectionTokens( $t ) {
		return wfSetVar( $this->mEditSectionTokens, $t );
	}

	public function setIndexPolicy( $policy ) {
		return wfSetVar( $this->mIndexPolicy, $policy );
	}

	public function setTOCHTML( $tochtml ) {
		return wfSetVar( $this->mTOCHTML, $tochtml );
	}

	public function setTimestamp( $timestamp ) {
		return wfSetVar( $this->mTimestamp, $timestamp );
	}

	public function setTOCEnabled( $flag ) {
		return wfSetVar( $this->mTOCEnabled, $flag );
	}

	public function addCategory( $c, $sort ) {
		$this->mCategories[$c] = $sort;
	}

	/**
	 * @since 1.25
	 */
	public function setIndicator( $id, $content ) {
		$this->mIndicators[$id] = $content;
	}

	public function addLanguageLink( $t ) {
		$this->mLanguageLinks[] = $t;
	}

	public function addWarning( $s ) {
		$this->mWarnings[$s] = 1;
	}

	public function addOutputHook( $hook, $data = false ) {
		$this->mOutputHooks[] = array( $hook, $data );
	}

	public function setNewSection( $value ) {
		$this->mNewSection = (bool)$value;
	}
	public function hideNewSection( $value ) {
		$this->mHideNewSection = (bool)$value;
	}
	public function getHideNewSection() {
		return (bool)$this->mHideNewSection;
	}
	public function getNewSection() {
		return (bool)$this->mNewSection;
	}

	/**
	 * Checks, if a url is pointing to the own server
	 *
	 * @param string $internal The server to check against
	 * @param string $url The url to check
	 * @return bool
	 */
	public static function isLinkInternal( $internal, $url ) {
		return (bool)preg_match( '/^' .
			# If server is proto relative, check also for http/https links
			( substr( $internal, 0, 2 ) === '//' ? '(?:https?:)?' : '' ) .
			preg_quote( $internal, '/' ) .
			# check for query/path/anchor or end of link in each case
			'(?:[\?\/\#]|$)/i',
			$url
		);
	}

	public function addExternalLink( $url ) {
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
	 * @param Title $title
	 * @param int|null $id Optional known page_id so we can skip the lookup
	 */
	public function addLink( Title $title, $id = null ) {
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
	 * @param string $sha1 Base 36 SHA-1 of file (or false if non-existing)
	 * @return void
	 */
	public function addImage( $name, $timestamp = null, $sha1 = null ) {
		$this->mImages[$name] = 1;
		if ( $timestamp !== null && $sha1 !== null ) {
			$this->mFileSearchOptions[$name] = array( 'time' => $timestamp, 'sha1' => $sha1 );
		}
	}

	/**
	 * Register a template dependency for this output
	 * @param Title $title
	 * @param int $page_id
	 * @param int $rev_id
	 * @return void
	 */
	public function addTemplate( $title, $page_id, $rev_id ) {
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
	 * @param Title $title Title object, must be an interwiki link
	 * @throws MWException If given invalid input
	 */
	public function addInterwikiLink( $title ) {
		if ( !$title->isExternal() ) {
			throw new MWException( 'Non-interwiki link passed, internal parser error.' );
		}
		$prefix = $title->getInterwiki();
		if ( !isset( $this->mInterwikiLinks[$prefix] ) ) {
			$this->mInterwikiLinks[$prefix] = array();
		}
		$this->mInterwikiLinks[$prefix][$title->getDBkey()] = 1;
	}

	/**
	 * Add some text to the "<head>".
	 * If $tag is set, the section with that tag will only be included once
	 * in a given page.
	 * @param string $section
	 * @param string|bool $tag
	 */
	public function addHeadItem( $section, $tag = false ) {
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
	 * Add one or more variables to be set in mw.config in JavaScript.
	 *
	 * @param string|array $keys Key or array of key/value pairs.
	 * @param mixed $value [optional] Value of the configuration variable.
	 * @since 1.23
	 */
	public function addJsConfigVars( $keys, $value = null ) {
		if ( is_array( $keys ) ) {
			foreach ( $keys as $key => $value ) {
				$this->mJsConfigVars[$key] = $value;
			}
			return;
		}

		$this->mJsConfigVars[$keys] = $value;
	}

	/**
	 * Copy items from the OutputPage object into this one
	 *
	 * @param OutputPage $out
	 */
	public function addOutputPageMetadata( OutputPage $out ) {
		$this->addModules( $out->getModules() );
		$this->addModuleScripts( $out->getModuleScripts() );
		$this->addModuleStyles( $out->getModuleStyles() );
		$this->addModuleMessages( $out->getModuleMessages() );
		$this->addJsConfigVars( $out->getJsConfigVars() );

		$this->mHeadItems = array_merge( $this->mHeadItems, $out->getHeadItemsArray() );
		$this->mPreventClickjacking = $this->mPreventClickjacking || $out->getPreventClickjacking();
	}

	/**
	 * Add a tracking category, getting the title from a system message,
	 * or print a debug message if the title is invalid.
	 *
	 * Any message used with this function should be registered so it will
	 * show up on Special:TrackingCategories. Core messages should be added
	 * to SpecialTrackingCategories::$coreTrackingCategories, and extensions
	 * should add to "TrackingCategories" in their extension.json.
	 *
	 * @param string $msg Message key
	 * @param Title $title title of the page which is being tracked
	 * @return bool Whether the addition was successful
	 * @since 1.25
	 */
	public function addTrackingCategory( $msg, $title ) {
		if ( $title->getNamespace() === NS_SPECIAL ) {
			wfDebug( __METHOD__ . ": Not adding tracking category $msg to special page!\n" );
			return false;
		}

		// Important to parse with correct title (bug 31469)
		$cat = wfMessage( $msg )
			->title( $title )
			->inContentLanguage()
			->text();

		# Allow tracking categories to be disabled by setting them to "-"
		if ( $cat === '-' ) {
			return false;
		}

		$containerCategory = Title::makeTitleSafe( NS_CATEGORY, $cat );
		if ( $containerCategory ) {
			$this->addCategory( $containerCategory->getDBkey(), $this->getProperty( 'defaultsort' ) ?: '' );
			return true;
		} else {
			wfDebug( __METHOD__ . ": [[MediaWiki:$msg]] is not a valid title!\n" );
			return false;
		}
	}

	/**
	 * Override the title to be used for display
	 * -- this is assumed to have been validated
	 * (check equal normalisation, etc.)
	 *
	 * @param string $text Desired title text
	 */
	public function setDisplayTitle( $text ) {
		$this->setTitleText( $text );
		$this->setProperty( 'displaytitle', $text );
	}

	/**
	 * Get the title to be used for display
	 *
	 * @return string
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
	 * @param string $flag
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
	 * Since 1.23, page_props are also indexed by numeric value, to allow
	 * for efficient "top k" queries of pages wrt a given property.
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
	 * @note Do not use setProperty() to set a property which is only used
	 * in a context where the ParserOutput object itself is already available,
	 * for example a normal page view. There is no need to save such a property
	 * in the database since the text is already parsed. You can just hook
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

	/**
	 * @param string $name The property name to look up.
	 *
	 * @return mixed|bool The value previously set using setProperty(). False if null or no value
	 * was set for the given property name.
	 *
	 * @note You need to use getProperties() to check for boolean and null properties.
	 */
	public function getProperty( $name ) {
		return isset( $this->mProperties[$name] ) ? $this->mProperties[$name] : false;
	}

	public function unsetProperty( $name ) {
		unset( $this->mProperties[$name] );
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
	 * @return array
	 */
	public function getUsedOptions() {
		if ( !isset( $this->mAccessedOptions ) ) {
			return array();
		}
		return array_keys( $this->mAccessedOptions );
	}

	/**
	 * Tags a parser option for use in the cache key for this parser output.
	 * Registered as a watcher at ParserOptions::registerWatcher() by Parser::clearState().
	 *
	 * @see ParserCache::getKey
	 * @see ParserCache::save
	 * @see ParserOptions::addExtraKey
	 * @see ParserOptions::optionsHash
	 * @param string $option
	 */
	public function recordOption( $option ) {
		$this->mAccessedOptions[$option] = true;
	}

	/**
	 * @deprecated since 1.25. Instead, store any relevant data using setExtensionData,
	 *    and implement Content::getSecondaryDataUpdates() if possible, or use the
	 *    'SecondaryDataUpdates' hook to construct the necessary update objects.
	 *
	 * @note Hard deprecation and removal without long deprecation period, since there are no
	 *       known users, but known conceptual issues.
	 *
	 * @todo remove in 1.26
	 *
	 * @param DataUpdate $update
	 *
	 * @throws MWException
	 */
	public function addSecondaryDataUpdate( DataUpdate $update ) {
		wfDeprecated( __METHOD__, '1.25' );
		throw new MWException( 'ParserOutput::addSecondaryDataUpdate() is no longer supported. Override Content::getSecondaryDataUpdates() or use the SecondaryDataUpdates hook instead.' );
	}

	/**
	 * @deprecated since 1.25.
	 *
	 * @note Hard deprecation and removal without long deprecation period, since there are no
	 *       known users, but known conceptual issues.
	 *
	 * @todo remove in 1.26
	 *
	 * @return bool false (since 1.25)
	 */
	public function hasCustomDataUpdates() {
		wfDeprecated( __METHOD__, '1.25' );
		return false;
	}

	/**
	 * @deprecated since 1.25. Instead, store any relevant data using setExtensionData,
	 *    and implement Content::getSecondaryDataUpdates() if possible, or use the
	 *    'SecondaryDataUpdates' hook to construct the necessary update objects.
	 *
	 * @note Hard deprecation and removal without long deprecation period, since there are no
	 *       known users, but known conceptual issues.
	 *
	 * @todo remove in 1.26
	 *
	 * @param Title $title
	 * @param bool $recursive
	 *
	 * @return array An array of instances of DataUpdate
	 */
	public function getSecondaryDataUpdates( Title $title = null, $recursive = true ) {
		wfDeprecated( __METHOD__, '1.25' );
		return array();
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
	 *   conflicts in naming keys. It is suggested to use the extension's name as a prefix.
	 *
	 * @param mixed $value The value to set. Setting a value to null is equivalent to removing
	 *   the value.
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
	 * @return mixed|null The value previously set for the given key using setExtensionData()
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
		if ( !$clock || $clock === 'cpu' ) {
			$ru = wfGetRusage();
			if ( $ru ) {
				$ret['cpu'] = $ru['ru_utime.tv_sec'] + $ru['ru_utime.tv_usec'] / 1e6;
				$ret['cpu'] += $ru['ru_stime.tv_sec'] + $ru['ru_stime.tv_usec'] / 1e6;
			}
		}
		return $ret;
	}

	/**
	 * Resets the parse start timestamps for future calls to getTimeSinceStart()
	 * @since 1.22
	 */
	public function resetParseStartTime() {
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
	public function getTimeSinceStart( $clock ) {
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
	public function setLimitReportData( $key, $value ) {
		$this->mLimitReportData[$key] = $value;
	}

	/**
	 * Check whether the cache TTL was lowered due to dynamic content
	 *
	 * When content is determined by more than hard state (e.g. page edits),
	 * such as template/file transclusions based on the current timestamp or
	 * extension tags that generate lists based on queries, this return true.
	 *
	 * @return bool
	 * @since 1.25
	 */
	public function hasDynamicContent() {
		global $wgParserCacheExpireTime;

		return $this->getCacheExpiry() < $wgParserCacheExpireTime;
	}

	/**
	 * Get or set the prevent-clickjacking flag
	 *
	 * @since 1.24
	 * @param bool|null $flag New flag value, or null to leave it unchanged
	 * @return bool Old flag value
	 */
	public function preventClickjacking( $flag = null ) {
		return wfSetVar( $this->mPreventClickjacking, $flag );
	}

	/**
	 * Save space for serialization by removing useless values
	 * @return array
	 */
	public function __sleep() {
		return array_diff(
			array_keys( get_object_vars( $this ) ),
			array( 'mParseStartTime' )
		);
	}
}
