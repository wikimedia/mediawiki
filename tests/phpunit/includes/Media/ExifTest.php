<?php

use MediaWiki\MainConfigNames;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Media
 * @covers \Exif
 * @requires extension exif
 */
class ExifTest extends MediaWikiIntegrationTestCase {
	private const FILE_PATH = __DIR__ . '/../../data/media/';

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::ShowEXIF, true );
	}

	public function testGPSExtraction() {
		$filename = self::FILE_PATH . 'exif-gps.jpg';
		$seg = JpegMetadataExtractor::segmentSplitter( $filename );
		$exif = new Exif( $filename, $seg['byteOrder'] );
		$data = $exif->getFilteredData();
		$expected = [
			'GPSLatitude' => 88.5180555556,
			'GPSLongitude' => -21.12357,
			'GPSAltitude' => -3.141592653,
			'GPSDOP' => '5/1',
			'GPSVersionID' => '2.2.0.0',
		];
		$this->assertEqualsWithDelta( $expected, $data, 0.0000000001 );
	}

	/**
	 * Test GPS coordinate conversion with different formats and directions
	 * @dataProvider provideGPSCoordinates
	 */
	public function testExifGPSCoordinateConversion( $mockData, $expectedResult ) {
		// We need state to override
		$filename = self::FILE_PATH . 'exif-gps.jpg';
		$exif = TestingAccessWrapper::newFromObject( new Exif( $filename, 'BE' ) );

		$exif->mRawExifData = [ 'GPS' => $mockData ];
		$exif->makeFilteredData();
		$exif->collapseData();
		$data = $exif->getFilteredData();

		if ( empty( $expectedResult ) ) {
			$this->assertSame( $expectedResult, $data, 'Expected an empty array' );
		} else {
			$firstKey = array_key_first( $expectedResult );
			$this->assertEqualsWithDelta(
				$expectedResult[$firstKey],
				$data[$firstKey],
				0.0000000001
			);
		}
	}

	/**
	 * Data provider for GPS coordinate conversion tests
	 */
	public function provideGPSCoordinates() {
		return [
			'North latitude' => [
				[
					'GPSLatitude' => [ '10/1', '20/1', '30/1' ],
					'GPSLatitudeRef' => 'N'
				],
				[ 'GPSLatitude' => 10.341666666666667 ]
			],
			'South latitude' => [
				[
					'GPSLatitude' => [ '10/1', '20/1', '30/1' ],
					'GPSLatitudeRef' => 'S'
				],
				[ 'GPSLatitude' => -10.341666666666667 ]
			],
			'East longitude' => [
				[
					'GPSLongitude' => [ '10/1', '20/1', '30/1' ],
					'GPSLongitudeRef' => 'E'
				],
				[ 'GPSLongitude' => 10.341666666666667 ]
			],
			'West longitude' => [
				[
					'GPSLongitude' => [ '10/1', '20/1', '30/1' ],
					'GPSLongitudeRef' => 'W'
				],
				[ 'GPSLongitude' => -10.341666666666667 ]
			],
			'Non-standard degrees format - North latitude' => [
				[
					'GPSLatitude' => '45/1',
					'GPSLatitudeRef' => 'N'
				],
				[ 'GPSLatitude' => 45.0 ]
			],
			'Non-standard degrees format - West longitude' => [
				[
					'GPSLongitude' => '100/1',
					'GPSLongitudeRef' => 'W'
				],
				[ 'GPSLongitude' => -100.0 ]
			],
			'Zero denominator should be handled gracefully' => [
				[
					'GPSLatitude' => [ '10/0', '20/1', '30/1' ],
					'GPSLatitudeRef' => 'N'
				],
				[]
			],
			'Missing direction reference should be ignored' => [
				[
					'GPSLatitude' => [ '10/1', '20/1', '30/1' ]
				],
				[]
			],
			'Invalid direction should be ignored' => [
				[
					'GPSLatitude' => [ '10/1', '20/1', '30/1' ],
					'GPSLatitudeRef' => 'X'
				],
				[]
			],
			'Zero degrees test' => [
				[
					'GPSLatitude' => [ '0/1', '20/1', '30/1' ],
					'GPSLatitudeRef' => 'N'
				],
				[ 'GPSLatitude' => 0.3416666666666667 ]
			],
			'Extreme latitude - North Pole' => [
				[
					'GPSLatitude' => [ '90/1', '0/1', '0/1' ],
					'GPSLatitudeRef' => 'N'
				],
				[ 'GPSLatitude' => 90.0 ]
			],
			'Large DMS degrees for latitude' => [
				[
				'GPSLatitude' => [ '120/1', '0/1', '0/1' ],
				'GPSLatitudeRef' => 'N'
				],
				[ 'GPSLatitude' => 120.0 ]
			],
			'Large non-standard degrees for longitude' => [
				[
					'GPSLongitude' => '400/1',
					'GPSLongitudeRef' => 'E'
				],
				[ 'GPSLongitude' => 400.0 ]
			],
			'Large non-standard degrees for latitude' => [
				[
					'GPSLatitude' => '200/1',
					'GPSLatitudeRef' => 'N'
				],
				[ 'GPSLatitude' => 200.0 ]
			],
			'Negative value in non-standard degrees should be ignored' => [
				[
					'GPSLatitude' => '-45/1',
					'GPSLatitudeRef' => 'N'
				],
				[]
			],
			'Wrong array size - 2 elements instead of 3' => [
				[
					'GPSLatitude' => [ '10/1', '20/1' ],
					'GPSLatitudeRef' => 'N'
				],
				[]
			],
			'Non-fraction value in DMS format' => [
				[
					'GPSLatitude' => [ '10', '20/1', '30/1' ],
					'GPSLatitudeRef' => 'N'
				],
				[]
			],
		];
	}

	public function testUnicodeUserComment() {
		$filename = self::FILE_PATH . 'exif-user-comment.jpg';
		$seg = JpegMetadataExtractor::segmentSplitter( $filename );
		$exif = new Exif( $filename, $seg['byteOrder'] );
		$data = $exif->getFilteredData();

		$expected = [
			'UserComment' => 'test⁔comment',
		];
		$this->assertEquals( $expected, $data );
	}
}
