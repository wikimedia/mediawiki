<?php

use MediaWiki\Password\Argon2Password;

/**
 * @group large
 * @covers \MediaWiki\Password\Argon2Password
 * @covers \MediaWiki\Password\Password
 * @covers \MediaWiki\Password\ParameterizedPassword
 */
class Argon2PasswordTest extends PasswordTestCase {

	protected function setUp(): void {
		parent::setUp();
		if ( !defined( 'PASSWORD_ARGON2I' ) ) {
			$this->markTestSkipped( 'Argon2 support not found' );
		}
	}

	/**
	 * Return an array of configs to be used for this class's password type.
	 *
	 * @return array[]
	 */
	protected static function getTypeConfigs() {
		return [
			'argon2' => [
				'class' => Argon2Password::class,
				'algo' => 'argon2i',
				'memory_cost' => 1024,
				'time_cost' => 2,
				'threads' => 2,
			]
		];
	}

	/**
	 * @return array
	 */
	public static function providePasswordTests() {
		return [
			[
				true,
				':argon2:$argon2i$v=19$m=1024,t=2,p=2$RHpGTXJPeFlSV2NDTEswNA$VeW7rumZY4pL8XO4KeQkKD43r5uX3eazVJRtrFN7lNc',
				'password',
			],
			[
				true,
				':argon2:$argon2i$v=19$m=2048,t=5,p=3$MHFKSnh6WWZEWkpKa09SUQ$vU92h/8hkByL5VKW1P9amCj054pZILGKznAvKWAivZE',
				'password',
			],
			[
				true,
				':argon2:$argon2i$v=19$m=1024,t=2,p=2$bFJ4TzM5RWh2T0VmeFhDTA$AHFUFZRh69aZYBqyxn6tpujpEcf2JP8wgRCPU3nw3W4',
				"pass\x00word",
			],
			[
				false,
				':argon2:$argon2i$v=19$m=1024,t=2,p=2$UGZqTWJRUkI1alVNTGRUbA$RcASw9XUWjCDO9WNnuVkGkEylURUW/CcNwSffdFwN74',
				'password',
			],
			// argon2id
			[
				true,
				':argon2:$argon2id$v=19$m=65536,t=1,p=1$SS51Z0U2bkQ5Mk1GYUNQOQ$jdN3UnHn6MHOaOeWiX+RqRhcwVPLLDlEAKPvDt/qKIY',
				'password'
			]
		];
	}

	/**
	 * @dataProvider provideNeedsUpdate
	 */
	public function testNeedsUpdate( $updateExpected, $hash ) {
		$password = $this->passwordFactory->newFromCiphertext( $hash );
		$this->assertSame( $updateExpected, $password->needsUpdate() );
	}

	public static function provideNeedsUpdate() {
		return [
			[ false, ':argon2:$argon2i$v=19$m=1024,t=2,p=2$bFJ4TzM5RWh2T0VmeFhDTA$AHFUFZRh69aZYBqyxn6tpujpEcf2JP8wgRCPU3nw3W4' ],
			[ false, ':argon2:$argon2i$v=19$m=1024,t=2,p=2$<whatever>' ],
			[ true, ':argon2:$argon2i$v=19$m=666,t=2,p=2$<whatever>' ],
			[ true, ':argon2:$argon2i$v=19$m=1024,t=666,p=2$<whatever>' ],
			[ true, ':argon2:$argon2i$v=19$m=1024,t=2,p=666$<whatever>' ],
		];
	}
}
