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

use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * This wrapper class will call out to curl (if available) or fallback
 * to regular PHP if necessary for handling internal HTTP requests.
 *
 * Renamed from HttpRequest to MWHttpRequest to avoid conflict with
 * PHP's HTTP extension.
 */
abstract class MWHttpRequest implements LoggerAwareInterface {
	public const SUPPORTS_FILE_POSTS = false;

	/**
	 * @var int|string
	 */
	protected $timeout = 'default';

	protected $content;
	protected $headersOnly = null;
	protected $postData = null;
	protected $proxy = null;
	protected $noProxy = false;
	protected $sslVerifyHost = true;
	protected $sslVerifyCert = true;
	protected $caInfo = null;
	protected $method = "GET";
	/** @var array */
	protected $reqHeaders = [];
	protected $url;
	protected $parsedUrl;
	/** @var callable */
	protected $callback;
	protected $maxRedirects = 5;
	protected $followRedirects = false;
	protected $connectTimeout;

	/**
	 * @var CookieJar
	 */
	protected $cookieJar;

	protected $headerList = [];
	protected $respVersion = "0.9";
	protected $respStatus = "200 Ok";
	/** @var string[][] */
	protected $respHeaders = [];

	/** @var StatusValue */
	protected $status;

	/**
	 * @var Profiler
	 */
	protected $profiler;

	/**
	 * @var string
	 */
	protected $profileName;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @param string $url Url to use. If protocol-relative, will be expanded to an http:// URL
	 * @param array $options (optional) extra params to pass (see HttpRequestFactory::create())
	 * @codingStandardsIgnoreStart
	 * @phan-param array{timeout?:int|string,connectTimeout?:int|string,postData?:array,proxy?:string,noProxy?:bool,sslVerifyHost?:bool,sslVerifyCert?:bool,caInfo?:string,maxRedirects?:int,followRedirects?:bool,userAgent?:string,logger?:LoggerInterface,username?:string,password?:string,originalRequest?:WebRequest|array{ip:string,userAgent:string},method?:string} $options
	 * @codingStandardsIgnoreEnd
	 * @param string $caller The method making this request, for profiling
	 * @param Profiler|null $profiler An instance of the profiler for profiling, or null
	 * @throws Exception
	 */
	public function __construct(
		$url, array $options = [], $caller = __METHOD__, Profiler $profiler = null
	) {
		$this->url = wfExpandUrl( $url, PROTO_HTTP );
		$this->parsedUrl = wfParseUrl( $this->url );

		$this->logger = $options['logger'] ?? new NullLogger();

		if ( !$this->parsedUrl || !self::isValidURI( $this->url ) ) {
			$this->status = StatusValue::newFatal( 'http-invalid-url', $url );
		} else {
			$this->status = StatusValue::newGood( 100 ); // continue
		}

		if ( isset( $options['timeout'] ) && $options['timeout'] != 'default' ) {
			$this->timeout = $options['timeout'];
		} else {
			// The timeout should always be set by HttpRequestFactory, so this
			// should only happen if the class was directly constructed
			wfDeprecated( __METHOD__ . ' without the timeout option', '1.35' );
			global $wgHTTPTimeout;
			$this->timeout = $wgHTTPTimeout;
		}
		if ( isset( $options['connectTimeout'] ) && $options['connectTimeout'] != 'default' ) {
			$this->connectTimeout = $options['connectTimeout'];
		} else {
			// The timeout should always be set by HttpRequestFactory, so this
			// should only happen if the class was directly constructed
			wfDeprecated( __METHOD__ . ' without the connectTimeout option', '1.35' );
			global $wgHTTPConnectTimeout;
			$this->connectTimeout = $wgHTTPConnectTimeout;
		}
		if ( isset( $options['userAgent'] ) ) {
			$this->setUserAgent( $options['userAgent'] );
		}
		if ( isset( $options['username'] ) && isset( $options['password'] ) ) {
			$this->setHeader(
				'Authorization',
				'Basic ' . base64_encode( $options['username'] . ':' . $options['password'] )
			);
		}
		if ( isset( $options['originalRequest'] ) ) {
			$this->setOriginalRequest( $options['originalRequest'] );
		}

		$this->setHeader( 'X-Request-Id', WebRequest::getRequestId() );

		$members = [ "postData", "proxy", "noProxy", "sslVerifyHost", "caInfo",
				"method", "followRedirects", "maxRedirects", "sslVerifyCert", "callback" ];

		foreach ( $members as $o ) {
			if ( isset( $options[$o] ) ) {
				// ensure that MWHttpRequest::method is always
				// uppercased. T38137
				if ( $o == 'method' ) {
					$options[$o] = strtoupper( $options[$o] );
				}
				$this->$o = $options[$o];
			}
		}

		if ( $this->noProxy ) {
			$this->proxy = ''; // noProxy takes precedence
		}

		// Profile based on what's calling us
		$this->profiler = $profiler;
		$this->profileName = $caller;
	}

