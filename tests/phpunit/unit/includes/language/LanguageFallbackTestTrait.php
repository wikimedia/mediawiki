<?php

use Wikimedia\Assert\PostconditionException;

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
	 *   * fallbackMap: A map of language codes to fallback sequences to use.
	 *   * siteLangCode
	 * @return string|object Name of class or object with the three methods getFirst, getAll, and
	 *   getAllIncludingSiteLanguage (or getFallbackFor, getFallbacksFor, and
	 *   getFallbacksIncludingSiteLanguage if callMethod() is suitably overridden).
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
	 * @param int $expectedGets How many times it's expected that 'getItem' will be called
	 * @param array $map Map language codes to fallback arrays to return
	 * @return LocalisationCache
	 */
	protected function getMockLocalisationCache( $expectedGets, $map ) {
		$mockLocCache = $this->createMock( LocalisationCache::class );
		$mockLocCache->expects( $this->exactly( $expectedGets ) )->method( 'getItem' )
			->with( $this->anything(),
				$this->logicalOr( 'fallbackSequence', 'originalFallbackSequence' ) )
			->will( $this->returnCallback( function ( $code, $key ) use ( $map ) {
				if ( $key === 'originalFallbackSequence' || $code === 'en' ) {
					return $map[$code];
				}
				$fallbacks = $map[$code];
				if ( !$fallbacks || $fallbacks[count( $fallbacks ) - 1] !== 'en' ) {
					$fallbacks[] = 'en';
				}
				return $fallbacks;
			} ) );
		$mockLocCache->expects( $this->never() )->method( $this->anythingBut( 'getItem' ) );
		return $mockLocCache;
	}

	/**
	 * Convenience/readability wrapper to call a method on a class or object.
	 *
	 * @param string|object $callee As in return value of getCallee()
	 * @param string $method Name of method to call
	 * @param mixed ...$params To pass to method
	 * @return mixed Return value of method
	 */
	public function callMethod( $callee, $method, ...$params ) {
		return [ $callee, $method ]( ...$params );
	}

	/**
	 * @param string $code
	 * @param array $expected
	 * @param array $options
	 * @dataProvider provideGetAll
	 * @covers MediaWiki\Languages\LanguageFallback::getFirst
	 * @covers Language::getFallbackFor
	 */
	public function testGetFirst( $code, array $expected, array $options = [] ) {
		$callee = $this->getCallee( $options );
		// One behavior difference between the old static methods and the new instance methods:
		// returning null instead of false.
		$defaultExpected = is_object( $callee ) ? null : false;
		$this->assertSame( $expected[0] ?? $defaultExpected,
			$this->callMethod( $callee, 'getFirst', $code ) );
	}

	/**
	 * @param string $code
	 * @param array $expected
	 * @param array $options
	 * @dataProvider provideGetAll
	 * @covers MediaWiki\Languages\LanguageFallback::getAll
	 * @covers Language::getFallbacksFor
	 */
	public function testGetAll( $code, array $expected, array $options = [] ) {
		$this->assertSame( $expected,
			$this->callMethod( $this->getCallee( $options ), 'getAll', $code ) );
	}

	/**
	 * @param string $code
	 * @param array $expected
	 * @param array $options
	 * @dataProvider provideGetAll
	 * @covers MediaWiki\Languages\LanguageFallback::getAll
	 * @covers Language::getFallbacksFor
	 */
	public function testGetAll_messages( $code, array $expected, array $options = [] ) {
		$this->assertSame( $expected,
			$this->callMethod( $this->getCallee( $options ), 'getAll',
				$code, $this->getMessagesKey() ) );
	}

	public static function provideGetAll() {
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
	 * @dataProvider provideGetAll_strict
	 * @covers MediaWiki\Languages\LanguageFallback::getAll
	 * @covers Language::getFallbacksFor
	 */
	public function testGetAll_strict( $code, array $expected, array $options = [] ) {
		$this->assertSame( $expected,
			$this->callMethod( $this->getCallee( $options ), 'getAll',
				$code, $this->getStrictKey() ) );
	}

	public static function provideGetAll_strict() {
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
	 * @covers MediaWiki\Languages\LanguageFallback::getAll
	 * @covers Language::getFallbacksFor
	 */
	public function testGetAll_invalidMode() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid fallback mode "7"' );

		$callee = $this->getCallee( [ 'expectedGets' => 0 ] );

		// These should not throw, because of short-circuiting. If they do, it will fail the test,
		// because we pass 5 and 6 instead of 7.
		$this->callMethod( $callee, 'getAll', 'en', 5 );
		$this->callMethod( $callee, 'getAll', '!!!', 6 );

		// This is the one that should throw.
		$this->callMethod( $callee, 'getAll', 'fr', 7 );
	}

	/**
	 * @covers MediaWiki\Languages\LanguageFallback::getAll
	 * @covers Language::getFallbacksFor
	 */
	public function testGetAll_invalidFallback() {
		$callee = $this->getCallee( [ 'fallbackMap' => [ 'qqz' => [ 'fr', 'de', '!!!', 'hi' ] ] ] );

		$this->expectException( PostconditionException::class );
		$this->expectExceptionMessage( "Invalid fallback code '!!!' in fallback sequence for 'qqz'" );
		$this->callMethod( $callee, 'getAll', 'qqz' );
	}

	/**
	 * @covers MediaWiki\Languages\LanguageFallback::getAll
	 * @covers Language::getFallbacksFor
	 */
	public function testGetAll_invalidFallback_strict() {
		$callee = $this->getCallee( [ 'fallbackMap' => [ 'qqz' => [ 'fr', 'de', '!!!', 'hi' ] ] ] );

		$this->expectException( PostconditionException::class );
		$this->expectExceptionMessage( "Invalid fallback code '!!!' in fallback sequence for 'qqz'" );
		$this->callMethod( $callee, 'getAll', 'qqz', $this->getStrictKey() );
	}

	/**
	 * @param string $code
	 * @param string $siteLangCode
	 * @param array $expected
	 * @param int $expectedGets
	 * @dataProvider provideGetAllIncludingSiteLanguage
	 * @covers MediaWiki\Languages\LanguageFallback::getAllIncludingSiteLanguage
	 * @covers Language::getFallbacksIncludingSiteLanguage
	 */
	public function testGetAllIncludingSiteLanguage(
		$code, $siteLangCode, array $expected, $expectedGets = 1
	) {
		$callee = $this->getCallee(
			[ 'siteLangCode' => $siteLangCode, 'expectedGets' => $expectedGets ] );
		$this->assertSame( $expected,
			$this->callMethod( $callee, 'getAllIncludingSiteLanguage', $code ) );

		// Call again to make sure we don't call LocalisationCache again
		$this->callMethod( $callee, 'getAllIncludingSiteLanguage', $code );
	}

	public static function provideGetAllIncludingSiteLanguage() {
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
