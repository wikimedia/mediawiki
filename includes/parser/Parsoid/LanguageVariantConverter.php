<?php

namespace MediaWiki\Parser\Parsoid;

use InvalidArgumentException;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWiki\Rest\HttpException;
use MediaWiki\Revision\RevisionAccessException;
use ParserOutput;
use Title;
use TitleFactory;
use Wikimedia\Parsoid\Config\PageConfig;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Parsoid;

/**
 * @since 1.40
 * @unstable should be marked stable before 1.40 release
 */
class LanguageVariantConverter {
	/** @var PageConfigFactory */
	private $pageConfigFactory;

	/** @var PageConfig */
	private $pageConfig;

	/** @var PageIdentity */
	private $pageIdentity;

	/** @var Title */
	private $pageTitle;

	/** @var Parsoid */
	private $parsoid;

	/** @var array */
	private $parsoidSettings;

	/** @var SiteConfig */
	private $siteConfig;

	/** @var TitleFactory */
	private $titleFactory;
	private LanguageFactory $languageFactory;

	/** @var string */
	private $pageLanguageOverride;

	public function __construct(
		PageIdentity $pageIdentity,
		PageConfigFactory $pageConfigFactory,
		Parsoid $parsoid,
		array $parsoidSettings,
		SiteConfig $siteConfig,
		TitleFactory $titleFactory,
		LanguageFactory $languageFactory
	) {
		$this->pageConfigFactory = $pageConfigFactory;
		$this->pageIdentity = $pageIdentity;
		$this->parsoid = $parsoid;
		$this->parsoidSettings = $parsoidSettings;
		$this->siteConfig = $siteConfig;
		$this->titleFactory = $titleFactory;
		$this->languageFactory = $languageFactory;

		// @phan-suppress-next-line PhanPossiblyNullTypeMismatchProperty
		$this->pageTitle = $this->titleFactory->castFromPageIdentity( $this->pageIdentity );
	}

	/**
	 * Set the PageConfig object to be used during language variant conversion.
	 * If not provided, the object will be created.
	 *
	 * @param PageConfig $pageConfig
	 * @return void
	 */
	public function setPageConfig( PageConfig $pageConfig ) {
		$this->pageConfig = $pageConfig;
	}

	/**
	 * Set the page content language override.
	 *
	 * @param string $language
	 * @return void
	 */
	public function setPageLanguageOverride( string $language ) {
		$this->pageLanguageOverride = $language;
	}

	/**
	 * Perform variant conversion on a PageBundle object.
	 *
	 * @param PageBundle $pageBundle
	 * @param string $targetVariantCode
	 * @param string|null $sourceVariantCode
	 *
	 * @return PageBundle
	 * @throws HttpException
	 */
	public function convertPageBundleVariant(
		PageBundle $pageBundle,
		string $targetVariantCode,
		string $sourceVariantCode = null
	): PageBundle {
		[ $pageLanguageCode, $sourceVariantCode ] =
			$this->getBaseAndSourceLanguageCode( $pageBundle, $sourceVariantCode );

		if ( !$this->siteConfig->langConverterEnabledForLanguage( $pageLanguageCode ) ) {
			throw new InvalidArgumentException( "LanguageConversion is not supported for $pageLanguageCode." );
		}

		$pageConfig = $this->getPageConfig( $pageLanguageCode, $sourceVariantCode );

		$modifiedPageBundle = $this->parsoid->pb2pb(
			$pageConfig, 'variant', $pageBundle,
			[
				'variant' => [
					'source' => $sourceVariantCode,
					'target' => $targetVariantCode,
				]
			]
		);

		return $modifiedPageBundle;
	}

	/**
	 * Perform variant conversion on a ParserOutput object.
	 *
	 * @param ParserOutput $parserOutput
	 * @param string $targetVariantCode
	 * @param string|null $sourceVariantCode
	 *
	 * @return ParserOutput
	 */
	public function convertParserOutputVariant(
		ParserOutput $parserOutput,
		string $targetVariantCode,
		string $sourceVariantCode = null
	): ParserOutput {
		$pageBundle = PageBundleParserOutputConverter::pageBundleFromParserOutput( $parserOutput );
		$modifiedPageBundle = $this->convertPageBundleVariant( $pageBundle, $targetVariantCode, $sourceVariantCode );

		return PageBundleParserOutputConverter::parserOutputFromPageBundle( $modifiedPageBundle );
	}

