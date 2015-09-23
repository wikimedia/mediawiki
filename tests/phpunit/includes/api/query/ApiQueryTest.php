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
		$this->setMwGlobals( 'wgHooks', array(
			'InterwikiLoadPrefix' => array(
				function ( $prefix, &$data ) {
					if ( $prefix == 'apiquerytestiw' ) {
						$data = array( 'iw_url' => 'wikipedia' );
					}
					return false;
				}
			)
		) );
	}

	public function testTitlesGetNormalized() {
		global $wgMetaNamespace;

		$this->setMwGlobals( array(
			'wgCapitalLinks' => true,
		) );

		$data = $this->doApiRequest( array(
			'action' => 'query',
			'titles' => 'Project:articleA|article_B' ) );

		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'normalized', $data[0]['query'] );

		// Forge a normalized title
		$to = Title::newFromText( $wgMetaNamespace . ':ArticleA' );

		$this->assertEquals(
			array(
				'from' => 'Project:articleA',
				'to' => $to->getPrefixedText(),
			),
			$data[0]['query']['normalized'][0]
		);

		$this->assertEquals(
			array(
				'from' => 'article_B',
				'to' => 'Article B'
			),
			$data[0]['query']['normalized'][1]
		);
	}

	public function testTitlesAreRejectedIfInvalid() {
		$title = false;
		while ( !$title || Title::newFromText( $title )->exists() ) {
			$title = md5( mt_rand( 0, 10000 ) + rand( 0, 999000 ) );
		}

		$data = $this->doApiRequest( array(
			'action' => 'query',
			'titles' => $title . '|Talk:' ) );

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
		$this->setMwGlobals( array(
			'wgCapitalLinks' => true,
		) );

		$api = new MockApiQueryBase();
		$exceptionCaught = false;
		try {
			$this->assertEquals( $expected, $api->titlePartToKey( $titlePart, $namespace ) );
		} catch ( UsageException $e ) {
			$exceptionCaught = true;
		}
		$this->assertEquals( $expectException, $exceptionCaught,
			'UsageException thrown by titlePartToKey' );
	}

	function provideTestTitlePartToKey() {
		return array(
			array( 'a  b  c', NS_MAIN, 'A_b_c', false ),
			array( 'x', NS_MAIN, 'X', false ),
			array( 'y ', NS_MAIN, 'Y_', false ),
			array( 'template:foo', NS_CATEGORY, 'Template:foo', false ),
			array( 'apiquerytestiw:foo', NS_CATEGORY, 'Apiquerytestiw:foo', false ),
			array( "\xF7", NS_MAIN, null, true ),
			array( 'template:foo', NS_MAIN, null, true ),
			array( 'apiquerytestiw:foo', NS_MAIN, null, true ),
		);
	}

	/**
	 * Test if all classes in the query module manager exists
	 */
	public function testClassNamesInModuleManager() {
		global $wgAutoloadLocalClasses, $wgAutoloadClasses;

		// wgAutoloadLocalClasses has precedence, just like in includes/AutoLoader.php
		$classes = $wgAutoloadLocalClasses + $wgAutoloadClasses;

		$api = new ApiMain(
			new FauxRequest( array( 'action' => 'query', 'meta' => 'siteinfo' ) )
		);
		$queryApi = new ApiQuery( $api, 'query' );
		$modules = $queryApi->getModuleManager()->getNamesWithClasses();
		foreach ( $modules as $name => $class ) {
			$this->assertArrayHasKey(
				$class,
				$classes,
				'Class ' . $class . ' for api module ' . $name . ' not in autoloader (with exact case)'
			);
		}
	}
}
