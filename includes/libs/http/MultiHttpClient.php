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

namespace Wikimedia\Http;

use InvalidArgumentException;
use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;

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
	/** Regex for headers likely to contain tokens, etc. that we want to redact from logs */
	private const SENSITIVE_HEADERS = '/(^|-|_)(authorization|auth|password|cookie)($|-|_)/';
	/**
	 * @phpcs:ignore MediaWiki.Commenting.PropertyDocumentation.ObjectTypeHintVar
	 * @var resource|object|null curl_multi_init() handle, initialized in getCurlMulti()
	 */
	protected $cmh = null;
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
	/** @var string|null */
	protected $proxy;
	/** @var string|false */
	protected $localProxy = false;
	/** @var string[] */
	protected $localVirtualHosts = [];
	/** @var string */
	protected $userAgent = 'wikimedia/multi-http-client v1.1';
	/** @var LoggerInterface */
	protected $logger;
	protected array $headers = [];

	// In PHP 7 due to https://bugs.php.net/bug.php?id=76480 the request/connect
	// timeouts are periodically polled instead of being accurately respected.
	// The select timeout is set to the minimum timeout multiplied by this factor.
	private const TIMEOUT_ACCURACY_FACTOR = 0.1;

	private ?TelemetryHeadersInterface $telemetry = null;

	/**
	 * Since 1.35, callers should use HttpRequestFactory::createMultiClient() to get
	 * a client object with appropriately configured timeouts instead of constructing
	 * a MultiHttpClient directly.
	 *
	 * @param array $options
	 *   - connTimeout       : default connection timeout (seconds)
	 *   - reqTimeout        : default request timeout (seconds)
	 *   - maxConnTimeout    : maximum connection timeout (seconds)
	 *   - maxReqTimeout     : maximum request timeout (seconds)
	 *   - proxy             : HTTP proxy to use
	 *   - localProxy        : Reverse proxy to use for domains in localVirtualHosts
	 *   - localVirtualHosts : Domains that are configured as virtual hosts on the same machine
	 *   - usePipelining     : whether to use HTTP pipelining if possible (for all hosts)
	 *   - maxConnsPerHost   : maximum number of concurrent connections (per host)
	 *   - userAgent         : The User-Agent header value to send
	 *   - logger            : a \Psr\Log\LoggerInterface instance for debug logging
	 *   - caBundlePath      : path to specific Certificate Authority bundle (if any)
	 *   - headers           : an array of default headers to send with every request
	 *   - telemetry         : a \Wikimedia\Http\RequestTelemetry instance to track telemetry data
	 * @throws \Exception
	 */
	public function __construct( array $options ) {
		if ( isset( $options['caBundlePath'] ) ) {
			$this->caBundlePath = $options['caBundlePath'];
			if ( !file_exists( $this->caBundlePath ) ) {
				throw new InvalidArgumentException( "Cannot find CA bundle: " . $this->caBundlePath );
			}
		}
		static $opts = [
			'connTimeout', 'maxConnTimeout', 'reqTimeout', 'maxReqTimeout',
			'usePipelining', 'maxConnsPerHost', 'proxy', 'userAgent', 'logger',
			'localProxy', 'localVirtualHosts', 'headers', 'telemetry'
		];
		foreach ( $opts as $key ) {
			if ( isset( $options[$key] ) ) {
				$this->$key = $options[$key];
			}
		}
		$this->logger ??= new NullLogger;
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
	 * 		[ $rcode, $rdesc, $rhdrs, $rbody, $rerr ] = $http->run( $req );
	 * @endcode
	 * @param array $req HTTP request array
	 * @param array $opts
	 *   - connTimeout     : connection timeout per request (seconds)
	 *   - reqTimeout      : post-connection timeout per request (seconds)
	 *   - usePipelining   : whether to use HTTP pipelining if possible (for all hosts)
	 *   - maxConnsPerHost : maximum number of concurrent connections (per host)
	 *   - httpVersion     : One of 'v1.0', 'v1.1', 'v2' or 'v2.0'. Leave empty to use
	 *                       PHP/curl's default
	 * @param string $caller The method making this request, for attribution in logs
	 * @return array Response array for request
	 */
	public function run( array $req, array $opts = [], string $caller = __METHOD__ ) {
		return $this->runMulti( [ $req ], $opts, $caller )[0]['response'];
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
	 *        [ $rcode, $rdesc, $rhdrs, $rbody, $rerr ] = $req['response'];
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
	 *   - httpVersion     : One of 'v1.0', 'v1.1', 'v2' or 'v2.0'. Leave empty to use
	 *                       PHP/curl's default
	 * @param string $caller The method making these requests, for attribution in logs
	 * @return array[] $reqs With response array populated for each
	 * @throws \Exception
	 */
	public function runMulti( array $reqs, array $opts = [], string $caller = __METHOD__ ) {
		$this->normalizeRequests( $reqs );
		$opts += [ 'connTimeout' => $this->connTimeout, 'reqTimeout' => $this->reqTimeout ];

		if ( $this->maxConnTimeout && $opts['connTimeout'] > $this->maxConnTimeout ) {
			$opts['connTimeout'] = $this->maxConnTimeout;
		}
		if ( $this->maxReqTimeout && $opts['reqTimeout'] > $this->maxReqTimeout ) {
			$opts['reqTimeout'] = $this->maxReqTimeout;
		}

		if ( $this->isCurlEnabled() ) {
			switch ( $opts['httpVersion'] ?? null ) {
				case 'v1.0':
					$opts['httpVersion'] = CURL_HTTP_VERSION_1_0;
					break;
				case 'v1.1':
					$opts['httpVersion'] = CURL_HTTP_VERSION_1_1;
					break;
				case 'v2':
				case 'v2.0':
					$opts['httpVersion'] = CURL_HTTP_VERSION_2_0;
					break;
				default:
					$opts['httpVersion'] = CURL_HTTP_VERSION_NONE;
			}
			return $this->runMultiCurl( $reqs, $opts, $caller );
		} else {
			# TODO: Add handling for httpVersion option
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
	 *   - httpVersion:    : HTTP version to use
	 * @phan-param array{connTimeout?:int,reqTimeout?:int,usePipelining?:bool,maxConnsPerHost?:int} $opts
	 * @param string $caller The method making these requests, for attribution in logs
	 * @return array $reqs With response array populated for each
	 * @throws \Exception
	 * @suppress PhanTypeInvalidDimOffset
	 */
	private function runMultiCurl( array $reqs, array $opts, string $caller = __METHOD__ ) {
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
			$mrc = curl_multi_exec( $chm, $active );

			if ( $mrc !== CURLM_OK ) {
				$error = curl_multi_strerror( $mrc );
				$this->logger->error( 'curl_multi_exec() failed: {error}', [
					'error' => $error,
					'exception' => new RuntimeException(),
					'method' => $caller,
				] );
				break;
			}

			// Wait (if possible) for available work...
			if ( $active > 0 && curl_multi_select( $chm, $selectTimeout ) === -1 ) {
				$errno = curl_multi_errno( $chm );
				$error = curl_multi_strerror( $errno );
				$this->logger->error( 'curl_multi_select() failed: {error}', [
					'error' => $error,
					'exception' => new RuntimeException(),
					'method' => $caller,
				] );
			}
		} while ( $active > 0 );

		$queuedMessages = null;
		do {
			$info = curl_multi_info_read( $chm, $queuedMessages );
			if ( $info !== false && $info['msg'] === CURLMSG_DONE ) {
				// Note: cast to integer even works on PHP 8.0+ despite the
				// handle being an object not a resource, because CurlHandle
				// has a backwards-compatible cast_object handler.
				$infos[(int)$info['handle']] = $info;
			}
		} while ( $queuedMessages > 0 );

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
					$this->logger->error( 'Error fetching URL "{url}": {error}', [
						'url' => $req['url'],
						'error' => $req['response']['error'],
						'exception' => new RuntimeException(),
						'method' => $caller,
					] );
				} else {
					$this->logger->debug(
						"HTTP complete: {method} {url} code={response_code} size={size} " .
						"total={total_time} connect={connect_time}",
						[
							'method' => $req['method'],
							'url' => $req['url'],
							'response_code' => $req['response']['code'],
							'size' => curl_getinfo( $ch, CURLINFO_SIZE_DOWNLOAD ),
							'total_time' => $this->getCurlTime(
								$ch, CURLINFO_TOTAL_TIME, 'CURLINFO_TOTAL_TIME_T'
							),
							'connect_time' => $this->getCurlTime(
								$ch, CURLINFO_CONNECT_TIME, 'CURLINFO_CONNECT_TIME_T'
							),
						]
					);
				}
			} else {
				$req['response']['error'] = "(curl error: no status set)";
			}

			// For convenience with array destructuring
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
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-param array{url:string,proxy?:?string,query:mixed,method:string,body:string|resource,headers:array<string,string>,stream?:resource,flags:array} $req
	 * @param array $opts
	 *   - connTimeout : default connection timeout
	 *   - reqTimeout : default request timeout
	 *   - httpVersion: default HTTP version
	 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintReturn
	 * @return resource|object
	 * @throws \Exception
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
		curl_setopt( $ch, CURLOPT_HTTP_VERSION, $opts['httpVersion'] ?? CURL_HTTP_VERSION_NONE );

		if ( $req['method'] === 'PUT' ) {
			curl_setopt( $ch, CURLOPT_PUT, 1 );
			// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.is_resource
			if ( is_resource( $req['body'] ) ) {
				curl_setopt( $ch, CURLOPT_INFILE, $req['body'] );
				if ( isset( $req['headers']['content-length'] ) ) {
					curl_setopt( $ch, CURLOPT_INFILESIZE, $req['headers']['content-length'] );
				} elseif ( isset( $req['headers']['transfer-encoding'] ) &&
					$req['headers']['transfer-encoding'] === 'chunks'
				) {
					curl_setopt( $ch, CURLOPT_UPLOAD, true );
				} else {
					throw new InvalidArgumentException( "Missing 'Content-Length' or 'Transfer-Encoding' header." );
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
				static function ( $ch, $fd, $length ) {
					return (string)fread( $fd, $length );
				}
			);
		} elseif ( $req['method'] === 'POST' ) {
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $req['body'] );
		} else {
			// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.is_resource
			if ( is_resource( $req['body'] ) || $req['body'] !== '' ) {
				throw new InvalidArgumentException( "HTTP body specified for a non PUT/POST request." );
			}
			$req['headers']['content-length'] = 0;
		}

		if ( !isset( $req['headers']['user-agent'] ) ) {
			$req['headers']['user-agent'] = $this->userAgent;
		}

		$headers = [];
		foreach ( $req['headers'] as $name => $value ) {
			if ( strpos( $name, ':' ) !== false ) {
				throw new InvalidArgumentException( "Header name must not contain colon-space." );
			}
			$headers[] = $name . ': ' . trim( $value );
		}
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

		curl_setopt( $ch, CURLOPT_HEADERFUNCTION,
			static function ( $ch, $header ) use ( &$req ) {
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
				[ $name, $value ] = explode( ":", $header, 2 );
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
			static function ( $ch, $data ) use ( &$req, $hasOutputStream ) {
				if ( $hasOutputStream ) {
					// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
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
	 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintReturn
	 * @return resource|object
	 * @throws \Exception
	 */
	protected function getCurlMulti( array $opts ) {
		if ( !$this->cmh ) {
			$cmh = curl_multi_init();
			// Limit the size of the idle connection cache such that consecutive parallel
			// request batches to the same host can avoid having to keep making connections
			curl_multi_setopt( $cmh, CURLMOPT_MAXCONNECTS, (int)$this->maxConnsPerHost );
			$this->cmh = $cmh;
		}

		$curlVersion = curl_version()['version'];

		// CURLMOPT_MAX_HOST_CONNECTIONS is available since PHP 7.0.7 and cURL 7.30.0
		if ( version_compare( $curlVersion, '7.30.0', '>=' ) ) {
			// Limit the number of in-flight requests for any given host
			$maxHostConns = $opts['maxConnsPerHost'] ?? $this->maxConnsPerHost;
			curl_multi_setopt( $this->cmh, CURLMOPT_MAX_HOST_CONNECTIONS, (int)$maxHostConns );
		}

		if ( $opts['usePipelining'] ?? $this->usePipelining ) {
			if ( version_compare( $curlVersion, '7.43', '<' ) ) {
				// The option is a boolean
				$pipelining = 1;
			} elseif ( version_compare( $curlVersion, '7.62', '<' ) ) {
				// The option is a bitfield and HTTP/1.x pipelining is supported
				$pipelining = CURLPIPE_HTTP1 | CURLPIPE_MULTIPLEX;
			} else {
				// The option is a bitfield but HTTP/1.x pipelining has been removed
				$pipelining = CURLPIPE_MULTIPLEX;
			}
			// Suppress deprecation, we know already (T264735)
			// phpcs:ignore Generic.PHP.NoSilencedErrors
			@curl_multi_setopt( $this->cmh, CURLMOPT_PIPELINING, $pipelining );
		}

		return $this->cmh;
	}

	/**
	 * Get a time in seconds, formatted with microsecond resolution, or fall back to second
	 * resolution on PHP 7.2
	 *
	 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
	 * @param resource|object $ch
	 * @param int $oldOption
	 * @param string $newConstName
	 * @return string
	 */
	private function getCurlTime( $ch, $oldOption, $newConstName ): string {
		if ( defined( $newConstName ) ) {
			return sprintf( "%.6F", curl_getinfo( $ch, constant( $newConstName ) ) / 1e6 );
		} else {
			return (string)curl_getinfo( $ch, $oldOption );
		}
	}

	/**
	 * Execute a set of HTTP(S) requests sequentially.
	 *
	 * @see MultiHttpClient::runMulti()
	 * @todo Remove dependency on MediaWikiServices: rewrite using Guzzle T202352
	 * @param array $reqs Map of HTTP request arrays
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-param array<int,array{url:string,query:array,method:string,body:string,headers:array<string,string>,proxy?:?string}> $reqs
	 * @param array $opts
	 *   - connTimeout     : connection timeout per request (seconds)
	 *   - reqTimeout      : post-connection timeout per request (seconds)
	 * @phan-param array{connTimeout:int,reqTimeout:int} $opts
	 * @return array $reqs With response array populated for each
	 * @throws \Exception
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
			$httpRequest->setLogger( $this->logger );
			foreach ( $req['headers'] as $header => $value ) {
				$httpRequest->setHeader( $header, $value );
			}
			$sv = $httpRequest->execute()->getStatusValue();

			$respHeaders = array_map(
				static function ( $v ) {
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
	 * Normalize headers array
	 * @param array $headers
	 * @return array
	 */
	private function normalizeHeaders( array $headers ): array {
		$normalized = [];
		foreach ( $headers as $name => $value ) {
			$normalized[strtolower( $name )] = $value;
		}
		return $normalized;
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
				throw new InvalidArgumentException( "Request has no 'method' field set." );
			} elseif ( !isset( $req['url'] ) ) {
				throw new InvalidArgumentException( "Request has no 'url' field set." );
			}
			if ( $this->localProxy !== false && $this->isLocalURL( $req['url'] ) ) {
				$this->useReverseProxy( $req, $this->localProxy );
			}
			$req['query'] ??= [];
			$req['headers'] = $this->normalizeHeaders(
				array_merge(
					$this->headers,
					$this->telemetry ? $this->telemetry->getRequestHeaders() : [],
					$req['headers'] ?? []
				)
			);

			if ( !isset( $req['body'] ) ) {
				$req['body'] = '';
				$req['headers']['content-length'] = 0;
			}
			// Redact some headers we know to have tokens before logging them
			$logHeaders = $req['headers'];
			foreach ( $logHeaders as $header => $value ) {
				if ( preg_match( self::SENSITIVE_HEADERS, $header ) === 1 ) {
					$logHeaders[$header] = '[redacted]';
				}
			}
			$this->logger->debug( "HTTP start: {method} {url}",
				[
					'method' => $req['method'],
					'url' => $req['url'],
					'headers' => $logHeaders,
				]
			);
			$req['flags'] ??= [];
		}
	}

	private function useReverseProxy( array &$req, string $proxy ) {
		$parsedProxy = parse_url( $proxy );
		if ( $parsedProxy === false ) {
			throw new InvalidArgumentException( "Invalid reverseProxy configured: $proxy" );
		}
		$parsedUrl = parse_url( $req['url'] );
		if ( $parsedUrl === false ) {
			throw new InvalidArgumentException( "Invalid url specified: {$req['url']}" );
		}
		// Set the current host in the Host header
		// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset
		$req['headers']['Host'] = $parsedUrl['host'];
		// Replace scheme, host and port in the request
		// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset
		$parsedUrl['scheme'] = $parsedProxy['scheme'];
		// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset
		$parsedUrl['host'] = $parsedProxy['host'];
		if ( isset( $parsedProxy['port'] ) ) {
			$parsedUrl['port'] = $parsedProxy['port'];
		} else {
			unset( $parsedUrl['port'] );
		}
		$req['url'] = self::assembleUrl( $parsedUrl );
		// Explicitly disable use of another proxy by setting to false,
		// since null will fallback to $this->proxy
		$req['proxy'] = false;
	}

	/**
	 * This is derived from MediaWiki\Utils\UrlUtils::assemble but changed to work
	 * with parse_url's result so the delimiter is hardcoded.
	 *
	 * The basic structure used:
	 * [scheme://][[user][:pass]@][host][:port][path][?query][#fragment]
	 *
	 * @param array $urlParts URL parts, as output from parse_url()
	 * @return string URL assembled from its component parts
	 */
	private static function assembleUrl( array $urlParts ): string {
		$result = isset( $urlParts['scheme'] ) ? $urlParts['scheme'] . '://' : '';

		if ( isset( $urlParts['host'] ) ) {
			if ( isset( $urlParts['user'] ) ) {
				$result .= $urlParts['user'];
				if ( isset( $urlParts['pass'] ) ) {
					$result .= ':' . $urlParts['pass'];
				}
				$result .= '@';
			}

			$result .= $urlParts['host'];

			if ( isset( $urlParts['port'] ) ) {
				$result .= ':' . $urlParts['port'];
			}
		}

		if ( isset( $urlParts['path'] ) ) {
			$result .= $urlParts['path'];
		}

		if ( isset( $urlParts['query'] ) && $urlParts['query'] !== '' ) {
			$result .= '?' . $urlParts['query'];
		}

		if ( isset( $urlParts['fragment'] ) ) {
			$result .= '#' . $urlParts['fragment'];
		}

		return $result;
	}

	/**
	 * Check if the URL can be served by localhost
	 *
	 * @note this is mostly a copy of MWHttpRequest::isLocalURL()
	 * @param string $url Full url to check
	 * @return bool
	 */
	private function isLocalURL( $url ) {
		if ( !$this->localVirtualHosts ) {
			// Shortcut
			return false;
		}

		// Extract host part
		$matches = [];
		if ( preg_match( '!^https?://([\w.-]+)[/:].*$!', $url, $matches ) ) {
			$host = $matches[1];
			// Split up dotwise
			$domainParts = explode( '.', $host );
			// Check if this domain or any superdomain is listed as a local virtual host
			$domainParts = array_reverse( $domainParts );

			$domain = '';
			$countParts = count( $domainParts );
			for ( $i = 0; $i < $countParts; $i++ ) {
				$domainPart = $domainParts[$i];
				if ( $i == 0 ) {
					$domain = $domainPart;
				} else {
					$domain = $domainPart . '.' . $domain;
				}

				if ( in_array( $domain, $this->localVirtualHosts ) ) {
					return true;
				}
			}
		}

		return false;
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
		// Minimum 10us
		if ( $selectTimeout < 10e-6 ) {
			$selectTimeout = 10e-6;
		}
		return $selectTimeout;
	}

	/**
	 * Register a logger
	 */
	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	public function __destruct() {
		if ( $this->cmh ) {
			curl_multi_close( $this->cmh );
			$this->cmh = null;
		}
	}

}
/** @deprecated class alias since 1.43 */
class_alias( MultiHttpClient::class, 'MultiHttpClient' );
