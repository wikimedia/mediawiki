<?php

namespace MediaWiki\Tests\Integration\Specials\Helpers;

use LoginHelper;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers LoginHelper
 */
class LoginHelperTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		// Clear the cache for the valid error messages after each test.
		$loginHelper = TestingAccessWrapper::newFromClass( LoginHelper::class );
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

		$actualMessages = LoginHelper::getValidErrorMessages();
		$this->assertContains( 'testing-abc', $actualMessages );
		$this->assertContains( 'exception-nologin-text', $actualMessages );
		$this->assertContains( 'mailnologintext', $actualMessages );
		$this->assertTrue( $hookCalled );
	}

	public function testGetValidErrorMessagesWithoutHook() {
		$actualMessages = LoginHelper::getValidErrorMessages();
		$this->assertNotContains( 'testing-abc', $actualMessages );
		$this->assertContains( 'exception-nologin-text', $actualMessages );
		$this->assertContains( 'mailnologintext', $actualMessages );
	}
}
