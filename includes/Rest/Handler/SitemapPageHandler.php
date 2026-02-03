<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Cache\GenderCache;
use MediaWiki\Config\Config;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Page\SitemapGenerator;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Generate a single sitemap XML page
 */
class SitemapPageHandler extends SitemapHandlerBase {
	private const CACHE_VERSION = 1;

	private Config $config;
	private ?array $data = null;

	public function __construct(
		Config $config,
		LanguageConverterFactory $languageConverterFactory,
		Language $contLang,
		PermissionManager $permissionManager,
		private IConnectionProvider $connectionProvider,
		private GenderCache $genderCache,
		private WANObjectCache $wanCache,
	) {
		parent::__construct(
			$config,
			$languageConverterFactory,
			$contLang,
			$permissionManager,
		);
		$this->config = $config;
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
			'pageId' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-sitemap-page-id' ),
			],
			'include_namespace' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'namespace',
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-sitemap-include-namespace' ),
			],
		];
	}

	/** @inheritDoc */
	protected function getLastModified() {
		return $this->getData()['timestamp'];
	}

	protected function getXml(): string {
		return $this->getData()['xml'];
	}

	/**
	 * Get the UNIX timestamp of generation and the XML
	 * @return array Associative array:
	 *   - xml: The XML
	 *   - timestamp: The UNIX timestamp
	 */
	private function getData() {
		if ( $this->data === null ) {
			$params = $this->getValidatedParams();
			$startId = $this->getOffset( $params['indexId'], $params['pageId'] );
			$endId = $startId + $this->sitemapSize;
			$namespaces = $params['include_namespace'];
			$namespacesStr = $namespaces ? implode( ',', $namespaces ) : '';
			$this->data = $this->wanCache->getWithSetCallback(
				$this->wanCache->makeKey( 'sitemap', $startId, $endId, $namespacesStr ),
				$this->expiry,
				function () use ( $startId, $endId, $namespaces ) {
					$generator = new SitemapGenerator(
						$this->contLang,
						$this->languageConverterFactory,
						$this->genderCache
					);
					$xml = $generator
						->namespacesFromConfig( $this->config )
						->additionalNamespaces( $namespaces )
						->idRange( $startId, $endId )
						->getXml( $this->connectionProvider->getReplicaDatabase() );
					return [
						'xml' => $xml,
						'timestamp' => ConvertibleTimestamp::time()
					];
				},
				[
					'segmentable' => true,
					'version' => self::CACHE_VERSION,
				]
			);
		}
		return $this->data;
	}

	protected function getResponseSchema(): array {
		return [
			'type' => 'object',
			'xml' => [
				'name' => 'urlset',
				'namespace' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
			],
			'properties' => [
				'url' => [
					'type' => 'array',
					'xml' => [ 'wrapped' => false ],
					'items' => [
						'type' => 'object',
						'xml' => [ 'name' => 'url' ],
						'properties' => [
							'loc' => [
								'type' => 'string',
								'format' => 'uri',
								'xml' => [ 'name' => 'loc' ],
								'description' => 'URL of the article',
							],
							'lastmod' => [
								'type' => 'string',
								'format' => 'date-time',
								'xml' => [ 'name' => 'lastmod' ],
								'description' => 'Last modification date in ISO 8601 format',
							],
						],
					],
				],
			],
		];
	}

	protected function getResponseExample(): string {
		return '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://example.org/wiki/Main_Page</loc>
    <lastmod>2024-01-15T10:30:00Z</lastmod>
  </url>
  <url>
    <loc>https://example.org/wiki/Another_Page</loc>
    <lastmod>2024-01-14T08:20:00Z</lastmod>
  </url>
</urlset>';
	}
}
