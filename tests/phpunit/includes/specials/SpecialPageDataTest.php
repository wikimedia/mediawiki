<?php

/**
 * @covers SpecialPageData
 *
 * @group Database
 *
 * @group SpecialPage
 *
 * @license GPL-2.0+
 * @author Daniel Kinzler
 */
class SpecialPageDataTest extends SpecialPageTestBase {

	protected function newSpecialPage() {
		$page = new SpecialPageData();

		// why is this needed?
		$page->getContext()->setOutput( new OutputPage( $page->getContext() ) );

		$page->setRequestHandler( new PageDataRequestHandler( 'html' ) );

		return $page;
	}

	public function provideExecute() {
		$cases = [];

		$cases[] = [ '', [], [], '!!', 200 ];

		$cases[] = [ '', [ 'title' => 'Q42' ], [], '!!', 303,  [ 'Location' => '!.+!' ] ];

		$subpageCases = [];
		foreach ( $cases as $c ) {
			$case = $c;
			$case[0] = '';

			if ( isset( $case[1]['title'] ) ) {
				$case[0] .= $case[1]['title'];
				unset( $case[1]['title'] );
			}

			$subpageCases[] = $case;
		}

		$cases = array_merge( $cases, $subpageCases );

		$cases[] = [
			'',
			[ 'title' => 'Q42' ],
			[ 'Accept' => 'text/HTML' ],
			'!!',
			303,
			[ 'Location' => '!Q42$!' ]
		];

		$cases[] = [
			'',
			[
				'title' => 'Q42',
				'revision' => '4242',
			],
			[ 'Accept' => 'text/HTML' ],
			'!!',
			303,
			[ 'Location' => '!Q42(\?|&)oldid=4242!' ]
		];

		$cases[] = [
			'Q42',
			[],
			[],
			'!!',
			303,
			[ 'Location' => '!Q42&action=raw!' ]
		];

		// #31: /Q5 with "Accept: text/foobar" triggers a 406
		$cases[] = [
			'Q42',
			[],
			[ 'Accept' => 'text/foobar' ],
			'!!',
			406,
			[],
		];

		$cases[] = [
			'Q42',
			[],
			[ 'Accept' => 'text/HTML' ],
			'!!',
			303,
			[ 'Location' => '!Q42$!' ]
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
				$this->assertInternalType( 'string', $value, "header: $name" );
				$this->assertRegExp( $exp, $value, "header: $name" );
			}
		} catch ( HttpError $e ) {
			$this->assertEquals( $expCode, $e->getStatusCode(), "status code" );
			$this->assertRegExp( $expRegExp, $e->getHTML(), "error output" );
		}
	}

	public function testSpecialPageWithoutParameters() {
		$this->setContentLang( Language::factory( 'en' ) );
		$request = new FauxRequest();
		$request->response()->header( 'Status: 200 OK', true, 200 ); // init/reset

		list( $output, ) = $this->executeSpecialPage( '', $request );

		$this->assertContains(
			"Content negotiation applies based on you client's Accept header.",
			$output,
			"output"
		);
	}

}
