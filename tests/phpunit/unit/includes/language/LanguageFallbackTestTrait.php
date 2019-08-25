<?php

/**
 * Code to test the getFallbackFor, getFallbacksFor, and getFallbacksIncludingSiteLanguage methods
 * that have historically been static methods of the Language class. It can be used to test any
 * class or object that implements those three methods.
 */
trait LanguageFallbackTestTrait {
	/**
	 * @param array $options Valid keys:
	 *   * expectedGets: How many times we expect to hit the localisation cache. (This can be
	 *   ignored in integration tests -- it's enough to test in unit tests.)
	 *   * siteLangCode
	 * @return string|object Name of class or object with the three methods getFallbackFor,
	 *   getFallbacksFor, and getFallbacksIncludingSiteLanguage.
	 */
	abstract protected function getCallee( array $options = [] );

	/**
	 * @return int Value that was historically in Language::MESSAGES_FALLBACKS
	 */
	abstract protected function getMessagesKey();

	/**
	 * @return int Value that was historically in Language::STRICT_FALLBACKS
	 */
	abstract protected function getStrictKey();

	/**
	 * Convenience/readability wrapper to call a method on a class or object.
	 *
	 * @param string|object As in return value of getCallee()
	 * @param string $method Name of method to call
	 * @param mixed ...$params To pass to method
	 * @return mixed Return value of method
	 */
	private function callMethod( $callee, $method, ...$params ) {
		return [ $callee, $method ]( ...$params );
	}

	/**
	 * @param string $code
	 * @param array $expected
	 * @param array $options
	 * @dataProvider provideGetFallbacksFor
	 * @covers ::getFallbackFor
	 * @covers Language::getFallbackFor
	 */
	public function testGetFallbackFor( $code, array $expected, array $options = [] ) {
		$callee = $this->getCallee( $options );
		// One behavior difference between the old static methods and the new instance methods:
		// returning null instead of false.
		$defaultExpected = is_object( $callee ) ? null : false;
		$this->assertSame( $expected[0] ?? $defaultExpected,
			$this->callMethod( $callee, 'getFallbackFor', $code ) );
	}

	/**
	 * @param string $code
	 * @param array $expected
	 * @param array $options
	 * @dataProvider provideGetFallbacksFor
	 * @covers ::getFallbacksFor
	 * @covers Language::getFallbacksFor
	 */
	public function testGetFallbacksFor( $code, array $expected, array $options = [] ) {
		$this->assertSame( $expected,
			$this->callMethod( $this->getCallee( $options ), 'getFallbacksFor', $code ) );
	}

	/**
	 * @param string $code
	 * @param array $expected
	 * @param array $options
	 * @dataProvider provideGetFallbacksFor
	 * @covers ::getFallbacksFor
	 * @covers Language::getFallbacksFor
	 */
	public function testGetFallbacksFor_messages( $code, array $expected, array $options = [] ) {
		$this->assertSame( $expected,
			$this->callMethod( $this->getCallee( $options ), 'getFallbacksFor',
				$code, $this->getMessagesKey() ) );
	}

	public static function provideGetFallbacksFor() {
		return [
			'en' => [ 'en', [], [ 'expectedGets' => 0 ] ],
			'fr' => [ 'fr', [ 'en' ] ],
			'sco' => [ 'sco', [ 'en' ] ],
			'yi' => [ 'yi', [ 'he', 'en' ] ],
			'ruq' => [ 'ruq', [ 'ruq-latn', 'ro', 'en' ] ],
			'sh' => [ 'sh', [ 'bs', 'sr-el', 'hr', 'en' ] ],
		];
	}

	/**
	 * @param string $code
	 * @param array $expected
	 * @param array $options
	 * @dataProvider provideGetFallbacksFor_strict
	 * @covers ::getFallbacksFor
	 * @covers Language::getFallbacksFor
	 */
	public function testGetFallbacksFor_strict( $code, array $expected, array $options = [] ) {
		$this->assertSame( $expected,
			$this->callMethod( $this->getCallee( $options ), 'getFallbacksFor',
				$code, $this->getStrictKey() ) );
	}

