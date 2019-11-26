<?php

use Wikimedia\AtEase\AtEase;

/**
 * @group Database
 * @group Cache
 * @covers LinkBatch
 */
class LinkBatchTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers LinkBatch::__construct()
	 * @covers LinkBatch::getSize()
	 * @covers LinkBatch::isEmpty()
	 */
	public function testConstructEmpty() {
		$batch = new LinkBatch();

		$this->assertTrue( $batch->isEmpty() );
		$this->assertSame( 0, $batch->getSize() );
	}

	/**
	 * @covers LinkBatch::__construct()
	 * @covers LinkBatch::getSize()
	 * @covers LinkBatch::isEmpty()
	 */
	public function testConstruct() {
		$batch = new LinkBatch( [
			new TitleValue( NS_MAIN, 'Foo' ),
			new TitleValue( NS_TALK, 'Bar' ),
		] );

		$this->assertFalse( $batch->isEmpty() );
		$this->assertSame( 2, $batch->getSize() );
	}

	/**
	 * @covers LinkBatch::addObj()
	 * @covers LinkBatch::getSize()
	 */
	public function testAddObj() {
		$batch = new LinkBatch( [
			new TitleValue( NS_MAIN, 'Foo' )
		] );

		$batch->addObj( new TitleValue( NS_TALK, 'Bar' ) );
		$batch->addObj( new TitleValue( NS_MAIN, 'Foo' ) );

		$this->assertSame( 2, $batch->getSize() );
	}

	/**
	 * @covers LinkBatch::add()
	 * @covers LinkBatch::getSize()
	 */
	public function testAdd() {
		$batch = new LinkBatch( [
			new TitleValue( NS_MAIN, 'Foo' )
		] );

		$batch->add( NS_TALK, 'Bar' );
		$batch->add( NS_MAIN, 'Foo' );

		$this->assertSame( 2, $batch->getSize() );
	}

	public function testExecute() {
		$existing1 = $this->getExistingTestPage( __METHOD__ . '1' )->getTitle();
		$existing2 = $this->getExistingTestPage( __METHOD__ . '2' )->getTitle();
		$nonexisting1 = $this->getNonexistingTestPage( __METHOD__ . 'x' )->getTitle();
		$nonexisting2 = $this->getNonexistingTestPage( __METHOD__ . 'y' )->getTitle();

		$cache = $this->getMockBuilder( LinkCache::class )
			->disableOriginalConstructor()
			->getMock();

		$good = [];
		$bad = [];

		$cache->expects( $this->exactly( 2 ) )
			->method( 'addGoodLinkObjFromRow' )
			->willReturnCallback( function ( TitleValue $title, $row ) use ( &$good ) {
				$good["$title"] = $title;
			} );

		$cache->expects( $this->exactly( 2 ) )
			->method( 'addBadLinkObj' )
			->willReturnCallback( function ( TitleValue $title ) use ( &$bad ) {
				$bad["$title"] = $title;
			} );

		$this->setService( 'LinkCache', $cache );

		$batch = new LinkBatch();

		$batch->addObj( $existing1 );
		$batch->addObj( $existing2 );
		$batch->addObj( $nonexisting1 );
		$batch->addObj( $nonexisting2 );

		// Bad stuff, should be skipped!
		$batch->add( NS_MAIN, '_X' );
		$batch->add( NS_MAIN, 'X_' );
		$batch->add( NS_MAIN, '' );

		AtEase::suppressWarnings();
		$batch->execute();
		AtEase::restoreWarnings();

		$this->assertArrayHasKey( $existing1->getTitleValue()->__toString(), $good );
		$this->assertArrayHasKey( $existing2->getTitleValue()->__toString(), $good );

		$this->assertArrayHasKey( $nonexisting1->getTitleValue()->__toString(), $bad );
		$this->assertArrayHasKey( $nonexisting2->getTitleValue()->__toString(), $bad );
	}

}
