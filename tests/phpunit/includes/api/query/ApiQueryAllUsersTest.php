<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\User;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiQueryAllUsers
 */
class ApiQueryAllUsersTest extends ApiTestCase {
	use MockAuthorityTrait;
	use TempUserTestTrait;

	private const USER_PREFIX = 'ApiQueryAllUsersTest ';

	private const TEMP_USER_PREFIX = '~';

	private const TEMP_USER_CONFIG = [
		'genPattern' => self::TEMP_USER_PREFIX . '$1',
		'matchPattern' => self::TEMP_USER_PREFIX . '$1',
		'reservedPattern' => self::TEMP_USER_PREFIX . '$1',
	];

	/** @var User[] */
	private static $usersAdded = [];

	protected function setUp(): void {
		parent::setUp();

		$this->enableAutoCreateTempUser( self::TEMP_USER_CONFIG );
	}

	public function addDBDataOnce() {
		$groupManager = $this->getServiceContainer()->getUserGroupManager();
		$userA = $this->getMutableTestUser( [], self::USER_PREFIX . 'A' )->getUser();
		$userB = $this->getMutableTestUser( [], self::USER_PREFIX . 'B' )->getUser();
		$userC = $this->getMutableTestUser( [], self::USER_PREFIX . 'C' )->getUser();
		$userD = $this->getMutableTestUser( [], self::TEMP_USER_PREFIX . 'D' )->getUser();
		$groupManager->addUserToGroup( $userB, 'bot' );
		$groupManager->addUserToGroup( $userC, 'bot' );
		self::$usersAdded = [ $userA, $userB, $userC, $userD ];
	}

	public function testPrefix() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auprefix' => self::USER_PREFIX
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertApiResultsHasUser( self::$usersAdded[0]->getName(), $result );
		$this->assertApiResultsHasUser( self::$usersAdded[1]->getName(), $result );
		$this->assertApiResultsHasUser( self::$usersAdded[2]->getName(), $result );
	}

	public function testImplicitRights() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auprefix' => self::USER_PREFIX,
			'aurights' => 'stashedit',
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertApiResultsHasUser( self::$usersAdded[0]->getName(), $result );
		$this->assertApiResultsHasUser( self::$usersAdded[1]->getName(), $result );
		$this->assertApiResultsHasUser( self::$usersAdded[2]->getName(), $result );
	}

	public function testTempUserImplicitRights() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auprefix' => self::TEMP_USER_PREFIX,
			'aurights' => 'stashedit',
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertApiResultsHasUser( self::$usersAdded[3]->getName(), $result );
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
		$this->assertApiResultsHasUser( self::$usersAdded[1]->getName(), $result );
		$this->assertApiResultsHasUser( self::$usersAdded[2]->getName(), $result );
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

	public function testNamedOnly() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auexcludetemp' => true,
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertApiResultsHasUser( self::$usersAdded[0]->getName(), $result );
		$this->assertApiResultsHasUser( self::$usersAdded[1]->getName(), $result );
		$this->assertApiResultsHasUser( self::$usersAdded[2]->getName(), $result );
		$this->assertApiResultsNotHasUser( self::$usersAdded[3]->getName(), $result );
	}

	public function testNamedOnlyTempDisabled() {
		$this->disableAutoCreateTempUser(
			array_merge( self::TEMP_USER_CONFIG, [ 'known' => false ] )
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auexcludetemp' => true,
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertApiResultsHasUser( self::$usersAdded[0]->getName(), $result );
		$this->assertApiResultsHasUser( self::$usersAdded[1]->getName(), $result );
		$this->assertApiResultsHasUser( self::$usersAdded[2]->getName(), $result );
		// The temp user from the setup will return as the filter will be disabled.
		$this->assertApiResultsHasUser( self::$usersAdded[3]->getName(), $result );
	}

	public function testNamedOnlyTempKnown() {
		$this->disableAutoCreateTempUser(
			array_merge( self::TEMP_USER_CONFIG, [ 'enabled' => false, 'known' => true ] )
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auexcludetemp' => true,
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertApiResultsHasUser( self::$usersAdded[0]->getName(), $result );
		$this->assertApiResultsHasUser( self::$usersAdded[1]->getName(), $result );
		$this->assertApiResultsHasUser( self::$usersAdded[2]->getName(), $result );
		$this->assertApiResultsNotHasUser( self::$usersAdded[3]->getName(), $result );
	}

	public function testTempOnly() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auexcludenamed' => true,
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertApiResultsHasUser( self::$usersAdded[3]->getName(), $result );
		$this->assertCount( 1, $result[0]['query']['allusers'] );
	}

	public function testTempOnlyTempDisabled() {
		$this->disableAutoCreateTempUser();

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auexcludenamed' => true,
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		// We'll get some count higher than 1 if the feature is disabled as the
		// filter gets ignored in that case.
		$this->assertNotCount( 1, $result[0]['query']['allusers'] );
	}

	public function testTempOnlyTempKnown() {
		$this->disableAutoCreateTempUser(
			array_merge( self::TEMP_USER_CONFIG, [ 'enabled' => false, 'known' => true ] )
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allusers',
			'auexcludenamed' => true,
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allusers', $result[0]['query'] );
		$this->assertApiResultsHasUser( self::$usersAdded[3]->getName(), $result );
		$this->assertCount( 1, $result[0]['query']['allusers'] );
	}

	private function assertApiResultsHasUser( $username, $results ) {
		$this->assertNotFalse(
			array_search(
				$username,
				array_column( $results[0]['query']['allusers'], 'name' )
			),
			"Failed to assert that '{$username}' is in the AllUsers API response"
		);
	}

	private function assertApiResultsNotHasUser( $username, $results ) {
		$this->assertFalse(
			array_search(
				$username,
				array_column( $results[0]['query']['allusers'], 'name' )
			),
			"Failed to assert that '{$username}' is not in the AllUsers API response"
		);
	}
}
