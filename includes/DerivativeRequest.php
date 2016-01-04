<?php
/**
 * Similar to FauxRequest, but only fakes URL parameters and method
 * (POST or GET) and use the base request for the remaining stuff
 * (cookies, session and headers).
 *
 * @ingroup HTTP
 * @since 1.19
 */
class DerivativeRequest extends FauxRequest {
	private $base;

	/**
	 * @param WebRequest $base
	 * @param array $data Array of *non*-urlencoded key => value pairs, the
	 *   fake GET/POST values
	 * @param bool $wasPosted Whether to treat the data as POST
	 */
	public function __construct( WebRequest $base, $data, $wasPosted = false ) {
		$this->base = $base;
		parent::__construct( $data, $wasPosted );
	}

	public function getCookie( $key, $prefix = null, $default = null ) {
		return $this->base->getCookie( $key, $prefix, $default );
	}

	public function checkSessionCookie() {
		return $this->base->checkSessionCookie();
	}

	public function getHeader( $name, $flags = 0 ) {
		return $this->base->getHeader( $name, $flags );
	}

	public function getAllHeaders() {
		return $this->base->getAllHeaders();
	}

	public function getSessionData( $key ) {
		return $this->base->getSessionData( $key );
	}

	public function setSessionData( $key, $data ) {
		$this->base->setSessionData( $key, $data );
	}

	public function getAcceptLang() {
		return $this->base->getAcceptLang();
	}

	public function getIP() {
		return $this->base->getIP();
	}

	public function getProtocol() {
		return $this->base->getProtocol();
	}

	public function getElapsedTime() {
		return $this->base->getElapsedTime();
	}
}
