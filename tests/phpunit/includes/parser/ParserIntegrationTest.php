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
 * @covers Preprocessor_DOM
 * @covers Preprocessor_Hash
 * @covers DateFormatter
 * @covers LinkHolderArray
 * @covers StripState
 * @covers ParserOptions
 * @covers ParserOutput
 */
class ParserIntegrationTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/** @var array */
	private $ptTest;

	/** @var ParserTestRunner */
	private $ptRunner;

	/** @var ScopedCallback */
	private $ptTeardownScope;

	public function __construct( $runner, $fileName, $test ) {
		parent::__construct( 'testParse', [ '[details omitted]' ],
			basename( $fileName ) . ': ' . $test['desc'] );
		$this->ptTest = $test;
		$this->ptRunner = $runner;
	}

	public function testParse() {
		$this->ptRunner->getRecorder()->setTestCase( $this );
		$result = $this->ptRunner->runTest( $this->ptTest );
		$this->assertEquals( $result->expected, $result->actual );
	}

	public function setUp() {
		$this->ptTeardownScope = $this->ptRunner->staticSetup();
	}

	public function tearDown() {
		if ( $this->ptTeardownScope ) {
			ScopedCallback::consume( $this->ptTeardownScope );
		}
	}
}
