<?php

use MediaWiki\Storage\SlotRecord;

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
		$page = $this->getExistingTestPage( $pageName );
		$user = $this->getTestUser()->getUser();
		$page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new WikitextContent( 'Some text' ) )
			->saveRevision( CommentStoreComment::newUnsavedComment( 'inserting content' ) );

		$apiResult = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $pageName,
			'rvprop' => 'content',
			'rvslots' => 'main',
		] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'pages', $apiResult[0]['query'] );
		foreach ( $apiResult[0]['query']['pages'] as $page ) {
			$this->assertArrayHasKey( 'revisions', $page );
			foreach ( $page['revisions'] as $revision ) {
				$this->assertArrayHasKey( 'slots', $revision );
				$this->assertArrayHasKey( 'main', $revision['slots'] );
				$this->assertArrayHasKey( 'contentformat', $revision['slots']['main'],
					'contentformat should be included when asking content so client knows how to interpret it'
				);
				$this->assertArrayHasKey( 'contentmodel', $revision['slots']['main'],
					'contentmodel should be included when asking content so client knows how to interpret it'
				);
			}
		}
	}
}
