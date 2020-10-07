<?php

/**
 * @group API
 * @group medium
 * @covers ApiCSPReport
 */
class ApiCSPReportTest extends MediaWikiIntegrationTestCase {

	protected function setUp() : void {
		parent::setUp();
		$this->setMwGlobals( [
			'CSPFalsePositiveUrls' => [],
		] );
	}

	public function testInternalReportonly() {
		$params = [
			'reportonly' => '1',
			'source' => 'internal',
		];
		$cspReport = [
			'document-uri' => 'https://doc.test/path',
			'referrer' => 'https://referrer.test/path',
			'violated-directive' => 'connet-src',
			'disposition' => 'report',
			'blocked-uri' => 'https://blocked.test/path?query',
			'line-number' => 4,
			'column-number' => 2,
			'source-file' => 'https://source.test/path?query',
		];

		$log = $this->doExecute( $params, $cspReport );

		$this->assertEquals(
			[
				[
					'[report-only] Received CSP report: ' .
						'<https://blocked.test> blocked from being loaded on <https://doc.test/path>:4',
					[
						'method' => 'ApiCSPReport::execute',
						'user_id' => 'logged-out',
						'user-agent' => 'Test/0.0',
						'source' => 'internal'
					]
				],
			],
			$log,
			'logged messages'
		);
	}

	public function testFalsePositiveOriginMatch() {
		$params = [
			'reportonly' => '1',
			'source' => 'internal',
		];
		$cspReport = [
			'document-uri' => 'https://doc.test/path',
			'referrer' => 'https://referrer.test/path',
			'violated-directive' => 'connet-src',
			'disposition' => 'report',
			'blocked-uri' => 'https://blocked.test/path/file?query',
			'line-number' => 4,
			'column-number' => 2,
			'source-file' => 'https://source.test/path/file?query',
		];

		$this->setMwGlobals( [
			'wgCSPFalsePositiveUrls' => [
				'https://blocked.test/path/' => true,
			],
		] );
		$log = $this->doExecute( $params, $cspReport );

		$this->assertSame(
			[],
			$log,
			'logged messages'
		);
	}

	private function doExecute( array $params, array $cspReport ) {
		$log = [];
		$logger = $this->createMock( Psr\Log\AbstractLogger::class );
		$logger->method( 'warning' )->will( $this->returnCallback(
			function ( $msg, $ctx ) use ( &$log ) {
				unset( $ctx['csp-report'] );
				$log[] = [ $msg, $ctx ];
			}
		) );
		$this->setLogger( 'csp-report-only', $logger );

		$postBody = json_encode( [ 'csp-report' => $cspReport ] );
		$req = $this->getMockBuilder( FauxRequest::class )
			->setMethods( [ 'getRawInput' ] )
			->setConstructorArgs( [ $params, /* $wasPosted */ true ] )
			->getMock();
		$req->method( 'getRawInput' )->willReturn( $postBody );
		$req->setHeaders( [
			'Content-Type' => 'application/csp-report',
			'User-Agent' => 'Test/0.0'
		] );

		$api = $this->getMockBuilder( ApiCSPReport::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getParameter', 'getRequest', 'getResult' ] )
			->getMock();
		$api->method( 'getParameter' )->will( $this->returnCallback(
			function ( $key ) use ( $req ) {
				return $req->getRawVal( $key );
			}
		) );
		$api->method( 'getRequest' )->willReturn( $req );
		$api->method( 'getResult' )->willReturn( new ApiResult( false ) );

		$api->execute();
		return $log;
	}
}
