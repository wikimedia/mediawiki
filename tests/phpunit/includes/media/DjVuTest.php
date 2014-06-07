<?php
/**
 * @covers DjVuHandler
 */
class DjVuTest extends MediaWikiTestCase {

	/**
	 * @var string the directory where test files are
	 */
	protected $filePath;

	/**
	 * @var FSRepo the repository to use
	 */
	protected $repo;

	/**
	 * @var DjVuHandler
	 */
	protected $handler;

	protected function setUp() {
		global $wgDjvuRenderer, $wgDjvuDump, $wgDjvuToXML;
		parent::setUp();

		//cli tool setup
		$wgDjvuRenderer = $wgDjvuRenderer ? $wgDjvuRenderer : '/usr/bin/ddjvu';
		$wgDjvuDump = $wgDjvuDump ? $wgDjvuDump : '/usr/bin/djvudump';
		$wgDjvuToXML = $wgDjvuToXML ? $wgDjvuToXML : '/usr/bin/djvutoxml';
		if (
			!$this->checkIfToolExists( $wgDjvuRenderer ) ||
			!$this->checkIfToolExists( $wgDjvuDump ) ||
			!$this->checkIfToolExists( $wgDjvuToXML )
		) {
			$this->markTestSkipped( 'This test needs the installation of the ddjvu, djvutoxml and djvudump tools' );
		}

		//file repo setup
		$this->filePath = __DIR__ . '/../../data/media/';
		$backend = new FSFileBackend( array(
			'name' => 'localtesting',
			'wikiId' => wfWikiId(),
			'lockManager' => new NullLockManager( array() ),
			'containerPaths' => array( 'data' => $this->filePath )
		) );
		$this->repo = new FSRepo( array(
			'name' => 'temp',
			'url' => 'http://localhost/thumbtest',
			'backend' => $backend
		) );

		$this->handler = new DjVuHandler();
	}

	/**
	 * Check if a tool exist
	 *
	 * @param string $path path to the tool
	 * @return bool
	 */
	protected function checkIfToolExists( $path ) {
		wfSuppressWarnings();
		$result = file_exists( $path );
		wfRestoreWarnings();
		return $result;
	}

	protected function dataFile( $name, $type ) {
		return new UnregisteredLocalFile(
			false,
			$this->repo,
			'mwstore://localtesting/data/' . $name,
			$type
		);
	}

	public function testGetImageSize() {
		$this->assertArrayEquals(
			array( 2480, 3508, 'DjVu', 'width="2480" height="3508"' ),
			$this->handler->getImageSize( null, $this->filePath . '/LoremIpsum.djvu' ),
			'Test file LoremIpsum.djvu should have a size of 2480 * 3508'
		);
	}

	public function testInvalidFile() {
		$this->assertFalse(
			$this->handler->getMetadata( null, $this->filePath . '/README' ),
			'Getting Metadata for an inexistent file should returns false'
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
		$this->assertArrayEquals(
			array( 2480, 3508 ),
			$this->handler->getPageDimensions( $file, 1 ),
			'Page 1 of test file LoremIpsum.djvu should have a size of 2480 * 3508'
		);
	}

	public function testGetPageText() {
		$file = $this->dataFile( 'LoremIpsum.djvu', 'image/x.djvu' );
		$this->assertEquals(
			"Lorem ipsum \n1 \n",
			(string) $this->handler->getPageText( $file, 1 ),
			"Text layer of page 1 of file LoremIpsum.djvu should be 'Lorem ipsum \n1 \n'"
		);
	}
}
