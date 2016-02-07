<?php
/**
 * MediaWiki edit token
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

use MediaWiki\Logger\LoggerFactory;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Token where user is logged out but has session state
 *
 * This is mostly equivalent to a normal edit token, except
 * we want to still accept tokens from when they had no state,
 * if the user had no state recently. So we have a 1 hour
 * backwards compatability window where stateless tokens are
 * still valid.
 *
 * @todo Is 1 hour a reasonable choice of timeout for people
 *  with sessions?
 * @since 1.28
 */
class StatefulLoggedOutToken extends Token implements LoggerAwareInterface {

	const MAX_STATELESS_TOKEN_TIME = 3600; // 1 hour

	/**
	 * @var LoggerInterface $logger
	 */
	private $logger;

	/**
	 * If the user was just recently made stateful, also check the old
	 * stateless token.
	 *
	 * @var StatelessLoggedOutToken
	 */
	private $statelessCompatToken;

	/**
	 * Timestamp of when we became stateful
	 *
	 * @var String Timestamp in MW format
	 */
	private $statefulTimestamp;

	/** @var String per-type token salt. */
	private $salt;

	/**
	 * Token that is for logged out users with a session
	 *
	 * @param $ip string IP address (v4 or v6)
	 * @param $statelessSecret string non-unique secret for stateless token ($wgSecretKey)
	 * @param $statefulSecret string Unique per-session secret for this token
	 * @param $statefulTime string Timestamp when the session was started
	 * @param $salt string Extra salt for this token (ie for different token types)
	 * @param $new boolean Is the session newly constructed?
	 */
	public function __construct(
		$ip, $statelessSecret, $statefulSecret, $statefulTime, $salt, $new = false
	) {
		$this->statelessCompatToken = new StatelessLoggedOutToken(
			$ip, $statelessSecret, $salt
		);
		$this->statefulTimestamp = $statefulTime;
		$this->setLogger( LoggerFactory::getInstance( 'token' ) );

		parent::__construct( $statefulSecret, $salt, $new );
		$this->salt = $salt; // temporary for log messages
	}

	/**
	 * Sets a logger instance on the object.
	 *
	 * @param LoggerInterface $logger
	 * @return null
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	public function match( $userToken, $maxAge = null ) {
		// @todo Remove global when done testing.
		global $wgLoggedOutTokenTestingMode;

		$res = $this->realMatch( $userToken, $maxAge );
		if ( !$res
			&& hash_equals( '+\\', substr( $userToken, -2 ) )
		) {
			// @todo For now this just logs failures, but once
			// we are out of the testing phase, this will cause
			// the token check to fail on failure.
			if ( strlen( $userToken ) === 2 ) {
				$this->logger->info(
					"Old style +\\ '" . $this->salt . "'-token provided for anon",
					[ "method" => __METHOD__, "salt" => $this->salt ]
				);
			} else {
				$this->logger->info(
					"Invalid '$this->salt' token provided for anon with session",
					[ "method" => __METHOD__, "salt" => $this->salt ]
				);
			}
			return $wgLoggedOutTokenTestingMode ? true : $res;
		}
		return $res;
	}

	public function realMatch( $userToken, $maxAge = null ) {
		$normalMatch = parent::match( $userToken, $maxAge );
		if ( $normalMatch ) {
			return $normalMatch;
		}

		// If the session was just recently created, still allow the user
		// to submit old style cookies.
		$compatMaxAge = $maxAge ? min( $maxAge, self::MAX_STATELESS_TOKEN_TIME )
			: self::MAX_STATELESS_TOKEN_TIME;
		$compatMatch = $this->statelessCompatToken->realMatch( $userToken, $compatMaxAge );
		$compatWindow = \wfTimestamp( TS_UNIX ) - self::MAX_STATELESS_TOKEN_TIME;
		$stateAge = \wfTimestamp( TS_UNIX, $this->statefulTimestamp );
		if ( $stateAge > $compatWindow || $this->wasNew() ) {
			return $compatMatch;
		} elseif ( $compatMatch ) {
			$this->logger->info(
				'Valid stateless anon token rejected for stateful anon because state'
				. ' too old (Session started: {age})',
				[
					'method' => __METHOD__,
					'age' => \wfTimestamp( TS_ISO_8601, $stateAge )
				]
			);
		}
		return false;
	}

	/**
	 * If this token is new, we don't know if the user supports cookies yet.
	 *
	 * Thus still return a stateless cookie for this request.
	 *
	 * @return String the value of the token
	 */
	public function toString() {
		if ( $this->wasNew() ) {
			return $this->statelessCompatToken->toString();
		}
		return parent::toString();
	}
}
