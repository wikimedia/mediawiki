<?php

use MediaWiki\Languages\LanguageFallback;

/**
 * @internal For LanguageFallbackTest and LanguageFallbackIntegrationTest
 */
trait LanguageFallbackTestTrait {
	/**
	 * @param array $options Valid keys:
	 *   * expectedGets: How many times we expect to hit the localisation cache. (This can be
	 *   ignored in integration tests -- it's enough to test in unit tests.)
	 *   * fallbackMap: A map of language codes to fallback sequences to use.
	 *   * siteLangCode
	 * @return LanguageFallback
	 */
	abstract protected function getCallee( array $options = [] ): LanguageFallback;

	/**
	 * @param int $expectedGets How many times it's expected that 'getItem' will be called
	 * @param array $map Map language codes to fallback arrays to return
	 * @return LocalisationCache
	 */
	protected function getMockLocalisationCache( $expectedGets, $map ) {
		$mockLocCache = $this->createNoOpMock( LocalisationCache::class, [ 'getItem' ] );
		$mockLocCache->expects( $this->exactly( $expectedGets ) )->method( 'getItem' )
			->with( $this->anything(),
				$this->logicalOr( 'fallbackSequence', 'originalFallbackSequence' ) )
			->willReturnCallback( static function ( $code, $key ) use ( $map ) {
				if ( $key === 'originalFallbackSequence' || $code === 'en' ) {
					return $map[$code];
				}
				$fallbacks = $map[$code];
				if ( !$fallbacks || $fallbacks[count( $fallbacks ) - 1] !== 'en' ) {
					$fallbacks[] = 'en';
				}
				return $fallbacks;
			} );
		return $mockLocCache;
	}

	/**
	 * @param string $code
	 * @param array $expected
	 * @param array $options
	 * @dataProvider provideGetAll
	 */
	public function testGetFirst( $code, array $expected, array $options = [] ) {
		$callee = $this->getCallee( $options );
		$this->assertSame( $expected[0] ?? null,
			$callee->getFirst( $code )
		);
	}

	/**
	 * @param string $code
	 * @param array $expected
	 * @param array $options
	 * @dataProvider provideGetAll
	 */
	public function testGetAll( $code, array $expected, array $options = [] ) {
		$this->assertSame( $expected,
			$this->getCallee( $options )->getAll( $code )
		);
	}

	/**
	 * @param string $code
	 * @param array $expected
	 * @param array $options
	 * @dataProvider provideGetAll
	 */
	public function testGetAll_messages( $code, array $expected, array $options = [] ) {
		$this->assertSame( $expected,
			$this->getCallee( $options )->getAll( $code, LanguageFallback::MESSAGES )
		);
	}

	public static function provideGetAll() {
		return [
			'en' => [ 'en', [], [ 'expectedGets' => 0 ] ],
			'fr' => [ 'fr', [ 'en' ] ],
			'sco' => [ 'sco', [ 'en' ] ],
			'yi' => [ 'yi', [ 'he', 'en' ] ],
			'ruq' => [ 'ruq', [ 'ruq-latn', 'ro', 'en' ] ],
			'sh' => [ 'sh', [ 'sh-latn', 'bs', 'hr', 'sr-latn', 'sr-el', 'sh-cyrl', 'sr-cyrl', 'sr-ec', 'en' ] ],
		];
	}

	/**
	 * @param string $code
	 * @param array $expected
	 * @param array $options
	 * @dataProvider provideGetAll_strict
	 */
	public function testGetAll_strict( $code, array $expected, array $options = [] ) {
		$this->assertSame( $expected,
			$this->getCallee( $options )->getAll( $code, LanguageFallback::STRICT )
		);
	}

	public static function provideGetAll_strict() {
		return [
			'en' => [ 'en', [], [ 'expectedGets' => 0 ] ],
			'fr' => [ 'fr', [] ],
			'sco' => [ 'sco', [ 'en' ] ],
			'yi' => [ 'yi', [ 'he' ] ],
			'ruq' => [ 'ruq', [ 'ruq-latn', 'ro' ] ],
			'sh' => [ 'sh', [ 'sh-latn', 'bs', 'hr', 'sr-latn', 'sr-el', 'sh-cyrl', 'sr-cyrl', 'sr-ec' ] ],
		];
	}

	public function testGetAll_invalidMode() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid fallback mode "7"' );

		$callee = $this->getCallee( [ 'expectedGets' => 0 ] );

		// These should not throw, because of short-circuiting. If they do, it will fail the test,
		// because we pass 5 and 6 instead of 7.
		$callee->getAll( 'en', 5 );
		$callee->getAll( '!!!', 6 );

		// This is the one that should throw.
		$callee->getAll( 'fr', 7 );
	}

	/**
	 * @param string $code
	 * @param string $siteLangCode
	 * @param array $expected
	 * @param int $expectedGets
	 * @dataProvider provideGetAllIncludingSiteLanguage
	 */
	public function testGetAllIncludingSiteLanguage(
		$code, $siteLangCode, array $expected, $expectedGets = 1
	) {
		$callee = $this->getCallee(
			[ 'siteLangCode' => $siteLangCode, 'expectedGets' => $expectedGets ] );
		$this->assertSame( $expected,
			$callee->getAllIncludingSiteLanguage( $code )
		);

		// Call again to make sure we don't call LocalisationCache again
		$callee->getAllIncludingSiteLanguage( $code );
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
				[ [ 'sh-latn', 'bs', 'hr', 'sr-latn', 'sr-el', 'sh-cyrl', 'sr-cyrl', 'sr-ec', 'en' ], [ 'ruq', 'ruq-latn', 'ro' ] ], 2 ],
			'ruq on sh' => [ 'ruq', 'sh',
				[ [ 'ruq-latn', 'ro', 'en' ], [ 'sh', 'sh-latn', 'bs', 'hr', 'sr-latn', 'sr-el', 'sh-cyrl', 'sr-cyrl', 'sr-ec' ] ], 2 ],
		];
	}
}
