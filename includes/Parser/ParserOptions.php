<?php
/**
 * Options for the PHP parser
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Content\Content;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageRecord;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\StubObject\StubObject;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\MWTimestamp;
use ReflectionClass;
use Wikimedia\IPUtils;
use Wikimedia\ScopedCallback;

/**
 * @brief Set options of the Parser
 *
 * How to add an option in core:
 *  1. Add it to one of the arrays in ParserOptions::setDefaults()
 *  2. If necessary, add an entry to ParserOptions::$inCacheKey
 *  3. Add a getter and setter in the section for that.
 *
 * How to add an option in an extension:
 *  1. Use the 'ParserOptionsRegister' hook to register it.
 *  2. Where necessary, use $popt->getOption() and $popt->setOption()
 *     to access it.
 *
 * @ingroup Parser
 */
class ParserOptions {

	/**
	 * Default values for all options that are relevant for caching.
	 * @see self::getDefaults()
	 * @var array|null
	 */
	private static $defaults = null;

	/**
	 * Lazy-loaded options
	 * @var callable[]|null
	 */
	private static $lazyOptions = null;

	/**
	 * Initial lazy-loaded options (before hook)
	 * @var callable[]
	 */
	private static $initialLazyOptions = [
		'dateformat' => [ self::class, 'initDateFormat' ],
		'speculativeRevId' => [ self::class, 'initSpeculativeRevId' ],
		'speculativePageId' => [ self::class, 'initSpeculativePageId' ],
	];

	/**
	 * Specify options that are included in the cache key
	 * @var array|null
	 */
	private static $cacheVaryingOptionsHash = null;

	/**
	 * Initial inCacheKey options (before hook)
	 * @var array
	 */
	private static $initialCacheVaryingOptionsHash = [
		'dateformat' => true,
		'thumbsize' => true,
		'printable' => true,
		'userlang' => true,
		'useParsoid' => true,
		'suppressSectionEditLinks' => true,
		'collapsibleSections' => true,
	];

	/**
	 * Specify pseudo-options that are actually callbacks.
	 * These must be ignored when checking for cacheability.
	 * @var array
	 */
	private static $callbacks = [
		'currentRevisionRecordCallback' => true,
		'templateCallback' => true,
		'speculativeRevIdCallback' => true,
		'speculativePageIdCallback' => true,
	];

	/**
	 * Current values for all options that are relevant for caching.
	 * @var array
	 */
	private $options;

	/**
	 * Timestamp used for {{CURRENTDAY}} etc.
	 * @var string|null
	 * @note Caching based on parse time is handled externally
	 */
	private $mTimestamp;

	/**
	 * Stored user object
	 * @var UserIdentity
	 * @todo Track this for caching somehow without fragmenting the cache
	 */
	private $mUser;

	/**
	 * Function to be called when an option is accessed.
	 * @var callable|null
	 * @note Used for collecting used options, does not affect caching
	 */
	private $onAccessCallback = null;

	/**
	 * If the page being parsed is a redirect, this should hold the redirect
	 * target.
	 * @var Title|null
	 * @todo Track this for caching somehow
	 */
	private $redirectTarget = null;

	/**
	 * Appended to the options hash
	 * @var string
	 */
	private $mExtraKey = '';

	/**
	 * The reason for rendering the content.
	 * @var string
	 */
	private $renderReason = 'unknown';

	/**
	 * Fetch an option and track that is was accessed
	 * @since 1.30
	 * @param string $name Option name
	 * @return mixed
	 */
	public function getOption( $name ) {
		if ( !array_key_exists( $name, $this->options ) ) {
			throw new InvalidArgumentException( "Unknown parser option $name" );
		}

		$this->lazyLoadOption( $name );
		$this->optionUsed( $name );
		return $this->options[$name];
	}

	/**
	 * @param string $name Lazy load option without tracking usage
	 */
	private function lazyLoadOption( $name ) {
		$lazyOptions = self::getLazyOptions();
		if ( isset( $lazyOptions[$name] ) && $this->options[$name] === null ) {
			$this->options[$name] = $lazyOptions[$name]( $this, $name );
		}
	}

	/**
	 * Resets lazy loaded options to null in the provided $options array
	 * @param array $options
	 * @return array
	 */
	private function nullifyLazyOption( array $options ): array {
		return array_fill_keys( array_keys( self::getLazyOptions() ), null ) + $options;
	}

	/**
	 * Get lazy-loaded options.
	 *
	 * This array should be initialised by the constructor. The return type
	 * hint is used as an assertion to ensure this has happened and to coerce
	 * the type for static analysis.
	 *
	 * @internal Public for testing only
	 *
	 * @return array
	 */
	public static function getLazyOptions(): array {
		// Trigger a call to the 'ParserOptionsRegister' hook if it hasn't
		// already been called.
		if ( self::$lazyOptions === null ) {
			self::getDefaults();
		}
		return self::$lazyOptions;
	}

	/**
	 * Get cache varying options, with the name of the option in the key, and a
	 * boolean in the value which indicates whether the cache is indeed varied.
	 *
	 * @see self::allCacheVaryingOptions()
	 *
	 * @return array
	 */
	private static function getCacheVaryingOptionsHash(): array {
		// Trigger a call to the 'ParserOptionsRegister' hook if it hasn't
		// already been called.
		if ( self::$cacheVaryingOptionsHash === null ) {
			self::getDefaults();
		}
		return self::$cacheVaryingOptionsHash;
	}

	/**
	 * Set an option, generically
	 * @since 1.30
	 * @param string $name Option name
	 * @param mixed $value New value. Passing null will set null, unlike many
	 *  of the existing accessors which ignore null for historical reasons.
	 * @return mixed Old value
	 */
	public function setOption( $name, $value ) {
		if ( !array_key_exists( $name, $this->options ) ) {
			throw new InvalidArgumentException( "Unknown parser option $name" );
		}
		$old = $this->options[$name];
		$this->options[$name] = $value;
		return $old;
	}

	/**
	 * Legacy implementation
	 * @since 1.30 For implementing legacy setters only. Don't use this in new code.
	 * @deprecated since 1.30
	 * @param string $name Option name
	 * @param mixed $value New value. Passing null does not set the value.
	 * @return mixed Old value
	 */
	protected function setOptionLegacy( $name, $value ) {
		if ( !array_key_exists( $name, $this->options ) ) {
			throw new InvalidArgumentException( "Unknown parser option $name" );
		}
		return wfSetVar( $this->options[$name], $value );
	}

