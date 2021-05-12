<?php

namespace MediaWiki\Tests\User;

use MediaWiki\User\ActorStore;
use MediaWiki\User\UserIdentity;
use MediaWikiIntegrationTestCase;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Base class with utilities for testing database access to actor table.
 *
 * @package MediaWiki\Tests\User
 */
abstract class ActorStoreTestBase extends MediaWikiIntegrationTestCase {
	protected const IP = '2600:1004:B14A:5DDD:3EBE:BBA4:BFBA:F37E';

	public function addDBData() {
		$this->tablesUsed[] = 'actor';

		$actors = [
			'registered' => [ 'actor_id' => '42', 'actor_user' => '24', 'actor_name' => 'TestUser' ],
			'anon' => [ 'actor_id' => '43', 'actor_user' => null, 'actor_name' => self::IP ],
			'another registered' => [ 'actor_id' => '44', 'actor_user' => '25', 'actor_name' => 'TestUser1' ],
			'external' => [ 'actor_id' => '45', 'actor_user' => null, 'actor_name' => 'acme>TestUser' ],
			'user name 0' => [ 'actor_id' => '46', 'actor_user' => '26', 'actor_name' => '0' ],
		];

		foreach ( $actors as $description => $row ) {
			$this->assertTrue( $this->db->insert(
				'actor',
				$row,
				__METHOD__,
				[ 'IGNORE' ]
			), "Sanity: must create {$description} actor" );
		}
	}

	/**
	 * @param string|false $wikiId
	 * @return ActorStore
	 */
	protected function getStore( $wikiId = UserIdentity::LOCAL ) : ActorStore {
		return $this->getServiceContainer()->getActorStoreFactory()->getActorStore( $wikiId );
	}

	/**
	 * Execute the $callback passing it an ActorStore for $wikiId,
	 * making sure no queries are made to local DB.
	 * @param string|false $wikiId
	 * @param callable $callback ( ActorStore $store, IDatababase $db )
	 */
	protected function executeWithForeignStore( $wikiId, callable $callback ) {
		$dbLoadBalancer = $this->getServiceContainer()->getDBLoadBalancer();
		$dbLoadBalancer->setDomainAliases( [ $wikiId => $dbLoadBalancer->getLocalDomainID() ] );

		$foreignLB = $this->getServiceContainer()
			->getDBLoadBalancerFactory()
			->getMainLB( $wikiId );
		$foreignLB->setDomainAliases( [ $wikiId => $dbLoadBalancer->getLocalDomainID() ] );
		$foreignDB = $foreignLB->getConnectionRef( DB_MASTER );

		$store = new ActorStore(
			$dbLoadBalancer,
			$this->getServiceContainer()->getUserNameUtils(),
			new NullLogger(),
			$wikiId
		);

		// Redefine the DBLoadBalancer service to verify we don't attempt to resolve its IDs via wfGetDB()
		$localLoadBalancerMock = $this->createNoOpMock( ILoadBalancer::class );
		try {
			$this->setService( 'DBLoadBalancer', $localLoadBalancerMock );
			$callback( $store, $foreignDB );
		} finally {
			// Restore the original loadBalancer.
			$this->setService( 'DBLoadBalancer', $dbLoadBalancer );
		}
	}

	/**
	 * Check whether two actors are the same in the context of $wikiId
	 * @param UserIdentity $expected
	 * @param UserIdentity $actor
	 * @param string|false $wikiId
	 */
	protected function assertSameActors(
		UserIdentity $expected,
		UserIdentity $actor,
		$wikiId = UserIdentity::LOCAL
	) {
		$actor->assertWiki( $wikiId );
		$this->assertSame( $expected->getId( $wikiId ), $actor->getId( $wikiId ) );
		$this->assertSame( $expected->getName(), $actor->getName() );
		$this->assertSame( $expected->getWikiId(), $actor->getWikiId() );
	}
}
