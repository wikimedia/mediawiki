<?php

use MediaWiki\FileRepo\File\File;
use MediaWiki\Revision\MutableRevisionRecord;

/**
 * @group Search
 * @covers \FauxSearchResultSet
 */
class FauxSearchResultTest extends MediaWikiUnitTestCase {
	use MockTitleTrait;

	public function testConstruct() {
		$title = $this->makeMockTitle( 'Foo', [ 'id' => 0 ] );
		$r = new FauxSearchResult( $title );
		$this->assertSame( $title, $r->getTitle() );
		$this->assertTrue( $r->isMissingRevision() );
		$this->assertNull( $r->getFile() );
		$this->assertSame( 0, $r->getWordCount() );

		$title = $this->makeMockTitle( 'Foo', [ 'id' => 1 ] );
		$rev = new MutableRevisionRecord( $title );
		$rev->setTimestamp( '20000101000000' );
		$r = new FauxSearchResult( $title, $rev );
		$this->assertFalse( $r->isMissingRevision() );
		$this->assertSame( '20000101000000', $r->getTimestamp() );

		$title = $this->makeMockTitle( 'Foo', [ 'id' => 1 ] );
		$rev = new MutableRevisionRecord( $title );
		$r = new FauxSearchResult( $title, $rev, null, '123' );
		$this->assertSame( 3, $r->getByteSize() );

		$title = $this->makeMockTitle( 'Foo', [ 'id' => 0 ] );
		$file = $this->getFileMock( 'Foo.png' );
		$r = new FauxSearchResult( $title, null, $file );
		$this->assertSame( $file, $r->getFile() );
	}

	/**
	 * @param string $filename
	 * @return File
	 */
	private function getFileMock( $filename ) {
		$title = $this->getMockBuilder( File::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getName' ] )
			->getMock();
		$title->method( 'getName' )
			->willReturn( $filename );
		return $title;
	}

}
