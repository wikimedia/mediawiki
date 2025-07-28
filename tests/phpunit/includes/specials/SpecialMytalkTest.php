<?php

use MediaWiki\Block\AnonIpBlockTarget;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\Permissions\Authority;
use MediaWiki\Specials\Redirects\SpecialMytalk;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\UserIdentityValue;

/**
 * @group SpecialPage
 * @group Database
 * @covers \MediaWiki\Specials\Redirects\SpecialMytalk
 * @covers \MediaWiki\Auth\AuthManager
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

	public function testLoggedOutAndBlockedWithTempAccountsEnabled() {
		$this->enableAutoCreateTempUser();

		$user = new UserIdentityValue( 0, '127.0.0.1' );
		$block = new DatabaseBlock( [
			'target' => new AnonIpBlockTarget( '127.0.0.1' ),
			'by' => $this->getTestSysop()->getUser(),
			'allowUsertalk' => true,
		] );
		$this->getServiceContainer()
			->getDatabaseBlockStoreFactory()
			->getDatabaseBlockStore( $block->getWikiId() )
			->insertBlock( $block );

		[ $html, ] = $this->executeSpecialPage(
			'',
			null,
			null,
			$this->mockUserAuthorityWithBlock( $user, $block )
		);
		$this->assertStringContainsString( 'mytalk-appeal-submit', $html );
	}
}
