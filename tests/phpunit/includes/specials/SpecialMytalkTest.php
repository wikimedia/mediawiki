<?php

use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\Permissions\Authority;
use MediaWiki\Specials\Redirects\SpecialMytalk;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;

/**
 * @group SpecialPage
 * @group Database
 * @covers \MediaWiki\Specials\Redirects\SpecialMytalk
 */
class SpecialMytalkTest extends SpecialPageTestBase {
	use TempUserTestTrait;
	use MockAuthorityTrait;

	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		return new SpecialMytalk(
			$this->getServiceContainer()->getTempUserConfig(),
			$this->getServiceContainer()->getTempUserCreator(),
			$this->getServiceContainer()->getAuthManager()
		);
	}

	private function testRedirect( Authority $user ) {
		[ , $response ] = $this->executeSpecialPage(
			'',
			null,
			null,
			$user
		);
		$this->assertStringContainsString(
			'User_talk:' . $user->getUser()->getName(),
			$response->getHeader( 'LOCATION' )
		);
	}

	public function testLoggedIn() {
		$this->testRedirect( $this->mockRegisteredNullAuthority() );
	}

	public function testTempAccount() {
		$this->enableAutoCreateTempUser();
		$this->testRedirect( $this->mockTempNullAuthority() );
	}

	public function testLoggedOut() {
		$this->disableAutoCreateTempUser();
		$this->testRedirect( $this->mockAnonNullAuthority() );
	}

	public function testLoggedOutWithTempAccountsEnabled() {
		$this->enableAutoCreateTempUser();
		$this->expectException( UserNotLoggedIn::class );
		$this->executeSpecialPage();
	}
}
