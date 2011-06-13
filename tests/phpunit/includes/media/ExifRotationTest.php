<?php

/**
 * @group Broken
 */
class ExifRotationTest extends MediaWikiTestCase {

	function setUp() {
	}

	/**
	 *
	 * @dataProvider providerFiles
	 */
	function testMetadata( $name, $type, $info ) {
		$handler = new BitmapHandler();

		$file = UnregisteredLocalFile::newFromPath( dirname( __FILE__ ) . '/' . $name, $type );
		$this->assertEquals( $file->getWidth(), $info['width'], "$name: width check" );
		$this->assertEquals( $file->getHeight(), $info['height'], "$name: height check" );
	}

	function providerFiles() {
		return array(
			array(
				'landscape-plain.jpg',
				'image/jpeg',
				array(
					'width' => 1024,
					'height' => 768,
				)
			),
			array(
				'portrait-rotated.jpg',
				'image/jpeg',
				array(
					'width' => 768, // as rotated
					'height' => 1024, // as rotated
				)
			)
		);
	}
}

