<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
		$stats = $this->statsFactory->getCounter( 'sessionstore_active_backend_total' );
		$userInfo = $sessionInfo->getUserInfo();

		if ( $userInfo === null ) {
			$this->logger->debug( 'No user info found for this session', [
				'sessionInfo' => (string)$sessionInfo,
				'exception' => new RuntimeException(),
			] );

			// We don't yet know whether this is an anonymous or authenticated session
			// (e.g., getSessionById() is being used), so we need to check both stores.
			$anonKey = $this->anonSessionStore->makeKey( 'MWSession', $sessionInfo->getId() );
			$authKey = $this->authenticatedSessionStore->makeKey( 'MWSession', $sessionInfo->getId() );
			$anonData = $this->anonSessionStore->get( $anonKey );
			$authData = $this->authenticatedSessionStore->get( $authKey );
			$anonUserId = $anonData['metadata']['userId'] ?? null;
			$authUserId = $authData['metadata']['userId'] ?? null;

			if ( $anonData && $anonUserId !== 0 ) {
				// The data does not match the store!
				// This is actually expected when the two stores are the same
				// (which is useful for testing in production).
				if ( !$this->sameBackend ) {
					$this->logger->warning( 'Authenticated data should not be in the anonymous store', [
						'sessionInfo' => (string)$sessionInfo,
						'exception' => new RuntimeException(),
					] );
				}

				$anonData = false;
				$anonUserId = null;
			}

			if ( $authData && $authUserId === 0 ) {
				if ( !$this->sameBackend ) {
					$this->logger->warning( 'Anonymous data should not be in the authenticated store', [
						'sessionInfo' => (string)$sessionInfo,
						'exception' => new RuntimeException(),
					] );
				}
				$authData = false;
				$authUserId = null;
			}

			if ( $anonData && $authData && !$this->sameBackend ) {
				$this->logger->warning( 'Both stores should not have the same session data', [
					'sessionInfo' => (string)$sessionInfo,
					'exception' => new RuntimeException(),
				] );
			}

			if ( $authData ) {
				$stats->setLabel( 'type', self::STATS_LABEL_AUTH )
					->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
					->increment();
				return [ $this->authenticatedSessionStore, true ];
			}

			$stats->setLabel( 'type', self::STATS_LABEL_ANON )
				->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
				->increment();
			return [ $this->anonSessionStore, false ];
		}

		if ( !$userInfo->isAnon() ) {
			$stats->setLabel( 'type', self::STATS_LABEL_AUTH )
				->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
				->increment();
			// Hopefully, we can use the user info here to know if it's an anonymous user or
			// and authenticated user. This will help us determine which store to use.
			return [ $this->authenticatedSessionStore, true ];
		}

		$stats->setLabel( 'type', self::STATS_LABEL_ANON )
			->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
			->increment();
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
		$userId = $data['metadata']['userId'] ?? null;

		if ( $isAuthenticated && $userId === 0 ) {
			$this->logger->warning( 'Authenticated data should not be in the anonymous store', [
				'sessionInfo' => (string)$info,
				'user' => (string)$info->getUserInfo(),
				'exception' => new RuntimeException(),
			] );
		}

		if ( !$isAuthenticated && $userId !== 0 && $userId !== null ) {
			$this->logger->warning( 'Anonymous data should not be in the authenticated store', [
				'sessionInfo' => (string)$info,
				'user' => (string)$info->getUserInfo(),
				'exception' => new RuntimeException(),
			] );
		}

		$this->statsFactory->getCounter( 'sessionstore_get_total' )
			->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
			->increment();

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

		[ $store, $isAuthenticated ] = $this->getActiveStore( $info );

		$key = $store->makeKey( 'MWSession', $info->getId() );
		$userId = $value['metadata']['userId'] ?? null;

		// SessionManager::generateSessionId() can perform a cache warming
		// operation by setting `false` as the cache value. We don't want to
		// log those.
		if ( $value ) {
			if ( $isAuthenticated && $userId === 0 ) {
				$this->logger->warning( 'Session data is authenticated, should not be an anonymous user', [
					'sessionInfo' => (string)$info,
					'user' => (string)$info->getUserInfo(),
					'exception' => new RuntimeException(),
				] );
			}

			if ( !$isAuthenticated && $userId !== 0 && $userId !== null ) {
				$this->logger->warning( 'Session data is anonymous, should not be an authenticated user', [
					'sessionInfo' => (string)$info,
					'user' => (string)$info->getUserInfo(),
					'exception' => new RuntimeException(),
				] );
			}
		}

		$store->set( $key, $value, $exptime, $flags );

		$this->statsFactory->getCounter( 'sessionstore_set_total' )
			->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
			->increment();
	}

	/**
	 * Deletes session data from the session store for the provided key.
	 *
	 * @param SessionInfo $info
	 */
	public function delete( SessionInfo $info ): void {
		$this->logger->debug( __METHOD__ . " was called for $info" );

		[ $store, /** $isAuthenticated */ ] = $this->getActiveStore( $info );
		$key = $store->makeKey( 'MWSession', $info->getId() );

		$store->delete( $key );

		$this->statsFactory->getCounter( 'sessionstore_delete_total' )
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
