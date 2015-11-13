<?php

class CSPTest extends MediaWikiTestCase {
	/** @var CSP */
	private $csp;

	protected function setUp() {
		$this->setMwGlobals( array(
			'wgAllowExternalImages' => false,
			'wgAllowExternalImagesFrom' => array(),
			'wgAllowImageTag' => false,
			'wgEnableImageWhitelist' => false,
			'wgCrossSiteAJAXdomains' => array(
				'sister-site.somewhere.com',
				'*.wikipedia.org',
				'??.wikinews.org'
			),
			'wgScriptPath' => '/w',
		) );
		// Note, there are some obscure globals which
		// could affect the results which aren't included here.

		$context = RequestContext::getMain();
		$resp = $context->getRequest()->response();
		$conf = $context->getConfig();
		$csp = new CSP( 'secret', $resp, $conf );
		$this->csp = TestingAccessWrapper::newFromObject( $csp );

		return parent::setUp();
	}

	/**
	 * @dataProvider providerFalsePositiveBrowser
	 */
	public function testFalsePositiveBrowser( $ua, $expected ) {
		$actual = CSP::falsePositiveBrowser( $ua );
		$this->assertEquals( $expected, $actual, $ua );
	}

	public function providerFalsePositiveBrowser() {
		// @codingStandardsIgnoreStart Generic.Files.LineLength
		return array(
			array( 'Mozilla/5.0 (X11; Linux i686; rv:41.0) Gecko/20100101 Firefox/41.0', true ),
			array( 'Mozilla/5.0 (X11; U; Linux i686; en-ca) AppleWebKit/531.2+ (KHTML, like Gecko) Version/5.0 Safari/531.2+ Debian/squeeze (2.30.6-1) Epiphany/2.30.6', false )
		);
		// @codingStandardsIgnoreEnd Generic.Files.LineLength
	}

	/**
	 * @dataProvider providerMakeCSPDirectives
	 */
	public function testMakeCSPDirectives( $policy, $expected ) {
		$actual = $this->csp->makeCSPDirectives( $policy, CSP::FULL_MODE );
		$policyJson = formatJson::encode( $policy );
		$this->assertEquals( $expected, $actual, $policyJson );
	}

	public function providerMakeCSPDirectives() {
		return array(
			array( false, '' ),
			array(
				true,
				"script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline' sister-site.somewhere.com *.wikipedia.org; default-src * data: blob:; style-src * data: blob: 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json"
			),
			array(
				array(),
				"script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline' sister-site.somewhere.com *.wikipedia.org; default-src * data: blob:; style-src * data: blob: 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json"
			 ),
			array(
				array( 'script-src' => array( 'http://example.com', 'http://something,else.com' ) ),
				"script-src 'unsafe-eval' 'self' 'nonce-secret' http://example.com http://something%2Celse.com 'unsafe-inline' sister-site.somewhere.com *.wikipedia.org; default-src * data: blob:; style-src * data: blob: 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json"
			),
			array(
				array( 'unsafeFallback' => false ),
				"script-src 'unsafe-eval' 'self' 'nonce-secret' sister-site.somewhere.com *.wikipedia.org; default-src * data: blob:; style-src * data: blob: 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json"
			),
			array(
				array( 'unsafeFallback' => true ),
				"script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline' sister-site.somewhere.com *.wikipedia.org; default-src * data: blob:; style-src * data: blob: 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json"
			),
			array(
				array( 'default-src' => false ),
				"script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline' sister-site.somewhere.com *.wikipedia.org; default-src * data: blob:; style-src * data: blob: 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json"
			),
			array(
				array( 'default-src' => true ),
				"script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline' sister-site.somewhere.com *.wikipedia.org; default-src 'self' data: blob: sister-site.somewhere.com *.wikipedia.org; style-src 'self' data: blob: sister-site.somewhere.com *.wikipedia.org 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json"
			),
			array(
				array( 'default-src' => array( 'https://foo.com', 'http://bar.com', 'baz.de' ) ),
				"script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline' sister-site.somewhere.com *.wikipedia.org; default-src 'self' data: blob: https://foo.com http://bar.com baz.de sister-site.somewhere.com *.wikipedia.org; style-src 'self' data: blob: https://foo.com http://bar.com baz.de sister-site.somewhere.com *.wikipedia.org 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json"
			),
			array(
				array( 'includeCORS' => false ),
				"script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline'; default-src * data: blob:; style-src * data: blob: 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json"
			),
			array(
				array( 'includeCORS' => false, 'default-src' => true ),
				"script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline'; default-src 'self' data: blob:; style-src 'self' data: blob: 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json"
			),
			array(
				array( 'includeCORS' => true ),
				"script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline' sister-site.somewhere.com *.wikipedia.org; default-src * data: blob:; style-src * data: blob: 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json"
			),
			array(
				array( 'report-uri' => false ),
				"script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline' sister-site.somewhere.com *.wikipedia.org; default-src * data: blob:; style-src * data: blob: 'unsafe-inline'"
			),
			array(
				array( 'report-uri' => true ),
				"script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline' sister-site.somewhere.com *.wikipedia.org; default-src * data: blob:; style-src * data: blob: 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json"
			),
			array(
				array( 'report-uri' => 'https://example.com/index.php?foo;report=csp' ),
				"script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline' sister-site.somewhere.com *.wikipedia.org; default-src * data: blob:; style-src * data: blob: 'unsafe-inline'; report-uri https://example.com/index.php?foo%3Breport=csp"
			),
		);
	}

