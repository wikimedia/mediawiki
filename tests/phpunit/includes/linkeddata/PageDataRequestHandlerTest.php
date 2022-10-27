<?php

use MediaWiki\MainConfigNames;

/**
 * @covers PageDataRequestHandler
 * @group PageData
 */
class PageDataRequestHandlerTest extends \MediaWikiLangTestCase {

	/**
	 * @var Title
	 */
	private $interfaceTitle;

	/**
	 * @var int
	 */
	private $obLevel;

	protected function setUp(): void {
		parent::setUp();

		$this->interfaceTitle = Title::newFromText( __CLASS__ );
		$this->obLevel = ob_get_level();

		$this->overrideConfigValue( MainConfigNames::ArticlePath, '/wiki/$1' );
	}

	protected function tearDown(): void {
		$obLevel = ob_get_level();

		while ( ob_get_level() > $this->obLevel ) {
			ob_end_clean();
		}

		if ( $obLevel !== $this->obLevel ) {
			$this->fail( "Test changed output buffer level: was {$this->obLevel}" .
				"before test, but $obLevel after test."
			);
		}

		parent::tearDown();
	}

	/**
	 * @return PageDataRequestHandler
	 */
	protected function newHandler() {
		return new PageDataRequestHandler();
	}

	/**
	 * @param array $params
	 * @param string[] $headers
	 *
	 * @return OutputPage
	 */
	protected function makeOutputPage( array $params, array $headers ) {
		// construct request
		$request = new FauxRequest( $params );
		$request->response()->header( 'Status: 200 OK', true, 200 ); // init/reset

		foreach ( $headers as $name => $value ) {
			$request->setHeader( strtoupper( $name ), $value );
		}

		// construct Context and OutputPage
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );

		$output = new OutputPage( $context );
		$output->setTitle( $this->interfaceTitle );
		$context->setOutput( $output );

