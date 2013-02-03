<?php
/**
 * Object caching using the Ehcache RESTful web service.
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
 * Client for the Ehcache RESTful web service - http://ehcache.org/documentation/cache_server.html
 * TODO: Simplify configuration and add to the installer.
 *
 * @ingroup Cache
 */
class EhcacheBagOStuff extends BagOStuff {
	var $servers, $cacheName, $connectTimeout, $timeout, $curlOptions,
		$requestData, $requestDataPos;

	var $curls = array();

	/**
	 * @param $params array
	 * @throws MWException
	 */
	function __construct( $params ) {
		if ( !defined( 'CURLOPT_TIMEOUT_MS' ) ) {
			throw new MWException( __CLASS__ . ' requires curl version 7.16.2 or later.' );
		}
		if ( !extension_loaded( 'zlib' ) ) {
			throw new MWException( __CLASS__ . ' requires the zlib extension' );
		}
		if ( !isset( $params['servers'] ) ) {
			throw new MWException( __METHOD__ . ': servers parameter is required' );
		}
		$this->servers = $params['servers'];
		$this->cacheName = isset( $params['cache'] ) ? $params['cache'] : 'mw';
		$this->connectTimeout = isset( $params['connectTimeout'] )
			? $params['connectTimeout'] : 1;
		$this->timeout = isset( $params['timeout'] ) ? $params['timeout'] : 1;
		$this->curlOptions = array(
			CURLOPT_CONNECTTIMEOUT_MS => intval( $this->connectTimeout * 1000 ),
			CURLOPT_TIMEOUT_MS => intval( $this->timeout * 1000 ),
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_POST => 0,
			CURLOPT_POSTFIELDS => '',
			CURLOPT_HTTPHEADER => array(),
		);
	}

