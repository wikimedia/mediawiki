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

use Config;
use ExtensionRegistry;
use Language;
use LanguageConverter;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MagicWordArray;
use MagicWordFactory;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\Utils\UrlUtils;
use MutableConfig;
use MWException;
use NamespaceInfo;
use Parser;
use ParserOutput;
use PrefixingStatsdDataFactoryProxy;
use Psr\Log\LoggerInterface;
use Title;
use UnexpectedValueException;
use WikiMap;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Parsoid\Config\SiteConfig as ISiteConfig;
use Wikimedia\Parsoid\Core\ContentMetadataCollector;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\Utils\Utils;

/**
 * Site-level configuration for Parsoid
 *
 * This includes both global configuration and wiki-level configuration.
 *
 * @since 1.39
 */
class SiteConfig extends ISiteConfig {

	/**
	 * Regular expression fragment for matching wikitext comments.
	 * Meant for inclusion in other regular expressions.
	 */
	protected const COMMENT_REGEXP_FRAGMENT = '<!--(?>[\s\S]*?-->)';

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::GalleryOptions,
		MainConfigNames::AllowExternalImages,
		MainConfigNames::AllowExternalImagesFrom,
		MainConfigNames::Server,
		MainConfigNames::ArticlePath,
		MainConfigNames::InterwikiMagic,
		MainConfigNames::ExtraInterlanguageLinkPrefixes,
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
	];

	/** @var ServiceOptions */
	private $config;

	/** @var Config */
	private $optionalConfig;

	/** @var array Parsoid-specific options array from $config */
	private $parsoidSettings;

	/** @var Language */
	private $contLang;

	/** @var StatsdDataFactoryInterface */
	private $stats;

	/** @var MagicWordFactory */
	private $magicWordFactory;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/** @var InterwikiLookup */
	private $interwikiLookup;

	/** @var Parser */
	private $parser;

	/** @var UserOptionsLookup */
	private $userOptionsLookup;

	/** @var ObjectFactory */
	private $objectFactory;

	/** @var LanguageFactory */
	private $languageFactory;

	/** @var LanguageConverterFactory */
	private $languageConverterFactory;

	/** @var LanguageNameUtils */
	private $languageNameUtils;

	/** @var UrlUtils */
	private $urlUtils;

	/** @var string|null */
	private $baseUri;

	/** @var string|null */
	private $relativeLinkPrefix;

	/** @var array|null */
	private $interwikiMap;

	/** @var array|null */
	private $variants;

	/** @var array */
	private $extensionTags;

	/**
	 * @param ServiceOptions $config MediaWiki main configuration object
	 * @param array $parsoidSettings Parsoid-specific options array from main configuration.
	 * @param ObjectFactory $objectFactory
	 * @param Language $contentLanguage Content language.
	 * @param StatsdDataFactoryInterface $stats
	 * @param MagicWordFactory $magicWordFactory
	 * @param NamespaceInfo $namespaceInfo
	 * @param SpecialPageFactory $specialPageFactory
	 * @param InterwikiLookup $interwikiLookup
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param LanguageFactory $languageFactory
	 * @param LanguageConverterFactory $languageConverterFactory
	 * @param LanguageNameUtils $languageNameUtils
	 * @param UrlUtils $urlUtils
	 * @param Parser $parser
	 * @param Config $optionalConfig
	 */
	public function __construct(
		ServiceOptions $config,
		array $parsoidSettings,
		ObjectFactory $objectFactory,
		Language $contentLanguage,
		StatsdDataFactoryInterface $stats,
		MagicWordFactory $magicWordFactory,
		NamespaceInfo $namespaceInfo,
		SpecialPageFactory $specialPageFactory,
		InterwikiLookup $interwikiLookup,
		UserOptionsLookup $userOptionsLookup,
		LanguageFactory $languageFactory,
		LanguageConverterFactory $languageConverterFactory,
		LanguageNameUtils $languageNameUtils,
		UrlUtils $urlUtils,
		// These arguments are temporary and will be removed once
		// better solutions are found.
		Parser $parser, // T268776
		Config $optionalConfig // T268777
	) {
		parent::__construct();

		$config->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->config = $config;
		$this->optionalConfig = $optionalConfig;
		$this->parsoidSettings = $parsoidSettings;

		$this->objectFactory = $objectFactory;
		$this->contLang = $contentLanguage;
		$this->stats = $stats;
		$this->magicWordFactory = $magicWordFactory;
		$this->namespaceInfo = $namespaceInfo;
		$this->specialPageFactory = $specialPageFactory;
		$this->interwikiLookup = $interwikiLookup;
		$this->parser = $parser;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->languageFactory = $languageFactory;
		$this->languageConverterFactory = $languageConverterFactory;
		$this->languageNameUtils = $languageNameUtils;
		$this->urlUtils = $urlUtils;

		// Override parent default
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
		// TODO: inject this (T257586)
		$parsoidModules = ExtensionRegistry::getInstance()->getAttribute( 'ParsoidModules' );
		foreach ( $parsoidModules as $configOrSpec ) {
			$this->registerExtensionModule( $configOrSpec );
		}
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

	public function metrics(): ?StatsdDataFactoryInterface {
		// TODO: inject
		static $prefixedMetrics = null;
		if ( $prefixedMetrics === null ) {
			$prefixedMetrics = new PrefixingStatsdDataFactoryProxy(
				// Our stats will also get prefixed with 'MediaWiki.'
				$this->stats,
				$this->parsoidSettings['metricsPrefix'] ?? 'Parsoid.'
			);
		}
		return $prefixedMetrics;
	}

	public function nativeGalleryEnabled(): bool {
		return $this->parsoidSettings['nativeGalleryEnabled'] ?? false;
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

		$bits = wfParseUrl( $url );
		if ( !$bits ) {
			throw new UnexpectedValueException( "Failed to parse article path '$url'" );
		}

		if ( empty( $bits['path'] ) ) {
			$path = '/';
		} else {
			$path = wfRemoveDotSegments( $bits['path'] );
		}

		$relParts = [ 'query' => true, 'fragment' => true ];
		$base = array_diff_key( $bits, $relParts );
		$rel = array_intersect_key( $bits, $relParts );

		$i = strrpos( $path, '/' );
		$base['path'] = substr( $path, 0, $i + 1 );
		$rel['path'] = '.' . substr( $path, $i );

		$this->baseUri = wfAssembleUrl( $base );
		$this->relativeLinkPrefix = wfAssembleUrl( $rel );
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

	public function interwikiMap(): array {
		// Unfortunate that this mostly duplicates \ApiQuerySiteinfo::appendInterwikiMap()
		if ( $this->interwikiMap !== null ) {
			return $this->interwikiMap;
		}
		$this->interwikiMap = [];

		$getPrefixes = $this->interwikiLookup->getAllPrefixes();
		$langNames = $this->languageNameUtils->getLanguageNames();
		$extraLangPrefixes = $this->config->get( MainConfigNames::ExtraInterlanguageLinkPrefixes );
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
			if ( strpos( $val['url'], '$1' ) === false ) {
				$val['url'] .= '$1';
			}

			if ( substr( $row['iw_url'], 0, 2 ) == '//' ) {
				$val['protorel'] = true;
			}
			if ( isset( $row['iw_local'] ) && $row['iw_local'] == '1' ) {
				$val['local'] = true;
			}
			if ( isset( $langNames[$prefix] ) ) {
				$val['language'] = true;
			}
			if ( in_array( $prefix, $localInterwikis, true ) ) {
				$val['localinterwiki'] = true;
			}
			if ( in_array( $prefix, $extraLangPrefixes, true ) ) {
				$val['extralanglink'] = true;

				/**
				 * ApiQuerySiteinfo adds a 'linktext' field, but Parsoid
				 * doesn't use this -- and because it uses wfMessage()
				 * it implicitly uses a MessageCache which would have to
				 * be injected here.
				 */
				// $linktext = wfMessage( "interlanguage-link-$prefix" );
				// if ( !$linktext->isDisabled() ) {
				// 	$val['linktext'] = $linktext->text();
				// }
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

	public function lang(): string {
		return $this->config->get( MainConfigNames::LanguageCode );
	}

	public function mainpage(): string {
		// @todo Perhaps should inject TitleFactory here?
		return Title::newMainPage()->getPrefixedText();
	}

	public function responsiveReferences(): array {
		// @todo This is from the Cite extension, which shouldn't be known about by core
		// T268777
		return [
			'enabled' => $this->optionalConfig->has( 'CiteResponsiveReferences' ) ?
				$this->optionalConfig->get( 'CiteResponsiveReferences' ) : false,
			'threshold' => 10,
		];
	}

	public function rtl(): bool {
		return $this->contLang->isRTL();
	}

	/** @inheritDoc */
	public function langConverterEnabled( string $lang ): bool {
		if ( $this->languageConverterFactory->isConversionDisabled() ) {
			return false;
		}
		if ( !in_array( $lang, LanguageConverter::$languagesWithVariants, true ) ) {
			return false;
		}
		try {
			$langObject = $this->languageFactory->getLanguage( $lang );
			$converter = $this->languageConverterFactory->getLanguageConverter( $langObject );
			return $converter->hasVariants();
		} catch ( MWException $ex ) {
			// Probably a syntactically invalid language code
			return false;
		}
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

	/** @inheritDoc */
	public function exportMetadataToHead(
		Document $document,
		ContentMetadataCollector $metadata,
		string $defaultTitle,
		string $lang
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

	public function variants(): array {
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
		return $this->parser->getFunctionSynonyms();
	}

	/** @inheritDoc */
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
		if ( !ExtensionRegistry::getInstance()->isLoaded( 'TimedMediaHandler' ) ) {
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
		$this->extensionTags = array_fill_keys( $this->parser->getTags(), true );
	}

	/** @inheritDoc */
	protected function getNonNativeExtensionTags(): array {
		if ( $this->extensionTags === null ) {
			$this->populateExtensionTags();
		}
		return $this->extensionTags;
	}

	/** @inheritDoc */
	public function getMaxTemplateDepth(): int {
		return (int)$this->config->get( MainConfigNames::MaxTemplateDepth );
	}

	/**
	 * Overrides the max template depth in the MediaWiki configuration.
	 * @param int $depth
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

	/** @return array */
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
}
