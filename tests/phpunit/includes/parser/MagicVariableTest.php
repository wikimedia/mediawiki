<?php
/**
 * This file is intended to test magic variables in the parser
 * It was inspired by Raymond & Matěj Grabovský commenting about r66200
 *
 * As of february 2011, it only tests some revisions and date related
 * magic variables.
 *
 * @author Antoine Musso
 * @copyright Copyright © 2011, Antoine Musso
 * @file
 * @todo covers tags
 */

class MagicVariableTest extends MediaWikiTestCase {
	/**
	 * @var Parser
	 */
	private $testParser = null;

	/**
	 * An array of magicword returned as type integer by the parser
	 * They are usually returned as a string for i18n since we support
	 * persan numbers for example, but some magic explicitly return
	 * them as integer.
	 * @see MagicVariableTest::assertMagic()
	 */
	private $expectedAsInteger = array(
		'revisionday',
		'revisionmonth1',
	);

	/** setup a basic parser object */
	protected function setUp() {
		parent::setUp();

		$contLang = Language::factory( 'en' );
		$this->setMwGlobals( array(
			'wgLanguageCode' => 'en',
			'wgContLang' => $contLang,
		) );

		$this->testParser = new Parser();
		$this->testParser->Options( ParserOptions::newFromUserAndLang( new User, $contLang ) );

		# initialize parser output
		$this->testParser->clearState();

		# Needs a title to do magic word stuff
		$title = Title::newFromText( 'Tests' );
		$title->mRedirect = false; # Else it needs a db connection just to check if it's a redirect (when deciding the page language)

		$this->testParser->setTitle( $title );
	}

	/**
	 * @param int $num upper limit for numbers
	 * @return array of numbers from 1 up to $num
	 */
	private static function createProviderUpTo( $num ) {
		$ret = array();
		for ( $i = 1; $i <= $num; $i++ ) {
			$ret[] = array( $i );
		}

		return $ret;
	}

	/**
	 * @return array of months numbers (as an integer)
	 */
	public static function provideMonths() {
		return self::createProviderUpTo( 12 );
	}

	/**
	 * @return array of days numbers (as an integer)
	 */
	public static function provideDays() {
		return self::createProviderUpTo( 31 );
	}

	############### TESTS #############################################
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

	/**
	 * Rough tests for {{SERVERNAME}} magic word
	 * Bug 31176
	 * @group Database
	 * @dataProvider provideDataServernameFromDifferentProtocols
	 */
	public function testServernameFromDifferentProtocols( $server ) {
		$this->setMwGlobals( 'wgServer', $server );

		$this->assertMagic( 'localhost', 'servername' );
	}

	public static function provideDataServernameFromDifferentProtocols() {
		return array(
			array( 'http://localhost/' ),
			array( 'https://localhost/' ),
			array( '//localhost/' ), # bug 31176
		);
	}

	############### HELPERS ############################################

	/** assertion helper expecting a magic output which is zero padded */
	public function assertZeroPadded( $magic, $value ) {
		$this->assertMagicPadding( $magic, $value, '%02d' );
	}

	/** assertion helper expecting a magic output which is unpadded */
	public function assertUnPadded( $magic, $value ) {
		$this->assertMagicPadding( $magic, $value, '%d' );
	}

	/**
	 * Main assertion helper for magic variables padding
	 * @param $magic string Magic variable name
	 * @param $value mixed Month or day
	 * @param $format string sprintf format for $value
	 */
	private function assertMagicPadding( $magic, $value, $format ) {
		# Initialize parser timestamp as year 2010 at 12h34 56s.
		# month and day are given by the caller ($value). Month < 12!
		if ( $value > 12 ) {
			$month = $value % 12;
		} else {
			$month = $value;
		}

		$this->setParserTS(
			sprintf( '2010%02d%02d123456', $month, $value )
		);

		# please keep the following commented line of code. It helps debugging.
		//print "\nDEBUG (value $value):" . sprintf( '2010%02d%02d123456', $value, $value ) . "\n";

		# format expectation and test it
		$expected = sprintf( $format, $value );
		$this->assertMagic( $expected, $magic );
	}

	/** helper to set the parser timestamp and revision timestamp */
	private function setParserTS( $ts ) {
		$this->testParser->Options()->setTimestamp( $ts );
		$this->testParser->mRevisionTimestamp = $ts;
	}

	/**
	 * Assertion helper to test a magic variable output
	 */
	private function assertMagic( $expected, $magic ) {
		if ( in_array( $magic, $this->expectedAsInteger ) ) {
			$expected = (int)$expected;
		}

		# Generate a message for the assertion
		$msg = sprintf( "Magic %s should be <%s:%s>",
			$magic,
			$expected,
			gettype( $expected )
		);

		$this->assertSame(
			$expected,
			$this->testParser->getVariableValue( $magic ),
			$msg
		);
	}
}
