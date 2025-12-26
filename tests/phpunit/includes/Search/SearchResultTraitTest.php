<?php

use MediaWiki\Search\SearchResultTrait;

class SearchResultTraitTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers \MediaWiki\Search\SearchResultTrait::getExtensionData
	 * @covers \MediaWiki\Search\SearchResultTrait::setExtensionData
	 */
	public function testExtensionData() {
		$result = new class() {
			use SearchResultTrait;
		};
		$this->assertEquals( [], $result->getExtensionData(), 'starts empty' );

		$data = [ 'hello' => 'world' ];
		$result->setExtensionData( static function () use ( &$data ) {
			return $data;
		} );
		$this->assertEquals( $data, $result->getExtensionData(), 'can set extension data' );
		$data['this'] = 'that';
		$this->assertEquals( $data, $result->getExtensionData(), 'refetches from callback' );
	}

	/**
	 * @covers \MediaWiki\Search\SearchResultTrait::getExtensionData
	 * @covers \MediaWiki\Search\SearchResultTrait::setExtensionData
	 */
	public function testExtensionDataArrayBC() {
		$result = new class() {
			use SearchResultTrait;
		};
		$data = [ 'hello' => 'world' ];
		$this->hideDeprecated( 'MediaWiki\\Search\\SearchResultTrait::setExtensionData with array argument' );
		$this->assertEquals( [], $result->getExtensionData(), 'starts empty' );
		$result->setExtensionData( $data );
		$this->assertEquals( $data, $result->getExtensionData(), 'can set extension data' );
		$data['this'] = 'that';
		$this->assertNotEquals( $data, $result->getExtensionData(), 'shouldnt hold any reference' );

		$result->setExtensionData( $data );
		$this->assertEquals( $data, $result->getExtensionData(), 'can replace extension data' );
	}
}
