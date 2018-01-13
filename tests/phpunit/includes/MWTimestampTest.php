<?php

/**
 * Tests timestamp parsing and output.
 */
class MWTimestampTest extends MediaWikiLangTestCase {
	protected function setUp() {
		parent::setUp();

		// Avoid 'GetHumanTimestamp' hook and others
		$this->setMwGlobals( 'wgHooks', [] );
	}

	/**
	 * @dataProvider provideHumanTimestampTests
	 * @covers MWTimestamp::getHumanTimestamp
	 */
	public function testHumanTimestamp(
		$tsTime, // The timestamp to format
		$currentTime, // The time to consider "now"
		$timeCorrection, // The time offset to use
		$dateFormat, // The date preference to use
		$expectedOutput, // The expected output
		$desc // Description
	) {
		$user = $this->createMock( User::class );
		$user->expects( $this->any() )
			->method( 'getOption' )
			->with( 'timecorrection' )
			->will( $this->returnValue( $timeCorrection ) );

		$user->expects( $this->any() )
			->method( 'getDatePreference' )
			->will( $this->returnValue( $dateFormat ) );

		$tsTime = new MWTimestamp( $tsTime );
		$currentTime = new MWTimestamp( $currentTime );

		$this->assertEquals(
			$expectedOutput,
			$tsTime->getHumanTimestamp( $currentTime, $user ),
			$desc
		);
	}

	public static function provideHumanTimestampTests() {
		return [
			[
				'20111231170000',
				'20120101000000',
				'Offset|0',
				'mdy',
				'Yesterday at 17:00',
				'"Yesterday" across years',
			],
			[
				'20120717190900',
				'20120717190929',
				'Offset|0',
				'mdy',
				'just now',
				'"Just now"',
			],
			[
				'20120717190900',
				'20120717191530',
				'Offset|0',
				'mdy',
				'6 minutes ago',
				'X minutes ago',
			],
			[
				'20121006173100',
				'20121006173200',
				'Offset|0',
				'mdy',
				'1 minute ago',
				'"1 minute ago"',
			],
			[
				'20120617190900',
				'20120717190900',
				'Offset|0',
				'mdy',
				'June 17',
				'Another month'
			],
			[
				'19910130151500',
				'20120716193700',
				'Offset|0',
				'mdy',
				'15:15, January 30, 1991',
				'Different year',
			],
			[
				'20120101050000',
				'20120101080000',
				'Offset|-360',
				'mdy',
				'Yesterday at 23:00',
				'"Yesterday" across years with time correction',
			],
			[
				'20120714184300',
				'20120716184300',
				'Offset|-420',
				'mdy',
				'Saturday at 11:43',
				'Recent weekday with time correction',
			],
			[
				'20120714184300',
				'20120715040000',
				'Offset|-420',
				'mdy',
				'11:43',
				'Today at another time with time correction',
			],
			[
				'20120617190900',
				'20120717190900',
				'Offset|0',
				'dmy',
				'17 June',
				'Another month with dmy'
			],
			[
				'20120617190900',
				'20120717190900',
				'Offset|0',
				'ISO 8601',
				'06-17',
				'Another month with ISO-8601'
			],
			[
				'19910130151500',
				'20120716193700',
				'Offset|0',
				'ISO 8601',
				'1991-01-30T15:15:00',
				'Different year with ISO-8601',
			],
		];
	}

	/**
	 * @dataProvider provideRelativeTimestampTests
	 * @covers MWTimestamp::getRelativeTimestamp
	 */
	public function testRelativeTimestamp(
		$tsTime, // The timestamp to format
		$currentTime, // The time to consider "now"
		$timeCorrection, // The time offset to use
		$dateFormat, // The date preference to use
		$expectedOutput, // The expected output
		$desc // Description
	) {
		$user = $this->createMock( User::class );
		$user->expects( $this->any() )
			->method( 'getOption' )
			->with( 'timecorrection' )
			->will( $this->returnValue( $timeCorrection ) );

		$tsTime = new MWTimestamp( $tsTime );
		$currentTime = new MWTimestamp( $currentTime );

		$this->assertEquals(
			$expectedOutput,
			$tsTime->getRelativeTimestamp( $currentTime, $user ),
			$desc
		);
	}

	public static function provideRelativeTimestampTests() {
		return [
			[
				'20111231170000',
				'20120101000000',
				'Offset|0',
				'mdy',
				'7 hours ago',
				'"Yesterday" across years',
			],
			[
				'20120717190900',
				'20120717190929',
				'Offset|0',
				'mdy',
				'29 seconds ago',
				'"Just now"',
			],
			[
				'20120717190900',
				'20120717191530',
				'Offset|0',
				'mdy',
				'6 minutes and 30 seconds ago',
				'Combination of multiple units',
			],
			[
				'20121006173100',
				'20121006173200',
				'Offset|0',
				'mdy',
				'1 minute ago',
				'"1 minute ago"',
			],
			[
				'19910130151500',
				'20120716193700',
				'Offset|0',
				'mdy',
				'2 decades, 1 year, 168 days, 2 hours, 8 minutes and 48 seconds ago',
				'A long time ago',
			],
			[
				'20120101050000',
				'20120101080000',
				'Offset|-360',
				'mdy',
				'3 hours ago',
				'"Yesterday" across years with time correction',
			],
			[
				'20120714184300',
				'20120716184300',
				'Offset|-420',
				'mdy',
				'2 days ago',
				'Recent weekday with time correction',
			],
			[
				'20120714184300',
				'20120715040000',
				'Offset|-420',
				'mdy',
				'9 hours and 17 minutes ago',
				'Today at another time with time correction',
			],
		];
	}
}
