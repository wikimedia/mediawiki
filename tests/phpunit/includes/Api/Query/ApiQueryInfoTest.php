<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group API
 * @group medium
 * @group Database
 *
 * @covers \MediaWiki\Api\ApiQueryInfo
 */
class ApiQueryInfoTest extends ApiTestCase {
	use TempUserTestTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::WatchlistExpiry => true,
			MainConfigNames::WatchlistExpiryMaxDuration => '6 months',
		] );
	}

	public function testExecute() {
		// Mock time for a deterministic result, without cut off from dynamic "max duration"
		ConvertibleTimestamp::setFakeTime( '2011-01-01T00:00:00Z' );

		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();
		$user = $this->getTestUser()->getUser();
		RequestContext::getMain()->setUser( $user );
		$this->getServiceContainer()->getWatchlistManager()->addWatch(
			$user,
			$title,
			// 3 months later
			'2011-04-01T00:00:00Z'
		);

		[ $data ] = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'info',
				'inprop' => 'watched|notificationtimestamp',
				'titles' => $title->getText() . '|' . 'NonExistingPage_lkasdoiewlmasdoiwem7483',
		], null, false, $user );

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'pages', $data['query'] );
		$this->assertArrayHasKey( $page->getId(), $data['query']['pages'] );

		$info = $data['query']['pages'][$page->getId()];
		$this->assertSame( $page->getId(), $info['pageid'] );
		$this->assertSame( NS_MAIN, $info['ns'] );
		$this->assertSame( 'Pluto', $info['title'] );
		$this->assertSame( 'wikitext', $info['contentmodel'] );
		$this->assertSame( 'en', $info['pagelanguage'] );
		$this->assertSame( 'en', $info['pagelanguagehtmlcode'] );
		$this->assertSame( 'ltr', $info['pagelanguagedir'] );
		$this->assertSame( '2011-01-01T00:00:00Z', $info['touched'] );
		$this->assertSame( $title->getLatestRevID(), $info['lastrevid'] );
		$this->assertSame( $title->getLength(), $info['length'] );
		$this->assertSame( true, $info['new'] );
		$this->assertSame( true, $info['watched'] );
		$this->assertSame( '2011-04-01T00:00:00Z', $info['watchlistexpiry'] );
		$this->assertArrayNotHasKey( 'actions', $info );
		$this->assertArrayNotHasKey( 'linkclasses', $info );
	}

	public function testExecuteLinkClasses() {
		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		[ $data ] = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'info',
				'titles' => $title->getText(),
				'inprop' => 'linkclasses',
				'inlinkcontext' => $title->getText(),
		] );

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'pages', $data['query'] );
		$this->assertArrayHasKey( $page->getId(), $data['query']['pages'] );

		$info = $data['query']['pages'][$page->getId()];
		$this->assertArrayHasKey( 'linkclasses', $info );
		$this->assertEquals( [], $info['linkclasses'] );
	}

	public function testExecuteEditActions() {
		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		[ $data ] = $this->doApiRequest( [
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

	public function testExecuteEditActionsAutoCreate() {
		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		// Disabled
		$this->disableAutoCreateTempUser();
		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'info',
			'titles' => $title->getText(),
			'intestactions' => 'edit',
			'intestactionsautocreate' => true,
		], null, false, new User() );
		$result = $data['query']['pages'][$page->getId()]['wouldautocreate']['edit'];
		$this->assertFalse( $result );

		// Enabled
		$this->setGroupPermissions( '*', 'createaccount', true );
		$this->enableAutoCreateTempUser();
		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'info',
			'titles' => $title->getText(),
			'intestactions' => 'edit',
			'intestactionsautocreate' => true,
		], null, false, new User() );
		$result = $data['query']['pages'][$page->getId()]['wouldautocreate']['edit'];
		$this->assertTrue( $result );

		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'info',
			'titles' => $title->getText(),
			'intestactions' => 'create',
			'intestactionsautocreate' => true,
		], null, false, new User() );
		$result = $data['query']['pages'][$page->getId()]['wouldautocreate']['create'];
		$this->assertTrue( $result );

		// Enabled - 'read' is not an autocreate action
		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'info',
			'titles' => $title->getText(),
			'intestactions' => 'read',
			'intestactionsautocreate' => true,
		], null, false, new User() );
		$result = $data['query']['pages'][$page->getId()]['wouldautocreate']['read'];
		$this->assertFalse( $result );

		// Enabled - but the user is logged in
		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'info',
			'titles' => $title->getText(),
			'intestactions' => 'edit',
			'intestactionsautocreate' => true,
		], null, false, static::getTestSysop()->getAuthority() );
		$result = $data['query']['pages'][$page->getId()]['wouldautocreate']['edit'];
		$this->assertFalse( $result );

		// Enabled - but the user isn't allowed to create accounts
		$this->setGroupPermissions( '*', 'createaccount', false );
		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'info',
			'titles' => $title->getText(),
			'intestactions' => 'edit',
			'intestactionsautocreate' => true,
		], null, false, new User() );
		$result = $data['query']['pages'][$page->getId()]['wouldautocreate']['edit'];
		$this->assertFalse( $result );
	}

	public function testExecuteEditActionsFull() {
		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		[ $data ] = $this->doApiRequest( [
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

	public function testExecuteEditActionsFullBlock() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = $this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'targetUser' => $badActor,
				'by' => $sysop,
				'expiry' => 'infinity',
				'sitewide' => 1,
				'enableAutoblock' => true,
			] );

		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		[ $data ] = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'info',
				'titles' => $title->getText(),
				'intestactions' => 'edit',
				'intestactionsdetail' => 'full',
		], null, false, $badActor );

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

	public function testExecuteEditActionsFullBlockTempAccount() {
		$this->setGroupPermissions( '*', 'createaccount', true );
		$this->enableAutoCreateTempUser();

		$blockIp = '127.0.0.1';
		RequestContext::getMain()->getRequest()->setIP( $blockIp );

		$sysop = $this->getTestSysop()->getUser();

		$block = $this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'address' => $blockIp,
				'by' => $sysop,
				'expiry' => 'infinity',
				'sitewide' => 1,
			] );

		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		[ $data ] = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'info',
				'titles' => $title->getText(),
				'intestactions' => 'edit',
				'intestactionsdetail' => 'full',
		], null, false, new User() );

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

	public function testAssociatedPage() {
		$page = $this->getExistingTestPage( 'Demo' );
		$title = $page->getTitle();

		$title2 = Title::makeTitle( NS_TALK, 'Page does not exist' );
		// Make sure it doesn't exist
		$this->getNonexistingTestPage( $title2 );

		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'info',
			'titles' => $title->getPrefixedText() . '|' . $title2->getPrefixedText(),
			'inprop' => 'associatedpage',
		] );

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'pages', $data['query'] );
		$this->assertArrayHasKey( $page->getId(), $data['query']['pages'] );

		$info = $data['query']['pages'][$page->getId()];
		$this->assertArrayHasKey( 'associatedpage', $info );
		$this->assertEquals(
			'Talk:Demo',
			$info['associatedpage']
		);

		// For the non-existing page
		$this->assertArrayHasKey( -1, $data['query']['pages'] );

		$info = $data['query']['pages'][ -1 ];
		$this->assertArrayHasKey( 'associatedpage', $info );
		$this->assertEquals(
			'Page does not exist',
			$info['associatedpage']
		);
	}

	public function testDisplayTitle() {
		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'info',
			'inprop' => 'displaytitle',
			'titles' => 'Art&copy',
		] );

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'pages', $data['query'] );

		// For the non-existing page
		$this->assertArrayHasKey( -1, $data['query']['pages'] );

		$info = $data['query']['pages'][ -1 ];
		$this->assertArrayHasKey( 'displaytitle', $info );
		$this->assertEquals(
			'Art&amp;copy',
			$info['displaytitle']
		);
	}

}
