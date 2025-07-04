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
	/** @var string */
	private $method;

	/** @var UriInterface */
	private $uri;

	/** @var string */
	private $protocolVersion;

	/** @var StreamInterface */
	private $body;

	/** @var array */
	private $serverParams;

	/** @var array */
	private $cookieParams;

	/** @var array */
	private $queryParams;

	/** @var UploadedFileInterface[] */
	private $uploadedFiles;

	/** @var array */
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
		$this->setParsedBody( $params['parsedBody'] ?? null );
		parent::__construct( $params['cookiePrefix'] ?? '' );
	}

	/** @inheritDoc */
	public function getMethod() {
		return $this->method;
	}

	/** @inheritDoc */
	public function getUri() {
		return $this->uri;
	}

	/** @inheritDoc */
	public function getProtocolVersion() {
		return $this->protocolVersion;
	}

	/** @inheritDoc */
	public function getBody() {
		return $this->body;
	}

	/** @inheritDoc */
	public function getServerParams() {
		return $this->serverParams;
	}

	/** @inheritDoc */
	public function getCookieParams() {
		return $this->cookieParams;
	}

	/** @inheritDoc */
	public function getQueryParams() {
		return $this->queryParams;
	}

	/** @inheritDoc */
	public function getUploadedFiles() {
		return $this->uploadedFiles;
	}

	/** @inheritDoc */
	public function getPostParams() {
		return $this->postParams;
	}

	/** @inheritDoc */
	public function hasBody(): bool {
		if ( parent::hasBody() ) {
			return true;
		}

		if ( $this->parsedBody !== null ) {
			return true;
		}

		if ( $this->postParams !== [] ) {
			return true;
		}

		if ( $this->getBody()->getSize() > 0 ) {
			return true;
		}

		return false;
	}

}
