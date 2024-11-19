<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Tests\Api\ApiTestCase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group API
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiQueryLanguageinfo
 */
class ApiQueryLanguageinfoTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();
		// register custom language names so this test is independent of CLDR
		$this->setTemporaryHook(
			'LanguageGetTranslatedLanguageNames',
			static function ( array &$names, $code ) {
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

	private function doQuery( array $params ): array {
		$params += [
			'action' => 'query',
			'meta' => 'languageinfo',
			'uselang' => 'en',
		];

		$res = $this->doApiRequest( $params );

		$this->assertArrayNotHasKey( 'warnings', $res[0] );

		return [ $res[0]['query']['languageinfo'], $res[0]['continue'] ?? null ];
	}

	public static function provideTestAllPropsForSingleLanguage() {
		yield [
			'sr',
			[
				'code' => 'sr',
				'bcp47' => 'sr',
				'autonym' => 'српски / srpski',
				'name' => 'српски / srpski',
				'fallbacks' => [ 'sr-ec', 'sr-cyrl', 'sr-el', 'sr-latn' ],
				'dir' => 'ltr',
				'variants' => [ 'sr', 'sr-ec', 'sr-el' ],
				'variantnames' => [
					'sr' => 'Ћир./lat.',
					'sr-ec' => 'Ћирилица',
					'sr-el' => 'Latinica',
				],
			]
		];

		yield [
			'qtp', // reserved for local use by ISO 639; registered in setUp()
			[
				'code' => 'qtp',
				'bcp47' => 'qtp',
				'autonym' => '',
				'name' => 'a custom language code MediaWiki knows nothing about',
				'fallbacks' => [],
				'dir' => 'ltr',
				'variants' => [ 'qtp' ],
				'variantnames' => [ 'qtp' => 'qtp' ],
			]
		];
	}

	/**
	 * @dataProvider provideTestAllPropsForSingleLanguage
	 */
	public function testAllPropsForSingleLanguage( string $langCode, array $expected ) {
		[ $response, $continue ] = $this->doQuery( [
			'liprop' => 'code|bcp47|dir|autonym|name|fallbacks|variants|variantnames',
			'licode' => $langCode,
		] );

		$this->assertArrayEquals( [ $langCode => $expected ], $response );
	}

	public function testNameInOtherLanguageForSingleLanguage() {
		[ $response, $continue ] = $this->doQuery( [
			'liprop' => 'name',
			'licode' => 'de',
			'uselang' => 'pt',
		] );

		$this->assertArrayEquals( [ 'de' => [ 'name' => 'alemão' ] ], $response );
	}

	/**
	 * Test ensures continuation is applied if the test runs for longer than allowed
	 *
	 * ApiQueryLanguageinfo::MAX_EXECUTE_SECONDS controls the speed the API has to have before
	 * applying continuation.
	 *
	 * @see T329609#8613954
	 */
	public function testContinuationNecessary() {
		$time = 0;
		ConvertibleTimestamp::setFakeTime( static function () use ( &$time ) {
			$time++;
			return $time;
		} );

		[ $response, $continue ] = $this->doQuery( [] );

		$this->assertCount( 2, $response );
		$this->assertArrayHasKey( 'licontinue', $continue );
	}

	/**
	 * Test ensures continuation is applied if the test runs for longer than allowed
	 *
	 * ApiQueryLanguageinfo::MAX_EXECUTE_SECONDS controls the speed the API has to have before
	 * applying continuation.
	 *
	 * @see T329609#8613954
	 */
	public function testContinuationNotNecessary() {
		$time = 0;
		ConvertibleTimestamp::setFakeTime( static function () use ( &$time ) {
			$time += 2;
			return $time;
		} );

		[ $response, $continue ] = $this->doQuery( [
			'licode' => 'de',
		] );

		$this->assertNull( $continue );
	}

	public function testContinuationInAlphabeticalOrderNotParameterOrder() {
		$time = 0;
		ConvertibleTimestamp::setFakeTime( static function () use ( &$time ) {
			$time++;
			return $time;
		} );
		$params = [ 'licode' => 'en|ru|zh|de|yue' ];

		[ $response, $continue ] = $this->doQuery( $params );

		$this->assertCount( 2, $response );
		$this->assertArrayHasKey( 'licontinue', $continue );
		$this->assertSame( [ 'de', 'en' ], array_keys( $response ) );

		$time = 0;
		$params = $continue + $params;
		[ $response, $continue ] = $this->doQuery( $params );

		$this->assertCount( 2, $response );
		$this->assertArrayHasKey( 'licontinue', $continue );
		$this->assertSame( [ 'ru', 'yue' ], array_keys( $response ) );

		$time = 0;
		$params = $continue + $params;
		[ $response, $continue ] = $this->doQuery( $params );

		$this->assertCount( 1, $response );
		$this->assertNull( $continue );
		$this->assertSame( [ 'zh' ], array_keys( $response ) );
	}

	public function testResponseHasModulePathEvenIfEmpty() {
		[ $response, $continue ] = $this->doQuery( [ 'licode' => '' ] );
		$this->assertSame( [], $response );
		// the real test is that $res[0]['query']['languageinfo'] in doQuery() didn’t fail
	}

}
