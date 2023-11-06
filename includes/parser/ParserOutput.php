<?php
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

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Json\JsonUnserializable;
use MediaWiki\Json\JsonUnserializableTrait;
use MediaWiki\Json\JsonUnserializer;
use MediaWiki\Language\RawMessage;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Parser\ParserOutputStringSets;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Title\Title;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Parsoid\Core\ContentMetadataCollector;
use Wikimedia\Parsoid\Core\ContentMetadataCollectorCompat;
use Wikimedia\Parsoid\Core\TOCData;
use Wikimedia\Reflection\GhostFieldAccessTrait;

/**
 * Rendered output of a wiki page, as parsed from wikitext.
 *
 * ParserOutput objects are created by the ParserOutputAccess service,
 * which automatically caches them via ParserCache when possible,
 * and produces new output from the Parser (or Parsoid) as-needed.
 *
 * Higher-level access is also available via the ContentHandler class,
 * with as its main consumers our APIs and OutputPage/Skin frontend.
 *
 * @ingroup Parser
 */
class ParserOutput extends CacheTime implements ContentMetadataCollector {
	use GhostFieldAccessTrait;
	use JsonUnserializableTrait;
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
	 * @internal
	 * String Key used to store the parsoid render ID in ParserOutput
	 */
	public const PARSOID_RENDER_ID_KEY = 'parsoid-render-id';

	/**
	 * @var string|null The output text
	 */
	private $mText = null;

	/**
	 * @var string[] List of the full text of language links, in the order they appear.
	 */
	private $mLanguageLinks;

	/**
	 * @var array<string,string> Map of category names to sort keys
	 */
	private $mCategories;

	/**
	 * @var array<string,string> Page status indicators, usually displayed in top-right corner.
	 */
	private $mIndicators = [];

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
	 * @var array<string,array> DB keys of the images used mapped to sha1 and MW timestamp.
	 */
	private $mFileSearchOptions = [];

	/**
	 * @var array<string,int> External link URLs, in the key only.
	 */
	private $mExternalLinks = [];

	/**
	 * @var array<string,array<string,int>> 2-D map of prefix/DBK (in keys only)
	 *  for the inline interwiki links in the document.
	 */
	private $mInterwikiLinks = [];

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
	 * @var string[] Modules to be loaded by ResourceLoader
	 */
	private $mModules = [];

	/**
	 * @var string[] Modules of which only the CSS will be loaded by ResourceLoader.
	 */
	private $mModuleStyles = [];

	/**
	 * @var array JavaScript config variable for mw.config combined with this page.
	 */
	private $mJsConfigVars = [];

	/**
	 * @var array<string,int> Warning text to be returned to the user.
	 *  Wikitext formatted, in the key only.
	 */
	private $mWarnings = [];

	/**
	 * @var array<string,array> *Unformatted* warning messages and
	 * arguments to be returned to the user.  This is for internal use
	 * when merging ParserOutputs and are not serialized/deserialized.
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
	 * @var string HTML of the TOC.
	 */
	private $mTOCHTML = '';

	/**
	 * @var string Timestamp of the revision.
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
	 * @var array Timestamps for getTimeSinceStart().
	 */
	private $mParseStartTime = [];

	/**
	 * @var bool Whether to emit X-Frame-Options: DENY.
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

	/** @var string[] */
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
	 */
	private $mWrapperDivClasses = [];

	/** @var int Upper bound of expiry based on parse duration */
	private $mMaxAdaptiveExpiry = INF;

