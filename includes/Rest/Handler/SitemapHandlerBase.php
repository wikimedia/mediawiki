<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Config\Config;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\SitemapGenerator;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HeaderParser\HttpDate;
use MediaWiki\Rest\StringStream;
use Wikimedia\Timestamp\ConvertibleTimestamp;

abstract class SitemapHandlerBase extends Handler {
	protected bool $enabled;
	/** @var float|int */
	protected $indexSize;
	/** @var float|int */
	protected $sitemapSize;
	/** @var float|int */
	protected $expiry;

	protected function __construct(
		Config $config,
		protected LanguageConverterFactory $languageConverterFactory,
		protected Language $contLang,
		private PermissionManager $permissionManager,
	) {
		$apiConf = $config->get( MainConfigNames::SitemapApiConfig );
		$this->enabled = $apiConf['enabled'] ?? false;
		$this->indexSize = $apiConf['sitemapsPerIndex'] ?? 30_000;
		$variants = SitemapGenerator::getVariants( $contLang, $languageConverterFactory );
		$this->sitemapSize = ( $apiConf['pagesPerSitemap'] ?? 30_000 ) / ( count( $variants ) + 1 );
		$this->expiry = $apiConf['expiry'] ?? 3600;
	}

	protected function getOffset( $indexId, $fileId ) {
		return $this->sitemapSize * ( $indexId * $this->indexSize + $fileId );
	}

	public function execute() {
		if ( !$this->enabled ) {
			return $this->getResponseFactory()
				->createHttpError( 404, [ 'message' => 'Disabled by configuration' ] );
		}
		if ( !$this->permissionManager->isEveryoneAllowed( 'read' ) ) {
			return $this->getResponseFactory()
				->createHttpError( 403, [ 'message' => 'This site has restricted access' ] );
		}
		$xml = $this->getXml();
		$response = $this->getResponseFactory()->create();
		$response->setHeader( 'Content-Type', 'application/xml; charset=utf-8' );
		$response->setHeader(
			'Expires',
			HttpDate::format(
				( $this->getLastModified() ?? ConvertibleTimestamp::time() ) + $this->expiry
			)
		);
		$response->setHeader( 'Cache-Control', 'public' );
		$response->setBody( new StringStream( $xml ) );
		return $response;
	}

	abstract protected function getXml();
}
