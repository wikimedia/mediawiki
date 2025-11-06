<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Session
 */

namespace MediaWiki\Session;

use MediaWiki\WikiMap\WikiMap;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\CachedBagOStuff;
use Wikimedia\Stats\StatsFactory;

/**
 * An implementation of a session store with two backends for storing
 * anonymous and authenticated sessions separately.
 *
 * It is recommended to use a backend with strong persistence for the
 * authenticated sessions (since a session loss can be annoying to users, and
 * the amount of authentication sessions is limited), and a cheaper backend
 * (possibly with an eviction mechanism, such as Memcached) for the anonymous
 * sessions, as it's easy to accidentally or maliciously create lots of them.
 *
 * Multiple stores are injected here to be used for the different types
 * of sessions. A few criteria are used to decide whether a session is
 * anonymous or authenticated:
 *
 * 1) Usually, the SessionInfo object directly tells us whether the session is
 *    authenticated or anonymous.
 * 2) When $sessionInfo->getUserInfo() is null (meaning either we are loading a
 *    session from its ID, e.g., for compatibility with PHP's session_id(), or
 *    we tried to load an authenticated session, but for some reason it failed),
 *    we check which store has data for the given session ID and use that.
 * 3) If neither store has any data, it's an anonymous (empty) session.
 *
 * The underlying assumption is that the same session ID won't be reused for both
 * anonymous and authenticated sessions (because immutable sessions are always
 * authenticated, and for mutable sessions, we always call resetId() before
 * changing the user's identity).
 *
 * @ingroup Session
 * @since 1.45
 */
class MultiBackendSessionStore implements SessionStore {

	private const STATS_LABEL_ANON = 'anonymous';
	private const STATS_LABEL_AUTH = 'authenticated';

	private BagOStuff $anonSessionStore;
	private BagOStuff $authenticatedSessionStore;
	/** @var bool Track whether injected backends are the same or not. */
	private bool $sameBackend;
	private StatsFactory $statsFactory;

	/**
	 * The store that should be used during the request at
	 * a given point in time, which will be the session backend.
	 */
	private LoggerInterface $logger;

	public function __construct(
		BagOStuff $anonSessionStore,
		BagOStuff $authenticatedSessionStore,
		LoggerInterface $logger,
		StatsFactory $statsFactory
	) {
		$this->setLogger( $logger );
		// Do some logging of the individual stores before wrapping.
		$this->logger->debug( 'SessionManager using anon store ' . get_class( $anonSessionStore ) );
		$this->logger->debug( 'SessionManager using auth store ' . get_class( $authenticatedSessionStore ) );

		$this->sameBackend = $anonSessionStore === $authenticatedSessionStore;
		$this->anonSessionStore = $this->wrapWithCachedBagOStuff( $anonSessionStore );
		$this->authenticatedSessionStore = $this->wrapWithCachedBagOStuff( $authenticatedSessionStore );
		$this->statsFactory = $statsFactory;
	}

	/** @inheritDoc */
	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	private function wrapWithCachedBagOStuff( BagOStuff $store ): CachedBagOStuff {
		if ( $store instanceof CachedBagOStuff ) {
			return $store;
		}

		return new CachedBagOStuff( $store );
	}