	/**
	 * Whether to extract interlanguage links
	 *
	 * When true, interlanguage links will be returned by
	 * ParserOutput::getLanguageLinks() instead of generating link HTML.
	 *
	 * @return bool
	 */
	public function getInterwikiMagic() {
		return $this->getOption( 'interwikiMagic' );
	}

	/**
	 * Specify whether to extract interlanguage links
	 * @param bool|null $x New value (null is no change)
	 * @return bool Old value
	 */
	public function setInterwikiMagic( $x ) {
		return $this->setOptionLegacy( 'interwikiMagic', $x );
	}

	/**
	 * Allow all external images inline?
	 * @return bool
	 */
	public function getAllowExternalImages() {
		return $this->getOption( 'allowExternalImages' );
	}

	/**
	 * External images to allow
	 *
	 * When self::getAllowExternalImages() is false
	 *
	 * @return string|string[] URLs to allow
	 */
	public function getAllowExternalImagesFrom() {
		return $this->getOption( 'allowExternalImagesFrom' );
	}

	/**
	 * Use the on-wiki external image whitelist?
	 * @return bool
	 */
	public function getEnableImageWhitelist() {
		return $this->getOption( 'enableImageWhitelist' );
	}

	/**
	 * Allow inclusion of special pages?
	 * @return bool
	 */
	public function getAllowSpecialInclusion() {
		return $this->getOption( 'allowSpecialInclusion' );
	}

	/**
	 * Allow inclusion of special pages?
	 * @param bool|null $x New value (null is no change)
	 * @return bool Old value
	 */
	public function setAllowSpecialInclusion( $x ) {
		return $this->setOptionLegacy( 'allowSpecialInclusion', $x );
	}

	/**
	 * Parsing an interface message in the user language?
	 *
	 * Note: use isMessage() to determine if we are parsing a message in any language.
	 *
	 * @return bool
	 */
	public function getInterfaceMessage() {
		return $this->getOption( 'interfaceMessage' );
	}

	/**
	 * Parsing an interface message in the user language?
	 * @param bool|null $x New value (null is no change)
	 * @return bool Old value
	 */
	public function setInterfaceMessage( $x ) {
		return $this->setOptionLegacy( 'interfaceMessage', $x );
	}

	/**
	 * Parsing a message?
	 *
	 * @since 1.45
	 * @return bool
	 */
	public function isMessage() {
		return $this->getOption( 'isMessage' );
	}

	/**
	 * Set whether we are parsing a message
	 *
	 * @since 1.45
	 * @param bool $x
	 * @return bool
	 */
	public function setIsMessage( $x ) {
		return $this->setOption( 'isMessage', $x );
	}

	/**
	 * Target language for the parse
	 * @return Language|null
	 */
	public function getTargetLanguage() {
		return $this->getOption( 'targetLanguage' );
	}

	/**
	 * Target language for the parse
	 * @param Language|null $x New value
	 * @return Language|null Old value
	 */
	public function setTargetLanguage( $x ) {
		return $this->setOption( 'targetLanguage', $x );
	}

	/**
	 * Maximum size of template expansions, in bytes
	 * @return int
	 */
	public function getMaxIncludeSize() {
		return $this->getOption( 'maxIncludeSize' );
	}

	/**
	 * Maximum size of template expansions, in bytes
	 * @param int|null $x New value (null is no change)
	 * @return int Old value
	 */
	public function setMaxIncludeSize( $x ) {
		return $this->setOptionLegacy( 'maxIncludeSize', $x );
	}

	/**
	 * Maximum number of nodes touched by PPFrame::expand()
	 * @return int
	 */
	public function getMaxPPNodeCount() {
		return $this->getOption( 'maxPPNodeCount' );
	}

	/**
	 * Maximum number of nodes touched by PPFrame::expand()
	 * @param int|null $x New value (null is no change)
	 * @return int Old value
	 */
	public function setMaxPPNodeCount( $x ) {
		return $this->setOptionLegacy( 'maxPPNodeCount', $x );
	}

	/**
	 * Maximum recursion depth in PPFrame::expand()
	 * @return int
	 */
	public function getMaxPPExpandDepth() {
		return $this->getOption( 'maxPPExpandDepth' );
	}

	/**
	 * Maximum recursion depth for templates within templates
	 * @return int
	 * @internal Only used by Parser (T318826)
	 */
	public function getMaxTemplateDepth() {
		return $this->getOption( 'maxTemplateDepth' );
	}

	/**
	 * Maximum recursion depth for templates within templates
	 * @param int|null $x New value (null is no change)
	 * @return int Old value
	 * @internal Only used by ParserTestRunner (T318826)
	 */
	public function setMaxTemplateDepth( $x ) {
		return $this->setOptionLegacy( 'maxTemplateDepth', $x );
	}

	/**
	 * Maximum number of calls per parse to expensive parser functions
	 * @since 1.20
	 * @return int
	 */
	public function getExpensiveParserFunctionLimit() {
		return $this->getOption( 'expensiveParserFunctionLimit' );
	}

	/**
	 * Maximum number of calls per parse to expensive parser functions
	 * @since 1.20
	 * @param int|null $x New value (null is no change)
	 * @return int Old value
	 */
	public function setExpensiveParserFunctionLimit( $x ) {
		return $this->setOptionLegacy( 'expensiveParserFunctionLimit', $x );
	}

	/**
	 * Remove HTML comments
	 * @warning Only applies to preprocess operations
	 * @return bool
	 */
	public function getRemoveComments() {
		return $this->getOption( 'removeComments' );
	}

	/**
	 * Remove HTML comments
	 * @warning Only applies to preprocess operations
	 * @param bool|null $x New value (null is no change)
	 * @return bool Old value
	 */
	public function setRemoveComments( $x ) {
		return $this->setOptionLegacy( 'removeComments', $x );
	}

	/**
	 * @deprecated since 1.38. This does nothing now, to control limit reporting
	 * please provide 'includeDebugInfo' option to ParserOutput::getText.
	 *
	 * Enable limit report in an HTML comment on output
	 * @return bool
	 */
	public function getEnableLimitReport() {
		return false;
	}

