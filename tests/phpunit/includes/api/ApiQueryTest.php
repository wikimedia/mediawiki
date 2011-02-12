<?php

require_once dirname( __FILE__ ) . '/ApiSetup.php';

/**
 * @group Database
 */
class ApiQueryTest extends ApiTestSetup {

	function setUp() {
		parent::setUp();
		$this->doLogin();
	}

	function testTitlesGetNormalized() {

		global $wgSitename;

		$data = $this->doApiRequest( array(
			'action' => 'query',
			'titles' => 'Project:articleA|article_B' ) );


		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'normalized', $data[0]['query'] );

		$this->assertEquals(
			array(
				'from' => 'Project:articleA',
				'to' => $wgSitename . ':ArticleA'
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

	function testTitlesAreRejectedIfInvalid() {
		$title = false;
		while( !$title || Title::newFromText( $title )->exists() ) {
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

}
