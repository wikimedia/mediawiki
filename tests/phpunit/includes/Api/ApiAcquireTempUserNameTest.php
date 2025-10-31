<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\TempUser\TempUserCreator;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiAcquireTempUserName
 */
class ApiAcquireTempUserNameTest extends ApiTestCase {
	use MockAuthorityTrait;
	use TempUserTestTrait;

	public function testExecuteDiesWhenNotEnabled() {
		$this->disableAutoCreateTempUser();
		$this->expectApiErrorCode( 'tempuserdisabled' );

		$this->doApiRequestWithToken( [
			"action" => "acquiretempusername",
		] );
	}

	public function testExecuteDiesWhenUserIsRegistered() {
		$this->enableAutoCreateTempUser();
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
		ConvertibleTimestamp::setFakeTime( '20240405060708' );
		$this->enableAutoCreateTempUser( [
			'genPattern' => '~$1',
		] );

		$this->assertArrayEquals(
			[ 'acquiretempusername' => '~2024-1' ],
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
