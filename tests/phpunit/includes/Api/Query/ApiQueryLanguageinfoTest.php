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
				'digittransforms' => [],
				'digitgroupingpattern' => '#,##0.###',
				'minimumgroupingdigits' => 1,
				'namespacenames' => [
					-2 => 'Медиј',
					-1 => 'Посебно',
					0 => '',
					1 => 'Разговор',
					2 => 'Корисник',
					3 => 'Разговор_с_корисником',
					4 => 'TestWiki',
					5 => 'Разговор_о_TestWiki',
					6 => 'Датотека',
					7 => 'Разговор_о_датотеци',
					8 => 'Медијавики',
					9 => 'Разговор_о_Медијавикију',
					10 => 'Шаблон',
					11 => 'Разговор_о_шаблону',
					12 => 'Помоћ',
					13 => 'Разговор_о_помоћи',
					14 => 'Категорија',
					15 => 'Разговор_о_категорији',
				],
				'namespacealiases' => [
					'Медија' => -2,
					'Сарадник' => 2,
					'Сурадник' => 2,
					'Разговор_са_корисником' => 3,
					'Разговор_са_сарадником' => 3,
					'Разговор_с_сарадником' => 3,
					'Разговор_са_сурадником' => 3,
					'Разговор_с_сурадником' => 3,
					'Специјално' => -1,
					'Фотографија' => 6,
					'Слика' => 6,
					'Разговор_о_фотографији' => 7,
					'Разговор_о_слици' => 7,
					'МедијаВики' => 8,
					'Разговор_о_МедијаВикију' => 9,
					'Разговор_о_MediaWiki-ју' => 9,
					'Предложак' => 10,
					'Разговор_о_предлошку' => 11,
					'Medija' => -2,
					'Specijalno' => -1,
					'Saradnik' => 2,
					'Suradnik' => 2,
					'Razgovor_sa_korisnikom' => 3,
					'Razgovor_sa_saradnikom' => 3,
					'Razgovor_s_saradnikom' => 3,
					'Razgovor_sa_suradnikom' => 3,
					'Razgovor_s_suradnikom' => 3,
					'Fotografija' => 6,
					'Slika' => 6,
					'Razgovor_o_fotografiji' => 7,
					'Razgovor_o_slici' => 7,
					'MedijaViki' => 8,
					'MedijaWiki' => 8,
					'Razgovor_o_MedijaVikiju' => 9,
					'Razgovor_o_MedijaViki-ju' => 9,
					'Razgovor_o_MediaWiki-ju' => 9,
					'Razgovor_o_MediaWikiju' => 9,
					'Razgovor_o_MedijaWiki-ju' => 9,
					'Razgovor_o_MedijaWikiju' => 9,
					'Razgovor_o_Medijaviki-ju' => 9,
					'Predložak' => 10,
					'Razgovor_o_predlošku' => 11,
					'Image' => 6,
					'Image_talk' => 7,
					'Медиј' => -2,
					'Посебно' => -1,
					'' => 0,
					'Разговор' => 1,
					'Корисник' => 2,
					'Разговор с корисником' => 3,
					'TestWiki' => 4,
					'Разговор о TestWiki' => 5,
					'Датотека' => 6,
					'Разговор о датотеци' => 7,
					'Медијавики' => 8,
					'Разговор о Медијавикију' => 9,
					'Шаблон' => 10,
					'Разговор о шаблону' => 11,
					'Помоћ' => 12,
					'Разговор о помоћи' => 13,
					'Категорија' => 14,
					'Разговор о категорији' => 15,
					'Medij' => -2,
					'Posebno' => -1,
					'Razgovor' => 1,
					'Korisnik' => 2,
					'Razgovor s korisnikom' => 3,
					'Razgovor o TestWiki' => 5,
					'Datoteka' => 6,
					'Razgovor o datoteci' => 7,
					'Medijaviki' => 8,
					'Razgovor o Medijavikiju' => 9,
					'Šablon' => 10,
					'Razgovor o šablonu' => 11,
					'Pomoć' => 12,
					'Razgovor o pomoći' => 13,
					'Kategorija' => 14,
					'Razgovor o kategoriji' => 15,
				]
			]
		];

		# this language has interesting grouping patterns
		yield [
			'hi',
			[
				'code' => 'hi',
				'bcp47' => 'hi',
				'autonym' => 'हिन्दी',
				'name' => 'हिन्दी',
				'fallbacks' => [],
				'dir' => 'ltr',
				'variants' => [ 'hi' ],
				'variantnames' => [
					'hi' => 'हिन्दी',
				],
				'digittransforms' => [
					0 => '०',
					1 => '१',
					2 => '२',
					3 => '३',
					4 => '४',
					5 => '५',
					6 => '६',
					7 => '७',
					8 => '८',
					9 => '९',
				],
				'digitgroupingpattern' => '#,##,##0.###',
				'minimumgroupingdigits' => 1,
				'namespacenames' => [
					-2 => 'मीडिया',
					-1 => 'विशेष',
					2 => 'सदस्य',
					3 => 'सदस्य_वार्ता',
					0 => '',
					1 => 'वार्ता',
					4 => 'TestWiki',
					5 => 'TestWiki_वार्ता',
					6 => 'चित्र',
					7 => 'चित्र_वार्ता',
					8 => 'मीडियाविकि',
					9 => 'मीडियाविकि_वार्ता',
					10 => 'साँचा',
					11 => 'साँचा_वार्ता',
					12 => 'सहायता',
					13 => 'सहायता_वार्ता',
					14 => 'श्रेणी',
					15 => 'श्रेणी_वार्ता',
				],
				'namespacealiases' => [
					'Image' => 6,
					'Image_talk' => 7,
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
				'digittransforms' => [],
				'digitgroupingpattern' => '#,##0.###',
				'minimumgroupingdigits' => 1,
				'namespacenames' => [
					-2 => 'Media',
					-1 => 'Special',
					0 => '',
					1 => 'Talk',
					2 => 'User',
					3 => 'User_talk',
					4 => 'TestWiki',
					5 => 'TestWiki_talk',
					6 => 'File',
					7 => 'File_talk',
					8 => 'MediaWiki',
					9 => 'MediaWiki_talk',
					10 => 'Template',
					11 => 'Template_talk',
					12 => 'Help',
					13 => 'Help_talk',
					14 => 'Category',
					15 => 'Category_talk',
				],
				'namespacealiases' => [
					'Image' => 6,
					'Image_talk' => 7,
				],
			]
		];
	}

	/**
	 * @dataProvider provideTestAllPropsForSingleLanguage
	 */
	public function testAllPropsForSingleLanguage( string $langCode, array $expected ) {
		[ $response, $continue ] = $this->doQuery( [
			'liprop' => 'code|bcp47|dir|autonym|name|fallbacks|variants|variantnames|' .
				'digittransforms|digitgroupingpattern|minimumgroupingdigits|namespacenames|namespacealiases',
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
