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
 */

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * MWHttpRequest implemented using the Guzzle library
 *
 * Differences from the CurlHttpRequest implementation:
 *   1) a new 'sink' option is available as an alternative to callbacks.  See:
 *        http://docs.guzzlephp.org/en/stable/request-options.html#sink)
 *      The 'callback' option remains available as well.  If both 'sink' and 'callback' are
 *      specified, 'sink' is used.
 *   2) callers may set a custom handler via the 'handler' option.
 *      If this is not set, Guzzle will use curl (if available) or PHP streams (otherwise)
 *   3) setting either sslVerifyHost or sslVerifyCert will enable both.  Guzzle does not allow
 *      them to be set separately.
 *
 * @since 1.33
 */
class GuzzleHttpRequest extends MWHttpRequest {
	const SUPPORTS_FILE_POSTS = true;

	protected $handler = null;
	protected $sink = null;
	/** @var array */
	protected $guzzleOptions = [ 'http_errors' => false ];

	/**
	 * @param string $url Url to use. If protocol-relative, will be expanded to an http:// URL
	 * @param array $options (optional) extra params to pass (see HttpRequestFactory::create())
	 * @param string $caller The method making this request, for profiling
	 * @param Profiler|null $profiler An instance of the profiler for profiling, or null
	 * @throws Exception
	 */
	public function __construct(
		$url, array $options = [], $caller = __METHOD__, Profiler $profiler = null
	) {
		parent::__construct( $url, $options, $caller, $profiler );

		if ( isset( $options['handler'] ) ) {
			$this->handler = $options['handler'];
		}
		if ( isset( $options['sink'] ) ) {
			$this->sink = $options['sink'];
		}
	}

	/**
	 * Set a read callback to accept data read from the HTTP request.
	 * By default, data is appended to an internal buffer which can be
	 * retrieved through $req->getContent().
	 *
	 * To handle data as it comes in -- especially for large files that
	 * would not fit in memory -- you can instead set your own callback,
	 * in the form function($resource, $buffer) where the first parameter
	 * is the low-level resource being read (implementation specific),
	 * and the second parameter is the data buffer.
	 *
	 * You MUST return the number of bytes handled in the buffer; if fewer
	 * bytes are reported handled than were passed to you, the HTTP fetch
	 * will be aborted.
	 *
	 * This function overrides any 'sink' or 'callback' constructor option.
	 *
	 * @param callable|null $callback
	 * @throws InvalidArgumentException
	 */
	public function setCallback( $callback ) {
		$this->sink = null;
		$this->doSetCallback( $callback );
	}

	/**
	 * Worker function for setting callbacks.  Calls can originate both internally and externally
	 * via setCallback).  Defaults to the internal read callback if $callback is null.
	 *
	 * If a sink is already specified, this does nothing.  This causes the 'sink' constructor
	 * option to override the 'callback' constructor option.
	 *
	 * @param callable|null $callback
	 * @throws InvalidArgumentException
	 */
	protected function doSetCallback( $callback ) {
		if ( !$this->sink ) {
			parent::doSetCallback( $callback );
			$this->sink = new MWCallbackStream( $this->callback );
		}
	}

