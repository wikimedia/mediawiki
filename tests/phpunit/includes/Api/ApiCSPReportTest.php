<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiCSPReport;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiResult;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWikiIntegrationTestCase;
use TestLogger;

/**
 * @group API
 * @group medium
 * @covers \MediaWiki\Api\ApiCSPReport
 */
class ApiCSPReportTest extends MediaWikiIntegrationTestCase {

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

		unset( $log[0][2]['csp-report'] );
		$this->assertEquals(
			[
				[
					'warning',
					'[{flags}] Received CSP report: ' .
						'<{blockedOrigin}> blocked from being loaded on <{documentUri}>:{line}',
					[
						'method' => ApiCSPReport::class . '::execute',
						'user_id' => 'logged-out',
						'user-agent' => 'Test/0.0',
						'source' => 'internal',
						'flags' => 'report-only',
						'blockedOrigin' => 'https://blocked.test',
						'documentUri' => 'https://doc.test/path',
						'line' => 4
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

		$this->overrideConfigValue(
			MainConfigNames::CSPFalsePositiveUrls,
			[ 'https://blocked.test/path/' => true ]
		);
		$log = $this->doExecute( $params, $cspReport );

		$logLevels = array_map( static fn ( array $logLine ) => $logLine[0], $log );
		$this->assertSame(
			[ 'debug' ],
			$logLevels,
			'logged messages'
		);
	}

	public function testChromeExtensionUrl() {
		$params = [
			'reportonly' => '1',
			'source' => 'internal',
		];
		$cspReport = [
			'document-uri' => 'https://doc.test/path',
			'referrer' => 'https://referrer.test/path',
			'violated-directive' => 'script-src',
			'disposition' => 'report',
			'blocked-uri' => 'chrome-extension://example-extension/script.js',
			'line-number' => 10,
			'column-number' => 5,
			'source-file' => 'https://doc.test/path',
		];

		$log = $this->doExecute( $params, $cspReport );
		$logLevels = array_map( static fn ( array $logLine ) => $logLine[0], $log );
		$this->assertSame( [ 'warning' ], $logLevels, 'should have log entry' );
		$this->assertEquals(
			'chrome-extension://example-extension',
			$log[0][2]['blockedOrigin'],
			'should show extension ID, not blank'
		);
	}

	public function testChromeExtensionFalsePositive() {
		$params = [
			'reportonly' => '1',
			'source' => 'internal',
		];
		$cspReport = [
			'document-uri' => 'https://doc.test/path',
			'referrer' => 'https://referrer.test/path',
			'violated-directive' => 'script-src',
			'disposition' => 'report',
			'blocked-uri' => 'chrome-extension://example-extension/script.js',
			'line-number' => 10,
			'column-number' => 5,
			'source-file' => 'https://doc.test/path',
		];

		$this->overrideConfigValue(
			MainConfigNames::CSPFalsePositiveUrls,
			[ 'chrome-extension://example-extension' => true ]
		);

		$log = $this->doExecute( $params, $cspReport );

		$logLevels = array_map( static fn ( array $logLine ) => $logLine[0], $log );
		$this->assertSame( [ 'debug' ], $logLevels, 'should not warn for false positive chrome-extension' );
	}

	private function doExecute( array $params, array $cspReport ) {
		$logger = new TestLogger( true, null, true );
		$this->setLogger( 'csp-report-only', $logger );

		$postBody = json_encode( [ 'csp-report' => $cspReport ] );
		$req = $this->getMockBuilder( FauxRequest::class )
			->onlyMethods( [ 'getRawInput' ] )
			->setConstructorArgs( [ $params, /* $wasPosted */ true ] )
			->getMock();
		$req->method( 'getRawInput' )->willReturn( $postBody );
		$req->setHeaders( [
			'Content-Type' => 'application/csp-report',
			'User-Agent' => 'Test/0.0'
		] );

		$services = $this->getServiceContainer();
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $req );
		$main = new ApiMain( $context );
		$api = $this->getMockBuilder( ApiCSPReport::class )
			->setConstructorArgs( [ $main, 'mock' ] )
			->onlyMethods( [ 'getParameter', 'getRequest', 'getResult' ] )
			->getMock();
		$api->method( 'getParameter' )->willReturnCallback(
			static function ( $key ) use ( $req ) {
				return $req->getRawVal( $key );
			}
		);
		$api->method( 'getRequest' )->willReturn( $req );
		$api->method( 'getResult' )->willReturn( new ApiResult( false ) );

		$api->execute();

		return $logger->getBuffer();
	}
}
