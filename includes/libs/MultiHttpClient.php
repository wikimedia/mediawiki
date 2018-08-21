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
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;

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
 *   - sink     : resource to receive the HTTP response body (preferred over stream)
 *                @since 1.33
 *   - stream   : resource to stream the HTTP response body to
 *                @deprecated since 1.33, use sink instead
 *   - proxy    : HTTP proxy to use
 *   - flags    : map of boolean flags which supports:
 *                  - relayResponseHeaders : write out header via header()
 * Request maps can use integer index 0 instead of 'method' and 1 instead of 'url'.
 *
 * @since 1.23
 */
class MultiHttpClient implements LoggerAwareInterface {
	/** @var float connection timeout in seconds, zero to wait indefinitely*/
	protected $connTimeout = 10;
	/** @var float request timeout in seconds, zero to wait indefinitely*/
	protected $reqTimeout = 300;
	/** @var string|null proxy */
	protected $proxy;
	/** @var int CURLMOPT_PIPELINING value, only effective if curl is available */
	protected $pipeliningMode = 0;
	/** @var int CURLMOPT_MAXCONNECTS value, only effective if curl is available */
	protected $maxConnsPerHost = 50;
	/** @var string */
	protected $userAgent = 'wikimedia/multi-http-client v1.0';
	/** @var LoggerInterface */
	protected $logger;
	/** @var string|null SSL certificates path */
	protected $caBundlePath;

