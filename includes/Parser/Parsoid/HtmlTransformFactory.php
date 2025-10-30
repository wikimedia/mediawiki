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
	public function __construct(
		private readonly Parsoid $parsoid,
		private readonly array $parsoidSettings,
		private readonly PageConfigFactory $configFactory,
		private readonly IContentHandlerFactory $contentHandlerFactory,
		private readonly SiteConfig $siteConfig,
		private readonly TitleFactory $titleFactory,
		private readonly LanguageConverterFactory $languageConverterFactory,
		private readonly LanguageFactory $languageFactory,
	) {
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
