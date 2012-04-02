<?php

/**
 * @group API
 * @group Database
 */
class ApiQueryTest extends ApiTestCase {

	function setUp() {
		parent::setUp();
		$this->doLogin();
	}

	function testTitlesGetNormalized() {

		global $wgMetaNamespace;

		$data = $this->doApiRequest( array(
			'action' => 'query',
			'titles' => 'Project:articleA|article_B' ) );


		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'normalized', $data[0]['query'] );

		// Forge a normalized title
		$to = Title::newFromText( $wgMetaNamespace.':ArticleA' );

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