	public function testMakeCSPDirectivesImage() {
		global $wgAllowImageTag;
		$origImg = wfSetVar( $wgAllowImageTag, true );

		$actual = $this->csp->makeCSPDirectives( true, CSP::FULL_MODE );

		$wgAllowImageTag = $origImg;

		$expected = "script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline' sister-site.somewhere.com *.wikipedia.org; default-src * data: blob:; style-src * data: blob: 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json";
		$this->assertEquals( $expected, $actual );
	}

	public function testMakeCSPDirectivesReportUri() {
		$actual = $this->csp->makeCSPDirectives(
			true,
			CSP::REPORT_ONLY_MODE
		);
		$expected = "script-src 'unsafe-eval' 'self' 'nonce-secret' 'unsafe-inline' sister-site.somewhere.com *.wikipedia.org; default-src * data: blob:; style-src * data: blob: 'unsafe-inline'; report-uri /w/api.php?action=cspreport&format=json&reportonly=1";
		$this->assertEquals( $expected, $actual );
	}

	public function testGetHeaderName() {
		$this->assertEquals(
			$this->csp->getHeaderName( CSP::REPORT_ONLY_MODE ),
			'Content-Security-Policy-Report-Only'
		);
		$this->assertEquals(
			$this->csp->getHeaderName( CSP::FULL_MODE ),
			'Content-Security-Policy'
		);
	}

	public function testGetReportUri() {
		$full = $this->csp->getReportUri( CSP::FULL_MODE );
		$fullExpected = '/w/api.php?action=cspreport&format=json';
		$this->assertEquals( $full, $fullExpected, 'normal report uri' );

		$report = $this->csp->getReportUri( CSP::REPORT_ONLY_MODE );
		$reportExpected = $fullExpected . '&reportonly=1';
		$this->assertEquals( $report, $reportExpected, 'report only' );

		global $wgScriptPath;
		$origPath = wfSetVar( $wgScriptPath, '/tl;dr/a,%20wiki' );
		$esc = $this->csp->getReportUri( CSP::FULL_MODE );
		$escExpected = '/tl%3Bdr/a%2C%20wiki/api.php?action=cspreport&format=json';
		$wgScriptPath = $origPath;
		$this->assertEquals( $esc, $escExpected, 'test esc rules' );
	}

	/**
	 * @dataProvider providerPrepareUrlForCSP
	 */
	public function testPrepareUrlForCSP( $url, $expected ) {
		$actual = $this->csp->prepareUrlForCSP( $url );
		$this->assertEquals( $actual, $expected, $url );
	}

	public function providerPrepareUrlForCSP() {
		global $wgServer;
		return array(
			array( $wgServer, false ),
			array( 'https://example.com', 'https://example.com' ),
			array( 'https://example.com:200', 'https://example.com:200' ),
			array( 'http://example.com', 'http://example.com' ),
			array( 'example.com', 'example.com' ),
			array( '*.example.com', '*.example.com' ),
			array( 'https://*.example.com', 'https://*.example.com' ),
			array( '//example.com', 'example.com' ),
			array( 'https://example.com/path', 'https://example.com' ),
			array( 'https://tl;dr.com', 'https://tl%3Bdr.com' ),
			array( 'yes,no.com', 'yes%2Cno.com' ),
			array( '/relative-url', false ),
		);
	}

	public function testEscapeUrlForCSP() {
		$escaped = $this->csp->escapeUrlForCSP( ',;%2B' );
		$this->assertEquals( $escaped, '%2C%3B%2B' );
	}
}
