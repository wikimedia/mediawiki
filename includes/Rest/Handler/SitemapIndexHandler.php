<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Config\Config;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler;
use Wikimedia\Message\MessageValue;
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
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-sitemap-index-id' ),
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

	protected function getResponseSchema(): array {
		return [
			'type' => 'object',
			'xml' => [
				'name' => 'sitemapindex',
				'namespace' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
			],
			'properties' => [
				'sitemap' => [
					'type' => 'array',
					'xml' => [ 'wrapped' => false ],
					'items' => [
						'type' => 'object',
						'xml' => [ 'name' => 'sitemap' ],
						'properties' => [
							'loc' => [
								'type' => 'string',
								'format' => 'uri',
								'xml' => [ 'name' => 'loc' ],
								'description' => 'URL of a sitemap page',
							],
						],
					],
				],
			],
		];
	}

	protected function getResponseExample(): string {
		return '<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap><loc>https://example.org/sitemap/0/page/0</loc></sitemap>
  <sitemap><loc>https://example.org/sitemap/0/page/1</loc></sitemap>
</sitemapindex>';
	}
}
