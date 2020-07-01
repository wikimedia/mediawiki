<?php

namespace MediaWiki\Rest;

use GuzzleHttp\Psr7\LazyOpenStream;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;

// phpcs:disable MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals

/**
 * This is a request class that gets data directly from the superglobals and
 * other global PHP state, notably php://input.
 */
class RequestFromGlobals extends RequestBase {
	private $uri;
	private $protocol;
	private $uploadedFiles;

	/**
	 * @param array $params Associative array of parameters:
	 *   - cookiePrefix: The prefix for cookie names used by getCookie()
	 */
	public function __construct( $params = [] ) {
		parent::__construct( $params['cookiePrefix'] ?? '' );
	}

	// RequestInterface

	public function getMethod() {
		return $_SERVER['REQUEST_METHOD'] ?? 'GET';
	}

	public function getUri() {
		if ( $this->uri === null ) {
			$requestUrl = \WebRequest::getGlobalRequestURL();

			// Uri constructor will throw exception if parse_url() fails to parse
			// the $requestUrl. This will happen if the URL is relative and contains
			// colon-number pattern that looks like a port.
			//
			// Since $requestUrl here is absolute-path references so all titles that
			// contain colon followed by a number would be inacessible if the exception
			// occurs. To forestall the exception we temporarily turn the request URL
			// into to absolute form before instantiating the Uri and we turn it back to
			// its absolute-path reference form afterwards. T256831.
			$needsAbsoluteUrl = parse_url( $requestUrl ) === false;

			if ( $needsAbsoluteUrl ) {
				$requestUrl = \WebRequest::detectProtocol() === 'http'
					? wfGetServerUrl( PROTO_HTTP ) . $requestUrl
					: wfGetServerUrl( PROTO_HTTPS ) . $requestUrl;

				// Instantiates Uri and remove the temporary host and scheme that
				// we added to turn the URL back to its absolute-path reference form.
				$uriInstance = new Uri( $requestUrl );
				$this->uri = $uriInstance->withScheme( '' )->withHost( '' );
			} else {
				$this->uri = new Uri( $requestUrl );
			}
		}
		return $this->uri;
	}

	// MessageInterface

	public function getProtocolVersion() {
		if ( $this->protocol === null ) {
			$serverProtocol = $_SERVER['SERVER_PROTOCOL'] ?? '';
			$prefixLength = strlen( 'HTTP/' );
			if ( strncmp( $serverProtocol, 'HTTP/', $prefixLength ) === 0 ) {
				$this->protocol = substr( $serverProtocol, $prefixLength );
			} else {
				$this->protocol = '1.1';
			}
		}
		return $this->protocol;
	}

	protected function initHeaders() {
		$this->setHeaders( getallheaders() );
	}

	public function getBody() {
		return new LazyOpenStream( 'php://input', 'r' );
	}

	// ServerRequestInterface

	public function getServerParams() {
		return $_SERVER;
	}

	public function getCookieParams() {
		return $_COOKIE;
	}

	public function getQueryParams() {
		return $_GET;
	}

	public function getUploadedFiles() {
		if ( $this->uploadedFiles === null ) {
			$this->uploadedFiles = ServerRequest::normalizeFiles( $_FILES );
		}
		return $this->uploadedFiles;
	}

	public function getPostParams() {
		return $_POST;
	}
}
