<?php
class GIFHandlerTest extends MediaWikiTestCase {

	public function setUp() {
		$this->filePath = __DIR__ .  '/../../data/media';
		$this->backend = new FSFileBackend( array(
			'name'           => 'localtesting',
			'lockManager'    => 'nullLockManager',
			'containerPaths' => array( 'data' => $this->filePath )
		) );
		$this->repo = new FSRepo( array(
			'name'    => 'temp',
			'url'     => 'http://localhost/thumbtest',
			'backend' => $this->backend
		) );
		$this->handler = new GIFHandler();
	}

	public function testInvalidFile() {
		$res = $this->handler->getMetadata( null, $this->filePath . '/README' );
		$this->assertEquals( GIFHandler::BROKEN_FILE, $res );
	}
	/**
	 * @param $filename String basename of the file to check
	 * @param $expected boolean Expected result.
	 * @dataProvider dataIsAnimated
	 */
	public function testIsAnimanted( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/gif' );
		$actual = $this->handler->isAnimatedImage( $file );
		$this->assertEquals( $expected, $actual );
	}
	public function dataIsAnimated() {
		return array(
			array( 'animated.gif', true ),
			array( 'nonanimated.gif', false ),
		);
	}

	/**
	 * @param $filename String
	 * @param $expected Integer Total image area
	 * @dataProvider dataGetImageArea
	 */
	public function testGetImageArea( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/gif' );
		$actual = $this->handler->getImageArea( $file, $file->getWidth(), $file->getHeight() );
		$this->assertEquals( $expected, $actual );
	}
	public function dataGetImageArea() {
		return array(
			array( 'animated.gif', 5400 ),
			array( 'nonanimated.gif', 1350 ),
		);
	}

	/**
	 * @param $metadata String Serialized metadata
	 * @param $expected Integer One of the class constants of GIFHandler
	 * @dataProvider dataIsMetadataValid
	 */
	public function testIsMetadataValid( $metadata, $expected ) {
		$actual = $this->handler->isMetadataValid( null, $metadata );
		$this->assertEquals( $expected, $actual );
	}
	public function dataIsMetadataValid() {
		return array(
			array( GIFHandler::BROKEN_FILE, GIFHandler::METADATA_GOOD ),
			array( '', GIFHandler::METADATA_BAD ),
			array( null, GIFHandler::METADATA_BAD ),
			array( 'Something invalid!', GIFHandler::METADATA_BAD ),
			array( 'a:4:{s:10:"frameCount";i:1;s:6:"looped";b:0;s:8:"duration";d:0.1000000000000000055511151231257827021181583404541015625;s:8:"metadata";a:2:{s:14:"GIFFileComment";a:1:{i:0;s:35:"GIF test file ⁕ Created with GIMP";}s:15:"_MW_GIF_VERSION";i:1;}}', GIFHandler::METADATA_GOOD ),
		);
	}

	/**
	 * @param $filename String
	 * @param $expected String Serialized array
	 * @dataProvider dataGetMetadata
	 */
	public function testGetMetadata( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/gif' );
		$actual = $this->handler->getMetadata( $file, "$this->filePath/$filename" );
		$this->assertEquals( unserialize( $expected ), unserialize( $actual ) );
	}

	public function dataGetMetadata() {
		return array(
			array( 'nonanimated.gif', 'a:4:{s:10:"frameCount";i:1;s:6:"looped";b:0;s:8:"duration";d:0.1000000000000000055511151231257827021181583404541015625;s:8:"metadata";a:2:{s:14:"GIFFileComment";a:1:{i:0;s:35:"GIF test file ⁕ Created with GIMP";}s:15:"_MW_GIF_VERSION";i:1;}}' ),
			array( 'animated-xmp.gif', 'a:4:{s:10:"frameCount";i:4;s:6:"looped";b:1;s:8:"duration";d:2.399999999999999911182158029987476766109466552734375;s:8:"metadata";a:5:{s:6:"Artist";s:7:"Bawolff";s:16:"ImageDescription";a:2:{s:9:"x-default";s:18:"A file to test GIF";s:5:"_type";s:4:"lang";}s:15:"SublocationDest";s:13:"The interwebs";s:14:"GIFFileComment";a:1:{i:0;s:16:"GIƒ·test·file";}s:15:"_MW_GIF_VERSION";i:1;}}' ),
		);
	}

	private function dataFile( $name, $type ) {
		return new UnregisteredLocalFile( false, $this->repo,
			"mwstore://localtesting/data/$name", $type );
	}
}
