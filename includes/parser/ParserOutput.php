<?php
declare( strict_types = 1 );

/**
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
 */

namespace MediaWiki\Parser;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Edit\ParsoidRenderID;
use MediaWiki\Json\JsonDeserializable;
use MediaWiki\Json\JsonDeserializableTrait;
use MediaWiki\Json\JsonDeserializer;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Output\OutputPage;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Title\TitleValue;
use UnexpectedValueException;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Core\ContentMetadataCollector;
use Wikimedia\Parsoid\Core\ContentMetadataCollectorCompat;
use Wikimedia\Parsoid\Core\LinkTarget as ParsoidLinkTarget;
use Wikimedia\Parsoid\Core\TOCData;

/**
 * ParserOutput is a rendering of a Content object or a message.
 * Content objects and messages often contain wikitext, but not always.
 *
 * `ParserOutput` object combine the HTML rendering of Content objects
 * or messages, available via `::getRawText()`, with various bits of
 * metadata generated during rendering, which may include categories,
 * links, page properties, and extension data, among others.
 *
 * `ParserOutput` objects corresponding to the content of page revisions
 * are created by the `ParserOutputAccess` service, which
 * automatically caches them via `ParserCache` where appropriate and
 * produces new output via `ContentHandler` as needed.
 *
 * In addition, wikitext from system messages as well as odd bits of
 * wikitext rendered to create special pages and other UX elements are
 * rendered to `ParserOutput` objects.  In these cases the metadata
 * from the `ParserOutput` is generally discarded and the
 * `ParserOutput` is not cached.  `ParserOptions::setIsMessage(true)`
 * is usually used when rendering system messages.
 * `ParserOptions::setInterfaceMessage(true)` is usually used when
 * rendering system messages in the user interface language,
 * and occasionally for the other odd bits of wikitext as well.
 * These options are not used as consistently as one would hope.
 *
 * A `ParserOutput` object corresponding to a given revision may be a
 * combination of the renderings of multiple "slots":
 * the Multi-Content Revisions (MCR) work allows articles to be
 * composed from multiple `Content` objects.  Each `Content` renders
 * to a `ParserOutput`, and those `ParserOutput`s are merged by
 * `RevisionRenderer::combineSlotOutput()` to create the final article
 * output.
 *
 * Similarly, `OutputPage` maintains metadata overlapping
 * with the metadata kept by `ParserOutput` (T301020) and may merge
 * several `ParserOutput`s using `OutputPage::addParserOutput()` to
 * create the final output page.  Parsoid parses certain transclusions
 * in independent top-level contexts using
 * `Parser::parseExtensionTagAsTopLevelDoc()` and these also result in
 * `ParserOutput`s which are merged via
 * `ParserOutput::collectMetadata()`.
 *
 * Future plans for incremental parsing and asynchronous rendering may
 * result in several of these component `ParserOutput` objects being
 * cached independently and then recombined asynchronously, so
 * operations on `ParserOutput` objects should be compatible with that
 * model (T300979).
 *
 * @ingroup Parser
 */
class ParserOutput extends CacheTime implements ContentMetadataCollector {
	use JsonDeserializableTrait;
	// This is used to break cyclic dependencies and allow a measure
	// of compatibility when new methods are added to ContentMetadataCollector
	// by Parsoid.
	use ContentMetadataCollectorCompat;

	/**
	 * Feature flags to indicate to extensions that MediaWiki core supports and
	 * uses getText() stateless transforms.
	 *
	 * @since 1.31
	 */
	public const SUPPORTS_STATELESS_TRANSFORMS = 1;

	/**
	 * @since 1.31
	 */
	public const SUPPORTS_UNWRAP_TRANSFORM = 1;

	/**
	 * @internal
	 * @since 1.38
	 */
	public const MW_MERGE_STRATEGY_KEY = '_mw-strategy';

	/**
	 * Merge strategy to use for ParserOutput accumulators: "union"
	 * means that values are strings, stored as a set, and exposed as
	 * a PHP associative array mapping from values to `true`.
	 *
	 * This constant should be treated as @internal until we expose
	 * alternative merge strategies for external use.
	 * @internal
	 * @since 1.38
	 */
	public const MW_MERGE_STRATEGY_UNION = 'union';

	/**
	 * @var string|null The output text
	 */
	private $mRawText = null;

	/**
	 * @var array<string,string> Array mapping interwiki prefix to (non DB key) Titles (e.g. 'fr' => 'Test page')
	 */
	private $mLanguageLinkMap = [];

	/**
	 * @var array<string,string> Map of category names to sort keys
	 */
	private $mCategories = [];

	/**
	 * @var array<string,string> Page status indicators, usually displayed in top-right corner.
	 */
	private array $mIndicators = [];

	/**
	 * @var string Title text of the chosen language variant, as HTML.
	 */
	private $mTitleText;

	/**
	 * @var array<int,array<string,int>> 2-D map of NS/DBK to ID for the links in the document.
	 *  ID=zero for broken.
	 */
	private $mLinks = [];

	/**
	 * @var array<string,int> Keys are DBKs for the links to special pages in the document.
	 * @since 1.35
	 */
	private $mLinksSpecial = [];

	/**
	 * @var array<int,array<string,int>> 2-D map of NS/DBK to ID for the template references.
	 *  ID=zero for broken.
	 */
	private $mTemplates = [];

	/**
	 * @var array<int,array<string,int>> 2-D map of NS/DBK to rev ID for the template references.
	 *  ID=zero for broken.
	 */
	private $mTemplateIds = [];

	/**
	 * @var array<string,int> DB keys of the images used, in the array key only
	 */
	private $mImages = [];

	/**
	 * @var array<string,array<string,string>> DB keys of the images used mapped to sha1 and MW timestamp.
	 */
	private $mFileSearchOptions = [];

	/**
	 * @var array<string,int> External link URLs, in the key only.
	 */
	private array $mExternalLinks = [];

	/**
	 * @var array<string,array<string,int>> 2-D map of prefix/DBK (in keys only)
	 *  for the inline interwiki links in the document.
	 */
	private $mInterwikiLinks = [];

	/**
	 * @var array<int,array<string,bool>> 2-D map of NS/DBK to true for #ifexist and similar
	 */
	private $existenceLinks = [];

	/**
	 * @var bool Show a new section link?
	 */
	private $mNewSection = false;

	/**
	 * @var bool Hide the new section link?
	 */
	private $mHideNewSection = false;

	/**
	 * @var bool No gallery on category page? (__NOGALLERY__).
	 */
	private $mNoGallery = false;

	/**
	 * @var string[] Items to put in the <head> section
	 */
	private $mHeadItems = [];

	/**
	 * @var array<string,true> Modules to be loaded by ResourceLoader
	 */
	private $mModuleSet = [];

	/**
	 * @var array<string,true> Modules of which only the CSS will be loaded by ResourceLoader.
	 */
	private $mModuleStyleSet = [];

	/**
	 * @var array JavaScript config variable for mw.config combined with this page.
	 */
	private $mJsConfigVars = [];

	/**
	 * @var array<string,int> Warning text to be returned to the user.
	 *  Wikitext formatted, in the key only.
	 * @deprecated since 1.45; use ::$mWarningMsgs instead
	 */
	private $mWarnings = [];

	/**
	 * @var array<string,MessageValue> *Unformatted* warning messages and
	 * arguments to be returned to the user.
	 */
	private $mWarningMsgs = [];

	/**
	 * @var ?TOCData Table of contents data, or null if it hasn't been set.
	 */
	private $mTOCData;

	/**
	 * @var array Name/value pairs to be cached in the DB.
	 */
	private $mProperties = [];

	/**
	 * @var ?string Timestamp of the revision.
	 */
	private $mTimestamp;

	/**
	 * @var bool Whether OOUI should be enabled.
	 */
	private $mEnableOOUI = false;

	/**
	 * @var bool Whether the index policy has been set to 'index'.
	 */
	private $mIndexSet = false;

	/**
	 * @var bool Whether the index policy has been set to 'noindex'.
	 */
	private $mNoIndexSet = false;

	/**
	 * @var array extra data used by extensions.
	 */
	private $mExtensionData = [];

	/**
	 * @var array Parser limit report data.
	 */
	private $mLimitReportData = [];

	/** @var array Parser limit report data for JSON */
	private $mLimitReportJSData = [];

	/** @var string Debug message added by ParserCache */
	private $mCacheMessage = '';

	/**
	 * @var array Timestamps for getTimeProfile().
	 */
	private $mParseStartTime = [];

	/**
	 * @var array Durations for getTimeProfile().
	 */
	private $mTimeProfile = [];

	/**
	 * @var bool Whether to emit X-Frame-Options: DENY.
	 * This controls if anti-clickjacking / frame-breaking headers will
	 * be sent. This should be done for pages where edit actions are possible.
	 */
	private $mPreventClickjacking = false;

	/**
	 * @var string[] Extra script-src for CSP
	 */
	private $mExtraScriptSrcs = [];

	/**
	 * @var string[] Extra default-src for CSP [Everything but script and style]
	 */
	private $mExtraDefaultSrcs = [];

	/**
	 * @var string[] Extra style-src for CSP
	 */
	private $mExtraStyleSrcs = [];

	/**
	 * @var array<string,true> Generic flags.
	 */
	private $mFlags = [];

	private const SPECULATIVE_FIELDS = [
		'speculativePageIdUsed',
		'mSpeculativeRevId',
		'revisionTimestampUsed',
	];

	/** @var int|null Assumed rev ID for {{REVISIONID}} if no revision is set */
	private $mSpeculativeRevId;
	/** @var int|null Assumed page ID for {{PAGEID}} if no revision is set */
	private $speculativePageIdUsed;
	/** @var string|null Assumed rev timestamp for {{REVISIONTIMESTAMP}} if no revision is set */
	private $revisionTimestampUsed;

	/** @var string|null SHA-1 base 36 hash of any self-transclusion */
	private $revisionUsedSha1Base36;

	/** string CSS classes to use for the wrapping div, stored in the array keys.
	 * If no class is given, no wrapper is added.
	 * @var array<string,true>
	 */
	private $mWrapperDivClasses = [];

	/** @var int Upper bound of expiry based on parse duration */
	private $mMaxAdaptiveExpiry = INF;

	// finalizeAdaptiveCacheExpiry() uses TTL = MAX( m * PARSE_TIME + b, MIN_AR_TTL)
	// Current values imply that m=3933.333333 and b=-333.333333
	// See https://www.nngroup.com/articles/website-response-times/
	private const PARSE_FAST_SEC = 0.100; // perceived "fast" page parse
	private const PARSE_SLOW_SEC = 1.0; // perceived "slow" page parse
	private const FAST_AR_TTL = 60; // adaptive TTL for "fast" pages
	private const SLOW_AR_TTL = 3600; // adaptive TTL for "slow" pages
	private const MIN_AR_TTL = 15; // min adaptive TTL (for pool counter, and edit stashing)

