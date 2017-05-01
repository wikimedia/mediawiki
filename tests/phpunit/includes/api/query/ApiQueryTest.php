<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiQuery
 */
class ApiQueryTest extends ApiTestCase {
	protected function setUp() {
		parent::setUp();
		$this->doLogin();

		// Setup apiquerytestiw: as interwiki prefix
		$this->setMwGlobals( 'wgHooks', [
			'InterwikiLoadPrefix' => [
				function ( $prefix, &$data ) {
					if ( $prefix == 'apiquerytestiw' ) {
						$data = [ 'iw_url' => 'wikipedia' ];
					}
					return false;
				}
			]
		] );
	}

	public function testTitlesGetNormalized() {
		global $wgMetaNamespace;

		$this->setMwGlobals( [
			'wgCapitalLinks' => true,
		] );

		$data = $this->doApiRequest( [
			'action' => 'query',
			'titles' => 'Project:articleA|article_B' ] );

		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'normalized', $data[0]['query'] );

		// Forge a normalized title
		$to = Title::newFromText( $wgMetaNamespace . ':ArticleA' );

		$this->assertEquals(
			[
				'fromencoded' => false,
				'from' => 'Project:articleA',
				'to' => $to->getPrefixedText(),
			],
			$data[0]['query']['normalized'][0]
		);

		$this->assertEquals(
			[
				'fromencoded' => false,
				'from' => 'article_B',
				'to' => 'Article B'
			],
			$data[0]['query']['normalized'][1]
		);
	}

	public function testTitlesAreRejectedIfInvalid() {
		$title = false;
		while ( !$title || Title::newFromText( $title )->exists() ) {
			$title = md5( mt_rand( 0, 100000 ) );
		}

		$data = $this->doApiRequest( [
			'action' => 'query',
			'titles' => $title . '|Talk:' ] );

		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'pages', $data[0]['query'] );
		$this->assertEquals( 2, count( $data[0]['query']['pages'] ) );

		$this->assertArrayHasKey( -2, $data[0]['query']['pages'] );
		$this->assertArrayHasKey( -1, $data[0]['query']['pages'] );

		$this->assertArrayHasKey( 'missing', $data[0]['query']['pages'][-2] );
		$this->assertArrayHasKey( 'invalid', $data[0]['query']['pages'][-1] );
	}

	/**
	 * Test the ApiBase::titlePartToKey function
	 *
	 * @param string $titlePart
	 * @param int $namespace
	 * @param string $expected
	 * @param string $expectException
	 * @dataProvider provideTestTitlePartToKey
	 */
	function testTitlePartToKey( $titlePart, $namespace, $expected, $expectException ) {
		$this->setMwGlobals( [
			'wgCapitalLinks' => true,
		] );

		$api = new MockApiQueryBase();
		$exceptionCaught = false;
		try {
			$this->assertEquals( $expected, $api->titlePartToKey( $titlePart, $namespace ) );
		} catch ( ApiUsageException $e ) {
			$exceptionCaught = true;
		}
		$this->assertEquals( $expectException, $exceptionCaught,
			'ApiUsageException thrown by titlePartToKey' );
	}

	function provideTestTitlePartToKey() {
		return [
			[ 'a  b  c', NS_MAIN, 'A_b_c', false ],
			[ 'x', NS_MAIN, 'X', false ],
			[ 'y ', NS_MAIN, 'Y_', false ],
			[ 'template:foo', NS_CATEGORY, 'Template:foo', false ],
			[ 'apiquerytestiw:foo', NS_CATEGORY, 'Apiquerytestiw:foo', false ],
			[ "\xF7", NS_MAIN, null, true ],
			[ 'template:foo', NS_MAIN, null, true ],
			[ 'apiquerytestiw:foo', NS_MAIN, null, true ],
		];
	}

	/**
	 * Test if all classes in the query module manager exists
	 */
	public function testClassNamesInModuleManager() {
		$api = new ApiMain(
			new FauxRequest( [ 'action' => 'query', 'meta' => 'siteinfo' ] )
		);
		$queryApi = new ApiQuery( $api, 'query' );
		$modules = $queryApi->getModuleManager()->getNamesWithClasses();

		foreach ( $modules as $name => $class ) {
			$this->assertTrue(
				class_exists( $class ),
				'Class ' . $class . ' for api module ' . $name . ' does not exist (with exact case)'
			);
		}
	}
}
