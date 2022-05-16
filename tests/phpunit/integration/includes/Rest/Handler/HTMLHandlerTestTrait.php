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
			$this->parsoidOutputStash = new SimpleParsoidOutputStash( new HashBagOStuff() );
		}
		return $this->parsoidOutputStash;
	}

	/**
	 * @param string $page
	 * @param array $queryParams
	 *
	 * @return array
	 */
	private function executePageHTMLRequest( string $page, array $queryParams = [] ): array {
		$handler = $this->newHandler();
		$request = new RequestData( [
			'pathParams' => [ 'title' => $page ],
			'queryParams' => $queryParams,
		] );
		$result = $this->executeHandler( $handler,
			$request,
			[ 'format' => 'html' ] );
		$etag = $result->getHeaderLine( 'ETag' );
		$stashKey = ParsoidRenderID::newFromETag( $etag );

		return [ $result->getBody()->getContents(), $etag, $stashKey ];
	}

	/**
	 * @param int $revId
	 * @param array $queryParams
	 *
	 * @return array
	 */
	private function executeRevisionHTMLRequest( int $revId, array $queryParams = [] ): array {
		$handler = $this->newHandler();
		$request = new RequestData( [
			'pathParams' => [ 'id' => $revId ],
			'queryParams' => $queryParams,
		] );
		$result = $this->executeHandler( $handler,
			$request,
			[ 'format' => 'html' ] );
		$etag = $result->getHeaderLine( 'ETag' );
		$stashKey = ParsoidRenderID::newFromETag( $etag );

		return [ $result->getBody()->getContents(), $etag, $stashKey ];
	}
}
