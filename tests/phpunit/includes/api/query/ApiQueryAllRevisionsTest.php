<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiQueryAllRevisions
 */
class ApiQueryAllRevisionsTest extends ApiTestCase {

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed[] = 'revision';
	}

	/**
	 * @group medium
	 */
	public function testContentComesWithContentModelAndFormat() {
		$pageName = 'Help:' . __METHOD__;
		$title = Title::newFromText( $pageName );
		$page = WikiPage::factory( $title );

		$page->doEditContent(
			ContentHandler::makeContent( 'Some text', $page->getTitle() ),
			'inserting content'
		);
		$page->doEditContent(
			ContentHandler::makeContent( 'Some other text', $page->getTitle() ),
			'adding revision'
		);

		$apiResult = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allrevisions',
			'arvprop' => 'content',
			'arvslots' => 'main',
			'arvdir' => 'older',
		] );

		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'allrevisions', $apiResult[0]['query'] );
		$this->assertArrayHasKey( 0, $apiResult[0]['query']['allrevisions'] );
		$this->assertArrayHasKey( 'title', $apiResult[0]['query']['allrevisions'][0] );
		$this->assertSame( $pageName, $apiResult[0]['query']['allrevisions'][0]['title'] );
		$this->assertArrayHasKey( 'revisions', $apiResult[0]['query']['allrevisions'][0] );
		$this->assertCount( 2, $apiResult[0]['query']['allrevisions'][0]['revisions'] );

		foreach ( $apiResult[0]['query']['allrevisions'] as $page ) {
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
