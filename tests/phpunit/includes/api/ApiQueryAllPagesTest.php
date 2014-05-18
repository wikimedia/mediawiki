<?php

/**
 * @group API
 * @group Database
 * @group medium
 */
class ApiQueryAllPagesTest extends ApiTestCase {

	protected function setUp() {
		parent::setUp();
		$this->doLogin();
	}

	/**
	 * @todo give this test a real name explaining what is being tested here
	 */
	public function testBug25702() {
		$title = Title::newFromText( 'Category:Template:xyz' );
		$page = WikiPage::factory( $title );
		$page->doEdit( 'Some text', 'inserting content' );

		$result = $this->doApiRequest( array(
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => NS_CATEGORY,
			'apprefix' => 'Template:x' ) );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allpages', $result[0]['query'] );
		$this->assertNotEquals( 0, count( $result[0]['query']['allpages'] ),
			'allpages list does not contain page Category:Template:xyz' );
	}
}