	private const EDITSECTION_REGEX =
		'#<mw:editsection page="(.*?)" section="(.*?)">(.*?)</mw:editsection>#s';

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
	public function __construct( $text = null, $languageLinks = [], $categoryLinks = [],
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
	public function hasText(): bool {
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
	 *  - bodyContentOnly: (bool) . Default: true
	 * @return string HTML
	 * @return-taint escaped
	 */
	public function getText( $options = [] ) {
		$options += [
			'allowTOC' => true,
			'injectTOC' => true,
			'enableSectionEditLinks' => true,
			'userLang' => null,
			'skin' => null,
			'unwrap' => false,
			'wrapperDivClass' => $this->getWrapperDivClass(),
			'deduplicateStyles' => true,
			'absoluteURLs' => false,
			'includeDebugInfo' => false,
			'bodyContentOnly' => true,
		];
		$text = $this->getRawText();
		if (
			$options['bodyContentOnly'] &&
			PageBundleParserOutputConverter::hasPageBundle( $this )
		) {
			// This is a full HTML document, generated by Parsoid.
			// Strip everything but the <body>
			// Probably would be better to process this as a DOM.
			$text = preg_replace( '!^.*?<body[^>]*>!s', '', $text, 1 );
			$text = preg_replace( '!</body>\s*</html>\s*$!', '', $text, 1 );
		}

		$redirectHeader = $this->getRedirectHeader();
		if ( $redirectHeader ) {
			$text = $redirectHeader . $text;
		}

		if ( $options['includeDebugInfo'] ) {
			$text .= $this->renderDebugInfo();
		}

		( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
			->onParserOutputPostCacheTransform( $this, $text, $options );

		if ( $options['wrapperDivClass'] !== '' && !$options['unwrap'] ) {
			$text = Html::rawElement( 'div', [ 'class' => $options['wrapperDivClass'] ], $text );
		}

		if ( $options['enableSectionEditLinks'] ) {
			// TODO: Skin should not be required.
			// It would be better to define one or more narrow interfaces to use here,
			// so this code doesn't have to depend on all of Skin.
			// See OutputPage::addParserOutputText()
			$skin = $options['skin'] ?: RequestContext::getMain()->getSkin();

			$text = preg_replace_callback(
				self::EDITSECTION_REGEX,
				function ( $m ) use ( $skin ) {
					$editsectionPage = Title::newFromText( htmlspecialchars_decode( $m[1] ) );
					$editsectionSection = htmlspecialchars_decode( $m[2] );
					$editsectionContent = Sanitizer::decodeCharReferences( $m[3] );

					if ( !is_object( $editsectionPage ) ) {
						LoggerFactory::getInstance( 'Parser' )
							->error(
								'ParserOutput::getText(): bad title in editsection placeholder',
								[
									'placeholder' => $m[0],
									'editsectionPage' => $m[1],
									'titletext' => $this->getTitleText(),
									'phab' => 'T261347',
								]
							);
						return '';
					}

					return $skin->doEditSectionLink(
						$editsectionPage,
						$editsectionSection,
						$editsectionContent,
						$skin->getLanguage()
					);
				},
				$text
			);
		} else {
			$text = preg_replace( self::EDITSECTION_REGEX, '', $text );
		}

		if ( $options['allowTOC'] ) {
			if ( $options['injectTOC'] ) {
				if ( count( $this->getSections() ) === 0 ) {
					$toc = '';
				} else {
					$userLang = $options['userLang'];
					$skin = $options['skin'];
					if ( ( !$userLang ) && $skin ) {
						// TODO: See above comment about replacing the use
						// of 'skin' here.
						$userLang = $skin->getLanguage();
					}
					if ( !$userLang ) {
						$userLang = RequestContext::getMain()->getLanguage();
					}
					$toc = Linker::generateTOC( $this->getTOCData(), $userLang );

					// XXX Use DI to inject this once ::getText() is moved out of ParserOutput.
					$services = MediaWikiServices::getInstance();
					$toc = $services->getTidy()->tidy( $toc, [ Sanitizer::class, 'armorFrenchSpaces' ] );
				}
				$text = Parser::replaceTableOfContentsMarker( $text, $toc );
			}
		} else {
			$text = Parser::replaceTableOfContentsMarker( $text, '' );
		}

		if ( $options['deduplicateStyles'] ) {
			$seen = [];
			$text = preg_replace_callback(
				'#<style\s+([^>]*data-mw-deduplicate\s*=[^>]*)>.*?</style>#s',
				static function ( $m ) use ( &$seen ) {
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

		// Expand all relative URLs
		if ( $options['absoluteURLs'] && $text ) {
			$text = Linker::expandLocalLinks( $text );
		}

		// Hydrate slot section header placeholders generated by RevisionRenderer.
		$text = preg_replace_callback(
			'#<mw:slotheader>(.*?)</mw:slotheader>#',
			static function ( $m ) {
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
	 *
	 * @return string
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

	public function &getLanguageLinks() {
		return $this->mLanguageLinks;
	}

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
	 * @deprecated since 1.40; use ::getCategoryNames() and
	 * ::getCategorySortKey() instead. For @internal use ::getCategoryMap()
	 * may be used, but it is not recommended.
	 */
	public function &getCategories() {
		wfDeprecated( __METHOD__, '1.40' );
		return $this->mCategories;
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
	 * category is not present in this ParserOutput.
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
	 * @return string[]
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
	 * @return array
	 */
	public function getSections(): array {
		if ( $this->mTOCData !== null ) {
			return $this->mTOCData->toLegacy();
		}
		// For compatibility
		return [];
	}

	public function &getLinks() {
		return $this->mLinks;
	}

	/**
	 * @return array Keys are DBKs for the links to special pages in the document
	 * @since 1.35
	 */
	public function &getLinksSpecial() {
		return $this->mLinksSpecial;
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
		return $this->mModules;
	}

	public function getModuleStyles() {
		return $this->mModuleStyles;
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

	public function getWarnings(): array {
		return array_keys( $this->mWarnings );
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
	 * @deprecated since 1.40; use ::getTOCData() instead
	 */
	public function getTOCHTML() {
		wfDeprecated( __METHOD__, '1.40' );
		return $this->mTOCHTML;
	}

	/**
	 * @return bool
	 * @internal Only for the temporary use of
	 *   OutputPage::addParserOutputMetadata; will be removed with
	 *   ::{get,set}TOCHTML().
	 */
	public function hasTOCHTML() {
		return (bool)$this->mTOCHTML;
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
	 * Set the text of the ParserOutput.
	 *
	 * If you did not generate html, pass null to mark it as such.
	 *
	 * @since 1.39 You can now pass null to this function
	 * @param string|null $text HTML content of ParserOutput or null if not generated
	 * @return string|null Previous value of ParserOutput's text
	 */
	public function setText( $text ) {
		return wfSetVar( $this->mText, $text, true );
	}

	public function setLanguageLinks( $ll ) {
		return wfSetVar( $this->mLanguageLinks, $ll );
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
	 * @internal
	 * @deprecated since 1.40
	 * T293513: We can remove this once we get rid of MW 1.38 and older
	 * parsercache serialization tests since those serialized
	 * files have artificial TOC data (which we cannot replicate
	 * via on-demand TOC generation).
	 */
	public function setTOCHTML( $tochtml ) {
		wfDeprecated( __METHOD__, '1.40' );
		return wfSetVar( $this->mTOCHTML, $tochtml );
	}

	public function setTimestamp( $timestamp ) {
		return wfSetVar( $this->mTimestamp, $timestamp );
	}

	/**
	 * Add a category.
	 * @param string $c The category name
	 * @param string $sort The sort key
	 */
	public function addCategory( $c, $sort = '' ): void {
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

	public function addLanguageLink( $t ): void {
		$this->mLanguageLinks[] = $t;
	}

	/**
	 * Add a warning to the output for this page.
	 * @param string $msg The localization message key for the warning
	 * @param mixed|JsonUnserializable ...$args Optional arguments for the
	 *   message. These arguments must be serializable/unserializable with
	 *   JsonCodec; see the @note on ParserOutput::setExtensionData()
	 * @since 1.38
	 */
	public function addWarningMsg( string $msg, ...$args ): void {
		// preserve original arguments in $mWarningMsgs to allow merge
		// @todo: these aren't serialized/unserialized yet -- before we
		// turn on serialization of $this->mWarningMsgs we need to ensure
		// callers aren't passing nonserializable arguments: T343048.
		$jsonCodec = MediaWikiServices::getInstance()->getJsonCodec();
		$path = $jsonCodec->detectNonSerializableData( $args, true );
		if ( $path !== null ) {
			wfDeprecatedMsg(
				"ParserOutput::addWarningMsg() called with nonserializable arguments: $path",
				'1.41'
			);
		}
		$this->mWarningMsgs[$msg] = $args;
		$s = wfMessage( $msg, ...$args )
			// some callers set the title here?
			->inContentLanguage() // because this ends up in cache
			->text();
		$this->mWarnings[$s] = 1;
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
			( substr( $internal, 0, 2 ) === '//' ? '(?:https?:)?' : '' ) .
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
	 * @param LinkTarget $link (used to require Title until 1.38)
	 * @param int|null $id Optional known page_id so we can skip the lookup
	 */
	public function addLink( LinkTarget $link, $id = null ): void {
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
			$page = MediaWikiServices::getInstance()->getPageStore()->getPageForLink( $link );
			$id = $page->getId();
		}
		$this->mLinks[$ns][$dbk] = $id;
	}

	/**
	 * Register a file dependency for this output
	 * @param string $name Title dbKey
	 * @param string|false|null $timestamp MW timestamp of file creation (or false if non-existing)
	 * @param string|false|null $sha1 Base 36 SHA-1 of file (or false if non-existing)
	 */
	public function addImage( $name, $timestamp = null, $sha1 = null ): void {
		$this->mImages[$name] = 1;
		if ( $timestamp !== null && $sha1 !== null ) {
			$this->mFileSearchOptions[$name] = [ 'time' => $timestamp, 'sha1' => $sha1 ];
		}
	}

	/**
	 * Register a template dependency for this output
	 *
	 * @param LinkTarget $link (used to require Title until 1.38)
	 * @param int $page_id
	 * @param int $rev_id
	 */
	public function addTemplate( $link, $page_id, $rev_id ): void {
		$ns = $link->getNamespace();
		$dbk = $link->getDBkey();
		$this->mTemplates[$ns][$dbk] = $page_id;
		$this->mTemplateIds[$ns][$dbk] = $rev_id; // For versioning
	}

	/**
	 * @param LinkTarget $link LinkTarget object, must be an interwiki link
	 *       (used to require Title until 1.38).
	 *
	 * @throws MWException If given invalid input
	 */
	public function addInterwikiLink( $link ): void {
		if ( !$link->isExternal() ) {
			throw new MWException( 'Non-interwiki link passed, internal parser error.' );
		}
		$prefix = $link->getInterwiki();
		$this->mInterwikiLinks[$prefix][$link->getDBkey()] = 1;
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
		$this->mModules = array_merge( $this->mModules, $modules );
	}

	/**
	 * @see OutputPage::addModuleStyles
	 * @param string[] $modules
	 */
	public function addModuleStyles( array $modules ): void {
		$this->mModuleStyles = array_merge( $this->mModuleStyles, $modules );
	}

	/**
	 * Add one or more variables to be set in mw.config in JavaScript.
	 *
	 * @param string|array $keys Key or array of key/value pairs.
	 * @param mixed|null $value [optional] Value of the configuration variable.
	 * @since 1.23
	 * @deprecated since 1.38, use ::setJsConfigVar() or ::appendJsConfigVar()
	 *  which ensures compatibility with asynchronous parsing.
	 */
	public function addJsConfigVars( $keys, $value = null ): void {
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
	 * This gives the primary language of the output, including
	 * any variant used, as a MediaWiki-internal language code.  This can
	 * be converted to a standard IETF language tag complying with BCP 47
	 * by passing it to the LanguageCode::bcp47() method.
	 *
	 * Note that this may differ from the wiki's primary language (because
	 * page language can differ from primary language) and can differ from
	 * the page language as well (because the parser uses the user language
	 * when processing interface messages).  It will also differ from the
	 * Parser's target language when language conversion has been performed;
	 * in that case it will be equal to
	 * Parser::getTargetLanguageConverter()->getPreferredVariant(); use
	 * LanguageFactory::getParentLanguage() on that code if you need the
	 * base language.  (Note that ::getPreferredVariant() depends on the
	 * request URL and the User language as well.)
	 *
	 * Finally, note that the actual HTML included in this ParserOutput
	 * may actually represent content in multiple languages.  It is
	 * expected that the HTML will use internal `lang` attributes (with
	 * BCP-47 values) to switch from the primary language reported by
	 * this method.
	 *
	 * @return ?Bcp47Code The primary language for this output, as a
	 *   MediaWiki-internal language code, or `null` if a language
	 *   was not set.
	 * @since 1.40
	 */
	public function getLanguage(): ?Bcp47Code {
		// This information is temporarily stored in extension data (T303329)
		$code = $this->getExtensionData( 'core:target-lang-variant' );
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
	 * @internal
	 *
	 * Store a unique rendering id for this output. This is used by the REST API
	 * for stashing content to support editing use cases.
	 *
	 * @param ParsoidRenderID $parsoidRenderId
	 */
	public function setParsoidRenderId( ParsoidRenderID $parsoidRenderId ): void {
		$this->setExtensionData( self::PARSOID_RENDER_ID_KEY, $parsoidRenderId->getKey() );
	}

	/**
	 * @internal
	 *
	 * Return the Parsoid rendering id for this ParserOutput. This is only set
	 * where the ParserOutput has been generated by Parsoid.
	 *
	 * @return string|null
	 */
	public function getParsoidRenderId(): ?string {
		return $this->getExtensionData( self::PARSOID_RENDER_ID_KEY );
	}

	/**
	 * Attach a flag to the output so that it can be checked later to handle special cases
	 *
	 * @param string $flag
	 * @deprecated since 1.38; use ::setOutputFlag()
	 */
	public function setFlag( $flag ): void {
		wfDeprecated( __METHOD__, '1.38' );
		$this->mFlags[$flag] = true;
	}

	/**
	 * @param string $flag
	 * @return bool Whether the given flag was set to signify a special case
	 * @deprecated since 1.38; use ::getOutputFlag()
	 */
	public function getFlag( $flag ): bool {
		wfDeprecated( __METHOD__, '1.38' );
		return isset( $this->mFlags[$flag] );
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
	 * page_props is a key value store indexed by the page ID. This allows
	 * the parser to set a property on a page which can then be quickly
	 * retrieved given the page ID or via a DB join when given the page
	 * title.
	 *
	 * Since 1.23, page_props are also indexed by numeric value, to allow
	 * for efficient "top k" queries of pages wrt a given property.
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
	 * @note It is strongly recommended that only strings be used for $value.
	 *  Although any JSON-serializable value can be stored/fetched in
	 *  ParserOutput, when the values are stored to the database
	 *  (in deferred/LinksUpdate/PagePropsTable.php) they will be stringified:
	 *  booleans will be converted to '0' and '1', null will become '',
	 *  and everything else will be cast to string.  Page properties
	 *  obtained from the PageProps service will always be strings.
	 *
	 * @note Do not use setPageProperty() to set a property which is only used
	 * in a context where the ParserOutput object itself is already available,
	 * for example a normal page view. There is no need to save such a property
	 * in the database since the text is already parsed. You can just hook
	 * OutputPageParserOutput and get your data out of the ParserOutput object.
	 *
	 * If you are writing an extension where you want to set a property in the
	 * parser which is used by an OutputPageParserOutput hook, you have to
	 * associate the extension data directly with the ParserOutput object.
	 * Since MediaWiki 1.21, you should use setExtensionData() to do this:
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
	 * @param string $name
	 * @param int|float|string|bool|null $value
	 * @since 1.38
	 */
	public function setPageProperty( string $name, $value ): void {
		$this->mProperties[$name] = $value;
	}

	/**
	 * Look up a page property.
	 * @param string $name The page property name to look up.
	 * @return int|float|string|bool|null The value previously set using setPageProperty().
	 * Returns null if no value was set for the given property name.
	 *
	 * @note You would need to use ::getPageProperties() to test for an
	 *  explicitly-set null value; but see the note in ::setPageProperty()
	 *  about avoiding the use of non-string values.
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
	 * @return array<string,int|float|string|bool|null>
	 * @since 1.38
	 */
	public function getPageProperties(): array {
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
	 * @param string $name A flag name
	 * @param bool $val
	 * @since 1.38
	 */
	public function setOutputFlag( string $name, bool $val = true ): void {
		switch ( $name ) {
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
	 * @param string $name A flag name
	 * @return bool The flag value
	 * @since 1.38
	 */
	public function getOutputFlag( string $name ): bool {
		switch ( $name ) {
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
			return isset( $this->mFlags[$name] );

		}
	}

	/**
	 * Provides a uniform interface to various string sets stored
	 * in the ParserOutput.  String sets internal to MediaWiki core should
	 * have names which are constants in ParserOutputStringSets.  Extensions
	 * should use ::appendExtensionData() rather than creating new string sets
	 * with ::appendOutputStrings() in order to prevent namespace conflicts.
	 *
	 * @param string $name A string set name
	 * @param string[] $value
	 * @since 1.41
	 */
	public function appendOutputStrings( string $name, array $value ): void {
		switch ( $name ) {
			case ParserOutputStringSets::MODULE:
				$this->addModules( $value );
				break;
			case ParserOutputStringSets::MODULE_STYLE:
				$this->addModuleStyles( $value );
				break;
			case ParserOutputStringSets::EXTRA_CSP_DEFAULT_SRC:
				foreach ( $value as $v ) {
					$this->addExtraCSPDefaultSrc( $v );
				}
				break;
			case ParserOutputStringSets::EXTRA_CSP_SCRIPT_SRC:
				foreach ( $value as $v ) {
					$this->addExtraCSPScriptSrc( $v );
				}
				break;
			case ParserOutputStringSets::EXTRA_CSP_STYLE_SRC:
				foreach ( $value as $v ) {
					$this->addExtraCSPStyleSrc( $v );
				}
				break;
			default:
				throw new UnexpectedValueException( "Unknown output string set name $name" );
		}
	}

	/**
	 * Provides a uniform interface to various boolean string sets stored
	 * in the ParserOutput.  String sets internal to MediaWiki core should
	 * have names which are constants in ParserOutputStringSets.  Extensions
	 * should only use ::getOutputStrings() to query string sets defined in
	 * ParserOutputStringSets in core; they should use ::appendExtensionData()
	 * to define their own string sets.
	 *
	 * @param string $name A string set name
	 * @return string[] The string set value
	 * @since 1.41
	 */
	public function getOutputStrings( string $name ): array {
		switch ( $name ) {
			case ParserOutputStringSets::MODULE:
				return $this->getModules();
			case ParserOutputStringSets::MODULE_STYLE:
				return $this->getModuleStyles();
			case ParserOutputStringSets::EXTRA_CSP_DEFAULT_SRC:
				return $this->getExtraCSPDefaultSrcs();
			case ParserOutputStringSets::EXTRA_CSP_SCRIPT_SRC:
				return $this->getExtraCSPScriptSrcs();
			case ParserOutputStringSets::EXTRA_CSP_STYLE_SRC:
				return $this->getExtraCSPStyleSrcs();
			default:
				throw new UnexpectedValueException( "Unknown output string set name $name" );
		}
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
	 * @note Only scalar values, e.g. numbers, strings, arrays or MediaWiki\Json\JsonUnserializable
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
	 * @param mixed|JsonUnserializable $value The value to set.
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

	private static function getTimes( $clock = null ): array {
		$ret = [];
		if ( !$clock || $clock === 'wall' ) {
			$ret['wall'] = microtime( true );
		}
		if ( !$clock || $clock === 'cpu' ) {
			$ru = getrusage( 0 /* RUSAGE_SELF */ );
			$ret['cpu'] = $ru['ru_utime.tv_sec'] + $ru['ru_utime.tv_usec'] / 1e6;
			$ret['cpu'] += $ru['ru_stime.tv_sec'] + $ru['ru_stime.tv_usec'] / 1e6;
		}
		return $ret;
	}

	/**
	 * Resets the parse start timestamps for future calls to getTimeSinceStart()
	 * @since 1.22
	 */
	public function resetParseStartTime(): void {
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

	private function renderDebugInfo(): string {
		$text = '';

		$limitReportData = $this->getLimitReportData();
		// If nothing set it, we can't get it.
		if ( $limitReportData ) {
			$limitReport = "NewPP limit report\n";

			if ( array_key_exists( 'cachereport-origin', $limitReportData ) ) {
				$limitReport .= "Parsed by {$limitReportData['cachereport-origin']}\n";
			}

			if ( array_key_exists( 'cachereport-timestamp', $limitReportData ) ) {
				$limitReport .= "Cached time: {$limitReportData['cachereport-timestamp']}\n";
			}

			if ( array_key_exists( 'cachereport-ttl', $limitReportData ) ) {
				$limitReport .= "Cache expiry: {$limitReportData['cachereport-ttl']}\n";
			}

			if ( array_key_exists( 'cachereport-transientcontent', $limitReportData ) ) {
				$transient = $limitReportData['cachereport-transientcontent'] ? 'true' : 'false';
				$limitReport .= "Reduced expiry: $transient\n";
			}

			// TODO: flags should go into limit report too.
			$limitReport .= 'Complications: [' . implode( ', ', $this->getAllFlags() ) . "]\n";

			$hookRunner = new HookRunner( MediaWikiServices::getInstance()->getHookContainer() );
			foreach ( $limitReportData as $key => $value ) {
				if ( in_array( $key, [
					'cachereport-origin',
					'cachereport-timestamp',
					'cachereport-ttl',
					'cachereport-transientcontent',
					'limitreport-timingprofile',
				] ) ) {
					// These keys are processed separately.
					continue;
				}
				if ( $hookRunner->onParserLimitReportFormat(
					$key, $value, $limitReport, false, false )
				) {
					$keyMsg = wfMessage( $key )->inLanguage( 'en' )->useDatabase( false );
					$valueMsg = wfMessage( [ "$key-value-text", "$key-value" ] )
						->inLanguage( 'en' )->useDatabase( false );
					if ( !$valueMsg->exists() ) {
						$valueMsg = new RawMessage( '$1' );
					}
					if ( !$keyMsg->isDisabled() && !$valueMsg->isDisabled() ) {
						$valueMsg->params( $value );
						$limitReport .= "{$keyMsg->text()}: {$valueMsg->text()}\n";
					}
				}
			}
			// Since we're not really outputting HTML, decode the entities and
			// then re-encode the things that need hiding inside HTML comments.
			$limitReport = htmlspecialchars_decode( $limitReport );

			// Sanitize for comment. Note '‐' in the replacement is U+2010,
			// which looks much like the problematic '-'.
			$limitReport = str_replace( [ '-', '&' ], [ '‐', '&amp;' ], $limitReport );
			$text = "\n<!-- \n$limitReport-->\n";

			$profileReport = $limitReportData['limitreport-timingprofile'] ?? null;
			if ( $profileReport ) {
				$text .= "<!--\nTransclusion expansion time report (%,ms,calls,template)\n";
				$text .= implode( "\n", $profileReport ) . "\n-->\n";
			}
		}

		if ( $this->mCacheMessage ) {
			$text .= "\n<!-- $this->mCacheMessage\n -->\n";
		}

		$parsoidVersion = $this->getExtensionData( 'core:parsoid-version' );
		if ( $parsoidVersion ) {
			$text .= "\n<!--Parsoid $parsoidVersion-->\n";
		}

		return $text;
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
		$parserCacheExpireTime = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::ParserCacheExpireTime );

		return $this->getCacheExpiry() < $parserCacheExpireTime;
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
	public function setPreventClickjacking( bool $flag ) {
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
		return array_filter( array_keys( get_object_vars( $this ) ),
			static function ( $field ) {
				if ( $field === 'mParseStartTime' || $field === 'mWarningMsgs' ) {
					return false;
				}
				// Unserializing unknown private fields in HHVM causes
				// member variables with nulls in their names (T229366)
				return strpos( $field, "\0" ) === false;
			}
		);
	}

	/**
	 * Merges internal metadata such as flags, accessed options, and profiling info
	 * from $source into this ParserOutput. This should be used whenever the state of $source
	 * has any impact on the state of this ParserOutput.
	 *
	 * @param ParserOutput $source
	 */
	public function mergeInternalMetaDataFrom( ParserOutput $source ): void {
		$this->mWarnings = self::mergeMap( $this->mWarnings, $source->mWarnings ); // don't use getter
		$this->mTimestamp = $this->useMaxValue( $this->mTimestamp, $source->getTimestamp() );

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
	 *
	 * @param ParserOutput $source
	 */
	public function mergeHtmlMetaDataFrom( ParserOutput $source ): void {
		// HTML and HTTP
		$this->mHeadItems = self::mergeMixedList( $this->mHeadItems, $source->getHeadItems() );
		$this->mModules = self::mergeList( $this->mModules, $source->getModules() );
		$this->mModuleStyles = self::mergeList( $this->mModuleStyles, $source->getModuleStyles() );
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
				// metadata to be concatendated.
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
	 *
	 * @param ParserOutput $source
	 */
	public function mergeTrackingMetaDataFrom( ParserOutput $source ): void {
		$this->mLanguageLinks = self::mergeList( $this->mLanguageLinks, $source->getLanguageLinks() );
		$this->mCategories = self::mergeMap( $this->mCategories, $source->getCategoryMap() );
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
			$this->mFlags + array_flip( ParserOutputFlags::cases() )
		);
		foreach ( $flags as $name ) {
			if ( $this->getOutputFlag( $name ) ) {
				$metadata->setOutputFlag( $name );
			}
		}

		// Uniform handling of string sets: they are unioned.
		// (This includes modules, style modes, and CSP src.)
		foreach ( ParserOutputStringSets::cases() as $name ) {
			$metadata->appendOutputStrings(
				$name, $this->getOutputStrings( $name )
			);
		}

		foreach ( $this->mCategories as $cat => $key ) {
			// Numeric category strings are going to come out of the
			// `mCategories` array as ints; cast back to string.
			$metadata->addCategory( (string)$cat, $key );
		}

		foreach ( $this->mJsConfigVars as $key => $value ) {
			if ( is_array( $value ) && isset( $value[self::MW_MERGE_STRATEGY_KEY] ) ) {
				$strategy = $value[self::MW_MERGE_STRATEGY_KEY];
				foreach ( $value as $item => $ignore ) {
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
			if ( is_array( $value ) && isset( $value[self::MW_MERGE_STRATEGY_KEY] ) ) {
				$strategy = $value[self::MW_MERGE_STRATEGY_KEY];
				foreach ( $value as $item => $ignore ) {
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
			$metadata->addExternalLink( $url );
		}
		foreach ( $this->mProperties as $prop => $value ) {
			$metadata->setPageProperty( $prop, $value );
		}
		foreach ( $this->mWarningMsgs as $msg => $args ) {
			$metadata->addWarningMsg( $msg, ...$args );
		}
		foreach ( $this->mLimitReportData as $key => $value ) {
			$metadata->setLimitReportData( $key, $value );
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

	private static function merge2D( array $a, array $b ): array {
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

	private static function useEachMinValue( array $a, array $b ): array {
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
			'Text' => $this->mText,
			'LanguageLinks' => $this->mLanguageLinks,
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
			'NewSection' => $this->mNewSection,
			'HideNewSection' => $this->mHideNewSection,
			'NoGallery' => $this->mNoGallery,
			'HeadItems' => $this->mHeadItems,
			'Modules' => $this->mModules,
			'ModuleStyles' => $this->mModuleStyles,
			'JsConfigVars' => $this->mJsConfigVars,
			'Warnings' => $this->mWarnings,
			'Sections' => $this->getSections(),
			'Properties' => self::detectAndEncodeBinary( $this->mProperties ),
			'TOCHTML' => $this->mTOCHTML,
			'Timestamp' => $this->mTimestamp,
			'EnableOOUI' => $this->mEnableOOUI,
			'IndexPolicy' => $this->getIndexPolicy(),
			// may contain arbitrary structures!
			'ExtensionData' => $this->mExtensionData,
			'LimitReportData' => $this->mLimitReportData,
			'LimitReportJSData' => $this->mLimitReportJSData,
			'CacheMessage' => $this->mCacheMessage,
			'ParseStartTime' => $this->mParseStartTime,
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

	public static function newFromJsonArray( JsonUnserializer $unserializer, array $json ): ParserOutput {
		$parserOutput = new ParserOutput();
		$parserOutput->initFromJson( $unserializer, $json );
		return $parserOutput;
	}

	/**
	 * Initialize member fields from an array returned by jsonSerialize().
	 * @param JsonUnserializer $unserializer
	 * @param array $jsonData
	 */
	protected function initFromJson( JsonUnserializer $unserializer, array $jsonData ): void {
		parent::initFromJson( $unserializer, $jsonData );

		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!

		$this->mText = $jsonData['Text'];
		$this->mLanguageLinks = $jsonData['LanguageLinks'];
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
		$this->mNewSection = $jsonData['NewSection'];
		$this->mHideNewSection = $jsonData['HideNewSection'];
		$this->mNoGallery = $jsonData['NoGallery'];
		$this->mHeadItems = $jsonData['HeadItems'];
		$this->mModules = $jsonData['Modules'];
		$this->mModuleStyles = $jsonData['ModuleStyles'];
		$this->mJsConfigVars = $jsonData['JsConfigVars'];
		$this->mWarnings = $jsonData['Warnings'];
		$this->mFlags = $jsonData['Flags'];
		if (
			$jsonData['Sections'] !== [] ||
			// backward-compatibility: distinguish "no sections" from
			// "sections not set" (Will be unnecessary after T327439.)
			$this->getOutputFlag( 'mw:toc-set' )
		) {
			$this->setSections( $jsonData['Sections'] );
			unset( $this->mFlags['mw:toc-set'] );
			if ( isset( $jsonData['TOCExtensionData'] ) ) {
				$tocData = $this->getTOCData(); // created by setSections() above
				foreach ( $jsonData['TOCExtensionData'] as $key => $value ) {
					$tocData->setExtensionData( $key, $value );
				}
			}
		}
		$this->mProperties = self::detectAndDecodeBinary( $jsonData['Properties'] );
		$this->mTOCHTML = $jsonData['TOCHTML'];
		$this->mTimestamp = $jsonData['Timestamp'];
		$this->mEnableOOUI = $jsonData['EnableOOUI'];
		$this->setIndexPolicy( $jsonData['IndexPolicy'] );
		$this->mExtensionData = $jsonData['ExtensionData'] ?? [];
		$this->mLimitReportData = $jsonData['LimitReportData'];
		$this->mLimitReportJSData = $jsonData['LimitReportJSData'];
		$this->mCacheMessage = $jsonData['CacheMessage'] ?? '';
		$this->mParseStartTime = $jsonData['ParseStartTime'];
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

	public function __wakeup() {
		// Backwards compatibility, pre 1.36
		$priorAccessedOptions = $this->getGhostFieldValue( 'mAccessedOptions' );
		if ( $priorAccessedOptions ) {
			$this->mParseUsedOptions = $priorAccessedOptions;
		}
		// Backwards compatibility, pre 1.39
		$priorIndexPolicy = $this->getGhostFieldValue( 'mIndexPolicy' );
		if ( $priorIndexPolicy ) {
			$this->setIndexPolicy( $priorIndexPolicy );
		}
		// Backwards compatibility, pre 1.40
		$mSections = $this->getGhostFieldValue( 'mSections' );
		if ( $mSections !== null && $mSections !== [] ) {
			$this->setSections( $mSections );
		}
	}

	public function __get( $name ) {
		if ( property_exists( get_called_class(), $name ) ) {
			// Direct access to a public property, deprecated.
			wfDeprecatedMsg( "ParserOutput::{$name} public read access deprecated", '1.38' );
			return $this->$name;
		} elseif ( property_exists( $this, $name ) ) {
			// Dynamic property access, deprecated.
			wfDeprecatedMsg( "ParserOutput::{$name} dynamic property read access deprecated", '1.38' );
			return $this->$name;
		} else {
			trigger_error( "Inaccessible property via __get(): $name" );
			return null;
		}
	}

	public function __set( $name, $value ) {
		if ( property_exists( get_called_class(), $name ) ) {
			// Direct access to a public property, deprecated.
			wfDeprecatedMsg( "ParserOutput::$name public write access deprecated", '1.38' );
			$this->$name = $value;
		} else {
			// Dynamic property access, deprecated.
			wfDeprecatedMsg( "ParserOutput::$name dynamic property write access deprecated", '1.38' );
			$this->$name = $value;
		}
	}
}
