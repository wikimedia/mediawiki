<?php

/**
 * @group ContentHandler
 */
class TextContentHandlerTest extends MediaWikiLangTestCase {
	/**
	 * @covers TextContentHandler::supportsDirectEditing
	 */
	public function testSupportsDirectEditing() {
		$handler = new TextContentHandler();
		$this->assertTrue( $handler->supportsDirectEditing(), 'direct editing is supported' );
	}

	/**
	 * @covers SearchEngine::makeSearchFieldMapping
	 * @covers ContentHandler::getFieldsForSearchIndex
	 */
	public function testFieldsForIndex() {
		$handler = new TextContentHandler();

		$mockEngine = $this->createMock( SearchEngine::class );

		$mockEngine->expects( $this->atLeastOnce() )
			->method( 'makeSearchFieldMapping' )
			->willReturnCallback( function ( $name, $type ) {
				$mockField =
					$this->getMockBuilder( SearchIndexFieldDefinition::class )
						->setConstructorArgs( [ $name, $type ] )
						->getMock();
				$mockField->expects( $this->atLeastOnce() )->method( 'getMapping' )->willReturn( [
						'testData' => 'test',
						'name' => $name,
						'type' => $type,
					] );
				return $mockField;
			} );

		/**
		 * @var SearchEngine $mockEngine
		 */
		$fields = $handler->getFieldsForSearchIndex( $mockEngine );
		$mappedFields = [];
		foreach ( $fields as $name => $field ) {
			$this->assertInstanceOf( SearchIndexField::class, $field );
			/**
			 * @var SearchIndexField $field
			 */
			$mappedFields[$name] = $field->getMapping( $mockEngine );
		}
		$this->assertArrayHasKey( 'language', $mappedFields );
		$this->assertEquals( 'test', $mappedFields['language']['testData'] );
		$this->assertEquals( 'language', $mappedFields['language']['name'] );
	}
}
