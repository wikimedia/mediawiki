<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group Cache
 * @covers MessageBlobStore
 */
class MessageBlobStoreTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		parent::setUp();
		// MediaWiki tests defaults $wgMainWANCache to CACHE_NONE.
		// Use hash instead so that caching is observed
		$this->wanCache = $this->getMockBuilder( 'WANObjectCache' )
			->setConstructorArgs( [ [
				'cache' => new HashBagOStuff(),
				'pool' => 'test',
				'relayer' => new EventRelayerNull( [] )
			] ] )
			->setMethods( [ 'makePurgeValue' ] )
			->getMock();

		$this->wanCache->expects( $this->any() )
			->method( 'makePurgeValue' )
			->will( $this->returnCallback( function ( $timestamp, $holdoff ) {
				// Disable holdoff as it messes with testing
				return WANObjectCache::PURGE_VAL_PREFIX . (float)$timestamp . ':0';
			} ) );
	}

	protected function makeBlobStore( $methods = null, $rl = null ) {
		$blobStore = $this->getMockBuilder( 'MessageBlobStore' )
			->setConstructorArgs( [ $rl ] )
			->setMethods( $methods )
			->getMock();

		$access = TestingAccessWrapper::newFromObject( $blobStore );
		$access->wanCache = $this->wanCache;
		return $blobStore;
	}

	protected function makeModule( array $messages ) {
		$module = new ResourceLoaderTestModule( [ 'messages' => $messages ] );
		$module->setName( 'test.blobstore' );
		return $module;
	}

	/** @covers MessageBlobStore::setLogger */
	public function testSetLogger() {
		$blobStore = $this->makeBlobStore();
		$this->assertSame( null, $blobStore->setLogger( new Psr\Log\NullLogger() ) );
	}

	/** @covers MessageBlobStore::getResourceLoader */
	public function testGetResourceLoader() {
		// Call protected method
		$blobStore = TestingAccessWrapper::newFromObject( $this->makeBlobStore() );
		$this->assertInstanceOf(
			ResourceLoader::class,
			$blobStore->getResourceLoader()
		);
	}

	/** @covers MessageBlobStore::fetchMessage */
	public function testFetchMessage() {
		$module = $this->makeModule( [ 'mainpage' ] );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );

		$blobStore = $this->makeBlobStore( null, $rl );
		$blob = $blobStore->getBlob( $module, 'en' );

		$this->assertEquals( '{"mainpage":"Main Page"}', $blob, 'Generated blob' );
	}

	/** @covers MessageBlobStore::fetchMessage */
	public function testFetchMessageFail() {
		$module = $this->makeModule( [ 'i-dont-exist' ] );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );

		$blobStore = $this->makeBlobStore( null, $rl );
		$blob = $blobStore->getBlob( $module, 'en' );

		$this->assertEquals( '{"i-dont-exist":"\u29fci-dont-exist\u29fd"}', $blob, 'Generated blob' );
	}

	public function testGetBlob() {
		$module = $this->makeModule( [ 'foo' ] );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );

		$blobStore = $this->makeBlobStore( [ 'fetchMessage' ], $rl );
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->returnValue( 'Example' ) );

		$blob = $blobStore->getBlob( $module, 'en' );

		$this->assertEquals( '{"foo":"Example"}', $blob, 'Generated blob' );
	}

	public function testGetBlobCached() {
		$module = $this->makeModule( [ 'example' ] );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );

		$blobStore = $this->makeBlobStore( [ 'fetchMessage' ], $rl );
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->returnValue( 'First' ) );

		$module = $this->makeModule( [ 'example' ] );
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First"}', $blob, 'Generated blob' );

		$blobStore = $this->makeBlobStore( [ 'fetchMessage' ], $rl );
		$blobStore->expects( $this->never() )
			->method( 'fetchMessage' )
			->will( $this->returnValue( 'Second' ) );

		$module = $this->makeModule( [ 'example' ] );
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First"}', $blob, 'Cache hit' );
	}

	public function testUpdateMessage() {
		$module = $this->makeModule( [ 'example' ] );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );
		$blobStore = $this->makeBlobStore( [ 'fetchMessage' ], $rl );
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->returnValue( 'First' ) );

		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First"}', $blob, 'Generated blob' );

		$blobStore->updateMessage( 'example' );

		$module = $this->makeModule( [ 'example' ] );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );
		$blobStore = $this->makeBlobStore( [ 'fetchMessage' ], $rl );
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->returnValue( 'Second' ) );

		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"Second"}', $blob, 'Updated blob' );
	}

	public function testValidation() {
		$module = $this->makeModule( [ 'foo' ] );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );

		$blobStore = $this->makeBlobStore( [ 'fetchMessage' ], $rl );
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->returnValueMap( [
				[ 'foo', 'en', 'Hello' ],
			] ) );

		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"foo":"Hello"}', $blob, 'Generated blob' );

		// Now, imagine a change to the module is deployed. The module now contains
		// message 'foo' and 'bar'. While updateMessage() was not called (since no
		// message values were changed) it should detect the change in list of
		// message keys.
		$module = $this->makeModule( [ 'foo', 'bar' ] );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );

		$blobStore = $this->makeBlobStore( [ 'fetchMessage' ], $rl );
		$blobStore->expects( $this->exactly( 2 ) )
			->method( 'fetchMessage' )
			->will( $this->returnValueMap( [
				[ 'foo', 'en', 'Hello' ],
				[ 'bar', 'en', 'World' ],
			] ) );

		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"foo":"Hello","bar":"World"}', $blob, 'Updated blob' );
	}

	public function testClear() {
		$module = $this->makeModule( [ 'example' ] );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );
		$blobStore = $this->makeBlobStore( [ 'fetchMessage' ], $rl );
		$blobStore->expects( $this->exactly( 2 ) )
			->method( 'fetchMessage' )
			->will( $this->onConsecutiveCalls( 'First', 'Second' ) );

		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First"}', $blob, 'Generated blob' );

		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First"}', $blob, 'Cache-hit' );

		$blobStore->clear();

		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"Second"}', $blob, 'Updated blob' );
	}
}