	/**
	 * @deprecated since 1.38. This does nothing now, to control limit reporting
	 * please provide 'includeDebugInfo' option to ParserOutput::getText.
	 *
	 * Enable limit report in an HTML comment on output
	 * @param bool|null $x New value (null is no change)
	 * @return bool Old value
	 */
	public function enableLimitReport( $x = true ) {
		return false;
	}

	/**
	 * Clean up signature texts?
	 * @see Parser::cleanSig
	 * @return bool
	 */
	public function getCleanSignatures() {
		return $this->getOption( 'cleanSignatures' );
	}

	/**
	 * Clean up signature texts?
	 * @see Parser::cleanSig
	 * @param bool|null $x New value (null is no change)
	 * @return bool Old value
	 */
	public function setCleanSignatures( $x ) {
		return $this->setOptionLegacy( 'cleanSignatures', $x );
	}

	/**
	 * Target attribute for external links
	 * @return string|false
	 * @internal Only set by installer (T317647)
	 */
	public function getExternalLinkTarget() {
		return $this->getOption( 'externalLinkTarget' );
	}

	/**
	 * Target attribute for external links
	 * @param string|false|null $x New value (null is no change)
	 * @return string Old value
	 * @internal Only used by installer (T317647)
	 */
	public function setExternalLinkTarget( $x ) {
		return $this->setOptionLegacy( 'externalLinkTarget', $x );
	}

	/**
	 * Whether content conversion should be disabled
	 * @return bool
	 */
	public function getDisableContentConversion() {
		return $this->getOption( 'disableContentConversion' );
	}

	/**
	 * Whether content conversion should be disabled
	 * @param bool|null $x New value (null is no change)
	 * @return bool Old value
	 */
	public function disableContentConversion( $x = true ) {
		return $this->setOptionLegacy( 'disableContentConversion', $x );
	}

	/**
	 * Whether title conversion should be disabled
	 * @return bool
	 */
	public function getDisableTitleConversion() {
		return $this->getOption( 'disableTitleConversion' );
	}

	/**
	 * Whether title conversion should be disabled
	 * @param bool|null $x New value (null is no change)
	 * @return bool Old value
	 */
	public function disableTitleConversion( $x = true ) {
		return $this->setOptionLegacy( 'disableTitleConversion', $x );
	}

	/**
	 * Thumb size preferred by the user.
	 * @return int
	 */
	public function getThumbSize() {
		return $this->getOption( 'thumbsize' );
	}

	/**
	 * Thumb size preferred by the user.
	 * @param int|null $x New value (null is no change)
	 * @return int Old value
	 */
	public function setThumbSize( $x ) {
		return $this->setOptionLegacy( 'thumbsize', $x );
	}

	/**
	 * Parsing the page for a "preview" operation?
	 * @return bool
	 */
	public function getIsPreview() {
		return $this->getOption( 'isPreview' );
	}

	/**
	 * Parsing the page for a "preview" operation?
	 * @param bool|null $x New value (null is no change)
	 * @return bool Old value
	 */
	public function setIsPreview( $x ) {
		return $this->setOptionLegacy( 'isPreview', $x );
	}

	/**
	 * Parsing the page for a "preview" operation on a single section?
	 * @return bool
	 */
	public function getIsSectionPreview() {
		return $this->getOption( 'isSectionPreview' );
	}

	/**
	 * Parsing the page for a "preview" operation on a single section?
	 * @param bool|null $x New value (null is no change)
	 * @return bool Old value
	 */
	public function setIsSectionPreview( $x ) {
		return $this->setOptionLegacy( 'isSectionPreview', $x );
	}

	/**
	 * Parsing the printable version of the page?
	 * @return bool
	 */
	public function getIsPrintable() {
		return $this->getOption( 'printable' );
	}

	/**
	 * Parsing the printable version of the page?
	 * @param bool|null $x New value (null is no change)
	 * @return bool Old value
	 */
	public function setIsPrintable( $x ) {
		return $this->setOptionLegacy( 'printable', $x );
	}

	/**
	 * Transform wiki markup when saving the page?
	 * @return bool
	 */
	public function getPreSaveTransform() {
		return $this->getOption( 'preSaveTransform' );
	}

	/**
	 * Transform wiki markup when saving the page?
	 * @param bool|null $x New value (null is no change)
	 * @return bool Old value
	 */
	public function setPreSaveTransform( $x ) {
		return $this->setOptionLegacy( 'preSaveTransform', $x );
	}

	/**
	 * Parsoid-format HTML output, or legacy wikitext parser HTML?
	 * @see T300191
	 * @unstable
	 * @since 1.41
	 * @return bool
	 */
	public function getUseParsoid(): bool {
		return $this->getOption( 'useParsoid' );
	}

	/**
	 * Request Parsoid-format HTML output.
	 * @see T300191
	 * @unstable
	 * @since 1.41
	 */
	public function setUseParsoid( bool $value = true ) {
		$this->setOption( 'useParsoid', $value );
	}

	/**
	 * Date format index
	 * @return string
	 */
	public function getDateFormat() {
		return $this->getOption( 'dateformat' );
	}

	/**
	 * Lazy initializer for dateFormat
	 * @param ParserOptions $popt
	 * @return string
	 */
	private static function initDateFormat( ParserOptions $popt ) {
		$userFactory = MediaWikiServices::getInstance()->getUserFactory();
		return $userFactory->newFromUserIdentity( $popt->getUserIdentity() )->getDatePreference();
	}

	/**
	 * Date format index
	 * @param string|null $x New value (null is no change)
	 * @return string Old value
	 */
	public function setDateFormat( $x ) {
		return $this->setOptionLegacy( 'dateformat', $x );
	}

	/**
	 * Get the user language used by the parser for this page and split the parser cache.
	 *
	 * @warning Calling this causes the parser cache to be fragmented by user language!
	 * To avoid cache fragmentation, output should not depend on the user language.
	 * Use Parser::getTargetLanguage() instead!
	 *
	 * @note This function will trigger a cache fragmentation by recording the
	 * 'userlang' option, see optionUsed(). This is done to avoid cache pollution
	 * when the page is rendered based on the language of the user.
	 *
	 * @note When saving, this will return the default language instead of the user's.
	 * {{int: }} uses this which used to produce inconsistent link tables (T16404).
	 *
	 * @return Language
	 * @since 1.19
	 */
	public function getUserLangObj() {
		return $this->getOption( 'userlang' );
	}

