<?php

class SquidUpdatesTest extends MediaWikiTestCase {
	public function testPurgeMergeWeb() {
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$urls1 = array();
		$title = Title::newMainPage();
		$urls1[] = $title->getCanonicalURL( '?x=1' );
		$urls1[] = $title->getCanonicalURL( '?x=2' );
		$urls1[] = $title->getCanonicalURL( '?x=3' );
		$update1 = new SquidUpdate( $urls1 );
		DeferredUpdates::addUpdate( $update1 );

		$urls2 = array();
		$urls2[] = $title->getCanonicalURL( '?x=2' );
		$urls2[] = $title->getCanonicalURL( '?x=3' );
		$urls2[] = $title->getCanonicalURL( '?x=4' );
		$update2 = new SquidUpdate( $urls2 );
		DeferredUpdates::addUpdate( $update2 );

		$wrapper = TestingAccessWrapper::newFromObject( $update1 );
		$this->assertEquals( array_merge( $urls1, $urls2 ), $wrapper->urls );
	}
}
