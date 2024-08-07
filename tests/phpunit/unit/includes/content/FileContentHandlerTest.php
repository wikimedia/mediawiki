<?php

namespace MediaWiki\Tests\Unit;

use MediaWiki\Content\FileContentHandler;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Parser\MagicWordFactory;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Title\TitleFactory;
use MediaWikiUnitTestCase;
use ParserFactory;
use SearchEngine;
use SearchIndexField;
use SearchIndexFieldDefinition;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * @group ContentHandler
 *
 * @covers \MediaWiki\Content\FileContentHandler
 */
class FileContentHandlerTest extends MediaWikiUnitTestCase {
	/**
	 * @var FileContentHandler
	 */
	private $handler;

	protected function setUp(): void {
		parent::setUp();

		$this->handler = new FileContentHandler(
			CONTENT_MODEL_WIKITEXT,
			$this->createMock( TitleFactory::class ),
			$this->createMock( ParserFactory::class ),
			$this->createMock( GlobalIdGenerator::class ),
			$this->createMock( LanguageNameUtils::class ),
			$this->createMock( LinkRenderer::class ),
			$this->createMock( MagicWordFactory::class ),
			$this->createMock( ParsoidParserFactory::class )
		);
	}

	public function testIndexMapping() {
		$mockEngine = $this->createMock( SearchEngine::class );

		$mockEngine->expects( $this->atLeastOnce() )
			->method( 'makeSearchFieldMapping' )
			->willReturnCallback( function ( $name, $type ) {
				$mockField =
					$this->getMockBuilder( SearchIndexFieldDefinition::class )
						->onlyMethods( [ 'getMapping' ] )
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
			$this->assertInstanceOf( SearchIndexField::class, $field );
			$this->assertEquals( $name, $field->getName() );
			unset( $expect[$name] );
		}
		$this->assertSame( [], $expect );
	}
}