	/**
	 * Same as getUserLangObj() but returns a string instead.
	 *
	 * @warning Calling this causes the parser cache to be fragmented by user language!
	 * To avoid cache fragmentation, output should not depend on the user language.
	 * Use Parser::getTargetLanguage() instead!
	 *
	 * @see getUserLangObj()
	 *
	 * @return string Language code
	 * @since 1.17
	 */
	public function getUserLang() {
		return $this->getUserLangObj()->getCode();
	}

	/**
	 * Set the user language used by the parser for this page and split the parser cache.
	 * @param string|Language $x New value
	 * @return Language Old value
	 */
	public function setUserLang( $x ) {
		if ( is_string( $x ) ) {
			$x = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( $x );
		}

		return $this->setOptionLegacy( 'userlang', $x );
	}

	/**
	 * Are magic ISBN links enabled?
	 * @since 1.28
	 * @return bool
	 */
	public function getMagicISBNLinks() {
		return $this->getOption( 'magicISBNLinks' );
	}

	/**
	 * Are magic PMID links enabled?
	 * @since 1.28
	 * @return bool
	 */
	public function getMagicPMIDLinks() {
		return $this->getOption( 'magicPMIDLinks' );
	}

	/**
	 * Are magic RFC links enabled?
	 * @since 1.28
	 * @return bool
	 */
	public function getMagicRFCLinks() {
		return $this->getOption( 'magicRFCLinks' );
	}

	/**
	 * Should the table of contents be suppressed?
	 * Used when parsing "code" pages (like JavaScript) as wikitext
	 * for backlink support and categories, but where we don't want
	 * other metadata generated (like the table of contents).
	 * @see T307691
	 * @since 1.39
	 * @return bool
	 */
	public function getSuppressTOC() {
		return $this->getOption( 'suppressTOC' );
	}

	/**
	 * Suppress generation of the table of contents.
	 * Used when parsing "code" pages (like JavaScript) as wikitext
	 * for backlink support and categories, but where we don't want
	 * other metadata generated (like the table of contents).
	 * @see T307691
	 * @since 1.39
	 * @deprecated since 1.42; just clear the metadata in the final
	 *  parser output
	 */
	public function setSuppressTOC() {
		wfDeprecated( __METHOD__, '1.42' );
		$this->setOption( 'suppressTOC', true );
	}

	/**
	 * Should section edit links be suppressed?
	 * Used when parsing wikitext which will be presented in a
	 * non-interactive context: previews, UX text, etc.
	 * @since 1.42
	 * @return bool
	 */
	public function getSuppressSectionEditLinks() {
		return $this->getOption( 'suppressSectionEditLinks' );
	}

	/**
	 * Suppress section edit links in the output.
	 * Used when parsing wikitext which will be presented in a
	 * non-interactive context: previews, UX text, etc.
	 * @since 1.42
	 */
	public function setSuppressSectionEditLinks() {
		$this->setOption( 'suppressSectionEditLinks', true );
	}

	/**
	 * Should section contents be wrapped in <div> to make them
	 * collapsible?
	 * @since 1.42
	 */
	public function getCollapsibleSections(): bool {
		return $this->getOption( 'collapsibleSections' );
	}

	/**
	 * Wrap section contents in a <div> to allow client-side code
	 * to collapse them.
	 * @since 1.42
	 */
	public function setCollapsibleSections(): void {
		$this->setOption( 'collapsibleSections', true );
	}

	/**
	 * If the wiki is configured to allow raw html ($wgRawHtml = true)
	 * is it allowed in the specific case of parsing this page.
	 *
	 * This is meant to disable unsafe parser tags in cases where
	 * a malicious user may control the input to the parser.
	 *
	 * @note This is expected to be true for normal pages even if the
	 *  wiki has $wgRawHtml disabled in general. The setting only
	 *  signifies that raw html would be unsafe in the current context
	 *  provided that raw html is allowed at all.
	 * @since 1.29
	 * @return bool
	 */
	public function getAllowUnsafeRawHtml() {
		return $this->getOption( 'allowUnsafeRawHtml' );
	}

	/**
	 * If the wiki is configured to allow raw html ($wgRawHtml = true)
	 * is it allowed in the specific case of parsing this page.
	 * @see self::getAllowUnsafeRawHtml()
	 * @since 1.29
	 * @param bool|null $x Value to set or null to get current value
	 * @return bool Current value for allowUnsafeRawHtml
	 */
	public function setAllowUnsafeRawHtml( $x ) {
		return $this->setOptionLegacy( 'allowUnsafeRawHtml', $x );
	}

	/**
	 * Class to use to wrap output from Parser::parse()
	 * @since 1.30
	 * @return string|false
	 */
	public function getWrapOutputClass() {
		return $this->getOption( 'wrapclass' );
	}

	/**
	 * CSS class to use to wrap output from Parser::parse()
	 * @since 1.30
	 * @param string $className Class name to use for wrapping.
	 *   Passing false to indicate "no wrapping" was deprecated in MediaWiki 1.31.
	 * @return string|false Current value
	 */
	public function setWrapOutputClass( $className ) {
		if ( $className === true ) { // DWIM, they probably want the default class name
			$className = 'mw-parser-output';
		}
		if ( $className === false ) {
			wfDeprecated( __METHOD__ . '( false )', '1.31' );
		}
		return $this->setOption( 'wrapclass', $className );
	}

	/**
	 * Callback to fetch the current revision
	 * @internal
	 * @since 1.35
	 * @return callable
	 */
	public function getCurrentRevisionRecordCallback() {
		return $this->getOption( 'currentRevisionRecordCallback' );
	}

	/**
	 * Callback to fetch the current revision
	 * @internal
	 * @since 1.35
	 * @param callable|null $x New value
	 * @return callable Old value
	 */
	public function setCurrentRevisionRecordCallback( $x ) {
		return $this->setOption( 'currentRevisionRecordCallback', $x );
	}

	/**
	 * Callback to fetch a template
	 * @see Parser::statelessFetchTemplate
	 * @return callable
	 */
	public function getTemplateCallback() {
		return $this->getOption( 'templateCallback' );
	}

	/**
	 * Set the callback to fetch a template
	 * @param callable|null $x New value (null is no change)
	 * @return callable Old value
	 */
	public function setTemplateCallback( $x ) {
		return $this->setOptionLegacy( 'templateCallback', $x );
	}

