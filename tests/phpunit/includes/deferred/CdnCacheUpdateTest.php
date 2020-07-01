<?php

class CdnCacheUpdateTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers CdnCacheUpdate::merge
	 */
	public function testPurgeMergeWeb() {
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$title = Title::newMainPage();

		$urls1 = [];
		$urls1[] = $title->getCanonicalURL( '?x=1' );
		$urls1[] = $title->getCanonicalURL( '?x=2' );
		$urls1[] = $title->getCanonicalURL( '?x=3' );
		$update1 = $this->newCdnCacheUpdate( $urls1 );

		$urls2 = [];
		$urls2[] = $title->getCanonicalURL( '?x=2' );
		$urls2[] = $title->getCanonicalURL( '?x=3' );
		$urls2[] = $title->getCanonicalURL( '?x=4' );
		$urls2[] = $title;
		$update2 = $this->newCdnCacheUpdate( $urls2 );

		$expected = [
			$title->getInternalURL(),
			$title->getInternalURL( 'action=history' ),
			$title->getCanonicalURL( '?x=1' ),
			$title->getCanonicalURL( '?x=2' ),
			$title->getCanonicalURL( '?x=3' ),
			$title->getCanonicalURL( '?x=4' ),
		];
		DeferredUpdates::addUpdate( $update1 );
		DeferredUpdates::addUpdate( $update2 );

		$this->assertEquals( $expected, $update1->getUrls() );

		/** @var CdnCacheUpdate $update */
		$update = null;
		DeferredUpdates::clearPendingUpdates();
		DeferredUpdates::addCallableUpdate( function () use ( $urls1, $urls2, &$update ) {
			$update = $this->newCdnCacheUpdate( $urls1 );
			DeferredUpdates::addUpdate( $update );
			DeferredUpdates::addUpdate( $this->newCdnCacheUpdate( $urls2 ) );
			DeferredUpdates::addUpdate(
				$this->newCdnCacheUpdate( $urls2 ),
				DeferredUpdates::PRESEND
			);
		} );
		DeferredUpdates::doUpdates();

		$this->assertEquals( $expected, $update->getUrls() );

		$this->assertEquals( DeferredUpdates::pendingUpdatesCount(), 0, 'PRESEND update run' );
	}

	/**
	 * @param array $urls
	 * @return CdnCacheUpdate
	 */
	private function newCdnCacheUpdate( array $urls ) {
		return $this->getMockBuilder( CdnCacheUpdate::class )
			->setConstructorArgs( [ $urls ] )
			->setMethods( [ 'doUpdate' ] )
			->getMock();
	}
}
