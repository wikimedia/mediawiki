<?php
/**
 * MediaWiki session provider interface
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
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Config;
use Language;
use WebRequest;

/**
 * A SessionProvider provides SessionInfo and support for Session
 *
 * A SessionProvider is responsible for taking a WebRequest and determining
 * the authenticated session that it's a part of. It does this by returning an
 * SessionInfo object with basic information about the session it thinks is
 * associated with the request, namely the session ID and possibly the
 * authenticated user the session belongs to.
 *
 * The SessionProvider also provides for updating the WebResponse with
 * information necessary to provide the client with data that the client will
 * send with later requests, and for populating the Vary and X-Vary-Options
 * headers with the data necessary to correctly vary the cache on these client
 * requests.
 *
 * An important part of the latter is indicating whether it even *can* tell the
 * client to include such data in future requests, via the persistsSessionId()
 * and persistsUser() methods. The cases are (in order of decreasing commonness):
 *  - Cannot persist ID or User: The request identifies and authenticates a
 *    particular local user, and the client cannot be instructed to include an
 *    arbitrary session ID with future requests.
 *  - Can persist ID and User: The client can be instructed to return at least
 *    one piece of arbitrary data, that being the session ID. The user identity
 *    might also be given to the client, otherwise it's saved in the session data.
 *  - Can persist ID but not User: The request uniquely identifies and
 *    authenticates a local user, and the client can be instructed to return an
 *    arbitrary session ID with future requests.
 *  - Can persist User but not ID: I can't think of a way this would make
 *    sense.
 *
 * @ingroup Session
 * @since 1.27
 */
abstract class SessionProvider implements LoggerAwareInterface {

	/** @var LoggerInterface */
	protected $logger;

	/** @var Config */
	protected $config;

	/** @var SessionManager */
	protected $manager;

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Set configuration
	 * @param Config $config
	 */
	public function setConfig( Config $config ) {
		$this->config = $config;
	}

	/**
	 * Set the session manager
	 * @param SessionManager $manager
	 */
	public function setManager( SessionManager $manager ) {
		$this->manager = $manager;
	}

	/**
	 * Get the session manager
	 * @return SessionManager
	 */
	public function getManager() {
		return $this->manager;
	}

	/**
	 * Provide session info for a request
	 *
	 * If no session exists for the request, return null. Otherwise return an
	 * SessionInfo object identifying the session.
	 *
	 * If multiple SessionProviders provide sessions, the one with highest
	 * priority wins. In case of a tie, an exception is thrown.
	 * SessionProviders are encouraged to make priorities user-configurable
	 * unless only max-priority makes sense.
	 *
	 * @warning This will be called early in the MediaWiki setup process,
	 *  before $wgUser, $wgLang, $wgOut, $wgParser, $wgTitle, and corresponding
	 *  pieces of the main RequestContext are set up! If you try to use these,
	 *  things *will* break.
	 * @note The SessionProvider must not attempt to auto-create users.
	 *  MediaWiki will do this later (when it's safe) if the chosen session has
	 *  a user with a valid name but no ID.
	 * @param WebRequest $request
	 * @return SessionInfo|null
	 */
	abstract public function provideSessionInfo( WebRequest $request );

	/**
	 * Provide session info for a new, empty session
	 * @param string|null $id ID to force for the new session
	 * @return SessionInfo|null
	 */
	public function newSessionInfo( $id = null ) {
		if ( $this->persistsUser() && $this->persistsSessionId() ) {
			return new SessionInfo( SessionInfo::MIN_PRIORITY, array(
				'id' => $id,
				'provider' => $this,
			) );
		}
		return null;
	}

	/**
	 * Indicate whether self::persistSession() can save arbitrary session IDs
	 *
	 * If false, any session passed to self::persistSession() will have an ID
	 * that was originally provided by self::provideSessionInfo().
	 *
	 * If true, the provider may be passed sessions with arbitrary session IDs,
	 * and will be expected to manipulate the request in such a way that future
	 * requests will cause self::provideSessionInfo() to provide a request with
	 * that ID.
	 *
	 * For example, a session provider for OAuth would function by matching the
	 * OAuth headers to a particular user, and then would use self::hashToSessionId()
	 * to turn the user and OAuth client ID (and maybe also the user token and
	 * client secret) into a session ID, and therefore can't easily assign that
	 * user+client a different ID. Similarly, a session provider for SSL client
	 * certificates would function by matching the certificate to a particular
	 * user, and then would use self::hashToSessionId() to turn the user and
	 * certificate fingerprint into a session ID, and therefore can't easily
	 * assign a different ID either. On the other hand, a provider that saves
	 * the session ID into a cookie can easily just set the cookie to a
	 * different value.
	 *
	 * @return bool
	 */
	abstract public function persistsSessionId();

