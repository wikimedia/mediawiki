<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiQueryRevisions
 */
class ApiQueryRevisionsTest extends ApiTestCase {

	/**
	 * @group medium
	 */
	public function testContentComesWithContentModelAndFormat() {
		$pageName = 'Help:' . __METHOD__;
		$title = Title::newFromText( $pageName );
		$page = WikiPage::factory( $title );
		$page->doEdit( 'Some text', 'inserting content' );

		$apiResult = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $pageName,
			'rvprop' => 'content',
		] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'pages', $apiResult[0]['query'] );
		foreach ( $apiResult[0]['query']['pages'] as $page ) {
			$this->assertArrayHasKey( 'revisions', $page );
			foreach ( $page['revisions'] as $revision ) {
				$this->assertArrayHasKey( 'contentformat', $revision,
					'contentformat should be included when asking content so client knows how to interpret it'
				);
				$this->assertArrayHasKey( 'contentmodel', $revision,
					'contentmodel should be included when asking content so client knows how to interpret it'
				);
			}
		}
	}

	public function testStructuredContent() {
		$jsonName = 'User:FooBar/testStructuredContent.json';
		WikiPage::factory( Title::newFromText( $jsonName ) )
			->doEditContent( new JsonContent( '{"test":"bar"}' ), 'summary' );

		$wikitextName = 'Help:' . __METHOD__;
		WikiPage::factory( Title::newFromText( $wikitextName ) )
			->doEditContent( new WikitextContent( 'foobar' ), 'summary' );

		$apiResult = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => "$jsonName|$wikitextName",
			'rvprop' => 'structuredcontent',
		] );

		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'pages', $apiResult[0]['query'] );

		foreach ( $apiResult[0]['query']['pages'] as $info ) {
			$rev = $info['revisions'][0];
			if ( $info['title'] === $jsonName ) {
				$this->assertArrayHasKey( 'contentmodel', $rev );
				$this->assertEquals( 'json', $rev['contentmodel'] );
				$this->assertArrayHasKey( 'content', $rev );
				$this->assertArrayHasKey( 'test', $rev['content'] );
				$this->assertEquals( 'bar', $rev['content']['test'] );
			} elseif ( $info['title'] === $wikitextName ) {
				$this->assertArrayHasKey( 'contentmodel', $rev );
				$this->assertEquals( 'wikitext', $rev['contentmodel'] );
				$this->assertArrayHasKey( 'content', $rev );
				$this->assertEquals( 'foobar', $rev['content'] );
			} else {
				$this->assertTrue( false, "Unexpected title {$info['title']}" );
			}
		}
	}
}
