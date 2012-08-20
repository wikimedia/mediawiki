<?php
/**
 * Base class for memcached clients.
 *
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
 * Base class for memcached clients.
 *
 * @ingroup Cache
 */
class MemcachedBagOStuff extends BagOStuff {
	protected $client;

	/**
	 * Fill in the defaults for any parameters missing from $params, using the
	 * backwards-compatible global variables
	 */
	protected function applyDefaultParams( $params ) {
		if ( !isset( $params['servers'] ) ) {
			$params['servers'] = $GLOBALS['wgMemCachedServers'];
		}
		if ( !isset( $params['debug'] ) ) {
			$params['debug'] = $GLOBALS['wgMemCachedDebug'];
		}
		if ( !isset( $params['persistent'] ) ) {
			$params['persistent'] = $GLOBALS['wgMemCachedPersistent'];
		}
		if  ( !isset( $params['compress_threshold'] ) ) {
			$params['compress_threshold'] = 1500;
		}
		if ( !isset( $params['timeout'] ) ) {
			$params['timeout'] = $GLOBALS['wgMemCachedTimeout'];
		}
		if ( !isset( $params['connect_timeout'] ) ) {
			$params['connect_timeout'] = 0.5;
		}
		return $params;
	}

	/**
	 * @param $key string
	 * @return Mixed
	 */
	public function get( $key ) {
		return $this->client->get( $this->encodeKey( $key ) );
	}

	/**
	 * @param $key string
	 * @param $value
	 * @param $exptime int
	 * @return bool
	 */
	public function set( $key, $value, $exptime = 0 ) {
		return $this->client->set( $this->encodeKey( $key ), $value,
			$this->fixExpiry( $exptime ) );
	}

	/**
	 * @param $key string
	 * @param $time int
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		return $this->client->delete( $this->encodeKey( $key ), $time );
	}

	/**
	 * @param $key string
	 * @param $value int
	 * @param $exptime int (default 0)
	 * @return Mixed
	 */
	public function add( $key, $value, $exptime = 0 ) {
		return $this->client->add( $this->encodeKey( $key ), $value,
			$this->fixExpiry( $exptime ) );
	}

	/**
	 * @param $key string
	 * @param $value int
	 * @param $exptime
	 * @return Mixed
	 */
	public function replace( $key, $value, $exptime = 0 ) {
		return $this->client->replace( $this->encodeKey( $key ), $value, 
			$this->fixExpiry( $exptime ) );
	}

	/**
	 * Get the underlying client object. This is provided for debugging
	 * purposes.
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * Encode a key for use on the wire inside the memcached protocol.
	 *
	 * We encode spaces and line breaks to avoid protocol errors. We encode
	 * the other control characters for compatibility with libmemcached
	 * verify_key. We leave other punctuation alone, to maximise backwards
	 * compatibility.
	 * @param $key string
	 * @return string
	 */
	public function encodeKey( $key ) {
		return preg_replace_callback( '/[\x00-\x20\x25\x7f]+/',
			array( $this, 'encodeKeyCallback' ), $key );
	}

	/**
	 * @param $m array
	 * @return string
	 */
	protected function encodeKeyCallback( $m ) {
		return rawurlencode( $m[0] );
	}

	/**
	 * TTLs higher than 30 days will be detected as absolute TTLs
	 * (UNIX timestamps), and will result in the cache entry being
	 * discarded immediately because the expiry is in the past.
	 * Clamp expiries >30d at 30d, unless they're >=1e9 in which
	 * case they are likely to really be absolute (1e9 = 2011-09-09)
	 */
	function fixExpiry( $expiry ) {
		if ( $expiry > 2592000 && $expiry < 1000000000 ) {
			$expiry = 2592000;
		}
		return $expiry;
	}

	/**
	 * Decode a key encoded with encodeKey(). This is provided as a convenience
	 * function for debugging.
	 *
	 * @param $key string
	 *
	 * @return string
	 */
	public function decodeKey( $key ) {
		return urldecode( $key );
	}

	/**
	 * Send a debug message to the log
	 */
	protected function debugLog( $text ) {
		global $wgDebugLogGroups;
		if( !isset( $wgDebugLogGroups['memcached'] ) ) {
			# Prefix message since it will end up in main debug log file
			$text = "memcached: $text";
		}
		if ( substr( $text, -1 ) !== "\n" ) {
			$text .= "\n";
		}
		wfDebugLog( 'memcached', $text );
	}
}