	/**
	 * A guess for {{REVISIONID}}, calculated using the callback provided via
	 * setSpeculativeRevIdCallback(). For consistency, the value will be calculated upon the
	 * first call of this method, and re-used for subsequent calls.
	 *
	 * If no callback was defined via setSpeculativeRevIdCallback(), this method will return false.
	 *
	 * @since 1.32
	 * @return int|false
	 */
	public function getSpeculativeRevId() {
		return $this->getOption( 'speculativeRevId' );
	}

	/**
	 * A guess for {{PAGEID}}, calculated using the callback provided via
	 * setSpeculativeRevPageCallback(). For consistency, the value will be calculated upon the
	 * first call of this method, and re-used for subsequent calls.
	 *
	 * If no callback was defined via setSpeculativePageIdCallback(), this method will return false.
	 *
	 * @since 1.34
	 * @return int|false
	 */
	public function getSpeculativePageId() {
		return $this->getOption( 'speculativePageId' );
	}

	/**
	 * Callback registered with ParserOptions::$lazyOptions, triggered by getSpeculativeRevId().
	 *
	 * @param ParserOptions $popt
	 * @return int|false
	 */
	private static function initSpeculativeRevId( ParserOptions $popt ) {
		$cb = $popt->getOption( 'speculativeRevIdCallback' );
		$id = $cb ? $cb() : null;

		// returning null would result in this being re-called every access
		return $id ?? false;
	}

	/**
	 * Callback registered with ParserOptions::$lazyOptions, triggered by getSpeculativePageId().
	 *
	 * @param ParserOptions $popt
	 * @return int|false
	 */
	private static function initSpeculativePageId( ParserOptions $popt ) {
		$cb = $popt->getOption( 'speculativePageIdCallback' );
		$id = $cb ? $cb() : null;

		// returning null would result in this being re-called every access
		return $id ?? false;
	}

	/**
	 * Callback to generate a guess for {{REVISIONID}}
	 * @param callable|null $x New value
	 * @return callable|null Old value
	 * @since 1.28
	 */
	public function setSpeculativeRevIdCallback( $x ) {
		$this->setOption( 'speculativeRevId', null ); // reset
		return $this->setOption( 'speculativeRevIdCallback', $x );
	}

	/**
	 * Callback to generate a guess for {{PAGEID}}
	 * @param callable|null $x New value
	 * @return callable|null Old value
	 * @since 1.34
	 */
	public function setSpeculativePageIdCallback( $x ) {
		$this->setOption( 'speculativePageId', null ); // reset
		return $this->setOption( 'speculativePageIdCallback', $x );
	}

	/**
	 * Timestamp used for {{CURRENTDAY}} etc.
	 * @return string TS_MW timestamp
	 */
	public function getTimestamp() {
		if ( $this->mTimestamp === null ) {
			$this->mTimestamp = wfTimestampNow();
		}
		return $this->mTimestamp;
	}

	/**
	 * Timestamp used for {{CURRENTDAY}} etc.
	 * @param string|null $x New value (null is no change)
	 * @return string Old value
	 */
	public function setTimestamp( $x ) {
		return wfSetVar( $this->mTimestamp, $x );
	}

	/**
	 * Note that setting or changing this does not *make* the page a redirect
	 * or change its target, it merely records the information for reference
	 * during the parse.
	 *
	 * @since 1.24
	 * @param Title|null $title
	 */
	public function setRedirectTarget( $title ) {
		$this->redirectTarget = $title;
	}

	/**
	 * Get the previously-set redirect target.
	 *
	 * @since 1.24
	 * @return Title|null
	 */
	public function getRedirectTarget() {
		return $this->redirectTarget;
	}

	/**
	 * Extra key that should be present in the parser cache key.
	 * @warning Consider registering your additional options with the
	 *  ParserOptionsRegister hook instead of using this method.
	 * @param string $key
	 */
	public function addExtraKey( $key ) {
		$this->mExtraKey .= '!' . $key;
	}

	/**
	 * Get the identity of the user for whom the parse is made.
	 * @since 1.36
	 * @return UserIdentity
	 */
	public function getUserIdentity(): UserIdentity {
		return $this->mUser;
	}

	/**
	 * @param UserIdentity $user
	 * @param Language|null $lang
	 */
	public function __construct( UserIdentity $user, $lang = null ) {
		if ( $lang === null ) {
			global $wgLang;
			StubObject::unstub( $wgLang );
			$lang = $wgLang;
		}
		$this->initialiseFromUser( $user, $lang );
	}

	/**
	 * Get a ParserOptions object for an anonymous user
	 * @since 1.27
	 * @return ParserOptions
	 */
	public static function newFromAnon() {
		return new ParserOptions( MediaWikiServices::getInstance()->getUserFactory()->newAnonymous(),
			MediaWikiServices::getInstance()->getContentLanguage() );
	}

	/**
	 * Get a ParserOptions object from a given user.
	 * Language will be taken from $wgLang.
	 *
	 * @since 1.13
	 * @param UserIdentity $user
	 * @return ParserOptions
	 */
	public static function newFromUser( $user ) {
		return new ParserOptions( $user );
	}

	/**
	 * Get a ParserOptions object from a given user and language
	 *
	 * @since 1.19
	 * @param UserIdentity $user
	 * @param Language $lang
	 * @return ParserOptions
	 */
	public static function newFromUserAndLang( UserIdentity $user, Language $lang ) {
		return new ParserOptions( $user, $lang );
	}

	/**
	 * Get a ParserOptions object from a IContextSource object
	 *
	 * @since 1.19
	 * @param IContextSource $context
	 * @return ParserOptions
	 */
	public static function newFromContext( IContextSource $context ) {
		$contextUser = $context->getUser();

		// Use the stashed temporary account name instead of an IP address as the user for the ParserOptions
		// (if a stashed name is set). This is so that magic words like {{REVISIONUSER}} show the temporary account
		// name instead of IP address.
		$tempUserCreator = MediaWikiServices::getInstance()->getTempUserCreator();
		if ( $tempUserCreator->isEnabled() && IPUtils::isIPAddress( $contextUser->getName() ) ) {
			// We do not attempt to acquire a temporary account name if no name is stashed, as this may be called in
			// contexts (such as the parse API) where the user will not be performing an edit on their next action
			// and therefore would be increasing the rate limit unnecessarily.
			$tempName = $tempUserCreator->getStashedName( $context->getRequest()->getSession() );
			if ( $tempName !== null ) {
				$contextUser = UserIdentityValue::newAnonymous( $tempName );
			}
		}

		return new ParserOptions( $contextUser, $context->getLanguage() );
	}

