<?php
namespace MediaWiki\Tests\Language;

use MediaWiki\MainConfigNames;
use MediaWikiLangTestCase;

/**
 * @group Language
 * @covers \MediaWiki\Language\Language::userAdjust
 */
class TimeAdjustTest extends MediaWikiLangTestCase {
	private const LOCAL_TZ_OFFSET = 17;

	/**
	 * Test offset usage for a given Language::userAdjust
	 * @dataProvider provideUserAdjust
	 */
	public function testUserAdjust( string $date, $correction, string $expected ) {
		$this->overrideConfigValue( MainConfigNames::LocalTZoffset, self::LOCAL_TZ_OFFSET );

		$this->assertSame(
			$expected,
			$this->getServiceContainer()->getContentLanguage()->userAdjust( $date, $correction )
		);
	}

	public static function provideUserAdjust() {
		// Note: make sure to use dates in the past, especially with geographical time zones, to avoid any
		// chance of tests failing due to a change to the time zone rules.
		yield 'Literal int 0 (technically undocumented)' => [ '20221015120000', 0, '20221015120000' ];
		yield 'Literal int 2 (technically undocumented)' => [ '20221015120000', 2, '20221015140000' ];
		yield 'Literal int -2 (technically undocumented)' => [ '20221015120000', -2, '20221015100000' ];

		yield 'Literal 0' => [ '20221015120000', '0', '20221015120000' ];
		yield 'Literal 5' => [ '20221015120000', '5', '20221015170000' ];
		yield 'Literal -5' => [ '20221015120000', '-5', '20221015070000' ];

		$offsetsData = [
			'+00:00' => [ '20221015120000', '20221015120000', 0 ],
			'+02:00' => [ '20221015120000', '20221015140000', 2 * 60 ],
			'+02:15' => [ '20221015120000', '20221015141500', 2.25 * 60 ],
			'+14:00' => [ '20221015120000', '20221016020000', 14 * 60 ],
			'-06:00' => [ '20221015120000', '20221015060000', -6 * 60 ],
			'-06:45' => [ '20221015120000', '20221015051500', -6.75 * 60 ],
			'-12:00' => [ '20221015120000', '20221015000000', -12 * 60 ],
		];
		foreach ( $offsetsData as $offset => [ $time, $expected, $minutesVal ] ) {
			yield "Literal $offset" => [ $time, $offset, $expected ];
			yield "Full format $offset" => [ $time, "Offset|$minutesVal", $expected ];
		}
		yield 'Literal +15:00, capped at +14' => [ '20221015120000', '+15:00', '20221016020000' ];
		yield 'Full format +15:00, capped at +14' => [ '20221015120000', 'Offset|' . ( 15 * 60 ), '20221016020000' ];
		yield 'Literal -13:00, capped at -12' => [ '20221015120000', '-13:00', '20221015000000' ];
		yield 'Full format -13:00, capped at -12' => [ '20221015120000', 'Offset|' . ( -13 * 60 ), '20221015000000' ];

		yield 'Geo: Europe/Rome when +2 and +2 is stored' => [
			'20221015120000',
			'ZoneInfo|120|Europe/Rome',
			'20221015140000'
		];
		yield 'Geo: Europe/Rome when +2 and +1 is stored' => [
			'20221015120000',
			'ZoneInfo|60|Europe/Rome',
			'20221015140000'
		];
		yield 'Geo: Europe/Rome when +1 and +2 is stored' => [
			'20220320120000',
			'ZoneInfo|120|Europe/Rome',
			'20220320130000'
		];
		yield 'Geo: Europe/Rome when +1 and +1 is stored' => [
			'20220320120000',
			'ZoneInfo|60|Europe/Rome',
			'20220320130000'
		];

		yield 'Invalid geographical zone, fall back to offset' => [
			'20221015120000',
			'ZoneInfo|42|Eriador/Hobbiton',
			'20221015124200'
		];

		// These fall back to the local offset
		yield 'System 0, fallback to local offset' => [ '20221015120000', 'System|0', '20221015121700' ];
		yield 'System 120, fallback to local offset' => [ '20221015120000', 'System|120', '20221015121700' ];
		yield 'System -60, fallback to local offset' => [ '20221015120000', 'System|-60', '20221015121700' ];

		yield 'Garbage, fallback to local offset' => [ '20221015120000', 'WhatAmIEvenDoingHere', '20221015121700' ];
		yield 'Empty string, fallback to local offset' => [ '20221015120000', '', '20221015121700' ];

		yield 'T32148 - local date in year 10000' => [
			'99991231235959',
			'ZoneInfo|600|Asia/Vladivostok',
			'99991231235959'
		];
		yield 'T32148 - date in year 10000 due to local offset' => [
			'99991231235959',
			'System|0',
			'99991231235959'
		];
	}
}
