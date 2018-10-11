<?php

use Wikimedia\TestingAccessWrapper;

class CdnCacheUpdateTest extends MediaWikiTestCase {

	/**
	 * @covers CdnCacheUpdate::merge
	 */
	public function testPurgeMergeWeb() {
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$urls1 = [];
		$title = Title::newMainPage();
		$urls1[] = $title->getCanonicalURL( '?x=1' );
		$urls1[] = $title->getCanonicalURL( '?x=2' );
		$urls1[] = $title->getCanonicalURL( '?x=3' );
		$update1 = $this->newCdnCacheUpdate( $urls1 );
		DeferredUpdates::addUpdate( $update1 );

		$urls2 = [];
		$urls2[] = $title->getCanonicalURL( '?x=2' );
		$urls2[] = $title->getCanonicalURL( '?x=3' );
		$urls2[] = $title->getCanonicalURL( '?x=4' );
		$update2 = $this->newCdnCacheUpdate( $urls2 );
		DeferredUpdates::addUpdate( $update2 );

		$wrapper = TestingAccessWrapper::newFromObject( $update1 );
		$this->assertEquals( array_merge( $urls1, $urls2 ), $wrapper->urls );

		$update = null;
		DeferredUpdates::clearPendingUpdates();
		DeferredUpdates::addCallableUpdate( function () use ( $urls1, $urls2, &$update ) {
			$update = $this->newCdnCacheUpdate( $urls1 );
			DeferredUpdates::addUpdate( $update );
			DeferredUpdates::addUpdate( $this->newCdnCacheUpdate( $urls2 ) );
			DeferredUpdates::addUpdate(
				$this->newCdnCacheUpdate( $urls2 ), DeferredUpdates::PRESEND );
		} );
		DeferredUpdates::doUpdates();

		$wrapper = TestingAccessWrapper::newFromObject( $update );
		$this->assertEquals( array_merge( $urls1, $urls2 ), $wrapper->urls );

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