	/**
	 * Creates a "canonical" ParserOptions object
	 *
	 * For historical reasons, certain options may have default values
	 * that are different from the canonical values used for caching;
	 * this is done to avoid expiring the entirety of an existing
	 * ParserCache's contents in production when a default changes.
	 * See the discussion in ::getDefaults() for more details.
	 *
	 * @since 1.30
	 * @since 1.32 Added string and IContextSource as options for the first parameter
	 * @since 1.36 UserIdentity is also allowed
	 * @deprecated since 1.38. Use ::newFromContext, ::newFromAnon or ::newFromUserAndLang instead.
	 *   Canonical ParserOptions are now exactly the same as non-canonical.
	 * @param IContextSource|string|UserIdentity $context
	 *  - If an IContextSource, the options are initialized based on the source's UserIdentity and Language.
	 *  - If the string 'canonical', the options are initialized with an anonymous user and
	 *    the content language.
	 *  - If a UserIdentity, the options are initialized for that UserIdentity
	 *    'userlang' is taken from the $userLang parameter, defaulting to $wgLang if that is null.
	 * @param Language|StubObject|null $userLang (see above)
	 * @return ParserOptions
	 */
	public static function newCanonical( $context, $userLang = null ) {
		if ( $context instanceof IContextSource ) {
			$ret = self::newFromContext( $context );
		} elseif ( $context === 'canonical' ) {
			$ret = self::newFromAnon();
		} elseif ( $context instanceof UserIdentity ) {
			$ret = new self( $context, $userLang );
		} else {
			throw new InvalidArgumentException(
				'$context must be an IContextSource, the string "canonical", or a UserIdentity'
			);
		}
		return $ret;
	}

