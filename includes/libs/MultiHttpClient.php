<?php
/**
 * HTTP service client
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
 */

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class to handle concurrent HTTP requests
 *
 * HTTP request maps are arrays that use the following format:
 *   - method   : GET/HEAD/PUT/POST/DELETE
 *   - url      : HTTP/HTTPS URL
 *   - query    : <query parameter field/value associative array> (uses RFC 3986)
 *   - headers  : <header name/value associative array>
 *   - body     : source to get the HTTP request body from;
 *                this can simply be a string (always), a resource for
 *                PUT requests, and a field/value array for POST request;
 *                array bodies are encoded as multipart/form-data and strings
 *                use application/x-www-form-urlencoded (headers sent automatically)
 *   - stream   : resource to stream the HTTP response body to
 *   - proxy    : HTTP proxy to use
 *   - flags    : map of boolean flags which supports:
 *                  - relayResponseHeaders : write out header via header()
 * Request maps can use integer index 0 instead of 'method' and 1 instead of 'url'.
 *
 * @since 1.23
 */
class MultiHttpClient implements LoggerAwareInterface {
	/** @var resource */
	protected $multiHandle = null; // curl_multi handle
	/** @var string|null SSL certificates path */
	protected $caBundlePath;
	/** @var int */
	protected $connTimeout = 10;
	/** @var int */
	protected $reqTimeout = 300;
	/** @var bool */
	protected $usePipelining = false;
	/** @var int */
	protected $maxConnsPerHost = 50;
	/** @var string|null proxy */
	protected $proxy;
	/** @var string */
	protected $userAgent = 'wikimedia/multi-http-client v1.0';
	/** @var LoggerInterface */
	protected $logger;

	/**
	 * @param array $options
	 *   - connTimeout     : default connection timeout (seconds)
	 *   - reqTimeout      : default request timeout (seconds)
	 *   - proxy           : HTTP proxy to use
	 *   - usePipelining   : whether to use HTTP pipelining if possible (for all hosts)
	 *   - maxConnsPerHost : maximum number of concurrent connections (per host)
	 *   - userAgent       : The User-Agent header value to send
	 * @throws Exception
	 */
	public function __construct( array $options ) {
		if ( isset( $options['caBundlePath'] ) ) {
			$this->caBundlePath = $options['caBundlePath'];
			if ( !file_exists( $this->caBundlePath ) ) {
				throw new Exception( "Cannot find CA bundle: " . $this->caBundlePath );
			}
		}
		static $opts = [
			'connTimeout', 'reqTimeout', 'usePipelining', 'maxConnsPerHost',
			'proxy', 'userAgent', 'logger'
		];
		foreach ( $opts as $key ) {
			if ( isset( $options[$key] ) ) {
				$this->$key = $options[$key];
			}
		}
		if ( $this->logger === null ) {
			$this->logger = new NullLogger;
		}
	}

	/**
	 * Execute an HTTP(S) request
	 *
	 * This method returns a response map of:
	 *   - code    : HTTP response code or 0 if there was a serious cURL error
	 *   - reason  : HTTP response reason (empty if there was a serious cURL error)
	 *   - headers : <header name/value associative array>
	 *   - body    : HTTP response body or resource (if "stream" was set)
	 *   - error     : Any cURL error string
	 * The map also stores integer-indexed copies of these values. This lets callers do:
	 * @code
	 *		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $http->run( $req );
	 * @endcode
	 * @param array $req HTTP request array
	 * @param array $opts
	 *   - connTimeout    : connection timeout per request (seconds)
	 *   - reqTimeout     : post-connection timeout per request (seconds)
	 * @return array Response array for request
	 */
	public function run( array $req, array $opts = [] ) {
		return $this->runMulti( [ $req ], $opts )[0]['response'];
	}

