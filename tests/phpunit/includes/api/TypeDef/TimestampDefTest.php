<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use ApiMain;
use ApiUsageException;
use MediaWikiLangTestCase;
use MockApi;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @covers MediaWiki\Api\TypeDef\TimestampDef
 */
class TimestampDefTest extends MediaWikiLangTestCase {

	const NOW = '$NOW';

	/** @dataProvider provideValidate */
	public function testValidate( $value, $expect, $options = [] ) {
		$typeDef = new TimestampDef;
		$api = new MockApi;
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mMainModule = new ApiMain;
		$w->mModulePrefix = 'tt';

		$options += [
			'warnings' => [],
		];

		if ( $expect instanceof ApiUsageException ) {
			$this->setExpectedException( ApiUsageException::class, $expect->getMessage() );
			$typeDef->validate( 'foobar', $value, [], $api );
		} else {
			if ( $expect === self::NOW ) {
				// Since the second might change mid-run, grab 'now' before and
				// after the call and assert the returned value is between the two.
				$nowBefore = wfTimestamp( TS_MW );
				$ts = $typeDef->validate( 'foobar', $value, [], $api );
				$nowAfter = wfTimestamp( TS_MW );
				$this->assertGreaterThanOrEqual( $nowBefore, $ts );
				$this->assertLessThanOrEqual( $nowAfter, $ts );
			} else {
				$this->assertSame( $expect, $typeDef->validate( 'foobar', $value, [], $api ) );
			}
			$this->assertEquals( $options['warnings'], $api->warnings );
		}
	}

	public static function provideValidate() {
		return [
			// Test all formats documented in the api-help-datatype-timestamp message
			'ISO format' => [ '2018-02-03T04:05:06Z', '20180203040506' ],
			'ISO format without punctuation' => [ '20180203T040506', '20180203040506' ],
			'ISO format with ms' => [ '2018-02-03T04:05:06.999Z', '20180203040506' ],
			'ISO format with ms without punctuation' => [ '20180203T040506.999', '20180203040506' ],
			'MW format' => [ '20180102030405', '20180102030405' ],
			'Generic format' => [ '2018-01-02 03:04:05', '20180102030405' ],
			'Generic format + TZ' => [ '2018-01-02 03:04:05 GMT', '20180102030405' ],
			'Generic format + TZ (2)' => [ '2018-01-02 03:04:05+01', '20180102030405' ],
			'Generic format + TZ (3)' => [ '2018-01-02 03:04:05-01', '20180102030405' ],
			'Exif format' => [ '2018:01:02 03:04:05', '20180102030405' ],
			'RFC 2822 format' => [ 'Mon, 15 Jan 2001 14:56:00', '20010115145600' ],
			'RFC 2822 format + TZ' => [ 'Mon, 15 Jan 2001 14:56:00 +0100', '20010115145600' ],
			'C ctime format' => [ 'Mon Jan 15 14:56:00 2001', '20010115145600' ],
			'Seconds-since-epoch format' => [ '1517630706', '20180203040506' ],
			'Seconds-since-epoch format (2)' => [ '253402300799', '99991231235959' ],
			'Now' => [ 'now', self::NOW ],

			// Plus some others
			'Empty' => [ '', self::NOW, [
				'warnings' => [ [ 'apiwarn-unclearnowtimestamp', 'ttfoobar', '' ] ],
			] ],
			'Zero' => [ '0', self::NOW, [
				'warnings' => [ [ 'apiwarn-unclearnowtimestamp', 'ttfoobar', '0' ] ],
			] ],

			// Error handling
			'Bad value' => [ 'bogus',
				ApiUsageException::newWithMessage( null, [ 'apierror-badtimestamp', 'ttfoobar', 'bogus' ] )
			],
		];
	}

	public function testGetParamInfo() {
		$typeDef = new TimestampDef;
		$this->assertSame( [], $typeDef->getParamInfo( 'foobar', [], new MockApi ) );
		$this->assertSame( [ 'default' => '2018-01-02T03:04:05Z' ], $typeDef->getParamInfo(
			'foobar', [ ApiBase::PARAM_DFLT => '20180102030405' ], new MockApi
		) );
	}

}
