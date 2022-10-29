<?php

namespace MediaWiki\Tests\Rest\Handler;

use HashBagOStuff;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\Edit\SimpleParsoidOutputStash;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use MediaWiki\Rest\RequestData;

/**
 * This trait is used in PageHTMLHandlerTest.php & RevisionHTMLHandlerTest.php
 * to construct requests and perform stashing for the Parsoid Output stash feature.
 */
trait HTMLHandlerTestTrait {

	private $parsoidOutputStash = null;

	private function getParsoidOutputStash(): ParsoidOutputStash {
		if ( !$this->parsoidOutputStash ) {
			$chFactory = $this->getServiceContainer()->getContentHandlerFactory();
			$this->parsoidOutputStash = new SimpleParsoidOutputStash( $chFactory, new HashBagOStuff(), 120 );
		}
		return $this->parsoidOutputStash;
	}

	/**
	 * @param string $page
	 * @param array $queryParams
	 * @param array $config
	 *
	 * @return array
	 * @throws \Exception
	 */
	private function executePageHTMLRequest(
		string $page,
		array $queryParams = [],
		array $config = []
	): array {
		$handler = $this->newHandler();
		$request = new RequestData( [
			'pathParams' => [ 'title' => $page ],
			'queryParams' => $queryParams,
		] );
		$result = $this->executeHandler( $handler,
			$request,
			$config + [ 'format' => 'html' ] );
		$etag = $result->getHeaderLine( 'ETag' );
		$stashKey = ParsoidRenderID::newFromETag( $etag );

		return [ $result->getBody()->getContents(), $etag, $stashKey ];
	}

	/**
	 * @param int $revId
	 * @param array $queryParams
	 * @param array $config
	 *
	 * @return array
	 * @throws \Exception
	 */
	private function executeRevisionHTMLRequest(
		int $revId,
		array $queryParams = [],
		array $config = []
	): array {
		$handler = $this->newHandler();
		$request = new RequestData( [
			'pathParams' => [ 'id' => $revId ],
			'queryParams' => $queryParams,
		] );
		$result = $this->executeHandler( $handler,
			$request,
			$config + [ 'format' => 'html' ] );
		$etag = $result->getHeaderLine( 'ETag' );
		$stashKey = ParsoidRenderID::newFromETag( $etag );

		return [ $result->getBody()->getContents(), $etag, $stashKey ];
	}
}
