<?php

class MWDebugTest extends MediaWikiIntegrationTestCase {

	protected function setUp() : void {
		parent::setUp();
		/** Clear log before each test */
		MWDebug::clearLog();
	}

	public static function setUpBeforeClass() : void {
		parent::setUpBeforeClass();
		MWDebug::init();
		Wikimedia\suppressWarnings();
	}

	public static function tearDownAfterClass() : void {
		parent::tearDownAfterClass();
		MWDebug::deinit();
		Wikimedia\restoreWarnings();
	}

	/**
	 * @covers MWDebug::log
	 */
	public function testAddLog() {
		MWDebug::log( 'logging a string' );
		$this->assertEquals(
			[ [
				'msg' => 'logging a string',
				'type' => 'log',
				'caller' => 'MWDebugTest->testAddLog',
			] ],
			MWDebug::getLog()
		);
	}

	/**
	 * @covers MWDebug::warning
	 */
	public function testAddWarning() {
		MWDebug::warning( 'Warning message' );
		$this->assertEquals(
			[ [
				'msg' => 'Warning message',
				'type' => 'warn',
				'caller' => 'MWDebugTest::testAddWarning',
			] ],
			MWDebug::getLog()
		);
	}

	/**
	 * @covers MWDebug::deprecated
	 */
	public function testAvoidDuplicateDeprecations() {
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );

		// assertCount() not available on WMF integration server
		$this->assertCount( 1, MWDebug::getLog(),
			"Only one deprecated warning per function should be kept"
		);
	}

	/**
	 * @covers MWDebug::deprecated
	 */
	public function testAvoidNonConsecutivesDuplicateDeprecations() {
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );
		MWDebug::warning( 'some warning' );
		MWDebug::log( 'we could have logged something too' );
		// Another deprecation
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );

		// assertCount() not available on WMF integration server
		$this->assertCount( 3, MWDebug::getLog(),
			"Only one deprecated warning per function should be kept"
		);
	}

	/**
	 * @covers MWDebug::appendDebugInfoToApiResult
	 */
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
	 *
	 * @return FauxRequest
	 */
	private function newApiRequest( array $params, $requestUrl ) {
		$request = $this->getMockBuilder( FauxRequest::class )
			->setMethods( [ 'getRequestURL' ] )
			->setConstructorArgs( [
				$params
			] )
			->getMock();

		$request->expects( $this->any() )
			->method( 'getRequestURL' )
			->will( $this->returnValue( $requestUrl ) );

		return $request;
	}

}