	private function getPageConfig( string $pageLanguageCode, ?string $sourceVariantCode ): PageConfig {
		if ( $this->pageConfig ) {
			return $this->pageConfig;
		}

		try {
			$this->pageConfig = $this->pageConfigFactory->create(
				$this->pageIdentity,
				null,
				null,
				null,
				$pageLanguageCode,
				$this->parsoidSettings
			);

			if ( $sourceVariantCode ) {
				$this->pageConfig->setVariant( $sourceVariantCode );
			}
		} catch ( RevisionAccessException $exception ) {
			// TODO: Throw a different exception, this class should not know
			//       about HTTP status codes.
			throw new HttpException( 'The specified revision is deleted or suppressed.', 404 );
		}

		return $this->pageConfig;
	}

	/**
	 * Try to determine the page's language code as follows:
	 *
	 * First consider any value set by calling setPageLanguageOverride.
	 * If setPageLanguageOverride() has not been called, check for a content-language header in $pageBundle.
	 * If that is not given, use the $default if given.
	 *
	 * If we don't have $default, but we do have a PageConfig in $this->pageConfig,
	 * return $this->pageConfig->getPageLanguage().
	 * Finally, fall back to $this->pageTitle->getPageLanguage()->getCode();
	 *
	 * @param PageBundle $pageBundle
	 * @param string|null $default
	 *
	 * @return string A language code. May be a variant.
	 */
	private function getPageLanguageCode( PageBundle $pageBundle, ?string $default = null ): string {
		// If a language was set by calling setPageLanguageOverride(), always use it!
		if ( $this->pageLanguageOverride ) {
			return $this->pageLanguageOverride;
		}

		// If the page bundle contains a language code, use that.
		$pageBundleLanguage = $pageBundle->headers[ 'content-language' ] ?? null;
		if ( $pageBundleLanguage ) {
			return $pageBundleLanguage;
		}

		// NOTE: Use explicit default *before* we try PageBundle, because PageConfig::getPageLanguage()
		//       falls back to Title::getPageLanguage(). If we did that first, $default would never be used.
		if ( $default ) {
			return $default;
		}

		// If we have a PageConfig, we can ask it for the page's language. Note that this will fall back to
		// Title::getPageLanguage(), so it has to be the last thing we try.
		if ( $this->pageConfig ) {
			return $this->pageConfig->getPageLanguage();
		}

		// Finally, just go by the code associated with the title. This may come from the database or
		// it may be determined based on the title itself.
		return $this->pageTitle->getPageLanguage()->getCode();
	}

	/**
	 * Determine the codes of the base language and the source variant.
	 *
	 * The base language will be used to find the appropriate LanguageConverter. It should never be a variant.
	 * The source variant will be used to instruct the LanguageConverter. It should always be a variant (or
	 * null to trigger auto-detection of the source variant).
	 *
	 * @param PageBundle $pageBundle
	 * @param string|null $sourceLanguageCode
	 *
	 * @return array<string> [ string $baseLanguageCode, ?string $sourceLanguageCode ]
	 */
	private function getBaseAndSourceLanguageCode( PageBundle $pageBundle, ?string $sourceLanguageCode ): array {
		// Try to determine the language code associated with the content of the page.
		// The result may be a variant code.
		$baseLanguageCode = $this->getPageLanguageCode( $pageBundle, $sourceLanguageCode );

		// To find out if $baseLanguageCode is actually a variant, get the parent language and compare.
		$parentLang = $this->languageFactory->getParentLanguage( $baseLanguageCode );

		// If $parentLang is not the same language as $baseLanguageCode, this means that
		// $baseLanguageCode is a variant. In that case, set $sourceLanguageCode to that
		// variant (unless $sourceLanguageCode is already set), and set $baseLanguageCode
		// to the code of $baseLanguage.
		if ( $parentLang && $parentLang->getCode() !== $baseLanguageCode ) {
			if ( !$sourceLanguageCode ) {
				$sourceLanguageCode = $baseLanguageCode;
			}
			$baseLanguageCode = $parentLang->getCode();
		}

		// If the source variant isn't actually a variant, trigger auto-detection
		if ( $sourceLanguageCode === $baseLanguageCode ) {
			$sourceLanguageCode = null;
		}

		// Invalid phan error: Returning array{0:string,1:?non-empty-string|?string} but declared to
		// return string[]
		// @phan-suppress-next-line PhanTypeMismatchReturn
		return [ $baseLanguageCode, $sourceLanguageCode ];
	}
}
