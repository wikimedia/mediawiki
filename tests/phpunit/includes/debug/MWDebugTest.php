<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\TitleValue;
use Psr\Log\LoggerInterface;

/**
 * @covers MWDebug
 */
class MWDebugTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		$this->setMwGlobals( 'wgDevelopmentWarnings', false );

		parent::setUp();
		/** Clear log before each test */
		MWDebug::clearLog();
	}

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		MWDebug::init();
	}

	public static function tearDownAfterClass(): void {
		MWDebug::deinit();
		parent::tearDownAfterClass();
	}

	public function testLog() {
		@MWDebug::log( 'logging a string' );
		$this->assertEquals(
			[ [
				'msg' => 'logging a string',
				'type' => 'log',
				'caller' => 'MWDebugTest->testLog',
			] ],
			MWDebug::getLog()
		);
	}

	public function testWarningProduction() {
		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->once() )->method( 'info' );
		$this->setLogger( 'warning', $logger );

		@MWDebug::warning( 'Ohnosecond!' );
	}

	public function testWarningDevelopment() {
		$this->setMwGlobals( 'wgDevelopmentWarnings', true );

		$this->expectNotice();
		$this->expectNoticeMessage( 'Ohnosecond!' );

		MWDebug::warning( 'Ohnosecond!' );
	}

	/**
	 * Message from the error channel are copied to the debug toolbar "Console" log.
	 *
	 * This normally happens via wfDeprecated -> MWDebug::deprecated -> trigger_error
	 * -> MWExceptionHandler -> LoggerFactory -> LegacyLogger -> MWDebug::debugMsg.
	 *
	 * The above test asserts up until trigger_error.
	 * This test asserts from LegacyLogger down.
	 */
	public function testMessagesFromErrorChannel() {
		// Turn off to keep mw-error.log file empty in CI (and thus avoid build failure)
		$this->setMwGlobals( 'wgDebugLogGroups', [] );

		MWExceptionHandler::handleError( E_USER_DEPRECATED, 'Warning message' );
		$this->assertEquals(
			[ [
				'msg' => 'PHP Deprecated: Warning message',
				'type' => 'warn',
				'caller' => 'MWDebugTest::testMessagesFromErrorChannel',
			] ],
			MWDebug::getLog()
		);
	}

	public function testDetectDeprecatedOverride() {
		$baseclassInstance = new TitleValue( NS_MAIN, 'Test' );

		$this->assertFalse(
			MWDebug::detectDeprecatedOverride(
				$baseclassInstance,
				TitleValue::class,
				'getNamespace',
				MW_VERSION
			)
		);

		// create a dummy subclass that overrides a method
		$subclassInstance = new class ( NS_MAIN, 'Test' ) extends TitleValue {
			public function getNamespace(): int {
				// never called
				return -100;
			}
		};

		$this->expectDeprecation();
		$this->expectDeprecationMessage( '@anonymous' );

		MWDebug::detectDeprecatedOverride(
			$subclassInstance,
			TitleValue::class,
			'getNamespace',
			MW_VERSION
		);
	}

	public function testDeprecated() {
		$this->expectDeprecation();
		$this->expectDeprecationMessage( 'wfOldFunction' );

		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );
	}

	/**
	 * @doesNotPerformAssertions
	 */
	public function testDeprecatedIgnoreDuplicate() {
		@MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );

		// If we reach here, than the second one did not throw any deprecation warning.
		// The first one was silenced to seed the ignore logic.
	}

	/**
	 * @doesNotPerformAssertions
	 */
	public function testDeprecatedIgnoreNonConsecutivesDuplicate() {
		@MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );
		@MWDebug::warning( 'some warning' );
		@MWDebug::log( 'we could have logged something too' );
		// Another deprecation (not silenced)
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );
	}

	public function testAppendDebugInfoToApiResultXmlFormat() {
		$request = $this->newApiRequest(
			[ 'action' => 'help', 'format' => 'xml' ],
			'/api.php?action=help&format=xml'
		);

		$context = new RequestContext();
		$context->setRequest( $request );

		$result = new ApiResult( false );

		MWDebug::appendDebugInfoToApiResult( $context, $result );

		$this->assertInstanceOf( ApiResult::class, $result );
		$data = $result->getResultData();

		$expectedKeys = [ 'mwVersion', 'phpEngine', 'phpVersion', 'gitRevision', 'gitBranch',
			'gitViewUrl', 'time', 'log', 'debugLog', 'queries', 'request', 'memory',
			'memoryPeak', 'includes', '_element' ];

		foreach ( $expectedKeys as $expectedKey ) {
			$this->assertArrayHasKey( $expectedKey, $data['debuginfo'], "debuginfo has $expectedKey" );
		}

		$xml = ApiFormatXml::recXmlPrint( 'help', $data, null );

		// exception not thrown
		$this->assertIsString( $xml );
	}

	/**
	 * @param string[] $params
	 * @param string $requestUrl
	 * @return FauxRequest
	 */
	private function newApiRequest( array $params, $requestUrl ) {
		$req = new FauxRequest( $params );
		$req->setRequestURL( $requestUrl );
		return $req;
	}
}
