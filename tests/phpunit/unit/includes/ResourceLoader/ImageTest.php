<?php

use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\DerivativeContext;
use MediaWiki\ResourceLoader\Image;
use MediaWiki\Tests\ResourceLoader\ImageModuleTest;

/**
 * @group ResourceLoader
 * @covers \MediaWiki\ResourceLoader\Image
 */
class ImageTest extends MediaWikiUnitTestCase {
	private const IMAGES_PATH = __DIR__ . '/../../../data/resourceloader';

	protected function getTestImage( $name ) {
		$options = ImageModuleTest::COMMON_IMAGE_DATA[$name];
		$fileDescriptor = is_string( $options ) ? $options : $options['file'];
		$allowedVariants = ( is_array( $options ) && isset( $options['variants'] ) ) ?
			$options['variants'] : [];
		$variants = array_fill_keys( $allowedVariants, [ 'color' => 'red' ] );
		return new ResourceLoaderImageTestable(
			$name,
			'test',
			$fileDescriptor,
			self::IMAGES_PATH,
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

		$image = $this->getTestImage( $imageName );
		$context = new DerivativeContext(
			$this->createMock( Context::class )
		);
		$context->setLanguage( $languageCode );
		$context->setDirection( $dirMap[$languageCode] );

		$this->assertEquals( $image->getPath( $context ), self::IMAGES_PATH . '/' . $path );
	}

	public function testGetExtension() {
		$image = $this->getTestImage( 'def' );
		$this->assertSame( 'svg', $image->getExtension() );
		$this->assertSame( 'svg', $image->getExtension( 'original' ) );
		$this->assertSame( 'png', $image->getExtension( 'rasterized' ) );
		$image = $this->getTestImage( 'abc' );
		$this->assertSame( 'gif', $image->getExtension() );
		$this->assertSame( 'gif', $image->getExtension( 'original' ) );
		$this->assertSame( 'gif', $image->getExtension( 'rasterized' ) );
	}

	public function testGetImageData() {
		$context = $this->createMock( Context::class );

		$image = $this->getTestImage( 'def' );
		$data = file_get_contents( self::IMAGES_PATH . '/def.svg' );
		$dataConstructive = file_get_contents( self::IMAGES_PATH . '/def_variantize.svg' );
		$this->assertEquals( $image->getImageData( $context, null, 'original' ), $data );
		$this->assertEquals(
			$image->getImageData( $context, 'destructive', 'original' ),
			$dataConstructive
		);
		// Stub, since we don't know if we even have a SVG handler, much less what exactly it'll output
		$this->assertSame( 'RASTERIZESTUB', $image->getImageData( $context, null, 'rasterized' ) );

		$image = $this->getTestImage( 'abc' );
		$data = file_get_contents( self::IMAGES_PATH . '/abc.gif' );
		$this->assertEquals( $image->getImageData( $context, null, 'original' ), $data );
		$this->assertEquals( $image->getImageData( $context, null, 'rasterized' ), $data );
	}

	public function testMassageSvgPathdata() {
		$image = $this->getTestImage( 'ghi' );
		$data = file_get_contents( self::IMAGES_PATH . '/ghi.svg' );
		$dataMassaged = file_get_contents( self::IMAGES_PATH . '/ghi_massage.svg' );
		$this->assertEquals( $image->massageSvgPathdata( $data ), $dataMassaged );
	}
}

class ResourceLoaderImageTestable extends Image {
	private const MOCK_FALLBACKS = [
		'en-gb' => [ 'en' ],
		'de-formal' => [ 'de' ],
	];

	// Make some protected methods public
	public function massageSvgPathdata( $svg ) {
		return parent::massageSvgPathdata( $svg );
	}

	// Stub, since we don't know if we even have a SVG handler, much less what exactly it'll output
	public function rasterize( $svg ) {
		return 'RASTERIZESTUB';
	}

	protected function getLangFallbacks( string $code ): array {
		return self::MOCK_FALLBACKS[$code] ?? [];
	}
}
