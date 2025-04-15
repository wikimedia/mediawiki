<?php
namespace MediaWiki\Tests\Parser;

use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * This file is intended to test magic variables in the parser
 * It was inspired by Raymond & Matěj Grabovský commenting about r66200
 *
 * As of february 2011, it only tests some revisions and date related
 * magic variables.
 *
 * @author Antoine Musso
 * @group Database
 * @covers \MediaWiki\Parser\Parser
 * @covers \MediaWiki\Parser\CoreMagicVariables
 */
class MagicVariableTest extends MediaWikiIntegrationTestCase {
	private Parser $testParser;

	/** setup a basic parser object */
	protected function setUp(): void {
		parent::setUp();

		$services = $this->getServiceContainer();
		$contLang = $services->getLanguageFactory()->getLanguage( 'en' );
		$this->setService( 'ContentLanguage', $contLang );
		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => $contLang->getCode(),
			// NOTE: Europe/Stockholm DST applies Sun, Mar 26, 2023 2:00 - Sun, Oct 29, 2023 3:00AM
			MainConfigNames::Localtimezone => 'Europe/Stockholm',
			MainConfigNames::MiserMode => false,
			MainConfigNames::ParserCacheExpireTime => 86400 * 7,
		] );

		$this->testParser = $services->getParserFactory()->create();
		$this->testParser->setOptions( ParserOptions::newFromUserAndLang( new User, $contLang ) );

		# initialize parser output
		$this->testParser->clearState();

		# Needs a title to do magic word stuff
		$title = Title::makeTitle( NS_MAIN, 'Tests' );
		# Else it needs a db connection just to check if it's a redirect
		# (when deciding the page language).
		$title->mRedirect = false;

		$this->testParser->setTitle( $title );
	}

	/**
	 * @param int $num Upper limit for numbers
	 * @return array Array of strings naming numbers from 1 up to $num
	 */
	private static function createProviderUpTo( $num ) {
		$ret = [];
		for ( $i = 1; $i <= $num; $i++ ) {
			$ret[] = [ strval( $i ) ];
		}

		return $ret;
	}

	/**
	 * @return array Array of months numbers (as an integer)
	 */
	public static function provideMonths() {
		return self::createProviderUpTo( 12 );
	}

	/**
	 * @return array Array of days numbers (as an integer)
	 */
	public static function provideDays() {
		return self::createProviderUpTo( 31 );
	}

	# ############## TESTS #############################################
	# @todo FIXME:
	#  - those got copy pasted, we can probably make them cleaner
	#  - tests are lacking useful messages

	# day

	/** @dataProvider provideDays */
	public function testCurrentdayIsUnPadded( $day ) {
		$this->assertUnPadded( 'currentday', $day );
	}

	/** @dataProvider provideDays */
	public function testCurrentdaytwoIsZeroPadded( $day ) {
		$this->assertZeroPadded( 'currentday2', $day );
	}

	/** @dataProvider provideDays */
	public function testLocaldayIsUnPadded( $day ) {
		$this->assertUnPadded( 'localday', $day );
	}

	/** @dataProvider provideDays */
	public function testLocaldaytwoIsZeroPadded( $day ) {
		$this->assertZeroPadded( 'localday2', $day );
	}

	# month

	/** @dataProvider provideMonths */
	public function testCurrentmonthIsZeroPadded( $month ) {
		$this->assertZeroPadded( 'currentmonth', $month );
	}

	/** @dataProvider provideMonths */
	public function testCurrentmonthoneIsUnPadded( $month ) {
		$this->assertUnPadded( 'currentmonth1', $month );
	}

	/** @dataProvider provideMonths */
	public function testLocalmonthIsZeroPadded( $month ) {
		$this->assertZeroPadded( 'localmonth', $month );
	}

	/** @dataProvider provideMonths */
	public function testLocalmonthoneIsUnPadded( $month ) {
		$this->assertUnPadded( 'localmonth1', $month );
	}

	# revision day

	/** @dataProvider provideDays */
	public function testRevisiondayIsUnPadded( $day ) {
		$this->assertUnPadded( 'revisionday', $day );
	}

	/** @dataProvider provideDays */
	public function testRevisiondaytwoIsZeroPadded( $day ) {
		$this->assertZeroPadded( 'revisionday2', $day );
	}

	# revision month

	/** @dataProvider provideMonths */
	public function testRevisionmonthIsZeroPadded( $month ) {
		$this->assertZeroPadded( 'revisionmonth', $month );
	}

	/** @dataProvider provideMonths */
	public function testRevisionmonthoneIsUnPadded( $month ) {
		$this->assertUnPadded( 'revisionmonth1', $month );
	}

	public static function provideCurrentUnitTimestampWords() {
		return [
			// Afternoon
			[ 'currentmonth', '20200208153011', '02', 604800 ],
			[ 'currentmonth1', '20200208153011', '2', 604800 ],
			[ 'currentmonthname', '20200208153011', 'February', 604800 ],
			[ 'currentmonthnamegen', '20200208153011', 'February', 604800 ],
			[ 'currentmonthabbrev', '20200208153011', 'Feb', 604800 ],
			[ 'currentday', '20200208153011', '8', 30601 ],
			[ 'currentday2', '20200208153011', '08', 30601 ],
			[ 'currentdayname', '20200208153011', 'Saturday', 30601 ],
			[ 'currentyear', '20200208153011', '2020', 604800 ],
			[ 'currenthour', '20200208153011', '15', 1801 ],
			[ 'currentweek', '20200208153011', '6', 30601 ],
			[ 'currentdow', '20200208153011', '6', 30601 ],
			[ 'currenttime', '20200208153011', '15:30', 3600 ],
			// Night
			[ 'currentmonth', '20200208223011', '02', 604800 ],
			[ 'currentmonth1', '20200208223011', '2', 604800 ],
			[ 'currentmonthname', '20200208223011', 'February', 604800 ],
			[ 'currentmonthnamegen', '20200208223011', 'February', 604800 ],
			[ 'currentmonthabbrev', '20200208223011', 'Feb', 604800 ],
			[ 'currentday', '20200208223011', '8', 5401 ],
			[ 'currentday2', '20200208223011', '08', 5401 ],
			[ 'currentdayname', '20200208223011', 'Saturday', 5401 ],
			[ 'currentyear', '20200208223011', '2020', 604800 ],
			[ 'currenthour', '20200208223011', '22', 1801 ],
			[ 'currentweek', '20200208223011', '6', 5401 ],
			[ 'currentdow', '20200208223011', '6', 5401 ],
			[ 'currenttime', '20200208223011', '22:30', 3600 ]
		];
	}

	public static function provideLocalUnitTimestampWords() {
		// NOTE: Europe/Stockholm DST applies Sun, Mar 26, 2023 2:00 - Sun, Oct 29, 2023 3:00AM
		return [
			// Afternoon
			[ 'localmonth', '20200208153011', '02', 604800 ],
			[ 'localmonth1', '20200208153011', '2', 604800 ],
			[ 'localmonthname', '20200208153011', 'February', 604800 ],
			[ 'localmonthnamegen', '20200208153011', 'February', 604800 ],
			[ 'localmonthabbrev', '20200208153011', 'Feb', 604800 ],
			[ 'localday', '20200208153011', '8', 27001 ],
			[ 'localday2', '20200208153011', '08', 27001 ],
			[ 'localdayname', '20200208153011', 'Saturday', 27001 ],
			[ 'localyear', '20200208153011', '2020', 604800 ],
			[ 'localhour', '20200208153011', '16', 1801 ],
			[ 'localweek', '20200208153011', '6', 27001 ],
			[ 'localdow', '20200208153011', '6', 27001 ],
			[ 'localtime', '20200208153011', '16:30', 3600 ],
			// Night
			[ 'localmonth', '20200208223011', '02', 604800 ],
			[ 'localmonth1', '20200208223011', '2', 604800 ],
			[ 'localmonthname', '20200208223011', 'February', 604800 ],
			[ 'localmonthnamegen', '20200208223011', 'February', 604800 ],
			[ 'localmonthabbrev', '20200208223011', 'Feb', 604800 ],
			[ 'localday', '20200208223011', '8', 1801 ],
			[ 'localday2', '20200208223011', '08', 1801 ],
			[ 'localdayname', '20200208223011', 'Saturday', 1801 ],
			[ 'localyear', '20200208223011', '2020', 604800 ],
			[ 'localhour', '20200208223011', '23', 1801 ],
			[ 'localweek', '20200208223011', '6', 1801 ],
			[ 'localdow', '20200208223011', '6', 1801 ],
			[ 'localtime', '20200208223011', '23:30', 3600 ],
			// Late night / early morning
			[ 'localmonth', '20200208233011', '02', 604800 ],
			[ 'localmonth1', '20200208233011', '2', 604800 ],
			[ 'localmonthname', '20200208233011', 'February', 604800 ],
			[ 'localmonthnamegen', '20200208233011', 'February', 604800 ],
			[ 'localmonthabbrev', '20200208233011', 'Feb', 604800 ],
			[ 'localday', '20200208233011', '9', 84601 ],
			[ 'localday2', '20200208233011', '09', 84601 ],
			[ 'localdayname', '20200208233011', 'Sunday', 84601 ],
			[ 'localyear', '20200208233011', '2020', 604800 ],
			[ 'localhour', '20200208233011', '00', 1801 ],
			[ 'localweek', '20200208233011', '6', 84601 ],
			[ 'localdow', '20200208233011', '0', 84601 ],
			[ 'localtime', '20200208233011', '00:30', 3600 ]
		];
	}

	/**
	 * @param string $word
	 * @param string $ts
	 * @param string $expOutput
	 * @param int $expTTL
	 * @dataProvider provideCurrentUnitTimestampWords
	 * @dataProvider provideLocalUnitTimestampWords
	 */
	public function testCurrentUnitTimestampExpiry( $word, $ts, $expOutput, $expTTL ) {
		$this->setParserTimestamp( $ts );

		$this->assertMagic( $expOutput, $word );
		$this->assertSame( $expTTL, $this->testParser->getOutput()->getCacheExpiry() );
	}

	# ############## HELPERS ############################################

	/**
	 * assertion helper expecting a magic output which is zero padded
	 * @param string $magic
	 * @param string $value
	 */
	public function assertZeroPadded( $magic, $value ) {
		$this->assertMagicPadding( $magic, $value, '%02d' );
	}

	/**
	 * assertion helper expecting a magic output which is unpadded
	 * @param string $magic
	 * @param string $value
	 */
	public function assertUnPadded( $magic, $value ) {
		$this->assertMagicPadding( $magic, $value, '%d' );
	}

	/**
	 * Main assertion helper for magic variables padding
	 * @param string $magic Magic variable name
	 * @param mixed $value Month or day
	 * @param string $format Sprintf format for $value
	 */
	private function assertMagicPadding( $magic, $value, $format ) {
		# Initialize parser timestamp as year 2010 at 12h34 56s.
		# month and day are given by the caller ($value). Month < 12!
		$month = ( $value + 11 ) % 12 + 1;
		$this->setParserTimestamp(
			sprintf( '2010%02d%02d123456', $month, $value )
		);

		# please keep the following commented line of code. It helps debugging.
		// print "\nDEBUG (value $value):" . sprintf( '2010%02d%02d123456', $value, $value ) . "\n";

		# format expectation and test it
		$expected = sprintf( $format, $value );
		$this->assertMagic( $expected, $magic );
	}

	/**
	 * helper to set the parser timestamp and revision timestamp
	 * @param string $ts
	 */
	private function setParserTimestamp( $ts ) {
		$this->testParser->getOptions()->setTimestamp( $ts );
		TestingAccessWrapper::newFromObject( $this->testParser )->mRevisionTimestamp = $ts;
	}

	/**
	 * Assertion helper to test a magic variable output
	 * @param string|int $expected
	 * @param string $magic
	 */
	private function assertMagic( $expected, $magic ) {
		# Generate a message for the assertion
		$msg = sprintf( "Magic %s should be <%s:%s>",
			$magic,
			$expected,
			get_debug_type( $expected )
		);

		$this->assertSame(
			$expected,
			TestingAccessWrapper::newFromObject( $this->testParser )->expandMagicVariable( $magic ),
			$msg
		);
	}
}
