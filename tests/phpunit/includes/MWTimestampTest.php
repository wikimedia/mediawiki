<?php

use MediaWiki\User\StaticUserOptionsLookup;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\MWTimestamp;

/**
 * @covers MWTimestamp
 */
class MWTimestampTest extends MediaWikiLangTestCase {

	private function setMockUserOptions( array $options ) {
		$defaults = $this->getServiceContainer()->getMainConfig()->get( 'DefaultUserOptions' );

		// $options are set as the options for "Pamela", the name used in the tests
		$userOptionsLookup = new StaticUserOptionsLookup(
			[ 'Pamela' => $options ],
			$defaults
		);

		$this->setService( 'UserOptionsLookup', $userOptionsLookup );
	}

	/**
	 * @dataProvider provideRelativeTimestampTests
	 */
	public function testRelativeTimestamp(
		$tsTime, // The timestamp to format
		$currentTime, // The time to consider "now"
		$timeCorrection, // The time offset to use
		$dateFormat, // The date preference to use
		$expectedOutput, // The expected output
		$desc // Description
	) {
		$this->setMockUserOptions( [
			'timecorrection' => $timeCorrection,
			'date' => $dateFormat
		] );

		$user = new UserIdentityValue( 13, 'Pamela' );

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
