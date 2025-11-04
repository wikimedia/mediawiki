<?php

/**
 * @group Search
 * @covers \SearchIndexFieldDefinition
 */
class SearchIndexFieldTest extends \MediaWikiUnitTestCase {

	public static function provideMergeCases() {
		return [
			[ 0, 'test', 0, 'test', true ],
			[ SearchIndexField::INDEX_TYPE_NESTED, 'test',
				SearchIndexField::INDEX_TYPE_NESTED, 'test', false ],
			[ 0, 'test', 0, 'test2', true ],
			[ 0, 'test', 1, 'test', false ],
		];
	}

	/**
	 * @dataProvider provideMergeCases
	 * @param int $t1
	 * @param string $n1
	 * @param int $t2
	 * @param string $n2
	 * @param bool $result
	 */
	public function testMerge( $t1, $n1, $t2, $n2, $result ) {
		$field1 =
			$this->getMockBuilder( SearchIndexFieldDefinition::class )
				->onlyMethods( [ 'getMapping' ] )
				->setConstructorArgs( [ $n1, $t1 ] )
				->getMock();
		$field2 =
			$this->getMockBuilder( SearchIndexFieldDefinition::class )
				->onlyMethods( [ 'getMapping' ] )
				->setConstructorArgs( [ $n2, $t2 ] )
				->getMock();

		if ( $result ) {
			$this->assertNotFalse( $field1->merge( $field2 ) );
		} else {
			$this->assertFalse( $field1->merge( $field2 ) );
		}

		$field1->setFlag( 0xFF );
		$this->assertFalse( $field1->merge( $field2 ) );

		$field1->setMergeCallback(
			static function ( $a, $b ) {
				return "test";
			}
		);
		$this->assertEquals( "test", $field1->merge( $field2 ) );
	}

}
