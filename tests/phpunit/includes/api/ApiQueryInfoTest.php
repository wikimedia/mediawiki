<?php

use MediaWiki\Block\DatabaseBlock;

/**
 * @group API
 * @group medium
 * @group Database
 *
 * @coversDefaultClass ApiQueryInfo
 */
class ApiQueryInfoTest extends ApiTestCase {

	/**
	 * @covers ::execute
	 * @covers ::extractPageInfo
	 */
	public function testExecute() {
		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		list( $data ) = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'info',
				'titles' => $title->getText(),
		] );

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'pages', $data['query'] );
		$this->assertArrayHasKey( $page->getId(), $data['query']['pages'] );

		$info = $data['query']['pages'][$page->getId()];
		$this->assertSame( $page->getId(), $info['pageid'] );
		$this->assertSame( $title->getNamespace(), $info['ns'] );
		$this->assertSame( $title->getText(), $info['title'] );
		$this->assertSame( $title->getContentModel(), $info['contentmodel'] );
		$this->assertSame( $title->getPageLanguage()->getCode(), $info['pagelanguage'] );
		$this->assertSame( $title->getPageLanguage()->getHtmlCode(), $info['pagelanguagehtmlcode'] );
		$this->assertSame( $title->getPageLanguage()->getDir(), $info['pagelanguagedir'] );
		$this->assertSame( wfTimestamp( TS_ISO_8601, $title->getTouched() ), $info['touched'] );
		$this->assertSame( $title->getLatestRevID(), $info['lastrevid'] );
		$this->assertSame( $title->getLength(), $info['length'] );
		$this->assertSame( $title->isNewPage(), $info['new'] );
		$this->assertArrayNotHasKey( 'actions', $info );
	}

	/**
	 * @covers ::execute
	 * @covers ::extractPageInfo
	 */
	public function testExecuteEditActions() {
		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		list( $data ) = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'info',
				'titles' => $title->getText(),
				'intestactions' => 'edit'
		] );

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'pages', $data['query'] );
		$this->assertArrayHasKey( $page->getId(), $data['query']['pages'] );

		$info = $data['query']['pages'][$page->getId()];
		$this->assertArrayHasKey( 'actions', $info );
		$this->assertArrayHasKey( 'edit', $info['actions'] );
		$this->assertTrue( $info['actions']['edit'] );
	}

	/**
	 * @covers ::execute
	 * @covers ::extractPageInfo
	 */
	public function testExecuteEditActionsFull() {
		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		list( $data ) = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'info',
				'titles' => $title->getText(),
				'intestactions' => 'edit',
				'intestactionsdetail' => 'full',
		] );

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'pages', $data['query'] );
		$this->assertArrayHasKey( $page->getId(), $data['query']['pages'] );

		$info = $data['query']['pages'][$page->getId()];
		$this->assertArrayHasKey( 'actions', $info );
		$this->assertArrayHasKey( 'edit', $info['actions'] );
		$this->assertIsArray( $info['actions']['edit'] );
		$this->assertSame( [], $info['actions']['edit'] );
	}

	/**
	 * @covers ::execute
	 * @covers ::extractPageInfo
	 */
	public function testExecuteEditActionsFullBlock() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = new DatabaseBlock( [
			'address' => $badActor->getName(),
			'user' => $badActor->getId(),
			'by' => $sysop->getId(),
			'expiry' => 'infinity',
			'sitewide' => 1,
			'enableAutoblock' => true,
		] );

		$block->insert();

		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		list( $data ) = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'info',
				'titles' => $title->getText(),
				'intestactions' => 'edit',
				'intestactionsdetail' => 'full',
		], null, false, $badActor );

		$block->delete();

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'pages', $data['query'] );
		$this->assertArrayHasKey( $page->getId(), $data['query']['pages'] );

		$info = $data['query']['pages'][$page->getId()];
		$this->assertArrayHasKey( 'actions', $info );
		$this->assertArrayHasKey( 'edit', $info['actions'] );
		$this->assertIsArray( $info['actions']['edit'] );
		$this->assertArrayHasKey( 0, $info['actions']['edit'] );
		$this->assertArrayHasKey( 'code', $info['actions']['edit'][0] );
		$this->assertSame( 'blocked', $info['actions']['edit'][0]['code'] );
		$this->assertArrayHasKey( 'data', $info['actions']['edit'][0] );
		$this->assertArrayHasKey( 'blockinfo', $info['actions']['edit'][0]['data'] );
		$this->assertArrayHasKey( 'blockid', $info['actions']['edit'][0]['data']['blockinfo'] );
		$this->assertSame( $block->getId(), $info['actions']['edit'][0]['data']['blockinfo']['blockid'] );
	}

}
