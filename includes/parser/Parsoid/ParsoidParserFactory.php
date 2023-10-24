<?php

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use ParserFactory;
use Wikimedia\Parsoid\Config\DataAccess;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\UUID\GlobalIdGenerator;

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
 * @file
 * @ingroup Parser
 */
class ParsoidParserFactory /* eventually this may extend \ParserFactory */ {
	private SiteConfig $siteConfig;
	private DataAccess $dataAccess;
	private PageConfigFactory $pageConfigFactory;
	private LanguageConverterFactory $languageConverterFactory;
	private ParserFactory $legacyParserFactory;
	private GlobalIdGenerator $globalIdGenerator;

	/**
	 * @param SiteConfig $siteConfig
	 * @param DataAccess $dataAccess
	 * @param PageConfigFactory $pageConfigFactory
	 * @param LanguageConverterFactory $languageConverterFactory
	 * @param ParserFactory $legacyParserFactory
	 */
	public function __construct(
		SiteConfig $siteConfig,
		DataAccess $dataAccess,
		PageConfigFactory $pageConfigFactory,
		LanguageConverterFactory $languageConverterFactory,
		ParserFactory $legacyParserFactory,
		GlobalIdGenerator $globalIdGenerator
	) {
		$this->siteConfig = $siteConfig;
		$this->dataAccess = $dataAccess;
		$this->pageConfigFactory = $pageConfigFactory;
		$this->languageConverterFactory = $languageConverterFactory;
		$this->legacyParserFactory = $legacyParserFactory;
		$this->globalIdGenerator = $globalIdGenerator;
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
			$this->legacyParserFactory,
			$this->globalIdGenerator
		);
	}
}
