<?php

use MediaWiki\MainConfigNames;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Media
 * @requires extension exif
 * @covers \FormatMetadata
 */
class FormatMetadataTest extends MediaWikiMediaTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => 'en',
			MainConfigNames::ShowEXIF => true,
		] );
	}

	/**
	 * @covers \MediaWiki\FileRepo\File\File::formatMetadata
	 */
	public function testInvalidDate() {
		$file = $this->dataFile( 'broken_exif_date.jpg', 'image/jpeg' );

		// Throws an error if bug hit
		$meta = $file->formatMetadata();
		$this->assertIsArray( $meta, 'Valid metadata extracted' );

		// Find date exif entry
		$this->assertArrayHasKey( 'visible', $meta );
		$dateIndex = null;
		foreach ( $meta['visible'] as $i => $data ) {
			if ( $data['id'] == 'exif-datetimeoriginal' ) {
				$dateIndex = $i;
			}
		}
		$this->assertNotNull( $dateIndex, 'Date entry exists in metadata' );
		$this->assertSame( '0000:01:00 00:02:27',
			$meta['visible'][$dateIndex]['value'],
			'File with invalid date metadata (T31471)' );
	}

	/**
	 * @dataProvider provideResolveMultivalueValue
	 */
	public function testResolveMultivalueValue( $input, $output ) {
		$formatMetadata = new FormatMetadata();
		$class = new ReflectionClass( FormatMetadata::class );
		$method = $class->getMethod( 'resolveMultivalueValue' );
		$method->setAccessible( true );
		$actualInput = $method->invoke( $formatMetadata, $input );
		$this->assertEquals( $output, $actualInput );
	}

	public static function provideResolveMultivalueValue() {
		return [
			'nonArray' => [
				'foo',
				'foo'
			],
			'multiValue' => [
				[ 'first', 'second', 'third', '_type' => 'ol' ],
				'first'
			],
			'noType' => [
				[ 'first', 'second', 'third' ],
				'first'
			],
			'typeFirst' => [
				[ '_type' => 'ol', 'first', 'second', 'third' ],
				'first'
			],
			'multilang' => [
				[
					'en' => 'first',
					'de' => 'Erste',
					'_type' => 'lang'
				],
				[
					'en' => 'first',
					'de' => 'Erste',
					'_type' => 'lang'
				],
			],
			'multilang-multivalue' => [
				[
					'en' => [ 'first', 'second' ],
					'de' => [ 'Erste', 'Zweite' ],
					'_type' => 'lang'
				],
				[
					'en' => 'first',
					'de' => 'Erste',
					'_type' => 'lang'
				],
			],
		];
	}

	/**
	 * @dataProvider provideGetFormattedData
	 */
	public function testGetFormattedData( $input, $output ) {
		$this->assertEquals( $output, FormatMetadata::getFormattedData( $input ) );
	}

	public static function provideGetFormattedData() {
		return [
			[
				[ 'Software' => 'Adobe Photoshop CS6 (Macintosh)' ],
				[ 'Software' => 'Adobe Photoshop CS6 (Macintosh)' ],
			],
			[
				[ 'Software' => [ 'FotoWare FotoStation' ] ],
				[ 'Software' => 'FotoWare FotoStation' ],
			],
			[
				[ 'Software' => [ [ 'Capture One PRO', '3.7.7' ] ] ],
				[ 'Software' => 'Capture One PRO (Version 3.7.7)' ],
			],
			[
				[ 'Software' => [ [ 'FotoWare ColorFactory', '' ] ] ],
				[ 'Software' => 'FotoWare ColorFactory (Version )' ],
			],
			[
				[ 'Software' => [ 'x-default' => 'paint.net 4.0.12', '_type' => 'lang' ] ],
				[ 'Software' => '<ul class="metadata-langlist">' .
						'<li class="mw-metadata-lang-default">' .
							'<span class="mw-metadata-lang-value">paint.net 4.0.12</span>' .
						"</li>\n" .
					'</ul>'
				],
			],
			[
				// https://phabricator.wikimedia.org/T178130
				// WebMHandler.php turns both 'muxingapp' & 'writingapp' to 'Software'
				[ 'Software' => [ [ 'Lavf57.25.100' ], [ 'Lavf57.25.100' ] ] ],
				[ 'Software' => "<ul><li>Lavf57.25.100</li>\n<li>Lavf57.25.100</li></ul>" ],
			],
		];
	}

	/**
	 * @dataProvider provideGetPriorityLanguagesData
	 * @param string $language
	 * @param string[] $expected
	 */
	public function testGetPriorityLanguagesInternal_language_expect(
		string $language,
		array $expected
	): void {
		$formatMetadata = TestingAccessWrapper::newFromObject( new FormatMetadata() );
		$context = $formatMetadata->getContext();
		$context->setLanguage( $this->getServiceContainer()->getLanguageFactory()->getLanguage( $language ) );

		$x = $formatMetadata->getPriorityLanguages();
		$this->assertSame( $expected, $x );
	}

	public static function provideGetPriorityLanguagesData() {
		return [
			'LanguageMl' => [
				'ml',
				[ 'ml', 'en' ],
			],
			'LanguageEn' => [
				'en',
				[ 'en', 'en' ],
			],
			'LanguageQqx' => [
				'qqx',
				[ 'qqx', 'en' ],
			],
		];
	}
}