	/**
	 * @param array $options
	 *   - connTimeout     : default connection timeout (seconds)
	 *   - reqTimeout      : default request timeout (seconds)
	 *   - proxy           : HTTP proxy to use
	 *   - pipeliningMode  : whether to use HTTP pipelining/multiplexing if possible (for all
	 *                       hosts).  The exact behavior is dependent on curl version.
	 *   - maxConnsPerHost : maximum number of concurrent connections (per host)
	 *   - userAgent       : The User-Agent header value to send
	 *   - logger          : a \Psr\Log\LoggerInterface instance for debug logging
	 *   - caBundlePath    : path to specific Certificate Authority bundle (if any)
	 * @throws Exception
	 *
	 * usePipelining is an alias for pipelining mode, retained for backward compatibility.
	 * If both usePipelining and pipeliningMode are specified, pipeliningMode wins.
	 */
	public function __construct( array $options ) {
		if ( isset( $options['caBundlePath'] ) ) {
			$this->caBundlePath = $options['caBundlePath'];
			if ( !file_exists( $this->caBundlePath ) ) {
				throw new Exception( "Cannot find CA bundle: {$this->caBundlePath}" );
			}
		}

		// Backward compatibility.  Defers to newer option naming if both are specified.
		if ( isset( $options['usePipelining'] ) ) {
			$this->pipeliningMode = $options['usePipelining'];
		}

		static $opts = [
			'connTimeout', 'reqTimeout', 'proxy', 'pipeliningMode', 'maxConnsPerHost',
			'userAgent', 'logger'
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
	 *   - body    : HTTP response body
	 *   - error   : Any error string
	 * The map also stores integer-indexed copies of these values. This lets callers do:
	 * @code
	 *        list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $http->run( $req );
	 * @endcode
	 * @param array $req HTTP request array
	 * @param array $opts
	 *   - connTimeout    : connection timeout per request (seconds)
	 *   - reqTimeout     : post-connection timeout per request (seconds)
	 *   - handler        : optional custom handler
	 *                      See http://docs.guzzlephp.org/en/stable/handlers-and-middleware.html
	 * @return array Response array for request
	 * @throws Exception
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
	 *   - body    : HTTP response body
	 *   - error   : Any error string
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
	 *   - pipeliningMode  : whether to use HTTP pipelining/multiplexing if possible (for all
	 *                       hosts). The exact behavior is dependent on curl version.
	 *   - maxConnsPerHost : maximum number of concurrent connections (per host)
	 *   - handler         : optional custom handler.
	 *                       See http://docs.guzzlephp.org/en/stable/handlers-and-middleware.html
	 * @return array $reqs With response array populated for each
	 * @throws Exception
	 *
	 * usePipelining is an alias for pipelining mode, retained for backward compatibility.
	 * If both usePipelining and pipeliningMode are specified, pipeliningMode wins.
	 */
	public function runMulti( array $reqs, array $opts = [] ) {
		$this->normalizeRequests( $reqs );
		return $this->runMultiGuzzle( $reqs, $opts );
	}

	/**
	 * Determines if the curl extension is available
	 *
	 * @return bool true if curl is available, false otherwise.
	 */
	protected function isCurlEnabled() {
		return extension_loaded( 'curl' );
	}

	/**
	 * Execute a set of HTTP(S) requests concurrently
	 *
	 * @see MultiHttpClient::runMulti()
	 *
	 * @param array $reqs Map of HTTP request arrays
	 * @param array $opts
	 * @return array $reqs With response array populated for each
	 * @throws Exception
	 */
	private function runMultiGuzzle( array $reqs, array $opts = [] ) {
		$guzzleOptions = [
			'timeout' => $opts['reqTimeout'] ?? $this->reqTimeout,
			'connect_timeout' => $opts['connTimeout'] ?? $this->connTimeout,
			'allow_redirects' => [
				'max' => 4,
			],
		];

		if ( !is_null( $this->caBundlePath ) ) {
			$guzzleOptions['verify'] = $this->caBundlePath;
		}

		// Include curl-specific option section only if curl is available.
		// Our defaults may differ from curl's defaults, depending on curl version.
		if ( $this->isCurlEnabled() ) {
			// Backward compatibility
			$optsPipeliningMode = $opts['pipeliningMode'] ?? ( $opts['usePipelining'] ?? null );

			// Per-request options override class-level options
			$pipeliningMode = $optsPipeliningMode ?? $this->pipeliningMode;
			$maxConnsPerHost = $opts['maxConnsPerHost'] ?? $this->maxConnsPerHost;

			$guzzleOptions['curl'][CURLMOPT_PIPELINING] = (int)$pipeliningMode;
			$guzzleOptions['curl'][CURLMOPT_MAXCONNECTS] = (int)$maxConnsPerHost;
		}

		if ( isset( $opts['handler'] ) ) {
			$guzzleOptions['handler'] = $opts['handler'];
		}

		$guzzleOptions['headers']['user-agent'] = $this->userAgent;

		$client = new Client( $guzzleOptions );
		$promises = [];
		foreach ( $reqs as $index => $req ) {
			$reqOptions = [
				'proxy' => $req['proxy'] ?? $this->proxy,
			];

			if ( $req['method'] == 'POST' ) {
				$reqOptions['form_params'] = $req['body'];

				// Suppress 'Expect: 100-continue' header, as some servers
				// will reject it with a 417 and Curl won't auto retry
				// with HTTP 1.0 fallback
				$reqOptions['expect'] = false;
			}

			if ( isset( $req['headers']['user-agent'] ) ) {
				$reqOptions['headers']['user-agent'] = $req['headers']['user-agent'];
			}

			// Backward compatibility for pre-Guzzle naming
			if ( isset( $req['sink'] ) ) {
				$reqOptions['sink'] = $req['sink'];
			} elseif ( isset( $req['stream'] ) ) {
				$reqOptions['sink'] = $req['stream'];
			}

			if ( !empty( $req['flags']['relayResponseHeaders'] ) ) {
				$reqOptions['on_headers'] = function ( ResponseInterface $response ) {
					foreach ( $response->getHeaders() as $name => $values ) {
						foreach ( $values as $value ) {
							header( $name . ': ' . $value . "\r\n" );
						}
					}
				};
			}

			$url = $req['url'];
			$query = http_build_query( $req['query'], '', '&', PHP_QUERY_RFC3986 );
			if ( $query != '' ) {
				$url .= strpos( $req['url'], '?' ) === false ? "?$query" : "&$query";
			}
			$promises[$index] = $client->requestAsync( $req['method'], $url, $reqOptions );
		}

		$results = GuzzleHttp\Promise\settle( $promises )->wait();

		foreach ( $results as $index => $result ) {
			if ( $result['state'] === 'fulfilled' ) {
				$this->guzzleHandleSuccess( $reqs[$index], $result['value'] );
			} elseif ( $result['state'] === 'rejected' ) {
				$this->guzzleHandleFailure( $reqs[$index], $result['reason'] );
			} else {
				// This should never happen, and exists only in case of changes to guzzle
				throw new UnexpectedValueException(
					"Unrecognized result state: {$result['state']}" );
			}
		}

		foreach ( $reqs as &$req ) {
			$req['response'][0] = $req['response']['code'];
			$req['response'][1] = $req['response']['reason'];
			$req['response'][2] = $req['response']['headers'];
			$req['response'][3] = $req['response']['body'];
			$req['response'][4] = $req['response']['error'];
		}

		return $reqs;
	}

	/**
	 * Called for successful requests
	 *
	 * @param array $req the original request
	 * @param ResponseInterface $response
	 */
	private function guzzleHandleSuccess( &$req, $response ) {
		$req['response'] = [
			'code' => $response->getStatusCode(),
			'reason' => $response->getReasonPhrase(),
			'headers' => $this->parseHeaders( $response->getHeaders() ),
			'body' => isset( $req['sink'] ) ? '' : $response->getBody()->getContents(),
			'error' => '',
		];
	}

	/**
	 * Called for failed requests
	 *
	 * @param array $req the original request
	 * @param Exception $reason
	 */
	private function guzzleHandleFailure( &$req, $reason ) {
		$req['response'] = [
			'code' => $reason->getCode(),
			'reason' => '',
			'headers' => [],
			'body' => '',
			'error' => $reason->getMessage(),
		];

		if (
			$reason instanceof GuzzleHttp\Exception\RequestException &&
			$reason->hasResponse()
		) {
			$response = $reason->getResponse();
			if ( $response ) {
				$req['response']['reason'] = $response->getReasonPhrase();
				$req['response']['headers'] = $this->parseHeaders( $response->getHeaders() );
				$req['response']['body'] = $response->getBody()->getContents();
			}
		}

		$this->logger->warning( "Error fetching URL \"{$req['url']}\": " .
			$req['response']['error'] );
	}

	/**
	 * Parses response headers.
	 *
	 * @param string[][] $guzzleHeaders
	 * @return array
	 */
	private function parseHeaders( $guzzleHeaders ) {
		$headers = [];
		foreach ( $guzzleHeaders as $name => $values ) {
			$headers[strtolower( $name )] = implode( ', ', $values );
		}
		return $headers;
	}

	/**
	 * Normalize request information
	 *
	 * @param array $reqs the requests to normalize
	 * @throws Exception
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
	 * Register a logger
	 *
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}
}
