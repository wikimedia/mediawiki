<?php
declare( strict_types = 1 );

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\Parsoid\Config\DataAccess;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Parsoid;

/**
 * ParserFactory which uses a ParsoidParser.
 *
 * This is similar to \ParserFactory, but simplified since we don't need
 * to try to reuse parser objects.  Eventually we'll be able to simplify
 * \ParserFactory the same way.
 *
 * @since 1.41
 * @internal May be combined with \ParserFactory or otherwise refactored
 *
 * @ingroup Parser
 *
 * @note Eventually this may extend \ParserFactory
 */
class ParsoidParserFactory {
	public function __construct(
		private readonly SiteConfig $siteConfig,
		private readonly DataAccess $dataAccess,
		private readonly PageConfigFactory $pageConfigFactory,
		private readonly LanguageConverterFactory $languageConverterFactory,
		private readonly ParserFactory $legacyParserFactory,
	) {
	}

	/**
	 * Creates a new Parsoid parser.
	 * @return ParsoidParser
	 * @since 1.41
	 * @unstable
	 */
	public function create(): ParsoidParser {
		return new ParsoidParser(
			new Parsoid( $this->siteConfig, $this->dataAccess ),
			$this->pageConfigFactory,
			$this->languageConverterFactory,
			$this->dataAccess
		);
	}
}
