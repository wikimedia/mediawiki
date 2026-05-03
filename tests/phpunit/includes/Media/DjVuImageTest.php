<?php

namespace MediaWiki\Tests\Media;

use MediaWiki\Media\DjVuImage;
use MediaWikiMediaTestCase;

/**
 * @group Media
 * @covers \MediaWiki\Media\DjVuImage
 */
class DjVuImageTest extends MediaWikiMediaTestCase {

	private const string FILE_NAME = __DIR__ . '/../../data/media/LoremIpsum.djvu';

	private function getFile(): DjVuImage {
		return new DjVuImage( self::FILE_NAME );
	}

	public function testIsValid() {
		$this->assertTrue( $this->getFile()->isValid() );
	}

	public function testRetrieveMetadata() {
		$data = $this->getFile()->retrieveMetadata();
		$this->assertNotEquals( [], $data );
	}

	public function testGetImageSize() {
		$data = $this->getFile()->getImageSize();
		$this->assertNotEquals( [], $data );
	}
}
