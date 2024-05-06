<?php

namespace MediaWiki\Tests\Unit\User;

use MediaWiki\User\ActorCache;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\ActorCache
 */
class ActorCacheTest extends MediaWikiUnitTestCase {

	/**
	 * @param ActorCache $cache
	 * @param int $actorId
	 * @param UserIdentity $actor
	 */
	private function assertCacheContains( ActorCache $cache, int $actorId, UserIdentity $actor ) {
		$this->assertSame( $actor,
			$cache->getActor( ActorCache::KEY_ACTOR_ID, $actorId ) );
		$this->assertSame( $actor,
			$cache->getActor( ActorCache::KEY_USER_NAME, $actor->getName() ) );
		$this->assertSame( $actor,
			$cache->getActor( ActorCache::KEY_USER_ID, $actor->getId( $actor->getWikiId() ) ) );
		$this->assertSame( $actorId,
			$cache->getActorId( ActorCache::KEY_ACTOR_ID, 1 ) );
		$this->assertSame( $actorId,
			$cache->getActorId( ActorCache::KEY_USER_NAME, $actor->getName() ) );
		$this->assertSame( $actorId,
			$cache->getActorId( ActorCache::KEY_USER_ID, $actor->getId( $actor->getWikiId() ) ) );
	}

	/**
	 * @param ActorCache $cache
	 * @param int $actorId
	 * @param string $actorName
	 * @param int $userId
	 */
	private function assertCacheNotContains( ActorCache $cache, int $actorId, string $actorName, int $userId ) {
		$this->assertNull( $cache->getActor( ActorCache::KEY_ACTOR_ID, $actorId ) );
		$this->assertNull( $cache->getActor( ActorCache::KEY_USER_NAME, $actorName ) );
		$this->assertNull( $cache->getActor( ActorCache::KEY_USER_ID, $userId ) );
		$this->assertNull( $cache->getActorId( ActorCache::KEY_ACTOR_ID, $actorId ) );
		$this->assertNull( $cache->getActorId( ActorCache::KEY_USER_NAME, $actorName ) );
		$this->assertNull( $cache->getActorId( ActorCache::KEY_USER_ID, $userId ) );
	}

	public static function provideGetActor() {
		yield 'local' => [
			'actor' => new UserIdentityValue( 10, 'Hello' ),
		];
		yield 'foreign' => [
			'actor' => new UserIdentityValue( 10, 'Hello', 'acmewiki' ),
		];
	}

	/**
	 * @dataProvider provideGetActor
	 */
	public function testGetActor( UserIdentity $actor ) {
		$cache = new ActorCache( 5 );
		$cache->add( 1, $actor );
		$this->assertCacheContains( $cache, 1, $actor );
	}

	public static function provideRemove() {
		yield 'Same actor' => [
			'addedActor' => new UserIdentityValue( 10, 'Hello' ),
			'removedActor' => new UserIdentityValue( 10, 'Hello' )
		];
		yield 'Different name' => [
			'addedActor' => new UserIdentityValue( 10, 'Hello' ),
			'removedActor' => new UserIdentityValue( 10, 'Goodbye' )
		];
		yield 'Different user ID' => [
			'addedActor' => new UserIdentityValue( 10, 'Hello' ),
			'removedActor' => new UserIdentityValue( 11, 'Hello' )
		];
	}

	/**
	 * @dataProvider provideRemove
	 */
	public function testRemove( UserIdentity $added, UserIdentity $removed ) {
		$cache = new ActorCache( 5 );
		$cache->add( 1, $added );
		$this->assertCacheContains( $cache, 1, $added );
		$cache->remove( $removed );
		$this->assertCacheNotContains( $cache, 1, $added->getName(), $added->getId() );
		$this->assertCacheNotContains( $cache, 1, $removed->getName(), $removed->getId() );
	}

	public function testEvict() {
		$cache = new ActorCache( 1 );
		$actor1 = new UserIdentityValue( 10, 'Hello' );
		$cache->add( 2, new UserIdentityValue( 12, 'Goodbye' ) );
		$cache->add( 1, $actor1 );
		$this->assertCacheContains( $cache, 1, $actor1 );
		$this->assertCacheNotContains( $cache, 2, 'Goodbye', 12 );
	}

	public function testDoesNotCacheAnonUserId() {
		$cache = new ActorCache( 5 );
		$actor = new UserIdentityValue( 0, '127.0.0.1' );
		$cache->add( 1, $actor );
		$this->assertNull( $cache->getActor( ActorCache::KEY_USER_ID, 0 ) );
		$this->assertNull( $cache->getActorId( ActorCache::KEY_USER_ID, 0 ) );
		$this->assertSame( $actor, $cache->getActor( ActorCache::KEY_USER_NAME, '127.0.0.1' ) );
		$this->assertSame( 1, $cache->getActorId( ActorCache::KEY_USER_NAME, '127.0.0.1' ) );
		$this->assertSame( $actor, $cache->getActor( ActorCache::KEY_ACTOR_ID, 1 ) );
		$this->assertSame( 1, $cache->getActorId( ActorCache::KEY_ACTOR_ID, 1 ) );
	}
}
