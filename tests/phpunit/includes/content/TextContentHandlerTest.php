<?php

/**
 * @group ContentHandler
 */
class TextContentHandlerTest extends MediaWikiLangTestCase {
	public function addDBDataOnce() {
		$this->insertPage( 'Not_Main_Page', 'This is not a main page' );
		$this->insertPage( 'Smithee', 'A smithee is one who smiths. See also [[Alan Smithee]]' );
	}

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

		$mockEngine = $this->getMock( 'SearchEngine' );

		$mockEngine->expects( $this->atLeastOnce() )
			->method( 'makeSearchFieldMapping' )
			->willReturnCallback( function ( $name, $type ) {
				$mockField =
					$this->getMockBuilder( 'SearchIndexFieldDefinition' )
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
		 * @var $mockEngine SearchEngine
		 */
		$fields = $handler->getFieldsForSearchIndex( $mockEngine );
		$mappedFields = [];
		foreach ( $fields as $name => $field ) {
			$this->assertInstanceOf( 'SearchIndexField', $field );
			/**
			 * @var $field SearchIndexField
			 */
			$mappedFields[$name] = $field->getMapping( $mockEngine );
		}
		$this->assertArrayHasKey( 'language', $mappedFields );
		$this->assertEquals( 'test', $mappedFields['language']['testData'] );
		$this->assertEquals( 'language', $mappedFields['language']['name'] );
	}

	/**
	 * @covers ContentHandler::getDataForSearchIndex
	 */
	public function testDataIndexFields() {
		$mockEngine = $this->getMock( 'SearchEngine' );
		$title = Title::newFromText( 'Not_Main_Page', NS_MAIN );
		$page = new WikiPage( $title );
		var_dump($page->getContentModel(), $page->getContentHandler());

		$this->setTemporaryHook( 'SearchDataForIndex',
			function ( &$fields, ContentHandler $handler, WikiPage $page, ParserOutput $output,
			           SearchEngine $engine ) {
				$fields['testDataField'] = 'test content';
			} );


		$output = $page->getContent()->getParserOutput( $title );
		$data = $page->getContentHandler()->getDataForSearchIndex( $page, $output, $mockEngine );
		$this->assertArrayHasKey( 'text', $data );
		$this->assertArrayHasKey( 'text_bytes', $data );
		$this->assertArrayHasKey( 'language', $data );
		$this->assertArrayHasKey( 'testDataField', $data );
		$this->assertEquals( 'test content', $data['testDataField'] );
	}

	/**
	 * @covers ContentHandler::getParserOutputForIndexing
	 */
	public function testParserOutputForIndexing() {
		$title = Title::newFromText( 'Smithee', NS_MAIN );
		$page = new WikiPage( $title );

		$out = $page->getContentHandler()->getParserOutputForIndexing( $page );
		$this->assertInstanceOf( ParserOutput::class, $out );
		$this->assertContains( 'one who smiths', $out->getRawText() );
	}
}
