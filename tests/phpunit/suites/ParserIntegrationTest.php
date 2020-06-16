<?php

use Wikimedia\ScopedCallback;

/**
 * This is the TestCase subclass for running a single parser test via the
 * ParserTestRunner integration test system.
 *
 * Note: the following groups are not used by PHPUnit.
 * The list in ParserTestFileSuite::__construct() is used instead.
 *
 * @group large
 * @group Database
 * @group Parser
 * @group ParserTests
 *
 * @covers Parser
 * @covers BlockLevelPass
 * @covers CoreParserFunctions
 * @covers CoreTagHooks
 * @covers Sanitizer
 * @covers Preprocessor
 * @covers Preprocessor_Hash
 * @covers DateFormatter
 * @covers LinkHolderArray
 * @covers StripState
 * @covers ParserOptions
 * @covers ParserOutput
 */
class ParserIntegrationTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;
	use MediaWikiTestCaseTrait;

	/** @var array */
	private $ptTest;

	/** @var ParserTestRunner */
	private $ptRunner;

	/** @var ScopedCallback */
	private $ptTeardownScope;

	/** @var string|null */
	private $skipMessage = null;

	public function __construct( $runner, $fileName, $test, $skipMessage = null ) {
		parent::__construct( 'testParse', [ '[details omitted]' ],
			basename( $fileName ) . ': ' . $test['desc'] );
		$this->ptTest = $test;
		$this->ptRunner = $runner;
		$this->skipMessage = $skipMessage;
	}

	public function testParse() {
		if ( $this->skipMessage !== null ) {
			$this->markTestSkipped( $this->skipMessage );
		}
		$this->ptRunner->getRecorder()->setTestCase( $this );
		$result = $this->ptRunner->runTest( $this->ptTest );
		if ( $result === false ) {
			// Test intentionally skipped.
			$result = new ParserTestResult( $this->ptTest, "SKIP", "SKIP" );
		}
		$this->assertEquals( $result->expected, $result->actual );
	}

	protected function setUp() : void {
		$this->ptTeardownScope = $this->ptRunner->staticSetup();
		$this->hideDeprecated( 'Hooks::clear' );
	}

	protected function tearDown() : void {
		if ( $this->ptTeardownScope ) {
			ScopedCallback::consume( $this->ptTeardownScope );
		}
	}
}
