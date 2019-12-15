<?php

/**
 * @covers SpecialPageData
 * @group Database
 * @group SpecialPage
 *
 * @author Daniel Kinzler
 */
class SpecialPageDataTest extends SpecialPageTestBase {

	protected function setUp() : void {
		parent::setUp();

		$this->setContentLang( 'qqx' );
	}

	protected function newSpecialPage() {
		$page = new SpecialPageData();

		// why is this needed?
		$page->getContext()->setOutput( new OutputPage( $page->getContext() ) );

		$page->setRequestHandler( new PageDataRequestHandler() );

		return $page;
	}

	public function provideExecute() {
		$cases = [];

		$cases['Empty request'] = [ '', [], [], '!!', 200 ];

		$cases['Only title specified'] = [
			'',
			[ 'target' => 'Helsinki' ],
			[],
			'!!',
			303,
			[ 'Location' => '!.+!' ]
		];

		$cases['Accept only HTML'] = [
			'',
			[ 'target' => 'Helsinki' ],
			[ 'Accept' => 'text/HTML' ],
			'!!',
			303,
			[ 'Location' => '!Helsinki$!' ]
		];

		$cases['Accept only HTML with revid'] = [
			'',
			[
				'target' => 'Helsinki',
				'revision' => '4242',
			],
			[ 'Accept' => 'text/HTML' ],
			'!!',
			303,
			[ 'Location' => '!Helsinki(\?|&)oldid=4242!' ]
		];

		$cases['Nothing specified'] = [
			'main/Helsinki',
			[],
			[],
			'!!',
			303,
			[ 'Location' => '!Helsinki&action=raw!' ]
		];

		$cases['Nothing specified'] = [
			'/Helsinki',
			[],
			[],
			'!!',
			303,
			[ 'Location' => '!Helsinki&action=raw!' ]
		];

		$cases['Invalid Accept header'] = [
			'main/Helsinki',
			[],
			[ 'Accept' => 'text/foobar' ],
			'!!',
			406,
			[],
		];

		return $cases;
	}

	/**
	 * @dataProvider provideExecute
	 *
	 * @param string $subpage The subpage to request (or '')
	 * @param array  $params  Request parameters
	 * @param array  $headers  Request headers
	 * @param string $expRegExp   Regex to match the output against.
	 * @param int    $expCode     Expected HTTP status code
	 * @param array  $expHeaders  Expected HTTP response headers
	 */
	public function testExecute(
		$subpage,
		array $params,
		array $headers,
		$expRegExp,
		$expCode = 200,
		array $expHeaders = []
	) {
		$request = new FauxRequest( $params );
		$request->response()->header( 'Status: 200 OK', true, 200 ); // init/reset

		foreach ( $headers as $name => $value ) {
			$request->setHeader( strtoupper( $name ), $value );
		}

		try {
			/* @var FauxResponse $response */
			list( $output, $response ) = $this->executeSpecialPage( $subpage, $request );

			$this->assertEquals( $expCode, $response->getStatusCode(), "status code" );
			$this->assertRegExp( $expRegExp, $output, "output" );

			foreach ( $expHeaders as $name => $exp ) {
				$value = $response->getHeader( $name );
				$this->assertNotNull( $value, "header: $name" );
				$this->assertIsString( $value, "header: $name" );
				$this->assertRegExp( $exp, $value, "header: $name" );
			}
		} catch ( HttpError $e ) {
			$this->assertEquals( $expCode, $e->getStatusCode(), "status code" );
			$this->assertRegExp( $expRegExp, $e->getHTML(), "error output" );
		}
	}

	public function testSpecialPageWithoutParameters() {
		$request = new FauxRequest();
		$request->response()->header( 'Status: 200 OK', true, 200 ); // init/reset

		list( $output, ) = $this->executeSpecialPage( '', $request );

		$this->assertStringContainsString( '(pagedata-text)', $output );
	}

}
