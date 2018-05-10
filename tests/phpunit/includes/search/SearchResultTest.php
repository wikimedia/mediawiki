<?php

class SearchResultTest extends MediawikiTestCase {
	/**
	 * @covers SearchResult::getExtensionData
	 * @covers SearchResult::setExtensionData
	 */
	public function testExtensionData() {
		$result = SearchResult::newFromTitle( Title::newMainPage() );
		$this->assertEquals( [], $result->getExtensionData(), 'starts empty' );

		$data = [ 'hello' => 'world' ];
		$result->setExtensionData( function () use ( &$data ) {
			return $data;
		} );
		$this->assertEquals( $data, $result->getExtensionData(), 'can set extension data' );
		$data['this'] = 'that';
		$this->assertEquals( $data, $result->getExtensionData(), 'refetches from callback' );
	}

	/**
	 * @covers SearchResult::getExtensionData
	 * @covers SearchResult::setExtensionData
	 */
	public function testExtensionDataArrayBC() {
		$result = SearchResult::newFromTitle( Title::newMainPage() );
		$data = [ 'hello' => 'world' ];
		$this->hideDeprecated( 'SearchResult::setExtensionData with array argument' );
		$this->assertEquals( [], $result->getExtensionData(), 'starts empty' );
		$result->setExtensionData( $data );
		$this->assertEquals( $data, $result->getExtensionData(), 'can set extension data' );
		$data['this'] = 'that';
		$this->assertNotEquals( $data, $result->getExtensionData(), 'shouldnt hold any reference' );

		$result->setExtensionData( $data );
		$this->assertEquals( $data, $result->getExtensionData(), 'can replace extension data' );
	}
}
