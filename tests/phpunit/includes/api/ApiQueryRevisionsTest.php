<?php

/**
 * @group API
 * @group Database
 */
class ApiQueryRevisionsTest extends ApiTestCase {

	function setUp() {
		parent::setUp();
		$this->setMwGlobals( 'wgAPIMaxUncachedDiffs', 100 );
	}

	/**
	 * @group medium
	 */
	function testContentComesWithContentModelAndFormat() {

		$pageName = 'Help:' . __METHOD__ ;
		$title = Title::newFromText( $pageName );
		$page = WikiPage::factory( $title );
		$page->doEdit( 'Some text', 'inserting content' );

		$apiResult = $this->doApiRequest( array(
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $pageName,
			'rvprop' => 'content',
		) );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'pages', $apiResult[0]['query'] );
		foreach( $apiResult[0]['query']['pages'] as $page ) {
			$this->assertArrayHasKey( 'revisions', $page );
			foreach( $page['revisions'] as $revision ) {
				$this->assertArrayHasKey( 'contentformat', $revision,
					'contentformat should be included when asking content so'
					. ' client knows how to interpretate it'
				);
				$this->assertArrayHasKey( 'contentmodel', $revision,
					'contentmodel should be included when asking content so'
					. ' client knows how to interpretate it'
				);
			}
		}
	}

	function testMissingContent() {
		$pageName = 'Help:' . __METHOD__ ;
		$title = Title::newFromText( $pageName );
		$page = WikiPage::factory( $title );
		$page->doEdit( 'Some text', 'inserting content' );
		$page->clear();
		$rev_id1 = $page->getRevision()->getId();
		$page->doEdit( 'Some text 2', 'inserting more content' );
		$page->clear();
		$rev_id2 = $page->getRevision()->getId();

		// Now, fake up a broken revision
		$dbw = wfGetDB( DB_MASTER );
		$text_id = $dbw->selectField( 'revision', 'rev_text_id',
			array( 'rev_id' => $rev_id2 ), __METHOD__ );
		$dbw->update( 'text',
			array( 'old_text' => '', 'old_flags' => 'external' ),
			array( 'old_id' => $text_id ) );
		$page->clear();

		// Check fetching content
		$apiResult = $this->doApiRequest( array(
			'action' => 'query',
			'prop' => 'revisions',
			'revids' => $rev_id2,
			'rvprop' => 'content',
		) );

		$this->assertArrayHasKey( 'warnings', $apiResult[0] );
		$this->assertArrayHasKey( 'revisions', $apiResult[0]['warnings'] );
		$this->assertEquals( "Revision content not found for revision $rev_id2",
			$apiResult[0]['warnings']['revisions']['*'] );

		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'pages', $apiResult[0]['query'] );
		foreach( $apiResult[0]['query']['pages'] as $page ) {
			$this->assertArrayHasKey( 'revisions', $page );
			foreach( $page['revisions'] as $revision ) {
				$this->assertArrayHasKey( '*', $revision );
			}
		}

		// Check various permutations of diffs
		$apiResult = $this->doApiRequest( array(
			'action' => 'query',
			'prop' => 'revisions',
			'revids' => $rev_id2,
			'rvdiffto' => $rev_id1,
		) );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'pages', $apiResult[0]['query'] );
		foreach( $apiResult[0]['query']['pages'] as $page ) {
			$this->assertArrayHasKey( 'revisions', $page );
			foreach( $page['revisions'] as $revision ) {
				$this->assertArrayHasKey( 'diff', $revision );
				$this->assertArrayHasKey( 'from', $revision['diff'] );
				$this->assertEquals( $revision['diff']['from'], $rev_id2 );
				$this->assertArrayHasKey( 'to', $revision['diff'] );
				$this->assertEquals( $revision['diff']['to'], $rev_id1 );
				$this->assertArrayHasKey( '*', $revision['diff'] );
			}
		}

		$apiResult = $this->doApiRequest( array(
			'action' => 'query',
			'prop' => 'revisions',
			'revids' => $rev_id1,
			'rvdiffto' => $rev_id2,
		) );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'pages', $apiResult[0]['query'] );
		foreach( $apiResult[0]['query']['pages'] as $page ) {
			$this->assertArrayHasKey( 'revisions', $page );
			foreach( $page['revisions'] as $revision ) {
				$this->assertArrayHasKey( 'diff', $revision );
				$this->assertArrayHasKey( 'from', $revision['diff'] );
				$this->assertEquals( $revision['diff']['from'], $rev_id1 );
				$this->assertArrayHasKey( 'to', $revision['diff'] );
				$this->assertEquals( $revision['diff']['to'], $rev_id2 );
				$this->assertArrayHasKey( '*', $revision['diff'] );
			}
		}

		$apiResult = $this->doApiRequest( array(
			'action' => 'query',
			'prop' => 'revisions',
			'revids' => $rev_id2,
			'rvdifftotext' => 'Hello',
		) );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'pages', $apiResult[0]['query'] );
		foreach( $apiResult[0]['query']['pages'] as $page ) {
			$this->assertArrayHasKey( 'revisions', $page );
			foreach( $page['revisions'] as $revision ) {
				$this->assertArrayHasKey( 'diff', $revision );
				$this->assertArrayHasKey( '*', $revision['diff'] );
			}
		}
	}
}
