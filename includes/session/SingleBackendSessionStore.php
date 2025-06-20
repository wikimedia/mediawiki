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

use MediaWiki\Logger\LoggerFactory;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\CachedBagOStuff;

/**
 * An implementation of a session store with a single backend for storing
 * anonymous and authenticated sessions. Authenticated and anonymous sessions
 * are treated the same in terms of their TTL.
 *
 * @ingroup Session
 * @since 1.45
 */
class SingleBackendSessionStore implements SessionStore {

	private CachedBagOStuff $store;

	public function __construct(
		BagOStuff $store,
	) {
		$this->store = $store instanceof CachedBagOStuff ? $store : new CachedBagOStuff( $store );
		LoggerFactory::getInstance( 'session' )->debug(
			'SessionManager using store ' . get_class( $this->store )
		);
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
		return $this->store->get( $key );
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
	}

	/**
	 * Deletes session data from the session store for the provided key.
	 *
	 * @param SessionInfo $info
	 */
	public function delete( SessionInfo $info ): void {
		$key = $this->makeKey( 'MWSession', $info->getId() );
		$this->store->delete( $key );
	}

	/**
	 * @inheritDoc
	 */
	public function shutdown(): void {
		$this->store->deleteObjectsExpiringBefore( wfTimestampNow() );
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
