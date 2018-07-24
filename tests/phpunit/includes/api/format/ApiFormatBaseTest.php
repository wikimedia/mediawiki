<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @covers ApiFormatBase
 */
class ApiFormatBaseTest extends ApiFormatTestBase {

	protected $printerName = 'mockbase';

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( [
			'wgServer' => 'http://example.org'
		] );
	}

	public function getMockFormatter( ApiMain $main = null, $format, $methods = [] ) {
		if ( $main === null ) {
			$context = new RequestContext;
			$context->setRequest( new FauxRequest( [], true ) );
			$main = new ApiMain( $context );
		}

		$mock = $this->getMockBuilder( ApiFormatBase::class )
			->setConstructorArgs( [ $main, $format ] )
			->setMethods( array_unique( array_merge( $methods, [ 'getMimeType', 'execute' ] ) ) )
			->getMock();
		if ( !in_array( 'getMimeType', $methods, true ) ) {
			$mock->method( 'getMimeType' )->willReturn( 'text/x-mock' );
		}
		return $mock;
	}

	protected function encodeData( array $params, array $data, $options = [] ) {
		$options += [
			'name' => 'mock',
			'class' => ApiFormatBase::class,
			'factory' => function ( ApiMain $main, $format ) use ( $options ) {
				$mock = $this->getMockFormatter( $main, $format );
				$mock->expects( $this->once() )->method( 'execute' )
					->willReturnCallback( function () use ( $mock ) {
						$mock->printText( "Format {$mock->getFormat()}: " );
						$mock->printText( "<b>ok</b>" );
					} );

				if ( isset( $options['status'] ) ) {
					$mock->setHttpStatus( $options['status'] );
				}

				return $mock;
			},
			'returnPrinter' => true,
		];

		$this->setMwGlobals( [
			'wgApiFrameOptions' => 'DENY',
		] );

		$ret = parent::encodeData( $params, $data, $options );
		$printer = TestingAccessWrapper::newFromObject( $ret['printer'] );
		$text = $ret['text'];

		if ( $options['name'] !== 'mockfm' ) {
			$ct = 'text/x-mock';
			$file = 'api-result.mock';
			$status = $options['status'] ?? null;
		} elseif ( isset( $params['wrappedhtml'] ) ) {
			$ct = 'text/mediawiki-api-prettyprint-wrapped';
			$file = 'api-result-wrapped.json';
			$status = null;

			// Replace varying field
			$text = preg_replace( '/"time":\d+/', '"time":1234', $text );
		} else {
			$ct = 'text/html';
			$file = 'api-result.html';
			$status = null;

			// Strip OutputPage-generated HTML
			if ( preg_match( '!<pre class="api-pretty-content">.*</pre>!s', $text, $m ) ) {
				$text = $m[0];
			}
		}

		$response = $printer->getMain()->getRequest()->response();
		$this->assertSame( "$ct; charset=utf-8", strtolower( $response->getHeader( 'Content-Type' ) ) );
		$this->assertSame( 'DENY', $response->getHeader( 'X-Frame-Options' ) );
		$this->assertSame( $file, $printer->getFilename() );
		$this->assertSame( "inline; filename=$file", $response->getHeader( 'Content-Disposition' ) );
		$this->assertSame( $status, $response->getStatusCode() );

		return $text;
	}

	public static function provideGeneralEncoding() {
		return [
			'normal' => [
				[],
				"Format MOCK: <b>ok</b>",
				[],
				[ 'name' => 'mock' ]
			],
			'normal ignores wrappedhtml' => [
				[],
				"Format MOCK: <b>ok</b>",
				[ 'wrappedhtml' => 1 ],
				[ 'name' => 'mock' ]
			],
			'HTML format' => [
				[],
				'<pre class="api-pretty-content">Format MOCK: &lt;b>ok&lt;/b></pre>',
				[],
				[ 'name' => 'mockfm' ]
			],
			'wrapped HTML format' => [
				[],
				// phpcs:ignore Generic.Files.LineLength.TooLong
				'{"status":200,"statustext":"OK","html":"<pre class=\"api-pretty-content\">Format MOCK: &lt;b>ok&lt;/b></pre>","modules":["mediawiki.apipretty"],"continue":null,"time":1234}',
				[ 'wrappedhtml' => 1 ],
				[ 'name' => 'mockfm' ]
			],
			'normal, with set status' => [
				[],
				"Format MOCK: <b>ok</b>",
				[],
				[ 'name' => 'mock', 'status' => 400 ]
			],
			'HTML format, with set status' => [
				[],
				'<pre class="api-pretty-content">Format MOCK: &lt;b>ok&lt;/b></pre>',
				[],
				[ 'name' => 'mockfm', 'status' => 400 ]
			],
			'wrapped HTML format, with set status' => [
				[],
				// phpcs:ignore Generic.Files.LineLength.TooLong
				'{"status":400,"statustext":"Bad Request","html":"<pre class=\"api-pretty-content\">Format MOCK: &lt;b>ok&lt;/b></pre>","modules":["mediawiki.apipretty"],"continue":null,"time":1234}',
				[ 'wrappedhtml' => 1 ],
				[ 'name' => 'mockfm', 'status' => 400 ]
			],
			'wrapped HTML format, cross-domain-policy' => [
				[ 'continue' => '< CrOsS-DoMaIn-PoLiCy >' ],
				// phpcs:ignore Generic.Files.LineLength.TooLong
				'{"status":200,"statustext":"OK","html":"<pre class=\"api-pretty-content\">Format MOCK: &lt;b>ok&lt;/b></pre>","modules":["mediawiki.apipretty"],"continue":"\u003C CrOsS-DoMaIn-PoLiCy \u003E","time":1234}',
				[ 'wrappedhtml' => 1 ],
				[ 'name' => 'mockfm' ]
			],
		];
	}

	/**
	 * @dataProvider provideFilenameEncoding
	 */
	public function testFilenameEncoding( $filename, $expect ) {
		$ret = parent::encodeData( [], [], [
			'name' => 'mock',
			'class' => ApiFormatBase::class,
			'factory' => function ( ApiMain $main, $format ) use ( $filename ) {
				$mock = $this->getMockFormatter( $main, $format, [ 'getFilename' ] );
				$mock->method( 'getFilename' )->willReturn( $filename );
				return $mock;
			},
			'returnPrinter' => true,
		] );
		$response = $ret['printer']->getMain()->getRequest()->response();

		$this->assertSame( "inline; $expect", $response->getHeader( 'Content-Disposition' ) );
	}

	public static function provideFilenameEncoding() {
		return [
			'something simple' => [
				'foo.xyz', 'filename=foo.xyz'
			],
			'more complicated, but still simple' => [
				'foo.!#$%&\'*+-^_`|~', 'filename=foo.!#$%&\'*+-^_`|~'
			],
			'Needs quoting' => [
				'foo\\bar.xyz', 'filename="foo\\\\bar.xyz"'
			],
			'Needs quoting (2)' => [
				'foo (bar).xyz', 'filename="foo (bar).xyz"'
			],
			'Needs quoting (3)' => [
				"foo\t\"b\x5car\"\0.xyz", "filename=\"foo\x5c\t\x5c\"b\x5c\x5car\x5c\"\x5c\0.xyz\""
			],
			'Non-ASCII characters' => [
				'fóo bár.🙌!',
				"filename=\"f\xF3o b\xE1r.?!\"; filename*=UTF-8''f%C3%B3o%20b%C3%A1r.%F0%9F%99%8C!"
			]
		];
	}

	public function testBasics() {
		$printer = $this->getMockFormatter( null, 'mock' );
		$this->assertTrue( $printer->canPrintErrors() );
		$this->assertSame(
			'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Data_formats',
			$printer->getHelpUrls()
		);
	}

	public function testDisable() {
		$this->setMwGlobals( [
			'wgApiFrameOptions' => 'DENY',
		] );

		$printer = $this->getMockFormatter( null, 'mock' );
		$printer->method( 'execute' )->willReturnCallback( function () use ( $printer ) {
			$printer->printText( 'Foo' );
		} );
		$this->assertFalse( $printer->isDisabled() );
		$printer->disable();
		$this->assertTrue( $printer->isDisabled() );

		$printer->setHttpStatus( 400 );
		$printer->initPrinter();
		$printer->execute();
		ob_start();
		$printer->closePrinter();
		$this->assertSame( '', ob_get_clean() );
		$response = $printer->getMain()->getRequest()->response();
		$this->assertNull( $response->getHeader( 'Content-Type' ) );
		$this->assertNull( $response->getHeader( 'X-Frame-Options' ) );
		$this->assertNull( $response->getHeader( 'Content-Disposition' ) );
		$this->assertNull( $response->getStatusCode() );
	}

	public function testNullMimeType() {
		$this->setMwGlobals( [
			'wgApiFrameOptions' => 'DENY',
		] );

		$printer = $this->getMockFormatter( null, 'mock', [ 'getMimeType' ] );
		$printer->method( 'execute' )->willReturnCallback( function () use ( $printer ) {
			$printer->printText( 'Foo' );
		} );
		$printer->method( 'getMimeType' )->willReturn( null );
		$this->assertNull( $printer->getMimeType(), 'sanity check' );

		$printer->initPrinter();
		$printer->execute();
		ob_start();
		$printer->closePrinter();
		$this->assertSame( 'Foo', ob_get_clean() );
		$response = $printer->getMain()->getRequest()->response();
		$this->assertNull( $response->getHeader( 'Content-Type' ) );
		$this->assertNull( $response->getHeader( 'X-Frame-Options' ) );
		$this->assertNull( $response->getHeader( 'Content-Disposition' ) );

		$printer = $this->getMockFormatter( null, 'mockfm', [ 'getMimeType' ] );
		$printer->method( 'execute' )->willReturnCallback( function () use ( $printer ) {
			$printer->printText( 'Foo' );
		} );
		$printer->method( 'getMimeType' )->willReturn( null );
		$this->assertNull( $printer->getMimeType(), 'sanity check' );
		$this->assertTrue( $printer->getIsHtml(), 'sanity check' );

		$printer->initPrinter();
		$printer->execute();
		ob_start();
		$printer->closePrinter();
		$this->assertSame( 'Foo', ob_get_clean() );
		$response = $printer->getMain()->getRequest()->response();
		$this->assertSame(
			'text/html; charset=utf-8', strtolower( $response->getHeader( 'Content-Type' ) )
		);
		$this->assertSame( 'DENY', $response->getHeader( 'X-Frame-Options' ) );
		$this->assertSame(
			'inline; filename=api-result.html', $response->getHeader( 'Content-Disposition' )
		);
	}

	public function testApiFrameOptions() {
		$this->setMwGlobals( [ 'wgApiFrameOptions' => 'DENY' ] );
		$printer = $this->getMockFormatter( null, 'mock' );
		$printer->initPrinter();
		$this->assertSame(
			'DENY',
			$printer->getMain()->getRequest()->response()->getHeader( 'X-Frame-Options' )
		);

		$this->setMwGlobals( [ 'wgApiFrameOptions' => 'SAMEORIGIN' ] );
		$printer = $this->getMockFormatter( null, 'mock' );
		$printer->initPrinter();
		$this->assertSame(
			'SAMEORIGIN',
			$printer->getMain()->getRequest()->response()->getHeader( 'X-Frame-Options' )
		);

		$this->setMwGlobals( [ 'wgApiFrameOptions' => false ] );
		$printer = $this->getMockFormatter( null, 'mock' );
		$printer->initPrinter();
		$this->assertNull(
			$printer->getMain()->getRequest()->response()->getHeader( 'X-Frame-Options' )
		);
	}

	public function testForceDefaultParams() {
		$context = new RequestContext;
		$context->setRequest( new FauxRequest( [ 'foo' => '1', 'bar' => '2', 'baz' => '3' ], true ) );
		$main = new ApiMain( $context );
		$allowedParams = [
			'foo' => [],
			'bar' => [ ApiBase::PARAM_DFLT => 'bar?' ],
			'baz' => 'baz!',
		];

		$printer = $this->getMockFormatter( $main, 'mock', [ 'getAllowedParams' ] );
		$printer->method( 'getAllowedParams' )->willReturn( $allowedParams );
		$this->assertEquals(
			[ 'foo' => '1', 'bar' => '2', 'baz' => '3' ],
			$printer->extractRequestParams(),
			'sanity check'
		);

		$printer = $this->getMockFormatter( $main, 'mock', [ 'getAllowedParams' ] );
		$printer->method( 'getAllowedParams' )->willReturn( $allowedParams );
		$printer->forceDefaultParams();
		$this->assertEquals(
			[ 'foo' => null, 'bar' => 'bar?', 'baz' => 'baz!' ],
			$printer->extractRequestParams()
		);
	}

	public function testGetAllowedParams() {
		$printer = $this->getMockFormatter( null, 'mock' );
		$this->assertSame( [], $printer->getAllowedParams() );

		$printer = $this->getMockFormatter( null, 'mockfm' );
		$this->assertSame( [
			'wrappedhtml' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => 'apihelp-format-param-wrappedhtml',
			]
		], $printer->getAllowedParams() );
	}

	public function testGetExamplesMessages() {
		$printer = TestingAccessWrapper::newFromObject( $this->getMockFormatter( null, 'mock' ) );
		$this->assertSame( [
			'action=query&meta=siteinfo&siprop=namespaces&format=mock'
				=> [ 'apihelp-format-example-generic', 'MOCK' ]
		], $printer->getExamplesMessages() );

		$printer = TestingAccessWrapper::newFromObject( $this->getMockFormatter( null, 'mockfm' ) );
		$this->assertSame( [
			'action=query&meta=siteinfo&siprop=namespaces&format=mockfm'
				=> [ 'apihelp-format-example-generic', 'MOCK' ]
		], $printer->getExamplesMessages() );
	}

	/**
	 * @dataProvider provideHtmlHeader
	 */
	public function testHtmlHeader( $post, $registerNonHtml, $expect ) {
		$context = new RequestContext;
		$request = new FauxRequest( [ 'a' => 1, 'b' => 2 ], $post );
		$request->setRequestURL( '/wx/api.php' );
		$context->setRequest( $request );
		$context->setLanguage( 'qqx' );
		$main = new ApiMain( $context );
		$printer = $this->getMockFormatter( $main, 'mockfm' );
		$mm = $printer->getMain()->getModuleManager();
		$mm->addModule( 'mockfm', 'format', ApiFormatBase::class, function () {
			return $mock;
		} );
		if ( $registerNonHtml ) {
			$mm->addModule( 'mock', 'format', ApiFormatBase::class, function () {
				return $mock;
			} );
		}

		$printer->initPrinter();
		$printer->execute();
		ob_start();
		$printer->closePrinter();
		$text = ob_get_clean();
		$this->assertContains( $expect, $text );
	}

	public static function provideHtmlHeader() {
		return [
			[ false, false, '(api-format-prettyprint-header-only-html: MOCK)' ],
			[ true, false, '(api-format-prettyprint-header-only-html: MOCK)' ],
			// phpcs:ignore Generic.Files.LineLength.TooLong
			[ false, true, '(api-format-prettyprint-header-hyperlinked: MOCK, mock, <a rel="nofollow" class="external free" href="http://example.org/wx/api.php?a=1&amp;b=2&amp;format=mock">http://example.org/wx/api.php?a=1&amp;b=2&amp;format=mock</a>)' ],
			[ true, true, '(api-format-prettyprint-header: MOCK, mock)' ],
		];
	}

}
