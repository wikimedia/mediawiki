<?php

/**
 * @group ResourceLoader
 */
class ResourceLoaderImageTest extends ResourceLoaderTestCase {

	protected $imagesPath;

	protected function setUp() {
		parent::setUp();
		$this->imagesPath = __DIR__ . '/../../data/resourceloader';
	}

	protected function getTestImage( $name ) {
		$options = ResourceLoaderImageModuleTest::$commonImageData[$name];
		$fileDescriptor = is_string( $options ) ? $options : $options['file'];
		$allowedVariants = ( is_array( $options ) && isset( $options['variants'] ) ) ?
			$options['variants'] : [];
		$variants = array_fill_keys( $allowedVariants, [ 'color' => 'red' ] );
		return new ResourceLoaderImageTestable(
			$name,
			'test',
			$fileDescriptor,
			$this->imagesPath,
			$variants
		);
	}

	public static function provideGetPath() {
		return [
			[ 'abc', 'en', 'abc.gif' ],
			[ 'abc', 'he', 'abc.gif' ],
			[ 'def', 'en', 'def.svg' ],
			[ 'def', 'he', 'def.svg' ],
			[ 'ghi', 'en', 'ghi.svg' ],
			[ 'ghi', 'he', 'jkl.svg' ],
			[ 'mno', 'en', 'mno-ltr.svg' ],
			[ 'mno', 'ar', 'mno-rtl.svg' ],
			[ 'mno', 'he', 'mno-ltr.svg' ],
			[ 'pqr', 'en', 'pqr-b.svg' ],
			[ 'pqr', 'en-gb', 'pqr-b.svg' ],
			[ 'pqr', 'de', 'pqr-f.svg' ],
			[ 'pqr', 'de-formal', 'pqr-f.svg' ],
			[ 'pqr', 'ar', 'pqr-f.svg' ],
			[ 'pqr', 'fr', 'pqr-a.svg' ],
			[ 'pqr', 'he', 'pqr-a.svg' ],
		];
	}

	/**
	 * @covers ResourceLoaderImage::getPath
	 * @dataProvider provideGetPath
	 */
	public function testGetPath( $imageName, $languageCode, $path ) {
		static $dirMap = [
			'en' => 'ltr',
			'en-gb' => 'ltr',
			'de' => 'ltr',
			'de-formal' => 'ltr',
			'fr' => 'ltr',
			'he' => 'rtl',
			'ar' => 'rtl',
		];
		static $contexts = [];

		$image = $this->getTestImage( $imageName );
		$context = $this->getResourceLoaderContext( [
			'lang' => $languageCode,
			'dir' => $dirMap[$languageCode],
		] );

		$this->assertEquals( $image->getPath( $context ), $this->imagesPath . '/' . $path );
	}

	/**
	 * @covers ResourceLoaderImage::getExtension
	 * @covers ResourceLoaderImage::getMimeType
	 */
	public function testGetExtension() {
		$image = $this->getTestImage( 'def' );
		$this->assertEquals( $image->getExtension(), 'svg' );
		$this->assertEquals( $image->getExtension( 'original' ), 'svg' );
		$this->assertEquals( $image->getExtension( 'rasterized' ), 'png' );
		$image = $this->getTestImage( 'abc' );
		$this->assertEquals( $image->getExtension(), 'gif' );
		$this->assertEquals( $image->getExtension( 'original' ), 'gif' );
		$this->assertEquals( $image->getExtension( 'rasterized' ), 'gif' );
	}

	/**
	 * @covers ResourceLoaderImage::getImageData
	 * @covers ResourceLoaderImage::variantize
	 * @covers ResourceLoaderImage::massageSvgPathdata
	 */
	public function testGetImageData() {
		$context = $this->getResourceLoaderContext();

		$image = $this->getTestImage( 'def' );
		$data = file_get_contents( $this->imagesPath . '/def.svg' );
		$dataConstructive = file_get_contents( $this->imagesPath . '/def_variantize.svg' );
		$this->assertEquals( $image->getImageData( $context, null, 'original' ), $data );
		$this->assertEquals(
			$image->getImageData( $context, 'destructive', 'original' ),
			$dataConstructive
		);
		// Stub, since we don't know if we even have a SVG handler, much less what exactly it'll output
		$this->assertEquals( $image->getImageData( $context, null, 'rasterized' ), 'RASTERIZESTUB' );

		$image = $this->getTestImage( 'abc' );
		$data = file_get_contents( $this->imagesPath . '/abc.gif' );
		$this->assertEquals( $image->getImageData( $context, null, 'original' ), $data );
		$this->assertEquals( $image->getImageData( $context, null, 'rasterized' ), $data );
	}

	/**
	 * @covers ResourceLoaderImage::massageSvgPathdata
	 */
	public function testMassageSvgPathdata() {
		$image = $this->getTestImage( 'ghi' );
		$data = file_get_contents( $this->imagesPath . '/ghi.svg' );
		$dataMassaged = file_get_contents( $this->imagesPath . '/ghi_massage.svg' );
		$this->assertEquals( $image->massageSvgPathdata( $data ), $dataMassaged );
	}
}

class ResourceLoaderImageTestable extends ResourceLoaderImage {
	// Make some protected methods public
	public function massageSvgPathdata( $svg ) {
		return parent::massageSvgPathdata( $svg );
	}
	// Stub, since we don't know if we even have a SVG handler, much less what exactly it'll output
	public function rasterize( $svg ) {
		return 'RASTERIZESTUB';
	}
}
