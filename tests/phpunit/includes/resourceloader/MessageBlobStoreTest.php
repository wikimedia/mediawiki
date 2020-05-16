<?php

use Psr\Log\NullLogger;

/**
 * @group ResourceLoader
 * @covers MessageBlobStore
 */
class MessageBlobStoreTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	private const NAME = 'test.blobstore';

	protected function setUp() : void {
		parent::setUp();
		// MediaWiki's test wrapper sets $wgMainWANCache to CACHE_NONE.
		// Use HashBagOStuff here so that we can observe caching.
		$this->wanCache = new WANObjectCache( [
			'cache' => new HashBagOStuff()
		] );

		$this->clock = 1301655600.000;
		$this->wanCache->setMockTime( $this->clock );
	}

	public function testBlobCreation() {
		$rl = new EmptyResourceLoader();
		$rl->register( self::NAME, [
			'factory' => function () {
				return $this->makeModule( [ 'mainpage' ] );
			}
		] );

		$blobStore = $this->makeBlobStore( null, $rl );
		$blob = $blobStore->getBlob( $rl->getModule( self::NAME ), 'en' );

		$this->assertEquals( '{"mainpage":"Main Page"}', $blob, 'Generated blob' );
	}

	public function testBlobCreation_empty() {
		$module = $this->makeModule( [] );
		$rl = new EmptyResourceLoader();

		$blobStore = $this->makeBlobStore( null, $rl );
		$blob = $blobStore->getBlob( $module, 'en' );

		$this->assertEquals( '{}', $blob, 'Generated blob' );
	}

	public function testBlobCreation_unknownMessage() {
		$module = $this->makeModule( [ 'i-dont-exist', 'mainpage', 'i-dont-exist2' ] );
		$rl = new EmptyResourceLoader();
		$blobStore = $this->makeBlobStore( null, $rl );

		// Generating a blob should continue without errors,
		// with keys of unknown messages excluded from the blob.
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"mainpage":"Main Page"}', $blob, 'Generated blob' );
	}

	public function testMessageCachingAndPurging() {
		$rl = new EmptyResourceLoader();
		// Register it so that MessageBlobStore::updateMessage can
		// discover it from the registry as a module that uses this message.
		$rl->register( self::NAME, [
			'factory' => function () {
				return $this->makeModule( [ 'example' ] );
			}
		] );
		$module = $rl->getModule( self::NAME );
		$blobStore = $this->makeBlobStore( [ 'fetchMessage' ], $rl );

		// Advance this new WANObjectCache instance to a normal state,
		// by doing one "get" and letting its hold off period expire.
		// Without this, the first real "get" would lazy-initialise the
		// checkKey and thus reject the first "set".
		$blobStore->getBlob( $module, 'en' );
		$this->clock += 20;

		// Arrange version 1 of a message
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->returnValue( 'First version' ) );

		// Assert
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First version"}', $blob, 'Blob for v1' );

		// Arrange version 2
		$blobStore = $this->makeBlobStore( [ 'fetchMessage' ], $rl );
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->returnValue( 'Second version' ) );
		$this->clock += 20;

		// Assert
		// We do not validate whether a cached message is up-to-date.
		// Instead, changes to messages will send us a purge.
		// When cache is not purged or expired, it must be used.
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First version"}', $blob, 'Reuse cached v1 blob' );

		// Purge cache
		$blobStore->updateMessage( 'example' );
		$this->clock += 20;

		// Assert
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"Second version"}', $blob, 'Updated blob for v2' );
	}

	public function testPurgeEverything() {
		$module = $this->makeModule( [ 'example' ] );
		$rl = new EmptyResourceLoader();
		$blobStore = $this->makeBlobStore( [ 'fetchMessage' ], $rl );
		// Advance this new WANObjectCache instance to a normal state.
		$blobStore->getBlob( $module, 'en' );
		$this->clock += 20;

		// Arrange version 1 and 2
		$blobStore->expects( $this->exactly( 2 ) )
			->method( 'fetchMessage' )
			->will( $this->onConsecutiveCalls( 'First', 'Second' ) );

		// Assert
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First"}', $blob, 'Blob for v1' );

		$this->clock += 20;

		// Assert
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First"}', $blob, 'Blob for v1 again' );

		// Purge everything
		$blobStore->clear();
		$this->clock += 20;

		// Assert
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"Second"}', $blob, 'Blob for v2' );
	}

	public function testValidateAgainstModuleRegistry() {
		// Arrange version 1 of a module
		$module = $this->makeModule( [ 'foo' ] );
		$rl = new EmptyResourceLoader();
		$blobStore = $this->makeBlobStore( [ 'fetchMessage' ], $rl );
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->returnValueMap( [
				// message key, language code, message value
				[ 'foo', 'en', 'Hello' ],
			] ) );

		// Assert
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"foo":"Hello"}', $blob, 'Blob for v1' );

		// Arrange version 2 of module
		// While message values may be out of date, the set of messages returned
		// must always match the set of message keys required by the module.
		// We do not receive purges for this because no messages were changed.
		$module = $this->makeModule( [ 'foo', 'bar' ] );
		$rl = new EmptyResourceLoader();
		$blobStore = $this->makeBlobStore( [ 'fetchMessage' ], $rl );
		$blobStore->expects( $this->exactly( 2 ) )
			->method( 'fetchMessage' )
			->will( $this->returnValueMap( [
				// message key, language code, message value
				[ 'foo', 'en', 'Hello' ],
				[ 'bar', 'en', 'World' ],
			] ) );

		// Assert
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"foo":"Hello","bar":"World"}', $blob, 'Blob for v2' );
	}

	public function testSetLoggedIsVoid() {
		$blobStore = $this->makeBlobStore();
		$this->assertNull( $blobStore->setLogger( new NullLogger() ) );
	}

	private function makeBlobStore( $methods = null, $rl = null ) {
		$blobStore = $this->getMockBuilder( MessageBlobStore::class )
			->setConstructorArgs( [
				$rl ?? $this->createMock( ResourceLoader::class ),
				null,
				$this->wanCache
			] )
			->setMethods( $methods )
			->getMock();

		return $blobStore;
	}

	private function makeModule( array $messages ) {
		$module = new ResourceLoaderTestModule( [ 'messages' => $messages ] );
		$module->setName( self::NAME );
		return $module;
	}
}
