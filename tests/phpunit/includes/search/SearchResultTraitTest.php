<?php

class SearchResultTraitTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers SearchResultTrait::getExtensionData
	 * @covers SearchResultTrait::setExtensionData
	 */
	public function testExtensionData() {
		$result = new class() {
			use SearchResultTrait;
		};
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
	 * @covers SearchResultTrait::getExtensionData
	 * @covers SearchResultTrait::setExtensionData
	 */
	public function testExtensionDataArrayBC() {
		$result = new class() {
			use SearchResultTrait;
		};
		$data = [ 'hello' => 'world' ];
		$this->hideDeprecated( 'SearchResultTrait::setExtensionData with array argument' );
		$this->assertEquals( [], $result->getExtensionData(), 'starts empty' );
		$result->setExtensionData( $data );
		$this->assertEquals( $data, $result->getExtensionData(), 'can set extension data' );
		$data['this'] = 'that';
		$this->assertNotEquals( $data, $result->getExtensionData(), 'shouldnt hold any reference' );

		$result->setExtensionData( $data );
		$this->assertEquals( $data, $result->getExtensionData(), 'can replace extension data' );
	}
}
