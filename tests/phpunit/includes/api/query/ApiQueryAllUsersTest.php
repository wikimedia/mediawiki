<?php

use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\User;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiQueryAllUsers
 */
class ApiQueryAllUsersTest extends ApiTestCase {
	use MockAuthorityTrait;

	private const USER_PREFIX = 'ApiQueryAllUsersTest ';

	public function addDBDataOnce() {
		$groupManager = $this->getServiceContainer()->getUserGroupManager();

		User::createNew( self::USER_PREFIX . 'A' );
		$userB = User::createNew( self::USER_PREFIX . 'B' );
		$groupManager->addUserToGroup( $userB, 'bot' );
		$userC = User::createNew( self::USER_PREFIX . 'C' );
		$groupManager->addUserToGroup( $userC, 'bot' );
	}

	public function testPrefix() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auprefix' => self::USER_PREFIX
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertContains( self::USER_PREFIX . 'A', $result[0]['query']['allusers'][0] );
		$this->assertContains( self::USER_PREFIX . 'B', $result[0]['query']['allusers'][1] );
		$this->assertContains( self::USER_PREFIX . 'C', $result[0]['query']['allusers'][2] );
	}

	public function testImplicitRights() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auprefix' => self::USER_PREFIX,
			'aurights' => 'stashedit'
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertContains( self::USER_PREFIX . 'A', $result[0]['query']['allusers'][0] );
		$this->assertContains( self::USER_PREFIX . 'B', $result[0]['query']['allusers'][1] );
		$this->assertContains( self::USER_PREFIX . 'C', $result[0]['query']['allusers'][2] );
	}

	public function testPermissions() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auprefix' => self::USER_PREFIX,
			'aurights' => 'bot'
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertContains( self::USER_PREFIX . 'B', $result[0]['query']['allusers'][0] );
		$this->assertContains( self::USER_PREFIX . 'C', $result[0]['query']['allusers'][1] );
	}

	public function testHiddenUser() {
		$userFactory = $this->getServiceContainer()->getUserFactory();
		$a = $userFactory->newFromName( self::USER_PREFIX . 'A' );
		$b = $userFactory->newFromName( self::USER_PREFIX . 'B' );
		$blockStatus = $this->getServiceContainer()->getBlockUserFactory()
			->newBlockUser(
				$b,
				new UltimateAuthority( $a ),
				'infinity',
				'',
				[ 'isHideUser' => true ],
			)
			->placeBlock();
		$this->assertStatusGood( $blockStatus );

		$apiParams = [
			'action' => 'query',
			'list' => 'allusers',
			'auprefix' => self::USER_PREFIX . 'B',
		];

		$result = $this->doApiRequest( $apiParams, null, null,
			$this->mockRegisteredAuthorityWithPermissions( [] ) );
		$this->assertSame( [], $result[0]['query']['allusers'] );

		$result = $this->doApiRequest( $apiParams, null, null,
			$this->mockRegisteredAuthorityWithPermissions( [ 'hideuser' ] ) );

		$this->assertSame(
			[ [
				'userid' => $b->getId(),
				'name' => $b->getName(),
				'hidden' => true,
			] ],
			$result[0]['query']['allusers']
		);

		$apiParams['auprop'] = 'blockinfo';
		$result = $this->doApiRequest( $apiParams, null, null,
			$this->mockRegisteredAuthorityWithPermissions( [ 'hideuser' ] ) );
		$this->assertArraySubmapSame(
			[
				'userid' => $b->getId(),
				'name' => $b->getName(),
				'hidden' => true,
				'blockedby' => 'ApiQueryAllUsersTest A',
				'blockreason' => '',
				'blockexpiry' => 'infinite',
				'blockpartial' => false,
			],
			$result[0]['query']['allusers'][0]
		);
	}

	public function testBlockInfo() {
		$userFactory = $this->getServiceContainer()->getUserFactory();
		$a = $userFactory->newFromName( self::USER_PREFIX . 'A' );
		$b = $userFactory->newFromName( self::USER_PREFIX . 'B' );
		$c = $userFactory->newFromName( self::USER_PREFIX . 'C' );

		$blockStatus = $this->getServiceContainer()->getBlockUserFactory()
			->newBlockUser(
				$b,
				new UltimateAuthority( $a ),
				'infinity',
			)
			->placeBlock();
		$this->assertStatusGood( $blockStatus );

		$blockStatus = $this->getServiceContainer()->getBlockUserFactory()
			->newBlockUser(
				$c,
				new UltimateAuthority( $a ),
				'infinity',
				'',
				[ 'isUserTalkEditBlocked' => true ],
			)
			->placeBlock();
		$this->assertStatusGood( $blockStatus );

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auprefix' => self::USER_PREFIX,
			'auprop' => 'blockinfo'
		] );

		$this->assertArraySubmapSame(
			[
				'userid' => $a->getId(),
				'name' => $a->getName(),
			],
			$result[0]['query']['allusers'][0]
		);
		$this->assertArraySubmapSame(
			[
				'userid' => $b->getId(),
				'name' => $b->getName(),
				'blockedby' => 'ApiQueryAllUsersTest A',
				'blockreason' => '',
				'blockexpiry' => 'infinite',
				'blockpartial' => false,
				'blockowntalk' => false
			],
			$result[0]['query']['allusers'][1]
		);
		$this->assertArraySubmapSame(
			[
				'userid' => $c->getId(),
				'name' => $c->getName(),
				'blockedby' => 'ApiQueryAllUsersTest A',
				'blockreason' => '',
				'blockexpiry' => 'infinite',
				'blockpartial' => false,
				'blockowntalk' => true
			],
			$result[0]['query']['allusers'][2]
		);
	}
}
