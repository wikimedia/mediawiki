<?php

use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialPasswordReset;
use MediaWiki\Tests\SpecialPage\FormSpecialPageTestCase;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;

/**
 * @covers \MediaWiki\Specials\SpecialPasswordReset
 * @group Database
 */
class SpecialPasswordResetTest extends FormSpecialPageTestCase {

	use TempUserTestTrait;

	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		return new SpecialPasswordReset(
			$this->getServiceContainer()->getPasswordReset()
		);
	}

	public function testView() {
		[ $html ] = $this->executeSpecialPage();
		// Check that the form fields are as expected
		$this->assertStringContainsString( '(passwordreset-username', $html );
		$this->assertStringContainsString( '(passwordreset-email', $html );
		$this->assertStringContainsString( '(passwordreset-text-many', $html );
		$this->assertStringContainsString( '(mailmypassword', $html );
	}

	public function testExecuteForTemporaryAccountUsername() {
		// Get an existing temporary account to test with
		$this->enableAutoCreateTempUser();
		$tempUser = $this->getServiceContainer()->getTempUserCreator()->create( null, new FauxRequest() )->getUser();
		// Make a fake POST request that tries to use a temporary account for the password
		$request = new FauxRequest(
			[ 'wpUsername' => $tempUser->getName() ],
			true
		);
		[ $html ] = $this->executeSpecialPage( '', $request );
		$this->assertStringContainsString( '(htmlform-user-not-valid', $html );
	}
}
