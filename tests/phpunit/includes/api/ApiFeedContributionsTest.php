<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Feed\ChannelFeed;
use MediaWiki\MainConfigNames;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiFeedContributions
 */
class ApiFeedContributionsTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::Feed, true );
	}

	/**
	 * Test that querying a hidden user by #<id> as a logged-out user
	 * builds the channel link for 'Unknown user' and does not leak the hidden username.
	 */
	public function testChannelLink_hiddenUserId_loggedOut() {
		$hiddenUser = $this->getMutableTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$block = new DatabaseBlock( [
			'expiry' => 'infinity',
			'hideName' => true,
		] );
		$block->setTarget( $hiddenUser );
		$block->setBlocker( $sysop );
		$blockStore->insertBlock( $block );

		$res = $this->doApiRequest( [
			'action' => 'feedcontributions',
			'user' => '#' . $hiddenUser->getId(),
			'feedformat' => 'rss',
		], null, true );

		$feedData = $res[3]->getResult()->getResultData( null, [ 'Strip' => 'none' ] );
		$this->assertArrayHasKey( '_feed', $feedData );
		/** @var ChannelFeed $feed */
		$feed = $feedData['_feed'];
		$this->assertInstanceOf( ChannelFeed::class, $feed );
		$this->assertStringNotContainsString( $hiddenUser->getName(), $feed->getUrl() );
		$this->assertStringContainsString( 'Special:Contributions/Unknown_user', $feed->getUrl() );
	}

	/**
	 * Test that querying a hidden user by #<id> with 'hideuser' right
	 * resolves to the hidden username in the channel link.
	 */
	public function testChannelLink_hiddenUserId_withHideuser() {
		$hiddenUser = $this->getMutableTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$block = new DatabaseBlock( [
			'expiry' => 'infinity',
			'hideName' => true,
		] );
		$block->setTarget( $hiddenUser );
		$block->setBlocker( $sysop );
		$blockStore->insertBlock( $block );

		// Give sysop the 'hideuser' right
		$this->setGroupPermissions( 'sysop', 'hideuser', true );

		$res = $this->doApiRequest( [
			'action' => 'feedcontributions',
			'user' => '#' . $hiddenUser->getId(),
			'feedformat' => 'rss',
		], null, true, $sysop );

		$feedData = $res[3]->getResult()->getResultData( null, [ 'Strip' => 'none' ] );
		$this->assertArrayHasKey( '_feed', $feedData );
		/** @var ChannelFeed $feed */
		$feed = $feedData['_feed'];
		$this->assertInstanceOf( ChannelFeed::class, $feed );
		$this->assertStringContainsString(
			'Special:Contributions/' . str_replace( ' ', '_', $hiddenUser->getName() ),
			$feed->getUrl()
		);
	}
}