		return $output;
	}

	public function handleRequestProvider() {
		$cases = [];

		$cases[] = [ '', [], [], 'Invalid title', 400 ];

		$cases[] = [
			'',
			[ 'target' => 'Helsinki' ],
			[],
			'',
			303,
			[ 'Location' => '?title=Helsinki&action=raw' ]
		];

		$subpageCases = [];
		foreach ( $cases as $c ) {
			$case = $c;
			$case[0] = 'main/';

			if ( isset( $case[1]['target'] ) ) {
				$case[0] .= $case[1]['target'];
				unset( $case[1]['target'] );
			}

			$subpageCases[] = $case;
		}

		$cases = array_merge( $cases, $subpageCases );

		$cases[] = [
			'',
			[ 'target' => 'Helsinki' ],
			[ 'Accept' => 'text/HTML' ],
			'',
			303,
			[ 'Location' => '/wiki/Helsinki' ]
		];

		$cases[] = [
			'',
			[
				'target' => 'Helsinki',
				'revision' => '4242',
			],
			[ 'Accept' => 'text/HTML' ],
			'',
			303,
			[ 'Location' => '?title=Helsinki&oldid=4242' ]
		];

		$cases[] = [
			'/Helsinki',
			[],
			[],
			'',
			303,
			[ 'Location' => '?title=Helsinki&action=raw' ]
		];

		// #31: /Q5 with "Accept: text/foobar" triggers a 406
		$cases[] = [
			'main/Helsinki',
			[],
			[ 'Accept' => 'text/foobar' ],
			'No matching format found',
			406,
		];

		$cases[] = [
			'no slash',
			[],
			[ 'Accept' => 'text/HTML' ],
			'Invalid title',
			400,
		];

		$cases[] = [
			'main',
			[],
			[ 'Accept' => 'text/HTML' ],
			'Invalid title',
			400,
		];

		$cases[] = [
			'xyz/Helsinki',
			[],
			[ 'Accept' => 'text/HTML' ],
			'Invalid title',
			400,
		];

		$cases[] = [
			'main/Helsinki',
			[],
			[ 'Accept' => 'text/HTML' ],
			'',
			303,
			[ 'Location' => '/wiki/Helsinki' ]
		];

		$cases[] = [
			'/Helsinki',
			[],
			[ 'Accept' => 'text/HTML' ],
			'',
			303,
			[ 'Location' => '/wiki/Helsinki' ]
		];

		$cases[] = [
			'main/AC/DC',
			[],
			[ 'Accept' => 'text/HTML' ],
			'',
			303,
			[ 'Location' => '/wiki/AC/DC' ]
		];

		return $cases;
	}

	/**
	 * @dataProvider handleRequestProvider
	 *
	 * @param string $subpage The subpage to request (or '')
	 * @param array $params Request parameters
	 * @param array $headers Request headers
	 * @param string $expectedOutput
	 * @param int $expectedStatusCode Expected HTTP status code.
	 * @param string[] $expectedHeaders Expected HTTP response headers.
	 */
	public function testHandleRequest(
		$subpage,
		array $params,
		array $headers,
		$expectedOutput = '',
		$expectedStatusCode = 200,
		array $expectedHeaders = []
	) {
		$output = $this->makeOutputPage( $params, $headers );
		$request = $output->getRequest();

		/** @var FauxResponse $response */
		$response = $request->response();

		// construct handler
		$handler = $this->newHandler();

		try {
			ob_start();
			$handler->handleRequest( $subpage, $request, $output );

			if ( $output->getRedirect() !== '' ) {
				// hack to apply redirect to web response
				$output->output();
			}

			$text = ob_get_clean();

			$this->assertEquals( $expectedStatusCode, $response->getStatusCode(), 'status code' );
			$this->assertSame( $expectedOutput, $text, 'output' );

			foreach ( $expectedHeaders as $name => $exp ) {
				$value = $response->getHeader( $name );
				$this->assertNotNull( $value, "header: $name" );
				$this->assertIsString( $value, "header: $name" );
				$this->assertStringEndsWith( $exp, $value, "header: $name" );
			}
		} catch ( HttpError $e ) {
			ob_end_clean();
			$this->assertEquals( $expectedStatusCode, $e->getStatusCode(), 'status code' );
			$this->assertStringContainsString( $expectedOutput, $e->getHTML(), 'error output' );
		}

		// We always set "Access-Control-Allow-Origin: *"
		$this->assertSame( '*', $response->getHeader( 'Access-Control-Allow-Origin' ) );
	}

	public function provideHttpContentNegotiation() {
		$helsinki = Title::makeTitle( NS_MAIN, 'Helsinki' );
		return [
			'Accept Header of HTML' => [
				$helsinki,
				[ 'ACCEPT' => 'text/html' ], // headers
				'Helsinki'
			],
			'Accept Header without weights' => [
				$helsinki,
				[ 'ACCEPT' => '*/*, text/html, text/x-wiki' ],
				'Helsinki&action=raw'
			],
			'Accept Header with weights' => [
				$helsinki,
				[ 'ACCEPT' => 'text/*; q=0.5, text/json; q=0.7, application/rdf+xml; q=0.8' ],
				'Helsinki&action=raw'
			],
			'Accept Header accepting evertyhing and HTML' => [
				$helsinki,
				[ 'ACCEPT' => 'text/html, */*' ],
				'Helsinki&action=raw'
			],
			'No Accept Header' => [
				$helsinki,
				[],
				'Helsinki&action=raw'
			],
		];
	}

	/**
	 * @dataProvider provideHttpContentNegotiation
	 *
	 * @param Title $title
	 * @param array $headers Request headers
	 * @param string $expectedRedirectSuffix Expected suffix of the HTTP Location header.
	 */
	public function testHttpContentNegotiation(
		Title $title,
		array $headers,
		$expectedRedirectSuffix
	) {
		/** @var FauxResponse $response */
		$output = $this->makeOutputPage( [], $headers );
		$request = $output->getRequest();

		$handler = $this->newHandler();
		$handler->httpContentNegotiation( $request, $output, $title );

		$this->assertStringEndsWith(
			$expectedRedirectSuffix,
			$output->getRedirect(),
			'redirect target'
		);
	}
}
