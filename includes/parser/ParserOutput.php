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
	/**
	 * Feature flags to indicate to extensions that MediaWiki core supports and
	 * uses getText() stateless transforms.
	 */
	const SUPPORTS_STATELESS_TRANSFORMS = 1;
	const SUPPORTS_UNWRAP_TRANSFORM = 1;

	/**
	 * @var string|null $mText The output text
	 */
	public $mText = null;

	/**
	 * @var array $mLanguageLinks List of the full text of language links,
	 *  in the order they appear.
	 */
	public $mLanguageLinks;

	/**
	 * @var array $mCategoriesMap of category names to sort keys
	 */
	public $mCategories;

	/**
	 * @var array $mIndicators Page status indicators, usually displayed in top-right corner.
	 */
	public $mIndicators = [];

	/**
	 * @var string $mTitleText Title text of the chosen language variant, as HTML.
	 */
	public $mTitleText;

	/**
	 * @var array $mLinks 2-D map of NS/DBK to ID for the links in the document.
	 *  ID=zero for broken.
	 */
	public $mLinks = [];

	/**
	 * @var array $mTemplates 2-D map of NS/DBK to ID for the template references.
	 *  ID=zero for broken.
	 */
	public $mTemplates = [];

	/**
	 * @var array $mTemplateIds 2-D map of NS/DBK to rev ID for the template references.
	 *  ID=zero for broken.
	 */
	public $mTemplateIds = [];

	/**
	 * @var array $mImages DB keys of the images used, in the array key only
	 */
	public $mImages = [];

	/**
	 * @var array $mFileSearchOptions DB keys of the images used mapped to sha1 and MW timestamp.
	 */
	public $mFileSearchOptions = [];

	/**
	 * @var array $mExternalLinks External link URLs, in the key only.
	 */
	public $mExternalLinks = [];

	/**
	 * @var array $mInterwikiLinks 2-D map of prefix/DBK (in keys only)
	 *  for the inline interwiki links in the document.
	 */
	public $mInterwikiLinks = [];

	/**
	 * @var bool $mNewSection Show a new section link?
	 */
	public $mNewSection = false;

	/**
	 * @var bool $mHideNewSection Hide the new section link?
	 */
	public $mHideNewSection = false;

	/**
	 * @var bool $mNoGallery No gallery on category page? (__NOGALLERY__).
	 */
	public $mNoGallery = false;

	/**
	 * @var array $mHeadItems Items to put in the <head> section
	 */
	public $mHeadItems = [];

	/**
	 * @var array $mModules Modules to be loaded by ResourceLoader
	 */
	public $mModules = [];

	/**
	 * @var array $mModuleScripts Modules of which only the JS will be loaded by ResourceLoader.
	 */
	public $mModuleScripts = [];

	/**
	 * @var array $mModuleStyles Modules of which only the CSSS will be loaded by ResourceLoader.
	 */
	public $mModuleStyles = [];

	/**
	 * @var array $mJsConfigVars JavaScript config variable for mw.config combined with this page.
	 */
	public $mJsConfigVars = [];

	/**
	 * @var array $mOutputHooks Hook tags as per $wgParserOutputHooks.
	 */
	public $mOutputHooks = [];

	/**
	 * @var array $mWarnings Warning text to be returned to the user.
	 *  Wikitext formatted, in the key only.
	 */
	public $mWarnings = [];

	/**
	 * @var array $mSections Table of contents
	 */
	public $mSections = [];

	/**
	 * @var array $mProperties Name/value pairs to be cached in the DB.
	 */
	public $mProperties = [];

	/**
	 * @var string $mTOCHTML HTML of the TOC.
	 */
	public $mTOCHTML = '';

	/**
	 * @var string $mTimestamp Timestamp of the revision.
	 */
	public $mTimestamp;

	/**
	 * @var bool $mEnableOOUI Whether OOUI should be enabled.
	 */
	public $mEnableOOUI = false;

	/**
	 * @var string $mIndexPolicy 'index' or 'noindex'?  Any other value will result in no change.
	 */
	private $mIndexPolicy = '';

	/**
	 * @var true[] $mAccessedOptions List of ParserOptions (stored in the keys).
	 */
	private $mAccessedOptions = [];

	/**
	 * @var array $mExtensionData extra data used by extensions.
	 */
	private $mExtensionData = [];

	/**
	 * @var array $mLimitReportData Parser limit report data.
	 */
	private $mLimitReportData = [];

	/** @var array Parser limit report data for JSON */
	private $mLimitReportJSData = [];

	/**
	 * @var array $mParseStartTime Timestamps for getTimeSinceStart().
	 */
	private $mParseStartTime = [];

	/**
	 * @var bool $mPreventClickjacking Whether to emit X-Frame-Options: DENY.
	 */
	private $mPreventClickjacking = false;

	/**
	 * @var array $mFlags Generic flags.
	 */
	private $mFlags = [];

	/** @var int|null Assumed rev ID for {{REVISIONID}} if no revision is set */
	private $mSpeculativeRevId;

	/** string CSS classes to use for the wrapping div, stored in the array keys.
	 * If no class is given, no wrapper is added.
	 */
	private $mWrapperDivClasses = [];

	/** @var int Upper bound of expiry based on parse duration */
	private $mMaxAdaptiveExpiry = INF;

	const EDITSECTION_REGEX =
		'#<(?:mw:)?editsection page="(.*?)" section="(.*?)"(?:/>|>(.*?)(</(?:mw:)?editsection>))#s';

	// finalizeAdaptiveCacheExpiry() uses TTL = MAX( m * PARSE_TIME + b, MIN_AR_TTL)
	// Current values imply that m=3933.333333 and b=-333.333333
	// See https://www.nngroup.com/articles/website-response-times/
	const PARSE_FAST_SEC = 0.100; // perceived "fast" page parse
	const PARSE_SLOW_SEC = 1.0; // perceived "slow" page parse
	const FAST_AR_TTL = 60; // adaptive TTL for "fast" pages
	const SLOW_AR_TTL = 3600; // adaptive TTL for "slow" pages
	const MIN_AR_TTL = 15; // min adaptive TTL (for sanity, pool counter, and edit stashing)

	/**
	 * @param string|null $text HTML. Use null to indicate that this ParserOutput contains only
	 *        meta-data, and the HTML output is undetermined, as opposed to empty. Passing null
	 *        here causes hasText() to return false.
	 * @param array $languageLinks
	 * @param array $categoryLinks
	 * @param bool $unused
	 * @param string $titletext
	 */
	public function __construct( $text = '', $languageLinks = [], $categoryLinks = [],
		$unused = false, $titletext = ''
	) {
		$this->mText = $text;
		$this->mLanguageLinks = $languageLinks;
		$this->mCategories = $categoryLinks;
		$this->mTitleText = $titletext;
	}

	/**
	 * Returns true if text was passed to the constructor, or set using setText(). Returns false
	 * if null was passed to the $text parameter of the constructor to indicate that this
	 * ParserOutput only contains meta-data, and the HTML output is undetermined.
	 *
	 * @since 1.32
	 *
	 * @return bool Whether this ParserOutput contains rendered text. If this returns false, the
	 *         ParserOutput contains meta-data only.
	 */
	public function hasText() {
		return ( $this->mText !== null );
	}

	/**
	 * Get the cacheable text with <mw:editsection> markers still in it. The
	 * return value is suitable for writing back via setText() but is not valid
	 * for display to the user.
	 *
	 * @return string
	 * @since 1.27
	 */
	public function getRawText() {
		if ( $this->mText === null ) {
			throw new LogicException( 'This ParserOutput contains no text!' );
		}

		return $this->mText;
	}

	/**
	 * Get the output HTML
	 *
	 * @param array $options (since 1.31) Transformations to apply to the HTML
	 *  - allowTOC: (bool) Show the TOC, assuming there were enough headings
	 *     to generate one and `__NOTOC__` wasn't used. Default is true,
	 *     but might be statefully overridden.
	 *  - enableSectionEditLinks: (bool) Include section edit links, assuming
	 *     section edit link tokens are present in the HTML. Default is true,
	 *     but might be statefully overridden.
	 *  - unwrap: (bool) Return text without a wrapper div. Default is false,
	 *    meaning a wrapper div will be added if getWrapperDivClass() returns
	 *    a non-empty string.
	 *  - wrapperDivClass: (string) Wrap the output in a div and apply the given
	 *    CSS class to that div. This overrides the output of getWrapperDivClass().
	 *    Setting this to an empty string has the same effect as 'unwrap' => true.
	 *  - deduplicateStyles: (bool) When true, which is the default, `<style>`
	 *    tags with the `data-mw-deduplicate` attribute set are deduplicated by
	 *    value of the attribute: all but the first will be replaced by `<link
	 *    rel="mw-deduplicated-inline-style" href="mw-data:..."/>` tags, where
	 *    the scheme-specific-part of the href is the (percent-encoded) value
	 *    of the `data-mw-deduplicate` attribute.
	 * @return string HTML
	 * @return-taint escaped
	 */
	public function getText( $options = [] ) {
		$options += [
			'allowTOC' => true,
			'enableSectionEditLinks' => true,
			'unwrap' => false,
			'deduplicateStyles' => true,
			'wrapperDivClass' => $this->getWrapperDivClass(),
		];
		$text = $this->getRawText();

		Hooks::runWithoutAbort( 'ParserOutputPostCacheTransform', [ $this, &$text, &$options ] );

		if ( $options['wrapperDivClass'] !== '' && !$options['unwrap'] ) {
			$text = Html::rawElement( 'div', [ 'class' => $options['wrapperDivClass'] ], $text );
		}

		if ( $options['enableSectionEditLinks'] ) {
			$text = preg_replace_callback(
				self::EDITSECTION_REGEX,
				function ( $m ) {
					$editsectionPage = Title::newFromText( htmlspecialchars_decode( $m[1] ) );
					$editsectionSection = htmlspecialchars_decode( $m[2] );
					$editsectionContent = isset( $m[4] ) ? Sanitizer::decodeCharReferences( $m[3] ) : null;

					if ( !is_object( $editsectionPage ) ) {
						throw new MWException( "Bad parser output text." );
					}

					$context = RequestContext::getMain();
					return $context->getSkin()->doEditSectionLink(
						$editsectionPage,
						$editsectionSection,
						$editsectionContent,
						$context->getLanguage()
					);
				},
				$text
			);
		} else {
			$text = preg_replace( self::EDITSECTION_REGEX, '', $text );
		}

		if ( $options['allowTOC'] ) {
			$text = str_replace( [ Parser::TOC_START, Parser::TOC_END ], '', $text );
		} else {
			$text = preg_replace(
				'#' . preg_quote( Parser::TOC_START, '#' ) . '.*?' . preg_quote( Parser::TOC_END, '#' ) . '#s',
				'',
				$text
			);
		}

		if ( $options['deduplicateStyles'] ) {
			$seen = [];
			$text = preg_replace_callback(
				'#<style\s+([^>]*data-mw-deduplicate\s*=[^>]*)>.*?</style>#s',
				function ( $m ) use ( &$seen ) {
					$attr = Sanitizer::decodeTagAttributes( $m[1] );
					if ( !isset( $attr['data-mw-deduplicate'] ) ) {
						return $m[0];
					}

					$key = $attr['data-mw-deduplicate'];
					if ( !isset( $seen[$key] ) ) {
						$seen[$key] = true;
						return $m[0];
					}

					// We were going to use an empty <style> here, but there
					// was concern that would be too much overhead for browsers.
					// So let's hope a <link> with a non-standard rel and href isn't
					// going to be misinterpreted or mangled by any subsequent processing.
					return Html::element( 'link', [
						'rel' => 'mw-deduplicated-inline-style',
						'href' => "mw-data:" . wfUrlencode( $key ),
					] );
				},
				$text
			);
		}

		// Hydrate slot section header placeholders generated by RevisionRenderer.
		$text = preg_replace_callback(
			'#<mw:slotheader>(.*?)</mw:slotheader>#',
			function ( $m ) {
				$role = htmlspecialchars_decode( $m[1] );
				// TODO: map to message, using the interface language. Set lang="xyz" accordingly.
				$headerText = $role;
				return $headerText;
			},
			$text
		);
		return $text;
	}

	/**
	 * Add a CSS class to use for the wrapping div. If no class is given, no wrapper is added.
	 *
	 * @param string $class
	 */
	public function addWrapperDivClass( $class ) {
		$this->mWrapperDivClasses[$class] = true;
	}

	/**
	 * Clears the CSS class to use for the wrapping div, effectively disabling the wrapper div
	 * until addWrapperDivClass() is called.
	 */
	public function clearWrapperDivClass() {
		$this->mWrapperDivClasses = [];
	}

	/**
	 * Returns the class (or classes) to be used with the wrapper div for this otuput.
	 * If there is no wrapper class given, no wrapper div should be added.
	 * The wrapper div is added automatically by getText().
	 *
	 * @return string
	 */
	public function getWrapperDivClass() {
		return implode( ' ', array_keys( $this->mWrapperDivClasses ) );
	}

	/**
	 * @param int $id
	 * @since 1.28
	 */
	public function setSpeculativeRevIdUsed( $id ) {
		$this->mSpeculativeRevId = $id;
	}

	/**
	 * @return int|null
	 * @since 1.28
	 */
	public function getSpeculativeRevIdUsed() {
		return $this->mSpeculativeRevId;
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
	 * @return array
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

	/**
	 * @deprecated since 1.31 Use getText() options.
	 */
	public function getEditSectionTokens() {
		wfDeprecated( __METHOD__, '1.31' );
		return true;
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

	public function setNoGallery( $value ) {
		$this->mNoGallery = (bool)$value;
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

	/**
	 * @return array
	 * @since 1.23
	 */
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

	/**
	 * @return string|null TS_MW timestamp of the revision content
	 */
	public function getTimestamp() {
		return $this->mTimestamp;
	}

	public function getLimitReportData() {
		return $this->mLimitReportData;
	}

	public function getLimitReportJSData() {
		return $this->mLimitReportJSData;
	}

	/**
	 * @deprecated since 1.31 Use getText() options.
	 */
	public function getTOCEnabled() {
		wfDeprecated( __METHOD__, '1.31' );
		return true;
	}

	public function getEnableOOUI() {
		return $this->mEnableOOUI;
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

	/**
	 * @deprecated since 1.31 Use getText() options.
	 */
	public function setEditSectionTokens( $t ) {
		wfDeprecated( __METHOD__, '1.31' );
		return true;
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

	/**
	 * @deprecated since 1.31 Use getText() options.
	 */
	public function setTOCEnabled( $flag ) {
		wfDeprecated( __METHOD__, '1.31' );
		return true;
	}

	public function addCategory( $c, $sort ) {
		$this->mCategories[$c] = $sort;
	}

	/**
	 * @param string $id
	 * @param string $content
	 * @since 1.25
	 */
	public function setIndicator( $id, $content ) {
		$this->mIndicators[$id] = $content;
	}

	/**
	 * Enables OOUI, if true, in any OutputPage instance this ParserOutput
	 * object is added to.
	 *
	 * @since 1.26
	 * @param bool $enable If OOUI should be enabled or not
	 */
	public function setEnableOOUI( $enable = false ) {
		$this->mEnableOOUI = $enable;
	}

	public function addLanguageLink( $t ) {
		$this->mLanguageLinks[] = $t;
	}

	public function addWarning( $s ) {
		$this->mWarnings[$s] = 1;
	}

	public function addOutputHook( $hook, $data = false ) {
		$this->mOutputHooks[] = [ $hook, $data ];
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

		# Replace unnecessary URL escape codes with the referenced character
		# This prevents spammers from hiding links from the filters
		$url = Parser::normalizeLinkUrl( $url );

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
			$this->mLinks[$ns] = [];
		}
		if ( is_null( $id ) ) {
			$id = $title->getArticleID();
		}
		$this->mLinks[$ns][$dbk] = $id;
	}

	/**
	 * Register a file dependency for this output
	 * @param string $name Title dbKey
	 * @param string|false|null $timestamp MW timestamp of file creation (or false if non-existing)
	 * @param string|false|null $sha1 Base 36 SHA-1 of file (or false if non-existing)
	 */
	public function addImage( $name, $timestamp = null, $sha1 = null ) {
		$this->mImages[$name] = 1;
		if ( $timestamp !== null && $sha1 !== null ) {
			$this->mFileSearchOptions[$name] = [ 'time' => $timestamp, 'sha1' => $sha1 ];
		}
	}

	/**
	 * Register a template dependency for this output
	 * @param Title $title
	 * @param int $page_id
	 * @param int $rev_id
	 */
	public function addTemplate( $title, $page_id, $rev_id ) {
		$ns = $title->getNamespace();
		$dbk = $title->getDBkey();
		if ( !isset( $this->mTemplates[$ns] ) ) {
			$this->mTemplates[$ns] = [];
		}
		$this->mTemplates[$ns][$dbk] = $page_id;
		if ( !isset( $this->mTemplateIds[$ns] ) ) {
			$this->mTemplateIds[$ns] = [];
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
			$this->mInterwikiLinks[$prefix] = [];
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

	/**
	 * @see OutputPage::addModules
	 */
	public function addModules( $modules ) {
		$this->mModules = array_merge( $this->mModules, (array)$modules );
	}

	/**
	 * @deprecated since 1.31 Use addModules() instead.
	 * @see OutputPage::addModuleScripts
	 */
	public function addModuleScripts( $modules ) {
		$this->mModuleScripts = array_merge( $this->mModuleScripts, (array)$modules );
	}

	/**
	 * @see OutputPage::addModuleStyles
	 */
	public function addModuleStyles( $modules ) {
		$this->mModuleStyles = array_merge( $this->mModuleStyles, (array)$modules );
	}

	/**
	 * Add one or more variables to be set in mw.config in JavaScript.
	 *
	 * @param string|array $keys Key or array of key/value pairs.
	 * @param mixed|null $value [optional] Value of the configuration variable.
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
	 * @todo Migrate some code to TrackingCategories
	 *
	 * @param string $msg Message key
	 * @param Title $title title of the page which is being tracked
	 * @return bool Whether the addition was successful
	 * @since 1.25
	 */
	public function addTrackingCategory( $msg, $title ) {
		if ( $title->isSpecialPage() ) {
			wfDebug( __METHOD__ . ": Not adding tracking category $msg to special page!\n" );
			return false;
		}

		// Important to parse with correct title (T33469)
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
	 *
	 * @note this is assumed to have been validated
	 * (check equal normalisation, etc.)
	 *
	 * @note this is expected to be safe HTML,
	 * ready to be served to the client.
	 *
	 * @param string $text Desired title text
	 */
	public function setDisplayTitle( $text ) {
		$this->setTitleText( $text );
		$this->setProperty( 'displaytitle', $text );
	}

	/**
	 * Get the title to be used for display.
	 *
	 * As per the contract of setDisplayTitle(), this is safe HTML,
	 * ready to be served to the client.
	 *
	 * @return string HTML
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
	 *   * Overriding the displayed article title (ParserOutput::setDisplayTitle()).
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
	 * @param string $name
	 * @param mixed $value
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
		return $this->mProperties[$name] ?? false;
	}

	public function unsetProperty( $name ) {
		unset( $this->mProperties[$name] );
	}

	public function getProperties() {
		if ( !isset( $this->mProperties ) ) {
			$this->mProperties = [];
		}
		return $this->mProperties;
	}

	/**
	 * Returns the options from its ParserOptions which have been taken
	 * into account to produce this output.
	 * @return string[]
	 */
	public function getUsedOptions() {
		if ( !isset( $this->mAccessedOptions ) ) {
			return [];
		}
		return array_keys( $this->mAccessedOptions );
	}

	/**
	 * Tags a parser option for use in the cache key for this parser output.
	 * Registered as a watcher at ParserOptions::registerWatcher() by Parser::clearState().
	 * The information gathered here is available via getUsedOptions(),
	 * and is used by ParserCache::save().
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
		$ret = [];
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

		if ( is_array( $value ) ) {
			if ( array_keys( $value ) === [ 0, 1 ]
				&& is_numeric( $value[0] )
				&& is_numeric( $value[1] )
			) {
				$data = [ 'value' => $value[0], 'limit' => $value[1] ];
			} else {
				$data = $value;
			}
		} else {
			$data = $value;
		}

		if ( strpos( $key, '-' ) ) {
			list( $ns, $name ) = explode( '-', $key, 2 );
			$this->mLimitReportJSData[$ns][$name] = $data;
		} else {
			$this->mLimitReportJSData[$key] = $data;
		}
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
	 * Lower the runtime adaptive TTL to at most this value
	 *
	 * @param int $ttl
	 * @since 1.28
	 */
	public function updateRuntimeAdaptiveExpiry( $ttl ) {
		$this->mMaxAdaptiveExpiry = min( $ttl, $this->mMaxAdaptiveExpiry );
		$this->updateCacheExpiry( $ttl );
	}

	/**
	 * Call this when parsing is done to lower the TTL based on low parse times
	 *
	 * @since 1.28
	 */
	public function finalizeAdaptiveCacheExpiry() {
		if ( is_infinite( $this->mMaxAdaptiveExpiry ) ) {
			return; // not set
		}

		$runtime = $this->getTimeSinceStart( 'wall' );
		if ( is_float( $runtime ) ) {
			$slope = ( self::SLOW_AR_TTL - self::FAST_AR_TTL )
				/ ( self::PARSE_SLOW_SEC - self::PARSE_FAST_SEC );
			// SLOW_AR_TTL = PARSE_SLOW_SEC * $slope + $point
			$point = self::SLOW_AR_TTL - self::PARSE_SLOW_SEC * $slope;

			$adaptiveTTL = min(
				max( $slope * $runtime + $point, self::MIN_AR_TTL ),
				$this->mMaxAdaptiveExpiry
			);
			$this->updateCacheExpiry( $adaptiveTTL );
		}
	}

	public function __sleep() {
		return array_diff(
			array_keys( get_object_vars( $this ) ),
			[ 'mParseStartTime' ]
		);
	}

	/**
	 * Merges internal metadata such as flags, accessed options, and profiling info
	 * from $source into this ParserOutput. This should be used whenever the state of $source
	 * has any impact on the state of this ParserOutput.
	 *
	 * @param ParserOutput $source
	 */
	public function mergeInternalMetaDataFrom( ParserOutput $source ) {
		$this->mOutputHooks = self::mergeList( $this->mOutputHooks, $source->getOutputHooks() );
		$this->mWarnings = self::mergeMap( $this->mWarnings, $source->mWarnings ); // don't use getter
		$this->mTimestamp = $this->useMaxValue( $this->mTimestamp, $source->getTimestamp() );

		if ( $this->mSpeculativeRevId && $source->mSpeculativeRevId
			&& $this->mSpeculativeRevId !== $source->mSpeculativeRevId
		) {
			wfLogWarning(
				'Inconsistent speculative revision ID encountered while merging parser output!'
			);
		}

		$this->mSpeculativeRevId = $this->useMaxValue(
			$this->mSpeculativeRevId,
			$source->getSpeculativeRevIdUsed()
		);
		$this->mParseStartTime = $this->useEachMinValue(
			$this->mParseStartTime,
			$source->mParseStartTime
		);

		$this->mFlags = self::mergeMap( $this->mFlags, $source->mFlags );
		$this->mAccessedOptions = self::mergeMap( $this->mAccessedOptions, $source->mAccessedOptions );

		// TODO: maintain per-slot limit reports!
		if ( empty( $this->mLimitReportData ) ) {
			$this->mLimitReportData = $source->mLimitReportData;
		}
		if ( empty( $this->mLimitReportJSData ) ) {
			$this->mLimitReportJSData = $source->mLimitReportJSData;
		}
	}

	/**
	 * Merges HTML metadata such as head items, JS config vars, and HTTP cache control info
	 * from $source into this ParserOutput. This should be used whenever the HTML in $source
	 * has been somehow mered into the HTML of this ParserOutput.
	 *
	 * @param ParserOutput $source
	 */
	public function mergeHtmlMetaDataFrom( ParserOutput $source ) {
		// HTML and HTTP
		$this->mHeadItems = self::mergeMixedList( $this->mHeadItems, $source->getHeadItems() );
		$this->mModules = self::mergeList( $this->mModules, $source->getModules() );
		$this->mModuleScripts = self::mergeList( $this->mModuleScripts, $source->getModuleScripts() );
		$this->mModuleStyles = self::mergeList( $this->mModuleStyles, $source->getModuleStyles() );
		$this->mJsConfigVars = self::mergeMap( $this->mJsConfigVars, $source->getJsConfigVars() );
		$this->mMaxAdaptiveExpiry = min( $this->mMaxAdaptiveExpiry, $source->mMaxAdaptiveExpiry );

		// "noindex" always wins!
		if ( $this->mIndexPolicy === 'noindex' || $source->mIndexPolicy === 'noindex' ) {
			$this->mIndexPolicy = 'noindex';
		} elseif ( $this->mIndexPolicy !== 'index' ) {
			$this->mIndexPolicy = $source->mIndexPolicy;
		}

		// Skin control
		$this->mNewSection = $this->mNewSection || $source->getNewSection();
		$this->mHideNewSection = $this->mHideNewSection || $source->getHideNewSection();
		$this->mNoGallery = $this->mNoGallery || $source->getNoGallery();
		$this->mEnableOOUI = $this->mEnableOOUI || $source->getEnableOOUI();
		$this->mPreventClickjacking = $this->mPreventClickjacking || $source->preventClickjacking();

		// TODO: we'll have to be smarter about this!
		$this->mSections = array_merge( $this->mSections, $source->getSections() );
		$this->mTOCHTML = $this->mTOCHTML . $source->mTOCHTML;

		// XXX: we don't want to concatenate title text, so first write wins.
		// We should use the first *modified* title text, but we don't have the original to check.
		if ( $this->mTitleText === null || $this->mTitleText === '' ) {
			$this->mTitleText = $source->mTitleText;
		}

		// class names are stored in array keys
		$this->mWrapperDivClasses = self::mergeMap(
			$this->mWrapperDivClasses,
			$source->mWrapperDivClasses
		);

		// NOTE: last write wins, same as within one ParserOutput
		$this->mIndicators = self::mergeMap( $this->mIndicators, $source->getIndicators() );

		// NOTE: include extension data in "tracking meta data" as well as "html meta data"!
		// TODO: add a $mergeStrategy parameter to setExtensionData to allow different
		// kinds of extension data to be merged in different ways.
		$this->mExtensionData = self::mergeMap(
			$this->mExtensionData,
			$source->mExtensionData
		);
	}

	/**
	 * Merges dependency tracking metadata such as backlinks, images used, and extension data
	 * from $source into this ParserOutput. This allows dependency tracking to be done for the
	 * combined output of multiple content slots.
	 *
	 * @param ParserOutput $source
	 */
	public function mergeTrackingMetaDataFrom( ParserOutput $source ) {
		$this->mLanguageLinks = self::mergeList( $this->mLanguageLinks, $source->getLanguageLinks() );
		$this->mCategories = self::mergeMap( $this->mCategories, $source->getCategories() );
		$this->mLinks = self::merge2D( $this->mLinks, $source->getLinks() );
		$this->mTemplates = self::merge2D( $this->mTemplates, $source->getTemplates() );
		$this->mTemplateIds = self::merge2D( $this->mTemplateIds, $source->getTemplateIds() );
		$this->mImages = self::mergeMap( $this->mImages, $source->getImages() );
		$this->mFileSearchOptions = self::mergeMap(
			$this->mFileSearchOptions,
			$source->getFileSearchOptions()
		);
		$this->mExternalLinks = self::mergeMap( $this->mExternalLinks, $source->getExternalLinks() );
		$this->mInterwikiLinks = self::merge2D(
			$this->mInterwikiLinks,
			$source->getInterwikiLinks()
		);

		// TODO: add a $mergeStrategy parameter to setProperty to allow different
		// kinds of properties to be merged in different ways.
		$this->mProperties = self::mergeMap( $this->mProperties, $source->getProperties() );

		// NOTE: include extension data in "tracking meta data" as well as "html meta data"!
		// TODO: add a $mergeStrategy parameter to setExtensionData to allow different
		// kinds of extension data to be merged in different ways.
		$this->mExtensionData = self::mergeMap(
			$this->mExtensionData,
			$source->mExtensionData
		);
	}

	private static function mergeMixedList( array $a, array $b ) {
		return array_unique( array_merge( $a, $b ), SORT_REGULAR );
	}

	private static function mergeList( array $a, array $b ) {
		return array_values( array_unique( array_merge( $a, $b ), SORT_REGULAR ) );
	}

	private static function mergeMap( array $a, array $b ) {
		return array_replace( $a, $b );
	}

	private static function merge2D( array $a, array $b ) {
		$values = [];
		$keys = array_merge( array_keys( $a ), array_keys( $b ) );

		foreach ( $keys as $k ) {
			if ( empty( $a[$k] ) ) {
				$values[$k] = $b[$k];
			} elseif ( empty( $b[$k] ) ) {
				$values[$k] = $a[$k];
			} elseif ( is_array( $a[$k] ) && is_array( $b[$k] ) ) {
				$values[$k] = array_replace( $a[$k], $b[$k] );
			} else {
				$values[$k] = $b[$k];
			}
		}

		return $values;
	}

	private static function useEachMinValue( array $a, array $b ) {
		$values = [];
		$keys = array_merge( array_keys( $a ), array_keys( $b ) );

		foreach ( $keys as $k ) {
			if ( is_array( $a[$k] ?? null ) && is_array( $b[$k] ?? null ) ) {
				$values[$k] = self::useEachMinValue( $a[$k], $b[$k] );
			} else {
				$values[$k] = self::useMinValue( $a[$k] ?? null, $b[$k] ?? null );
			}
		}

		return $values;
	}

	private static function useMinValue( $a, $b ) {
		if ( $a === null ) {
			return $b;
		}

		if ( $b === null ) {
			return $a;
		}

		return min( $a, $b );
	}

	private static function useMaxValue( $a, $b ) {
		if ( $a === null ) {
			return $b;
		}

		if ( $b === null ) {
			return $a;
		}

		return max( $a, $b );
	}

}
