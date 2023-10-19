<?php

use MediaWiki\User\User;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiQueryAllUsers
 */
class ApiQueryAllUsersTest extends ApiTestCase {
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
}