	/**
	 * @param string|null $text HTML. Use null to indicate that this ParserOutput contains only
	 *        meta-data, and the HTML output is undetermined, as opposed to empty. Passing null
	 *        here causes hasText() to return false. In 1.39 the default value changed from ''
	 *        to null.
	 * @param array $languageLinks
	 * @param array $categoryLinks
	 * @param bool $unused
	 * @param string $titletext
	 */
	public function __construct( ?string $text = null, array $languageLinks = [], array $categoryLinks = [],
		$unused = false, $titletext = ''
	) {
		$this->mRawText = $text;
		$this->mCategories = $categoryLinks;
		$this->mTitleText = $titletext;
		foreach ( $languageLinks as $ll ) {
			$this->addLanguageLink( $ll );
		}
		// If the content handler does not specify an alternative (by
		// calling ::resetParseStartTime() at a later point) then use
		// the creation of the ParserOutput as the "start of parse" time.
		$this->resetParseStartTime();
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
	public function hasText(): bool {
		return ( $this->mRawText !== null );
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
		if ( $this->mRawText === null ) {
			throw new LogicException( 'This ParserOutput contains no text!' );
		}

		return $this->mRawText;
	}

	/**
	 * Get the output HTML
	 *
	 * T293512: in the future, ParserOutput::getText() will be deprecated in favor of invoking
	 * the OutputTransformPipeline directly on a ParserOutput.
	 * @param array $options (since 1.31) Transformations to apply to the HTML
	 * 	- allowClone: (bool) Whether to clone the ParserOutput before
	 *     applying transformations. Default is false.
	 *  - allowTOC: (bool) Show the TOC, assuming there were enough headings
	 *     to generate one and `__NOTOC__` wasn't used. Default is true,
	 *     but might be statefully overridden.
	 *  - injectTOC: (bool) Replace the TOC_PLACEHOLDER with TOC contents;
	 *     otherwise the marker will be left in the article (and the skin
	 *     will be responsible for replacing or removing it).  Default is
	 *     true.
	 *  - enableSectionEditLinks: (bool) Include section edit links, assuming
	 *     section edit link tokens are present in the HTML. Default is true,
	 *     but might be statefully overridden.
	 *  - userLang: (Language) Language object used for localizing UX messages,
	 *    for example the heading of the table of contents. If omitted, will
	 *    use the language of the main request context.
	 *  - skin: (Skin) Skin object used for transforming section edit links.
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
	 *  - absoluteURLs: (bool) use absolute URLs in all links. Default: false
	 *  - includeDebugInfo: (bool) render PP limit report in HTML. Default: false
	 * @return string HTML
	 * @return-taint escaped
	 * @deprecated since 1.42, this method has side-effects on the ParserOutput
	 *  (see T353257) and so should be avoided in favor of directly invoking
	 *  the default output pipeline on a ParserOutput; for now, use of
	 *  ::runOutputPipeline() is preferred to ensure that ParserOptions are
	 *  available.
	 */
	public function getText( $options = [] ) {
		wfDeprecated( __METHOD__, '1.42' );
		$oldText = $this->mRawText; // T353257
		$options += [ 'allowClone' => false ];
		$po = $this->runPipelineInternal( null, $options );
		$newText = $po->getContentHolderText();
		// T353257: for back-compat only mutations to metadata performed by
		// the pipeline should be preserved; mutations to $mText should be
		// discarded.
		$this->setRawText( $oldText );
		return $newText;
	}

	/**
	 * @unstable This method is transitional and will be replaced by a method
	 * in another class, maybe ContentRenderer.  It allows us to break our
	 * porting work into two steps; in the first we bring ParserOptions to
	 * to each ::getText() callsite to ensure it is made available to the
	 * postprocessing pipeline.  In the second we move this functionality
	 * into the Content hierarchy and out of ParserOutput, which should become
	 * a pure value object.
	 *
	 * @param ParserOptions $popts
	 * @param array $options (since 1.31) Transformations to apply to the HTML
	 * 	 - allowClone: (bool) Whether to clone the ParserOutput before
	 *     applying transformations. Default is true.
	 *  - allowTOC: (bool) Show the TOC, assuming there were enough headings
	 *     to generate one and `__NOTOC__` wasn't used. Default is true,
	 *     but might be statefully overridden.
	 *  - injectTOC: (bool) Replace the TOC_PLACEHOLDER with TOC contents;
	 *     otherwise the marker will be left in the article (and the skin
	 *     will be responsible for replacing or removing it).  Default is
	 *     true.
	 *  - enableSectionEditLinks: (bool) Include section edit links, assuming
	 *     section edit link tokens are present in the HTML. Default is true,
	 *     but might be statefully overridden.
	 *  - userLang: (Language) Language object used for localizing UX messages,
	 *    for example the heading of the table of contents. If omitted, will
	 *    use the language of the main request context.
	 *  - skin: (Skin) Skin object used for transforming section edit links.
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
	 *  - absoluteURLs: (bool) use absolute URLs in all links. Default: false
	 *  - includeDebugInfo: (bool) render PP limit report in HTML. Default: false
	 *  It is planned to eventually deprecate this $options array and to be able to
	 *  pass its content in the $popts ParserOptions.
	 * @return ParserOutput
	 */
	public function runOutputPipeline( ParserOptions $popts, array $options = [] ): ParserOutput {
		return $this->runPipelineInternal( $popts, $options );
	}

	/**
	 * Temporary helper method to allow running the pipeline with null $popts for now, although
	 * passing a null ParserOptions is a temporary backward-compatibility hack and will be deprecated.
	 */
	private function runPipelineInternal( ?ParserOptions $popts, array $options = [] ): ParserOutput {
		$pipeline = MediaWikiServices::getInstance()->getDefaultOutputPipeline();
		$options += [
			'allowClone' => true,
			'allowTOC' => true,
			'injectTOC' => true,
			'enableSectionEditLinks' => !$this->getOutputFlag( ParserOutputFlags::NO_SECTION_EDIT_LINKS ),
			'userLang' => null,
			'skin' => null,
			'unwrap' => false,
			'wrapperDivClass' => $this->getWrapperDivClass(),
			'deduplicateStyles' => true,
			'absoluteURLs' => false,
			'includeDebugInfo' => false,
			'isParsoidContent' => PageBundleParserOutputConverter::hasPageBundle( $this ),
		];
		return $pipeline->run( $this, $popts, $options );
	}

	/**
	 * Adds a comment notice about cache state to the text of the page
	 * @param string $msg
	 * @internal used by ParserCache
	 */
	public function addCacheMessage( string $msg ): void {
		$this->mCacheMessage .= $msg;
	}

	/**
	 * Add a CSS class to use for the wrapping div. If no class is given, no wrapper is added.
	 *
	 * @param string $class
	 */
	public function addWrapperDivClass( $class ): void {
		$this->mWrapperDivClasses[$class] = true;
	}

	/**
	 * Clears the CSS class to use for the wrapping div, effectively disabling the wrapper div
	 * until addWrapperDivClass() is called.
	 */
	public function clearWrapperDivClass(): void {
		$this->mWrapperDivClasses = [];
	}

	/**
	 * Returns the class (or classes) to be used with the wrapper div for this output.
	 * If there is no wrapper class given, no wrapper div should be added.
	 * The wrapper div is added automatically by getText().
	 */
	public function getWrapperDivClass(): string {
		return implode( ' ', array_keys( $this->mWrapperDivClasses ) );
	}

	/**
	 * @param int $id
	 * @since 1.28
	 */
	public function setSpeculativeRevIdUsed( $id ): void {
		$this->mSpeculativeRevId = $id;
	}

	/**
	 * @return int|null
	 * @since 1.28
	 */
	public function getSpeculativeRevIdUsed(): ?int {
		return $this->mSpeculativeRevId;
	}

	/**
	 * @param int $id
	 * @since 1.34
	 */
	public function setSpeculativePageIdUsed( $id ): void {
		$this->speculativePageIdUsed = $id;
	}

	/**
	 * @return int|null
	 * @since 1.34
	 */
	public function getSpeculativePageIdUsed() {
		return $this->speculativePageIdUsed;
	}

	/**
	 * @param string $timestamp TS_MW timestamp
	 * @since 1.34
	 */
	public function setRevisionTimestampUsed( $timestamp ): void {
		$this->revisionTimestampUsed = $timestamp;
	}

	/**
	 * @return string|null TS_MW timestamp or null if not used
	 * @since 1.34
	 */
	public function getRevisionTimestampUsed() {
		return $this->revisionTimestampUsed;
	}

	/**
	 * @param string $hash Lowercase SHA-1 base 36 hash
	 * @since 1.34
	 */
	public function setRevisionUsedSha1Base36( $hash ): void {
		if ( $hash === null ) {
			return; // e.g. RevisionRecord::getSha1() returned null
		}

		if (
			$this->revisionUsedSha1Base36 !== null &&
			$this->revisionUsedSha1Base36 !== $hash
		) {
			$this->revisionUsedSha1Base36 = ''; // mismatched
		} else {
			$this->revisionUsedSha1Base36 = $hash;
		}
	}

	/**
	 * @return string|null Lowercase SHA-1 base 36 hash, null if unused, or "" on inconsistency
	 * @since 1.34
	 */
	public function getRevisionUsedSha1Base36() {
		return $this->revisionUsedSha1Base36;
	}

	/**
	 * @return string[]
	 * @note Before 1.43, this function returned an array reference.
	 * @deprecated since 1.43, use ::getLinkList(ParserOutputLinkTypes::LANGUAGE)
	 */
	public function getLanguageLinks() {
		$result = [];
		foreach ( $this->mLanguageLinkMap as $lang => $title ) {
			$result[] = "$lang:$title";
		}
		return $result;
	}

	/** @deprecated since 1.43, use ::getLinkList(ParserOutputLinkTypes::INTERWIKI) */
	public function getInterwikiLinks() {
		return $this->mInterwikiLinks;
	}

	/**
	 * Return the names of the categories on this page.
	 * Unlike ::getCategories(), sort keys are *not* included in the
	 * return value.
	 * @return array<string> The names of the categories
	 * @since 1.38
	 */
	public function getCategoryNames(): array {
		# Note that numeric category names get converted to 'int' when
		# stored as array keys; stringify the keys to ensure they
		# return to original string form so as not to confuse callers.
		return array_map( 'strval', array_keys( $this->mCategories ) );
	}

	/**
	 * Return category names and sort keys as a map.
	 *
	 * BEWARE that numeric category names get converted to 'int' when stored
	 * as array keys.  Because of this, use of this method is not recommended
	 * in new code; using ::getCategoryNames() and ::getCategorySortKey() will
	 * be less error-prone.
	 *
	 * @return array<string|int,string>
	 * @internal
	 */
	public function getCategoryMap(): array {
		return $this->mCategories;
	}

	/**
	 * Return the sort key for a given category name, or `null` if the
	 * category is not present in this ParserOutput.  Returns the
	 * empty string if the category is to use the default sort key.
	 *
	 * @note The effective sort key in the database may vary from what
	 * is returned here; see note in ParserOutput::addCategory().
	 *
	 * @param string $name The category name
	 * @return ?string The sort key for the category, or `null` if the
	 *  category is not present in this ParserOutput
	 * @since 1.40
	 */
	public function getCategorySortKey( string $name ): ?string {
		// This API avoids exposing the fact that numeric string category
		// names are going to be converted to 'int' when used as array
		// keys for the `mCategories` field.
		return $this->mCategories[$name] ?? null;
	}

	/**
	 * @return array<string,string> Maps identifiers to HTML contents
	 * @since 1.25
	 */
	public function getIndicators(): array {
		return $this->mIndicators;
	}

	public function getTitleText() {
		return $this->mTitleText;
	}

	/**
	 * @return ?TOCData the table of contents data, or null if it hasn't been
	 * set.
	 */
	public function getTOCData(): ?TOCData {
		return $this->mTOCData;
	}

	/**
	 * @internal
	 * @return string
	 */
	public function getCacheMessage(): string {
		return $this->mCacheMessage;
	}

	/**
	 * @internal
	 * @return array
	 */
	public function getSections(): array {
		if ( $this->mTOCData !== null ) {
			return $this->mTOCData->toLegacy();
		}
		// For compatibility
		return [];
	}

	/**
	 * Get a list of links of the given type.
	 *
	 * Provides a uniform interface to various lists of links stored in
	 * the metadata.
	 *
	 * Each element of the returned array has a LinkTarget as the 'link'
	 * property.  Local and template links also have 'pageid' set.
	 * Template links have 'revid' set.  Category links have 'sort' set.
	 * Media links optionally have 'time' and 'sha1' set.
	 *
	 * @param string $linkType A link type, which should be a constant from
	 *  ParserOutputLinkTypes.
	 * @return list<array{link:ParsoidLinkTarget,pageid?:int,revid?:int,sort?:string,time?:string|false,sha1?:string|false}>
	 */
	public function getLinkList( string $linkType ): array {
		# Note that fragments are dropped for everything except language links
		$result = [];
		switch ( $linkType ) {
			case ParserOutputLinkTypes::CATEGORY:
				foreach ( $this->mCategories as $dbkey => $sort ) {
					$result[] = [
						'link' => new TitleValue( NS_CATEGORY, (string)$dbkey ),
						'sort' => $sort,
					];
				}
				break;

			case ParserOutputLinkTypes::EXISTENCE:
				foreach ( $this->existenceLinks as $ns => $titles ) {
					foreach ( $titles as $dbkey => $unused ) {
						$result[] = [
							'link' => new TitleValue( $ns, (string)$dbkey )
						];
					}
				}
				break;

			case ParserOutputLinkTypes::INTERWIKI:
				foreach ( $this->mInterwikiLinks as $prefix => $arr ) {
					foreach ( $arr as $dbkey => $ignore ) {
						$result[] = [
							'link' => new TitleValue( NS_MAIN, (string)$dbkey, '', (string)$prefix ),
						];
					}
				}
				break;

			case ParserOutputLinkTypes::LANGUAGE:
				foreach ( $this->mLanguageLinkMap as $lang => $title ) {
					# language links can have fragments!
					[ $title, $frag ] = array_pad( explode( '#', $title, 2 ), 2, '' );
					$result[]  = [
						'link' => new TitleValue( NS_MAIN, $title, $frag, (string)$lang ),
					];
				}
				break;

			case ParserOutputLinkTypes::LOCAL:
				foreach ( $this->mLinks as $ns => $arr ) {
					foreach ( $arr as $dbkey => $id ) {
						$result[] = [
							'link' => new TitleValue( $ns, (string)$dbkey ),
							'pageid' => $id,
						];
					}
				}
				break;

			case ParserOutputLinkTypes::MEDIA:
				foreach ( $this->mImages as $dbkey => $ignore ) {
					$extra = $this->mFileSearchOptions[$dbkey] ?? [];
					$extra['link'] = new TitleValue( NS_FILE, (string)$dbkey );
					$result[] = $extra;
				}
				break;

			case ParserOutputLinkTypes::SPECIAL:
				foreach ( $this->mLinksSpecial as $dbkey => $ignore ) {
					$result[] = [
						'link' => new TitleValue( NS_SPECIAL, (string)$dbkey ),
					];
				}
				break;

			case ParserOutputLinkTypes::TEMPLATE:
				foreach ( $this->mTemplates as $ns => $arr ) {
					foreach ( $arr as $dbkey => $pageid ) {
						$result[] = [
							'link' => new TitleValue( $ns, (string)$dbkey ),
							'pageid' => $pageid,
							// default to invalid/broken revision if this is not present
							'revid' => $this->mTemplateIds[$ns][$dbkey] ?? 0,
						];
					}
				}
				break;

			default:
				throw new UnexpectedValueException( "Unknown link type $linkType" );
		}
		return $result;
	}

	/** @deprecated since 1.43, use ::getLinkList(ParserOutputLinkTypes::LOCAL) */
	public function &getLinks() {
		return $this->mLinks;
	}

	/**
	 * Return true if the given parser output has local links registered
	 * in the metadata.
	 * @return bool
	 * @since 1.44
	 */
	public function hasLinks(): bool {
		foreach ( $this->mLinks as $ns => $arr ) {
			foreach ( $arr as $dbkey => $id ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @return array Keys are DBKs for the links to special pages in the document
	 * @since 1.35
	 * @deprecated since 1.43, use ::getLinkList(ParserOutputLinkTypes::SPECIAL)
	 */
	public function &getLinksSpecial() {
		return $this->mLinksSpecial;
	}

	/** @deprecated since 1.43, use ::getLinkList(ParserOutputLinkTypes::TEMPLATE) */
	public function &getTemplates() {
		return $this->mTemplates;
	}

	/** @deprecated since 1.43, use ::getLinkList(ParserOutputLinkTypes::TEMPLATE) */
	public function &getTemplateIds() {
		return $this->mTemplateIds;
	}

	/** @deprecated since 1.43, use ::getLinkList(ParserOutputLinkTypes::MEDIA) */
	public function &getImages() {
		return $this->mImages;
	}

	/**
	 * Return true if there are image dependencies registered for this
	 * ParserOutput.
	 * @since 1.44
	 */
	public function hasImages(): bool {
		return $this->mImages !== [];
	}

	/** @deprecated since 1.43, use ::getLinkList(ParserOutputLinkTypes::MEDIA) */
	public function &getFileSearchOptions() {
		return $this->mFileSearchOptions;
	}

	/**
	 * @note Use of the reference returned by this method has been
	 *  deprecated since 1.43.  In a future release this will return a
	 *  normal array.  Use ::addExternalLink() to modify the set of
	 *  external links stored in this ParserOutput.
	 */
	public function &getExternalLinks(): array {
		return $this->mExternalLinks;
	}

	public function setNoGallery( $value ): void {
		$this->mNoGallery = (bool)$value;
	}

	public function getNoGallery() {
		return $this->mNoGallery;
	}

	public function getHeadItems() {
		return $this->mHeadItems;
	}

	public function getModules() {
		return array_keys( $this->mModuleSet );
	}

	public function getModuleStyles() {
		return array_keys( $this->mModuleStyleSet );
	}

	/**
	 * @param bool $showStrategyKeys Defaults to false; if set to true will
	 *  expose the internal `MW_MERGE_STRATEGY_KEY` in the result.  This
	 *  should only be used internally to allow safe merge of config vars.
	 * @return array
	 * @since 1.23
	 */
	public function getJsConfigVars( bool $showStrategyKeys = false ) {
		$result = $this->mJsConfigVars;
		// Don't expose the internal strategy key
		foreach ( $result as &$value ) {
			if ( is_array( $value ) && !$showStrategyKeys ) {
				unset( $value[self::MW_MERGE_STRATEGY_KEY] );
			}
		}
		return $result;
	}

	/** @deprecated since 1.45; use ::getWarningMsgs. */
	public function getWarnings(): array {
		// T343048: Don't emit deprecation warnings here until the
		// compatibility fallback in ApiParse is removed.
		return array_keys( $this->mWarnings );
	}

	/** @return list<MessageValue> */
	public function getWarningMsgs(): array {
		return array_values( $this->mWarningMsgs );
	}

	public function getIndexPolicy(): string {
		// 'noindex' wins if both are set. (T16899)
		if ( $this->mNoIndexSet ) {
			return 'noindex';
		} elseif ( $this->mIndexSet ) {
			return 'index';
		}
		return '';
	}

	/**
	 * @return string|null TS_MW timestamp of the revision content
	 */
	public function getRevisionTimestamp(): ?string {
		return $this->mTimestamp;
	}

	/**
	 * @return string|null TS_MW timestamp of the revision content
	 * @deprecated since 1.42; use ::getRevisionTimestamp() instead
	 */
	public function getTimestamp() {
		return $this->getRevisionTimestamp();
	}

	public function getLimitReportData() {
		return $this->mLimitReportData;
	}

	public function getLimitReportJSData() {
		return $this->mLimitReportJSData;
	}

	public function getEnableOOUI() {
		return $this->mEnableOOUI;
	}

	/**
	 * Get extra Content-Security-Policy 'default-src' directives
	 * @since 1.35
	 * @return string[]
	 */
	public function getExtraCSPDefaultSrcs() {
		return $this->mExtraDefaultSrcs;
	}

	/**
	 * Get extra Content-Security-Policy 'script-src' directives
	 * @since 1.35
	 * @return string[]
	 */
	public function getExtraCSPScriptSrcs() {
		return $this->mExtraScriptSrcs;
	}

	/**
	 * Get extra Content-Security-Policy 'style-src' directives
	 * @since 1.35
	 * @return string[]
	 */
	public function getExtraCSPStyleSrcs() {
		return $this->mExtraStyleSrcs;
	}

	/**
	 * Set the raw text of the ParserOutput.
	 *
	 * If you did not generate html, pass null to mark it as such.
	 *
	 * @since 1.42
	 * @param string|null $text HTML content of ParserOutput or null if not generated
	 * @param-taint $text exec_html
	 */
	public function setRawText( ?string $text ): void {
		$this->mRawText = $text;
	}

	/**
	 * Set the raw text of the ParserOutput.
	 *
	 * If you did not generate html, pass null to mark it as such.
	 *
	 * @since 1.39 You can now pass null to this function
	 * @param string|null $text HTML content of ParserOutput or null if not generated
	 * @param-taint $text exec_html
	 * @return string|null Previous value of ParserOutput's raw text
	 * @deprecated since 1.42; use ::setRawText() which matches the getter ::getRawText()
	 */
	public function setText( $text ) {
		return wfSetVar( $this->mRawText, $text, true );
	}

	/**
	 * @deprecated since 1.42, use ::addLanguageLink() instead.
	 */
	public function setLanguageLinks( $ll ) {
		wfDeprecated( __METHOD__, '1.42' );
		$old = $this->getLanguageLinks();
		$this->mLanguageLinkMap = [];
		if ( $ll === null ) { // T376323
			wfDeprecated( __METHOD__ . ' with null argument', '1.43' );
		}
		foreach ( ( $ll ?? [] ) as $l ) {
			$this->addLanguageLink( $l );
		}
		return $old;
	}

	/** @internal For use by OutputPage only. */
	public function clearLanguageLinks(): void {
		$this->mLanguageLinkMap = [];
	}

	public function setTitleText( $t ) {
		return wfSetVar( $this->mTitleText, $t );
	}

	/**
	 * @param TOCData $tocData Table of contents data for the page
	 */
	public function setTOCData( TOCData $tocData ): void {
		$this->mTOCData = $tocData;
	}

	/**
	 * @param array $sectionArray
	 * @return array Previous value of ::getSections()
	 */
	public function setSections( array $sectionArray ) {
		$oldValue = $this->getSections();
		$this->setTOCData( TOCData::fromLegacy( $sectionArray ) );
		return $oldValue;
	}

	/**
	 * Update the index policy of the robots meta tag.
	 *
	 * Note that calling this method does not guarantee
	 * that {@link self::getIndexPolicy()} will return the given policy –
	 * if different calls set the index policy to 'index' and 'noindex',
	 * then 'noindex' always wins (T16899), even if the 'index' call happened later.
	 * If this is not what you want,
	 * you can reset {@link ParserOutputFlags::NO_INDEX_POLICY} with {@link self::setOutputFlag()}.
	 *
	 * @param string $policy 'index' or 'noindex'.
	 * @return string The previous policy.
	 */
	public function setIndexPolicy( $policy ): string {
		$old = $this->getIndexPolicy();
		if ( $policy === 'noindex' ) {
			$this->mNoIndexSet = true;
		} elseif ( $policy === 'index' ) {
			$this->mIndexSet = true;
		}
		return $old;
	}

	/**
	 * @param ?string $timestamp TS_MW timestamp of the revision content
	 */
	public function setRevisionTimestamp( ?string $timestamp ): void {
		$this->mTimestamp = $timestamp;
	}

	/**
	 * @param ?string $timestamp TS_MW timestamp of the revision content
	 *
	 * @return ?string The previous value of the timestamp
	 * @deprecated since 1.42; use ::setRevisionTimestamp() instead
	 */
	public function setTimestamp( $timestamp ) {
		return wfSetVar( $this->mTimestamp, $timestamp );
	}

	/**
	 * Add a category.
	 *
	 * Although ParserOutput::getCategorySortKey() will return exactly
	 * the sort key you specify here, before storing in the database
	 * all sort keys will be language converted, HTML entities will be
	 * decoded, newlines stripped, and then they will be truncated to
	 * 255 bytes. Thus the "effective" sort key in the DB may be different
	 * from what is passed to `$sort` here and returned by
	 * ::getCategorySortKey().
	 *
	 * @param string|ParsoidLinkTarget $c The category name
	 * @param string $sort The sort key; an empty string indicates
	 *  that the default sort key for the page should be used.
	 */
	public function addCategory( $c, $sort = '' ): void {
		if ( $c instanceof ParsoidLinkTarget ) {
			$c = $c->getDBkey();
		}
		$this->mCategories[$c] = $sort;
	}

	/**
	 * Overwrite the category map.
	 * @param array<string,string> $c Map of category names to sort keys
	 * @since 1.38
	 */
	public function setCategories( array $c ): void {
		$this->mCategories = $c;
	}

	/**
	 * @param string $id
	 * @param string $content
	 * @param-taint $content exec_html
	 * @since 1.25
	 */
	public function setIndicator( $id, $content ): void {
		$this->mIndicators[$id] = $content;
	}

	/**
	 * Enables OOUI, if true, in any OutputPage instance this ParserOutput
	 * object is added to.
	 *
	 * @since 1.26
	 * @param bool $enable If OOUI should be enabled or not
	 */
	public function setEnableOOUI( bool $enable = false ): void {
		$this->mEnableOOUI = $enable;
	}

	/**
	 * Add a language link.
	 * @param ParsoidLinkTarget|string $t
	 */
	public function addLanguageLink( $t ): void {
		# Note that fragments are preserved
		if ( $t instanceof ParsoidLinkTarget ) {
			// Language links are unusual in using 'text' rather than 'db key'
			// Note that fragments are preserved.
			$lang = $t->getInterwiki();
			$title = $t->getText();
			if ( $t->hasFragment() ) {
				$title .= '#' . $t->getFragment();
			}
		} else {
			[ $lang, $title ] = array_pad( explode( ':', $t, 2 ), -2, '' );
		}
		if ( $lang === '' ) {
			throw new InvalidArgumentException( __METHOD__ . ' without prefix' );
		}
		$this->mLanguageLinkMap[$lang] ??= $title;
	}

	/**
	 * Add a warning to the output for this page.
	 * @param MessageSpecifier $mv
	 * @param ?string $key An optional deduplication key, used to prevent
	 *  duplicate messages.  If omitted or null, the MessageValue key will
	 *  be used for deduplication.
	 * @since 1.43
	 */
	public function addWarningMsgVal( MessageSpecifier $mv, ?string $key = null ) {
		$mv = MessageValue::newFromSpecifier( $mv );
		$key ??= $mv->getKey();
		$this->mWarningMsgs[$key] = $mv;
		// Ensure callers aren't passing nonserializable arguments: T343048.
		$jsonCodec = MediaWikiServices::getInstance()->getJsonCodec();
		$path = $jsonCodec->detectNonSerializableData( $mv, true );
		if ( $path !== null ) {
			throw new InvalidArgumentException( __METHOD__ . ": nonserializable" );
		}
		// For backward compatibility with callers of ::getWarnings()
		// and rollback compatibility for ParserCache; don't remove
		// until we no longer need rollback compatiblity with MW 1.43.
		$s = Message::newFromSpecifier( $mv )
			// some callers set the title here?
			->inContentLanguage() // because this ends up in cache
			->text();
		$this->mWarnings[$s] = 1;
	}

	/**
	 * Add a warning to the output for this page.
	 * @param string $msg The localization message key for the warning
	 * @param mixed|JsonDeserializable ...$args Optional arguments for the
	 *   message. These arguments must be serializable/deserializable with
	 *   JsonCodec; see the @note on ParserOutput::setExtensionData()
	 * @since 1.38
	 */
	public function addWarningMsg( string $msg, ...$args ): void {
		// T227447: Once MessageSpecifier is moved to a library, Parsoid would
		// be able to use ::addWarningMsgVal() directly and this method
		// could be deprecated and removed.
		$this->addWarningMsgVal( MessageValue::new( $msg, $args ) );
	}

	public function setNewSection( $value ): void {
		$this->mNewSection = (bool)$value;
	}

	/**
	 * @param bool $value Hide the new section link?
	 */
	public function setHideNewSection( bool $value ): void {
		$this->mHideNewSection = $value;
	}

	public function getHideNewSection(): bool {
		return (bool)$this->mHideNewSection;
	}

	public function getNewSection(): bool {
		return (bool)$this->mNewSection;
	}

	/**
	 * Checks, if a url is pointing to the own server
	 *
	 * @param string $internal The server to check against
	 * @param string $url The url to check
	 * @return bool
	 * @internal
	 */
	public static function isLinkInternal( $internal, $url ): bool {
		return (bool)preg_match( '/^' .
			# If server is proto relative, check also for http/https links
			( str_starts_with( $internal, '//' ) ? '(?:https?:)?' : '' ) .
			preg_quote( $internal, '/' ) .
			# check for query/path/anchor or end of link in each case
			'(?:[\?\/\#]|$)/i',
			$url
		);
	}

	public function addExternalLink( $url ): void {
		# We don't register links pointing to our own server, unless... :-)
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$server = $config->get( MainConfigNames::Server );
		$registerInternalExternals = $config->get( MainConfigNames::RegisterInternalExternals );
		# Replace unnecessary URL escape codes with the referenced character
		# This prevents spammers from hiding links from the filters
		$url = Parser::normalizeLinkUrl( $url );

		$registerExternalLink = true;
		if ( !$registerInternalExternals ) {
			$registerExternalLink = !self::isLinkInternal( $server, $url );
		}
		if ( $registerExternalLink ) {
			$this->mExternalLinks[$url] = 1;
		}
	}

	/**
	 * Record a local or interwiki inline link for saving in future link tables.
	 *
	 * @param ParsoidLinkTarget $link (used to require Title until 1.38)
	 * @param int|null $id Optional known page_id so we can skip the lookup
	 */
	public function addLink( ParsoidLinkTarget $link, $id = null ): void {
		if ( $link->isExternal() ) {
			// Don't record interwikis in pagelinks
			$this->addInterwikiLink( $link );
			return;
		}
		$ns = $link->getNamespace();
		$dbk = $link->getDBkey();
		if ( $ns === NS_MEDIA ) {
			// Normalize this pseudo-alias if it makes it down here...
			$ns = NS_FILE;
		} elseif ( $ns === NS_SPECIAL ) {
			// We don't want to record Special: links in the database, so put them in a separate place.
			// It might actually be wise to, but we'd need to do some normalization.
			$this->mLinksSpecial[$dbk] = 1;
			return;
		} elseif ( $dbk === '' ) {
			// Don't record self links -  [[#Foo]]
			return;
		}
		if ( $id === null ) {
			// T357048: This actually kills performance; we should batch these.
			$page = MediaWikiServices::getInstance()->getPageStore()->getPageForLink( $link );
			$id = $page->getId();
		}
		$this->mLinks[$ns][$dbk] = $id;
	}

	/**
	 * Register a file dependency for this output
	 * @param string|ParsoidLinkTarget $name Title dbKey
	 * @param string|false|null $timestamp MW timestamp of file creation (or false if non-existing)
	 * @param string|false|null $sha1 Base 36 SHA-1 of file (or false if non-existing)
	 */
	public function addImage( $name, $timestamp = null, $sha1 = null ): void {
		if ( $name instanceof ParsoidLinkTarget ) {
			$name = $name->getDBkey();
		}
		$this->mImages[$name] = 1;
		if ( $timestamp !== null && $sha1 !== null ) {
			$this->mFileSearchOptions[$name] = [ 'time' => $timestamp, 'sha1' => $sha1 ];
		}
	}

	/**
	 * Register a template dependency for this output
	 *
	 * @param ParsoidLinkTarget $link (used to require Title until 1.38)
	 * @param int $page_id
	 * @param int $rev_id
	 */
	public function addTemplate( $link, $page_id, $rev_id ): void {
		if ( $link->isExternal() ) {
			// Will throw an InvalidArgumentException in a future release.
			throw new InvalidArgumentException( __METHOD__ . " with interwiki link" );
		}
		$ns = $link->getNamespace();
		$dbk = $link->getDBkey();
		// T357048: Parsoid doesn't have page_id
		$this->mTemplates[$ns][$dbk] = $page_id;
		$this->mTemplateIds[$ns][$dbk] = $rev_id; // For versioning
	}

	/**
	 * @param ParsoidLinkTarget $link must be an interwiki link
	 *       (used to require Title until 1.38).
	 */
	public function addInterwikiLink( $link ): void {
		if ( !$link->isExternal() ) {
			throw new InvalidArgumentException( 'Non-interwiki link passed, internal parser error.' );
		}
		$prefix = $link->getInterwiki();
		$this->mInterwikiLinks[$prefix][$link->getDBkey()] = 1;
	}

	/**
	 * Add a dependency on the existence of a page. The cache entry will be
	 * invalidated if the page is created or deleted.
	 *
	 * @since 1.44
	 * @param ParsoidLinkTarget $link
	 */
	public function addExistenceDependency( ParsoidLinkTarget $link ) {
		$ns = $link->getNamespace();
		$dbk = $link->getDBkey();
		// Ignore some kinds of links, as in addLink()
		if ( $link->isExternal() || $ns === NS_SPECIAL || $dbk === '' ) {
			return;
		}
		if ( $ns === NS_MEDIA ) {
			$ns = NS_FILE;
		}
		$this->existenceLinks[$ns][$dbk] = true;
	}

	/**
	 * Add some text to the "<head>".
	 * If $tag is set, the section with that tag will only be included once
	 * in a given page.
	 * @param string $section
	 * @param string|false $tag
	 */
	public function addHeadItem( $section, $tag = false ): void {
		if ( $tag !== false ) {
			$this->mHeadItems[$tag] = $section;
		} else {
			$this->mHeadItems[] = $section;
		}
	}

	/**
	 * @see OutputPage::addModules
	 * @param string[] $modules
	 */
	public function addModules( array $modules ): void {
		$modules = array_fill_keys( $modules, true );
		$this->mModuleSet = array_merge( $this->mModuleSet, $modules );
	}

	/**
	 * @see OutputPage::addModuleStyles
	 * @param string[] $modules
	 */
	public function addModuleStyles( array $modules ): void {
		$modules = array_fill_keys( $modules, true );
		$this->mModuleStyleSet = array_merge( $this->mModuleStyleSet, $modules );
	}

	/**
	 * Add one or more variables to be set in mw.config in JavaScript.
	 *
	 * @param string|array $keys Key or array of key/value pairs.
	 * @param mixed|null $value [optional] Value of the configuration variable.
	 * @since 1.23
	 * @deprecated since 1.38, use ::setJsConfigVar() or ::appendJsConfigVar()
	 *  which ensures compatibility with asynchronous parsing; emitting warnings
	 *  since 1.43.
	 */
	public function addJsConfigVars( $keys, $value = null ): void {
		wfDeprecated( __METHOD__, '1.38' );
		if ( is_array( $keys ) ) {
			foreach ( $keys as $key => $value ) {
				$this->mJsConfigVars[$key] = $value;
			}
			return;
		}

		$this->mJsConfigVars[$keys] = $value;
	}

	/**
	 * Add a variable to be set in mw.config in JavaScript.
	 *
	 * In order to ensure the result is independent of the parse order, the values
	 * set here must be unique -- that is, you can pass the same $key
	 * multiple times but ONLY if the $value is identical each time.
	 * If you want to collect multiple pieces of data under a single key,
	 * use ::appendJsConfigVar().
	 *
	 * @param string $key Key to use under mw.config
	 * @param mixed|null $value Value of the configuration variable.
	 * @since 1.38
	 */
	public function setJsConfigVar( string $key, $value ): void {
		if (
			array_key_exists( $key, $this->mJsConfigVars ) &&
			$this->mJsConfigVars[$key] !== $value
		) {
			// Ensure that a key is mapped to only a single value in order
			// to prevent the resulting array from varying if content
			// is parsed in a different order.
			throw new InvalidArgumentException( "Multiple conflicting values given for $key" );
		}
		$this->mJsConfigVars[$key] = $value;
	}

	/**
	 * Append a value to a variable to be set in mw.config in JavaScript.
	 *
	 * In order to ensure the result is independent of the parse order,
	 * the value of this key will be an associative array, mapping all of
	 * the values set under that key to true.  (The array is implicitly
	 * ordered in PHP, but you should treat it as unordered.)
	 * If you want a non-array type for the key, and can ensure that only
	 * a single value will be set, you should use ::setJsConfigVar() instead.
	 *
	 * @param string $key Key to use under mw.config
	 * @param string $value Value to append to the configuration variable.
	 * @param string $strategy Merge strategy:
	 *  only MW_MERGE_STRATEGY_UNION is currently supported and external callers
	 *  should treat this parameter as @internal at this time and omit it.
	 * @since 1.38
	 */
	public function appendJsConfigVar(
		string $key,
		string $value,
		string $strategy = self::MW_MERGE_STRATEGY_UNION
	): void {
		if ( $strategy !== self::MW_MERGE_STRATEGY_UNION ) {
			throw new InvalidArgumentException( "Unknown merge strategy $strategy." );
		}
		if ( !array_key_exists( $key, $this->mJsConfigVars ) ) {
			$this->mJsConfigVars[$key] = [
				// Indicate how these values are to be merged.
				self::MW_MERGE_STRATEGY_KEY => $strategy,
			];
		} elseif ( !is_array( $this->mJsConfigVars[$key] ) ) {
			throw new InvalidArgumentException( "Mixing set and append for $key" );
		} elseif ( ( $this->mJsConfigVars[$key][self::MW_MERGE_STRATEGY_KEY] ?? null ) !== $strategy ) {
			throw new InvalidArgumentException( "Conflicting merge strategies for $key" );
		}
		$this->mJsConfigVars[$key][$value] = true;
	}

	/**
	 * Accommodate very basic transcluding of a temporary OutputPage object into parser output.
	 *
	 * This is a fragile method that cannot be relied upon in any meaningful way.
	 * It exists solely to support the wikitext feature of transcluding a SpecialPage, and
	 * only has to work for that use case to ensure relevant styles are loaded, and that
	 * essential config vars needed between SpecialPage and a JS feature are added.
	 *
	 * This relies on there being no overlap between modules or config vars added by
	 * the SpecialPage and those added by parser extensions. If there is overlap,
	 * then arise and break one or both sides. This is expected and unsupported.
	 *
	 * @internal For use by Parser for basic special page transclusion
	 * @param OutputPage $out
	 */
	public function addOutputPageMetadata( OutputPage $out ): void {
		// This should eventually use the same merge mechanism used
		// internally to merge ParserOutputs together.
		// (ie: $this->mergeHtmlMetaDataFrom( $out->getMetadata() )
		// once preventClickjacking, moduleStyles, modules, jsconfigvars,
		// and head items are moved to OutputPage::$metadata)

		// Take the strictest click-jacking policy. This is to ensure any one-click features
		// such as patrol or rollback on the transcluded special page will result in the wiki page
		// disallowing embedding in cross-origin iframes. Articles are generally allowed to be
		// embedded. Pages that transclude special pages are expected to be user pages or
		// other non-content pages that content re-users won't discover or care about.
		$this->mPreventClickjacking = $this->mPreventClickjacking || $out->getPreventClickjacking();

		$this->addModuleStyles( $out->getModuleStyles() );

		// TODO: Figure out if style modules suffice, or whether the below is needed as well.
		// Are there special pages that permit transcluding/including and also have JS modules
		// that should be activate on the host page?
		$this->addModules( $out->getModules() );
		$this->mJsConfigVars = self::mergeMapStrategy(
			$this->mJsConfigVars, $out->getJsConfigVars()
		);
		$this->mHeadItems = array_merge( $this->mHeadItems, $out->getHeadItemsArray() );
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
	public function setDisplayTitle( $text ): void {
		$this->setTitleText( $text );
		$this->setPageProperty( 'displaytitle', $text );
	}

	/**
	 * Get the title to be used for display.
	 *
	 * As per the contract of setDisplayTitle(), this is safe HTML,
	 * ready to be served to the client.
	 *
	 * @return string|false HTML
	 */
	public function getDisplayTitle() {
		$t = $this->getTitleText();
		if ( $t === '' ) {
			return false;
		}
		return $t;
	}

	/**
	 * Get the primary language code of the output.
	 *
	 * This returns the primary language of the output, including
	 * any LanguageConverter variant applied.
	 *
	 * NOTE: This may differ from the wiki's default content language
	 * ($wgLanguageCode, MediaWikiServices::getContentLanguage), because
	 * each page may have its own "page language" set (PageStoreRecord,
	 * Title::getDbPageLanguageCode, ContentHandler::getPageLanguage).
	 *
	 * NOTE: This may differ from the "page language" when parsing
	 * user interface messages, in which case this reflects the user
	 * language (including any variant preference).
	 *
	 * NOTE: This may differ from the Parser's "target language" that was
	 * set while the Parser was parsing the page, because the final output
	 * is converted to the current user's preferred LanguageConverter variant
	 * (assuming this is a variant of the target language).
	 * See Parser::getTargetLanguageConverter()->getPreferredVariant(); use
	 * LanguageFactory::getParentLanguage() on the language code to obtain
	 * the base language code. LanguageConverter::getPreferredVariant()
	 * depends on the global RequestContext for the URL and the User
	 * language preference.
	 *
	 * Finally, note that a single ParserOutput object may contain
	 * HTML content in multiple different languages and directions
	 * (T114640). Authors of wikitext and of parser extensions are
	 * expected to mark such subtrees with a `lang` attribute (set to
	 * a BCP-47 value, see Language::toBcp47Code()) and a corresponding
	 * `dir` attribute (see Language::getDir()). This method returns
	 * the language code for wrapper of the HTML content.
	 *
	 * @see Parser::internalParseHalfParsed
	 * @since 1.40
	 * @return ?Bcp47Code The primary language for this output,
	 *   or `null` if a language was not set.
	 */
	public function getLanguage(): ?Bcp47Code {
		// This information is temporarily stored in extension data (T303329)
		$code = $this->getExtensionData( 'core:target-lang-variant' );
		// This is null if the ParserOutput was cached by MW 1.40 or earlier,
		// or not constructed by Parser/ParserCache.
		return $code === null ? null : new Bcp47CodeValue( $code );
	}

	/**
	 * Set the primary language of the output.
	 *
	 * See the discussion and caveats in ::getLanguage().
	 *
	 * @param Bcp47Code $lang The primary language for this output, including
	 *   any variant specification.
	 * @since 1.40
	 */
	public function setLanguage( Bcp47Code $lang ): void {
		$this->setExtensionData( 'core:target-lang-variant', $lang->toBcp47Code() );
	}

	/**
	 * Return an HTML prefix to be applied on redirect pages, or null
	 * if this is not a redirect.
	 * @return ?string HTML to prepend to redirect pages, or null
	 * @internal
	 */
	public function getRedirectHeader(): ?string {
		return $this->getExtensionData( 'core:redirect-header' );
	}

	/**
	 * Set an HTML prefix to be applied on redirect pages.
	 * @param string $html HTML to prepend to redirect pages
	 */
	public function setRedirectHeader( string $html ): void {
		$this->setExtensionData( 'core:redirect-header', $html );
	}

	/**
	 * Store a unique rendering id for this ParserOutput.  This is used
	 * whenever a client needs to record a dependency on a specific parse.
	 * It is typically set only when a parser output is cached.
	 *
	 * @param string $renderId a UUID identifying a specific parse
	 * @internal
	 */
	public function setRenderId( string $renderId ): void {
		$this->setExtensionData( 'core:render-id', $renderId );
	}

	/**
	 * Return the unique rendering id for this ParserOutput. This is used
	 * whenever a client needs to record a dependency on a specific parse.
	 *
	 * @return string|null
	 * @internal
	 */
	public function getRenderId(): ?string {
		// Backward-compatibility with old cache contents
		// Can be removed after parser cache contents have expired
		$old = $this->getExtensionData( 'parsoid-render-id' );
		if ( $old !== null ) {
			return ParsoidRenderId::newFromKey( $old )->getUniqueID();
		}
		return $this->getExtensionData( 'core:render-id' );
	}

	/**
	 * @return string[] List of flags signifying special cases
	 * @internal
	 */
	public function getAllFlags(): array {
		return array_keys( $this->mFlags );
	}

	/**
	 * Set a page property to be stored in the page_props database table.
	 *
	 * page_props is a key-value store indexed by the page ID. This allows
	 * the parser to set a property on a page which can then be quickly
	 * retrieved given the page ID or via a DB join when given the page
	 * title.
	 *
	 * Since 1.23, page_props are also indexed by numeric value, to allow
	 * for efficient "top k" queries of pages wrt a given property.
	 * This only works if the value is passed as a int, float, or
	 * bool. Since 1.42 you should use ::setNumericPageProperty()
	 * if you want your page property value to be indexed, which will ensure
	 * that the value is of the proper type.
	 *
	 * setPageProperty() is thus used to propagate properties from the parsed
	 * page to request contexts other than a page view of the currently parsed
	 * article.
	 *
	 * Some applications examples:
	 *
	 *   * To implement hidden categories, hiding pages from category listings
	 *     by storing a page property.
	 *
	 *   * Overriding the displayed article title (ParserOutput::setDisplayTitle()).
	 *
	 *   * To implement image tagging, for example displaying an icon on an
	 *     image thumbnail to indicate that it is listed for deletion on
	 *     Wikimedia Commons.
	 *     This is not actually implemented, yet but would be pretty cool.
	 *
	 * @note Use of non-scalar values (anything other than
	 *  `string|int|float|bool`) has been deprecated in 1.42.
	 *  Although any JSON-serializable value can be stored/fetched in
	 *  ParserOutput, when the values are stored to the database
	 *  (in `deferred/LinksUpdate/PagePropsTable.php`) they will be
	 *  converted: booleans will be converted to '0' and '1', null
	 *  will become '', and everything else will be cast to string
	 *  (not JSON-serialized).  Page properties obtained from the
	 *  PageProps service will thus always be strings.
	 *
	 * @note The sort key stored in the database *will be NULL* unless
	 *  the value passed here is an `int|float|bool`.  If you *do not*
	 *  want your property *value* indexed and sorted (for example, the
	 *  value is a title string which can be numeric but only
	 *  incidentally, like when it gets retrieved from an array key)
	 *  be sure to cast to string or use
	 *  `::setUnsortedPageProperty()`.  If you *do* want your property
	 *  *value* indexed and sorted, you should use
	 *  `::setNumericPageProperty()` instead as this will ensure the
	 *  value type is correct. Note that either way it is possible to
	 *  efficiently look up all the pages with a certain property; we
	 *  are only talking about sorting the *values* assigned to the
	 *  property, for example for a "top N values of the property"
	 *  query.
	 *
	 * @note Note that `::getPageProperty()`/`::setPageProperty()` do
	 *  not do any conversions themselves; you should therefore be
	 *  careful to distinguish values returned from the PageProp
	 *  service (always strings) from values retrieved from a
	 *  ParserOutput.
	 *
	 * @note Do not use setPageProperty() to set a property which is only used
	 * in a context where the ParserOutput object itself is already available,
	 * for example a normal page view. There is no need to save such a property
	 * in the database since the text is already parsed; use
	 * ::setExtensionData() instead.
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
	 * @note The use of `null` as a value was deprecated in 1.42; use
	 * the empty string instead if you need a placeholder value, or
	 * ::unsetPageProperty() if you mean to remove a page property.
	 *
	 * @note The use of non-string values was deprecated in 1.42; if you
	 * need an page property value with a sort index
	 * use ::setNumericPageProperty().
	 *
	 * @param string $name
	 * @param string $value
	 * @since 1.38
	 */
	public function setPageProperty( string $name, string $value ): void {
		$this->setUnsortedPageProperty( $name, $value );
	}

	/**
	 * Set a numeric page property whose *value* is intended to be sorted
	 * and indexed.  The sort key used for the property will be the value,
	 * coerced to a number.
	 *
	 * See `::setPageProperty()` for details.
	 *
	 * In the future, we may allow the value to be specified independent
	 * of sort key (T357783).
	 *
	 * @param string $propName The name of the page property
	 * @param int|float|string $numericValue the numeric value
	 * @since 1.42
	 */
	public function setNumericPageProperty( string $propName, $numericValue ): void {
		if ( !is_numeric( $numericValue ) ) {
			throw new InvalidArgumentException( __METHOD__ . " with non-numeric value" );
		}
		// Coerce numeric sort key to a number.
		$this->mProperties[$propName] = 0 + $numericValue;
	}

	/**
	 * Set a page property whose *value* is not intended to be sorted and
	 * indexed.
	 *
	 * See `::setPageProperty()` for details.  It is recommended to
	 * use the empty string if you need a placeholder value (ie, if
	 * it is the *presence* of the property which is important, not
	 * the *value* the property is set to).
	 *
	 * It is still possible to efficiently look up all the pages with
	 * a certain property (the "presence" of it *is* indexed; see
	 * Special:PagesWithProp, list=pageswithprop).
	 *
	 * @param string $propName The name of the page property
	 * @param string $value Optional value; defaults to the empty string.
	 * @since 1.42
	 */
	public function setUnsortedPageProperty( string $propName, string $value = '' ): void {
		$this->mProperties[$propName] = $value;
	}

	/**
	 * Look up a page property.
	 * @param string $name The page property name to look up.
	 * @return ?scalar The value previously set using
	 * ::setPageProperty(), ::setUnsortedPageProperty(), or
	 * ::setNumericPageProperty().
	 * Returns null if no value was set for the given property name.
	 *
	 * @note You would need to use ::getPageProperties() to test for an
	 *  explicitly-set null value; but see the note in ::setPageProperty()
	 *  deprecating the use of null values.
	 * @since 1.38
	 */
	public function getPageProperty( string $name ) {
		return $this->mProperties[$name] ?? null;
	}

	/**
	 * Remove a page property.
	 * @param string $name The page property name.
	 * @since 1.38
	 */
	public function unsetPageProperty( string $name ): void {
		unset( $this->mProperties[$name] );
	}

	/**
	 * Return all the page properties set on this ParserOutput.
	 * @return array<string,?scalar>
	 * @since 1.38
	 */
	public function getPageProperties(): array {
		// @phan-suppress-next-line MediaWikiNoIssetIfDefined
		if ( !isset( $this->mProperties ) ) {
			$this->mProperties = [];
		}
		return $this->mProperties;
	}

	/**
	 * Provides a uniform interface to various boolean flags stored
	 * in the ParserOutput.  Flags internal to MediaWiki core should
	 * have names which are constants in ParserOutputFlags.  Extensions
	 * should use ::setExtensionData() rather than creating new flags
	 * with ::setOutputFlag() in order to prevent namespace conflicts.
	 *
	 * Flags are always combined with OR.  That is, the flag is set in
	 * the resulting ParserOutput if the flag is set in *any* of the
	 * fragments composing the ParserOutput.
	 *
	 * @note The combination policy means that a ParserOutput may end
	 * up with both INDEX_POLICY and NO_INDEX_POLICY set.  It is
	 * expected that NO_INDEX_POLICY "wins" in that case. (T16899)
	 * (This resolution is implemented in ::getIndexPolicy().)
	 *
	 * @param ParserOutputFlags|string $name A flag name
	 * @param bool $val
	 * @since 1.38
	 */
	public function setOutputFlag( ParserOutputFlags|string $name, bool $val = true ): void {
		if ( is_string( $name ) ) {
			$flag = ParserOutputFlags::tryFrom( $name );
		} else {
			$flag = $name;
			$name = $flag->value;
		}
		switch ( $flag ) {
			case ParserOutputFlags::NO_GALLERY:
				$this->setNoGallery( $val );
				break;

			case ParserOutputFlags::ENABLE_OOUI:
				$this->setEnableOOUI( $val );
				break;

			case ParserOutputFlags::NO_INDEX_POLICY:
				$this->mNoIndexSet = $val;
				break;

			case ParserOutputFlags::INDEX_POLICY:
				$this->mIndexSet = $val;
				break;

			case ParserOutputFlags::NEW_SECTION:
				$this->setNewSection( $val );
				break;

			case ParserOutputFlags::HIDE_NEW_SECTION:
				$this->setHideNewSection( $val );
				break;

			case ParserOutputFlags::PREVENT_CLICKJACKING:
				$this->setPreventClickjacking( $val );
				break;

			default:
				if ( $val ) {
					$this->mFlags[$name] = true;
				} else {
					unset( $this->mFlags[$name] );
				}
				break;
		}
	}

	/**
	 * Provides a uniform interface to various boolean flags stored
	 * in the ParserOutput.  Flags internal to MediaWiki core should
	 * have names which are constants in ParserOutputFlags.  Extensions
	 * should only use ::getOutputFlag() to query flags defined in
	 * ParserOutputFlags in core; they should use ::getExtensionData()
	 * to define their own flags.
	 *
	 * @param ParserOutputFlags|string $name A flag name
	 * @return bool The flag value
	 * @since 1.38
	 */
	public function getOutputFlag( ParserOutputFlags|string $name ): bool {
		if ( is_string( $name ) ) {
			$flag = ParserOutputFlags::tryFrom( $name );
		} else {
			$flag = $name;
			$name = $flag->value;
		}
		switch ( $flag ) {
			case ParserOutputFlags::NO_GALLERY:
				return $this->getNoGallery();

			case ParserOutputFlags::ENABLE_OOUI:
				return $this->getEnableOOUI();

			case ParserOutputFlags::INDEX_POLICY:
				return $this->mIndexSet;

			case ParserOutputFlags::NO_INDEX_POLICY:
				return $this->mNoIndexSet;

			case ParserOutputFlags::NEW_SECTION:
				return $this->getNewSection();

			case ParserOutputFlags::HIDE_NEW_SECTION:
				return $this->getHideNewSection();

			case ParserOutputFlags::PREVENT_CLICKJACKING:
				return $this->getPreventClickjacking();

			default:
				return $this->mFlags[$name] ?? false;

		}
	}

	/**
	 * Provides a uniform interface to various string sets stored
	 * in the ParserOutput.  String sets internal to MediaWiki core should
	 * have names which are constants in ParserOutputStringSets.  Extensions
	 * should use ::appendExtensionData() rather than creating new string sets
	 * with ::appendOutputStrings() in order to prevent namespace conflicts.
	 *
	 * @param string|ParserOutputStringSets $name A string set name
	 * @param string[] $value
	 * @since 1.41
	 */
	public function appendOutputStrings( string|ParserOutputStringSets $name, array $value ): void {
		if ( is_string( $name ) ) {
			$name = ParserOutputStringSets::from( $name );
		}
		match ( $name ) {
			ParserOutputStringSets::MODULE =>
				$this->addModules( $value ),
			ParserOutputStringSets::MODULE_STYLE =>
				$this->addModuleStyles( $value ),
			ParserOutputStringSets::EXTRA_CSP_DEFAULT_SRC =>
				array_walk( $value, fn ( $v, $i ) =>
					$this->addExtraCSPDefaultSrc( $v )
				),
			ParserOutputStringSets::EXTRA_CSP_SCRIPT_SRC =>
				array_walk( $value, fn ( $v, $i ) =>
					$this->addExtraCSPScriptSrc( $v )
				),
			ParserOutputStringSets::EXTRA_CSP_STYLE_SRC =>
				array_walk( $value, fn ( $v, $i ) =>
					$this->addExtraCSPStyleSrc( $v )
				),
		};
	}

	/**
	 * Provides a uniform interface to various boolean string sets stored
	 * in the ParserOutput.  String sets internal to MediaWiki core should
	 * have names which are constants in ParserOutputStringSets.  Extensions
	 * should only use ::getOutputStrings() to query string sets defined in
	 * ParserOutputStringSets in core; they should use ::appendExtensionData()
	 * to define their own string sets.
	 *
	 * @param string|ParserOutputStringSets $name A string set name
	 * @return string[] The string set value
	 * @since 1.41
	 */
	public function getOutputStrings( string|ParserOutputStringSets $name ): array {
		if ( is_string( $name ) ) {
			$name = ParserOutputStringSets::from( $name );
		}
		return match ( $name ) {
			ParserOutputStringSets::MODULE =>
				$this->getModules(),
			ParserOutputStringSets::MODULE_STYLE =>
				$this->getModuleStyles(),
			ParserOutputStringSets::EXTRA_CSP_DEFAULT_SRC =>
				$this->getExtraCSPDefaultSrcs(),
			ParserOutputStringSets::EXTRA_CSP_SCRIPT_SRC =>
				$this->getExtraCSPScriptSrcs(),
			ParserOutputStringSets::EXTRA_CSP_STYLE_SRC =>
				$this->getExtraCSPStyleSrcs(),
		};
	}

	/**
	 * Attaches arbitrary data to this ParserObject. This can be used to store some information in
	 * the ParserOutput object for later use during page output. The data will be cached along with
	 * the ParserOutput object, but unlike data set using setPageProperty(), it is not recorded in the
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
	 * @note Only scalar values, e.g. numbers, strings, arrays or MediaWiki\Json\JsonDeserializable
	 * instances are supported as a value. Attempt to set other class instance as extension data
	 * will break ParserCache for the page.
	 *
	 * @note Since MW 1.38 the practice of setting conflicting values for
	 * the same key has been deprecated.  As with ::setJsConfigVar(), if
	 * you set the same key multiple times on a ParserOutput, it is expected
	 * that the value will be identical each time.  If you want to collect
	 * multiple pieces of data under a single key, use ::appendExtensionData().
	 *
	 * @param string $key The key for accessing the data. Extensions should take care to avoid
	 *   conflicts in naming keys. It is suggested to use the extension's name as a prefix.
	 *
	 * @param mixed|JsonDeserializable $value The value to set.
	 *   Setting a value to null is equivalent to removing the value.
	 * @since 1.21
	 */
	public function setExtensionData( $key, $value ): void {
		if (
			array_key_exists( $key, $this->mExtensionData ) &&
			$this->mExtensionData[$key] !== $value
		) {
			// This behavior was deprecated in 1.38.  We will eventually
			// emit a warning here, then throw an exception.
		}
		if ( $value === null ) {
			unset( $this->mExtensionData[$key] );
		} else {
			$this->mExtensionData[$key] = $value;
		}
	}

	/**
	 * Appends arbitrary data to this ParserObject. This can be used
	 * to store some information in the ParserOutput object for later
	 * use during page output. The data will be cached along with the
	 * ParserOutput object, but unlike data set using
	 * setPageProperty(), it is not recorded in the database.
	 *
	 * See ::setExtensionData() for more details on rationale and use.
	 *
	 * In order to provide for out-of-order/asynchronous/incremental
	 * parsing, this method appends values to a set.  See
	 * ::setExtensionData() for the flag-like version of this method.
	 *
	 * @note Only values which can be array keys are currently supported
	 * as values.
	 *
	 * @param string $key The key for accessing the data. Extensions should take care to avoid
	 *   conflicts in naming keys. It is suggested to use the extension's name as a prefix.
	 *
	 * @param int|string $value The value to append to the list.
	 * @param string $strategy Merge strategy:
	 *  only MW_MERGE_STRATEGY_UNION is currently supported and external callers
	 *  should treat this parameter as @internal at this time and omit it.
	 * @since 1.38
	 */
	public function appendExtensionData(
		string $key,
		$value,
		string $strategy = self::MW_MERGE_STRATEGY_UNION
	): void {
		if ( $strategy !== self::MW_MERGE_STRATEGY_UNION ) {
			throw new InvalidArgumentException( "Unknown merge strategy $strategy." );
		}
		if ( !array_key_exists( $key, $this->mExtensionData ) ) {
			$this->mExtensionData[$key] = [
				// Indicate how these values are to be merged.
				self::MW_MERGE_STRATEGY_KEY => $strategy,
			];
		} elseif ( !is_array( $this->mExtensionData[$key] ) ) {
			throw new InvalidArgumentException( "Mixing set and append for $key" );
		} elseif ( ( $this->mExtensionData[$key][self::MW_MERGE_STRATEGY_KEY] ?? null ) !== $strategy ) {
			throw new InvalidArgumentException( "Conflicting merge strategies for $key" );
		}
		$this->mExtensionData[$key][$value] = true;
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
		$value = $this->mExtensionData[$key] ?? null;
		if ( is_array( $value ) ) {
			// Don't expose our internal merge strategy key.
			unset( $value[self::MW_MERGE_STRATEGY_KEY] );
		}
		return $value;
	}

	private static function getTimes( ?string $clock = null ): array {
		$ret = [];
		if ( !$clock || $clock === 'wall' ) {
			$ret['wall'] = hrtime( true ) / 10 ** 9;
		}
		if ( !$clock || $clock === 'cpu' ) {
			$ru = getrusage( 0 /* RUSAGE_SELF */ );
			$ret['cpu'] = $ru['ru_utime.tv_sec'] + $ru['ru_utime.tv_usec'] / 1e6;
			$ret['cpu'] += $ru['ru_stime.tv_sec'] + $ru['ru_stime.tv_usec'] / 1e6;
		}
		return $ret;
	}

	/**
	 * Resets the parse start timestamps for future calls to getTimeProfile()
	 * and recordTimeProfile().
	 *
	 * @since 1.22
	 */
	public function resetParseStartTime(): void {
		$this->mParseStartTime = self::getTimes();
		$this->mTimeProfile = [];
	}

	/**
	 * Unset the parse start time.
	 *
	 * This is intended for testing purposes only, in order to avoid
	 * spurious differences between testing outputs created at different
	 * times.
	 *
	 * @since 1.43
	 */
	public function clearParseStartTime(): void {
		$this->mParseStartTime = [];
	}

	/**
	 * Record the time since resetParseStartTime() was last called.
	 * The recorded time can be accessed using getTimeProfile().
	 *
	 * After resetParseStartTime() was called, the first call to recordTimeProfile()
	 * will record the time profile. Subsequent calls to recordTimeProfile() will have
	 * no effect until resetParseStartTime() is called again.
	 *
	 * @since 1.42
	 */
	public function recordTimeProfile() {
		if ( !$this->mParseStartTime ) {
			// If resetParseStartTime was never called, there is nothing to record
			return;
		}

		if ( $this->mTimeProfile !== [] ) {
			// Don't override the times recorded by the previous call to recordTimeProfile().
			return;
		}

		$now = self::getTimes();
		$this->mTimeProfile = [
			'wall' => $now['wall'] - $this->mParseStartTime['wall'],
			'cpu' => $now['cpu'] - $this->mParseStartTime['cpu'],
		];
	}

	/**
	 * Returns the time that elapsed between the most recent call to resetParseStartTime()
	 * and the first call to recordTimeProfile() after that.
	 *
	 * Clocks available are:
	 *  - wall: Wall clock time
	 *  - cpu: CPU time (requires getrusage)
	 *
	 * If recordTimeProfile() has noit been called since the most recent call to
	 * resetParseStartTime(), or if resetParseStartTime() was never called, then
	 * this method will return null.
	 *
	 * @param string $clock
	 *
	 * @since 1.42
	 * @return float|null
	 */
	public function getTimeProfile( string $clock ) {
		return $this->mTimeProfile[ $clock ] ?? null;
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
	 * HTML for display in the "NewPP limit report" comment.
	 *
	 * @since 1.22
	 * @param string $key Message key
	 * @param mixed $value Appropriate for Message::params()
	 */
	public function setLimitReportData( $key, $value ): void {
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
			[ $ns, $name ] = explode( '-', $key, 2 );
			$this->mLimitReportJSData[$ns][$name] = $data;
		} else {
			$this->mLimitReportJSData[$key] = $data;
		}
	}

	/**
	 * Check whether the cache TTL was lowered from the site default.
	 *
	 * When content is determined by more than hard state (e.g. page edits),
	 * such as template/file transclusions based on the current timestamp or
	 * extension tags that generate lists based on queries, this return true.
	 *
	 * This method mainly exists to facilitate the logic in
	 * WikiPage::triggerOpportunisticLinksUpdate. As such, beware that reducing the TTL for
	 * reasons that do not relate to "dynamic content", may have the side-effect of incurring
	 * more RefreshLinksJob executions.
	 *
	 * @internal For use by Parser and WikiPage
	 * @since 1.37
	 * @return bool
	 */
	public function hasReducedExpiry(): bool {
		if ( $this->getOutputFlag( ParserOutputFlags::HAS_ASYNC_CONTENT ) ) {
			// If this page has async content, then we should re-run
			// RefreshLinksJob whenever we regenerate the page.
			return true;
		}
		$parserCacheExpireTime = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::ParserCacheExpireTime );

		return $this->getCacheExpiry() < $parserCacheExpireTime;
	}

	public function getCacheExpiry(): int {
		$expiry = parent::getCacheExpiry();
		if ( $this->getOutputFlag( ParserOutputFlags::ASYNC_NOT_READY ) ) {
			$asyncExpireTime = MediaWikiServices::getInstance()->getMainConfig()->get(
				MainConfigNames::ParserCacheAsyncExpireTime
			);
			$expiry = min( $expiry, $asyncExpireTime );
		}
		return $expiry;
	}

	/**
	 * Set the prevent-clickjacking flag.  If set this will cause an
	 * `X-Frame-Options` header appropriate for edit pages to be sent.
	 * The header value is controlled by `$wgEditPageFrameOptions`.
	 *
	 * This is the default for special pages.  If you display a CSRF-protected
	 * form on an ordinary view page, then you need to call this function
	 * with `$flag = true`.
	 *
	 * @param bool $flag New flag value
	 * @since 1.38
	 */
	public function setPreventClickjacking( bool $flag ): void {
		$this->mPreventClickjacking = $flag;
	}

	/**
	 * Get the prevent-clickjacking flag.
	 *
	 * @return bool Flag value
	 * @since 1.38
	 * @see ::setPreventClickjacking
	 */
	public function getPreventClickjacking(): bool {
		return $this->mPreventClickjacking;
	}

	/**
	 * Lower the runtime adaptive TTL to at most this value
	 *
	 * @param int $ttl
	 * @since 1.28
	 */
	public function updateRuntimeAdaptiveExpiry( $ttl ): void {
		$this->mMaxAdaptiveExpiry = min( $ttl, $this->mMaxAdaptiveExpiry );
		$this->updateCacheExpiry( $ttl );
	}

	/**
	 * Add an extra value to Content-Security-Policy default-src directive
	 *
	 * Call this if you are including a resource (e.g. image) from a third party domain.
	 * This is used for all source types except style and script.
	 *
	 * @since 1.35
	 * @param string $src CSP source e.g. example.com
	 */
	public function addExtraCSPDefaultSrc( $src ): void {
		$this->mExtraDefaultSrcs[] = $src;
	}

	/**
	 * Add an extra value to Content-Security-Policy style-src directive
	 *
	 * @since 1.35
	 * @param string $src CSP source e.g. example.com
	 */
	public function addExtraCSPStyleSrc( $src ): void {
		$this->mExtraStyleSrcs[] = $src;
	}

	/**
	 * Add an extra value to Content-Security-Policy script-src directive
	 *
	 * Call this if you are loading third-party Javascript
	 *
	 * @since 1.35
	 * @param string $src CSP source e.g. example.com
	 */
	public function addExtraCSPScriptSrc( $src ): void {
		$this->mExtraScriptSrcs[] = $src;
	}

	/**
	 * Call this when parsing is done to lower the TTL based on low parse times
	 *
	 * @since 1.28
	 */
	public function finalizeAdaptiveCacheExpiry(): void {
		if ( is_infinite( $this->mMaxAdaptiveExpiry ) ) {
			return; // not set
		}

		$runtime = $this->getTimeProfile( 'wall' );
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

	/**
	 * Transfer parser options which affect post-processing from ParserOptions
	 * to this ParserOutput.
	 */
	public function setFromParserOptions( ParserOptions $parserOptions ) {
		// Copied from Parser.php::parse and should probably be abstracted
		// into the parent base class (probably as part of T236809)
		// Wrap non-interface parser output in a <div> so it can be targeted
		// with CSS (T37247)
		$class = $parserOptions->getWrapOutputClass();
		if ( $class !== false && !$parserOptions->isMessage() ) {
			$this->addWrapperDivClass( $class );
		}

		// Record whether we should suppress section edit links
		if ( $parserOptions->getSuppressSectionEditLinks() ) {
			$this->setOutputFlag( ParserOutputFlags::NO_SECTION_EDIT_LINKS );
		}

		// Record whether we should wrap sections for collapsing them
		if ( $parserOptions->getCollapsibleSections() ) {
			$this->setOutputFlag( ParserOutputFlags::COLLAPSIBLE_SECTIONS );
		}

		// Record whether this is a preview parse in the output (T341010)
		if ( $parserOptions->getIsPreview() ) {
			$this->setOutputFlag( ParserOutputFlags::IS_PREVIEW, true );
			// Ensure that previews aren't cacheable, just to be safe.
			$this->updateCacheExpiry( 0 );
		}

		// Record whether this was parsed with the legacy parser
		// (Unlike some other options here, this does/should fork the cache.)
		if ( $parserOptions->getUseParsoid() ) {
			$this->setOutputFlag( ParserOutputFlags::USE_PARSOID, true );
		}
	}

	/**
	 * Merges internal metadata such as flags, accessed options, and profiling info
	 * from $source into this ParserOutput. This should be used whenever the state of $source
	 * has any impact on the state of this ParserOutput.
	 */
	public function mergeInternalMetaDataFrom( ParserOutput $source ): void {
		$this->mWarnings = self::mergeMap( $this->mWarnings, $source->mWarnings ); // don't use getter
		$this->mWarningMsgs = self::mergeMap( $this->mWarningMsgs, $source->mWarningMsgs );
		$this->mTimestamp = $this->useMaxValue( $this->mTimestamp, $source->getRevisionTimestamp() );
		if ( $source->hasCacheTime() ) {
			$sourceCacheTime = $source->getCacheTime();
			if (
				!$this->hasCacheTime() ||
				// "undocumented use of -1 to mean not cacheable"
				// deprecated, but still supported by ::setCacheTime()
				strval( $sourceCacheTime ) === '-1' ||
				(
					strval( $this->getCacheTime() ) !== '-1' &&
					// use newer of the two times
					$this->getCacheTime() < $sourceCacheTime
				)
			) {
				$this->setCacheTime( $sourceCacheTime );
			}
		}
		if ( $source->getRenderId() !== null ) {
			// Final render ID should be a function of all component POs
			$rid = ( $this->getRenderId() ?? '' ) . $source->getRenderId();
			$this->setRenderId( $rid );
		}
		if ( $source->getCacheRevisionId() !== null ) {
			$sourceCacheRevisionId = $source->getCacheRevisionId();
			$thisCacheRevisionId = $this->getCacheRevisionId();
			if ( $thisCacheRevisionId === null ) {
				$this->setCacheRevisionId( $sourceCacheRevisionId );
			} elseif ( $sourceCacheRevisionId !== $thisCacheRevisionId ) {
				// May throw an exception here in the future
				wfDeprecated(
					__METHOD__ . ": conflicting revision IDs " .
					"$thisCacheRevisionId and $sourceCacheRevisionId"
				);
			}
		}

		foreach ( self::SPECULATIVE_FIELDS as $field ) {
			if ( $this->$field && $source->$field && $this->$field !== $source->$field ) {
				wfLogWarning( __METHOD__ . ": inconsistent '$field' properties!" );
			}
			$this->$field = $this->useMaxValue( $this->$field, $source->$field );
		}

		$this->mParseStartTime = $this->useEachMinValue(
			$this->mParseStartTime,
			$source->mParseStartTime
		);

		$this->mTimeProfile = $this->useEachTotalValue(
			$this->mTimeProfile,
			$source->mTimeProfile
		);

		$this->mFlags = self::mergeMap( $this->mFlags, $source->mFlags );
		$this->mParseUsedOptions = self::mergeMap( $this->mParseUsedOptions, $source->mParseUsedOptions );

		// TODO: maintain per-slot limit reports!
		if ( !$this->mLimitReportData ) {
			$this->mLimitReportData = $source->mLimitReportData;
		}
		if ( !$this->mLimitReportJSData ) {
			$this->mLimitReportJSData = $source->mLimitReportJSData;
		}
	}

	/**
	 * Merges HTML metadata such as head items, JS config vars, and HTTP cache control info
	 * from $source into this ParserOutput. This should be used whenever the HTML in $source
	 * has been somehow merged into the HTML of this ParserOutput.
	 */
	public function mergeHtmlMetaDataFrom( ParserOutput $source ): void {
		// HTML and HTTP
		$this->mHeadItems = self::mergeMixedList( $this->mHeadItems, $source->getHeadItems() );
		$this->addModules( $source->getModules() );
		$this->addModuleStyles( $source->getModuleStyles() );
		$this->mJsConfigVars = self::mergeMapStrategy( $this->mJsConfigVars, $source->mJsConfigVars );
		$this->mMaxAdaptiveExpiry = min( $this->mMaxAdaptiveExpiry, $source->mMaxAdaptiveExpiry );
		$this->mExtraStyleSrcs = self::mergeList(
			$this->mExtraStyleSrcs,
			$source->getExtraCSPStyleSrcs()
		);
		$this->mExtraScriptSrcs = self::mergeList(
			$this->mExtraScriptSrcs,
			$source->getExtraCSPScriptSrcs()
		);
		$this->mExtraDefaultSrcs = self::mergeList(
			$this->mExtraDefaultSrcs,
			$source->getExtraCSPDefaultSrcs()
		);

		// "noindex" always wins!
		$this->mIndexSet = $this->mIndexSet || $source->mIndexSet;
		$this->mNoIndexSet = $this->mNoIndexSet || $source->mNoIndexSet;

		// Skin control
		$this->mNewSection = $this->mNewSection || $source->getNewSection();
		$this->mHideNewSection = $this->mHideNewSection || $source->getHideNewSection();
		$this->mNoGallery = $this->mNoGallery || $source->getNoGallery();
		$this->mEnableOOUI = $this->mEnableOOUI || $source->getEnableOOUI();
		$this->mPreventClickjacking = $this->mPreventClickjacking || $source->getPreventClickjacking();

		$tocData = $this->getTOCData();
		$sourceTocData = $source->getTOCData();
		if ( $tocData !== null ) {
			if ( $sourceTocData !== null ) {
				// T327429: Section merging is broken, since it doesn't respect
				// global numbering, but there are tests which expect section
				// metadata to be concatenated.
				// There should eventually be a deprecation warning here.
				foreach ( $sourceTocData->getSections() as $s ) {
					$tocData->addSection( $s );
				}
			}
		} elseif ( $sourceTocData !== null ) {
			$this->setTOCData( $sourceTocData );
		}

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
		$this->mExtensionData = self::mergeMapStrategy(
			$this->mExtensionData,
			$source->mExtensionData
		);
	}

	/**
	 * Merges dependency tracking metadata such as backlinks, images used, and extension data
	 * from $source into this ParserOutput. This allows dependency tracking to be done for the
	 * combined output of multiple content slots.
	 */
	public function mergeTrackingMetaDataFrom( ParserOutput $source ): void {
		foreach (
			$source->getLinkList( ParserOutputLinkTypes::LANGUAGE )
			as [ 'link' => $link ]
		) {
			$this->addLanguageLink( $link );
		}
		$this->mCategories = self::mergeMap( $this->mCategories, $source->getCategoryMap() );
		foreach (
			$source->getLinkList( ParserOutputLinkTypes::LOCAL )
			as [ 'link' => $link, 'pageid' => $pageid ]
		) {
			$this->addLink( $link, $pageid );
		}
		foreach (
			$source->getLinkList( ParserOutputLinkTypes::TEMPLATE )
				as [ 'link' => $link, 'pageid' => $pageid, 'revid' => $revid ]
		) {
			$this->addTemplate( $link, $pageid, $revid );
		}
		foreach (
			$source->getLinkList( ParserOutputLinkTypes::EXISTENCE )
			as [ 'link' => $link ]
		) {
			$this->addExistenceDependency( $link );
		}
		foreach (
			$source->getLinkList( ParserOutputLinkTypes::MEDIA ) as $item
		) {
			$this->addImage( $item['link'], $item['time'] ?? null, $item['sha1'] ?? null );
		}
		$this->mExternalLinks = self::mergeMap( $this->mExternalLinks, $source->getExternalLinks() );
		foreach (
			$source->getLinkList( ParserOutputLinkTypes::INTERWIKI )
			as [ 'link' => $link ]
		) {
			$this->addInterwikiLink( $link );
		}

		foreach (
			$source->getLinkList( ParserOutputLinkTypes::SPECIAL )
			as [ 'link' => $link ]
		) {
			$this->addLink( $link );
		}

		// TODO: add a $mergeStrategy parameter to setPageProperty to allow different
		// kinds of properties to be merged in different ways.
		// (Model this after ::appendJsConfigVar(); use ::mergeMapStrategy here)
		$this->mProperties = self::mergeMap( $this->mProperties, $source->getPageProperties() );

		// NOTE: include extension data in "tracking meta data" as well as "html meta data"!
		$this->mExtensionData = self::mergeMapStrategy(
			$this->mExtensionData,
			$source->mExtensionData
		);
	}

	/**
	 * Adds the metadata collected in this ParserOutput to the supplied
	 * ContentMetadataCollector.  This is similar to ::mergeHtmlMetaDataFrom()
	 * but in the opposite direction, since ParserOutput is read/write while
	 * ContentMetadataCollector is write-only.
	 *
	 * @param ContentMetadataCollector $metadata
	 * @since 1.38
	 */
	public function collectMetadata( ContentMetadataCollector $metadata ): void {
		// Uniform handling of all boolean flags: they are OR'ed together.
		$flags = array_keys(
			$this->mFlags + array_flip( ParserOutputFlags::values() )
		);
		foreach ( $flags as $name ) {
			$name = (string)$name;
			if ( $this->getOutputFlag( $name ) ) {
				$metadata->setOutputFlag( $name );
			}
		}

		// Uniform handling of string sets: they are unioned.
		// (This includes modules, style modes, and CSP src.)
		foreach ( ParserOutputStringSets::values() as $name ) {
			$metadata->appendOutputStrings(
				$name, $this->getOutputStrings( $name )
			);
		}

		foreach ( $this->mCategories as $cat => $key ) {
			// Numeric category strings are going to come out of the
			// `mCategories` array as ints; cast back to string.
			// Also convert back to a LinkTarget!
			$lt = TitleValue::tryNew( NS_CATEGORY, (string)$cat );
			$metadata->addCategory( $lt, $key );
		}

		foreach ( $this->mLinks as $ns => $arr ) {
			foreach ( $arr as $dbk => $id ) {
				// Numeric titles are going to come out of the
				// `mLinks` array as ints; cast back to string.
				$lt = TitleValue::tryNew( $ns, (string)$dbk );
				$metadata->addLink( $lt, $id );
			}
		}

		foreach ( $this->mInterwikiLinks as $prefix => $arr ) {
			foreach ( $arr as $dbk => $ignore ) {
				$lt = TitleValue::tryNew( NS_MAIN, (string)$dbk, '', $prefix );
				$metadata->addLink( $lt );
			}
		}

		foreach ( $this->mLinksSpecial as $dbk => $ignore ) {
			// Numeric titles are going to come out of the
			// `mLinksSpecial` array as ints; cast back to string.
			$lt = TitleValue::tryNew( NS_SPECIAL, (string)$dbk );
			$metadata->addLink( $lt );
		}

		foreach ( $this->mImages as $name => $ignore ) {
			// Numeric titles come out of mImages as ints.
			$lt = TitleValue::tryNew( NS_FILE, (string)$name );
			$props = $this->mFileSearchOptions[$name] ?? [];
			$metadata->addImage( $lt, $props['time'] ?? null, $props['sha1'] ?? null );
		}

		foreach ( $this->mLanguageLinkMap as $lang => $title ) {
			# language links can have fragments!
			[ $title, $frag ] = array_pad( explode( '#', $title, 2 ), 2, '' );
			$lt = TitleValue::tryNew( NS_MAIN, $title, $frag, (string)$lang );
			$metadata->addLanguageLink( $lt );
		}

		foreach ( $this->mJsConfigVars as $key => $value ) {
			// Numeric keys and items are going to come out of the
			// `mJsConfigVars` array as ints; cast back to string.
			$key = (string)$key;
			if ( is_array( $value ) && isset( $value[self::MW_MERGE_STRATEGY_KEY] ) ) {
				$strategy = $value[self::MW_MERGE_STRATEGY_KEY];
				foreach ( $value as $item => $ignore ) {
					$item = (string)$item;
					if ( $item !== self::MW_MERGE_STRATEGY_KEY ) {
						$metadata->appendJsConfigVar( $key, $item, $strategy );
					}
				}
			} elseif ( $metadata instanceof ParserOutput &&
				array_key_exists( $key, $metadata->mJsConfigVars )
			) {
				// This behavior is deprecated, will likely result in
				// incorrect output, and we'll eventually emit a
				// warning here---but at the moment this is usually
				// caused by limitations in Parsoid and/or use of
				// the ParserAfterParse hook: T303015#7770480
				$metadata->mJsConfigVars[$key] = $value;
			} else {
				$metadata->setJsConfigVar( $key, $value );
			}
		}
		foreach ( $this->mExtensionData as $key => $value ) {
			// Numeric keys and items are going to come out of the array as
			// ints, cast back to string.
			$key = (string)$key;
			if ( is_array( $value ) && isset( $value[self::MW_MERGE_STRATEGY_KEY] ) ) {
				$strategy = $value[self::MW_MERGE_STRATEGY_KEY];
				foreach ( $value as $item => $ignore ) {
					$item = (string)$item;
					if ( $item !== self::MW_MERGE_STRATEGY_KEY ) {
						$metadata->appendExtensionData( $key, $item, $strategy );
					}
				}
			} elseif ( $metadata instanceof ParserOutput &&
				array_key_exists( $key, $metadata->mExtensionData )
			) {
				// This behavior is deprecated, will likely result in
				// incorrect output, and we'll eventually emit a
				// warning here---but at the moment this is usually
				// caused by limitations in Parsoid and/or use of
				// the ParserAfterParse hook: T303015#7770480
				$metadata->mExtensionData[$key] = $value;
			} else {
				$metadata->setExtensionData( $key, $value );
			}
		}
		foreach ( $this->mExternalLinks as $url => $ignore ) {
			$metadata->addExternalLink( (string)$url );
		}
		foreach ( $this->mProperties as $prop => $value ) {
			// Numeric properties are going to come out of the array as ints
			$prop = (string)$prop;
			if ( is_numeric( $value ) ) {
				$metadata->setNumericPageProperty( $prop, $value );
			} elseif ( is_string( $value ) ) {
				$metadata->setUnsortedPageProperty( $prop, $value );
			} else {
				// Deprecated, but there are still sites which call
				// ::setPageProperty() with "unusual" values (T374046)
				wfDeprecated( __METHOD__ . ' with unusual page property', '1.45' );
			}
		}
		foreach ( $this->mWarningMsgs as $key => $msg ) {
			$metadata->addWarningMsgVal( $msg, (string)$key );
		}
		foreach ( $this->mLimitReportData as $key => $value ) {
			$metadata->setLimitReportData( (string)$key, $value );
		}
		foreach ( $this->mIndicators as $id => $content ) {
			$metadata->setIndicator( (string)$id, $content );
		}

		// ParserOutput-only fields; maintained "behind the curtain"
		// since Parsoid doesn't have to know about them.
		//
		// In production use, the $metadata supplied to this method
		// will almost always be an instance of ParserOutput, passed to
		// Parsoid by core when parsing begins and returned to core by
		// Parsoid as a ContentMetadataCollector (Parsoid's name for
		// ParserOutput) when DataAccess::parseWikitext() is called.
		//
		// We may use still Parsoid's StubMetadataCollector for testing or
		// when running Parsoid in standalone mode, so forcing a downcast
		// here would lose some flexibility.

		if ( $metadata instanceof ParserOutput ) {
			foreach ( $this->getUsedOptions() as $opt ) {
				$metadata->recordOption( $opt );
			}
			if ( !is_infinite( $this->mMaxAdaptiveExpiry ) ) {
				$metadata->updateRuntimeAdaptiveExpiry( $this->mMaxAdaptiveExpiry );
			}
			if ( $this->mCacheExpiry !== null ) {
				$metadata->updateCacheExpiry( $this->mCacheExpiry );
			}
			if ( $this->mCacheTime !== '' ) {
				$metadata->setCacheTime( $this->mCacheTime );
			}
			if ( $this->mCacheRevisionId !== null ) {
				$metadata->setCacheRevisionId( $this->mCacheRevisionId );
			}
			// T293514: We should use the first *modified* title text, but
			// we don't have the original to check.
			$otherTitle = $metadata->getTitleText();
			if ( $otherTitle === null || $otherTitle === '' ) {
				$metadata->setTitleText( $this->getTitleText() );
			}
			foreach ( $this->mTemplates as $ns => $arr ) {
				foreach ( $arr as $dbk => $page_id ) {
					// default to invalid/broken revision if this is not present
					$rev_id = $this->mTemplateIds[$ns][$dbk] ?? 0;
					$metadata->addTemplate( TitleValue::tryNew( $ns, (string)$dbk ), $page_id, $rev_id );
				}
			}
			foreach (
				$this->getLinkList( ParserOutputLinkTypes::EXISTENCE )
				as [ 'link' => $link ]
			) {
				$metadata->addExistenceDependency( $link );
			}
		}
	}

	private static function mergeMixedList( array $a, array $b ): array {
		return array_unique( array_merge( $a, $b ), SORT_REGULAR );
	}

	private static function mergeList( array $a, array $b ): array {
		return array_values( array_unique( array_merge( $a, $b ), SORT_REGULAR ) );
	}

	private static function mergeMap( array $a, array $b ): array {
		return array_replace( $a, $b );
	}

	private static function mergeMapStrategy( array $a, array $b ): array {
		foreach ( $b as $key => $bValue ) {
			if ( !array_key_exists( $key, $a ) ) {
				$a[$key] = $bValue;
			} elseif (
				is_array( $a[$key] ) &&
				isset( $a[$key][self::MW_MERGE_STRATEGY_KEY] ) &&
				isset( $bValue[self::MW_MERGE_STRATEGY_KEY] )
			) {
				$strategy = $bValue[self::MW_MERGE_STRATEGY_KEY];
				if ( $strategy !== $a[$key][self::MW_MERGE_STRATEGY_KEY] ) {
					throw new InvalidArgumentException( "Conflicting merge strategy for $key" );
				}
				if ( $strategy === self::MW_MERGE_STRATEGY_UNION ) {
					// Note the array_merge is *not* safe to use here, because
					// the $bValue is expected to be a map from items to `true`.
					// If the item is a numeric string like '1' then array_merge
					// will convert it to an integer and renumber the array!
					$a[$key] = array_replace( $a[$key], $bValue );
				} else {
					throw new InvalidArgumentException( "Unknown merge strategy $strategy" );
				}
			} else {
				$valuesSame = ( $a[$key] === $bValue );
				if ( ( !$valuesSame ) &&
					is_object( $a[$key] ) &&
					is_object( $bValue )
				) {
					$jsonCodec = MediaWikiServices::getInstance()->getJsonCodec();
					$valuesSame = ( $jsonCodec->serialize( $a[$key] ) === $jsonCodec->serialize( $bValue ) );
				}
				if ( !$valuesSame ) {
					// Silently replace for now; in the future will first emit
					// a deprecation warning, and then (later) throw.
					$a[$key] = $bValue;
				}
			}
		}
		return $a;
	}

	private static function useEachMinValue( array $a, array $b ): array {
		$values = [];
		$keys = array_merge( array_keys( $a ), array_keys( $b ) );

		foreach ( $keys as $k ) {
			$values[$k] = min( $a[$k] ?? INF, $b[$k] ?? INF );
		}

		return $values;
	}

	private static function useEachTotalValue( array $a, array $b ): array {
		$values = [];
		$keys = array_merge( array_keys( $a ), array_keys( $b ) );

		foreach ( $keys as $k ) {
			$values[$k] = ( $a[$k] ?? 0 ) + ( $b[$k] ?? 0 );
		}

		return $values;
	}

	/**
	 * @param string|int|null $a
	 * @param string|int|null $b
	 * @return string|int|null
	 */
	private static function useMaxValue( $a, $b ) {
		if ( $a === null ) {
			return $b;
		}

		if ( $b === null ) {
			return $a;
		}

		return max( $a, $b );
	}

	/**
	 * Returns a JSON serializable structure representing this ParserOutput instance.
	 * @see newFromJson()
	 *
	 * @return array
	 */
	protected function toJsonArray(): array {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!

		$data = [
			'Text' => $this->mRawText,
			'LanguageLinks' => $this->getLanguageLinks(),
			'Categories' => $this->mCategories,
			'Indicators' => $this->mIndicators,
			'TitleText' => $this->mTitleText,
			'Links' => $this->mLinks,
			'LinksSpecial' => $this->mLinksSpecial,
			'Templates' => $this->mTemplates,
			'TemplateIds' => $this->mTemplateIds,
			'Images' => $this->mImages,
			'FileSearchOptions' => $this->mFileSearchOptions,
			'ExternalLinks' => $this->mExternalLinks,
			'InterwikiLinks' => $this->mInterwikiLinks,
			'ExistenceLinks' => $this->existenceLinks,
			'NewSection' => $this->mNewSection,
			'HideNewSection' => $this->mHideNewSection,
			'NoGallery' => $this->mNoGallery,
			'HeadItems' => $this->mHeadItems,
			'Modules' => array_keys( $this->mModuleSet ),
			'ModuleStyles' => array_keys( $this->mModuleStyleSet ),
			'JsConfigVars' => $this->mJsConfigVars,
			'Warnings' => $this->mWarnings,
			'WarningMsgs' => $this->mWarningMsgs,
			'Sections' => $this->getSections(),
			'Properties' => self::detectAndEncodeBinary( $this->mProperties ),
			'Timestamp' => $this->mTimestamp,
			'EnableOOUI' => $this->mEnableOOUI,
			'IndexPolicy' => $this->getIndexPolicy(),
			// may contain arbitrary structures!
			'ExtensionData' => $this->mExtensionData,
			'LimitReportData' => $this->mLimitReportData,
			'LimitReportJSData' => $this->mLimitReportJSData,
			'CacheMessage' => $this->mCacheMessage,
			'TimeProfile' => $this->mTimeProfile,
			'ParseStartTime' => [], // don't serialize this
			'PreventClickjacking' => $this->mPreventClickjacking,
			'ExtraScriptSrcs' => $this->mExtraScriptSrcs,
			'ExtraDefaultSrcs' => $this->mExtraDefaultSrcs,
			'ExtraStyleSrcs' => $this->mExtraStyleSrcs,
			'Flags' => $this->mFlags + (
				// backward-compatibility: distinguish "no sections" from
				// "sections not set" (Will be unnecessary after T327439.)
				$this->mTOCData === null ? [] : [ 'mw:toc-set' => true ]
			),
			'SpeculativeRevId' => $this->mSpeculativeRevId,
			'SpeculativePageIdUsed' => $this->speculativePageIdUsed,
			'RevisionTimestampUsed' => $this->revisionTimestampUsed,
			'RevisionUsedSha1Base36' => $this->revisionUsedSha1Base36,
			'WrapperDivClasses' => $this->mWrapperDivClasses,
		];

		// Fill in missing fields from parents. Array addition does not override existing fields.
		$data += parent::toJsonArray();

		// TODO: make more fields optional!

		if ( $this->mMaxAdaptiveExpiry !== INF ) {
			// NOTE: JSON can't encode infinity!
			$data['MaxAdaptiveExpiry'] = $this->mMaxAdaptiveExpiry;
		}

		if ( $this->mTOCData ) {
			// Temporarily add information from TOCData extension data
			// T327439: We should eventually make the entire mTOCData
			// serializable
			$toc = $this->mTOCData->jsonSerialize();
			if ( isset( $toc['extensionData'] ) ) {
				$data['TOCExtensionData'] = $toc['extensionData'];
			}
		}

		return $data;
	}

	public static function newFromJsonArray( JsonDeserializer $deserializer, array $json ): ParserOutput {
		$parserOutput = new ParserOutput();
		$parserOutput->initFromJson( $deserializer, $json );
		return $parserOutput;
	}

	/**
	 * Initialize member fields from an array returned by jsonSerialize().
	 * @param JsonDeserializer $deserializer
	 * @param array $jsonData
	 */
	protected function initFromJson( JsonDeserializer $deserializer, array $jsonData ): void {
		parent::initFromJson( $deserializer, $jsonData );

		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!

		$this->mRawText = $jsonData['Text'];
		$this->mLanguageLinkMap = [];
		foreach ( ( $jsonData['LanguageLinks'] ?? [] ) as $l ) {
			// T374736: old serialized parser cache entries may
			// contain invalid language links; drop them quietly.
			// (This code can be removed two LTS releases past 1.45.)
			if ( str_contains( $l, ':' ) ) {
				$this->addLanguageLink( $l );
			}
		}
		$this->mCategories = $jsonData['Categories'];
		$this->mIndicators = $jsonData['Indicators'];
		$this->mTitleText = $jsonData['TitleText'];
		$this->mLinks = $jsonData['Links'];
		$this->mLinksSpecial = $jsonData['LinksSpecial'];
		$this->mTemplates = $jsonData['Templates'];
		$this->mTemplateIds = $jsonData['TemplateIds'];
		$this->mImages = $jsonData['Images'];
		$this->mFileSearchOptions = $jsonData['FileSearchOptions'];
		$this->mExternalLinks = $jsonData['ExternalLinks'];
		$this->mInterwikiLinks = $jsonData['InterwikiLinks'];
		$this->existenceLinks = $jsonData['ExistenceLinks'] ?? [];
		$this->mNewSection = $jsonData['NewSection'];
		$this->mHideNewSection = $jsonData['HideNewSection'];
		$this->mNoGallery = $jsonData['NoGallery'];
		$this->mHeadItems = $jsonData['HeadItems'];
		$this->mModuleSet = array_fill_keys( $jsonData['Modules'], true );
		$this->mModuleStyleSet = array_fill_keys( $jsonData['ModuleStyles'], true );
		$this->mJsConfigVars = $jsonData['JsConfigVars'];
		$this->mWarnings = $jsonData['Warnings'] ?? [];
		$this->mWarningMsgs = $jsonData['WarningMsgs'] ?? [];
		$this->mFlags = $jsonData['Flags'];
		if ( isset( $jsonData['TOCData'] ) ) {
			$this->mTOCData = $jsonData['TOCData'];
		// Backward-compatibility with old TOCData encoding (T327439)
		// emitted in MW < 1.45
		} elseif (
			( $jsonData['Sections'] ?? [] ) !== [] ||
			// distinguish "no sections" from "sections not set"
			$this->getOutputFlag( 'mw:toc-set' )
		) {
			$this->setSections( $jsonData['Sections'] ?? [] );
			unset( $this->mFlags['mw:toc-set'] );
			if ( isset( $jsonData['TOCExtensionData'] ) ) {
				$tocData = $this->getTOCData(); // created by setSections() above
				foreach ( $jsonData['TOCExtensionData'] as $key => $value ) {
					$tocData->setExtensionData( (string)$key, $value );
				}
			}
		}
		// backward-compatibility: convert page properties to their
		// 'database representation'.  We haven't permitted non-string
		// non-numeric values since 1.45.
		$this->mProperties = [];
		foreach (
			self::detectAndDecodeBinary( $jsonData['Properties'] )
			as $k => $v
		) {
			if ( is_int( $v ) || is_float( $v ) || is_string( $v ) ) {
				$this->mProperties[$k] = $v;
			} elseif ( is_bool( $v ) ) {
				$this->mProperties[$k] = (int)$v;
			} elseif ( $v === null ) {
				$this->mProperties[$k] = '';
			} elseif ( is_array( $v ) ) {
				$this->mProperties[$k] = 'Array';
			} else {
				$this->mProperties[$k] = strval( $v );
			}
		}
		$this->mTimestamp = $jsonData['Timestamp'];
		$this->mEnableOOUI = $jsonData['EnableOOUI'];
		$this->setIndexPolicy( $jsonData['IndexPolicy'] );
		$this->mExtensionData = $jsonData['ExtensionData'] ?? [];
		$this->mLimitReportData = $jsonData['LimitReportData'];
		$this->mLimitReportJSData = $jsonData['LimitReportJSData'];
		$this->mCacheMessage = $jsonData['CacheMessage'] ?? '';
		$this->mParseStartTime = []; // invalid after reloading
		$this->mTimeProfile = $jsonData['TimeProfile'] ?? [];
		$this->mPreventClickjacking = $jsonData['PreventClickjacking'];
		$this->mExtraScriptSrcs = $jsonData['ExtraScriptSrcs'];
		$this->mExtraDefaultSrcs = $jsonData['ExtraDefaultSrcs'];
		$this->mExtraStyleSrcs = $jsonData['ExtraStyleSrcs'];
		$this->mSpeculativeRevId = $jsonData['SpeculativeRevId'];
		$this->speculativePageIdUsed = $jsonData['SpeculativePageIdUsed'];
		$this->revisionTimestampUsed = $jsonData['RevisionTimestampUsed'];
		$this->revisionUsedSha1Base36 = $jsonData['RevisionUsedSha1Base36'];
		$this->mWrapperDivClasses = $jsonData['WrapperDivClasses'];
		$this->mMaxAdaptiveExpiry = $jsonData['MaxAdaptiveExpiry'] ?? INF;
	}

	/**
	 * Finds any non-utf8 strings in the given array and replaces them with
	 * an associative array that wraps a base64 encoded version of the data.
	 * Inverse of detectAndDecodeBinary().
	 *
	 * @param array $properties
	 *
	 * @return array
	 */
	private static function detectAndEncodeBinary( array $properties ) {
		foreach ( $properties as $key => $value ) {
			if ( is_string( $value ) ) {
				if ( !mb_detect_encoding( $value, 'UTF-8', true ) ) {
					$properties[$key] = [
						// T313818: This key name conflicts with JsonCodec
						'_type_' => 'string',
						'_encoding_' => 'base64',
						'_data_' => base64_encode( $value ),
					];
				}
			}
		}

		return $properties;
	}

	/**
	 * Finds any associative arrays that represent encoded binary strings, and
	 * replaces them with the decoded binary data.
	 *
	 * @param array $properties
	 *
	 * @return array
	 */
	private static function detectAndDecodeBinary( array $properties ) {
		foreach ( $properties as $key => $value ) {
			if ( is_array( $value ) && isset( $value['_encoding_'] ) ) {
				if ( $value['_encoding_'] === 'base64' ) {
					$properties[$key] = base64_decode( $value['_data_'] );
				}
			}
		}

		return $properties;
	}

	public function __clone() {
		// It seems that very little of this object needs to be explicitly deep-cloned
		// while keeping copies reasonably separated.
		// Most of the non-scalar properties of this object are either
		// - (potentially multi-nested) arrays of scalars (which get deep-cloned), or
		// - arrays that may contain arbitrary elements (which don't necessarily get
		//   deep-cloned), but for which no particular care elsewhere is given to
		//   copying their references around (e.g. mJsConfigVars).
		// Hence, we are not going out of our way to ensure that the references to innermost
		// objects that may appear in a ParserOutput are unique. If that becomes the
		// expectation at any point, this method will require updating as well.
		// The exception is TOCData (which is an object), which we clone explicitly.
		if ( $this->mTOCData ) {
			$this->mTOCData = clone $this->mTOCData;
		}
	}

	/**
	 * Returns the content holder text of the ParserOutput.
	 * This will eventually be replaced by something like getContentHolder()->getText() when we have a
	 * ContentHolder/HtmlHolder class.
	 * @internal
	 * @unstable
	 * @return string
	 */
	public function getContentHolderText(): string {
		return $this->getRawText();
	}

	/**
	 * Sets the content holder text of the ParserOutput.
	 * This will eventually be replaced by something like getContentHolder()->setText() when we have a
	 * ContentHolder/HtmlHolder class.
	 * @internal
	 * @unstable
	 */
	public function setContentHolderText( string $s ): void {
		$this->setRawText( $s );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( ParserOutput::class, 'ParserOutput' );
