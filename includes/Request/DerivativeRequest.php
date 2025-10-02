<?php
/**
 * Deal with importing all those nasty globals and things
 *
 * Copyright © 2003 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Request;

use MediaWiki\Session\Session;

/**
 * Similar to MediaWiki\Request\FauxRequest, but only fakes URL parameters and method
 * (POST or GET) and use the base request for the remaining stuff
 * (cookies, session and headers).
 *
 * @newable
 *
 * @ingroup HTTP
 * @since 1.19
 */
class DerivativeRequest extends FauxRequest {
	private WebRequest $base;
	/** @var string|null */
	private $ip;

	/**
	 * @stable to call
	 *
	 * @param WebRequest $base
	 * @param array $data Array of *non*-urlencoded key => value pairs, the
	 *   fake GET/POST values
	 * @param bool $wasPosted Whether to treat the data as POST
	 */
	public function __construct( WebRequest $base, $data, $wasPosted = false ) {
		$this->base = $base;
		parent::__construct( $data, $wasPosted );
	}

	/** @inheritDoc */
	public function getCookie( $key, $prefix = null, $default = null ) {
		return $this->base->getCookie( $key, $prefix, $default );
	}

	/** @inheritDoc */
	public function getHeader( $name, $flags = 0 ) {
		return $this->base->getHeader( $name, $flags );
	}

	/** @inheritDoc */
	public function getAllHeaders() {
		return $this->base->getAllHeaders();
	}

	/** @inheritDoc */
	public function getSession(): Session {
		return $this->base->getSession();
	}

	/** @inheritDoc */
	public function getSessionData( $key ) {
		return $this->base->getSessionData( $key );
	}

	/** @inheritDoc */
	public function setSessionData( $key, $data ) {
		$this->base->setSessionData( $key, $data );
	}

	/** @inheritDoc */
	public function getAcceptLang() {
		return $this->base->getAcceptLang();
	}

	/** @inheritDoc */
	public function getIP(): string {
		return $this->ip ?: $this->base->getIP();
	}

	/** @inheritDoc */
	public function setIP( $ip ) {
		$this->ip = $ip;
	}

	/** @inheritDoc */
	public function getProtocol() {
		return $this->base->getProtocol();
	}

	/** @inheritDoc */
	public function getUpload( $key ) {
		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
		return $this->base->getUpload( $key );
	}

	/** @inheritDoc */
	public function getElapsedTime() {
		return $this->base->getElapsedTime();
	}
}
