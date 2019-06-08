<?php

/**
 * @covers LayeredParameterizedPassword
 * @covers Password
 */
class LayeredParameterizedPasswordTest extends PasswordTestCase {
	protected function getTypeConfigs() {
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
				'class' => Pbkdf2Password::class,
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

	protected function getValidTypes() {
		return [ 'testLargeLayeredFinal' ];
	}

	public static function providePasswordTests() {
		// phpcs:disable Generic.Files.LineLength
		return [
			[
				true,
				':testLargeLayeredTop:sha512:1024:512!sha512:1024:512!sha512:1024:512!sha512:1024:512!5!vnRy+2SrSA0fHt3dwhTP5g==!AVnwfZsAQjn+gULv7FSGjA==!xvHUX3WcpkeSn1lvjWcvBg==!It+OC/N9tu+d3ByHhuB0BQ==!Tb.gqUOiD.aWktVwHM.Q/O!7CcyMfXUPky5ptyATJsR2nq3vUqtnBC',
				'testPassword123'
			],
		];
		// phpcs:enable
	}

	/**
	 * @covers LayeredParameterizedPassword::partialCrypt
	 */
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
