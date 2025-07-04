<?php

namespace MediaWiki\Rest;

/**
 * Shared code between RequestData and RequestFromGlobals
 */
abstract class RequestBase implements RequestInterface {
	/**
	 * @var HeaderContainer|null
	 */
	private $headerCollection;

	/** @var array */
	private $pathParams = [];

	/** @var string */
	private $cookiePrefix;

	protected ?array $parsedBody = null;

	/**
	 * @internal
	 * @param string $cookiePrefix
	 */
	public function __construct( $cookiePrefix ) {
		$this->cookiePrefix = $cookiePrefix;
	}

	/**
	 * Override this in the implementation class if lazy initialisation of
	 * header values is desired. It should call setHeaders().
	 *
	 * @internal
	 */
	protected function initHeaders() {
	}

	public function __clone() {
		if ( $this->headerCollection !== null ) {
			$this->headerCollection = clone $this->headerCollection;
		}
	}

	/**
	 * Erase any existing headers and replace them with the specified header
	 * lines.
	 *
	 * Call this either from the constructor or from initHeaders() of the
	 * implementing class.
	 *
	 * @internal
	 * @param string[] $headers The header lines
	 */
	public function setHeaders( $headers ) {
		$this->headerCollection = new HeaderContainer;
		$this->headerCollection->resetHeaders( $headers );
	}

	/** @inheritDoc */
	public function getHeaders() {
		if ( $this->headerCollection === null ) {
			$this->initHeaders();
		}
		return $this->headerCollection->getHeaders();
	}

	/** @inheritDoc */
	public function getHeader( $name ) {
		if ( $this->headerCollection === null ) {
			$this->initHeaders();
		}
		return $this->headerCollection->getHeader( $name );
	}

	/** @inheritDoc */
	public function hasHeader( $name ) {
		if ( $this->headerCollection === null ) {
			$this->initHeaders();
		}
		return $this->headerCollection->hasHeader( $name );
	}

	/** @inheritDoc */
	public function getHeaderLine( $name ) {
		if ( $this->headerCollection === null ) {
			$this->initHeaders();
		}
		return $this->headerCollection->getHeaderLine( $name );
	}

	/** @inheritDoc */
	public function setPathParams( $params ) {
		$this->pathParams = $params;
	}

	/** @inheritDoc */
	public function getPathParams() {
		return $this->pathParams;
	}

	/** @inheritDoc */
	public function getPathParam( $name ) {
		return $this->pathParams[$name] ?? null;
	}

	/** @inheritDoc */
	public function getCookiePrefix() {
		return $this->cookiePrefix;
	}

	/** @inheritDoc */
	public function getCookie( $name, $default = null ) {
		$cookies = $this->getCookieParams();
		$prefixedName = $this->getCookiePrefix() . $name;
		if ( array_key_exists( $prefixedName, $cookies ) ) {
			return $cookies[$prefixedName];
		} else {
			return $default;
		}
	}

	public function getParsedBody(): ?array {
		return $this->parsedBody;
	}

	public function setParsedBody( ?array $data ) {
		$this->parsedBody = $data;
	}

	public function getBodyType(): ?string {
		[ $ct ] = explode( ';', $this->getHeaderLine( 'Content-Type' ), 2 );
		$ct = strtolower( trim( $ct ) );

		if ( $ct === '' ) {
			return null;
		}
		return $ct;
	}

	/**
	 * Return true if the client provided a content-length header or a
	 * transfer-encoding header.
	 *
	 * @see https://www.rfc-editor.org/rfc/rfc9110.html#name-content-length
	 *
	 * @return bool
	 */
	public function hasBody(): bool {
		// From RFC9110, section 8.6: A user agent SHOULD send Content-Length
		// in a request when the method defines a meaning for enclosed content
		// and it is not sending Transfer-Encoding. [...]
		// A user agent SHOULD NOT send a Content-Length header field when the
		// request message does not contain content and the method semantics do
		// not anticipate such data.

		if ( $this->getHeaderLine( 'content-length' ) !== '' ) {
			// If a content length is set, there is a body
			return true;
		}

		if ( $this->getHeaderLine( 'transfer-encoding' ) !== '' ) {
			// If a transfer encoding is set, there is a body
			return true;
		}

		return false;
	}

}
