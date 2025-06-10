<?php
/**
 * Copyright (C) 2011-2022 Wikimedia Foundation and others.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

// NO_PRELOAD -- anonymous class in parent

namespace MediaWiki\Parser\Parsoid\Config;

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Config\Config;
use MediaWiki\Config\MutableConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Exception\MWUnknownContentModelException;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\LanguageConverter;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\MagicWordArray;
use MediaWiki\Parser\MagicWordFactory;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\Utils\UrlUtils;
use MediaWiki\WikiMap\WikiMap;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Parsoid\Config\SiteConfig as ISiteConfig;
use Wikimedia\Parsoid\Core\ContentMetadataCollector;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\Utils\Utils;
use Wikimedia\Stats\PrefixingStatsdDataFactoryProxy;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Stats\StatsUtils;

/**
 * Site-level configuration for Parsoid
 *
 * This includes both global configuration and wiki-level configuration.
 *
 * @since 1.39
 * @internal
 */
class SiteConfig extends ISiteConfig {

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::GalleryOptions,
		MainConfigNames::AllowExternalImages,
		MainConfigNames::AllowExternalImagesFrom,
		MainConfigNames::Server,
		MainConfigNames::ArticlePath,
		MainConfigNames::InterwikiMagic,
		MainConfigNames::ExtraInterlanguageLinkPrefixes,
		MainConfigNames::InterlanguageLinkCodeMap,
		MainConfigNames::LocalInterwikis,
		MainConfigNames::LanguageCode,
		MainConfigNames::NamespaceAliases,
		MainConfigNames::UrlProtocols,
		MainConfigNames::Script,
		MainConfigNames::ScriptPath,
		MainConfigNames::LoadScript,
		MainConfigNames::LocalTZoffset,
		MainConfigNames::ThumbLimits,
		MainConfigNames::MaxTemplateDepth,
		MainConfigNames::NoFollowLinks,
		MainConfigNames::NoFollowNsExceptions,
		MainConfigNames::NoFollowDomainExceptions,
		MainConfigNames::ExternalLinkTarget,
		MainConfigNames::EnableMagicLinks,
		MainConfigNames::ParsoidExperimentalParserFunctionOutput,
	];

	private ServiceOptions $config;
	private Config $mwConfig;
	/** Parsoid-specific options array from $config */
	private array $parsoidSettings;
	private Language $contLang;
	private StatsdDataFactoryInterface $stats;
	private StatsFactory $statsFactory;
	private MagicWordFactory $magicWordFactory;
	private NamespaceInfo $namespaceInfo;
	private SpecialPageFactory $specialPageFactory;
	private InterwikiLookup $interwikiLookup;
	private ParserFactory $parserFactory;
	private UserOptionsLookup $userOptionsLookup;
	private ObjectFactory $objectFactory;
	private LanguageFactory $languageFactory;
	private LanguageConverterFactory $languageConverterFactory;
	private LanguageNameUtils $languageNameUtils;
	private UrlUtils $urlUtils;
	private IContentHandlerFactory $contentHandlerFactory;
	private ?string $baseUri = null;
	private ?string $relativeLinkPrefix = null;
	private ?array $interwikiMap = null;
	private ?array $variants = null;
	private ?array $extensionTags = null;
	private bool $isTimedMediaHandlerLoaded;

	/**
	 * @param ServiceOptions $config MediaWiki main configuration object
	 * @param array $parsoidSettings Parsoid-specific options array from main configuration.
	 * @param ObjectFactory $objectFactory
	 * @param Language $contentLanguage Content language.
	 * @param StatsdDataFactoryInterface $stats
	 * @param StatsFactory $statsFactory
	 * @param MagicWordFactory $magicWordFactory
	 * @param NamespaceInfo $namespaceInfo
	 * @param SpecialPageFactory $specialPageFactory
	 * @param InterwikiLookup $interwikiLookup
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param LanguageFactory $languageFactory
	 * @param LanguageConverterFactory $languageConverterFactory
	 * @param LanguageNameUtils $languageNameUtils
	 * @param UrlUtils $urlUtils
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param array $extensionParsoidModules
	 * @param ParserFactory $parserFactory
	 * @param Config $mwConfig
	 * @param bool $isTimedMediaHandlerLoaded
	 */
	public function __construct(
		ServiceOptions $config,
		array $parsoidSettings,
		ObjectFactory $objectFactory,
		Language $contentLanguage,
		StatsdDataFactoryInterface $stats,
		StatsFactory $statsFactory,
		MagicWordFactory $magicWordFactory,
		NamespaceInfo $namespaceInfo,
		SpecialPageFactory $specialPageFactory,
		InterwikiLookup $interwikiLookup,
		UserOptionsLookup $userOptionsLookup,
		LanguageFactory $languageFactory,
		LanguageConverterFactory $languageConverterFactory,
		LanguageNameUtils $languageNameUtils,
		UrlUtils $urlUtils,
		IContentHandlerFactory $contentHandlerFactory,
		array $extensionParsoidModules,
		// $parserFactory is temporary and may be removed once a better solution is found.
		ParserFactory $parserFactory, // T268776
		Config $mwConfig,
		bool $isTimedMediaHandlerLoaded
	) {
		parent::__construct();

		$config->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->config = $config;
		$this->mwConfig = $mwConfig;
		$this->parsoidSettings = $parsoidSettings;

		$this->objectFactory = $objectFactory;
		$this->contLang = $contentLanguage;
		$this->stats = $stats;
		$this->statsFactory = $statsFactory;
		$this->magicWordFactory = $magicWordFactory;
		$this->namespaceInfo = $namespaceInfo;
		$this->specialPageFactory = $specialPageFactory;
		$this->interwikiLookup = $interwikiLookup;
		$this->parserFactory = $parserFactory;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->languageFactory = $languageFactory;
		$this->languageConverterFactory = $languageConverterFactory;
		$this->languageNameUtils = $languageNameUtils;
		$this->urlUtils = $urlUtils;
		$this->contentHandlerFactory = $contentHandlerFactory;

		// Override parent default
		if ( isset( $this->parsoidSettings['linting'] ) ) {
			// @todo: Add this setting to MW's MainConfigSchema
			$this->linterEnabled = $this->parsoidSettings['linting'];
		}

		if ( isset( $this->parsoidSettings['wt2htmlLimits'] ) ) {
			$this->wt2htmlLimits = $this->parsoidSettings['wt2htmlLimits'] + $this->wt2htmlLimits;
		}
		if ( isset( $this->parsoidSettings['html2wtLimits'] ) ) {
			$this->html2wtLimits = $this->parsoidSettings['html2wtLimits'] + $this->html2wtLimits;
		}

		// Register extension modules
		foreach ( $extensionParsoidModules as $configOrSpec ) {
			$this->registerExtensionModule( $configOrSpec );
		}

		$this->isTimedMediaHandlerLoaded = $isTimedMediaHandlerLoaded;
	}

	/** @inheritDoc */
	public function getObjectFactory(): ObjectFactory {
		return $this->objectFactory;
	}

	/** @inheritDoc */
	public function getLogger(): LoggerInterface {
		// TODO: inject
		if ( $this->logger === null ) {
			$this->logger = LoggerFactory::getInstance( 'Parsoid' );
		}
		return $this->logger;
	}

	/**
	 * Get stats prefix
	 * @param bool $trimmed Trim trailing dot on prefix name
	 * @return string
	 */
	private function getStatsPrefix( bool $trimmed = false ): string {
		$component = $this->parsoidSettings['metricsPrefix'] ?? 'Parsoid.';
		if ( $trimmed ) {
			$component = rtrim( $component, '.' );
		}
		return $component;
	}

	public function metrics(): ?StatsdDataFactoryInterface {
		// TODO: inject
		static $prefixedMetrics = null;
		$prefixedMetrics ??= new PrefixingStatsdDataFactoryProxy(
			// Our stats will also get prefixed with 'MediaWiki.'
			$this->stats,
			$this->getStatsPrefix()
		);
		return $prefixedMetrics;
	}

	/**
	 * Create a prefixed StatsFactory for parsoid stats
	 */
	public function prefixedStatsFactory(): StatsFactory {
		$component = $this->getStatsPrefix( true );
		return $this->statsFactory->withComponent( $component );
	}

	/**
	 * Record a timing metric
	 * @param string $name
	 * @param float $value A time value in milliseconds
	 * @param array $labels
	 * @return void
	 */
	public function observeTiming( string $name, float $value, array $labels ) {
		$this->prefixedStatsFactory()->getTiming( $name )
			->setLabels( $labels )
			->observe( $value );
	}

	/**
	 * Record a histogram metric
	 * @param string $name
	 * @param float $value A time value in milliseconds
	 * @param array $buckets The buckets used in this histogram
	 * @param array $labels The metric labels
	 * @return void
	 */
	public function observeHistogram( string $name, float $value, array $buckets, array $labels ) {
		$metric = $this->prefixedStatsFactory()->getHistogram( $name, $buckets );
		foreach ( $labels as $labelKey => $labelValue ) {
			$metric->setLabel( $labelKey, $labelValue );
		}
		$metric->observe( $value );
	}

	/**
	 * Generate buckets based on skip and mean
	 * @param float $mean
	 * @param int $skip
	 * @return float[]
	 */
	public function getHistogramBuckets( float $mean, int $skip ) {
		return StatsUtils::makeBucketsFromMean( $mean, $skip );
	}

	/**
	 * Increment a counter metric
	 * @param string $name
	 * @param array $labels
	 * @param float $amount
	 * @return void
	 */
	public function incrementCounter( string $name, array $labels, float $amount = 1 ) {
		$this->prefixedStatsFactory()->getCounter( $name )
			->setLabels( $labels )
			->incrementBy( $amount );
	}

	public function galleryOptions(): array {
		return $this->config->get( MainConfigNames::GalleryOptions );
	}

	public function allowedExternalImagePrefixes(): array {
		if ( $this->config->get( MainConfigNames::AllowExternalImages ) ) {
			return [ '' ];
		} else {
			$allowFrom = $this->config->get( MainConfigNames::AllowExternalImagesFrom );
			return $allowFrom ? (array)$allowFrom : [];
		}
	}

	/**
	 * Determine the article base URI and relative prefix
	 *
	 * Populates `$this->baseUri` and `$this->relativeLinkPrefix` based on
	 * `$wgServer` and `$wgArticlePath`, by splitting it at the last '/' in the
	 * path portion.
	 */
	private function determineArticlePath(): void {
		$url = $this->config->get( MainConfigNames::Server ) .
			$this->config->get( MainConfigNames::ArticlePath );

		if ( substr( $url, -2 ) !== '$1' ) {
			throw new UnexpectedValueException( "Article path '$url' does not have '$1' at the end" );
		}
		$url = substr( $url, 0, -2 );

		$bits = $this->urlUtils->parse( $url );
		if ( !$bits ) {
			throw new UnexpectedValueException( "Failed to parse article path '$url'" );
		}

		if ( empty( $bits['path'] ) ) {
			$path = '/';
		} else {
			$path = UrlUtils::removeDotSegments( $bits['path'] );
		}

		$relParts = [ 'query' => true, 'fragment' => true ];
		$base = array_diff_key( $bits, $relParts );
		$rel = array_intersect_key( $bits, $relParts );

		$i = strrpos( $path, '/' );
		$base['path'] = substr( $path, 0, $i + 1 );
		$rel['path'] = '.' . substr( $path, $i );

		$this->baseUri = UrlUtils::assemble( $base );
		$this->relativeLinkPrefix = UrlUtils::assemble( $rel );
	}

	public function baseURI(): string {
		if ( $this->baseUri === null ) {
			$this->determineArticlePath();
		}
		return $this->baseUri;
	}

	public function relativeLinkPrefix(): string {
		if ( $this->relativeLinkPrefix === null ) {
			$this->determineArticlePath();
		}
		return $this->relativeLinkPrefix;
	}

	/**
	 * This is very similar to MagicWordArray::getBaseRegex() except we
	 * don't emit the named grouping constructs, which can cause havoc
	 * when embedded in other regexps with grouping constructs.
	 *
	 * @param MagicWordArray $magicWordArray
	 * @param string $delimiter
	 * @return string
	 */
	private static function mwaToRegex(
		MagicWordArray $magicWordArray,
		string $delimiter = '/'
	): string {
		return implode( '|', $magicWordArray->getBaseRegex( false, $delimiter ) );
	}

	public function redirectRegexp(): string {
		$redirect = self::mwaToRegex( $this->magicWordFactory->newArray( [ 'redirect' ] ), '@' );
		return "@$redirect@Su";
	}

	public function categoryRegexp(): string {
		$canon = $this->namespaceInfo->getCanonicalName( NS_CATEGORY );
		$result = [ $canon ];
		foreach ( $this->contLang->getNamespaceAliases() as $alias => $ns ) {
			if ( $ns === NS_CATEGORY && $alias !== $canon ) {
				$result[] = $alias;
			}
		}
		$category = implode( '|', array_map( function ( $v ) {
			return $this->quoteTitleRe( $v, '@' );
		}, $result ) );
		return "@(?i:$category)@";
	}

	public function bswRegexp(): string {
		$bsw = self::mwaToRegex( $this->magicWordFactory->getDoubleUnderscoreArray(), '@' );
		// Aliases for double underscore mws include the underscores
		// So, strip them since the base regexp will have included them
		// and they aren't expected at the use sites of bswRegexp
		$bsw = str_replace( '__', '', $bsw );
		return "@$bsw@Su";
	}

	/** @inheritDoc */
	public function canonicalNamespaceId( string $name ): ?int {
		$ret = $this->namespaceInfo->getCanonicalIndex( $name );
		return $ret === false ? null : $ret;
	}

	/** @inheritDoc */
	public function namespaceId( string $name ): ?int {
		$ret = $this->contLang->getNsIndex( $name );
		return $ret === false ? null : $ret;
	}

	/** @inheritDoc */
	public function namespaceName( int $ns ): ?string {
		$ret = $this->contLang->getFormattedNsText( $ns );
		return $ret === '' && $ns !== NS_MAIN ? null : $ret;
	}

	/** @inheritDoc */
	public function namespaceHasSubpages( int $ns ): bool {
		return $this->namespaceInfo->hasSubpages( $ns );
	}

	/** @inheritDoc */
	public function namespaceCase( int $ns ): string {
		return $this->namespaceInfo->isCapitalized( $ns ) ? 'first-letter' : 'case-sensitive';
	}

	/** @inheritDoc */
	public function namespaceIsTalk( int $ns ): bool {
		return $this->namespaceInfo->isTalk( $ns );
	}

	/** @inheritDoc */
	public function ucfirst( string $str ): string {
		return $this->contLang->ucfirst( $str );
	}

	/** @inheritDoc */
	public function specialPageLocalName( string $alias ): ?string {
		$aliases = $this->specialPageFactory->resolveAlias( $alias );
		return $aliases[0] !== null ? $this->specialPageFactory->getLocalNameFor( ...$aliases ) : $alias;
	}

	public function interwikiMagic(): bool {
		return $this->config->get( MainConfigNames::InterwikiMagic );
	}

	/** @inheritDoc */
	public function magicLinkEnabled( string $which ): bool {
		$m = $this->config->get( MainConfigNames::EnableMagicLinks );
		return $m[$which] ?? true;
	}

	public function interwikiMap(): array {
		// Unfortunate that this mostly duplicates \ApiQuerySiteinfo::appendInterwikiMap()
		if ( $this->interwikiMap !== null ) {
			return $this->interwikiMap;
		}
		$this->interwikiMap = [];

		$getPrefixes = $this->interwikiLookup->getAllPrefixes();
		$langNames = $this->languageNameUtils->getLanguageNames();
		$extraLangPrefixes = $this->config->get( MainConfigNames::ExtraInterlanguageLinkPrefixes );
		$extraLangCodeMap = $this->config->get( MainConfigNames::InterlanguageLinkCodeMap );
		$localInterwikis = $this->config->get( MainConfigNames::LocalInterwikis );

		foreach ( $getPrefixes as $row ) {
			$prefix = $row['iw_prefix'];
			$val = [];
			$val['prefix'] = $prefix;
			// ApiQuerySiteInfo::appendInterwikiMap uses PROTO_CURRENT here,
			// but that's the 'current' protocol *of the API request*; use
			// PROTO_CANONICAL instead.
			$val['url'] = $this->urlUtils->expand( $row['iw_url'], PROTO_CANONICAL ) ?? false;

			// Fix up broken interwiki hrefs that are missing a $1 placeholder
			// Just append the placeholder at the end.
			// This makes sure that the interwikiMatcher adds one match
			// group per URI, and that interwiki links work as expected.
			if ( !str_contains( $val['url'], '$1' ) ) {
				$val['url'] .= '$1';
			}

			if ( str_starts_with( $row['iw_url'], '//' ) ) {
				$val['protorel'] = true;
			}
			if ( isset( $row['iw_local'] ) && $row['iw_local'] == '1' ) {
				$val['local'] = true;
			}
			if ( isset( $langNames[$prefix] ) ) {
				$val['language'] = true;
				$standard = LanguageCode::replaceDeprecatedCodes( $prefix );
				if ( $standard !== $prefix ) {
					# Note that even if this code is deprecated, it should
					# only be remapped if extralanglink (set below) is false.
					$val['deprecated'] = $standard;
				}
				$val['bcp47'] = LanguageCode::bcp47( $standard );
			}
			if ( in_array( $prefix, $localInterwikis, true ) ) {
				$val['localinterwiki'] = true;
			}
			if ( in_array( $prefix, $extraLangPrefixes, true ) ) {
				$val['extralanglink'] = true;
				$val['code'] = $extraLangCodeMap[$prefix] ?? $prefix;
				$val['bcp47'] = LanguageCode::bcp47( $val['code'] );
			}

			$this->interwikiMap[$prefix] = $val;
		}
		return $this->interwikiMap;
	}

	public function iwp(): string {
		return WikiMap::getCurrentWikiId();
	}

	public function legalTitleChars(): string {
		return Title::legalChars();
	}

	public function linkPrefixRegex(): ?string {
		if ( !$this->contLang->linkPrefixExtension() ) {
			return null;
		}
		return '/[' . $this->contLang->linkPrefixCharset() . ']+$/Du';
	}

	/** @inheritDoc */
	protected function linkTrail(): string {
		return $this->contLang->linkTrail();
	}

	public function langBcp47(): Bcp47Code {
		return $this->contLang;
	}

	public function mainpage(): string {
		// @todo Perhaps should inject TitleFactory here?
		return Title::newMainPage()->getPrefixedText();
	}

	public function mainPageLinkTarget(): Title {
		// @todo Perhaps should inject TitleFactory here?
		return Title::newMainPage();
	}

	/**
	 * Lookup config
	 * @param string $key
	 * @return mixed config value for $key, if present or null, if not.
	 */
	public function getMWConfigValue( string $key ) {
		return $this->mwConfig->has( $key ) ? $this->mwConfig->get( $key ) : null;
	}

	public function rtl(): bool {
		return $this->contLang->isRTL();
	}

	public function langConverterEnabledBcp47( Bcp47Code $lang ): bool {
		if ( $this->languageConverterFactory->isConversionDisabled() ) {
			return false;
		}

		$langObject = $this->languageFactory->getLanguage( $lang );
		if ( !in_array( $langObject->getCode(), LanguageConverter::$languagesWithVariants, true ) ) {
			return false;
		}
		$converter = $this->languageConverterFactory->getLanguageConverter( $langObject );
		return $converter->hasVariants();
	}

	public function script(): string {
		return $this->config->get( MainConfigNames::Script );
	}

	public function scriptpath(): string {
		return $this->config->get( MainConfigNames::ScriptPath );
	}

	public function server(): string {
		return $this->config->get( MainConfigNames::Server );
	}

	/**
	 * @inheritDoc
	 * @param Document $document
	 * @param ContentMetadataCollector $metadata
	 * @param string $defaultTitle
	 * @param Bcp47Code $lang
	 */
	public function exportMetadataToHeadBcp47(
		Document $document,
		ContentMetadataCollector $metadata,
		string $defaultTitle,
		Bcp47Code $lang
	): void {
		'@phan-var ParserOutput $metadata'; // @var ParserOutput $metadata
		// Look for a displaytitle.
		$displayTitle = $metadata->getPageProperty( 'displaytitle' ) ?:
			// Use the default title, properly escaped
			Utils::escapeHtml( $defaultTitle );
		$this->exportMetadataHelper(
			$document,
			$this->config->get( MainConfigNames::LoadScript ),
			$metadata->getModules(),
			$metadata->getModuleStyles(),
			$metadata->getJsConfigVars(),
			$displayTitle,
			$lang
		);
	}

	public function timezoneOffset(): int {
		return $this->config->get( MainConfigNames::LocalTZoffset );
	}

	/**
	 * Language variant information
	 * @return array<string,array> Keys are MediaWiki-internal variant codes (e.g. "zh-cn"),
	 * values are arrays with two fields:
	 *   - base: (string) Base language code (e.g. "zh") (MediaWiki-internal)
	 *   - fallbacks: (string[]) Fallback variants (MediaWiki-internal codes)
	 * @deprecated since 1.43; use ::variantsFor() (T320662)
	 */
	public function variants(): array {
		// Deprecated for all external callers; to make private and remove this warning.
		if ( wfGetCaller() !== __CLASS__ . '->variantsFor' ) {
			wfDeprecated( __METHOD__, '1.43' );
		}

		if ( $this->variants !== null ) {
			return $this->variants;
		}
		$this->variants = [];

		$langNames = LanguageConverter::$languagesWithVariants;
		if ( $this->languageConverterFactory->isConversionDisabled() ) {
			// Ensure result is empty if language conversion is disabled.
			$langNames = [];
		}

		foreach ( $langNames as $langCode ) {
			$lang = $this->languageFactory->getLanguage( $langCode );
			$converter = $this->languageConverterFactory->getLanguageConverter( $lang );
			if ( !$converter->hasVariants() ) {
				continue;
			}

			$variants = $converter->getVariants();
			foreach ( $variants as $v ) {
				$fallbacks = $converter->getVariantFallbacks( $v );
				if ( !is_array( $fallbacks ) ) {
					$fallbacks = [ $fallbacks ];
				}
				$this->variants[$v] = [
					'base' => $langCode,
					'fallbacks' => $fallbacks,
				];
			}
		}
		return $this->variants;
	}

	/**
	 * Language variant information for the given language (or null if
	 * unknown).
	 * @param Bcp47Code $code The language for which you want variant information
	 * @return ?array{base:Bcp47Code,fallbacks:Bcp47Code[]} an array with
	 * two fields:
	 *   - base: (Bcp47Code) Base BCP-47 language code (e.g. "zh")
	 *   - fallbacks: (Bcp47Code[]) Fallback variants, as BCP-47 codes
	 */
	public function variantsFor( Bcp47Code $code ): ?array {
		$variants = $this->variants();
		$lang = $this->languageFactory->getLanguage( $code );
		$tuple = $variants[$lang->getCode()] ?? null;
		if ( $tuple === null ) {
			return null;
		}
		return [
			'base' => $this->languageFactory->getLanguage( $tuple['base'] ),
			'fallbacks' => array_map(
				[ $this->languageFactory, 'getLanguage' ],
				$tuple['fallbacks']
			),
		];
	}

	public function widthOption(): int {
		// Even though this looks like Parsoid is supporting per-user thumbsize
		// options, that is not the case, Parsoid doesn't receive user session state
		$thumbsize = $this->userOptionsLookup->getDefaultOption( 'thumbsize' );
		return $this->config->get( MainConfigNames::ThumbLimits )[$thumbsize];
	}

	/** @inheritDoc */
	protected function getVariableIDs(): array {
		return $this->magicWordFactory->getVariableIDs();
	}

	/** @inheritDoc */
	protected function getFunctionSynonyms(): array {
		return $this->parserFactory->getMainInstance()->getFunctionSynonyms();
	}

	/** @return array<string,array> $magicWord => [ int $caseSensitive, string ...$alias ] */
	protected function getMagicWords(): array {
		return $this->contLang->getMagicWords();
	}

	/** @inheritDoc */
	public function getMagicWordMatcher( string $id ): string {
		return $this->magicWordFactory->get( $id )->getRegexStartToEnd();
	}

	/** @inheritDoc */
	public function getParameterizedAliasMatcher( array $words ): callable {
		// PORT-FIXME: this should be combined with
		// getMediaPrefixParameterizedAliasMatcher; see PORT-FIXME comment
		// in that method.
		// Filter out timedmedia-* unless that extension is loaded, so Parsoid
		// doesn't have a hard dependency on an extension.
		if ( !$this->isTimedMediaHandlerLoaded ) {
			$words = preg_grep( '/^timedmedia_/', $words, PREG_GREP_INVERT );
		}
		$words = $this->magicWordFactory->newArray( $words );
		return static function ( $text ) use ( $words ) {
			$ret = $words->matchVariableStartToEnd( $text );
			if ( $ret[0] === false || $ret[1] === false ) {
				return null;
			} else {
				return [ 'k' => $ret[0], 'v' => $ret[1] ];
			}
		};
	}

	private function populateExtensionTags(): void {
		$this->extensionTags = array_fill_keys( $this->parserFactory->getMainInstance()->getTags(), true );
	}

	/** @inheritDoc */
	protected function getNonNativeExtensionTags(): array {
		if ( $this->extensionTags === null ) {
			$this->populateExtensionTags();
		}
		return $this->extensionTags;
	}

	/** @inheritDoc */
	protected function shouldValidateExtConfig(): bool {
		// Only perform json schema validation for extension module
		// configurations when running tests.
		return defined( 'MW_PHPUNIT_TEST' );
	}

	/** @inheritDoc */
	public function getMaxTemplateDepth(): int {
		return (int)$this->config->get( MainConfigNames::MaxTemplateDepth );
	}

	/**
	 * Overrides the max template depth in the MediaWiki configuration.
	 */
	public function setMaxTemplateDepth( int $depth ): void {
		// Parsoid's command-line tools let you set the max template depth
		// as a CLI argument.  Since we currently invoke the legacy
		// preprocessor in some situations, we can't just override
		// ::getMaxTemplateDepth() above, we need to reset the Config
		// service.
		if ( $this->config instanceof MutableConfig ) {
			$this->config->set( MainConfigNames::MaxTemplateDepth, $depth );
		} else {
			// Fall back on global variable (hopefully we're using
			// a GlobalVarConfig and this will work)
			$GLOBALS['wgMaxTemplateDepth'] = $depth;
		}
	}

	/** @inheritDoc */
	protected function getSpecialNSAliases(): array {
		$nsAliases = [
			'Special',
			$this->quoteTitleRe( $this->contLang->getNsText( NS_SPECIAL ) )
		];
		foreach (
			$this->contLang->getNamespaceAliases() +
			$this->config->get( MainConfigNames::NamespaceAliases )
			as $name => $ns
		) {
			if ( $ns === NS_SPECIAL ) {
				$nsAliases[] = $this->quoteTitleRe( $name );
			}
		}

		return $nsAliases;
	}

	/** @inheritDoc */
	protected function getSpecialPageAliases( string $specialPage ): array {
		return array_merge( [ $specialPage ],
			$this->contLang->getSpecialPageAliases()[$specialPage] ?? []
		);
	}

	/** @inheritDoc */
	protected function getProtocols(): array {
		return $this->config->get( MainConfigNames::UrlProtocols );
	}

	public function getNoFollowConfig(): array {
		return [
			'nofollow' => $this->config->get( MainConfigNames::NoFollowLinks ),
			'nsexceptions' => $this->config->get( MainConfigNames::NoFollowNsExceptions ),
			'domainexceptions' => $this->config->get( MainConfigNames::NoFollowDomainExceptions )
		];
	}

	/** @return string|false */
	public function getExternalLinkTarget() {
		return $this->config->get( MainConfigNames::ExternalLinkTarget );
	}

	/**
	 * Return the localization key we should use for asynchronous
	 * fallback content.
	 */
	public function getAsyncFallbackMessageKey(): string {
		return 'parsoid-async-not-ready-fallback';
	}

	// MW-specific helper

	/**
	 * Returns true iff Parsoid natively supports the given content model.
	 * @param string $model content model identifier
	 * @return bool
	 */
	public function supportsContentModel( string $model ): bool {
		if ( $model === CONTENT_MODEL_WIKITEXT ) {
			return true;
		}

		// Check if the content model serializes to wikitext.
		// NOTE: We could use isSupportedFormat( CONTENT_FORMAT_WIKITEXT ) if PageContent::getContent()
		//       would specify the format when calling serialize().
		try {
			$handler = $this->contentHandlerFactory->getContentHandler( $model );
			if ( $handler->getDefaultFormat() === CONTENT_FORMAT_WIKITEXT ) {
				return true;
			}
		} catch ( MWUnknownContentModelException ) {
			// If the content model is not known, it can't be supported.
			return false;
		}

		return $this->getContentModelHandler( $model ) !== null;
	}

}