	/**
	 * Indicate whether self::persistSession() can save arbitrary user info
	 *
	 * If false, any session passed to self::persistSession() will have a user
	 * that was originally provided by self::provideSessionInfo(). Further,
	 * self::provideSessionInfo() may only provide sessions that have a user
	 * already set.
	 *
	 * If true, the provider may be passed sessions with arbitrary users.
	 *
	 * For example, a session provider for OAuth or SSL client certificates
	 * would function by matching the OAuth headers or certificate to a
	 * particular user, and thus would return false here since it can't
	 * arbitrarily assign those OAuth credentials or that certificate to a
	 * different user. A session provider that shoves information into cookies,
	 * on the other hand, could easily do so.
	 *
	 * Since the user info is also saved into the session metadata, this
	 * default implementation assumes that any provider that can persist
	 * session IDs can also persist the user info (if nothing else by passing
	 * null to the $user parameter of SessionInfo::__construct()).
	 *
	 * @return bool
	 */
	public function persistsUser() {
		return $this->persistsSessionId();
	}

	/**
	 * Persist a session into a request/response
	 *
	 * For example, you might set cookies for the session's ID, user ID, user
	 * name, and user token on the passed request.
	 *
	 * To correctly persist a user independently of the session ID, the
	 * provider should persist both the user ID (or name, but preferably the
	 * ID) and the user token. When reading the data from the request, it
	 * should construct a User object from the ID/name and then verify that the
	 * User object's token matches the token included in the request. Should
	 * the tokens not match, an anonymous user must be passed to
	 * SessionInfo::__construct().
	 *
	 * @param SessionBackend $session Session to persist
	 * @param WebRequest $request Request into which to persist the session
	 */
	abstract public function persistSession( SessionBackend $session, WebRequest $request );

	/**
	 * Remove any persisted session from a request/response
	 *
	 * For example, blank and expire any cookies set by self::persistSession().
	 *
	 * @param WebRequest $request Request from which to remove any session data
	 */
	abstract public function unpersistSession( WebRequest $request );

	/**
	 * Indicate whether an immutable session could be returned for the user.
	 * @param string $username
	 * @return bool
	 */
	public function immutableSessionCouldExistForUser( $username ) {
		if ( $this->persistsUser() ) {
			return false;
		} else {
			throw new \BadMethodCallException(
				__METHOD__ . ' must be implmented when persistsUser() is false'
			);
		}
	}

	/**
	 * Prevent immutable sessions from being created for the user.
	 *
	 * This might add the username to a blacklist, or it might just delete
	 * whatever authentication credentials would allow such a session in the
	 * first place (e.g. remove all OAuth grants or delete record of the SSL
	 * client certificate).
	 *
	 * The intention is that the named account will never again be usable for
	 * normal login (i.e. there is no way to undo the prevention of access).
	 *
	 * @param string $username
	 */
	public function preventImmutableSessionsForUser( $username ) {
		if ( !$this->persistsUser() ) {
			throw new \BadMethodCallException(
				__METHOD__ . ' must be implmented when persistsUser() is false'
			);
		}
	}

	/**
	 * Return the HTTP headers that need varying on.
	 *
	 * The return value is such that someone could theoretically do this:
	 * @code
	 *  foreach ( $provider->getVaryHeaders() as $header => $options ) {
	 *  	$outputPage->addVaryHeader( $header, $options );
	 *  }
	 * @endcode
	 *
	 * @return array
	 */
	public function getVaryHeaders() {
		return array();
	}

	/**
	 * Return the list of cookies that need varying on.
	 * @return string[]
	 */
	public function getVaryCookies() {
		return array();
	}

	/**
	 * Get a suggested username for the login form
	 * @param WebRequest $request
	 * @return string|null
	 */
	public function suggestLoginUsername( WebRequest $request ) {
		return null;
	}

	/**
	 * Return an identifier for this session type
	 * @param Language $lang Language to use. If you use i18n messages, only use ->plain().
	 * @return string
	 */
	public function describe( Language $lang ) {
		$msg = wfMessage( 'sessionprovider-' . strtolower( get_class( $this ) ) );
		$msg->inLanguage( $lang );
		if ( $msg->isDisabled() ) {
			$msg = wfMessage( 'sessionprovider-generic', (string)$this )->inLanguage( $lang );
		}
		return $msg->plain();
	}

	/**
	 * Hash data as a session ID
	 *
	 * Generally this will only be used when self::persistsSessionId() is false and
	 * the provider has to base the session ID on the authenticated user's
	 * identity or other static data.
	 *
	 * @param string $data
	 * @param string|null $key Defaults to $this->config->get( 'SecretKey' )
	 * @return string
	 */
	final protected function hashToSessionId( $data, $key = null ) {
		$hash = \MWCryptHash::hmac( "$this\n$data", $key ?: $this->config->get( 'SecretKey' ), false );
		if ( strlen( $hash ) < 32 ) {
			// Should never happen, even md5 is 128 bits
			// @codeCoverageIgnoreStart
			throw new \UnexpectedValueException( 'Hash fuction returned less than 128 bits' );
			// @codeCoverageIgnoreEnd
		}
		if ( strlen( $hash ) >= 40 ) {
			$hash = wfBaseConvert( $hash, 16, 32, 32 );
		}
		return substr( $hash, 0, 32 );
	}

	/**
	 * @note Only override this if it makes sense to instantiate multiple
	 *  instances of the provider. Value returned must be unique across
	 *  configured providers.
	 * @return string
	 */
	public function __toString() {
		return get_class( $this );
	}

}