	/**
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Simple function to test if we can make any sort of requests at all, using
	 * cURL or fopen()
	 * @return bool
	 */
	public static function canMakeRequests() {
		return function_exists( 'curl_init' ) || wfIniGetBool( 'allow_url_fopen' );
	}

	/**
	 * Generate a new request object
	 * @deprecated since 1.34, use HttpRequestFactory instead
	 * @param string $url Url to use
	 * @param array|null $options (optional) extra params to pass (see HttpRequestFactory::create())
	 * @param string $caller The method making this request, for profiling
	 * @throws DomainException
	 * @return MWHttpRequest
	 * @see MWHttpRequest::__construct
	 */
	public static function factory( $url, array $options = null, $caller = __METHOD__ ) {
		if ( $options === null ) {
			$options = [];
		}
		return MediaWikiServices::getInstance()->getHttpRequestFactory()
			->create( $url, $options, $caller );
	}

	/**
	 * Get the body, or content, of the response to the request
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Set the parameters of the request
	 *
	 * @param array $args
	 * @todo overload the args param
	 */
	public function setData( array $args ) {
		$this->postData = $args;
	}

	/**
	 * Take care of setting up the proxy (do nothing if "noProxy" is set)
	 *
	 * @return void
	 */
	protected function proxySetup() {
		// If there is an explicit proxy set and proxies are not disabled, then use it
		if ( $this->proxy && !$this->noProxy ) {
			return;
		}

		// Otherwise, fallback to $wgHTTPProxy if this is not a machine
		// local URL and proxies are not disabled
		if ( self::isLocalURL( $this->url ) || $this->noProxy ) {
			$this->proxy = '';
		} else {
			global $wgHTTPProxy;
			$this->proxy = (string)$wgHTTPProxy;
		}
	}

