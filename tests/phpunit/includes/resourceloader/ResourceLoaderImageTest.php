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
		$allowedVariants = is_array( $options ) &&
			isset( $options['variants'] ) ? $options['variants'] : [];
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
			[ 'add', 'en', 'add.gif' ],
			[ 'add', 'he', 'add.gif' ],
			[ 'remove', 'en', 'remove.svg' ],
			[ 'remove', 'he', 'remove.svg' ],
			[ 'next', 'en', 'next.svg' ],
			[ 'next', 'he', 'prev.svg' ],
			[ 'help', 'en', 'help-ltr.svg' ],
			[ 'help', 'ar', 'help-rtl.svg' ],
			[ 'help', 'he', 'help-ltr.svg' ],
			[ 'bold', 'en', 'bold-b.svg' ],
			[ 'bold', 'de', 'bold-f.svg' ],
			[ 'bold', 'ar', 'bold-f.svg' ],
			[ 'bold', 'fr', 'bold-a.svg' ],
			[ 'bold', 'he', 'bold-a.svg' ],
		];
	}

	/**
	 * @covers ResourceLoaderImage::getPath
	 * @dataProvider provideGetPath
	 */
	public function testGetPath( $imageName, $languageCode, $path ) {
		static $dirMap = [
			'en' => 'ltr',
			'de' => 'ltr',
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
		$context = $this->getResourceLoaderContext();

		$image = $this->getTestImage( 'remove' );
		$data = file_get_contents( $this->imagesPath . '/remove.svg' );
		$dataConstructive = file_get_contents( $this->imagesPath . '/remove_variantize.svg' );
		$this->assertEquals( $image->getImageData( $context, null, 'original' ), $data );
		$this->assertEquals(
			$image->getImageData( $context, 'destructive', 'original' ),
			$dataConstructive
		);
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
