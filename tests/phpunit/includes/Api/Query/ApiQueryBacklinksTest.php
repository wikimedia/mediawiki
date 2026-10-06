<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiQuery;
use MediaWiki\Api\ApiQueryBacklinks;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Api\ApiTestCase;

/**
 * @group API
 * @covers \MediaWiki\Api\ApiQueryBacklinks
 */
class ApiQueryBacklinksTest extends ApiTestCase {

	private function newBacklinksModule(): ApiQueryBacklinks {
		$main = new ApiMain( new FauxRequest( [] ) );
		/** @var ApiQuery $query */
		$query = $main->getModuleManager()->getModule( 'query' );
		return $query->getModuleManager()->getModule( 'backlinks' );
	}

	public function testGetRestHelper_declaresListResponseSchema() {
		$schema = $this->newBacklinksModule()->getRestHelper()->getResponseBodySchema();

		// The response is a page of items: find the schema of the "items" array.
		$itemsSchema = null;
		foreach ( $schema['allOf'] as $fragment ) {
			$itemsSchema ??= $fragment['properties']['items'] ?? null;
		}
		$this->assertNotNull( $itemsSchema, 'Response schema describes "items"' );
		$this->assertSame( 'array', $itemsSchema['type'] );

		// Each item identifies a linking page. This is the essential assertion:
		// the item schema was injected into the list envelope.
		$this->assertSame( [ 'pageid', 'ns', 'title' ], $itemsSchema['items']['required'] );
		$this->assertSame( 'integer', $itemsSchema['items']['properties']['pageid']['type'] );
		$this->assertSame( 'integer', $itemsSchema['items']['properties']['ns']['type'] );
		$this->assertSame( 'string', $itemsSchema['items']['properties']['title']['type'] );
	}
}
