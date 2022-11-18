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

		$page->doEditContent(
			ContentHandler::makeContent( 'Some text', $page->getTitle() ),
			'inserting content'
		);

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

	/**
	 * @dataProvider provideSectionNewTestCases
	 * @param string $pageContent
	 * @param string $expectedSectionContent
	 * @group medium
	 */
	public function testSectionNewReturnsEmptyContentForPageWithSection(
		$pageContent,
		$expectedSectionContent
	) {
		$pageName = 'Help:' . __METHOD__;
		$page = $this->getExistingTestPage( $pageName );

		$status = $page->doEditContent(
			ContentHandler::makeContent( $pageContent, $page->getTitle() ),
			'inserting content'
		);

		$response = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $pageName,
			'rvprop' => 'content',
			'rvslots' => 'main',
			'rvsection' => 'new'
		] );

		$this->assertArrayHasKey( 'query', $response[0] );
		$this->assertArrayHasKey( 'pages', $response[0]['query'] );

		$this->assertSame(
			$expectedSectionContent,
			$response[0]['query']['pages'][$page->getId()]['revisions'][0]['slots']['main']['content']
		);
	}

	public function provideSectionNewTestCases() {
		yield 'page with existing section' => [
			"==A section==\ntext",
			''
		];
		yield 'page with no sections' => [
			'This page has no sections',
			'This page has no sections'
		];
	}
}
