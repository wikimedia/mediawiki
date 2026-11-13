<?php

namespace MediaWiki\Tests\Rest\Handler;

use Exception;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\Edit\ParsoidRenderID;
use MediaWiki\Edit\SimpleParsoidOutputStash;
use MediaWiki\Page\WikiPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\RequestData;
use MediaWikiTestCaseTrait;
use Wikimedia\ObjectCache\HashBagOStuff;

/**
 * This trait is used in PageHTMLHandlerTest.php & RevisionHTMLHandlerTest.php
 * to construct requests and perform stashing for the Parsoid Output stash feature.
 *
 * @extends \PHPUnit\Framework\TestCase
 */
trait HTMLHandlerTestTrait {

	use MediaWikiTestCaseTrait;
	use HandlerTestTrait;

	/** @var ParsoidOutputStash|null */
	private $parsoidOutputStash = null;

	private function getParsoidOutputStash(): ParsoidOutputStash {
		if ( !$this->parsoidOutputStash ) {
			$jsonCodec = $this->getServiceContainer()->getJsonCodec();
			$this->parsoidOutputStash = new SimpleParsoidOutputStash( $jsonCodec, new HashBagOStuff(), 120 );
		}
		return $this->parsoidOutputStash;
	}

	/**
	 * @param WikiPage $page
	 * @param array $queryParams
	 * @param array $config
	 *
	 * @return array
	 * @throws Exception
	 */
	private function executePageHTMLRequest(
		WikiPage $page,
		array $queryParams = [],
		array $config = [],
		?Authority $authority = null
	): array {
		$handler = $this->newHandler();
		$request = new RequestData( [
			'pathParams' => [ 'title' => $page->getTitle()->getPrefixedDBkey() ],
			'queryParams' => $queryParams,
		] );
		$result = $this->executeHandler(
			$handler,
			$request,
			$config + [ 'format' => 'html' ],
			[],
			[],
			[],
			$authority
		);
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
	 * @throws Exception
	 */
	private function executeRevisionHTMLRequest(
		int $revId,
		array $queryParams = [],
		array $config = [],
		?Authority $authority = null
	): array {
		$handler = $this->newHandler();
		$request = new RequestData( [
			'pathParams' => [ 'id' => $revId ],
			'queryParams' => $queryParams,
		] );
		$result = $this->executeHandler(
			$handler,
			$request,
			$config + [ 'format' => 'html' ],
			[],
			[],
			[],
			$authority
		);
		$etag = $result->getHeaderLine( 'ETag' );
		$stashKey = ParsoidRenderID::newFromETag( $etag );

		return [ $result->getBody()->getContents(), $etag, $stashKey ];
	}
}