	/**
	 * Check if the URL can be served by localhost
	 *
	 * @param string $url Full url to check
	 * @return bool
	 */
	private static function isLocalURL( $url ) {
		global $wgCommandLineMode, $wgLocalVirtualHosts;

		if ( $wgCommandLineMode ) {
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

				if ( in_array( $domain, $wgLocalVirtualHosts ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Set the user agent
	 * @param string $UA
	 */
	public function setUserAgent( $UA ) {
		$this->setHeader( 'User-Agent', $UA );
	}

	/**
	 * Set an arbitrary header
	 * @param string $name
	 * @param string $value
	 */
	public function setHeader( $name, $value ) {
		// I feel like I should normalize the case here...
		$this->reqHeaders[$name] = $value;
	}

	/**
	 * Get an array of the headers
	 * @return array
	 */
	protected function getHeaderList() {
		$list = [];

		if ( $this->cookieJar ) {
			$this->reqHeaders['Cookie'] =
				$this->cookieJar->serializeToHttpRequest(
					$this->parsedUrl['path'],
					$this->parsedUrl['host']
				);
		}

		foreach ( $this->reqHeaders as $name => $value ) {
			$list[] = "$name: $value";
		}

		return $list;
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
	 * @param callable|null $callback
	 * @throws InvalidArgumentException
	 */
	public function setCallback( $callback ) {
		$this->doSetCallback( $callback );
	}

	/**
	 * Worker function for setting callbacks.  Calls can originate both internally and externally
	 * via setCallback).  Defaults to the internal read callback if $callback is null.
	 *
	 * @param callable|null $callback
	 * @throws InvalidArgumentException
	 */
	protected function doSetCallback( $callback ) {
		if ( $callback === null ) {
			$callback = [ $this, 'read' ];
		} elseif ( !is_callable( $callback ) ) {
			$this->status->fatal( 'http-internal-error' );
			throw new InvalidArgumentException( __METHOD__ . ': invalid callback' );
		}
		$this->callback = $callback;
	}

	/**
	 * A generic callback to read the body of the response from a remote
	 * server.
	 *
	 * @param resource $fh
	 * @param string $content
	 * @return int
	 * @internal
	 */
	public function read( $fh, $content ) {
		$this->content .= $content;
		return strlen( $content );
	}

	/**
	 * Take care of whatever is necessary to perform the URI request.
	 *
	 * @return Status
	 * @note currently returns Status for B/C
	 */
	public function execute() {
		throw new LogicException( 'children must override this' );
	}

	protected function prepare() {
		$this->content = "";

		if ( strtoupper( $this->method ) == "HEAD" ) {
			$this->headersOnly = true;
		}

		$this->proxySetup(); // set up any proxy as needed

		if ( !$this->callback ) {
			$this->doSetCallback( null );
		}

		if ( !isset( $this->reqHeaders['User-Agent'] ) ) {
			$http = MediaWikiServices::getInstance()->getHttpRequestFactory();
			$this->setUserAgent( $http->getUserAgent() );
		}
	}

	/**
	 * Parses the headers, including the HTTP status code and any
	 * Set-Cookie headers.  This function expects the headers to be
	 * found in an array in the member variable headerList.
	 */
	protected function parseHeader() {
		$lastname = "";

		// Failure without (valid) headers gets a response status of zero
		if ( !$this->status->isOK() ) {
			$this->respStatus = '0 Error';
		}

		foreach ( $this->headerList as $header ) {
			if ( preg_match( "#^HTTP/([0-9.]+) (.*)#", $header, $match ) ) {
				$this->respVersion = $match[1];
				$this->respStatus = $match[2];
			} elseif ( preg_match( "#^[ \t]#", $header ) ) {
				$last = count( $this->respHeaders[$lastname] ) - 1;
				$this->respHeaders[$lastname][$last] .= "\r\n$header";
			} elseif ( preg_match( "#^([^:]*):[\t ]*(.*)#", $header, $match ) ) {
				$this->respHeaders[strtolower( $match[1] )][] = $match[2];
				$lastname = strtolower( $match[1] );
			}
		}

		$this->parseCookies();
	}

	/**
	 * Sets HTTPRequest status member to a fatal value with the error
	 * message if the returned integer value of the status code was
	 * not successful (1-299) or a redirect (300-399).
	 * See RFC2616, section 10, http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
	 * for a list of status codes.
	 */
	protected function setStatus() {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		if ( ( (int)$this->respStatus > 0 && (int)$this->respStatus < 400 ) ) {
			$this->status->setResult( true, (int)$this->respStatus );
		} else {
			list( $code, $message ) = explode( " ", $this->respStatus, 2 );
			$this->status->setResult( false, (int)$this->respStatus );
			$this->status->fatal( "http-bad-status", $code, $message );
		}
	}

	/**
	 * Get the integer value of the HTTP status code (e.g. 200 for "200 Ok")
	 * (see RFC2616, section 10, http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
	 * for a list of status codes.)
	 *
	 * @return int
	 */
	public function getStatus() {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		return (int)$this->respStatus;
	}

	/**
	 * Returns true if the last status code was a redirect.
	 *
	 * @return bool
	 */
	public function isRedirect() {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		$status = (int)$this->respStatus;

		if ( $status >= 300 && $status <= 303 ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns an associative array of response headers after the
	 * request has been executed.  Because some headers
	 * (e.g. Set-Cookie) can appear more than once the, each value of
	 * the associative array is an array of the values given.
	 * Header names are always in lowercase.
	 *
	 * @return array
	 */
	public function getResponseHeaders() {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		return $this->respHeaders;
	}

	/**
	 * Returns the value of the given response header.
	 *
	 * @param string $header case-insensitive
	 * @return string|null
	 */
	public function getResponseHeader( $header ) {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		if ( isset( $this->respHeaders[strtolower( $header )] ) ) {
			$v = $this->respHeaders[strtolower( $header )];
			return $v[count( $v ) - 1];
		}

		return null;
	}

	/**
	 * Tells the MWHttpRequest object to use this pre-loaded CookieJar.
	 *
	 * To read response cookies from the jar, getCookieJar must be called first.
	 *
	 * @param CookieJar $jar
	 */
	public function setCookieJar( CookieJar $jar ) {
		$this->cookieJar = $jar;
	}

	/**
	 * Returns the cookie jar in use.
	 *
	 * @return CookieJar
	 */
	public function getCookieJar() {
		if ( !$this->respHeaders ) {
			$this->parseHeader();
		}

		return $this->cookieJar;
	}

	/**
	 * Sets a cookie. Used before a request to set up any individual
	 * cookies. Used internally after a request to parse the
	 * Set-Cookie headers.
	 * @see Cookie::set
	 * @param string $name
	 * @param string $value
	 * @param array $attr
	 */
	public function setCookie( $name, $value, array $attr = [] ) {
		if ( !$this->cookieJar ) {
			$this->cookieJar = new CookieJar;
		}

		if ( $this->parsedUrl && !isset( $attr['domain'] ) ) {
			$attr['domain'] = $this->parsedUrl['host'];
		}

		$this->cookieJar->setCookie( $name, $value, $attr );
	}

	/**
	 * Parse the cookies in the response headers and store them in the cookie jar.
	 */
	protected function parseCookies() {
		if ( !$this->cookieJar ) {
			$this->cookieJar = new CookieJar;
		}

		if ( isset( $this->respHeaders['set-cookie'] ) ) {
			$url = parse_url( $this->getFinalUrl() );
			foreach ( $this->respHeaders['set-cookie'] as $cookie ) {
				$this->cookieJar->parseCookieResponseHeader( $cookie, $url['host'] );
			}
		}
	}

	/**
	 * Returns the final URL after all redirections.
	 *
	 * Relative values of the "Location" header are incorrect as
	 * stated in RFC, however they do happen and modern browsers
	 * support them.  This function loops backwards through all
	 * locations in order to build the proper absolute URI - Marooned
	 * at wikia-inc.com
	 *
	 * Note that the multiple Location: headers are an artifact of
	 * CURL -- they shouldn't actually get returned this way. Rewrite
	 * this when T31232 is taken care of (high-level redirect
	 * handling rewrite).
	 *
	 * @return string
	 */
	public function getFinalUrl() {
		$headers = $this->getResponseHeaders();

		// return full url (fix for incorrect but handled relative location)
		if ( isset( $headers['location'] ) ) {
			$locations = $headers['location'];
			$domain = '';
			$foundRelativeURI = false;
			$countLocations = count( $locations );

			for ( $i = $countLocations - 1; $i >= 0; $i-- ) {
				$url = parse_url( $locations[$i] );

				if ( isset( $url['host'] ) ) {
					$domain = $url['scheme'] . '://' . $url['host'];
					break; // found correct URI (with host)
				} else {
					$foundRelativeURI = true;
				}
			}

			if ( !$foundRelativeURI ) {
				return $locations[$countLocations - 1];
			}
			if ( $domain ) {
				return $domain . $locations[$countLocations - 1];
			}
			$url = parse_url( $this->url );
			if ( isset( $url['host'] ) ) {
				return $url['scheme'] . '://' . $url['host'] .
					$locations[$countLocations - 1];
			}
		}

		return $this->url;
	}

	/**
	 * Returns true if the backend can follow redirects. Overridden by the
	 * child classes.
	 * @return bool
	 */
	public function canFollowRedirects() {
		return true;
	}

	/**
	 * Set information about the original request. This can be useful for
	 * endpoints/API modules which act as a proxy for some service, and
	 * throttling etc. needs to happen in that service.
	 * Calling this will result in the X-Forwarded-For and X-Original-User-Agent
	 * headers being set.
	 * @param WebRequest|array $originalRequest When in array form, it's
	 *   expected to have the keys 'ip' and 'userAgent'.
	 * @note IP/user agent is personally identifiable information, and should
	 *   only be set when the privacy policy of the request target is
	 *   compatible with that of the MediaWiki installation.
	 */
	public function setOriginalRequest( $originalRequest ) {
		if ( $originalRequest instanceof WebRequest ) {
			$originalRequest = [
				'ip' => $originalRequest->getIP(),
				'userAgent' => $originalRequest->getHeader( 'User-Agent' ),
			];
		} elseif (
			!is_array( $originalRequest )
			|| array_diff( [ 'ip', 'userAgent' ], array_keys( $originalRequest ) )
		) {
			throw new InvalidArgumentException( __METHOD__ . ': $originalRequest must be a '
				. "WebRequest or an array with 'ip' and 'userAgent' keys" );
		}

		$this->reqHeaders['X-Forwarded-For'] = $originalRequest['ip'];
		$this->reqHeaders['X-Original-User-Agent'] = $originalRequest['userAgent'];
	}

	/**
	 * Check that the given URI is a valid one.
	 *
	 * This hardcodes a small set of protocols only, because we want to
	 * deterministically reject protocols not supported by all HTTP-transport
	 * methods.
	 *
	 * "file://" specifically must not be allowed, for security reasons
	 * (see <https://www.mediawiki.org/wiki/Special:Code/MediaWiki/r67684>).
	 *
	 * @todo FIXME this is wildly inaccurate and fails to actually check most stuff
	 *
	 * @since 1.34
	 * @param string $uri URI to check for validity
	 * @return bool
	 */
	public static function isValidURI( $uri ) {
		return (bool)preg_match(
			'/^https?:\/\/[^\/\s]\S*$/D',
			$uri
		);
	}
}
