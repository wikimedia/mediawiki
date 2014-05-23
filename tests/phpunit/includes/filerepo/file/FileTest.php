<?php

class FooFile extends File {
};

class FileTest extends MediaWikiTestCase {
	/**
	 * @covers File::getThumbnailBucket
	 */
	public function testGetThumbnailBucket() {
		$this->setMwGlobals( 'wgThumbnailBuckets', array( 256, 512, 1024, 2048, 4096 ) );

		$stub = $this->getMock( 'FooFile', array( 'getWidth' ), array( 'Foo', false ) );

		$stub->expects( $this->any() )->method( 'getWidth' )->will( $this->returnValue( 3000 ) );

		$this->assertEquals( 256, $stub->getThumbnailBucket( 120 ) );
		$this->assertEquals( 512, $stub->getThumbnailBucket( 300 ) );
		$this->assertEquals( 2048, $stub->getThumbnailBucket( 1024 ) );
		$this->assertEquals( false, $stub->getThumbnailBucket( 2048 ) );
		$this->assertEquals( false, $stub->getThumbnailBucket( 3500 ) );

		$this->setMwGlobals( 'wgThumbnailBuckets', null );

		$this->assertEquals( false, $stub->getThumbnailBucket( 1024 ) );
	}
}