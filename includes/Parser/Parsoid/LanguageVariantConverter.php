<?php
declare( strict_types = 1 );

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageFactory;
use MediaWiki\OutputTransform\OutputTransformPipeline;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Rest\HttpException;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Core\HtmlPageBundle;

/**
 * @since 1.40
 * @unstable should be marked stable before 1.40 release
 */
class LanguageVariantConverter {
	private readonly Title $pageTitle;
	/**
	 * Page language override from the Content-Language header.
	 */
	private ?Language $pageLanguageOverride = null;

	/** Hook for unit testing to supply mock for ParserOptions. */
	private ?ParserOptions $parserOptionsForTest = null;

	public function __construct(
		private readonly OutputTransformPipeline $languageConverterPipeline,
		private readonly LanguageFactory $languageFactory,
		private readonly SiteConfig $siteConfig,
		TitleFactory $titleFactory,
		private readonly PageIdentity $pageIdentity,
	) {
		$this->pageTitle = $titleFactory->newFromPageIdentity( $pageIdentity );
	}

	/**
	 * Set the page content language override.
	 *
	 * @param Bcp47Code $language
	 * @return void
	 */
	public function setPageLanguageOverride( Bcp47Code $language ) {
		$this->pageLanguageOverride = $this->languageFactory->getLanguage(
			$language
		);
	}

	/**
	 * Perform variant conversion on a HtmlPageBundle object.
	 *
	 * @param HtmlPageBundle $pageBundle
	 * @param Bcp47Code $targetVariant
	 * @param ?Bcp47Code $sourceVariant
	 *
	 * @return HtmlPageBundle The converted HtmlPageBundle, or the object passed in as
	 *         $pageBundle if the conversion is not supported.
	 * @throws HttpException
	 */
	public function convertPageBundleVariant(
		HtmlPageBundle $pageBundle,
		Bcp47Code $targetVariant,
		?Bcp47Code $sourceVariant = null
	): HtmlPageBundle {
		$parserOutput = PageBundleParserOutputConverter::parserOutputFromPageBundle(
			$pageBundle, title: $this->pageIdentity,
			siteConfig: $this->siteConfig
		);
		$modifiedParserOutput = $this->convertParserOutputVariant(
			$parserOutput, $targetVariant, $sourceVariant
		);
		return PageBundleParserOutputConverter::htmlPageBundleFromParserOutput(
			$modifiedParserOutput,
			siteConfig: $this->siteConfig,
			bodyOnly: false,
		);
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
		if ( !$parserOutput->getContentHolder()->isParsoidContent() ) {
			// Do not convert non-Parsoid HTML (it should be preconverted)
			return $parserOutput;
		}
		$targetVariant = $this->languageFactory->getLanguage( $targetVariant );
		$popts = $this->parserOptionsForTest ?? ParserOptions::newFromAnon();
		$popts->setVariant( $targetVariant );
		$popts->setUseParsoid( true );
		$parent = fn ( $l ) => $l === null ? null :
			$this->languageFactory->getParentLanguage( $l ) ??
			$this->languageFactory->getLanguage( $l );
		$popts->setTargetLanguage(
			$parent( $sourceVariant ) ??
			$parent( $this->pageLanguageOverride ) ??
			$parent( $parserOutput->getLanguage() ) ??
			$this->pageTitle->getPageLanguage()
		);
		// TEMPORARY during transition to new LanguageConverter
		// Ensure we can distinguish ParserOutputs created using the
		// older language converter implementation.
		$parserOutput->setExtensionData(
			'core:parsoid-languageconverter', 'postprocess'
		);
		return $this->languageConverterPipeline->run(
			$parserOutput, $popts, []
		);
	}
}
