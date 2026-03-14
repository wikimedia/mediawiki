<?php

namespace MediaWiki\Tests\Integration\Exception;

use MediaWiki\Exception\LoginErrorHelper;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Exception\LoginErrorHelper
 */
class LoginErrorHelperTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		// Clear the cache for the valid error messages after each test.
		$loginHelper = TestingAccessWrapper::newFromClass( LoginErrorHelper::class );
		$loginHelper->validErrorMessagesCache = null;
	}

	public function testGetValidErrorMessages() {
		$hookCalled = false;
		$this->setTemporaryHook(
			'LoginFormValidErrorMessages',
			static function ( &$messages ) use ( &$hookCalled ) {
				$messages[] = 'testing-abc';
				$hookCalled = true;
			}
		);

		$actualMessages = LoginErrorHelper::getValidErrorMessages();
		$this->assertContains( 'testing-abc', $actualMessages );
		$this->assertContains( 'exception-nologin-text', $actualMessages );
		$this->assertContains( 'mailnologintext', $actualMessages );
		$this->assertTrue( $hookCalled );
	}

	public function testGetValidErrorMessagesWithoutHook() {
		$actualMessages = LoginErrorHelper::getValidErrorMessages();
		$this->assertNotContains( 'testing-abc', $actualMessages );
		$this->assertContains( 'exception-nologin-text', $actualMessages );
		$this->assertContains( 'mailnologintext', $actualMessages );
	}
}