	public static function provideGetFallbacksFor_strict() {
		return [
			'en' => [ 'en', [], [ 'expectedGets' => 0 ] ],
			'fr' => [ 'fr', [] ],
			'sco' => [ 'sco', [ 'en' ] ],
			'yi' => [ 'yi', [ 'he' ] ],
			'ruq' => [ 'ruq', [ 'ruq-latn', 'ro' ] ],
			'sh' => [ 'sh', [ 'bs', 'sr-el', 'hr' ] ],
		];
	}

	/**
	 * @covers ::getFallbacksFor
	 * @covers Language::getFallbacksFor
	 */
	public function testGetFallbacksFor_exception() {
		$this->setExpectedException( MWException::class, 'Invalid fallback mode "7"' );

		$callee = $this->getCallee( [ 'expectedGets' => 0 ] );

		// These should not throw, because of short-circuiting. If they do, it will fail the test,
		// because we pass 5 and 6 instead of 7.
		$this->callMethod( $callee, 'getFallbacksFor', 'en', 5 );
		$this->callMethod( $callee, 'getFallbacksFor', '!!!', 6 );

		// This is the one that should throw.
		$this->callMethod( $callee, 'getFallbacksFor', 'fr', 7 );
	}

	/**
	 * @param string $code
	 * @param string $siteLangCode
	 * @param array $expected
	 * @param int $expectedGets
	 * @dataProvider provideGetFallbacksIncludingSiteLanguage
	 * @covers ::getFallbacksIncludingSiteLanguage
	 * @covers Language::getFallbacksIncludingSiteLanguage
	 */
	public function testGetFallbacksIncludingSiteLanguage(
		$code, $siteLangCode, array $expected, $expectedGets = 1
	) {
		$callee = $this->getCallee(
			[ 'siteLangCode' => $siteLangCode, 'expectedGets' => $expectedGets ] );
		$this->assertSame( $expected,
			$this->callMethod( $callee, 'getFallbacksIncludingSiteLanguage', $code ) );

		// Call again to make sure we don't call LocalisationCache again
		$this->callMethod( $callee, 'getFallbacksIncludingSiteLanguage', $code );
	}

	public static function provideGetFallbacksIncludingSiteLanguage() {
		return [
			'en on en' => [ 'en', 'en', [ [], [ 'en' ] ], 0 ],
			'fr on en' => [ 'fr', 'en', [ [ 'en' ], [] ] ],
			'en on fr' => [ 'en', 'fr', [ [], [ 'fr', 'en' ] ] ],
			'fr on fr' => [ 'fr', 'fr', [ [ 'en' ], [ 'fr' ] ] ],

			'sco on en' => [ 'sco', 'en', [ [ 'en' ], [] ] ],
			'en on sco' => [ 'en', 'sco', [ [], [ 'sco', 'en' ] ] ],
			'sco on sco' => [ 'sco', 'sco', [ [ 'en' ], [ 'sco' ] ] ],

			'fr on sco' => [ 'fr', 'sco', [ [ 'en' ], [ 'sco' ] ], 2 ],
			'sco on fr' => [ 'sco', 'fr', [ [ 'en' ], [ 'fr' ] ], 2 ],

			'fr on yi' => [ 'fr', 'yi', [ [ 'en' ], [ 'yi', 'he' ] ], 2 ],
			'yi on fr' => [ 'yi', 'fr', [ [ 'he', 'en' ], [ 'fr' ] ], 2 ],
			'yi on yi' => [ 'yi', 'yi', [ [ 'he', 'en' ], [ 'yi' ] ] ],

			'sh on ruq' => [ 'sh', 'ruq',
				[ [ 'bs', 'sr-el', 'hr', 'en' ], [ 'ruq', 'ruq-latn', 'ro' ] ], 2 ],
			'ruq on sh' => [ 'ruq', 'sh',
				[ [ 'ruq-latn', 'ro', 'en' ], [ 'sh', 'bs', 'sr-el', 'hr' ] ], 2 ],
		];
	}
}
