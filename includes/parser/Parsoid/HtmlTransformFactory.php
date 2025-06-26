<?php
declare( strict_types = 1 );

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWiki\Title\TitleFactory;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Parsoid;

/**
 * @since 1.40
 * @unstable should be marked stable before 1.40 release
 */
class HtmlTransformFactory {
	private Parsoid $parsoid;
	private array $parsoidSettings;
	private PageConfigFactory $configFactory;
	private IContentHandlerFactory $contentHandlerFactory;
	private SiteConfig $siteConfig;
	private TitleFactory $titleFactory;
	private LanguageConverterFactory $languageConverterFactory;
	private LanguageFactory $languageFactory;

	/**
	 * @param Parsoid $parsoid
	 * @param array $parsoidSettings
	 * @param PageConfigFactory $configFactory
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param SiteConfig $siteConfig
	 * @param TitleFactory $titleFactory
	 * @param LanguageConverterFactory $languageConverterFactory
	 * @param LanguageFactory $languageFactory
	 */
	public function __construct(
		Parsoid $parsoid,
		array $parsoidSettings,
		PageConfigFactory $configFactory,
		IContentHandlerFactory $contentHandlerFactory,
		SiteConfig $siteConfig,
		TitleFactory $titleFactory,
		LanguageConverterFactory $languageConverterFactory,
		LanguageFactory $languageFactory
	) {
		$this->parsoid = $parsoid;
		$this->parsoidSettings = $parsoidSettings;
		$this->configFactory = $configFactory;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->siteConfig = $siteConfig;
		$this->titleFactory = $titleFactory;
		$this->languageConverterFactory = $languageConverterFactory;
		$this->languageFactory = $languageFactory;
	}

	/**
	 * Get the HTML transform object for a given page and specified
	 * modified HTML.
	 *
	 * @param string $modifiedHTML
	 * @param PageIdentity $page
	 *
	 * @return HtmlToContentTransform
	 */
	public function getHtmlToContentTransform( string $modifiedHTML, PageIdentity $page ) {
		return new HtmlToContentTransform(
			$modifiedHTML,
			$page,
			$this->parsoid,
			$this->parsoidSettings,
			$this->configFactory,
			$this->contentHandlerFactory
		);
	}

	/**
	 * Get a language variant converter object for a given page
	 *
	 * @param PageIdentity $page
	 *
	 * @return LanguageVariantConverter
	 */
	public function getLanguageVariantConverter( PageIdentity $page ): LanguageVariantConverter {
		return new LanguageVariantConverter(
			$page,
			$this->configFactory,
			$this->parsoid,
			$this->siteConfig,
			$this->titleFactory,
			$this->languageConverterFactory,
			$this->languageFactory
		);
	}

}
