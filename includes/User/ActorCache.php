<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

/**
 * Simple in-memory cache for UserIdentity objects indexed by user ID,
 * actor ID and user name.
 *
 * We cannot just use MapCacheLRU for this because of eviction semantics:
 * we need to be able to remove UserIdentity from the cache even if
 * user ID or user name has changed, so we track the most accessed VALUES
 * in the cache, not keys, and evict them alongside with all their indexes.
 *
 * @since 1.37
 * @internal For use by ActorStore
 * @ingroup User
 */
class ActorCache {

	/** @var string Key by actor ID */
	public const KEY_ACTOR_ID = 'actorId';

	/** @var string Key by user ID */
	public const KEY_USER_ID = 'userId';

	/** @var string Key by user name */
	public const KEY_USER_NAME = 'name';

	private int $maxSize;

	/**
	 * @var array[][] Contains 3 keys, KEY_ACTOR_ID, KEY_USER_ID, and KEY_USER_NAME,
	 * each of which has a value of an array of arrays with actor ids and UserIdentity objects,
	 * keyed with the corresponding actor id/user id/user name
	 */
	private $cache = [ self::KEY_ACTOR_ID => [], self::KEY_USER_NAME => [], self::KEY_USER_ID => [] ];

	/**
	 * @param int $maxSize hold up to this many UserIdentity values
	 */
	public function __construct( int $maxSize ) {
		$this->maxSize = $maxSize;
	}

	/**
	 * Get user identity which has $keyType equal to $keyValue
	 * @param string $keyType one of self::KEY_* constants.
	 * @param string|int $keyValue
	 * @return UserIdentity|null
	 */
	public function getActor( string $keyType, $keyValue ): ?UserIdentity {
		return $this->getCachedValue( $keyType, $keyValue )['actor'] ?? null;
	}

	/**
	 * Get actor ID of the user which has $keyType equal to $keyValue.
	 * @param string $keyType one of self::KEY_* constants.
	 * @param string|int $keyValue
	 * @return int|null
	 */
	public function getActorId( string $keyType, $keyValue ): ?int {
		return $this->getCachedValue( $keyType, $keyValue )['actorId'] ?? null;
	}

	/**
	 * Add $actor with $actorId to the cache.
	 * @param int $actorId
	 * @param UserIdentity $actor
	 */
	public function add( int $actorId, UserIdentity $actor ) {
		while ( count( $this->cache[self::KEY_ACTOR_ID] ) >= $this->maxSize ) {
			$evictId = array_key_first( $this->cache[self::KEY_ACTOR_ID] );
			$this->remove( $this->cache[self::KEY_ACTOR_ID][$evictId]['actor'] );
		}
		$value = [ 'actorId' => $actorId, 'actor' => $actor ];
		$this->cache[self::KEY_ACTOR_ID][$actorId] = $value;
		$userId = $actor->getId( $actor->getWikiId() );
		if ( $userId ) {
			$this->cache[self::KEY_USER_ID][$userId] = $value;
		}
		$this->cache[self::KEY_USER_NAME][$actor->getName()] = $value;
	}

	public function remove( UserIdentity $actor ) {
		$oldByName = $this->cache[self::KEY_USER_NAME][$actor->getName()] ?? null;
		$oldByUserId = $this->cache[self::KEY_USER_ID][$actor->getId( $actor->getWikiId() )] ?? null;
		if ( $oldByName ) {
			unset( $this->cache[self::KEY_USER_ID][$oldByName['actor']->getId( $oldByName['actor']->getWikiId() )] );
			unset( $this->cache[self::KEY_ACTOR_ID][$oldByName['actorId']] );
		}
		if ( $oldByUserId ) {
			unset( $this->cache[self::KEY_USER_NAME][$oldByUserId['actor']->getName()] );
			unset( $this->cache[self::KEY_ACTOR_ID][$oldByUserId['actorId']] );
		}
		unset( $this->cache[self::KEY_USER_NAME][$actor->getName()] );
		unset( $this->cache[self::KEY_USER_ID][$actor->getId( $actor->getWikiId() )] );
	}

	/**
	 * Remove everything from the cache.
	 * @internal
	 */
	public function clear() {
		$this->cache = [ self::KEY_ACTOR_ID => [], self::KEY_USER_NAME => [], self::KEY_USER_ID => [] ];
	}

	/**
	 * @param string $keyType one of self::KEY_* constants.
	 * @param string|int $keyValue
	 * @return array|null [ 'actor' => UserIdentity, 'actorId' => int ]
	 */
	private function getCachedValue( string $keyType, $keyValue ): ?array {
		if ( isset( $this->cache[$keyType][$keyValue] ) ) {
			$cached = $this->cache[$keyType][$keyValue];
			$actorId = $cached['actorId'];
			// Record the actor with $actorId was recently used.
			$item = $this->cache[self::KEY_ACTOR_ID][$actorId];
			unset( $this->cache[self::KEY_ACTOR_ID][$actorId] );
			$this->cache[self::KEY_ACTOR_ID][$actorId] = $item;
			return $cached;
		}
		return null;
	}
}
