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
		$allowedVariants = is_array( $options ) && isset( $options['variants'] ) ? $options['variants'] : array();
		$variants = array_fill_keys( $allowedVariants, array( 'color' => 'red' ) );
		return new ResourceLoaderImageTestable( $name, 'test', $fileDescriptor, $this->imagesPath, $variants );
	}

	public static function provideGetPath() {
		return array(
			array( 'add', 'en', 'add.gif' ),
			array( 'add', 'he', 'add.gif' ),
			array( 'remove', 'en', 'remove.svg' ),
			array( 'remove', 'he', 'remove.svg' ),
			array( 'next', 'en', 'next.svg' ),
			array( 'next', 'he', 'prev.svg' ),
			array( 'help', 'en', 'help-ltr.svg' ),
			array( 'help', 'ar', 'help-rtl.svg' ),
			array( 'help', 'he', 'help-ltr.svg' ),
			array( 'bold', 'en', 'bold-b.svg' ),
			array( 'bold', 'de', 'bold-f.svg' ),
			array( 'bold', 'ar', 'bold-f.svg' ),
			array( 'bold', 'fr', 'bold-a.svg' ),
			array( 'bold', 'he', 'bold-a.svg' ),
		);
	}

	/**
	 * @covers ResourceLoaderImage::getPath
	 * @dataProvider provideGetPath
	 */
	public function testGetPath( $imageName, $languageCode, $path ) {
		static $dirMap = array(
			'en' => 'ltr',
			'de' => 'ltr',
			'fr' => 'ltr',
			'he' => 'rtl',
			'ar' => 'rtl',
		);
		static $contexts = array();

		$image = $this->getTestImage( $imageName );
		$context = $this->getResourceLoaderContext( $languageCode, $dirMap[$languageCode] );

		$this->assertEquals( $image->getPath( $context ), $this->imagesPath . '/' . $path );
	}

	/**
	 * @covers ResourceLoaderImage::getExtension
	 * @covers ResourceLoaderImage::getMimeType
	 */
	public function testGetExtension() {
		$image = $this->getTestImage( 'remove' );
		$this->assertEquals( $image->getExtension(), 'svg' );
		$this->assertEquals( $image->getExtension( 'original' ), 'svg' );
		$this->assertEquals( $image->getExtension( 'rasterized' ), 'png' );
		$image = $this->getTestImage( 'add' );
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
		$context = $this->getResourceLoaderContext( 'en', 'ltr' );

		$image = $this->getTestImage( 'remove' );
		$data = file_get_contents( $this->imagesPath . '/remove.svg' );
		$dataConstructive = file_get_contents( $this->imagesPath . '/remove_variantize.svg' );
		$this->assertEquals( $image->getImageData( $context, null, 'original' ), $data );
		$this->assertEquals( $image->getImageData( $context, 'destructive', 'original' ), $dataConstructive );
		// Stub, since we don't know if we even have a SVG handler, much less what exactly it'll output
		$this->assertEquals( $image->getImageData( $context, null, 'rasterized' ), 'RASTERIZESTUB' );

		$image = $this->getTestImage( 'add' );
		$data = file_get_contents( $this->imagesPath . '/add.gif' );
		$this->assertEquals( $image->getImageData( $context, null, 'original' ), $data );
		$this->assertEquals( $image->getImageData( $context, null, 'rasterized' ), $data );
	}

	/**
	 * @covers ResourceLoaderImage::massageSvgPathdata
	 */
	public function testMassageSvgPathdata() {
		$image = $this->getTestImage( 'next' );
		$data = file_get_contents( $this->imagesPath . '/next.svg' );
		$dataMassaged = file_get_contents( $this->imagesPath . '/next_massage.svg' );
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
