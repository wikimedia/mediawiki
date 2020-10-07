<?php

/**
 * @group large
 * @covers Argon2Password
 * @covers Password
 * @covers ParameterizedPassword
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class Argon2PasswordTest extends PasswordTestCase {

	protected function setUp() : void {
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
	protected function getTypeConfigs() {
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
		$result = [
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
			]
		];

		if ( defined( 'PASSWORD_ARGON2ID' ) ) {
			// @todo: Argon2id cases
			$result = array_merge( $result, [] );
		}

		return $result;
	}

	/**
	 * @dataProvider provideNeedsUpdate
	 */
	public function testNeedsUpdate( $updateExpected, $hash ) {
		$password = $this->passwordFactory->newFromCiphertext( $hash );
		$this->assertSame( $updateExpected, $password->needsUpdate() );
	}

	public function provideNeedsUpdate() {
		return [
			[ false, ':argon2:$argon2i$v=19$m=1024,t=2,p=2$bFJ4TzM5RWh2T0VmeFhDTA$AHFUFZRh69aZYBqyxn6tpujpEcf2JP8wgRCPU3nw3W4' ],
			[ false, ':argon2:$argon2i$v=19$m=1024,t=2,p=2$<whatever>' ],
			[ true, ':argon2:$argon2i$v=19$m=666,t=2,p=2$<whatever>' ],
			[ true, ':argon2:$argon2i$v=19$m=1024,t=666,p=2$<whatever>' ],
			[ true, ':argon2:$argon2i$v=19$m=1024,t=2,p=666$<whatever>' ],
		];
	}

	public function testPartialConfig() {
		// The default options changed in PHP 7.2.21 and 7.3.8. This seems to be the only way to
		// fetch them at runtime.
		$options = password_get_info( password_hash( '', PASSWORD_ARGON2I ) )['options'];

		$factory = new PasswordFactory();
		$factory->register( 'argon2', [
			'class' => Argon2Password::class,
			'algo' => 'argon2i',
		] );

		$partialPassword = $factory->newFromType( 'argon2' );
		$partialPassword->crypt( 'password' );

		$factory2 = new PasswordFactory();
		$factory2->register( 'argon2', [
			'class' => Argon2Password::class,
			'algo' => 'argon2i',
		] + $options );

		$fullPassword = $factory2->newFromCiphertext( $partialPassword->toString() );

		$this->assertFalse( $fullPassword->needsUpdate(),
			'Options not set for a password should fall back to defaults'
		);
	}
}
