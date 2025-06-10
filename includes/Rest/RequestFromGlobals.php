<?php

namespace MediaWiki\Rest;

use GuzzleHttp\Psr7\LazyOpenStream;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use InvalidArgumentException;
use MediaWiki\Request\WebRequest;

// phpcs:disable MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals

/**
 * This is a request class that gets data directly from the superglobals and
 * other global PHP state, notably php://input.
 */
class RequestFromGlobals extends RequestBase {
	/** @var Uri|null */
	private $uri;
	/** @var string|null */
	private $protocol;
	/** @var array|null */
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
		// Even though the spec says that method names should always be
		// upper case, some clients may send lower case method names (T359306).
		return strtoupper( $_SERVER['REQUEST_METHOD'] ?? 'GET' );
	}

	public function getUri() {
		if ( $this->uri === null ) {
			$requestUrl = WebRequest::getGlobalRequestURL();

			try {
				$uriInstance = new Uri( $requestUrl );
			} catch ( InvalidArgumentException ) {
				// Uri constructor will throw exception if the URL is
				// relative and contains colon-number pattern that
				// looks like a port.
				//
				// Since $requestUrl here is absolute-path references
				// so all titles that contain colon followed by a
				// number would be inaccessible if the exception occurs.
				$uriInstance = (
					new Uri( '//HOST:80' . $requestUrl )
				)->withScheme( '' )->withHost( '' )->withPort( null );
			}
			$this->uri = $uriInstance;
		}
		return $this->uri;
	}

	// MessageInterface

	public function getProtocolVersion() {
		if ( $this->protocol === null ) {
			$serverProtocol = $_SERVER['SERVER_PROTOCOL'] ?? '';
			$prefix = 'HTTP/';
			if ( str_starts_with( $serverProtocol, $prefix ) ) {
				$this->protocol = substr( $serverProtocol, strlen( $prefix ) );
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
		$this->uploadedFiles ??= ServerRequest::normalizeFiles( $_FILES );
		return $this->uploadedFiles;
	}

	public function getPostParams() {
		return $_POST;
	}

}
