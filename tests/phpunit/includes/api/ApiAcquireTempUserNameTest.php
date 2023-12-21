<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\TempUser\TempUserCreator;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiAcquireTempUserName
 */
class ApiAcquireTempUserNameTest extends ApiTestCase {
	use MockAuthorityTrait;

	public function testExecuteDiesWhenNotEnabled() {
		$this->overrideConfigValue(
			MainConfigNames::AutoCreateTempUser,
			[
				'enabled' => false,
			]
		);
		$this->expectApiErrorCode( 'tempuserdisabled' );

		$this->doApiRequestWithToken( [
			"action" => "acquiretempusername",
		] );
	}

	public function testExecuteDiesWhenUserIsRegistered() {
		$this->overrideConfigValue(
			MainConfigNames::AutoCreateTempUser,
			[
				'enabled' => true,
				'expireAfterDays' => null,
				'actions' => [ 'edit' ],
				'genPattern' => '*Unregistered $1',
				'matchPattern' => '*$1',
				'serialProvider' => [ 'type' => 'local' ],
				'serialMapping' => [ 'type' => 'plain-numeric' ],
			]
		);
		$this->expectApiErrorCode( 'alreadyregistered' );

		$this->doApiRequestWithToken(
			[
				'action' => 'acquiretempusername',
			],
			null,
			$this->mockRegisteredUltimateAuthority()
		);
	}

	public function testExecuteDiesWhenNameCannotBeAcquired() {
		$mockTempUserCreator = $this->createMock( TempUserCreator::class );
		$mockTempUserCreator->method( 'isEnabled' )
			->willReturn( true );
		$mockTempUserCreator->method( 'acquireAndStashName' )
			->willReturn( null );
		$this->overrideMwServices(
			null,
			[
				'TempUserCreator' => static function () use ( $mockTempUserCreator ) {
					return $mockTempUserCreator;
				}
			]
		);
		$this->expectApiErrorCode( 'tempuseracquirefailed' );

		$this->doApiRequestWithToken(
			[
				'action' => 'acquiretempusername',
			],
			null,
			$this->mockAnonUltimateAuthority()
		);
	}

	public function testExecuteForSuccessfulCall() {
		$this->overrideConfigValue(
			MainConfigNames::AutoCreateTempUser,
			[
				'enabled' => true,
				'expireAfterDays' => null,
				'actions' => [ 'edit' ],
				'genPattern' => '*Unregistered $1',
				'matchPattern' => '*$1',
				'serialProvider' => [ 'type' => 'local' ],
				'serialMapping' => [ 'type' => 'plain-numeric' ],
			]
		);

		$this->assertArrayEquals(
			[ 'acquiretempusername' => '*Unregistered 1' ],
			$this->doApiRequestWithToken(
				[
					'action' => 'acquiretempusername',
				],
				null,
				$this->mockAnonUltimateAuthority()
			)[0],
			true,
			true
		);
	}
}
