<?php
class PNGHandlerTest extends MediaWikiTestCase {

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
		$this->handler = new PNGHandler();
	}

	public function testInvalidFile() {
		$res = $this->handler->getMetadata( null, $this->filePath . '/README' );
		$this->assertEquals( PNGHandler::BROKEN_FILE, $res );
	}
	/**
	 * @param $filename String basename of the file to check
	 * @param $expected boolean Expected result.
	 * @dataProvider dataIsAnimated
	 */
	public function testIsAnimanted( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/png' );
		$actual = $this->handler->isAnimatedImage( $file );
		$this->assertEquals( $expected, $actual );
	}
	public function dataIsAnimated() {
		return array(
			array( 'Animated_PNG_example_bouncing_beach_ball.png', true ),
			array( '1bit-png.png', false ),
		);
	}

	/**
	 * @param $filename String
	 * @param $expected Integer Total image area
	 * @dataProvider dataGetImageArea
	 */
	public function testGetImageArea( $filename, $expected ) {
		$file = $this->dataFile($filename, 'image/png' );
		$actual = $this->handler->getImageArea( $file, $file->getWidth(), $file->getHeight() );
		$this->assertEquals( $expected, $actual );
	}
	public function dataGetImageArea() {
		return array(
			array( '1bit-png.png', 2500 ),
			array( 'greyscale-png.png', 2500 ),
			array( 'Png-native-test.png', 126000 ),
			array( 'Animated_PNG_example_bouncing_beach_ball.png', 10000 ),
		);
	}

	/**
	 * @param $metadata String Serialized metadata
	 * @param $expected Integer One of the class constants of PNGHandler
	 * @dataProvider dataIsMetadataValid
	 */
	public function testIsMetadataValid( $metadata, $expected ) {
		$actual = $this->handler->isMetadataValid( null, $metadata );
		$this->assertEquals( $expected, $actual );
	}
	public function dataIsMetadataValid() {
		return array(
			array( PNGHandler::BROKEN_FILE, PNGHandler::METADATA_GOOD ),
			array( '', PNGHandler::METADATA_BAD ),
			array( null, PNGHandler::METADATA_BAD ),
			array( 'Something invalid!', PNGHandler::METADATA_BAD ),
			array( 'a:6:{s:10:"frameCount";i:0;s:9:"loopCount";i:1;s:8:"duration";d:0;s:8:"bitDepth";i:8;s:9:"colorType";s:10:"truecolour";s:8:"metadata";a:1:{s:15:"_MW_PNG_VERSION";i:1;}}', PNGHandler::METADATA_GOOD ),
		);
	}

	/**
	 * @param $filename String
	 * @param $expected String Serialized array
	 * @dataProvider dataGetMetadata
	 */
	public function testGetMetadata( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/png' );
		$actual = $this->handler->getMetadata( $file, "$this->filePath/$filename" );
//		$this->assertEquals( unserialize( $expected ), unserialize( $actual ) );
		$this->assertEquals( ( $expected ), ( $actual ) );
	}
	public function dataGetMetadata() {
		return array(
			array( 'rgb-na-png.png', 'a:6:{s:10:"frameCount";i:0;s:9:"loopCount";i:1;s:8:"duration";d:0;s:8:"bitDepth";i:8;s:9:"colorType";s:10:"truecolour";s:8:"metadata";a:1:{s:15:"_MW_PNG_VERSION";i:1;}}' ),
			array( 'xmp.png', 'a:6:{s:10:"frameCount";i:0;s:9:"loopCount";i:1;s:8:"duration";d:0;s:8:"bitDepth";i:1;s:9:"colorType";s:14:"index-coloured";s:8:"metadata";a:2:{s:12:"SerialNumber";s:9:"123456789";s:15:"_MW_PNG_VERSION";i:1;}}' ), 
		);
	}

	private function dataFile( $name, $type ) {
		return new UnregisteredLocalFile( false, $this->repo,
			"mwstore://localtesting/data/$name", $type );
	}
}
