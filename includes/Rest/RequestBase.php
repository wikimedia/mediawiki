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

	/**
	 * @internal
	 * @param string $cookiePrefix
	 */
	protected function __construct( $cookiePrefix ) {
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
	protected function setHeaders( $headers ) {
		$this->headerCollection = new HeaderContainer;
		$this->headerCollection->resetHeaders( $headers );
	}

	public function getHeaders() {
		if ( $this->headerCollection === null ) {
			$this->initHeaders();
		}
		return $this->headerCollection->getHeaders();
	}

	public function getHeader( $name ) {
		if ( $this->headerCollection === null ) {
			$this->initHeaders();
		}
		return $this->headerCollection->getHeader( $name );
	}

	public function hasHeader( $name ) {
		if ( $this->headerCollection === null ) {
			$this->initHeaders();
		}
		return $this->headerCollection->hasHeader( $name );
	}

	public function getHeaderLine( $name ) {
		if ( $this->headerCollection === null ) {
			$this->initHeaders();
		}
		return $this->headerCollection->getHeaderLine( $name );
	}

	public function setPathParams( $params ) {
		$this->pathParams = $params;
	}

	public function getPathParams() {
		return $this->pathParams;
	}

	public function getPathParam( $name ) {
		return $this->pathParams[$name] ?? null;
	}

	public function getCookiePrefix() {
		return $this->cookiePrefix;
	}

	public function getCookie( $name, $default = null ) {
		$cookies = $this->getCookieParams();
		$prefixedName = $this->getCookiePrefix() . $name;
		if ( array_key_exists( $prefixedName, $cookies ) ) {
			return $cookies[$prefixedName];
		} else {
			return $default;
		}
	}
}
