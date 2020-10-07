<?php

use MediaWiki\MediaWikiServices;

/**
 * @group API
 * @group medium
 *
 * @covers ApiQueryLanguageinfo
 */
class ApiQueryLanguageinfoTest extends ApiTestCase {

	protected function setUp() : void {
		parent::setUp();
		// register custom language names so this test is independent of CLDR
		$this->setTemporaryHook(
			'LanguageGetTranslatedLanguageNames',
			function ( array &$names, $code ) {
				switch ( $code ) {
					case 'en':
						$names['sh'] = 'Serbo-Croatian';
						$names['qtp'] = 'a custom language code MediaWiki knows nothing about';
						break;
					case 'pt':
						$names['de'] = 'alemão';
						break;
				}
			}
		);
	}

	private function doQuery( array $params, $microtimeFunction = null ): array {
		$params += [
			'action' => 'query',
			'meta' => 'languageinfo',
			'uselang' => 'en',
		];

		if ( $microtimeFunction !== null ) {
			// hook into the module manager to override the factory function
			// so we can call the constructor with the custom $microtimeFunction
			$this->setTemporaryHook(
				'ApiQuery::moduleManager',
				function ( ApiModuleManager $moduleManager ) use ( $microtimeFunction ) {
					$moduleManager->addModule(
						'languageinfo',
						'meta',
						[
							'class' => ApiQueryLanguageinfo::class,
							'factory' => function ( $parent, $name ) use ( $microtimeFunction ) {
								$services = MediaWikiServices::getInstance();
								return new ApiQueryLanguageinfo(
									$parent,
									$name,
									$services->getLanguageFactory(),
									$services->getLanguageNameUtils(),
									$services->getLanguageFallback(),
									$services->getLanguageConverterFactory(),
									$microtimeFunction
								);
							}
						]
					);
				}
			);
		}

		$res = $this->doApiRequest( $params );

		$this->assertArrayNotHasKey( 'warnings', $res[0] );

		return [ $res[0]['query']['languageinfo'], $res[0]['continue'] ?? null ];
	}

	public function testAllPropsForSingleLanguage() {
		list( $response, $continue ) = $this->doQuery( [
			'liprop' => 'code|bcp47|dir|autonym|name|fallbacks|variants',
			'licode' => 'sh',
		] );

		$this->assertArrayEquals( [
			'sh' => [
				'code' => 'sh',
				'bcp47' => 'sh',
				'autonym' => 'srpskohrvatski / српскохрватски',
				'name' => 'Serbo-Croatian',
				'fallbacks' => [ 'bs', 'sr-el', 'hr' ],
				'dir' => 'ltr',
				'variants' => [ 'sh' ],
			],
		], $response );
	}

	public function testAllPropsForSingleCustomLanguage() {
		list( $response, $continue ) = $this->doQuery( [
			'liprop' => 'code|bcp47|dir|autonym|name|fallbacks|variants',
			'licode' => 'qtp', // reserved for local use by ISO 639; registered in setUp()
		] );

		$this->assertArrayEquals( [
			'qtp' => [
				'code' => 'qtp',
				'bcp47' => 'qtp',
				'autonym' => '',
				'name' => 'a custom language code MediaWiki knows nothing about',
				'fallbacks' => [],
				'dir' => 'ltr',
				'variants' => [ 'qtp' ],
			],
		], $response );
	}

	public function testNameInOtherLanguageForSingleLanguage() {
		list( $response, $continue ) = $this->doQuery( [
			'liprop' => 'name',
			'licode' => 'de',
			'uselang' => 'pt',
		] );

		$this->assertArrayEquals( [ 'de' => [ 'name' => 'alemão' ] ], $response );
	}

	public function testContinuationNecessary() {
		$time = 0;
		$microtimeFunction = function () use ( &$time ) {
			return $time += 0.75;
		};

		list( $response, $continue ) = $this->doQuery( [], $microtimeFunction );

		$this->assertCount( 2, $response );
		$this->assertArrayHasKey( 'licontinue', $continue );
	}

	public function testContinuationNotNecessary() {
		$time = 0;
		$microtimeFunction = function () use ( &$time ) {
			return $time += 1.5;
		};

		list( $response, $continue ) = $this->doQuery( [
			'licode' => 'de',
		], $microtimeFunction );

		$this->assertNull( $continue );
	}

	public function testContinuationInAlphabeticalOrderNotParameterOrder() {
		$time = 0;
		$microtimeFunction = function () use ( &$time ) {
			return $time += 0.75;
		};
		$params = [ 'licode' => 'en|ru|zh|de|yue' ];

		list( $response, $continue ) = $this->doQuery( $params, $microtimeFunction );

		$this->assertCount( 2, $response );
		$this->assertArrayHasKey( 'licontinue', $continue );
		$this->assertSame( [ 'de', 'en' ], array_keys( $response ) );

		$time = 0;
		$params = $continue + $params;
		list( $response, $continue ) = $this->doQuery( $params, $microtimeFunction );

		$this->assertCount( 2, $response );
		$this->assertArrayHasKey( 'licontinue', $continue );
		$this->assertSame( [ 'ru', 'yue' ], array_keys( $response ) );

		$time = 0;
		$params = $continue + $params;
		list( $response, $continue ) = $this->doQuery( $params, $microtimeFunction );

		$this->assertCount( 1, $response );
		$this->assertNull( $continue );
		$this->assertSame( [ 'zh' ], array_keys( $response ) );
	}

	public function testResponseHasModulePathEvenIfEmpty() {
		list( $response, $continue ) = $this->doQuery( [ 'licode' => '' ] );
		$this->assertSame( [], $response );
		// the real test is that $res[0]['query']['languageinfo'] in doQuery() didn’t fail
	}

}
