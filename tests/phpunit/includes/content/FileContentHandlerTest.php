<?php

/**
 * @group ContentHandler
 */
class FileContentHandlerTest extends MediaWikiLangTestCase {
	/**
	 * @var FileContentHandler
	 */
	private $handler;

	protected function setUp() {
		parent::setUp();

		$this->handler = new FileContentHandler();
	}

	public function testIndexMapping() {
		$mockEngine = $this->createMock( 'SearchEngine' );

		$mockEngine->expects( $this->atLeastOnce() )
			->method( 'makeSearchFieldMapping' )
			->willReturnCallback( function ( $name, $type ) {
				$mockField =
					$this->getMockBuilder( 'SearchIndexFieldDefinition' )
						->setMethods( [ 'getMapping' ] )
						->setConstructorArgs( [ $name, $type ] )
						->getMock();
				return $mockField;
			} );

		$map = $this->handler->getFieldsForSearchIndex( $mockEngine );
		$expect = [
			'file_media_type' => 1,
			'file_mime' => 1,
			'file_size' => 1,
			'file_width' => 1,
			'file_height' => 1,
			'file_bits' => 1,
			'file_resolution' => 1,
			'file_text' => 1,
		];
		foreach ( $map as $name => $field ) {
			$this->assertInstanceOf( 'SearchIndexField', $field );
			$this->assertEquals( $name, $field->getName() );
			unset( $expect[$name] );
		}
		$this->assertEmpty( $expect );
	}
}
