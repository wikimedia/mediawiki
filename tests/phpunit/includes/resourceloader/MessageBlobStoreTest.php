<?php

/**
 * @group Cache
 * @covers MessageBlobStore
 */
class MessageBlobStoreTest extends PHPUnit_Framework_TestCase {

	protected function setHashCache( MessageBlobStore $blobStore ) {
		// MediaWiki defaults WANCache to CACHE_NONE, set it to something
		// that persists within the process
		$access = TestingAccessWrapper::newFromObject( $blobStore );
		$access->wanCache = new WANObjectCache( array(
			'cache' => new HashBagOStuff(),
			'pool' => 'test',
			'relayer' => new EventRelayerNull( array() )
		) );
	}

	protected function makeModule( array $messages ) {
		$module = new ResourceLoaderTestModule( array( 'messages' => $messages ) );
		$module->setName( 'test.blobstore' );
		return $module;
	}

	public function testGetBlob() {
		$blobStore = $this->getMockBuilder( 'MessageBlobStore' )
			->setMethods( array( 'fetchMessage' ) )
			->getMock();
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

		$blobStore = $this->getMockBuilder( 'MessageBlobStore' )
			->setMethods( array( 'fetchMessage' ) )
			->getMock();
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->onConsecutiveCalls( 'First', 'Second' ) );
		$this->setHashCache( $blobStore );

		$module = $this->makeModule( array( 'example' ) );
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First"}', $blob, 'Generated blob' );

		//var_dump(TestingAccessWrapper::newFromObject($blobStore->wanCache)->cache);

		$module = $this->makeModule( array( 'example' ) );
		$blob = $blobStore->getBlob( $module, 'en' );

		$this->assertEquals( '{"example":"First"}', $blob, 'Cache hit' );
	}

	public function testCacheMiss() {
		$module = $this->makeModule( array( 'example' ) );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );

		$blobStore = $this->getMockBuilder( 'MessageBlobStore' )
			->setConstructorArgs( array( $rl ) )
			->setMethods( array( 'fetchMessage' ) )
			->getMock();
		$blobStore->expects( $this->any() )
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
