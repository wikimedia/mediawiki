<?php

use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserIdentityUtils;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\User\UserIdentityUtils
 */
class UserIdentityUtilsTest extends MediaWikiUnitTestCase {
	public static function provideIsTemp() {
		return [
			[ true ],
			[ false ]
		];
	}

	/**
	 * @dataProvider provideIsTemp
	 * @param bool $isTemp
	 */
	public function testIsTemp( $isTemp ) {
		$tempUserConfig = $this->createMock( TempUserConfig::class );
		$tempUserConfig
			->expects( $this->once() )
			->method( 'isTempName' )
			->willReturn( $isTemp );
		$userIdentityUtils = new UserIdentityUtils( $tempUserConfig );
		$userIdentity = new UserIdentityValue( 1, $isTemp ? 'Temp user' : 'Regular user' );
		$this->assertSame( $isTemp, $userIdentityUtils->isTemp( $userIdentity ) );
	}

	public static function provideIsNamed() {
		return [
			[
				'isRegistered' => false,
				'isTemp' => false,
				'expected' => false,
			],
			[
				'isRegistered' => false,
				'isTemp' => true,
				'expected' => false,
			],
			[
				'isRegistered' => true,
				'isTemp' => false,
				'expected' => true,
			],
			[
				'isRegistered' => true,
				'isTemp' => true,
				'expected' => false,
			],
		];
	}

	/**
	 * @dataProvider provideIsNamed
	 * @param bool $isRegistered
	 * @param bool $isTemp
	 * @param bool $expected
	 */
	public function testIsNamed( $isRegistered, $isTemp, $expected ) {
		$tempUserConfig = $this->createMock( TempUserConfig::class );
		$tempUserConfig
			->method( 'isTempName' )
			->willReturn( $isTemp );

		$userIdentityUtils = new UserIdentityUtils( $tempUserConfig );
		$userIdentity = new UserIdentityValue(
			$isRegistered ? 1 : 0,
			$isTemp ? 'Temp user' : 'Regular user'
		);
		$this->assertSame( $expected, $userIdentityUtils->isNamed( $userIdentity ) );
	}

	public function testGetShortUserTypeInternal() {
		$tempUserConfig = $this->createMock( TempUserConfig::class );
		$tempUserConfig
			->method( 'isTempName' )
			->willReturn( $this->onConsecutiveCalls( true, false ) );

		$userIdentityUtils = new UserIdentityUtils( $tempUserConfig );
		$anon = new UserIdentityValue(
			0,
			'127.0.0.1',
		);
		$temp = new UserIdentityValue(
			1,
			'Temp user'
		);
		$named = new UserIdentityValue(
			2,
			'Regular user'
		);
		$this->assertSame( 'anon', $userIdentityUtils->getShortUserTypeInternal( $anon ) );
		$this->assertSame( 'temp', $userIdentityUtils->getShortUserTypeInternal( $temp ) );
		$this->assertSame( 'named', $userIdentityUtils->getShortUserTypeInternal( $named ) );
	}
}
