<?php

use MediaWiki\Revision\MutableRevisionRecord;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group Search
 * @covers FauxSearchResultSet
 */
class FauxSearchResultTest extends MediaWikiUnitTestCase {

	public function testConstruct() {
		$title = $this->getTitleMock( 'Foo' );
		$r = new FauxSearchResult( $title );
		$this->assertSame( $title, $r->getTitle() );
		$this->assertTrue( $r->isMissingRevision() );
		$this->assertNull( $r->getFile() );
		$this->assertSame( 0, $r->getWordCount() );

		$title = $this->getTitleMock( 'Foo', 1 );
		$rev = new MutableRevisionRecord( $title );
		$rev->setTimestamp( '20000101000000' );
		$r = new FauxSearchResult( $title, $rev );
		$this->assertFalse( $r->isMissingRevision() );
		$this->assertSame( '20000101000000', $r->getTimestamp() );

		$title = $this->getTitleMock( 'Foo', 1 );
		$rev = new MutableRevisionRecord( $title );
		$r = new FauxSearchResult( $title, $rev, null, '123' );
		$this->assertSame( 3, $r->getByteSize() );

		$title = $this->getTitleMock( 'Foo' );
		$file = $this->getFileMock( 'Foo.png' );
		$r = new FauxSearchResult( $title, null, $file );
		$this->assertSame( $file, $r->getFile() );
	}

	/**
	 * @param $titleText
	 * @param int|null $articleId
	 * @return Title|MockObject
	 */
	private function getTitleMock( $titleText, $articleId = null ) {
		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getPrefixedText', 'getArticleID' ] )
			->getMock();
		$title->method( 'getPrefixedText' )->willReturn( $titleText );
		if ( $articleId ) {
			$title->method( 'getArticleID' )->willReturn( $articleId );
		} else {
			$title->expects( $this->never() )->method( 'getArticleID' );
		}
		return $title;
	}

	/**
	 * @param string $filename
	 * @return File|MockObject
	 */
	private function getFileMock( $filename ) {
		$title = $this->getMockBuilder( File::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getName' ] )
			->getMock();
		$title->method( 'getName' )
			->willReturn( $filename );
		return $title;
	}

}
