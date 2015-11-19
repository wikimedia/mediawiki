<?php

/**
 * @group Cache
 * @covers MessageBlobStore
 */
class MessageBlobStoreTest extends PHPUnit_Framework_TestCase {

	protected function makeBlobStore( $methods = null, $rl = null ) {
		// MediaWiki tests defaults $wgMainWANCache to CACHE_NONE.
		// Use hash instead so that caching is observed
		$wanCache = $this->getMockBuilder( 'WANObjectCache' )
			->setConstructorArgs( array( array(
				'cache' => new HashBagOStuff(),
				'pool' => 'test',
				'relayer' => new EventRelayerNull( array() )
			) ) )
			->setMethods( array( 'getHoldOffTTL' ) )
			->getMock();

		// Disable HOLDOFF_TTL as it messes with testing
		$wanCache->expects( $this->any() )
			->method( 'getHoldOffTTL' )
			->will( $this->returnValue( 0 ) );

		$blobStore = $this->getMockBuilder( 'MessageBlobStore' )
			->setConstructorArgs( array( $rl ) )
			->setMethods( $methods )
			->getMock();

		$access = TestingAccessWrapper::newFromObject( $blobStore );
		$access->wanCache = $wanCache;
		return $blobStore;
	}

	protected function makeModule( array $messages ) {
		$module = new ResourceLoaderTestModule( array( 'messages' => $messages ) );
		$module->setName( 'test.blobstore' );
		return $module;
	}

	public function testGetBlob() {
		$blobStore = $this->makeBlobStore( array( 'fetchMessage' ) );
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->returnValue( 'Example' ) );

		$module = $this->makeModule( array( 'foo' ) );
		$blob = $blobStore->getBlob( $module, 'en' );

		$this->assertEquals( '{"foo":"Example"}', $blob, 'Generated blob' );
	}

	public function testCacheHit() {
		$module = $this->makeModule( array( 'example' ) );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );

		$blobStore = $this->makeBlobStore( array( 'fetchMessage' ) );
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->onConsecutiveCalls( 'First', 'Second' ) );

		$module = $this->makeModule( array( 'example' ) );
		$blob1 = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First"}', $blob1, 'Generated blob' );

		$module = $this->makeModule( array( 'example' ) );
		$blob2 = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( $blob1, $blob2, 'Cache hit' );

	}

	public function testCacheMiss() {
		$module = $this->makeModule( array( 'example' ) );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );

		$blobStore = $this->makeBlobStore( array( 'fetchMessage' ), $rl );
		$blobStore->expects( $this->exactly( 2 ) )
			->method( 'fetchMessage' )
			->will( $this->onConsecutiveCalls( 'First', 'Second' ) );

		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First"}', $blob, 'Generated blob' );

		$blobStore->updateMessage( 'example' );

		$module = $this->makeModule( array( 'example' ) );
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"Second"}', $blob, 'Regenerated blob' );
	}
}
