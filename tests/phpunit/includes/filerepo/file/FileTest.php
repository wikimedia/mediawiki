<?php

namespace FileTest {

class FooFile extends \File { };

class FileTest extends \MediaWikiTestCase {
	/**
	 * @covers File::getThumbnailBucket
	 */
	public function testGetThumbnailBucket() {
		global $wgThumbnailBuckets;

		$wgThumbnailBuckets = array( 256, 512, 1024, 2048, 4096 );

		$stub = $this->getMock( '\FileTest\FooFile', array( 'getWidth' ), array( 'Foo', false ) );

		$stub->expects( $this->any() )->method( 'getWidth' )->will( $this->returnValue( 3000 ) );

		$this->assertEquals( 256, $stub->getThumbnailBucket( 120 ) );
		$this->assertEquals( 512, $stub->getThumbnailBucket( 300 ) );
		$this->assertEquals( 1024, $stub->getThumbnailBucket( 1024 ) );
		$this->assertEquals( false, $stub->getThumbnailBucket( 3500 ) );

		$wgThumbnailBuckets = null;

		$this->assertEquals( false, $stub->getThumbnailBucket( 1024 ) );
	}
}

}