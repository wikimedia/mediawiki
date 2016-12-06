<?php

/**
 * Test class for SpecialRecentchanges class
 *
 * @group Database
 *
 * @covers SpecialRecentChanges
 */
class SpecialRecentchangesTest extends AbstractChangesListSpecialPageTestCase {
	protected function setUp() {
		parent::setUp();

		# setup the CLSP object
		$this->changesListSpecialPage = TestingAccessWrapper::newFromObject(
			new SpecialRecentchanges
		);
	}

	public function provideParseParameters() {
		return [
			[ 'limit=123', [ 'limit' => '123' ] ],

			[ '234', [ 'limit' => '234' ] ],

			[ 'days=3', [ 'days' => '3' ] ],

			[ 'namespace=5', [ 'namespace' => 5 ] ],

			[ 'tagfilter=foo', [ 'tagfilter' => 'foo' ] ],
		];
	}

	public function validateOptionsProvider() {
		return [
			[
				[ 'hidemyself', 'hidebyothers' ],
				true,
				[],
			],
			[
				[ 'hidebots', 'hidehumans' ],
				true,
				[],
			],
			[
				[ 'hidepatrolled', 'hideunpatrolled' ],
				true,
				[],
			],
		];
	}

	/**
	 * @dataProvider validateOptionsProvider
	 */
	public function testValidateOptions( $optionsToSet, $expectedRedirect, $expectedRedirectOptions ) {
		$redirectQuery = [];
		$redirected = false;
		$output = $this->getMockBuilder( OutputPage::class )
			->disableProxyingToOriginalMethods()
			->disableOriginalConstructor()
			->getMock();
		$output->method( 'redirect' )->willReturnCallback(
			function ( $url ) use ( &$redirectQuery, &$redirected ) {
				$urlParts = wfParseUrl( $url );
				$query = isset( $urlParts[ 'query' ] ) ? $urlParts[ 'query' ] : '';
				parse_str( $query, $redirectQuery );
				$redirected = true;
			}
		);
		$ctx = new RequestContext();
		$ctx->setOutput( $output );
		$rc = new SpecialRecentChanges();
		$rc->setContext( $ctx );
		$opts = $rc->getDefaultOptions();

		foreach ( $optionsToSet as $option ) {
			$opts->setValue( $option, true );
		}

		$rc->validateOptions( $opts );

		$this->assertEquals( $expectedRedirect, $redirected, 'redirection' );

		if ( $expectedRedirect ) {
			$this->assertArrayEquals( $expectedRedirectOptions, $redirectQuery, 'redirection query' );
		}
	}

}
