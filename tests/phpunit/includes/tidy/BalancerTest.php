<?php

class BalancerTest extends MediaWikiTestCase {
	private $balancer;

	/**
	 * Anything that needs to happen before your tests should go here.
	 */
	protected function setUp() {
		// Be sure to do call the parent setup and teardown functions.
		// This makes sure that all the various cleanup and restorations
		// happen as they should (including the restoration for setMwGlobals).
		parent::setUp();
		$this->balancer = new MediaWiki\Tidy\Balancer( [
			'strict' => false, /* not strict */
			'allowedHtmlElements' => null, /* no sanitization */
			'tidyCompat' => false, /* standard parser */
		] );
	}

	/**
	 * Anything cleanup you need to do should go here.
	 */
	protected function tearDown() {
		parent::tearDown();
	}

	/**
	 * @covers Balancer::balance
	 * @dataProvider provideBalancerTests
	 */
	public function testBalancer( $description, $input, $expected ) {
		$output = $this->balancer->balance( $input );

		// Ignore self-closing tags
		$output = preg_replace( '/\s*\/>/', '>', $output );

		$this->assertEquals( $expected, $output, $description );
	}

	public static function provideBalancerTests() {
		// Get the tests from html5lib-tests.json
		$json = json_decode( file_get_contents(
			__DIR__ . '/html5lib-tests.json'
		), true );
		// Munge this slightly into the format phpunit expects
		// for providers, and filter out HTML constructs which
		// the balancer doesn't support.
		$tests = [];
		$start = '<html><head></head><body>';
		$end = '</body></html>';
		foreach ( $json as $filename => $cases ) {
			foreach ( $cases as $case ) {
				$html = $case['document']['html'];
				if (
					substr( $html, 0, strlen( $start ) ) !== $start ||
					substr( $html, -strlen( $end ) ) !== $end
				) {
					// Skip tests which involve stuff in the <head> or
					// weird doctypes.
					continue;
				}
				// We used to do this:
				//   $html = substr( $html, strlen( $start ), -strlen( $end ) );
				// But now we use a different field in the test case,
				// which reports how domino would parse this case in a
				// no-quirks <body> context.  (The original test case may
				// have had a different context, or relied on quirks mode.)
				$html = $case['document']['noQuirksBodyHtml'];
				// Normalize case of SVG attributes.
				$html = str_replace( 'foreignObject', 'foreignobject', $html );

				if ( isset( $case['document']['props']['comment'] ) ) {
					// Skip tests which include HTML comments, which
					// the balancer requires to have been stripped.
					continue;
				}
				if ( strpos( $case['data'], '<![CDATA[' ) !== false ) {
					// Skip tests involving <![CDATA[ ]]> quoting.
					continue;
				}
				if ( stripos( $case['data'], '<!DOCTYPE' ) !== false ) {
					// Skip tests involving doctypes.
					continue;
				}
				if ( preg_match( ',</?(html|head|body|frame|plaintext)>|<rdar:,i', $case['data'] ) ) {
					// Skip tests involving some literal tags, which are
					// unsupported but don't show up in the expected output.
					continue;
				}
				if (
					isset( $case['document']['props']['tags']['form'] ) ||
					isset( $case['document']['props']['tags']['iframe'] ) ||
					isset( $case['document']['props']['tags']['noembed'] ) ||
					isset( $case['document']['props']['tags']['noscript'] ) ||
					isset( $case['document']['props']['tags']['script'] ) ||
					isset( $case['document']['props']['tags']['select'] ) ||
					isset( $case['document']['props']['tags']['svg script'] ) ||
					isset( $case['document']['props']['tags']['svg title'] ) ||
					isset( $case['document']['props']['tags']['textarea'] ) ||
					isset( $case['document']['props']['tags']['title'] ) ||
					isset( $case['document']['props']['tags']['xmp'] )
				) {
					// Skip tests with unsupported tags which *do* show
					// up in the expected output.
					continue;
				}
				if (
					$filename === 'entities01.dat' ||
					$filename === 'entities02.dat' ||
					preg_match( '/&([a-z]+|#x[0-9A-F]+);/i', $case['data'] ) ||
					preg_match( '/^(&|&#|&#X|&#x|&#45|&x-test|&AMP)$/', $case['data'] )
				) {
					// Skip tests involving entity encoding.
					continue;
				}
				if (
					isset( $case['document']['props']['tagWithLt'] ) ||
					isset( $case['document']['props']['attrWithFunnyChar'] ) ||
					preg_match( ':^(</b test|<di|<foo bar=qux/>)$:', $case['data'] ) ||
					preg_match( ':</p<p>:', $case['data'] )
				) {
					// Skip tests with funny tag or attribute names,
					// which are really tests of the HTML tokenizer, not
					// the tree builder.
					continue;
				}
				if (
					stripos( $case['data'], 'encoding=" text/html "' ) !== false
				) {
					// The Sanitizer normalizes whitespace in attribute
					// values, which makes this test case invalid.
					continue;
				}
				if ( $filename === 'plain-text-unsafe.dat' ) {
					// Skip tests with ASCII null, etc.
					continue;
				}
				$tests[] = [
					$filename, # use better description?
					$case['data'],
					$html
				];
			}
		}
		return $tests;
	}
}
