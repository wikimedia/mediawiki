<?php
/**
 * Deal with importing all those nasty globals and things
 *
 * Copyright © 2003 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
 *
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

	public function getHeader( $name, $flags = 0 ) {
		return $this->base->getHeader( $name, $flags );
	}

	public function getAllHeaders() {
		return $this->base->getAllHeaders();
	}

	public function getSession() {
		return $this->base->getSession();
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
