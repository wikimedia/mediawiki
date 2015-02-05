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
 * @ingroup Cache
 */

/**
 * A cache class that directs writes to one set of servers and reads to
 * another. This assumes that the servers used for reads are setup to slave
 * those that writes go to. This can easily be used with redis for example.
 *
 * @ingroup Cache
 * @since 1.25
 */
class ReplicatedBagOStuff extends BagOStuff {
	/** @var BagOStuff */
	protected $mCache;
	/** @var BagOStuff */
	protected $sCache;

	/**
	 * Constructor. Parameters are:
	 *   - masterCache : Cache parameter structures, in the style required by $wgObjectCaches.
	 *                   See the documentation of $wgObjectCaches for more detail.
	 *   - slaveCache  : Cache parameter structures, in the style required by $wgObjectCaches.
	 *                   See the documentation of $wgObjectCaches for more detail.
	 *
	 * @param array $params
	 * @throws MWException
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		if ( !isset( $params['masterCache'] ) ) {
			throw new MWException( __METHOD__ . ': the "masterCache" parameter is required' );
		} elseif ( !isset( $params['slaveCache'] ) ) {
			throw new MWException( __METHOD__ . ': the "slaveCache" parameter is required' );
		}

		$this->mCache = ( $params['masterCache'] instanceof BagOStuff )
			? $params['masterCache']
			: ObjectCache::newFromParams( $params['masterCache'] );
		$this->sCache = ( $params['slaveCache'] instanceof BagOStuff )
			? $params['slaveCache']
			: ObjectCache::newFromParams( $params['slaveCache'] );
	}

	public function setDebug( $debug ) {
		$this->doWrite( 'setDebug', $debug );
	}

	public function get( $key, &$casToken = null ) {
		return $this->sCache->get( $key, $casToken );
	}

	public function cas( $casToken, $key, $value, $exptime = 0 ) {
		return $this->mCache->cas( $casToken, $key, $value, $exptime );
	}

	public function set( $key, $value, $exptime = 0 ) {
		return $this->mCache->set( $key, $value, $exptime );
	}

	public function delete( $key, $time = 0 ) {
		return $this->mCache->delete( $key, $time );
	}

	public function add( $key, $value, $exptime = 0 ) {
		return $this->mCache->add( $key, $value, $exptime );
	}

	public function incr( $key, $value = 1 ) {
		return $this->mCache->incr( $key, $value );
	}

	public function decr( $key, $value = 1 ) {
		return $this->mCache->decr( $key, $value );
	}

	public function lock( $key, $timeout = 6, $expiry = 6 ) {
		$this->mCache->lock( $key, $timeout, $expiry );
	}

	public function unlock( $key ) {
		$this->mCache->unlock( $key );
	}

	public function merge( $key, Closure $callback, $exptime = 0, $attempts = 10 ) {
		$this->mCache->merge( $key, $callback, $exptime, $attempts );
	}

	public function getLastError() {
		return ( $this->mCache->getLastError() != self::ERR_NONE )
			? $this->mCache->getLastError()
			: $this->sCache->getLastError();
	}

	public function clearLastError() {
		$this->mCache->clearLastError();
		$this->sCache->clearLastError();
	}
}
