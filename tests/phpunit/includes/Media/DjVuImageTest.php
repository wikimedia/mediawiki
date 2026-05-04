<?php

namespace MediaWiki\Tests\Media;

use MediaWiki\MainConfigNames;
use MediaWiki\Media\DjVuImage;
use MediaWiki\Tests\Common\Parser\DjVuSupport;
use MediaWikiMediaTestCase;

/**
 * @group Media
 * @covers \MediaWiki\Media\DjVuImage
 */
class DjVuImageTest extends MediaWikiMediaTestCase {

	private const string FILE_NAME = __DIR__ . '/../../data/media/LoremIpsum.djvu';

	private function getFile( bool $use ): DjVuImage {
		$this->overrideConfigValue( MainConfigNames::DjvuUseBoxedCommand, $use );
		return new DjVuImage( self::FILE_NAME );
	}

	protected function setUp(): void {
		parent::setUp();

		// cli tool setup
		$djvuSupport = new DjVuSupport();

		if ( !$djvuSupport->isEnabled() ) {
			$this->markTestSkipped(
				'This test needs the installation of the ddjvu, djvutxt and djvudump tools'
			);
		}
	}

	/** @dataProvider provideDjvuUseBoxedCommand */
	public function testIsValid( bool $use ) {
		$this->assertTrue( $this->getFile( $use )->isValid() );
	}

	public static function provideDjvuUseBoxedCommand() {
		return [
			[ true ],
			[ false ],
		];
	}

	/** @dataProvider provideDjvuUseBoxedCommand */
	public function testRetrieveMetadata( bool $use ) {
		$data = $this->getFile( $use )->retrieveMetadata();
		$this->assertNotEquals( [], $data );
	}

	/** @dataProvider provideDjvuUseBoxedCommand */
	public function testGetImageSize( bool $use ) {
		$data = $this->getFile( $use )->getImageSize();
		$this->assertNotEquals( [], $data );
	}
}
