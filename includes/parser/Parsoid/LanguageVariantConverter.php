<?php

namespace MediaWiki\Parser\Parsoid;

use InvalidArgumentException;
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

	public function __construct(
		PageIdentity $pageIdentity,
		PageConfigFactory $pageConfigFactory,
		Parsoid $parsoid,
		array $parsoidSettings,
		SiteConfig $siteConfig,
		TitleFactory $titleFactory
	) {
		$this->pageConfigFactory = $pageConfigFactory;
		$this->pageIdentity = $pageIdentity;
		$this->parsoid = $parsoid;
		$this->parsoidSettings = $parsoidSettings;
		$this->siteConfig = $siteConfig;
		$this->titleFactory = $titleFactory;
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
		$pageLanguageCode = $this->getPageLanguageCode( $pageBundle );

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

	private function getPageLanguageCode( PageBundle $pageBundle ): string {
		$languageCode = $pageBundle->headers[ 'content-language' ] ?? null;
		return $languageCode ?? $this->pageTitle->getPageLanguage()->getCode();
	}
}