	/**
	 * Execute a set of HTTP(S) requests concurrently
	 *
	 * The maps are returned by this method with the 'response' field set to a map of:
	 *   - code    : HTTP response code or 0 if there was a serious cURL error
	 *   - reason  : HTTP response reason (empty if there was a serious cURL error)
	 *   - headers : <header name/value associative array>
	 *   - body    : HTTP response body or resource (if "stream" was set)
	 *   - error   : Any cURL error string
	 * The map also stores integer-indexed copies of these values. This lets callers do:
	 * @code
	 *        list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $req['response'];
	 * @endcode
	 * All headers in the 'headers' field are normalized to use lower case names.
	 * This is true for the request headers and the response headers. Integer-indexed
	 * method/URL entries will also be changed to use the corresponding string keys.
	 *
	 * @param array $reqs Map of HTTP request arrays
	 * @param array $opts
	 *   - connTimeout     : connection timeout per request (seconds)
	 *   - reqTimeout      : post-connection timeout per request (seconds)
	 *   - usePipelining   : whether to use HTTP pipelining if possible
	 *   - maxConnsPerHost : maximum number of concurrent connections (per host)
	 * @return array $reqs With response array populated for each
	 * @throws Exception
	 */
	public function runMulti( array $reqs, array $opts = [] ) {
		$chm = $this->getCurlMulti();

		// Normalize $reqs and add all of the required cURL handles...
		$handles = [];
		foreach ( $reqs as $index => &$req ) {
			$req['response'] = [
				'code'     => 0,
				'reason'   => '',
				'headers'  => [],
				'body'     => '',
				'error'    => ''
			];
			if ( isset( $req[0] ) ) {
				$req['method'] = $req[0]; // short-form
				unset( $req[0] );
			}
			if ( isset( $req[1] ) ) {
				$req['url'] = $req[1]; // short-form
				unset( $req[1] );
			}
			if ( !isset( $req['method'] ) ) {
				throw new Exception( "Request has no 'method' field set." );
			} elseif ( !isset( $req['url'] ) ) {
				throw new Exception( "Request has no 'url' field set." );
			}
			$this->logger->debug( "{$req['method']}: {$req['url']}" );
			$req['query'] = isset( $req['query'] ) ? $req['query'] : [];
			$headers = []; // normalized headers
			if ( isset( $req['headers'] ) ) {
				foreach ( $req['headers'] as $name => $value ) {
					$headers[strtolower( $name )] = $value;
				}
			}
			$req['headers'] = $headers;
			if ( !isset( $req['body'] ) ) {
				$req['body'] = '';
				$req['headers']['content-length'] = 0;
			}
			$req['flags'] = isset( $req['flags'] ) ? $req['flags'] : [];
			$handles[$index] = $this->getCurlHandle( $req, $opts );
			if ( count( $reqs ) > 1 ) {
				// https://github.com/guzzle/guzzle/issues/349
				curl_setopt( $handles[$index], CURLOPT_FORBID_REUSE, true );
			}
		}
		unset( $req ); // don't assign over this by accident

		$indexes = array_keys( $reqs );
		if ( isset( $opts['usePipelining'] ) ) {
			curl_multi_setopt( $chm, CURLMOPT_PIPELINING, (int)$opts['usePipelining'] );
		}
		if ( isset( $opts['maxConnsPerHost'] ) ) {
			// Keep these sockets around as they may be needed later in the request
			curl_multi_setopt( $chm, CURLMOPT_MAXCONNECTS, (int)$opts['maxConnsPerHost'] );
		}

		// @TODO: use a per-host rolling handle window (e.g. CURLMOPT_MAX_HOST_CONNECTIONS)
		$batches = array_chunk( $indexes, $this->maxConnsPerHost );
		$infos = [];

		foreach ( $batches as $batch ) {
			// Attach all cURL handles for this batch
			foreach ( $batch as $index ) {
				curl_multi_add_handle( $chm, $handles[$index] );
			}
			// Execute the cURL handles concurrently...
			$active = null; // handles still being processed
			do {
				// Do any available work...
				do {
					$mrc = curl_multi_exec( $chm, $active );
					$info = curl_multi_info_read( $chm );
					if ( $info !== false ) {
						$infos[(int)$info['handle']] = $info;
					}
				} while ( $mrc == CURLM_CALL_MULTI_PERFORM );
				// Wait (if possible) for available work...
				if ( $active > 0 && $mrc == CURLM_OK ) {
					if ( curl_multi_select( $chm, 10 ) == -1 ) {
						// PHP bug 63411; https://curl.haxx.se/libcurl/c/curl_multi_fdset.html
						usleep( 5000 ); // 5ms
					}
				}
			} while ( $active > 0 && $mrc == CURLM_OK );
		}

		// Remove all of the added cURL handles and check for errors...
		foreach ( $reqs as $index => &$req ) {
			$ch = $handles[$index];
			curl_multi_remove_handle( $chm, $ch );

			if ( isset( $infos[(int)$ch] ) ) {
				$info = $infos[(int)$ch];
				$errno = $info['result'];
				if ( $errno !== 0 ) {
					$req['response']['error'] = "(curl error: $errno)";
					if ( function_exists( 'curl_strerror' ) ) {
						$req['response']['error'] .= " " . curl_strerror( $errno );
					}
					$this->logger->warning( "Error fetching URL \"{$req['url']}\": " .
						$req['response']['error'] );
				}
			} else {
				$req['response']['error'] = "(curl error: no status set)";
			}

			// For convenience with the list() operator
			$req['response'][0] = $req['response']['code'];
			$req['response'][1] = $req['response']['reason'];
			$req['response'][2] = $req['response']['headers'];
			$req['response'][3] = $req['response']['body'];
			$req['response'][4] = $req['response']['error'];
			curl_close( $ch );
			// Close any string wrapper file handles
			if ( isset( $req['_closeHandle'] ) ) {
				fclose( $req['_closeHandle'] );
				unset( $req['_closeHandle'] );
			}
		}
		unset( $req ); // don't assign over this by accident

		// Restore the default settings
		curl_multi_setopt( $chm, CURLMOPT_PIPELINING, (int)$this->usePipelining );
		curl_multi_setopt( $chm, CURLMOPT_MAXCONNECTS, (int)$this->maxConnsPerHost );

		return $reqs;
	}

