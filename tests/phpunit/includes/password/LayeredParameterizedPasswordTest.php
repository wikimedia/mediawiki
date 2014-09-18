<?php

class LayeredParameterizedPasswordTest extends PasswordTestCase {
	protected function getTypeConfigs() {
		return array(
			'testLargeLayeredTop' => array(
				'class' => 'LayeredParameterizedPassword',
				'types' => array(
					'testLargeLayeredBottom',
					'testLargeLayeredBottom',
					'testLargeLayeredBottom',
					'testLargeLayeredBottom',
					'testLargeLayeredFinal',
				),
			),
			'testLargeLayeredBottom' => array(
				'class' => 'Pbkdf2Password',
				'algo' => 'sha512',
				'cost' => 1024,
				'length' => 512,
			),
			'testLargeLayeredFinal' => array(
				'class' => 'BcryptPassword',
				'cost' => 5,
			)
		);
	}

	public static function providePasswordTests() {
		/** @codingStandardsIgnoreStart Generic.Files.LineLength.TooLong */
		return array(
			array( true, ':testLargeLayeredTop:sha512:1024:512!sha512:1024:512!sha512:1024:512!sha512:1024:512!5!vnRy+2SrSA0fHt3dwhTP5g==!AVnwfZsAQjn+gULv7FSGjA==!xvHUX3WcpkeSn1lvjWcvBg==!It+OC/N9tu+d3ByHhuB0BQ==!Tb.gqUOiD.aWktVwHM.Q/O!7CcyMfXUPky5ptyATJsR2nq3vUqtnBC', 'testPassword123' ),
		);
		/** @codingStandardsIgnoreEnd */
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

		$this->assertTrue( $totalPassword->equals( 'testPassword123' ) );
	}
}
