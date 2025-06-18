<?php

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Language\LanguageCode;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Config\PageConfig;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * @since 1.40
 * @unstable should be marked stable before 1.40 release
 */
class LanguageVariantConverter {
	private PageConfigFactory $pageConfigFactory;
	private ?PageConfig $pageConfig = null;
	private PageIdentity $pageIdentity;
	private Title $pageTitle;
	private Parsoid $parsoid;
	private SiteConfig $siteConfig;
	private LanguageConverterFactory $languageConverterFactory;
	private LanguageFactory $languageFactory;
	/**
	 * Page language override from the Content-Language header.
	 */
	private ?Bcp47Code $pageLanguageOverride = null;
	private bool $isFallbackLanguageConverterEnabled = true;

	/** Hook for unit testing to supply mock for ParserOptions. */
	private ?ParserOptions $parserOptionsForTest = null;

	public function __construct(
		PageIdentity $pageIdentity,
		PageConfigFactory $pageConfigFactory,
		Parsoid $parsoid,
		SiteConfig $siteConfig,
		TitleFactory $titleFactory,
		LanguageConverterFactory $languageConverterFactory,
		LanguageFactory $languageFactory
	) {
		$this->pageConfigFactory = $pageConfigFactory;
		$this->pageIdentity = $pageIdentity;
		$this->parsoid = $parsoid;
		$this->siteConfig = $siteConfig;
		$this->pageTitle = $titleFactory->newFromPageIdentity( $this->pageIdentity );
		$this->languageConverterFactory = $languageConverterFactory;
		$this->languageFactory = $languageFactory;
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
	 * @param Bcp47Code $language
	 * @return void
	 */
	public function setPageLanguageOverride( Bcp47Code $language ) {
		$this->pageLanguageOverride = $language;
	}

	/**
	 * Perform variant conversion on a PageBundle object.
	 *
	 * @param PageBundle $pageBundle
	 * @param Bcp47Code $targetVariant
	 * @param ?Bcp47Code $sourceVariant
	 *
	 * @return PageBundle The converted PageBundle, or the object passed in as
	 *         $pageBundle if the conversion is not supported.
	 * @throws HttpException
	 */
	public function convertPageBundleVariant(
		PageBundle $pageBundle,
		Bcp47Code $targetVariant,
		?Bcp47Code $sourceVariant = null
	): PageBundle {
		[ $pageLanguage, $sourceVariant ] =
			$this->getBaseAndSourceLanguage( $pageBundle, $sourceVariant );

		if ( !$this->siteConfig->langConverterEnabledBcp47( $pageLanguage ) ) {
			// If the language doesn't support variants, just return the content unmodified.
			return $pageBundle;
		}

		$pageConfig = $this->getPageConfig( $pageLanguage, $sourceVariant );

		if ( $this->parsoid->implementsLanguageConversionBcp47( $pageConfig, $targetVariant ) ) {
			return $this->parsoid->pb2pb(
				$pageConfig, 'variant', $pageBundle,
				[
					'variant' => [
						'source' => $sourceVariant,
						'target' => $targetVariant,
					]
				]
			);
		} else {
			if ( !$this->isFallbackLanguageConverterEnabled ) {
				// Fallback variant conversion is not enabled, return the page bundle as is.
				return $pageBundle;
			}

			// LanguageConverter::hasVariant and LanguageConverter::convertTo
			// could take a string|Bcp47Code in the future, which would
			// allow us to avoid the $targetVariantCode conversion here.
			$baseLanguage = $this->languageFactory->getParentLanguage( $targetVariant );
			$languageConverter = $this->languageConverterFactory->getLanguageConverter( $baseLanguage );
			$targetVariantCode = $this->languageFactory->getLanguage( $targetVariant )->getCode();
			if ( $languageConverter->hasVariant( $targetVariantCode ) ) {
				// NOTE: This is not a convert() because we have the exact desired variant
				// and don't need to compute a preferred variant based on a base language.
				// Also see T267067 for why convert() should be avoided.
				$convertedHtml = $languageConverter->convertTo( $pageBundle->html, $targetVariantCode );
				$pageVariant = $targetVariant;
			} else {
				// No conversion possible - pass through original HTML in original language
				$convertedHtml = $pageBundle->html;
				$pageVariant = $pageConfig->getPageLanguageBcp47();
			}

			// Add a note so that we can identify what was used to perform the variant conversion
			$msg = "<!-- Variant conversion performed using the core LanguageConverter -->";
			$convertedHtml = $msg . $convertedHtml;

			// NOTE: Keep this in sync with code in Parsoid.php in Parsoid repo
			// Add meta information that Parsoid normally adds
			$headers = [
				'content-language' => $pageVariant->toBcp47Code(),
				'vary' => [ 'Accept', 'Accept-Language' ]
			];
			$doc = DOMUtils::parseHTML( '' );
			$doc->appendChild( $doc->createElement( 'head' ) );
			DOMUtils::addHttpEquivHeaders( $doc, $headers );
			$docElt = $doc->documentElement;
			'@phan-var Element $docElt';
			$docHtml = DOMCompat::getOuterHTML( $docElt );
			$convertedHtml = preg_replace( "#</body>#", $docHtml, "$convertedHtml</body>" );
			return new PageBundle(
				$convertedHtml, [], [], $pageBundle->version, $headers
			);
		}
	}

	/**
	 * Perform variant conversion on a ParserOutput object.
	 *
	 * @param ParserOutput $parserOutput
	 * @param Bcp47Code $targetVariant
	 * @param ?Bcp47Code $sourceVariant
	 *
	 * @return ParserOutput
	 */
	public function convertParserOutputVariant(
		ParserOutput $parserOutput,
		Bcp47Code $targetVariant,
		?Bcp47Code $sourceVariant = null
	): ParserOutput {
		$pageBundle = PageBundleParserOutputConverter::pageBundleFromParserOutput( $parserOutput );
		$modifiedPageBundle = $this->convertPageBundleVariant( $pageBundle, $targetVariant, $sourceVariant );

		return PageBundleParserOutputConverter::parserOutputFromPageBundle( $modifiedPageBundle, $parserOutput );
	}

	/**
	 * Disable fallback language variant converter
	 */
	public function disableFallbackLanguageConverter(): void {
		$this->isFallbackLanguageConverterEnabled = false;
	}

	private function getPageConfig( Bcp47Code $pageLanguage, ?Bcp47Code $sourceVariant ): PageConfig {
		if ( $this->pageConfig ) {
			return $this->pageConfig;
		}

		try {
			$this->pageConfig = $this->pageConfigFactory->createFromParserOptions(
				// Hook for unit testing: can supply a mock for parser options
				$this->parserOptionsForTest ?? ParserOptions::newFromAnon(),
				$this->pageIdentity,
				null,
				$pageLanguage
			);

			if ( $sourceVariant ) {
				$this->pageConfig->setVariantBcp47( $sourceVariant );
			}
		} catch ( RevisionAccessException ) {
			// TODO: Throw a different exception, this class should not know
			//       about HTTP status codes.
			throw new LocalizedHttpException( new MessageValue( "rest-specified-revision-unavailable" ), 404 );
		}

		return $this->pageConfig;
	}

	/**
	 * Try to determine the page's language code as follows:
	 *
	 * First consider any value set by calling ::setPageLanguageOverride();
	 * this would have come from a Content-Language header.
	 *
	 * If ::setPageLanguageOverride() has not been called, check for a
	 * content-language header in $pageBundle, which should be
	 * equivalent.  These are used when the title/article doesn't
	 * (yet) exist.
	 *
	 * If these are not given, use the $default if given; this is used
	 * to allow additional parameters to the request to be used as
	 * fallbacks.
	 *
	 * If we don't have $default, but we do have a PageConfig in
	 * $this->pageConfig, return $this->pageConfig->getPageLanguage().
	 *
	 * Finally, fall back to $this->pageTitle->getPageLanguage().
	 *
	 * @param PageBundle $pageBundle
	 * @param Bcp47Code|null $default A default language, used after
	 *   Content-Language but before PageConfig/Title lookup.
	 *
	 * @return Bcp47Code the page language; may be a variant.
	 */
	private function getPageLanguage( PageBundle $pageBundle, ?Bcp47Code $default = null ): Bcp47Code {
		// If a language was set by calling setPageLanguageOverride(), always use it!
		if ( $this->pageLanguageOverride ) {
			return $this->pageLanguageOverride;
		}

		// If the page bundle contains a language code, use that.
		$pageBundleLanguage = $pageBundle->headers[ 'content-language' ] ?? null;
		if ( $pageBundleLanguage ) {
			// The HTTP header will contain a BCP-47 language code, not a
			// mediawiki-internal one.
			return new Bcp47CodeValue( $pageBundleLanguage );
		}

		// NOTE: Use explicit default *before* we try PageBundle, because PageConfig::getPageLanguage()
		//       falls back to Title::getPageLanguage(). If we did that first, $default would never be used.
		if ( $default ) {
			return $default;
		}

		// If we have a PageConfig, we can ask it for the page's language. Note that this will fall back to
		// Title::getPageLanguage(), so it has to be the last thing we try.
		if ( $this->pageConfig ) {
			return $this->pageConfig->getPageLanguageBcp47();
		}

		// Finally, just go by the code associated with the title. This may come from the database or
		// it may be determined based on the title itself.
		return $this->pageTitle->getPageLanguage();
	}

	/**
	 * Determine the codes of the base language and the source variant.
	 *
	 * The base language will be used to find the appropriate LanguageConverter.
	 * It should never be a variant.
	 *
	 * The source variant will be used to instruct the LanguageConverter.
	 * It should always be a variant (or null to trigger auto-detection of
	 * the source variant).
	 *
	 * @param PageBundle $pageBundle
	 * @param ?Bcp47Code $sourceLanguage
	 *
	 * @return array{0:Bcp47Code,1:?Bcp47Code} [ Bcp47Code $pageLanguage, ?Bcp47Code $sourceLanguage ]
	 */
	private function getBaseAndSourceLanguage( PageBundle $pageBundle, ?Bcp47Code $sourceLanguage ): array {
		// Try to determine the language code associated with the content of the page.
		// The result may be a variant code.
		$baseLanguage = $this->getPageLanguage( $pageBundle, $sourceLanguage );

		// To find out if $baseLanguage is actually a variant, get the parent language and compare.
		$parentLang = $this->languageFactory->getParentLanguage( $baseLanguage );

		// If $parentLang is not the same language as $baseLanguage, this means that
		// $baseLanguage is a variant. In that case, set $sourceLanguage to that
		// variant (unless $sourceLanguage is already set), and set $baseLanguage
		// to the $parentLang
		if ( $parentLang && strcasecmp( $parentLang->toBcp47Code(), $baseLanguage->toBcp47Code() ) !== 0 ) {
			if ( !$sourceLanguage ) {
				$sourceLanguage = $baseLanguage;
			}
			$baseLanguage = $parentLang;
		}

		if ( $sourceLanguage !== null ) {
			$parentConverter = $this->languageConverterFactory->getLanguageConverter( $parentLang );
			// If the source variant isn't actually a variant, trigger auto-detection
			$sourceIsVariant = (
				strcasecmp( $parentLang->toBcp47Code(), $sourceLanguage->toBcp47Code() ) !== 0 &&
				$parentConverter->hasVariant(
					LanguageCode::bcp47ToInternal( $sourceLanguage->toBcp47Code() )
				)
			);
			if ( !$sourceIsVariant ) {
				$sourceLanguage = null;
			}
		}

		return [ $baseLanguage, $sourceLanguage ];
	}
}
