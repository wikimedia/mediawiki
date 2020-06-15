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

use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class to handle multiple HTTP requests
 *
 * If curl is available, requests will be made concurrently.
 * Otherwise, they will be made serially.
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
 * Since 1.35, callers should use HttpRequestFactory::createMultiClient() to get
 * a client object with appropriately configured timeouts.
 *
 * @since 1.23
 */
class MultiHttpClient implements LoggerAwareInterface {
	/** @var resource curl_multi_init() handle */
	protected $cmh;
	/** @var string|null SSL certificates path */
	protected $caBundlePath;
	/** @var float */
	protected $connTimeout = 10;
	/** @var float */
	protected $maxConnTimeout = INF;
	/** @var float */
	protected $reqTimeout = 30;
	/** @var float */
	protected $maxReqTimeout = INF;
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

	// In PHP 7 due to https://bugs.php.net/bug.php?id=76480 the request/connect
	// timeouts are periodically polled instead of being accurately respected.
	// The select timeout is set to the minimum timeout multiplied by this factor.
	private const TIMEOUT_ACCURACY_FACTOR = 0.1;

	/**
	 * Since 1.35, callers should use HttpRequestFactory::createMultiClient() to get
	 * a client object with appropriately configured timeouts instead of constructing
	 * a MultiHttpClient directly.
	 *
	 * @param array $options
	 *   - connTimeout     : default connection timeout (seconds)
	 *   - reqTimeout      : default request timeout (seconds)
	 *   - maxConnTimeout  : maximum connection timeout (seconds)
	 *   - maxReqTimeout   : maximum request timeout (seconds)
	 *   - proxy           : HTTP proxy to use
	 *   - usePipelining   : whether to use HTTP pipelining if possible (for all hosts)
	 *   - maxConnsPerHost : maximum number of concurrent connections (per host)
	 *   - userAgent       : The User-Agent header value to send
	 *   - logger          : a \Psr\Log\LoggerInterface instance for debug logging
	 *   - caBundlePath    : path to specific Certificate Authority bundle (if any)
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
			'connTimeout', 'maxConnTimeout', 'reqTimeout', 'maxReqTimeout',
			'usePipelining', 'maxConnsPerHost', 'proxy', 'userAgent', 'logger'
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
	 *   - code    : HTTP response code or 0 if there was a serious error
	 *   - reason  : HTTP response reason (empty if there was a serious error)
	 *   - headers : <header name/value associative array>
	 *   - body    : HTTP response body or resource (if "stream" was set)
	 *   - error     : Any error string
	 * The map also stores integer-indexed copies of these values. This lets callers do:
	 * @code
	 * 		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $http->run( $req );
	 * @endcode
	 * @param array $req HTTP request array
	 * @param array $opts
	 *   - connTimeout     : connection timeout per request (seconds)
	 *   - reqTimeout      : post-connection timeout per request (seconds)
	 *   - usePipelining   : whether to use HTTP pipelining if possible (for all hosts)
	 *   - maxConnsPerHost : maximum number of concurrent connections (per host)
	 * @return array Response array for request
	 */
	public function run( array $req, array $opts = [] ) {
		return $this->runMulti( [ $req ], $opts )[0]['response'];
	}