	/**
	 * Reset static caches
	 * @internal For testing
	 */
	public static function clearStaticCache() {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( __METHOD__ . ' is just for testing' );
		}
		self::$defaults = null;
		self::$lazyOptions = null;
		self::$cacheVaryingOptionsHash = null;
	}

	/**
	 * Get default option values.
	 * @warning If you change the default for an existing option, existing
	 *  parser cache entries will match even though they were generated with
	 *  the old default value.  Usually this is a feature, since you don't
	 *  want to invalid the entire parser cache at once.  But to avoid bugs,
	 *  you'll need to handle this somehow, either by tolerating the old
	 *  value until the parser cache expires or by manually rejecting
	 *  parser cache entries created with the old default (e.g. with the
	 *  RejectParserCacheValue hook) although caution is warranted to avoid
	 *  effectively invalidating the entire cache at once.
	 * @return array
	 */
	private static function getDefaults() {
		$services = MediaWikiServices::getInstance();
		$mainConfig = $services->getMainConfig();
		$interwikiMagic = $mainConfig->get( MainConfigNames::InterwikiMagic );
		$allowExternalImages = $mainConfig->get( MainConfigNames::AllowExternalImages );
		$allowExternalImagesFrom = $mainConfig->get( MainConfigNames::AllowExternalImagesFrom );
		$enableImageWhitelist = $mainConfig->get( MainConfigNames::EnableImageWhitelist );
		$allowSpecialInclusion = $mainConfig->get( MainConfigNames::AllowSpecialInclusion );
		$maxArticleSize = $mainConfig->get( MainConfigNames::MaxArticleSize );
		$maxPPNodeCount = $mainConfig->get( MainConfigNames::MaxPPNodeCount );
		$maxTemplateDepth = $mainConfig->get( MainConfigNames::MaxTemplateDepth );
		$maxPPExpandDepth = $mainConfig->get( MainConfigNames::MaxPPExpandDepth );
		$cleanSignatures = $mainConfig->get( MainConfigNames::CleanSignatures );
		$externalLinkTarget = $mainConfig->get( MainConfigNames::ExternalLinkTarget );
		$expensiveParserFunctionLimit = $mainConfig->get( MainConfigNames::ExpensiveParserFunctionLimit );
		$enableMagicLinks = $mainConfig->get( MainConfigNames::EnableMagicLinks );
		$languageConverterFactory = $services->getLanguageConverterFactory();
		$userOptionsLookup = $services->getUserOptionsLookup();
		$contentLanguage = $services->getContentLanguage();

		if ( self::$defaults === null ) {
			// *UPDATE* ParserOptions::matches() if any of this changes as needed
			// Options matching the defaults are not included in the
			// ParserCache key.  This is done to allow adding new parser
			// options without invalidating the entire ParserCache, as long
			// as those options are set to the default value specified here.
			// It also allows deliberately serving old data from the parser
			// cache when a default value is updated, which avoids the
			// performance hit of invalidating the entire cache at once, but
			// beware that changing the defaults here will *not* mean that
			// all entries fetched from the cache will have been generated
			// with the new default, at least until the current parser cache
			// expires.
			self::$defaults = [
				'dateformat' => null,
				'interfaceMessage' => false,
				'isMessage' => false,
				'targetLanguage' => null,
				'removeComments' => true,
				'suppressTOC' => false,
				'suppressSectionEditLinks' => false,
				'collapsibleSections' => false,
				'enableLimitReport' => false,
				'preSaveTransform' => true,
				'isPreview' => false,
				'isSectionPreview' => false,
				'printable' => false,
				'allowUnsafeRawHtml' => true,
				'wrapclass' => 'mw-parser-output',
				'currentRevisionRecordCallback' => [ Parser::class, 'statelessFetchRevisionRecord' ],
				'templateCallback' => [ Parser::class, 'statelessFetchTemplate' ],
				'speculativeRevIdCallback' => null,
				'speculativeRevId' => null,
				'speculativePageIdCallback' => null,
				'speculativePageId' => null,
				'useParsoid' => false,
			];

			self::$cacheVaryingOptionsHash = self::$initialCacheVaryingOptionsHash;
			self::$lazyOptions = self::$initialLazyOptions;

			( new HookRunner( $services->getHookContainer() ) )->onParserOptionsRegister(
				self::$defaults,
				self::$cacheVaryingOptionsHash,
				self::$lazyOptions
			);

			ksort( self::$cacheVaryingOptionsHash );
		}

		// Unit tests depend on being able to modify the globals at will
		return self::$defaults + [
			'interwikiMagic' => $interwikiMagic,
			'allowExternalImages' => $allowExternalImages,
			'allowExternalImagesFrom' => $allowExternalImagesFrom,
			'enableImageWhitelist' => $enableImageWhitelist,
			'allowSpecialInclusion' => $allowSpecialInclusion,
			'maxIncludeSize' => $maxArticleSize * 1024,
			'maxPPNodeCount' => $maxPPNodeCount,
			'maxPPExpandDepth' => $maxPPExpandDepth,
			'maxTemplateDepth' => $maxTemplateDepth,
			'expensiveParserFunctionLimit' => $expensiveParserFunctionLimit,
			'externalLinkTarget' => $externalLinkTarget,
			'cleanSignatures' => $cleanSignatures,
			'disableContentConversion' => $languageConverterFactory->isConversionDisabled(),
			'disableTitleConversion' => $languageConverterFactory->isLinkConversionDisabled(),
			// FIXME: The fallback to false for enableMagicLinks is a band-aid to allow
			// the phpunit entrypoint patch (I82045c207738d152d5b0006f353637cfaa40bb66)
			// to be merged.
			// It is possible that a test somewhere is globally resetting $wgEnableMagicLinks
			// to null, or that ParserOptions is somehow similarly getting reset in such a way
			// that $enableMagicLinks ends up as null rather than an array. This workaround
			// seems harmless, but would be nice to eventually fix the underlying issue.
			'magicISBNLinks' => $enableMagicLinks['ISBN'] ?? false,
			'magicPMIDLinks' => $enableMagicLinks['PMID'] ?? false,
			'magicRFCLinks' => $enableMagicLinks['RFC'] ?? false,
			'thumbsize' => $userOptionsLookup->getDefaultOption( 'thumbsize' ),
			'userlang' => $contentLanguage,
		];
	}

	/**
	 * Get user options
	 *
	 * @param UserIdentity $user
	 * @param Language $lang
	 */
	private function initialiseFromUser( UserIdentity $user, Language $lang ) {
		// Initially lazy loaded option defaults must not be taken into account,
		// otherwise lazy loading does not work. Setting a default for lazy option
		// is useful for matching with canonical options.
		$this->options = $this->nullifyLazyOption( self::getDefaults() );

		$this->mUser = $user;
		$services = MediaWikiServices::getInstance();
		$optionsLookup = $services->getUserOptionsLookup();
		$this->options['thumbsize'] = $optionsLookup->getOption( $user, 'thumbsize' );
		$this->options['userlang'] = $lang;
	}

	/**
	 * Check if these options match that of another options set
	 *
	 * This ignores report limit settings that only affect HTML comments
	 *
	 * @param ParserOptions $other
	 * @return bool
	 * @since 1.25
	 */
	public function matches( ParserOptions $other ) {
		// Compare most options
		$options = array_keys( $this->options );
		$options = array_diff( $options, [
			'enableLimitReport', // only affects HTML comments
			'tidy', // Has no effect since 1.35; removed in 1.36
		] );
		foreach ( $options as $option ) {
			// Resolve any lazy options
			$this->lazyLoadOption( $option );
			$other->lazyLoadOption( $option );

			$o1 = $this->optionToString( $this->options[$option] );
			$o2 = $this->optionToString( $other->options[$option] );
			if ( $o1 !== $o2 ) {
				return false;
			}
		}

		// Compare most other fields
		foreach ( ( new ReflectionClass( $this ) )->getProperties() as $property ) {
			$field = $property->getName();
			if ( $property->isStatic() ) {
				continue;
			}
			if ( in_array( $field, [
				'options', // Already checked above
				'onAccessCallback', // only used for ParserOutput option tracking
			] ) ) {
				continue;
			}

			if ( !is_object( $this->$field ) && $this->$field !== $other->$field ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param ParserOptions $other
	 * @return bool Whether the cache key relevant options match those of $other
	 * @since 1.33
	 */
	public function matchesForCacheKey( ParserOptions $other ) {
		foreach ( self::allCacheVaryingOptions() as $option ) {
			// Populate any lazy options
			$this->lazyLoadOption( $option );
			$other->lazyLoadOption( $option );

			$o1 = $this->optionToString( $this->options[$option] );
			$o2 = $this->optionToString( $other->options[$option] );
			if ( $o1 !== $o2 ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Registers a callback for tracking which ParserOptions which are used.
	 *
	 * @since 1.16
	 * @param callable|null $callback New callback
	 * @return callable|null The old callback, if any
	 */
	public function registerWatcher( $callback ) {
		$oldCallback = $this->onAccessCallback;
		$this->onAccessCallback = $callback;
		return $oldCallback;
	}

	/**
	 * Record that an option was internally accessed.
	 *
	 * This calls the watcher set by ParserOptions::registerWatcher().
	 * Typically, the watcher callback is ParserOutput::recordOption().
	 * The information registered this way is consumed by ParserCache::save().
	 *
	 * @param string $optionName Name of the option
	 */
	private function optionUsed( $optionName ) {
		if ( $this->onAccessCallback ) {
			( $this->onAccessCallback )( $optionName );
		}
	}

	/**
	 * Return all option keys that vary the options hash
	 * @since 1.30
	 * @return string[]
	 */
	public static function allCacheVaryingOptions() {
		return array_keys( array_filter( self::getCacheVaryingOptionsHash() ) );
	}

	/**
	 * Convert an option to a string value
	 * @param mixed $value
	 * @return string
	 */
	private function optionToString( $value ) {
		if ( $value === true ) {
			return '1';
		} elseif ( $value === false ) {
			return '0';
		} elseif ( $value === null ) {
			return '';
		} elseif ( $value instanceof Language ) {
			return $value->getCode();
		} elseif ( is_array( $value ) ) {
			return '[' . implode( ',', array_map( $this->optionToString( ... ), $value ) ) . ']';
		} else {
			return (string)$value;
		}
	}

	/**
	 * Generate a hash string with the values set on these ParserOptions
	 * for the keys given in the array.
	 * This will be used as part of the hash key for the parser cache,
	 * so users sharing the options with vary for the same page share
	 * the same cached data safely.
	 *
	 * @since 1.17
	 * @param string[] $forOptions
	 * @param Title|null $title Used to get the content language of the page (since r97636)
	 * @return string Page rendering hash
	 */
	public function optionsHash( $forOptions, $title = null ) {
		$renderHashAppend = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::RenderHashAppend );

		$inCacheKey = self::allCacheVaryingOptions();

		// Resolve any lazy options
		$lazyOpts = array_intersect( $forOptions,
			$inCacheKey, array_keys( self::getLazyOptions() ) );
		foreach ( $lazyOpts as $k ) {
			$this->lazyLoadOption( $k );
		}

		$options = $this->options;
		$defaults = self::getDefaults();

		// We only include used options with non-canonical values in the key
		// so adding a new option doesn't invalidate the entire parser cache.
		// The drawback to this is that changing the default value of an option
		// requires manual invalidation of existing cache entries, as mentioned
		// in the docs on the relevant methods and hooks.
		$values = [];
		foreach ( array_intersect( $inCacheKey, $forOptions ) as $option ) {
			$v = $this->optionToString( $options[$option] );
			$d = $this->optionToString( $defaults[$option] );
			if ( $v !== $d ) {
				$values[] = "$option=$v";
			}
		}

		$confstr = $values ? implode( '!', $values ) : 'canonical';

		// add in language specific options, if any
		// @todo FIXME: This is just a way of retrieving the url/user preferred variant
		$services = MediaWikiServices::getInstance();
		$lang = $title ? $title->getPageLanguage() : $services->getContentLanguage();
		$converter = $services->getLanguageConverterFactory()->getLanguageConverter( $lang );
		$confstr .= $converter->getExtraHashOptions();

		$confstr .= $renderHashAppend;

		if ( $this->mExtraKey != '' ) {
			$confstr .= $this->mExtraKey;
		}

		$user = $services->getUserFactory()->newFromUserIdentity( $this->getUserIdentity() );
		// Give a chance for extensions to modify the hash, if they have
		// extra options or other effects on the parser cache.
		( new HookRunner( $services->getHookContainer() ) )->onPageRenderingHash(
			$confstr,
			$user,
			$forOptions
		);

		// Make it a valid memcached key fragment
		$confstr = str_replace( ' ', '_', $confstr );

		return $confstr;
	}

	/**
	 * Test whether these options are safe to cache
	 * @param string[]|null $usedOptions the list of options actually used in the parse. Defaults to all options.
	 * @return bool
	 * @since 1.30
	 */
	public function isSafeToCache( ?array $usedOptions = null ) {
		$defaults = self::getDefaults();
		$inCacheKey = self::getCacheVaryingOptionsHash();
		$usedOptions ??= array_keys( $this->options );
		foreach ( $usedOptions as $option ) {
			if ( empty( $inCacheKey[$option] ) && empty( self::$callbacks[$option] ) ) {
				$v = $this->optionToString( $this->options[$option] ?? null );
				$d = $this->optionToString( $defaults[$option] ?? null );
				if ( $v !== $d ) {
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Sets a hook to force that a page exists, and sets a current revision callback to return
	 * a revision with custom content when the current revision of the page is requested.
	 *
	 * @param PageIdentity $page
	 * @param Content $content
	 * @param UserIdentity $user The user that the fake revision is attributed to
	 * @param int $currentRevId
	 *
	 * @return ScopedCallback to unset the hook
	 * @internal since 1.44, this method is no longer considered safe to call
	 * by extensions. It may be removed or changed in a backwards incompatible
	 * way in 1.45 or later.
	 *
	 * @since 1.25
	 */
	public function setupFakeRevision( $page, $content, $user, $currentRevId = 0 ) {
		$oldCallback = $this->setCurrentRevisionRecordCallback(
			function ( $titleToCheck, $parser = null )
			use ( $page, $content, $user, $currentRevId, &$oldCallback )
			{
				if ( $titleToCheck->isSamePageAs( $page ) ) {
					if ( $page->exists() ) {
						$pageId = $page->getId();

						if ( $currentRevId ) {
							$parentRevision = $currentRevId;
						} elseif ( $page instanceof Title ) {
							$parentRevision = $page->getLatestRevID();
						} elseif ( $page instanceof PageRecord ) {
							$parentRevision = $page->getLatest();
						} else {
							$parentRevision = 0;
						}
					} else {
						$pageId = $this->getSpeculativePageId() ?: 0;
						$parentRevision = 0;
						$page = new PageIdentityValue(
							$pageId,
							$page->getNamespace(),
							$page->getDBkey(),
							$page->getWikiId()
						);
					}

					$revRecord = new MutableRevisionRecord( $page );
					$revRecord->setContent( SlotRecord::MAIN, $content )
						->setUser( $user )
						->setTimestamp( MWTimestamp::now( TS_MW ) )
						->setId( 0 )
						->setPageId( $pageId )
						->setParentId( $parentRevision );
					return $revRecord;
				} else {
					return $oldCallback( $titleToCheck, $parser );
				}
			}
		);

		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		$hookScope = $hookContainer->scopedRegister(
			'TitleExists',
			static function ( Title $titleToCheck, &$exists ) use ( $page ) {
				if ( $titleToCheck->isSamePageAs( $page ) ) {
					$exists = true;
				}
			}
		);

		$linkCache = MediaWikiServices::getInstance()->getLinkCache();
		$linkCache->clearBadLink( $page );

		return new ScopedCallback( function () use ( $page, $hookScope, $linkCache, $oldCallback ) {
			ScopedCallback::consume( $hookScope );
			$linkCache->clearLink( $page );
			$this->setCurrentRevisionRecordCallback( $oldCallback );
		} );
	}

	/**
	 * Returns reason for rendering the content. This human-readable, intended for logging and debugging only.
	 * Expected values include "edit", "view", "purge", "LinksUpdate", etc.
	 */
	public function getRenderReason(): string {
		return $this->renderReason;
	}

	/**
	 * Sets reason for rendering the content. This human-readable, intended for logging and debugging only.
	 * Expected values include "edit", "view", "purge", "LinksUpdate", etc.
	 */
	public function setRenderReason( string $renderReason ): void {
		$this->renderReason = $renderReason;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ParserOptions::class, 'ParserOptions' );

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 */
