<?php
/**
 * This file is intended to test magic variables in the parser
 * It was inspired by Raymond & Matěj Grabovský commenting about r66200
 *
 * As of february 2011, it only tests some revisions and date related
 * magic variables.
 *
 * @author Ashar Voultoiz
 * @copyright Copyright © 2011, Ashar Voultoiz
 * @file
 */

/** */
class MagicVariableTest extends PHPUnit_Framework_TestCase {
	/** Will contains a parser object*/
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
	function setUp() {
		global $wgContLang;
		$wgContLang = Language::factory( 'en' );

		$this->testParser = new Parser();
		$this->testParser->Options( new ParserOptions() );

		# initialize parser output
		$this->testParser->clearState();
	}

	/** destroy parser (TODO: is it really neded?)*/
	function tearDown() {
		unset( $this->testParser );
	}

	############### TESTS #############################################
	# FIXME:
	#  - those got copy pasted, we can probably make them cleaner
	#  - tests are lacking useful messages

	# day

	/** @dataProvider MediaWikiProvide::Days */
	function testCurrentdayIsUnPadded( $day ) {
		$this->assertUnPadded( 'currentday', $day );
	}
	/** @dataProvider MediaWikiProvide::Days */
	function testCurrentdaytwoIsZeroPadded( $day ) {
		$this->assertZeroPadded( 'currentday2', $day );
	}
	/** @dataProvider MediaWikiProvide::Days */
	function testLocaldayIsUnPadded( $day ) {
		$this->assertUnPadded( 'localday', $day );
	}
	/** @dataProvider MediaWikiProvide::Days */
	function testLocaldaytwoIsZeroPadded( $day ) {
		$this->assertZeroPadded( 'localday2', $day );
	}
	
	# month

	/** @dataProvider MediaWikiProvide::Months */
	function testCurrentmonthIsZeroPadded( $month ) {
		$this->assertZeroPadded( 'currentmonth', $month );
	}
	/** @dataProvider MediaWikiProvide::Months */
	function testCurrentmonthoneIsUnPadded( $month ) {
		$this->assertUnPadded( 'currentmonth1', $month );
	}
	/** @dataProvider MediaWikiProvide::Months */
	function testLocalmonthIsZeroPadded( $month ) {
		$this->assertZeroPadded( 'localmonth', $month );
	}
	/** @dataProvider MediaWikiProvide::Months */
	function testLocalmonthoneIsUnPadded( $month ) {
		$this->assertUnPadded( 'localmonth1', $month );
	}


	# revision day

	/** @dataProvider MediaWikiProvide::Days */
	function testRevisiondayIsUnPadded( $day ) {
		$this->assertUnPadded( 'revisionday', $day );
	}
	/** @dataProvider MediaWikiProvide::Days */
	function testRevisiondaytwoIsZeroPadded( $day ) {
		$this->assertZeroPadded( 'revisionday2', $day );
	}
	
	# revision month

	/** @dataProvider MediaWikiProvide::Months */
	function testRevisionmonthIsZeroPadded( $month ) {
		$this->assertZeroPadded( 'revisionmonth', $month );
	}
	/** @dataProvider MediaWikiProvide::Months */
	function testRevisionmonthoneIsUnPadded( $month ) {
		$this->assertUnPadded( 'revisionmonth1', $month );
	}

	############### HELPERS ############################################

	/** assertion helper expecting a magic output which is zero padded */
	PUBLIC function assertZeroPadded( $magic, $value ) {
		$this->assertMagicPadding( $magic, $value, '%02d' );
	}

	/** assertion helper expecting a magic output which is unpadded */
	PUBLIC function assertUnPadded( $magic, $value ) {
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
		if( $value > 12 ) { $month = $value % 12; }
		else { $month = $value; }
	
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
		if( in_array( $magic, $this->expectedAsInteger ) ) {
			$expected = (int) $expected;
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
