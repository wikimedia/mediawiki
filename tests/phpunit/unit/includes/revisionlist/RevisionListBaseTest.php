<?php

use MediaWiki\RevisionList\RevisionItemBase;
use MediaWiki\RevisionList\RevisionListBase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\RevisionList\RevisionListBase
 *
 * @author DannyS712
 */
class RevisionListBaseTest extends MediaWikiUnitTestCase {

	public function testGetType() {
		$revisionListBase = $this->getMockBuilder( RevisionListBase::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$this->assertNull( $revisionListBase->getType() );
	}

	public function testReset() {
		$revisionListBase = $this->getMockBuilder( RevisionListBase::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$revisionItemBase = $this->getMockBuilder( RevisionItemBase::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		// Actual contents aren't used
		$fakeRow = (object)[ 'key' => 'val' ];

		$resultWrapper = $this->createMock( IResultWrapper::class );
		$resultWrapper->expects( $this->once() )
			->method( 'rewind' );
		$resultWrapper->expects( $this->once() )
			->method( 'current' )
			->willReturn( $fakeRow );

		$revisionListBase->expects( $this->once() )
			->method( 'newItem' )
			->with( $fakeRow )
			->willReturn( $revisionItemBase );

		// res is normally the result of a db query that uses IReadableDatabase::select and cannot
		// be tested in a unit test
		$mockAccess = TestingAccessWrapper::newFromObject( $revisionListBase );
		$mockAccess->res = $resultWrapper;

		$this->assertSame( $revisionItemBase, $revisionListBase->reset() );
		$this->assertSame( $revisionItemBase, $revisionListBase->current() );
	}

	public function testResultWrapper() {
		// Test methods that only depend on the result wrapper
		$revisionListBase = $this->getMockBuilder( RevisionListBase::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$this->assertSame( 0, $revisionListBase->key() );
		$this->assertFalse( $revisionListBase->valid() );
		$this->assertSame( 0, $revisionListBase->length() );

		$resultWrapper = $this->createMock( IResultWrapper::class );
		$resultWrapper->expects( $this->once() )
			->method( 'key' )
			->willReturn( 991 );
		$resultWrapper->expects( $this->once() )
			->method( 'valid' )
			->willReturn( true );
		$resultWrapper->expects( $this->once() )
			->method( 'numRows' )
			->willReturn( 457 );

		// res is normally the result of a db query that uses IReadableDatabase::select and cannot
		// be tested in a unit test
		$mockAccess = TestingAccessWrapper::newFromObject( $revisionListBase );
		$mockAccess->res = $resultWrapper;

		$this->assertSame( 991, $revisionListBase->key() );
		$this->assertTrue( $revisionListBase->valid() );
		$this->assertSame( 457, $revisionListBase->length() );
	}

}
