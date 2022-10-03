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
	 * @param string $targetLanguageCode
	 * @return PageBundle
	 * @throws InvalidArgumentException If language conversion is not enabled for the source language
	 */
	public function convertPageBundleVariant(
		PageBundle $pageBundle,
		string $targetLanguageCode
	): PageBundle {
		$sourceLanguageCode = $this->getSourceLanguageCode( $pageBundle );
		if ( !$this->siteConfig->langConverterEnabledForLanguage( $sourceLanguageCode ) ) {
			throw new InvalidArgumentException( 'LanguageConversion is not enabled on this article.' );
		}

		$pageConfig = $this->getPageConfig( $sourceLanguageCode );

		$modifiedPageBundle = $this->parsoid->pb2pb(
			$pageConfig, 'variant', $pageBundle,
			[
				'variant' => [
					'source' => $sourceLanguageCode,
					'target' => $targetLanguageCode,
				]
			]
		);

		return $modifiedPageBundle;
	}

	/**
	 * Perform variant conversion on a ParserOutput object.
	 *
	 * @param ParserOutput $parserOutput
	 * @param string $targetLanguageCode
	 * @return ParserOutput
	 */
	public function convertParserOutputVariant(
		ParserOutput $parserOutput,
		string $targetLanguageCode
	): ParserOutput {
		$pageBundle = PageBundleParserOutputConverter::pageBundleFromParserOutput( $parserOutput );
		$modifiedPageBundle = $this->convertPageBundleVariant( $pageBundle, $targetLanguageCode );

		return PageBundleParserOutputConverter::parserOutputFromPageBundle( $modifiedPageBundle );
	}

	private function getPageConfig( string $sourceLanguageCode ): PageConfig {
		if ( $this->pageConfig ) {
			return $this->pageConfig;
		}

		try {
			$this->pageConfig = $this->pageConfigFactory->create(
				$this->pageIdentity,
				null,
				null,
				null,
				$sourceLanguageCode,
				$this->parsoidSettings
			);
		} catch ( RevisionAccessException $exception ) {
			// TODO: Throw a different exception, this class should not know
			//       about HTTP status codes.
			throw new HttpException( 'The specified revision is deleted or suppressed.', 404 );
		}

		return $this->pageConfig;
	}

	private function getSourceLanguageCode( PageBundle $pageBundle ): string {
		$languageCode = $pageBundle->headers[ 'content-language' ] ?? null;
		return $languageCode ?? $this->pageTitle->getPageLanguage()->getCode();
	}
}
