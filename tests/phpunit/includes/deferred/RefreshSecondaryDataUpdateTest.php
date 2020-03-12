<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\DerivedPageDataUpdater;
use MediaWiki\Storage\MutableRevisionRecord;

/**
 * @covers RefreshSecondaryDataUpdate
 */
class RefreshSecondaryDataUpdateTest extends MediaWikiTestCase {
	public function testSuccess() {
		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$queue = JobQueueGroup::singleton()->get( 'refreshLinksPrioritized' );
		$user = $this->getTestUser()->getUser();

		$goodCalls = 0;
		$goodUpdate = $this->getMockBuilder( DataUpdate::class )
			->setMethods( [ 'doUpdate', 'setTransactionTicket' ] )
			->getMock();
		$goodTrxFname = get_class( $goodUpdate ) . '::doUpdate';
		$goodUpdate->method( 'doUpdate' )
			->willReturnCallback( function () use ( &$goodCalls, $lbFactory, $goodTrxFname ) {
				// Update can commit since it owns the transaction
				$lbFactory->commitMasterChanges( $goodTrxFname );
				++$goodCalls;
			} );
		$goodUpdate->expects( $this->once() )
			->method( 'setTransactionTicket' );

		$updater = $this->getMockBuilder( DerivedPageDataUpdater::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getSecondaryDataUpdates' ] )
			->getMock();
		$updater->method( 'getSecondaryDataUpdates' )
			->willReturn( [ $goodUpdate ] );

		$revision = $this->getMockBuilder( MutableRevisionRecord::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getId' ] )
			->getMock();
		$revision->method( 'getId' )
			->willReturn( 42 );

		$dbw = wfGetDB( DB_MASTER );

		$dbw->startAtomic( __METHOD__ );

		$this->assertSame( 0, $queue->getSize() );
		$this->assertSame( 0, DeferredUpdates::pendingUpdatesCount() );
		$wikiPage = WikiPage::factory( Title::newFromText( 'TestPage' ) );
		DeferredUpdates::addUpdate( new RefreshSecondaryDataUpdate(
			$lbFactory,
			$user,
			$wikiPage,
			$revision,
			$updater,
			[]
		) );
		$this->assertSame( 1, DeferredUpdates::pendingUpdatesCount() );
		$this->assertSame( 0, $queue->getSize() );

		$dbw->endAtomic( __METHOD__ ); // run updates

		$this->assertSame( 0, DeferredUpdates::pendingUpdatesCount() );
		$queue->flushCaches();
		$this->assertSame( 0, $queue->getSize(), "Nothing failed; no enqueue" );
		$this->assertSame( 1, $goodCalls );
	}

	function testEnqueueOnFailure() {
		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$queue = JobQueueGroup::singleton()->get( 'refreshLinksPrioritized' );
		$user = $this->getTestUser()->getUser();

		$goodCalls = 0;
		$goodUpdate = $this->getMockBuilder( DataUpdate::class )
			->setMethods( [ 'doUpdate', 'setTransactionTicket' ] )
			->getMock();
		$goodTrxFname = get_class( $goodUpdate ) . '::doUpdate';
		$goodUpdate->method( 'doUpdate' )
			->willReturnCallback( function () use ( &$goodCalls, $lbFactory, $goodTrxFname ) {
				// Update can commit since it owns the transaction
				$lbFactory->commitMasterChanges( $goodTrxFname );
				++$goodCalls;
			} );
		$goodUpdate->expects( $this->once() )
			->method( 'setTransactionTicket' );

		$badCalls = 0;
		$badUpdate = $this->getMockBuilder( DataUpdate::class )
			->setMethods( [ 'doUpdate', 'setTransactionTicket' ] )
			->getMock();
		$badTrxFname = get_class( $goodUpdate ) . '::doUpdate';
		$badUpdate->expects( $this->once() )
			->method( 'setTransactionTicket' );
		$badUpdate->method( 'doUpdate' )
			->willReturnCallback( function () use ( &$badCalls, $lbFactory, $badTrxFname ) {
				// Update can commit since it owns the transaction
				$lbFactory->commitMasterChanges( $badTrxFname );
				++$badCalls;
				throw new LogicException( 'We have a problem' );
			} );

		$updater = $this->getMockBuilder( DerivedPageDataUpdater::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getSecondaryDataUpdates' ] )
			->getMock();
		$updater->method( 'getSecondaryDataUpdates' )
			->willReturn( [ $goodUpdate, $badUpdate ] );

		$revision = $this->getMockBuilder( MutableRevisionRecord::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getId' ] )
			->getMock();
		$revision->method( 'getId' )
			->willReturn( 42 );

		$dbw = wfGetDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );
		$goodCalls = 0;

		$this->assertSame( 0, $queue->getSize() );
		$this->assertSame( 0, DeferredUpdates::pendingUpdatesCount() );
		$wikiPage = WikiPage::factory( Title::newFromText( 'TestPage' ) );
		DeferredUpdates::addUpdate( new RefreshSecondaryDataUpdate(
			$lbFactory,
			$user,
			$wikiPage,
			$revision,
			$updater,
			[]
		) );
		$this->assertSame( 1, DeferredUpdates::pendingUpdatesCount() );
		$this->assertSame( 0, $queue->getSize() );

		try {
			// Trigger deferred updates run to execute the update and secondary updates
			$dbw->endAtomic( __METHOD__ );
			// Callback rigged to fail
			$this->fail( "Expected LogicException" );
		} catch ( LogicException $e ) {
			$this->assertSame( "We have a problem", $e->getMessage() );
		}

		$this->assertSame( 0, DeferredUpdates::pendingUpdatesCount() );
		$queue->flushCaches();
		$this->assertSame( 1, $queue->getSize(), "Update failed; job enqueued" );
		$this->assertSame( 1, $goodCalls );
		$this->assertSame( 1, $badCalls );

		// Run the RefreshLinksJob
		$this->runJobs();

		$queue->flushCaches();
		$this->assertSame( 0, $queue->getSize() );
	}

	private function runJobs() {
		// Run the job queue
		JobQueueGroup::destroySingletons();
		$jobs = new RunJobs;
		$jobs->loadParamsAndArgs( null, [ 'quiet' => true ], null );
		$jobs->execute();
	}
}
