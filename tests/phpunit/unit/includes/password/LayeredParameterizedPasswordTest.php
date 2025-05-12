<?php

use MediaWiki\Password\BcryptPassword;
use MediaWiki\Password\LayeredParameterizedPassword;
use MediaWiki\Password\ParameterizedPassword;
use MediaWiki\Password\Pbkdf2PasswordUsingHashExtension;

/**
 * @covers \MediaWiki\Password\LayeredParameterizedPassword
 * @covers \MediaWiki\Password\Password
 */
class LayeredParameterizedPasswordTest extends PasswordTestCase {
	protected static function getTypeConfigs() {
		return [
			'testLargeLayeredTop' => [
				'class' => LayeredParameterizedPassword::class,
				'types' => [
					'testLargeLayeredBottom',
					'testLargeLayeredBottom',
					'testLargeLayeredBottom',
					'testLargeLayeredBottom',
					'testLargeLayeredFinal',
				],
			],
			'testLargeLayeredBottom' => [
				'class' => Pbkdf2PasswordUsingHashExtension::class,
				'algo' => 'sha512',
				'cost' => 1024,
				'length' => 512,
			],
			'testLargeLayeredFinal' => [
				'class' => BcryptPassword::class,
				'cost' => 5,
			]
		];
	}

	protected static function getValidTypes() {
		return [ 'testLargeLayeredFinal' ];
	}

	public static function providePasswordTests() {
		return [
			[
				true,
				':testLargeLayeredTop:sha512:1024:512!sha512:1024:512!sha512:1024:512!sha512:1024:512!5!vnRy+2SrSA0fHt3dwhTP5g==!AVnwfZsAQjn+gULv7FSGjA==!xvHUX3WcpkeSn1lvjWcvBg==!It+OC/N9tu+d3ByHhuB0BQ==!Tb.gqUOiD.aWktVwHM.Q/O!7CcyMfXUPky5ptyATJsR2nq3vUqtnBC',
				'testPassword123'
			],
		];
		// phpcs:enable
	}

	public function testLargeLayeredPartialUpdate() {
		/** @var ParameterizedPassword $partialPassword */
		$partialPassword = $this->passwordFactory->newFromType( 'testLargeLayeredBottom' );
		$partialPassword->crypt( 'testPassword123' );

		/** @var LayeredParameterizedPassword $totalPassword */
		$totalPassword = $this->passwordFactory->newFromType( 'testLargeLayeredTop' );
		$totalPassword->partialCrypt( $partialPassword );

		$this->assertTrue( $totalPassword->verify( 'testPassword123' ) );
	}
}
