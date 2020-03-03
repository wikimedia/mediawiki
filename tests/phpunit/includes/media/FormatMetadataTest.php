<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group Media
 */
class FormatMetadataTest extends MediaWikiMediaTestCase {

	protected function setUp() : void {
		parent::setUp();

		$this->checkPHPExtension( 'exif' );
		$this->setMwGlobals( 'wgShowEXIF', true );
	}

	/**
	 * @covers File::formatMetadata
	 */
	public function testInvalidDate() {
		$file = $this->dataFile( 'broken_exif_date.jpg', 'image/jpeg' );

		// Throws an error if bug hit
		$meta = $file->formatMetadata();
		$this->assertNotEquals( false, $meta, 'Valid metadata extracted' );

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
	 * @param mixed $input
	 * @param mixed $output
	 * @dataProvider provideResolveMultivalueValue
	 * @covers FormatMetadata::resolveMultivalueValue
	 */
	public function testResolveMultivalueValue( $input, $output ) {
		$formatMetadata = new FormatMetadata();
		$class = new ReflectionClass( FormatMetadata::class );
		$method = $class->getMethod( 'resolveMultivalueValue' );
		$method->setAccessible( true );
		$actualInput = $method->invoke( $formatMetadata, $input );
		$this->assertEquals( $output, $actualInput );
	}

	public function provideResolveMultivalueValue() {
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
	 * @param mixed $input
	 * @param mixed $output
	 * @dataProvider provideGetFormattedData
	 * @covers FormatMetadata::getFormattedData
	 */
	public function testGetFormattedData( $input, $output ) {
		$this->assertEquals( $output, FormatMetadata::getFormattedData( $input ) );
	}

	public function provideGetFormattedData() {
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
	 * @covers FormatMetadata::getPriorityLanguages
	 * @dataProvider provideGetPriorityLanguagesData
	 * @param string $languageClass
	 * @param string[] $expected
	 */
	public function testGetPriorityLanguagesInternal_language_expect(
		string $languageClass,
		array $expected
	): void {
		$formatMetadata = TestingAccessWrapper::newFromObject( new FormatMetadata() );
		$context = $formatMetadata->getContext();
		$context->setLanguage( new $languageClass() );

		$x = $formatMetadata->getPriorityLanguages();
		$this->assertSame( $expected, $x );
	}

	public function provideGetPriorityLanguagesData() {
		return [
			'LanguageMl' => [
				LanguageMl::class,
				[ 'ml', 'en' ],
			],
			'LanguageEn' => [
				LanguageEn::class,
				[ 'en', 'en' ],
			],
			'LanguageQqx' => [
				LanguageQqx::class,
				[ 'qqx', 'en' ],
			],
		];
	}
}
