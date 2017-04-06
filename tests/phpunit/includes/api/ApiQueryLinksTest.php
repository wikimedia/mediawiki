<?php
/**
 * @group API
 * @group Database
 * @group medium
 */
class ApiQueryLinksTest extends ApiTestCase {

	public function testNoFilter() {
		$existingPageTitle = 'UTPage'; // added in MediaWikiTestCase->addCoreDBData();

		$newTitleString = 'Foo';
		$newPage = WikiPage::factory( Title::newFromText( $newTitleString ) );

		$newPage->doEditContent(
			ContentHandler::makeContent( "[[{$existingPageTitle}]]", $newPage->getTitle() ),
			'adding redlink'
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'generator' => 'links',
			'titles' => $newTitleString ] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );
		$this->assertNotEquals( 0, count( $result[0]['query']['pages'] ),
			'expected a page to be present' );
	}

	public function testFilterExists() {
		$existingPageTitle = 'UTPage'; // added in MediaWikiTestCase->addCoreDBData();

		$newTitleString = 'Foo';
		$newPage = WikiPage::factory( Title::newFromText( $newTitleString ) );

		$newPage->doEditContent(
			ContentHandler::makeContent( "[[{$existingPageTitle}]]", $newPage->getTitle() ),
			'adding redlink'
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'generator' => 'links',
			'gplfilter' => 'exists',
			'titles' => $newTitleString ] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );
		$this->assertNotEquals( 0, count( $result[0]['query']['pages'] ),
			'expected a page to be present' );

		$emptyResult = $this->doApiRequest( [
			'action' => 'query',
			'generator' => 'links',
			'gplfilter' => '!exists',
			'titles' => $newTitleString ] );

		$this->assertArrayHasKey( 'batchcomplete', $emptyResult[0] );
		$this->assertArrayNotHasKey( 'query', $emptyResult[0], 'expected no results' );
	}

	public function testFilterNotExists() {
		$titleString = 'Foo';
		$page = WikiPage::factory( Title::newFromText( $titleString ) );

		$page->doEditContent(
			ContentHandler::makeContent( '[[THISPAGEDOESNOTEXIST]]', $page->getTitle() ),
			'adding redlink'
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'generator' => 'links',
			'gplfilter' => '!exists',
			'titles' => $titleString ] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );
		$this->assertNotEquals( 0, count( $result[0]['query']['pages'] ),
			'expected a missing page' );

		$emptyResult = $this->doApiRequest( [
			'action' => 'query',
			'generator' => 'links',
			'gplfilter' => 'exists',
			'titles' => $titleString ] );

		$this->assertArrayHasKey( 'batchcomplete', $emptyResult[0] );
		$this->assertArrayNotHasKey( 'query', $emptyResult[0], 'expected no results' );
	}
}