	/**
	 * Execute a set of HTTP(S) requests.
	 *
	 * If curl is available, requests will be made concurrently.
	 * Otherwise, they will be made serially.
	 *
	 * The maps are returned by this method with the 'response' field set to a map of:
	 *   - code    : HTTP response code or 0 if there was a serious error
	 *   - reason  : HTTP response reason (empty if there was a serious error)
	 *   - headers : <header name/value associative array>
	 *   - body    : HTTP response body or resource (if "stream" was set)
	 *   - error   : Any error string
	 * The map also stores integer-indexed copies of these values. This lets callers do:
	 * @code
	 *        list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $req['response'];
	 * @endcode
	 * All headers in the 'headers' field are normalized to use lower case names.
	 * This is true for the request headers and the response headers. Integer-indexed
	 * method/URL entries will also be changed to use the corresponding string keys.
	 *
	 * @param array[] $reqs Map of HTTP request arrays
	 * @param array $opts Options
	 *   - connTimeout     : connection timeout per request (seconds)
	 *   - reqTimeout      : post-connection timeout per request (seconds)
	 *   - usePipelining   : whether to use HTTP pipelining if possible (for all hosts)
	 *   - maxConnsPerHost : maximum number of concurrent connections (per host)
	 * @return array[] $reqs With response array populated for each
	 * @throws Exception
	 */
	public function runMulti( array $reqs, array $opts = [] ) {
		$this->normalizeRequests( $reqs );
		$opts += [ 'connTimeout' => $this->connTimeout, 'reqTimeout' => $this->reqTimeout ];

		if ( $opts['connTimeout'] > $this->maxConnTimeout ) {
			$opts['connTimeout'] = $this->maxConnTimeout;
		}
		if ( $opts['reqTimeout'] > $this->maxReqTimeout ) {
			$opts['reqTimeout'] = $this->maxReqTimeout;
		}

		if ( $this->isCurlEnabled() ) {
			return $this->runMultiCurl( $reqs, $opts );
		} else {
			return $this->runMultiHttp( $reqs, $opts );
		}
	}

	/**
	 * Determines if the curl extension is available
	 *
	 * @return bool true if curl is available, false otherwise.
	 */
	protected function isCurlEnabled() {
		// Explicitly test if curl_multi* is blocked, as some users' hosts provide
		// them with a modified curl with the multi-threaded parts removed(!)
		return extension_loaded( 'curl' ) && function_exists( 'curl_multi_init' );
	}

	/**
	 * Execute a set of HTTP(S) requests concurrently
	 *
	 * @see MultiHttpClient::runMulti()
	 *
	 * @param array[] $reqs Map of HTTP request arrays
	 * @param array $opts
	 *   - connTimeout     : connection timeout per request (seconds)
	 *   - reqTimeout      : post-connection timeout per request (seconds)
	 *   - usePipelining   : whether to use HTTP pipelining if possible
	 *   - maxConnsPerHost : maximum number of concurrent connections (per host)
	 * @codingStandardsIgnoreStart
	 * @phan-param array{connTimeout?:int,reqTimeout?:int,usePipelining?:bool,maxConnsPerHost?:int} $opts
	 * @codingStandardsIgnoreEnd
	 * @return array $reqs With response array populated for each
	 * @throws Exception
	 * @suppress PhanTypeInvalidDimOffset
	 */
	private function runMultiCurl( array $reqs, array $opts ) {
		$chm = $this->getCurlMulti( $opts );

		$selectTimeout = $this->getSelectTimeout( $opts );

		// Add all of the required cURL handles...
		$handles = [];
		foreach ( $reqs as $index => &$req ) {
			$handles[$index] = $this->getCurlHandle( $req, $opts );
			curl_multi_add_handle( $chm, $handles[$index] );
		}
		unset( $req ); // don't assign over this by accident

		$infos = [];
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
			if ( $active > 0 && $mrc == CURLM_OK && curl_multi_select( $chm, $selectTimeout ) == -1 ) {
				// PHP bug 63411; https://curl.haxx.se/libcurl/c/curl_multi_fdset.html
				usleep( 5000 ); // 5ms
			}
		} while ( $active > 0 && $mrc == CURLM_OK );

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

