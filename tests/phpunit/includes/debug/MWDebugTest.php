<?php

class MWDebugTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		/** Clear log before each test */
		MWDebug::clearLog();
	}

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
		MWDebug::init();
		Wikimedia\suppressWarnings();
	}

	public static function tearDownAfterClass() {
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
		$this->assertEquals( 1,
			count( MWDebug::getLog() ),
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
		$this->assertEquals( 3,
			count( MWDebug::getLog() ),
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

		$apiMain = new ApiMain( $context );

		$result = new ApiResult( $apiMain );

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
		$this->assertInternalType( 'string', $xml );
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
