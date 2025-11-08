<?php

use MediaWiki\MainConfigNames;

/**
 * @group Media
 * @covers \DjVuHandler
 */
class DjVuTest extends MediaWikiMediaTestCase {

	/**
	 * @var DjVuHandler
	 */
	protected $handler;

	protected function setUp(): void {
		parent::setUp();

		// cli tool setup
		$djvuSupport = new DjVuSupport();

		if ( !$djvuSupport->isEnabled() ) {
			$this->markTestSkipped(
			'This test needs the installation of the ddjvu, djvutoxml and djvudump tools' );
		}

		$this->overrideConfigValue( MainConfigNames::DjvuUseBoxedCommand, true );

		$this->handler = new DjVuHandler();
	}

	public function testGetSizeAndMetadata() {
		$info = $this->handler->getSizeAndMetadata(
			new TrivialMediaHandlerState, $this->filePath . '/LoremIpsum.djvu' );
		$this->assertSame( 2480, $info['width'] );
		$this->assertSame( 3508, $info['height'] );
		$this->assertIsArray( $info['metadata']['data'] );
	}

	public function testInvalidFile() {
		$this->assertEquals(
			[ 'metadata' => [ 'error' => 'Error extracting metadata' ] ],
			$this->handler->getSizeAndMetadata(
				new TrivialMediaHandlerState, $this->filePath . '/some-nonexistent-file' ),
			'Getting metadata for a nonexistent file should return false'
		);
	}

	public function testPageCount() {
		$file = $this->dataFile( 'LoremIpsum.djvu', 'image/x.djvu' );
		$this->assertEquals(
			5,
			$this->handler->pageCount( $file ),
			'Test file LoremIpsum.djvu should be detected as containing 5 pages'
		);
	}

	public function testGetPageDimensions() {
		$file = $this->dataFile( 'LoremIpsum.djvu', 'image/x.djvu' );
		$this->assertSame(
			[ 'width' => 2480, 'height' => 3508 ],
			$this->handler->getPageDimensions( $file, 1 ),
			'Page 1 of test file LoremIpsum.djvu should have a size of 2480 * 3508'
		);
	}

	public function testGetPageText() {
		$file = $this->dataFile( 'LoremIpsum.djvu', 'image/x.djvu' );
		$this->assertSame(
			// note: this also tests that the column/paragraph is detected and converted
			"Lorem ipsum \n\n1 \n",
			$this->handler->getPageText( $file, 1 ),
			"Text layer of page 1 of file LoremIpsum.djvu should be 'Lorem ipsum \n\n1 \n'"
		);
	}
}
