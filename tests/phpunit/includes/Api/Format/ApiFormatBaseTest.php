<?php

namespace MediaWiki\Tests\Api\Format;

use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiFormatBase;
use MediaWiki\Api\ApiMain;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @group Database
 * @covers \MediaWiki\Api\ApiFormatBase
 */
class ApiFormatBaseTest extends ApiFormatTestBase {

	/** @inheritDoc */
	protected $printerName = 'mockbase';

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( MainConfigNames::Server, 'http://example.org' );
	}

	/**
	 * @param ApiMain|null $main
	 * @param string $format
	 * @param array $methods
	 * @return ApiFormatBase|MockObject
	 */
	public function getMockFormatter( ?ApiMain $main, $format, $methods = [] ) {
		if ( $main === null ) {
			$context = new RequestContext;
			$context->setRequest( new FauxRequest( [], true ) );
			$main = new ApiMain( $context );
		}

		$mock = $this->getMockBuilder( ApiFormatBase::class )
			->setConstructorArgs( [ $main, $format ] )
			->onlyMethods( array_unique( array_merge( $methods, [ 'getMimeType', 'execute' ] ) ) )
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
					->willReturnCallback( static function () use ( $mock ) {
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

		$this->overrideConfigValue( MainConfigNames::ApiFrameOptions, 'DENY' );

		$ret = parent::encodeData( $params, $data, $options );
		/** @var ApiFormatBase $printer */
		$printer = $ret['printer'];
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
				'{"status":400,"statustext":"Bad Request","html":"<pre class=\"api-pretty-content\">Format MOCK: &lt;b>ok&lt;/b></pre>","modules":["mediawiki.apipretty"],"continue":null,"time":1234}',
				[ 'wrappedhtml' => 1 ],
				[ 'name' => 'mockfm', 'status' => 400 ]
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
		$this->overrideConfigValue( MainConfigNames::ApiFrameOptions, 'DENY' );

		$printer = $this->getMockFormatter( null, 'mock' );
		$printer->method( 'execute' )->willReturnCallback( static function () use ( $printer ) {
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
		$this->overrideConfigValue( MainConfigNames::ApiFrameOptions, 'DENY' );

		$printer = $this->getMockFormatter( null, 'mock', [ 'getMimeType' ] );
		$printer->method( 'execute' )->willReturnCallback( static function () use ( $printer ) {
			$printer->printText( 'Foo' );
		} );
		$printer->method( 'getMimeType' )->willReturn( null );
		$this->assertNull( $printer->getMimeType() );

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
		$printer->method( 'execute' )->willReturnCallback( static function () use ( $printer ) {
			$printer->printText( 'Foo' );
		} );
		$printer->method( 'getMimeType' )->willReturn( null );
		$this->assertNull( $printer->getMimeType() );
		$this->assertTrue( $printer->getIsHtml() );

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

	public static function provideApiFrameOptions() {
		yield 'Override ApiFrameOptions to DENY' => [ 'DENY', 'DENY' ];
		yield 'Override ApiFrameOptions to SAMEORIGIN' => [ 'SAMEORIGIN', 'SAMEORIGIN' ];
		yield 'Override ApiFrameOptions to false' => [ false, null ];
	}

	/**
	 * @dataProvider provideApiFrameOptions
	 */
	public function testApiFrameOptions( $customConfig, $expectedHeader ) {
		$this->overrideConfigValue( MainConfigNames::ApiFrameOptions, $customConfig );
		$printer = $this->getMockFormatter( null, 'mock' );
		$printer->initPrinter();
		$this->assertSame(
			$expectedHeader,
			$printer->getMain()->getRequest()->response()->getHeader( 'X-Frame-Options' )
		);
	}

	public function testForceDefaultParams() {
		$context = new RequestContext;
		$context->setRequest( new FauxRequest( [ 'foo' => '1', 'bar' => '2', 'baz' => '3' ], true ) );
		$main = new ApiMain( $context );
		$allowedParams = [
			'foo' => [],
			'bar' => [ ParamValidator::PARAM_DEFAULT => 'bar?' ],
			'baz' => 'baz!',
		];

		$printer = $this->getMockFormatter( $main, 'mock', [ 'getAllowedParams' ] );
		$printer->method( 'getAllowedParams' )->willReturn( $allowedParams );
		$this->assertEquals(
			[ 'foo' => '1', 'bar' => '2', 'baz' => '3' ],
			$printer->extractRequestParams()
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
				ParamValidator::PARAM_DEFAULT => false,
				ApiBase::PARAM_HELP_MSG => 'apihelp-format-param-wrappedhtml',
			]
		], $printer->getAllowedParams() );
	}

	public function testGetExamplesMessages() {
		/** @var ApiFormatBase $printer */
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
		$mm->addModule( 'mockfm', 'format', [
			'class' => ApiFormatBase::class,
			'factory' => static function () {
				return $mock;
			}
		] );
		if ( $registerNonHtml ) {
			$mm->addModule( 'mock', 'format', [
				'class' => ApiFormatBase::class,
				'factory' => static function () {
					return $mock;
				}
			] );
		}

		$printer->initPrinter();
		$printer->execute();
		ob_start();
		$printer->closePrinter();
		$text = ob_get_clean();
		$this->assertStringContainsString( $expect, $text );
		$this->assertSame( 'private, must-revalidate, max-age=0', $main->getContext()->getRequest()->response()->getHeader( 'Cache-Control' ) );
	}

	public static function provideHtmlIsPrivate() {
		yield [ 'private', 'private' ];
		yield [ 'public', 'anon-public-user-private' ];
	}

	/**
	 * Assert that HTML output is not cacheable (T354045).
	 * @dataProvider provideHtmlIsPrivate
	 */
	public function testHtmlIsPrivate( $moduleCacheMode, $expectedCacheMode ) {
		$context = new RequestContext;
		$request = new FauxRequest( [ 'uselang' => 'qqx' ] );
		$request->setRequestURL( '/wx/api.php' );
		$context->setRequest( $request );
		$context->setLanguage( 'qqx' );
		$main = new ApiMain( $context );

		$printer = $this->getMockFormatter( $main, 'mockfm' );
		$mm = $printer->getMain()->getModuleManager();
		$mm->addModule( 'mockfm', 'format', [
			'class' => ApiFormatBase::class,
			'factory' => static function () {
				return $mock;
			}
		] );

		// pretend the output is cacheable
		$main->setCacheMode( $moduleCacheMode );
		$printer->initPrinter();

		$mainAccess = TestingAccessWrapper::newFromObject( $main );
		$this->assertSame( $expectedCacheMode, $main->getCacheMode() );

		$mainAccess->sendCacheHeaders( false );
		$this->assertSame(
			'private, must-revalidate, max-age=0',
			$request->response()->getHeader( 'cache-control' )
		);
	}

	public static function provideHtmlHeader() {
		return [
			[ false, false, '(api-format-prettyprint-header-only-html: MOCK)' ],
			[ true, false, '(api-format-prettyprint-header-only-html: MOCK)' ],
			[ false, true, '(api-format-prettyprint-header-hyperlinked: MOCK, mock, <a rel="nofollow" class="external free" href="http://example.org/wx/api.php?a=1&amp;b=2&amp;format=mock">http://example.org/wx/api.php?a=1&amp;b=2&amp;format=mock</a>)' ],
			[ true, true, '(api-format-prettyprint-header: MOCK, mock)' ],
		];
	}

}