	/**
	 * @param $key string
	 * @param $casToken[optional] mixed
	 * @return bool|mixed
	 */
	public function get( $key, &$casToken = null ) {
		wfProfileIn( __METHOD__ );
		$response = $this->doItemRequest( $key );
		if ( !$response || $response['http_code'] == 404 ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		if ( $response['http_code'] >= 300 ) {
			wfDebug( __METHOD__ . ": GET failure, got HTTP {$response['http_code']}\n" );
			wfProfileOut( __METHOD__ );
			return false;
		}
		$body = $response['body'];
		$type = $response['content_type'];
		if ( $type == 'application/vnd.php.serialized+deflate' ) {
			$body = gzinflate( $body );
			if ( !$body ) {
				wfDebug( __METHOD__ . ": error inflating $key\n" );
				wfProfileOut( __METHOD__ );
				return false;
			}
			$data = unserialize( $body );
		} elseif ( $type == 'application/vnd.php.serialized' ) {
			$data = unserialize( $body );
		} else {
			wfDebug( __METHOD__ . ": unknown content type \"$type\"\n" );
			wfProfileOut( __METHOD__ );
			return false;
		}

		$casToken = $body;

		wfProfileOut( __METHOD__ );
		return $data;
	}

	/**
	 * @param $key string
	 * @param $value mixed
	 * @param $expiry int
	 * @return bool
	 */
	public function set( $key, $value, $expiry = 0 ) {
		wfProfileIn( __METHOD__ );
		$expiry = $this->convertExpiry( $expiry );
		$ttl = $expiry ? $expiry - time() : 2147483647;
		$blob = serialize( $value );
		if ( strlen( $blob ) > 100 ) {
			$blob = gzdeflate( $blob );
			$contentType = 'application/vnd.php.serialized+deflate';
		} else {
			$contentType = 'application/vnd.php.serialized';
		}

		$code = $this->attemptPut( $key, $blob, $contentType, $ttl );

		if ( $code == 404 ) {
			// Maybe the cache does not exist yet, let's try creating it
			if ( !$this->createCache( $key ) ) {
				wfDebug( __METHOD__ . ": cache creation failed\n" );
				wfProfileOut( __METHOD__ );
				return false;
			}
			$code = $this->attemptPut( $key, $blob, $contentType, $ttl );
		}

		$result = false;
		if ( !$code ) {
			wfDebug( __METHOD__ . ": PUT failure for key $key\n" );
		} elseif ( $code >= 300 ) {
			wfDebug( __METHOD__ . ": PUT failure for key $key: HTTP $code\n" );
		} else {
			$result = true;
		}

		wfProfileOut( __METHOD__ );
		return $result;
	}

	/**
	 * @param $casToken mixed
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int
	 * @return bool
	 */
	public function cas( $casToken, $key, $value, $exptime = 0 ) {
		// Not sure if we can implement CAS for ehcache. There appears to be CAS-support per
		// http://ehcache.org/documentation/get-started/consistency-options#cas-cache-operations,
		// but I can't find any docs for our current implementation.
		throw new MWException( "CAS is not implemented in " . __CLASS__ );
	}

	/**
	 * @param $key string
	 * @param $time int
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		wfProfileIn( __METHOD__ );
		$response = $this->doItemRequest( $key,
			array( CURLOPT_CUSTOMREQUEST => 'DELETE' ) );
		$code = isset( $response['http_code'] ) ? $response['http_code'] : 0;
		if ( !$response || ( $code != 404 && $code >= 300 ) ) {
			wfDebug( __METHOD__ . ": DELETE failure for key $key\n" );
			$result = false;
		} else {
			$result = true;
		}
		wfProfileOut( __METHOD__ );
		return $result;
	}

	/**
	 * @see BagOStuff::merge()
	 * @return bool success
	 */
	public function merge( $key, closure $callback, $exptime = 0, $attempts = 10 ) {
		return $this->mergeViaLock( $key, $callback, $exptime, $attempts );
	}

	/**
	 * @param $key string
	 * @return string
	 */
	protected function getCacheUrl( $key ) {
		if ( count( $this->servers ) == 1 ) {
			$server = reset( $this->servers );
		} else {
			// Use consistent hashing
			$hashes = array();
			foreach ( $this->servers as $server ) {
				$hashes[$server] = md5( $server . '/' . $key );
			}
			asort( $hashes );
			reset( $hashes );
			$server = key( $hashes );
		}
		return "http://$server/ehcache/rest/{$this->cacheName}";
	}

	/**
	 * Get a cURL handle for the given cache URL.
	 * We cache the handles to allow keepalive.
	 */
	protected function getCurl( $cacheUrl ) {
		if ( !isset( $this->curls[$cacheUrl] ) ) {
			$this->curls[$cacheUrl] = curl_init();
		}
		return $this->curls[$cacheUrl];
	}

	/**
	 * @param $key string
	 * @param $data
	 * @param $type
	 * @param $ttl
	 * @return int
	 */
	protected function attemptPut( $key, $data, $type, $ttl ) {
		// In initial benchmarking, it was 30 times faster to use CURLOPT_POST
		// than CURLOPT_UPLOAD with CURLOPT_READFUNCTION. This was because
		// CURLOPT_UPLOAD was pushing the request headers first, then waiting
		// for an ACK packet, then sending the data, whereas CURLOPT_POST just
		// sends the headers and the data in a single send().
		$response = $this->doItemRequest( $key,
			array(
				CURLOPT_POST => 1,
				CURLOPT_CUSTOMREQUEST => 'PUT',
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_HTTPHEADER => array(
					'Content-Type: ' . $type,
					'ehcacheTimeToLiveSeconds: ' . $ttl
				)
			)
		);
		if ( !$response ) {
			return 0;
		} else {
			return $response['http_code'];
		}
	}

	/**
	 * @param $key string
	 * @return bool
	 */
	protected function createCache( $key ) {
		wfDebug( __METHOD__ . ": creating cache for $key\n" );
		$response = $this->doCacheRequest( $key,
			array(
				CURLOPT_POST => 1,
				CURLOPT_CUSTOMREQUEST => 'PUT',
				CURLOPT_POSTFIELDS => '',
			) );
		if ( !$response ) {
			wfDebug( __CLASS__ . ": failed to create cache for $key\n" );
			return false;
		}
		return ( $response['http_code'] == 201 /* created */
			|| $response['http_code'] == 409 /* already there */ );
	}

	/**
	 * @param $key string
	 * @param $curlOptions array
	 * @return array|bool|mixed
	 */
	protected function doCacheRequest( $key, $curlOptions = array() ) {
		$cacheUrl = $this->getCacheUrl( $key );
		$curl = $this->getCurl( $cacheUrl );
		return $this->doRequest( $curl, $cacheUrl, $curlOptions );
	}

	/**
	 * @param $key string
	 * @param $curlOptions array
	 * @return array|bool|mixed
	 */
	protected function doItemRequest( $key, $curlOptions = array() ) {
		$cacheUrl = $this->getCacheUrl( $key );
		$curl = $this->getCurl( $cacheUrl );
		$url = $cacheUrl . '/' . rawurlencode( $key );
		return $this->doRequest( $curl, $url, $curlOptions );
	}

	/**
	 * @param $curl
	 * @param $url string
	 * @param $curlOptions array
	 * @return array|bool|mixed
	 * @throws MWException
	 */
	protected function doRequest( $curl, $url, $curlOptions = array() ) {
		if ( array_diff_key( $curlOptions, $this->curlOptions ) ) {
			// var_dump( array_diff_key( $curlOptions, $this->curlOptions ) );
			throw new MWException( __METHOD__ . ": to prevent options set in one doRequest() " .
				"call from affecting subsequent doRequest() calls, only options listed " .
				"in \$this->curlOptions may be specified in the \$curlOptions parameter." );
		}
		$curlOptions += $this->curlOptions;
		$curlOptions[CURLOPT_URL] = $url;

		curl_setopt_array( $curl, $curlOptions );
		$result = curl_exec( $curl );
		if ( $result === false ) {
			wfDebug( __CLASS__ . ": curl error: " . curl_error( $curl ) . "\n" );
			return false;
		}
		$info = curl_getinfo( $curl );
		$info['body'] = $result;
		return $info;
	}
}
