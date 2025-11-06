<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Session
 */

namespace MediaWiki\Session;

use MediaWiki\WikiMap\WikiMap;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\CachedBagOStuff;
use Wikimedia\Stats\StatsFactory;

/**
 * An implementation of a session store with a single backend for storing
 * anonymous and authenticated sessions. Authenticated and anonymous sessions
 * are treated the same in terms of their TTL.
 *
 * @ingroup Session
 * @since 1.45
 */
class SingleBackendSessionStore implements SessionStore {

	use LoggerAwareTrait;

	private CachedBagOStuff $store;
	private StatsFactory $statsFactory;

	public function __construct(
		BagOStuff $store,
		LoggerInterface $logger,
		StatsFactory $statsFactory
	) {
		$this->setLogger( $logger );
		$this->logger->debug( 'SessionManager using store ' . get_class( $store ) );
		$this->store = $store instanceof CachedBagOStuff ? $store : new CachedBagOStuff( $store );
		$this->statsFactory = $statsFactory;
	}

	/** @inheritDoc */
	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
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
		$key = $this->makeKey( 'MWSession', $info->getId() );
		$value = $this->store->get( $key );

		$stats = $this->statsFactory->getCounter( 'sessionstore_get_total' );

		if ( !$this->store->wasLastGetCached() ) {
			$stats->setLabel( 'type', 'singlebackend' )
				->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
				->increment();
		}

		return $value;
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
		$key = $this->makeKey( 'MWSession', $info->getId() );
		$this->store->set( $key, $value, $exptime, $flags );

		if ( ( $flags & BagOStuff::WRITE_CACHE_ONLY ) === 0 ) {
			$this->statsFactory->getCounter( 'sessionstore_set_total' )
				->setLabel( 'type', 'singlebackend' )
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
		$key = $this->makeKey( 'MWSession', $info->getId() );
		$this->store->delete( $key );

		$this->statsFactory->getCounter( 'sessionstore_delete_total' )
			->setLabel( 'type', 'singlebackend' )
			->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
			->increment();
	}

	/**
	 * @inheritDoc
	 */
	public function shutdown(): void {
		// https://www.php.net/manual/en/session.configuration.php#ini.session.gc-divisor
		// Doing this here because of how Session::gc() works in PHP session handler. The
		// only difference here is that this is done at the end of the request rather than
		// the beginning. But we want to preserve behavior for 1 in every 100 requests.
		if ( random_int( 1, 100 ) === 1 ) {
			$this->store->deleteObjectsExpiringBefore( wfTimestampNow() );
		}
	}

	/**
	 * @param string $keygroup
	 * @param string|int $components
	 *
	 * @return string
	 */
	private function makeKey( string $keygroup, $components ): string {
		return $this->store->makeKey( $keygroup, $components );
	}
}
