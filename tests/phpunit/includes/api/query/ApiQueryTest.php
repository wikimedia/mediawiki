<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiQuery
 */
class ApiQueryTest extends ApiTestCase {
	/**
	 * @var array Storage for $wgHooks
	 */
	protected $hooks;

	protected function setUp() {
		global $wgHooks;

		parent::setUp();
		$this->doLogin();

		// Setup en: as interwiki prefix
		$this->hooks = $wgHooks;
		$wgHooks['InterwikiLoadPrefix'][] = function ( $prefix, &$data ) {
			if ( $prefix == 'apiquerytestiw' ) {
				$data = array( 'iw_url' => 'wikipedia' );
			}
			return false;
		};
	}

	protected function tearDown() {
		global $wgHooks;
		$wgHooks = $this->hooks;

		parent::tearDown();
	}

	public function testTitlesGetNormalized() {

		global $wgMetaNamespace;

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
	 * @param string $description
	 * @dataProvider provideTestTitlePartToKey
	 */
	function testTitlePartToKey( $titlePart, $namespace, $expected, $expectException ) {
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
}
