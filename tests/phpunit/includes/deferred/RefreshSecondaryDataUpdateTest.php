<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\DerivedPageDataUpdater;
use MediaWiki\Storage\MutableRevisionRecord;
use Psr\Log\NullLogger;
use Wikimedia\ScopedCallback;

/**
 * @covers RefreshSecondaryDataUpdate
 */
class RefreshSecondaryDataUpdateTest extends MediaWikiIntegrationTestCase {
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

	public function testEnqueueOnFailure() {
		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$queue = JobQueueGroup::singleton()->get( 'refreshLinksPrioritized' );
		$user = $this->getTestUser()->getUser();

		// T248189: DeferredUpdate will log the exception, don't fail because of that.
		$this->setLogger( 'exception', new NullLogger() );

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

	/**
	 * Attempted use of onTransactionResolution() to avoid an update running on
	 * rollback shouldn't cause DeferredUpdates to fail to get a ticket.
	 */
	public function testT248003() {
		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$user = $this->getTestUser()->getUser();

		$fname = __METHOD__;
		$dbw = $lbFactory->getMainLB()->getConnectionRef( DB_MASTER );
		$dbw->setFlag( DBO_TRX, $dbw::REMEMBER_PRIOR ); // make queries trigger TRX
		$reset = new ScopedCallback( [ $dbw, 'restoreFlags' ] );

		// Sanity check
		$this->assertSame( 0, $dbw->trxLevel() );
		$dbw->selectRow( 'page', '*', '', __METHOD__ );
		if ( !$dbw->trxLevel() ) {
			$this->markTestSkipped( 'No implicit transaction, cannot test for T248003' );
		}
		$dbw->commit( __METHOD__, $dbw::FLUSHING_INTERNAL );
		$this->assertSame( 0, $dbw->trxLevel() );

		$goodCalls = 0;
		$goodUpdate = $this->getMockBuilder( DataUpdate::class )
			->setMethods( [ 'doUpdate' ] )
			->getMock();
		$goodUpdate->method( 'doUpdate' )
			->willReturnCallback( function () use ( &$goodCalls ) {
				++$goodCalls;
			} );

		$updater = $this->getMockBuilder( DerivedPageDataUpdater::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getSecondaryDataUpdates' ] )
			->getMock();
		$updater->method( 'getSecondaryDataUpdates' )
			->willReturnCallback( function () use ( $dbw, $fname, $goodUpdate ) {
				$dbw->selectRow( 'page', '*', '', $fname );
				$dbw->onTransactionResolution( function () {
				}, $fname );

				return [ $goodUpdate ];
			} );

		$wikiPage = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$update = new RefreshSecondaryDataUpdate(
			$lbFactory,
			$user,
			$wikiPage,
			$wikiPage->getRevisionRecord(),
			$updater,
			[]
		);
		$update->doUpdate();

		$this->assertSame( 1, $goodCalls );
	}

	private function runJobs() {
		// Run the job queue
		JobQueueGroup::destroySingletons();
		$jobs = new RunJobs;
		$jobs->loadParamsAndArgs( null, [ 'quiet' => true ], null );
		$jobs->execute();
	}
}
