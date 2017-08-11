<?php
/**
 * MediaWiki session token
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
 * @ingroup Session
 */

namespace MediaWiki\Session;

/**
 * Value object representing a CSRF token
 *
 * @ingroup Session
 * @since 1.27
 */
class Token {
	/** CSRF token suffix. Plus and terminal backslash are included to stop
	 * editing from certain broken proxies. */
	const SUFFIX = '+\\';

	private $secret = '';
	private $salt = '';
	private $new = false;

	/**
	 * @param string $secret Token secret
	 * @param string $salt Token salt
	 * @param bool $new Whether the secret was newly-created
	 */
	public function __construct( $secret, $salt, $new = false ) {
		$this->secret = $secret;
		$this->salt = $salt;
		$this->new = $new;
	}

	/**
	 * Decode the timestamp from a token string
	 *
	 * Does not validate the token beyond the syntactic checks necessary to
	 * be able to extract the timestamp.
	 *
	 * @param string $token
	 * @return int|null
	 */
	public static function getTimestamp( $token ) {
		$suffixLen = strlen( self::SUFFIX );
		$len = strlen( $token );
		if ( $len <= 32 + $suffixLen ||
			substr( $token, -$suffixLen ) !== self::SUFFIX ||
			strspn( $token, '0123456789abcdef' ) + $suffixLen !== $len
		) {
			return null;
		}

		return hexdec( substr( $token, 32, -$suffixLen ) );
	}

	/**
	 * Get the string representation of the token at a timestamp
	 * @param int $timestamp
	 * @return string
	 */
	protected function toStringAtTimestamp( $timestamp ) {
		return hash_hmac( 'md5', $timestamp . $this->salt, $this->secret, false ) .
			dechex( $timestamp ) .
			self::SUFFIX;
	}

	/**
	 * Get the string representation of the token
	 * @return string
	 */
	public function toString() {
		return $this->toStringAtTimestamp( wfTimestamp() );
	}

	public function __toString() {
		return $this->toString();
	}

	/**
	 * Test if the token-string matches this token
	 * @param string $userToken
	 * @param int|null $maxAge Return false if $userToken is older than this many seconds
	 * @return bool
	 */
	public function match( $userToken, $maxAge = null ) {
		$timestamp = self::getTimestamp( $userToken );
		if ( $timestamp === null ) {
			return false;
		}
		if ( $maxAge !== null && $timestamp < wfTimestamp() - $maxAge ) {
			// Expired token
			return false;
		}

		$sessionToken = $this->toStringAtTimestamp( $timestamp );
		return hash_equals( $sessionToken, $userToken );
	}

	/**
	 * Indicate whether this token was just created
	 * @return bool
	 */
	public function wasNew() {
		return $this->new;
	}

}