	/**
	 * @see MWHttpRequest::execute
	 *
	 * @return Status
	 */
	public function execute() {
		$this->prepare();

		if ( !$this->status->isOK() ) {
			return Status::wrap( $this->status ); // TODO B/C; move this to callers
		}

		if ( $this->proxy ) {
			$this->guzzleOptions['proxy'] = $this->proxy;
		}

		$this->guzzleOptions['timeout'] = $this->timeout;
		$this->guzzleOptions['connect_timeout'] = $this->connectTimeout;
		$this->guzzleOptions['version'] = '1.1';

		if ( !$this->followRedirects ) {
			$this->guzzleOptions['allow_redirects'] = false;
		} else {
			$this->guzzleOptions['allow_redirects'] = [
				'max' => $this->maxRedirects
			];
		}

		if ( $this->method == 'POST' ) {
			$postData = $this->postData;
			if ( is_array( $postData ) ) {
				$this->guzzleOptions['form_params'] = $postData;
			} else {
				$this->guzzleOptions['body'] = $postData;
				// mimic CURLOPT_POST option
				if ( !isset( $this->reqHeaders['Content-Type'] ) ) {
					$this->reqHeaders['Content-Type'] = 'application/x-www-form-urlencoded';
				}
			}

			// Suppress 'Expect: 100-continue' header, as some servers
			// will reject it with a 417 and Curl won't auto retry
			// with HTTP 1.0 fallback
			$this->guzzleOptions['expect'] = false;
		}

		$this->guzzleOptions['headers'] = $this->reqHeaders;

		if ( $this->handler ) {
			$this->guzzleOptions['handler'] = $this->handler;
		}

		if ( $this->sink ) {
			$this->guzzleOptions['sink'] = $this->sink;
		}

		if ( $this->caInfo ) {
			$this->guzzleOptions['verify'] = $this->caInfo;
		} elseif ( !$this->sslVerifyHost && !$this->sslVerifyCert ) {
			$this->guzzleOptions['verify'] = false;
		}

		try {
			$client = new Client( $this->guzzleOptions );
			$request = new Request( $this->method, $this->url );
			$response = $client->send( $request );
			$this->headerList = $response->getHeaders();

			$this->respVersion = $response->getProtocolVersion();
			$this->respStatus = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
		} catch ( GuzzleHttp\Exception\ConnectException $e ) {
			// ConnectException is thrown for several reasons besides generic "timeout":
			//   Connection refused
			//   couldn't connect to host
			//   connection attempt failed
			//   Could not resolve IPv4 address for host
			//   Could not resolve IPv6 address for host
			if ( $this->usingCurl() ) {
				$handlerContext = $e->getHandlerContext();
				if ( $handlerContext['errno'] == CURLE_OPERATION_TIMEOUTED ) {
					$this->status->fatal( 'http-timed-out', $this->url );
				} else {
					$this->status->fatal( 'http-curl-error', $handlerContext['error'] );
				}
			} else {
				$this->status->fatal( 'http-request-error' );
			}
		} catch ( GuzzleHttp\Exception\RequestException $e ) {
			if ( $this->usingCurl() ) {
				$handlerContext = $e->getHandlerContext();
				$this->status->fatal( 'http-curl-error', $handlerContext['error'] );
			} else {
				// Non-ideal, but the only way to identify connection timeout vs other conditions
				$needle = 'Connection timed out';
				if ( strpos( $e->getMessage(), $needle ) !== false ) {
					$this->status->fatal( 'http-timed-out', $this->url );
				} else {
					$this->status->fatal( 'http-request-error' );
				}
			}
		} catch ( GuzzleHttp\Exception\GuzzleException $e ) {
			$this->status->fatal( 'http-internal-error' );
		}

		if ( $this->profiler ) {
			$profileSection = $this->profiler->scopedProfileIn(
				__METHOD__ . '-' . $this->profileName
			);
		}

		if ( $this->profiler ) {
			$this->profiler->scopedProfileOut( $profileSection );
		}

		$this->parseHeader();
		$this->setStatus();

		return Status::wrap( $this->status ); // TODO B/C; move this to callers
	}

	protected function prepare() {
		$this->doSetCallback( $this->callback );
		parent::prepare();
	}

	/**
	 * @return bool
	 */
	protected function usingCurl() {
		return ( $this->handler && is_a( $this->handler, 'GuzzleHttp\Handler\CurlHandler' ) ) ||
			( !$this->handler && extension_loaded( 'curl' ) );
	}

	/**
	 * Guzzle provides headers as an array.  Reprocess to match our expectations.  Guzzle will
	 * have already parsed and removed the status line (in EasyHandle::createResponse).
	 */
	protected function parseHeader() {
		// Failure without (valid) headers gets a response status of zero
		if ( !$this->status->isOK() ) {
			$this->respStatus = '0 Error';
		}

		foreach ( $this->headerList as $name => $values ) {
			$this->respHeaders[strtolower( $name )] = $values;
		}

		$this->parseCookies();
	}
}
