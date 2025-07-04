<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Cache\GenderCache;
use MediaWiki\Config\Config;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Page\SitemapGenerator;
use MediaWiki\Permissions\PermissionManager;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Generate a single sitemap XML file
 */
class SitemapFileHandler extends SitemapHandlerBase {
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
			],
			'fileId' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}

	/** @inheritDoc */
	protected function getLastModified() {
		return $this->getData()['timestamp'];
	}

	/** @inheritDoc */
	protected function getXml() {
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
			$startId = $this->getOffset( $params['indexId'], $params['fileId'] );
			$endId = $startId + $this->sitemapSize;
			$this->data = $this->wanCache->getWithSetCallback(
				$this->wanCache->makeKey( 'sitemap', $startId, $endId ),
				$this->expiry,
				function () use ( $startId, $endId ) {
					$generator = new SitemapGenerator(
						$this->contLang,
						$this->languageConverterFactory,
						$this->genderCache
					);
					$xml = $generator
						->namespacesFromConfig( $this->config )
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
}
