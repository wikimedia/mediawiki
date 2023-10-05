<?php

use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\TitleValue;

/**
 * @covers MWDebug
 */
class MWDebugTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
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

	public function testAddLog() {
		@MWDebug::log( 'logging a string' );
		$this->assertEquals(
			[ [
				'msg' => 'logging a string',
				'type' => 'log',
				'caller' => 'MWDebugTest->testAddLog',
			] ],
			MWDebug::getLog()
		);
	}

	public function testAddWarning() {
		@MWDebug::warning( 'Warning message' );
		$this->assertEquals(
			[ [
				'msg' => 'Warning message',
				'type' => 'warn',
				'caller' => 'MWDebugTest::testAddWarning',
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

		$this->assertTrue(
			@MWDebug::detectDeprecatedOverride(
				$subclassInstance,
				TitleValue::class,
				'getNamespace',
				MW_VERSION
			)
		);

		// A warning should have been logged
		$this->assertCount( 1, MWDebug::getLog() );
	}

	public function testAvoidDuplicateDeprecations() {
		@MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );
		@MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );

		$this->assertCount( 1, MWDebug::getLog(),
			"Only one deprecated warning per function should be kept"
		);
	}

	public function testAvoidNonConsecutivesDuplicateDeprecations() {
		@MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );
		@MWDebug::warning( 'some warning' );
		@MWDebug::log( 'we could have logged something too' );
		// Another deprecation
		@MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );

		$this->assertCount( 3, MWDebug::getLog(),
			"Only one deprecated warning per function should be kept"
		);
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