		return $reqs;
	}

	/**
	 * @param array &$req HTTP request map
	 * @codingStandardsIgnoreStart
	 * @phan-param array{url:string,proxy?:?string,query:mixed,method:string,body:string|resource,headers:string[],stream?:resource,flags:array} $req
	 * @codingStandardsIgnoreEnd
	 * @param array $opts
	 *   - connTimeout : default connection timeout
	 *   - reqTimeout : default request timeout
	 * @return resource
	 * @throws Exception
	 */
	protected function getCurlHandle( array &$req, array $opts ) {
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_PROXY, $req['proxy'] ?? $this->proxy );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT_MS, intval( $opts['connTimeout'] * 1e3 ) );
		curl_setopt( $ch, CURLOPT_TIMEOUT_MS, intval( $opts['reqTimeout'] * 1e3 ) );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 4 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		if ( $this->caBundlePath !== null ) {
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
		curl_setopt( $ch, CURLOPT_NOBODY, ( $req['method'] === 'HEAD' ) );

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
					return (string)fread( $fd, $length );
				}
			);
		} elseif ( $req['method'] === 'POST' ) {
			curl_setopt( $ch, CURLOPT_POST, 1 );
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
				if ( !empty( $req['flags']['relayResponseHeaders'] ) && trim( $header ) !== '' ) {
					header( $header );
				}
				$length = strlen( $header );
				$matches = [];
				if ( preg_match( "/^(HTTP\/(?:1\.[01]|2)) (\d{3}) (.*)/", $header, $matches ) ) {
					$req['response']['code'] = (int)$matches[2];
					$req['response']['reason'] = trim( $matches[3] );
					// After a redirect we will receive this again, but we already stored headers
					// that belonged to a redirect response. Start over.
					$req['response']['headers'] = [];
					return $length;
				}
				if ( strpos( $header, ":" ) === false ) {
					return $length;
				}
				list( $name, $value ) = explode( ":", $header, 2 );
				$name = strtolower( $name );
				$value = trim( $value );
				if ( isset( $req['response']['headers'][$name] ) ) {
					$req['response']['headers'][$name] .= ', ' . $value;
				} else {
					$req['response']['headers'][$name] = $value;
				}
				return $length;
			}
		);

		// This works with both file and php://temp handles (unlike CURLOPT_FILE)
		$hasOutputStream = isset( $req['stream'] );
		curl_setopt( $ch, CURLOPT_WRITEFUNCTION,
			function ( $ch, $data ) use ( &$req, $hasOutputStream ) {
				if ( $hasOutputStream ) {
					return fwrite( $req['stream'], $data );
				} else {
					// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
					$req['response']['body'] .= $data;

					return strlen( $data );
				}
			}
		);

		return $ch;
	}

	/**
	 * @param array $opts
	 * @return resource
	 * @throws Exception
	 */
	protected function getCurlMulti( array $opts ) {
		if ( !$this->cmh ) {
			if ( !function_exists( 'curl_multi_init' ) ) {
				throw new Exception( "PHP cURL function curl_multi_init missing. " .
					"Check https://www.mediawiki.org/wiki/Manual:CURL" );
			}
			$cmh = curl_multi_init();
			// Limit the size of the idle connection cache such that consecutive parallel
			// request batches to the same host can avoid having to keep making connections
			curl_multi_setopt( $cmh, CURLMOPT_MAXCONNECTS, (int)$this->maxConnsPerHost );
			$this->cmh = $cmh;
		}

		// Limit the number of in-flight requests for any given host
		$maxHostConns = $opts['maxConnsPerHost'] ?? $this->maxConnsPerHost;
		curl_multi_setopt( $this->cmh, CURLMOPT_MAX_HOST_CONNECTIONS, (int)$maxHostConns );
		// Configure when to multiplex multiple requests onto single TCP handles
		$pipelining = $opts['usePipelining'] ?? $this->usePipelining;
		curl_multi_setopt( $this->cmh, CURLMOPT_PIPELINING, $pipelining ? 3 : 0 );

		return $this->cmh;
	}

	/**
	 * Execute a set of HTTP(S) requests sequentially.
	 *
	 * @see MultiHttpClient::runMulti()
	 * @todo Remove dependency on MediaWikiServices: use a separate HTTP client
	 *  library or copy code from PhpHttpRequest
	 * @param array $reqs Map of HTTP request arrays
	 * @codingStandardsIgnoreStart
	 * @phan-param array<int,array{url:string,query:array,method:string,body:string,proxy?:?string,headers?:string[]}> $reqs
	 * @codingStandardsIgnoreEnd
	 * @param array $opts
	 *   - connTimeout     : connection timeout per request (seconds)
	 *   - reqTimeout      : post-connection timeout per request (seconds)
	 * @phan-param array{connTimeout:int,reqTimeout:int} $opts
	 * @return array $reqs With response array populated for each
	 * @throws Exception
	 */
	private function runMultiHttp( array $reqs, array $opts = [] ) {
		$httpOptions = [
			'timeout' => $opts['reqTimeout'] ?? $this->reqTimeout,
			'connectTimeout' => $opts['connTimeout'] ?? $this->connTimeout,
			'logger' => $this->logger,
			'caInfo' => $this->caBundlePath,
		];
		foreach ( $reqs as &$req ) {
			$reqOptions = $httpOptions + [
				'method' => $req['method'],
				'proxy' => $req['proxy'] ?? $this->proxy,
				'userAgent' => $req['headers']['user-agent'] ?? $this->userAgent,
				'postData' => $req['body'],
			];

			$url = $req['url'];
			$query = http_build_query( $req['query'], '', '&', PHP_QUERY_RFC3986 );
			if ( $query != '' ) {
				$url .= strpos( $req['url'], '?' ) === false ? "?$query" : "&$query";
			}

			$httpRequest = MediaWikiServices::getInstance()->getHttpRequestFactory()->create(
				$url, $reqOptions, __METHOD__ );
			$sv = $httpRequest->execute()->getStatusValue();

			$respHeaders = array_map(
				function ( $v ) {
					return implode( ', ', $v );
				},
				$httpRequest->getResponseHeaders() );

			$req['response'] = [
				'code' => $httpRequest->getStatus(),
				'reason' => '',
				'headers' => $respHeaders,
				'body' => $httpRequest->getContent(),
				'error' => '',
			];

			if ( !$sv->isOK() ) {
				$svErrors = $sv->getErrors();
				if ( isset( $svErrors[0] ) ) {
					$req['response']['error'] = $svErrors[0]['message'];

					// param values vary per failure type (ex. unknown host vs unknown page)
					if ( isset( $svErrors[0]['params'][0] ) ) {
						if ( is_numeric( $svErrors[0]['params'][0] ) ) {
							if ( isset( $svErrors[0]['params'][1] ) ) {
								// @phan-suppress-next-line PhanTypeInvalidDimOffset
								$req['response']['reason'] = $svErrors[0]['params'][1];
							}
						} else {
							$req['response']['reason'] = $svErrors[0]['params'][0];
						}
					}
				}
			}

			$req['response'][0] = $req['response']['code'];
			$req['response'][1] = $req['response']['reason'];
			$req['response'][2] = $req['response']['headers'];
			$req['response'][3] = $req['response']['body'];
			$req['response'][4] = $req['response']['error'];
		}

		return $reqs;
	}

	/**
	 * Normalize request information
	 *
	 * @param array[] &$reqs the requests to normalize
	 */
	private function normalizeRequests( array &$reqs ) {
		foreach ( $reqs as &$req ) {
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
			$req['query'] = $req['query'] ?? [];
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
			$req['flags'] = $req['flags'] ?? [];
		}
	}

	/**
	 * Get a suitable select timeout for the given options.
	 *
	 * @param array $opts
	 * @return float
	 */
	private function getSelectTimeout( $opts ) {
		$connTimeout = $opts['connTimeout'] ?? $this->connTimeout;
		$reqTimeout = $opts['reqTimeout'] ?? $this->reqTimeout;
		$timeouts = array_filter( [ $connTimeout, $reqTimeout ] );
		if ( count( $timeouts ) === 0 ) {
			return 1;
		}

		$selectTimeout = min( $timeouts ) * self::TIMEOUT_ACCURACY_FACTOR;
		// Minimum 10us for sanity
		if ( $selectTimeout < 10e-6 ) {
			$selectTimeout = 10e-6;
		}
		return $selectTimeout;
	}

	/**
	 * Register a logger
	 *
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	public function __destruct() {
		if ( $this->cmh ) {
			curl_multi_close( $this->cmh );
		}
	}
}