	/**
	 * @param array &$req HTTP request map
	 * @param array $opts
	 *   - connTimeout    : default connection timeout
	 *   - reqTimeout     : default request timeout
	 * @return resource
	 * @throws Exception
	 */
	protected function getCurlHandle( array &$req, array $opts = [] ) {
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT,
			isset( $opts['connTimeout'] ) ? $opts['connTimeout'] : $this->connTimeout );
		curl_setopt( $ch, CURLOPT_PROXY, isset( $req['proxy'] ) ? $req['proxy'] : $this->proxy );
		curl_setopt( $ch, CURLOPT_TIMEOUT,
			isset( $opts['reqTimeout'] ) ? $opts['reqTimeout'] : $this->reqTimeout );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 4 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		if ( !is_null( $this->caBundlePath ) ) {
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
			curl_setopt( $ch, CURLOPT_CAINFO, $this->caBundlePath );
		}
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

		$url = $req['url'];
		$query = http_build_query( $req['query'], '', '&', PHP_QUERY_RFC3986 );
		if ( $query != '' ) {
			$url .= strpos( $req['url'], '?' ) === false ? "?$query" : "&$query";
		}
		curl_setopt( $ch, CURLOPT_URL, $url );

		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $req['method'] );
		if ( $req['method'] === 'HEAD' ) {
			curl_setopt( $ch, CURLOPT_NOBODY, 1 );
		}

		if ( $req['method'] === 'PUT' ) {
			curl_setopt( $ch, CURLOPT_PUT, 1 );
			if ( is_resource( $req['body'] ) ) {
				curl_setopt( $ch, CURLOPT_INFILE, $req['body'] );
				if ( isset( $req['headers']['content-length'] ) ) {
					curl_setopt( $ch, CURLOPT_INFILESIZE, $req['headers']['content-length'] );
				} elseif ( isset( $req['headers']['transfer-encoding'] ) &&
					$req['headers']['transfer-encoding'] === 'chunks'
				) {
					curl_setopt( $ch, CURLOPT_UPLOAD, true );
				} else {
					throw new Exception( "Missing 'Content-Length' or 'Transfer-Encoding' header." );
				}
			} elseif ( $req['body'] !== '' ) {
				$fp = fopen( "php://temp", "wb+" );
				fwrite( $fp, $req['body'], strlen( $req['body'] ) );
				rewind( $fp );
				curl_setopt( $ch, CURLOPT_INFILE, $fp );
				curl_setopt( $ch, CURLOPT_INFILESIZE, strlen( $req['body'] ) );
				$req['_closeHandle'] = $fp; // remember to close this later
			} else {
				curl_setopt( $ch, CURLOPT_INFILESIZE, 0 );
			}
			curl_setopt( $ch, CURLOPT_READFUNCTION,
				function ( $ch, $fd, $length ) {
					$data = fread( $fd, $length );
					$len = strlen( $data );
					return $data;
				}
			);
		} elseif ( $req['method'] === 'POST' ) {
			curl_setopt( $ch, CURLOPT_POST, 1 );
			// Don't interpret POST parameters starting with '@' as file uploads, because this
			// makes it impossible to POST plain values starting with '@' (and causes security
			// issues potentially exposing the contents of local files).
			curl_setopt( $ch, CURLOPT_SAFE_UPLOAD, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $req['body'] );
		} else {
			if ( is_resource( $req['body'] ) || $req['body'] !== '' ) {
				throw new Exception( "HTTP body specified for a non PUT/POST request." );
			}
			$req['headers']['content-length'] = 0;
		}

		if ( !isset( $req['headers']['user-agent'] ) ) {
			$req['headers']['user-agent'] = $this->userAgent;
		}

		$headers = [];
		foreach ( $req['headers'] as $name => $value ) {
			if ( strpos( $name, ': ' ) ) {
				throw new Exception( "Headers cannot have ':' in the name." );
			}
			$headers[] = $name . ': ' . trim( $value );
		}
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

		curl_setopt( $ch, CURLOPT_HEADERFUNCTION,
			function ( $ch, $header ) use ( &$req ) {
				if ( !empty( $req['flags']['relayResponseHeaders'] ) ) {
					header( $header );
				}
				$length = strlen( $header );
				$matches = [];
				if ( preg_match( "/^(HTTP\/1\.[01]) (\d{3}) (.*)/", $header, $matches ) ) {
					$req['response']['code'] = (int)$matches[2];
					$req['response']['reason'] = trim( $matches[3] );
					return $length;
				}
				if ( strpos( $header, ":" ) === false ) {
					return $length;
				}
				list( $name, $value ) = explode( ":", $header, 2 );
				$req['response']['headers'][strtolower( $name )] = trim( $value );
				return $length;
			}
		);

		if ( isset( $req['stream'] ) ) {
			// Don't just use CURLOPT_FILE as that might give:
			// curl_setopt(): cannot represent a stream of type Output as a STDIO FILE*
			// The callback here handles both normal files and php://temp handles.
			curl_setopt( $ch, CURLOPT_WRITEFUNCTION,
				function ( $ch, $data ) use ( &$req ) {
					return fwrite( $req['stream'], $data );
				}
			);
		} else {
			curl_setopt( $ch, CURLOPT_WRITEFUNCTION,
				function ( $ch, $data ) use ( &$req ) {
					$req['response']['body'] .= $data;
					return strlen( $data );
				}
			);
		}

		return $ch;
	}

	/**
	 * @return resource
	 * @throws Exception
	 */
	protected function getCurlMulti() {
		if ( !$this->multiHandle ) {
			if ( !function_exists( 'curl_multi_init' ) ) {
				throw new Exception( "PHP cURL extension missing. " .
									 "Check https://www.mediawiki.org/wiki/Manual:CURL" );
			}
			$cmh = curl_multi_init();
			curl_multi_setopt( $cmh, CURLMOPT_PIPELINING, (int)$this->usePipelining );
			curl_multi_setopt( $cmh, CURLMOPT_MAXCONNECTS, (int)$this->maxConnsPerHost );
			$this->multiHandle = $cmh;
		}
		return $this->multiHandle;
	}

	/**
	 * Register a logger
	 *
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	function __destruct() {
		if ( $this->multiHandle ) {
			curl_multi_close( $this->multiHandle );
		}
	}
}
