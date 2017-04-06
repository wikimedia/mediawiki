<?php
/**
 * @group API
 * @group Database
 * @group medium
 */
class ApiQueryLinksTest extends ApiTestCase {
	private $existingPageTitle = 'UTPage'; // added in MediaWikiTestCase->addCoreDBData();
	private $testPageTitle = 'Foo';

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );
		$this->tablesUsed = array_unique(
			array_merge( $this->tablesUsed, [ 'page', 'revision', 'pagelinks' ] )
		);
	}

	protected function setUp() {
		parent::setUp();
		$newPage = WikiPage::factory( Title::newFromText( $this->testPageTitle ) );
		$newPage->doEditContent(
			ContentHandler::makeContent( "[[{$this->existingPageTitle}]] [[THISPAGEDOESNOTEXIST]]",
				$newPage->getTitle() ), 'test page'
		);
	}

	public function testNoFilter() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'generator' => 'links',
			'titles' => $this->testPageTitle ] );

		$this->assertQueryResults( $result, 2 );
	}

	public function testFilterExists() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'generator' => 'links',
			'gplfilter' => 'exists',
			'titles' => $this->testPageTitle ] );

		$this->assertQueryResults( $result, 1 );
	}

	public function testFilterNotExists() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'generator' => 'links',
			'gplfilter' => '!exists',
			'titles' => $this->testPageTitle ] );

		$this->assertQueryResults( $result, 1 );
	}

	private function assertQueryResults( $result, $expected ) {
		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );
		$this->assertEquals( $expected, count( $result[0]['query']['pages'] ) );
	}
}
