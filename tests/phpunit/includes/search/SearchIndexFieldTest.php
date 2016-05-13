<?php

/**
 * @group Search
 * @covers SearchIndexFieldDefinition
 */
class SearchIndexFieldTest extends MediaWikiTestCase {

	public function getMergeCases() {
		return [
			[ 0, 'test', 0, 'test', true ],
			[ SearchIndexField::INDEX_TYPE_NESTED, 'test',
			  SearchIndexField::INDEX_TYPE_NESTED, 'test', false ],
			[ 0, 'test', 0, 'test2', true ],
			[ 0, 'test', 1, 'test', false ],
		];
	}

	/**
	 * @dataProvider getMergeCases
	 */
	public function testMerge( $t1, $n1, $t2, $n2, $result ) {
		$field1 = $this->getMockBuilder( 'SearchIndexFieldDefinition' )
			->setMethods( [ 'getMapping' ] )
			->setConstructorArgs( [ $n1, $t1 ] )->getMock();
		$field2 = $this->getMockBuilder( 'SearchIndexFieldDefinition' )
			->setMethods( [ 'getMapping' ] )
			->setConstructorArgs( [ $n2, $t2 ] )->getMock();

		if ( $result ) {
			$this->assertNotFalse( $field1->merge( $field2 ) );
		} else {
			$this->assertFalse( $field1->merge( $field2 ) );
		}

		$field1->setFlag( 0xFF );
		$this->assertFalse( $field1->merge( $field2 ) );
	}
}
