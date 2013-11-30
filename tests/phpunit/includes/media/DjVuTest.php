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
		if (
			!$this->checkIfToolExists( 'ddjvu' ) ||
			!$this->checkIfToolExists( 'djvutoxml' ) ||
			!$this->checkIfToolExists( 'djvudump' )
		) {
			$this->markTestSkipped( 'This test needs the installation of the ddjvu, djvutoxml and djvudump tools' );
		}
		$wgDjvuToXML = 'djvutoxml';
		$wgDjvuDump = 'djvudump';
		$wgDjvuRenderer = 'ddjvu';

		//file repo setup
		$this->filePath = __DIR__ . '/../../data/media/';
		$backend = new FSFileBackend( array(
			'name' => 'localtesting',
			'lockManager' => 'nullLockManager',
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
	 * @param string $tool
	 * @return bool
	 */
	protected function checkIfToolExists( $tool ) {
		$resultCode = -1;
		wfShellExecWithStderr( $tool . ' --help', $resultCode );
		return $resultCode !== 127;
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
			array(  2480, 3508, 'width="2480" height="3508"', 'DjVu' ),
			$this->handler->getImageSize( null, $this->filePath . '/LoremIpsum.djvu' )
		);
	}

	public function testInvalidFile() {
		$this->assertFalse(
			$this->handler->getMetadata( null, $this->filePath . '/README' )
		);
	}

	public function testPageCount() {
		$file = $this->dataFile( 'LoremIpsum.djvu', 'image/x.djvu' );
		$this->assertEquals( 5, $this->handler->pageCount( $file ) );
	}

	public function testGetPageDimensions() {
		$file = $this->dataFile( 'LoremIpsum.djvu', 'image/x.djvu' );
		$this->assertArrayEquals( array( 2480, 3508 ), $this->handler->getPageDimensions( $file, 1 ) );
	}

	public function testGetPageText() {
		$file = $this->dataFile( 'LoremIpsum.djvu', 'image/x.djvu' );
		$this->assertEquals( 'Lorem ipsum \n1 \n', (string) $this->handler->getPageText( $file, 1 ) );
	}
}
