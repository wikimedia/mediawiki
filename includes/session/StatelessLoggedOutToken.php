<?php
/**
 * MediaWiki non-session based logged out edit token
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
 * Edit token for users with no session state
 *
 * Generally, this class should be instantiated as
 * new StatelessLoggedOutToken(
 *	$wgRequest->getIP() . $wgSecretKey, $salt
 * );
 * @since 1.28
 */

class StatelessLoggedOutToken extends Token implements LoggerAwareInterface {

	const MAX_ANON_TOKEN_AGE = 14400; // 4 hours

	/** @var String User IP address */
	private $ip;
	/** @var String Per-type salt for token */
	private $salt = '';
	/** @var LoggerInterface */
	private $logger;

	/**
	 * Make a new token for logged out users without sessions.
	 *
	 * @param $ip String Output of $this->getRequest()->getIP()
	 * @param $secret String A secret which is shared for all users
	 * @param $salt String A salt that varries depending on type of token.
	 */
	public function __construct( $ip, $secret, $salt ) {
		$this->ip = $ip;
		$this->salt = $salt;
		$this->setLogger( LoggerFactory::getInstance( 'token' ) );
		// Always make the token "new" since not from cookies.
		parent::__construct( $ip . $secret, $salt, true );
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
		// @todo Remove global when done testing phase.
		global $wgLoggedOutTokenTestingMode;

		$res = $this->realMatch( $userToken, $maxAge );
		if ( !$res && hash_equals( self::SUFFIX, substr( $userToken, -2 ) ) ) {
			// @todo For now we just log the failure until we are
			// out of the testing phase. Once we are done testing
			// this code branch will return the failure instead.
			if ( strlen( $userToken ) === 2 ) {
				$this->logger->info(
					"Old style +\\ '$this->salt'-token provided for anon",
					[ "method" => __METHOD__, "salt" => $this->salt ]
				);
			} else {
				$this->logger->info(
					"Invalid '$this->salt' token provided for anon w/o session",
					[ "method" => __METHOD__, "salt" => $this->salt ]
				);
			}
			return $wgLoggedOutTokenTestingMode ? true : $res;
		}
		return $res;
	}

	public function realMatch( $userToken, $maxAge = null ) {
		$maxAge = $maxAge ?: self::MAX_ANON_TOKEN_AGE;
		$maxAge = min( $maxAge, self::MAX_ANON_TOKEN_AGE );
		$res = parent::match( $userToken, $maxAge );
		if ( !$res ) {
			// @todo In the event of token match failure, it
			// might make sense to automatically persist the session.
			// On the other hand, it seems kind of wrong for a Token
			// object to effect state.
			// In any case, edit page will persist the session the
			// moment you hit preview or save, and htmlform will
			// persist on failure.
			$isItExpired = parent::match( $userToken, null );
			if ( $isItExpired ) {
				$this->logExpired( $maxAge, $userToken );
			}
		}
		return $res;
	}

	/**
	 * Log that we rejected a token as expired.
	 *
	 * We want to keep track of how often this happens, to ensure
	 * that most users aren't inconvenienced by the relatively short expiry
	 * of anon tokens.
	 *
	 * @param $maxAge integer Number of seconds token is good for.
	 * @param $token String Token being checked
	 */
	private function logExpired( $maxAge, $token ) {
		$this->logger->info(
			// putting $maxAge directly in msg so aggregated separately
			"Stateless token for {ip} rejected as expired (older than $maxAge s)",
			[
				'maxAge' => $maxAge,
				'ip' => $this->ip,
				'method' => __METHOD__,
				'tokenTimestamp' => $this->getTimestamp( $token )
			]
		);
	}
}
