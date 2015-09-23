<?php

class MWDebugTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		// Make sure MWDebug class is enabled
		static $MWDebugEnabled = false;
		if ( !$MWDebugEnabled ) {
			MWDebug::init();
			$MWDebugEnabled = true;
		}
		/** Clear log before each test */
		MWDebug::clearLog();
		MediaWiki\suppressWarnings();
	}

	protected function tearDown() {
		MediaWiki\restoreWarnings();
		parent::tearDown();
	}

	/**
	 * @covers MWDebug::log
	 */
	public function testAddLog() {
		MWDebug::log( 'logging a string' );
		$this->assertEquals(
			array( array(
				'msg' => 'logging a string',
				'type' => 'log',
				'caller' => __METHOD__,
			) ),
			MWDebug::getLog()
		);
	}

	/**
	 * @covers MWDebug::warning
	 */
	public function testAddWarning() {
		MWDebug::warning( 'Warning message' );
		$this->assertEquals(
			array( array(
				'msg' => 'Warning message',
				'type' => 'warn',
				'caller' => 'MWDebugTest::testAddWarning',
			) ),
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
			array( 'action' => 'help', 'format' => 'xml' ),
			'/api.php?action=help&format=xml'
		);

		$context = new RequestContext();
		$context->setRequest( $request );

		$apiMain = new ApiMain( $context );

		$result = new ApiResult( $apiMain );

		MWDebug::appendDebugInfoToApiResult( $context, $result );

		$this->assertInstanceOf( 'ApiResult', $result );
		$data = $result->getResultData();

		$expectedKeys = array( 'mwVersion', 'phpEngine', 'phpVersion', 'gitRevision', 'gitBranch',
			'gitViewUrl', 'time', 'log', 'debugLog', 'queries', 'request', 'memory',
			'memoryPeak', 'includes', '_element' );

		foreach ( $expectedKeys as $expectedKey ) {
			$this->assertArrayHasKey( $expectedKey, $data['debuginfo'], "debuginfo has $expectedKey" );
		}

		$xml = ApiFormatXml::recXmlPrint( 'help', $data );

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
		$request = $this->getMockBuilder( 'FauxRequest' )
			->setMethods( array( 'getRequestURL' ) )
			->setConstructorArgs( array(
				$params
			) )
			->getMock();

		$request->expects( $this->any() )
			->method( 'getRequestURL' )
			->will( $this->returnValue( $requestUrl ) );

		return $request;
	}

}
