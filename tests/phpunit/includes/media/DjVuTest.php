<?php
/**
 * @group Media
 * @covers DjVuHandler
 */
class DjVuTest extends MediaWikiMediaTestCase {

	/**
	 * @var DjVuHandler
	 */
	protected $handler;

	protected function setUp() {
		parent::setUp();

		// cli tool setup
		$djvuSupport = new DjVuSupport();

		if ( !$djvuSupport->isEnabled() ) {
			$this->markTestSkipped(
			'This test needs the installation of the ddjvu, djvutoxml and djvudump tools' );
		}

		$this->handler = new DjVuHandler();
	}

	public function testGetImageSize() {
		$this->assertSame(
			[ 2480, 3508, 'DjVu', 'width="2480" height="3508"' ],
			$this->handler->getImageSize( null, $this->filePath . '/LoremIpsum.djvu' ),
			'Test file LoremIpsum.djvu should have a size of 2480 * 3508'
		);
	}

	public function testInvalidFile() {
		$this->assertEquals(
			'a:1:{s:5:"error";s:25:"Error extracting metadata";}',
			$this->handler->getMetadata( null, $this->filePath . '/some-nonexistent-file' ),
			'Getting metadata for an inexistent file should return false'
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
		$this->assertEquals(
			"Lorem ipsum \n1 \n",
			(string)$this->handler->getPageText( $file, 1 ),
			"Text layer of page 1 of file LoremIpsum.djvu should be 'Lorem ipsum \n1 \n'"
		);
	}
}
