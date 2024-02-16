<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \ApiQueryAllUsers
 */
class ApiQueryAllUsersTest extends ApiTestCase {
	use MockAuthorityTrait;

	private const USER_PREFIX = 'ApiQueryAllUsersTest ';

	private static $usersAdded = [];

	public function addDBDataOnce() {
		$groupManager = $this->getServiceContainer()->getUserGroupManager();
		$userA = $this->getMutableTestUser( [], self::USER_PREFIX . 'A' )->getUser();
		$userB = $this->getMutableTestUser( [], self::USER_PREFIX . 'B' )->getUser();
		$userC = $this->getMutableTestUser( [], self::USER_PREFIX . 'C' )->getUser();
		$groupManager->addUserToGroup( $userB, 'bot' );
		$groupManager->addUserToGroup( $userC, 'bot' );
		self::$usersAdded = [ $userA, $userB, $userC ];
	}

	public function testPrefix() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auprefix' => self::USER_PREFIX
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertContains( self::$usersAdded[0]->getName(), $result[0]['query']['allusers'][0] );
		$this->assertContains( self::$usersAdded[1]->getName(), $result[0]['query']['allusers'][1] );
		$this->assertContains( self::$usersAdded[2]->getName(), $result[0]['query']['allusers'][2] );
	}

	public function testImplicitRights() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'aurights' => 'stashedit'
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertContains( self::$usersAdded[0]->getName(), $result[0]['query']['allusers'][0] );
		$this->assertContains( self::$usersAdded[1]->getName(), $result[0]['query']['allusers'][1] );
		$this->assertContains( self::$usersAdded[2]->getName(), $result[0]['query']['allusers'][2] );
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
		$this->assertContains( self::$usersAdded[1]->getName(), $result[0]['query']['allusers'][0] );
		$this->assertContains( self::$usersAdded[2]->getName(), $result[0]['query']['allusers'][1] );
	}

	public function testHiddenUser() {
		$a = self::$usersAdded[0];
		$b = self::$usersAdded[1];
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
				'blockedby' => $a->getName(),
				'blockreason' => '',
				'blockexpiry' => 'infinite',
				'blockpartial' => false,
			],
			$result[0]['query']['allusers'][0]
		);
	}

	public function testBlockInfo() {
		$a = self::$usersAdded[0];
		$b = self::$usersAdded[1];
		$c = self::$usersAdded[2];

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
				'blockedby' => $a->getName(),
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
				'blockedby' => $a->getName(),
				'blockreason' => '',
				'blockexpiry' => 'infinite',
				'blockpartial' => false,
				'blockowntalk' => true
			],
			$result[0]['query']['allusers'][2]
		);
	}

	public function testUserRights() {
		$userA = self::$usersAdded[0];
		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		$permissionManager->overrideUserRightsForTesting( $userA, [ 'protect' ] );

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auprefix' => self::USER_PREFIX,
			'auprop' => 'rights',
			'aulimit' => 2,
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertArrayHasKey( 'rights', $result[0]['query']['allusers'][0] );
		$this->assertArrayHasKey( 'rights', $result[0]['query']['allusers'][1] );
		$this->assertContains( 'protect', $result[0]['query']['allusers'][0]['rights'] );
		$this->assertNotContains( 'protect', $result[0]['query']['allusers'][1]['rights'] );
	}
}