	/**
	 * Decide and return which store is active for a given session info
	 * object and also a flag that indicates whether it is an authenticated
	 * store or not.
	 *
	 * @param SessionInfo $sessionInfo
	 * @return array{0:CachedBagOStuff,1:bool}
	 */
	private function getActiveStore( SessionInfo $sessionInfo ): array {
		$userInfo = $sessionInfo->getUserInfo();
		$anonData = null;

		if ( $userInfo === null ) {
			$this->logger->debug( 'No user info found for this session', [
				'sessionInfo' => (string)$sessionInfo,
				'exception' => new RuntimeException(),
			] );

			// We don't yet know whether this is an anonymous or authenticated session
			// (e.g., getSessionById() is being used), so we need to check both stores.
			$anonKey = $this->anonSessionStore->makeKey( 'MWSession', $sessionInfo->getId() );
			$authKey = $this->authenticatedSessionStore->makeKey( 'MWSession', $sessionInfo->getId() );

			$authData = $this->authenticatedSessionStore->get( $authKey );
			if ( !$this->authenticatedSessionStore->wasLastGetCached() ) {
				$this->statsFactory->getCounter( 'sessionstore_nouserinfo_get_total' )
					->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
					->setLabel( 'type', 'authenticated' )
					->setLabel( 'status', $authData ? 'hit' : 'miss' )
					->increment();
			}
			if ( !$authData ) {
				$anonData = $this->anonSessionStore->get( $anonKey );
				if ( !$this->anonSessionStore->wasLastGetCached() ) {
					$this->statsFactory->getCounter( 'sessionstore_nouserinfo_get_total' )
						->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
						->setLabel( 'type', 'anonymous' )
						->setLabel( 'status', $anonData ? 'hit' : 'miss' )
						->increment();
				}
			}

			$anonUserName = $anonData['metadata']['userName'] ?? null;
			$authUserName = $authData['metadata']['userName'] ?? null;

			if ( $anonData && $anonUserName !== null ) {
				// The data does not match the store!
				// This is actually expected when the two stores are the same
				// (which is useful for testing in production).
				if ( !$this->sameBackend ) {
					$this->logger->warning( 'No userInfo: authenticated data should not be in the anonymous store', [
						'sessionInfo' => (string)$sessionInfo,
						'exception' => new RuntimeException(),
					] );
				}

				$anonData = false;
				$anonUserName = null;
			}

			if ( $authData && $authUserName === null ) {
				if ( !$this->sameBackend ) {
					$this->logger->warning( 'No userInfo: anonymous data should not be in the authenticated store', [
						'sessionInfo' => (string)$sessionInfo,
						'exception' => new RuntimeException(),
					] );
				}
				$authData = false;
			}

			if ( $anonData && $authData && !$this->sameBackend ) {
				$this->logger->warning( 'Both stores should not have the same session data', [
					'sessionInfo' => (string)$sessionInfo,
					'exception' => new RuntimeException(),
				] );
			}

			if ( $authData ) {
				return [ $this->authenticatedSessionStore, true ];
			}

			return [ $this->anonSessionStore, false ];
		}

		if ( !$userInfo->isAnon() ) {
			// Hopefully, we can use the user info here to know if it's an anonymous user or
			// and authenticated user. This will help us determine which store to use.
			return [ $this->authenticatedSessionStore, true ];
		}

		return [ $this->anonSessionStore, false ];
	}

	/**
	 * Get session store data for a given key. This will look up the
	 * active store during the request and use that to fetch the data.
	 *
	 * @param SessionInfo $info
	 *
	 * @return mixed
	 */
	public function get( SessionInfo $info ) {
		$this->logger->debug( __METHOD__ . " was called for $info" );

		[ $store, $isAuthenticated ] = $this->getActiveStore( $info );

		$key = $store->makeKey( 'MWSession', $info->getId() );
		$data = $store->get( $key );
		$userName = $data['metadata']['userName'] ?? null;

		if ( $isAuthenticated && $data && $userName === null ) {
			$this->logger->warning( 'Store is authenticated, but the user associated to the data is anonymous', [
				'sessionInfo' => (string)$info,
				'user' => (string)$info->getUserInfo(),
				'exception' => new RuntimeException(),
			] );
		}

		if ( !$isAuthenticated && $userName !== null ) {
			$this->logger->warning( 'Store is anonymous, but the user associated to the data is authenticated', [
				'sessionInfo' => (string)$info,
				'user' => (string)$info->getUserInfo(),
				'exception' => new RuntimeException(),
			] );
		}

		// We are interested in tracking reads from the actual store (uncached in-process)
		// to know how many times we do actual store look-ups.
		if ( !$store->wasLastGetCached() ) {
			$this->statsFactory->getCounter( 'sessionstore_get_total' )
				->setLabel( 'type', $isAuthenticated ? self::STATS_LABEL_AUTH : self::STATS_LABEL_ANON )
				->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
				->increment();
		}

		return $data;
	}

