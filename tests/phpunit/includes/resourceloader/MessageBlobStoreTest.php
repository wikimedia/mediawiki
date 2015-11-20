<?php

/**
 * @group Database
 * @group Cache
 * @covers MessageBlobStore
 */
class MessageBlobStoreTest extends ResourceLoaderTestCase {
	protected $tablesUsed = array( 'msg_resource' );

	protected function makeBlobStore( $methods = null, $rl = null ) {
		$blobStore = $this->getMockBuilder( 'MessageBlobStore' )
			->setConstructorArgs( array( $rl ) )
			->setMethods( $methods )
			->getMock();

		return $blobStore;
	}

	protected function makeModule( array $messages ) {
		$module = new ResourceLoaderTestModule( array( 'messages' => $messages ) );
		$module->setName( 'test.blobstore' );
		return $module;
	}

	public function testGetBlob() {
		$module = $this->makeModule( array( 'foo' ) );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );

		$blobStore = $this->makeBlobStore( array( 'fetchMessage' ), $rl );
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->returnValue( 'Example' ) );

		$blob = $blobStore->getBlob( $module, 'en' );

		$this->assertEquals( '{"foo":"Example"}', $blob, 'Generated blob' );
	}

	public function testGetBlobCached() {
		$module = $this->makeModule( array( 'example' ) );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );

		$blobStore = $this->makeBlobStore( array( 'fetchMessage' ), $rl );
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->returnValue( 'First' ) );

		$module = $this->makeModule( array( 'example' ) );
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First"}', $blob, 'Generated blob' );

		$blobStore = $this->makeBlobStore( array( 'fetchMessage' ), $rl );
		$blobStore->expects( $this->never() )
			->method( 'fetchMessage' )
			->will( $this->returnValue( 'Second' ) );

		$module = $this->makeModule( array( 'example' ) );
		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"First"}', $blob, 'Cache hit' );
	}

	public function testUpdateMessage() {
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
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );
		$blobStore = $this->makeBlobStore( array( 'fetchMessage' ), $rl );
		$blobStore->expects( $this->never() )
			->method( 'fetchMessage' )
			->will( $this->returnValue( 'Wrong' ) );

		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"example":"Second"}', $blob, 'Updated blob' );
	}

	public function testValidation() {
		$module = $this->makeModule( array( 'foo' ) );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );

		$blobStore = $this->makeBlobStore( array( 'fetchMessage' ), $rl );
		$blobStore->expects( $this->once() )
			->method( 'fetchMessage' )
			->will( $this->returnValueMap( array(
				array( 'foo', 'en', 'Hello' ),
			) ) );

		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"foo":"Hello"}', $blob, 'Generated blob' );

		// Now, imagine a change to the module is deployed. The module now contains
		// message 'foo' and 'bar'. While updateMessage() was not called (since no
		// message values were changed) it should detect the change in list of
		// message keys.
		$module = $this->makeModule( array( 'foo', 'bar' ) );
		$rl = new ResourceLoader();
		$rl->register( $module->getName(), $module );

		$blobStore = $this->makeBlobStore( array( 'fetchMessage' ), $rl );
		$blobStore->expects( $this->exactly( 2 ) )
			->method( 'fetchMessage' )
			->will( $this->returnValueMap( array(
				array( 'foo', 'en', 'Hello' ),
				array( 'bar', 'en', 'World' ),
			) ) );

		$blob = $blobStore->getBlob( $module, 'en' );
		$this->assertEquals( '{"foo":"Hello","bar":"World"}', $blob, 'Updated blob' );
	}
}
