<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Config\Config;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Permissions\PermissionManager;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IConnectionProvider;

class SitemapIndexHandler extends SitemapHandlerBase {
	public function __construct(
		Config $config,
		LanguageConverterFactory $languageConverterFactory,
		Language $contLang,
		PermissionManager $permissionManager,
		private IConnectionProvider $connectionProvider,
	) {
		parent::__construct(
			$config,
			$languageConverterFactory,
			$contLang,
			$permissionManager,
		);
	}

	/** @inheritDoc */
	public function getParamSettings() {
		return [
			'indexId' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}

	protected function getXml(): string {
		$index = $this->getValidatedParams()['indexId'];
		$dbr = $this->connectionProvider->getReplicaDatabase();

		$maxPageId = (int)$dbr->newSelectQueryBuilder()
			->select( 'MAX(page_id)' )
			->from( 'page' )
			->caller( __METHOD__ )
			->fetchField();

		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" .
			"<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

		$offset = $this->getOffset( $index, 0 );
		if ( $offset <= $maxPageId ) {
			$numPages = $maxPageId - $offset;
			if ( $numPages > $this->indexSize * $this->sitemapSize ) {
				$nextUrl = $this->getRouter()->getRouteUrl( "/site/v1/sitemap/" . ( $index + 1 ) );
				$xml .= "<!-- maximum index size exceeded, make sure your robots.txt has:\n" .
					"Sitemap: $nextUrl -->\n";
				$numPages = $this->indexSize * $this->sitemapSize;
			}

			$numSitemaps = (int)ceil( $numPages / $this->sitemapSize );
			for ( $i = 0; $i < $numSitemaps; $i++ ) {
				$xml .= '<sitemap><loc>' .
					htmlspecialchars(
						$this->getRouter()->getRouteUrl( "/site/v1/sitemap/$index/page/$i" )
					) .
					"</loc></sitemap>\n";
			}
		}
		$xml .= "</sitemapindex>\n";
		return $xml;
	}
}
