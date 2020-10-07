<?php

namespace MediaWiki\Rest;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

/**
 * This is a Request class that allows data to be injected, for the purposes
 * of testing or internal requests.
 */
class RequestData extends RequestBase {
	private $method;

	/** @var UriInterface */
	private $uri;

	private $protocolVersion;

	/** @var StreamInterface */
	private $body;

	private $serverParams;

	private $cookieParams;

	private $queryParams;

	/** @var UploadedFileInterface[] */
	private $uploadedFiles;

	private $postParams;

	/**
	 * Construct a RequestData from an array of parameters.
	 *
	 * @param array $params An associative array of parameters. All parameters
	 *   have defaults. Parameters are:
	 *     - method: The HTTP method
	 *     - uri: The URI
	 *     - protocolVersion: The HTTP protocol version number
	 *     - bodyContents: A string giving the request body
	 *     - serverParams: Equivalent to $_SERVER
	 *     - cookieParams: Equivalent to $_COOKIE
	 *     - queryParams: Equivalent to $_GET
	 *     - uploadedFiles: An array of objects implementing UploadedFileInterface
	 *     - postParams: Equivalent to $_POST
	 *     - pathParams: The path template parameters
	 *     - headers: An array with the key being the header name
	 *     - cookiePrefix: A prefix to add to cookie names in getCookie()
	 */
	public function __construct( $params = [] ) {
		$this->method = $params['method'] ?? 'GET';
		$this->uri = $params['uri'] ?? new Uri;
		$this->protocolVersion = $params['protocolVersion'] ?? '1.1';
		$this->body = new StringStream( $params['bodyContents'] ?? '' );
		$this->serverParams = $params['serverParams'] ?? [];
		$this->cookieParams = $params['cookieParams'] ?? [];
		$this->queryParams = $params['queryParams'] ?? [];
		$this->uploadedFiles = $params['uploadedFiles'] ?? [];
		$this->postParams = $params['postParams'] ?? [];
		$this->setPathParams( $params['pathParams'] ?? [] );
		$this->setHeaders( $params['headers'] ?? [] );
		parent::__construct( $params['cookiePrefix'] ?? '' );
	}

	public function getMethod() {
		return $this->method;
	}

	public function getUri() {
		return $this->uri;
	}

	public function getProtocolVersion() {
		return $this->protocolVersion;
	}

	public function getBody() {
		return $this->body;
	}

	public function getServerParams() {
		return $this->serverParams;
	}

	public function getCookieParams() {
		return $this->cookieParams;
	}

	public function getQueryParams() {
		return $this->queryParams;
	}

	public function getUploadedFiles() {
		return $this->uploadedFiles;
	}

	public function getPostParams() {
		return $this->postParams;
	}
}
