<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiQueryAllPages
 */
class ApiQueryAllPagesTest extends ApiTestCase {
	/**
	 * Test T27702
	 * Prefixes of API search requests are not handled with case sensitivity and may result
	 * in wrong search results
	 */
	public function testPrefixNormalizationSearchBug() {
		$title = Title::newFromText( 'Category:Template:xyz' );
		$page = WikiPage::factory( $title );

		$page->doEditContent(
			ContentHandler::makeContent( 'Some text', $page->getTitle() ),
			'inserting content'
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => NS_CATEGORY,
			'apprefix' => 'Template:x' ] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allpages', $result[0]['query'] );
		$this->assertContains( 'Category:Template:xyz', $result[0]['query']['allpages'][0] );
	}
}
