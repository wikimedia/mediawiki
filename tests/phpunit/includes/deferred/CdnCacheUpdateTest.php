<?php

class CdnCacheUpdateTest extends MediaWikiTestCase {
	public function testPurgeMergeWeb() {
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$urls1 = [];
		$title = Title::newMainPage();
		$urls1[] = $title->getCanonicalURL( '?x=1' );
		$urls1[] = $title->getCanonicalURL( '?x=2' );
		$urls1[] = $title->getCanonicalURL( '?x=3' );
		$update1 = new CdnCacheUpdate( $urls1 );
		DeferredUpdates::addUpdate( $update1 );

		$urls2 = [];
		$urls2[] = $title->getCanonicalURL( '?x=2' );
		$urls2[] = $title->getCanonicalURL( '?x=3' );
		$urls2[] = $title->getCanonicalURL( '?x=4' );
		$update2 = new CdnCacheUpdate( $urls2 );
		DeferredUpdates::addUpdate( $update2 );

		$deferred = TestingAccessWrapper::newFromClass( 'DeferredUpdates' );
		$this->assertEquals( 1, count( $deferred->postSendUpdates['CdnCacheUpdate'] ),
			'CdnCacheUpdate deferred updates are merged' );

		$wrapper = TestingAccessWrapper::newFromObject( $update1 );
		$frequencies = array_count_values( $wrapper->urls );
		$dupes = array_filter( $frequencies, function( $f ) {
			return $f !== 1;
		} );
		$this->assertEquals( [], $dupes, "There must be no duplicate URLs" );
		$this->assertEquals( array_unique( array_merge( $urls1, $urls2 ) ), $wrapper->urls );

		$this->assertEquals( 0, count( $deferred->preSendUpdates ),
			'CdnCacheUpdate is not in preSendUpdates' );
		$this->assertEquals( $update1, $deferred->postSendUpdates['CdnCacheUpdate'],
			'The first update is in the deferred queue' );

	}
}
