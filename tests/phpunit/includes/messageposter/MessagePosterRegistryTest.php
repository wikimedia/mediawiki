<?php

use MediaWiki\MessagePoster\IMessagePoster;
use MediaWiki\MessagePoster\MessagePosterRegistry;

class TestMessagePoster implements IMessagePoster {
	public function __construct( $title ) {
	}

	public function post( User $user, $subject, $body, $bot ) {
	}
}

/**
 * Tests for MessagePosterRegistry
 *
 * @covers MediaWiki\MessagePoster\MessagePosterRegistry
 */
class MessagePosterRegistryTest extends MediaWikiTestCase {
	const TEST_MODEL = 'test-content-model';

	protected $messagePosterRegistry;

	protected function setUp() {
		parent::setUp();

		$this->messagePosterRegistry = new MessagePosterRegistry;
	}

	/**
	 * @expectedException MWException
	 * @expectedExceptionMessage Content model "test-content-model" is already registered
	 */
	public function testRegister() {
		$this->messagePosterRegistry->register( self::TEST_MODEL, TestMessagePoster::class );

		$titleMock = $this->getMockBuilder( Title::class )
			->getMock();

		$titleMock->expects( $this->any() )
			->method( 'getContentModel' )
			->willReturn( self::TEST_MODEL );

		$poster = $this->messagePosterRegistry->getMessagePoster( $titleMock );
		$this->assertInstanceOf( TestMessagePoster::class, $poster, 'After registration, getMessagePoster should return instance of correct class' );

		$this->messagePosterRegistry->register( self::TEST_MODEL, 'SomePoster' );
	}

	/**
	 * @expectedException MWException
	 * @expectedExceptionMessage "Talk:Test" has the content model "unknown-content-model", which is not registered
	 */
	public function testUnregisteredContentModel() {
		$titleMock = $this->getMockBuilder( Title::class )
			->getMock();

		$titleMock->expects( $this->any() )
			->method( 'getContentModel' )
			->willReturn( 'unknown-content-model' );

		$titleMock->expects( $this->any() )
			->method( 'getPrefixedDBkey' )
			->willReturn( 'Talk:Test' );

		$poster = $this->messagePosterRegistry->getMessagePoster( $titleMock );
	}
}
