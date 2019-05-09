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
			$this->uri = new Uri( \WebRequest::getGlobalRequestURL() );
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
		if ( function_exists( 'apache_request_headers' ) ) {
			$this->setHeaders( apache_request_headers() );
		} else {
			$headers = [];
			foreach ( $_SERVER as $name => $value ) {
				if ( substr( $name, 0, 5 ) === 'HTTP_' ) {
					$name = strtolower( str_replace( '_', '-', substr( $name, 5 ) ) );
					$headers[$name] = $value;
				} elseif ( $name === 'CONTENT_LENGTH' ) {
					$headers['content-length'] = $value;
				}
			}
			$this->setHeaders( $headers );
		}
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