	/**
	 * Set session store data for the corresponding key to the active
	 * store during the request.
	 *
	 * @param SessionInfo $info
	 * @param mixed $value
	 * @param int $exptime
	 * @param int $flags
	 */
	public function set( SessionInfo $info, $value, $exptime = 0, $flags = 0 ): void {
		$this->logger->debug( __METHOD__ . " was called for $info" );

		if ( $flags === BagOStuff::WRITE_CACHE_ONLY && $value === false ) {
			// writing $value === false in the cache is only used by
			// SessionManager::generateSessionId() to prevent an unnecessary store lookup.
			// We don't know which store the ID is going to be used for in that case,
			// so need to avoid calling getActiveStore() which would result in a store lookup
			// and defeat the purpose. Since a new random key can't conflict with existing keys,
			// no harm in just updating both caches.
			$anonKey = $this->anonSessionStore->makeKey( 'MWSession', $info->getId() );
			$authKey = $this->authenticatedSessionStore->makeKey( 'MWSession', $info->getId() );

			$this->anonSessionStore->set( $anonKey, false, 0, BagOStuff::WRITE_CACHE_ONLY );
			$this->authenticatedSessionStore->set( $authKey, false, 0, BagOStuff::WRITE_CACHE_ONLY );
			return;
		}

		[ $store, $isAuthenticated ] = $this->getActiveStore( $info );

		$key = $store->makeKey( 'MWSession', $info->getId() );
		$userName = $value['metadata']['userName'] ?? null;

		// SessionManager::generateSessionId() can perform a cache warming
		// operation by setting `false` as the cache value. We don't want to
		// log those.
		if ( $value ) {
			if ( $isAuthenticated && $userName === null ) {
				$this->logger->warning( 'Store is authenticated, but the user associated to the data is anonymous', [
					'sessionInfo' => (string)$info,
					'user' => (string)$info->getUserInfo(),
					'exception' => new RuntimeException(),
					'flags' => (string)$flags
				] );
			}

			if ( !$isAuthenticated && $userName !== null ) {
				$this->logger->warning( 'Store is anonymous, but the user associated to the data is authenticated', [
					'sessionInfo' => (string)$info,
					'user' => (string)$info->getUserInfo(),
					'exception' => new RuntimeException(),
					'flags' => (string)$flags
				] );
			}
		}

		$store->set( $key, $value, $exptime, $flags );

		if ( ( $flags & BagOStuff::WRITE_CACHE_ONLY ) === 0 ) {
			$this->statsFactory->getCounter( 'sessionstore_set_total' )
				->setLabel( 'type', $isAuthenticated ? self::STATS_LABEL_AUTH : self::STATS_LABEL_ANON )
				->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
				->increment();
		}
	}

	/**
	 * Deletes session data from the session store for the provided key.
	 *
	 * @param SessionInfo $info
	 */
	public function delete( SessionInfo $info ): void {
		$this->logger->debug( __METHOD__ . " was called for $info" );

		[ $store, $isAuthenticated ] = $this->getActiveStore( $info );
		$key = $store->makeKey( 'MWSession', $info->getId() );

		$store->delete( $key );

		$this->statsFactory->getCounter( 'sessionstore_delete_total' )
			->setLabel( 'type', $isAuthenticated ? self::STATS_LABEL_AUTH : self::STATS_LABEL_ANON )
			->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
			->increment();
	}

	/**
	 * @inheritDoc
	 */
	public function shutdown(): void {
		if ( random_int( 1, 100 ) === 1 ) {
			$this->logger->debug( 'Cleaning session store expired entries' );
			$timeNow = wfTimestampNow();
			$this->authenticatedSessionStore->deleteObjectsExpiringBefore( $timeNow );
			$this->anonSessionStore->deleteObjectsExpiringBefore( $timeNow );
		}
	}
}
