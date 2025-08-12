<?php
/**
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

namespace MediaWiki\Session;

use InvalidArgumentException;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Config\Config;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Request\WebRequest;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\User\User;
use MediaWiki\User\UserNameUtils;
use MWRestrictions;
use Psr\Log\LoggerInterface;
use Stringable;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

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
 * send with later requests, and for populating the Vary and Key headers with
 * the data necessary to correctly vary the cache on these client requests.
 *
 * An important part of the latter is indicating whether it even *can* tell the
 * client to include such data in future requests, via the persistsSessionId()
 * and canChangeUser() methods. The cases are (in order of decreasing
 * commonness):
 *  - Cannot persist ID, no changing User: The request identifies and
 *    authenticates a particular local user, and the client cannot be
 *    instructed to include an arbitrary session ID with future requests. For
 *    example, OAuth or SSL certificate auth.
 *  - Can persist ID and can change User: The client can be instructed to
 *    return at least one piece of arbitrary data, that being the session ID.
 *    The user identity might also be given to the client, otherwise it's saved
 *    in the session data. For example, cookie-based sessions.
 *  - Can persist ID but no changing User: The request uniquely identifies and
 *    authenticates a local user, and the client can be instructed to return an
 *    arbitrary session ID with future requests. For example, HTTP Digest
 *    authentication might somehow use the 'opaque' field as a session ID
 *    (although getting MediaWiki to return 401 responses without breaking
 *    other stuff might be a challenge).
 *  - Cannot persist ID but can change User: I can't think of a way this
 *    would make sense.
 *
 * Note that many methods that are technically "cannot persist ID" could be
 * turned into "can persist ID but not change User" using a session cookie,
 * as implemented by ImmutableSessionProviderWithCookie. If doing so, different
 * session cookie names should be used for different providers to avoid
 * collisions.
 *
 * @see https://www.mediawiki.org/wiki/Manual:SessionManager_and_AuthManager
 * @stable to extend
 * @since 1.27
 * @ingroup Session
 */
abstract class SessionProvider implements Stringable, SessionProviderInterface {

	protected LoggerInterface $logger;
	protected Config $config;
	protected SessionManager $manager;
	private HookContainer $hookContainer;
	private HookRunner $hookRunner;
	protected UserNameUtils $userNameUtils;

	/** @var int Session priority. Used for the default newSessionInfo(), but
	 * could be used by subclasses too.
	 */
	protected $priority;

	/**
	 * @stable to call
	 */
	public function __construct() {
		$this->priority = SessionInfo::MIN_PRIORITY + 10;
	}

	/**
	 * Initialise with dependencies of a SessionProvider
	 *
	 * @since 1.37
	 * @internal In production code SessionManager will initialize the
	 * SessionProvider, in tests SessionProviderTestTrait must be used.
	 */
	public function init(
		LoggerInterface $logger,
		Config $config,
		SessionManager $manager,
		HookContainer $hookContainer,
		UserNameUtils $userNameUtils
	) {
		$this->logger = $logger;
		$this->config = $config;
		$this->manager = $manager;
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userNameUtils = $userNameUtils;
		$this->postInitSetup();
	}

	/**
	 * A provider can override this to do any necessary setup after init()
	 * is called.
	 *
	 * @since 1.37
	 * @stable to override
	 */
	protected function postInitSetup() {
	}

	/**
	 * Get the config
	 *
	 * @since 1.37
	 * @return Config
	 */
	protected function getConfig() {
		return $this->config;
	}

	/**
	 * Get the session manager
	 * @return SessionManager
	 */
	public function getManager() {
		return $this->manager;
	}

	protected function getHookContainer(): HookContainer {
		return $this->hookContainer;
	}

	/**
	 * Get the HookRunner
	 *
	 * @internal This is for use by core only. Hook interfaces may be removed
	 *   without notice.
	 * @since 1.35
	 * @return HookRunner
	 */
	protected function getHookRunner(): HookRunner {
		return $this->hookRunner;
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
	 *  before $wgUser, $wgLang, $wgOut, $wgTitle, the global parser, and
	 *  corresponding pieces of the main RequestContext are set up! If you try
	 *  to use these, things *will* break.
	 * @note The SessionProvider must not attempt to auto-create users.
	 *  MediaWiki will do this later (when it's safe) if the chosen session has
	 *  a user with a valid name but no ID.
	 * @note For use by \MediaWiki\Session\SessionManager only
	 * @param WebRequest $request
	 * @return SessionInfo|null
	 */
	abstract public function provideSessionInfo( WebRequest $request );

	/**
	 * Provide session info for a new, empty session
	 *
	 * Return null if such a session cannot be created. This base
	 * implementation assumes that it only makes sense if a session ID can be
	 * persisted and changing users is allowed.
	 * @stable to override
	 *
	 * @note For use by \MediaWiki\Session\SessionManager only
	 * @param string|null $id ID to force for the new session
	 * @return SessionInfo|null
	 *  If non-null, must return true for $info->isIdSafe(); pass true for
	 *  $data['idIsSafe'] to ensure this.
	 */
	public function newSessionInfo( $id = null ) {
		if ( $this->canChangeUser() && $this->persistsSessionId() ) {
			return new SessionInfo( $this->priority, [
				'id' => $id,
				'provider' => $this,
				'persisted' => false,
				'idIsSafe' => true,
			] );
		}
		return null;
	}

	/**
	 * Merge saved session provider metadata
	 *
	 * This method will be used to compare the metadata returned by
	 * provideSessionInfo() with the saved metadata (which has been returned by
	 * provideSessionInfo() the last time the session was saved), and merge the two
	 * into the new saved metadata, or abort if the current request is not a valid
	 * continuation of the session.
	 *
	 * The default implementation checks that anything in both arrays is
	 * identical, then returns $providedMetadata.
	 * @stable to override
	 *
	 * @note For use by \MediaWiki\Session\SessionManager only
	 * @param array $savedMetadata Saved provider metadata
	 * @param array $providedMetadata Provided provider metadata (from the SessionInfo)
	 * @return array Resulting metadata
	 * @throws MetadataMergeException If the metadata cannot be merged.
	 *  Such exceptions will be handled by SessionManager and are a safe way of rejecting
	 *  a suspicious or incompatible session. The provider is expected to write an
	 *  appropriate message to its logger.
	 */
	public function mergeMetadata( array $savedMetadata, array $providedMetadata ) {
		foreach ( $providedMetadata as $k => $v ) {
			if ( array_key_exists( $k, $savedMetadata ) && $savedMetadata[$k] !== $v ) {
				$e = new MetadataMergeException( "Key \"$k\" changed" );
				$e->setContext( [
					'old_value' => $savedMetadata[$k],
					'new_value' => $v,
				] );
				throw $e;
			}
		}
		return $providedMetadata;
	}

	/**
	 * Validate a loaded SessionInfo and refresh provider metadata
	 *
	 * This is similar in purpose to the 'SessionCheckInfo' hook, and also
	 * allows for updating the provider metadata. On failure, the provider is
	 * expected to write an appropriate message to its logger.
	 * @stable to override
	 *
	 * @note For use by \MediaWiki\Session\SessionManager only
	 * @param SessionInfo $info Any changes by mergeMetadata() will already be reflected here.
	 * @param WebRequest $request
	 * @param array|null &$metadata Provider metadata, may be altered.
	 * @return bool Return false to reject the SessionInfo after all.
	 */
	public function refreshSessionInfo( SessionInfo $info, WebRequest $request, &$metadata ) {
		return true;
	}

	/**
	 * Indicate whether self::persistSession() can save arbitrary session IDs
	 *
	 * If false, any session passed to self::persistSession() will have an ID
	 * that was originally provided by self::provideSessionInfo().
	 *
	 * If true, the provider may be passed sessions with arbitrary session IDs,
	 * and will be expected to manipulate the request in such a way that future
	 * requests will cause self::provideSessionInfo() to provide a SessionInfo
	 * with that ID.
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
	 * @note For use by \MediaWiki\Session\SessionBackend only
	 * @return bool
	 */
	abstract public function persistsSessionId();

	/**
	 * Indicate whether the user associated with the request can be changed
	 *
	 * If false, any session passed to self::persistSession() will have a user
	 * that was originally provided by self::provideSessionInfo(). Further,
	 * self::provideSessionInfo() may only provide sessions that have a user
	 * already set.
	 *
	 * If true, the provider may be passed sessions with arbitrary users, and
	 * will be expected to manipulate the request in such a way that future
	 * requests will cause self::provideSessionInfo() to provide a SessionInfo
	 * with that ID. This can be as simple as not passing any 'userInfo' into
	 * SessionInfo's constructor, in which case SessionInfo will load the user
	 * from the saved session's metadata.
	 *
	 * For example, a session provider for OAuth or SSL client certificates
	 * would function by matching the OAuth headers or certificate to a
	 * particular user, and thus would return false here since it can't
	 * arbitrarily assign those OAuth credentials or that certificate to a
	 * different user. A session provider that shoves information into cookies,
	 * on the other hand, could easily do so.
	 *
	 * @note For use by \MediaWiki\Session\SessionBackend only
	 * @return bool
	 */
	abstract public function canChangeUser();

	/**
	 * Returns the duration (in seconds) for which users will be remembered when
	 * Session::setRememberUser() is set. Null means setting the remember flag will
	 * have no effect (and endpoints should not offer that option).
	 * @stable to override
	 * @return int|null
	 */
	public function getRememberUserDuration() {
		return null;
	}

	/**
	 * Notification that the session ID was reset
	 *
	 * No need to persist here, persistSession() will be called if appropriate.
	 * @stable to override
	 *
	 * @note For use by \MediaWiki\Session\SessionBackend only
	 * @param SessionBackend $session Session to persist
	 * @param string $oldId Old session ID
	 * @codeCoverageIgnore
	 */
	public function sessionIdWasReset( SessionBackend $session, $oldId ) {
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
	 * the tokens not match, an anonymous user *must* be passed to
	 * SessionInfo::__construct().
	 *
	 * When persisting a user independently of the session ID,
	 * $session->shouldRememberUser() should be checked first. If this returns
	 * false, the user token *must not* be saved to cookies. The user name
	 * and/or ID may be persisted, and should be used to construct an
	 * unverified UserInfo to pass to SessionInfo::__construct().
	 *
	 * A backend that cannot persist session ID or user info should implement
	 * this as a no-op.
	 *
	 * @note For use by \MediaWiki\Session\SessionBackend only
	 * @param SessionBackend $session Session to persist
	 * @param WebRequest $request Request into which to persist the session
	 */
	abstract public function persistSession( SessionBackend $session, WebRequest $request );

	/**
	 * Remove any persisted session from a request/response
	 *
	 * For example, blank and expire any cookies set by self::persistSession().
	 *
	 * A backend that cannot persist session ID or user info should implement
	 * this as a no-op.
	 *
	 * @note For use by \MediaWiki\Session\SessionManager only
	 * @param WebRequest $request Request from which to remove any session data
	 */
	abstract public function unpersistSession( WebRequest $request );

	/**
	 * Prevent future sessions for the user
	 *
	 * If the provider is capable of returning a SessionInfo with a verified
	 * UserInfo for the named user in some manner other than by validating
	 * against $user->getToken(), steps must be taken to prevent that from
	 * occurring in the future. This might add the username to a list, or
	 * it might just delete whatever authentication credentials would allow
	 * such a session in the first place (e.g. remove all OAuth grants or
	 * delete record of the SSL client certificate).
	 *
	 * The intention is that the named account will never again be usable for
	 * normal login (i.e. there is no way to undo the prevention of access).
	 *
	 * Note that the passed user name might not exist locally (i.e.
	 * UserIdentity::getId() === 0); the name should still be
	 * prevented, if applicable.
	 *
	 * @stable to override
	 * @note For use by \MediaWiki\Session\SessionManager only
	 * @param string $username
	 */
	public function preventSessionsForUser( $username ) {
		if ( !$this->canChangeUser() ) {
			throw new \BadMethodCallException(
				__METHOD__ . ' must be implemented when canChangeUser() is false'
			);
		}
	}

	/**
	 * Invalidate existing sessions for a user
	 *
	 * If the provider has its own equivalent of CookieSessionProvider's Token
	 * cookie (and doesn't use User::getToken() to implement it), it should
	 * reset whatever token it does use here.
	 *
	 * @stable to override
	 * @note For use by \MediaWiki\Session\SessionManager only
	 * @param User $user
	 */
	public function invalidateSessionsForUser( User $user ) {
	}

	/**
	 * Return the HTTP headers that need varying on.
	 *
	 * The return value is such that someone could theoretically do this:
	 * @code
	 * foreach ( $provider->getVaryHeaders() as $header => $_ ) {
	 *   $outputPage->addVaryHeader( $header );
	 * }
	 * @endcode
	 *
	 * @stable to override
	 * @note For use by \MediaWiki\Session\SessionManager only
	 * @return array<string,null>
	 */
	public function getVaryHeaders() {
		return [];
	}

	/**
	 * Return the list of cookies that need varying on.
	 * @stable to override
	 * @note For use by \MediaWiki\Session\SessionManager only
	 * @return string[]
	 */
	public function getVaryCookies() {
		return [];
	}

	/**
	 * Get a suggested username for the login form
	 *
	 * This is usually null, but may return the user name of a previous login session
	 * from the same device. This is meant to reduce login friction by reminding
	 * infrequent editors about their chosen username.
	 *
	 * The default CookieSessionProvider stores the last username in a dedicated cookie
	 * that, unlike the "Token" session ID cookie, is not removed during logout
	 * (via User::doLogout, SessionBackend::unpersist, SessionBackedn::save,
	 * and finally  CookieSessionProvider::unpersistSession).
	 *
	 * @stable to override
	 * @note For use by \MediaWiki\Session\SessionBackend only
	 * @param WebRequest $request
	 * @return string|null
	 */
	public function suggestLoginUsername( WebRequest $request ) {
		return null;
	}

	/**
	 * Fetch the rights allowed the user when the specified session is active.
	 *
	 * This is mainly meant for allowing the user to restrict access to the account
	 * by certain methods; you probably want to use this with GrantsInfo. The returned
	 * rights will be intersected with the user's actual rights.
	 *
	 * @stable to override
	 * @param SessionBackend $backend
	 * @return null|string[] Allowed user rights, or null to allow all.
	 */
	public function getAllowedUserRights( SessionBackend $backend ) {
		if ( $backend->getProvider() !== $this ) {
			// Not that this should ever happen...
			throw new InvalidArgumentException( 'Backend\'s provider isn\'t $this' );
		}

		return null;
	}

	/**
	 * Fetch any restrictions imposed on logins or actions when this
	 * session is active.
	 *
	 * @since 1.42
	 * @stable to override
	 *
	 * @param array|null $providerMetadata
	 * @return MWRestrictions|null
	 */
	public function getRestrictions( ?array $providerMetadata ): ?MWRestrictions {
		return null;
	}

	/**
	 * @note Only override this if it makes sense to instantiate multiple
	 *  instances of the provider. Value returned must be unique across
	 *  configured providers. If you override this, you'll likely need to
	 *  override self::describeMessage() as well.
	 * @return string
	 */
	public function __toString() {
		return static::class;
	}

	/**
	 * Return a Message identifying this session type
	 *
	 * This default implementation takes the class name, lowercases it,
	 * replaces backslashes with dashes, and prefixes 'sessionprovider-' to
	 * determine the message key. For example, MediaWiki\Session\CookieSessionProvider
	 * produces 'sessionprovider-mediawiki-session-cookiesessionprovider'.
	 *
	 * @stable to override
	 * @note If self::__toString() is overridden, this will likely need to be
	 *  overridden as well.
	 * @warning This will be called early during MediaWiki startup. Do not
	 *  use $wgUser, $wgLang, $wgOut, the global Parser, or their equivalents via
	 *  RequestContext from this method!
	 * @return Message
	 */
	protected function describeMessage() {
		return wfMessage(
			'sessionprovider-' . str_replace( '\\', '-', strtolower( static::class ) )
		);
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function describe( Language $lang ) {
		$msg = $this->describeMessage();
		$msg->inLanguage( $lang );
		if ( $msg->isDisabled() ) {
			$msg = wfMessage( 'sessionprovider-generic', (string)$this )->inLanguage( $lang );
		}
		return $msg->plain();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function whyNoSession() {
		return null;
	}

	/**
	 * Most session providers require protection against CSRF attacks (usually via CSRF tokens)
	 *
	 * @stable to override
	 * @return bool false
	 */
	public function safeAgainstCsrf() {
		return false;
	}

	/**
	 * Returns true if this provider is exempt from autocreate user permissions check
	 *
	 * By default returns false, meaning this provider respects the normal rights
	 * of anonymous user creation. When true the permission checks will be bypassed
	 * and the user will always be created (subject to other limitations, like read
	 * only db and such).
	 *
	 * @stable to override
	 * @since 1.42
	 */
	public function canAlwaysAutocreate(): bool {
		return false;
	}

	/**
	 * Hash data as a session ID
	 *
	 * Generally this will only be used when self::persistsSessionId() is false and
	 * the provider has to base the session ID on the verified user's identity
	 * or other static data. The SessionInfo should then typically have the
	 * 'forceUse' flag set to avoid persistent session failure if validation of
	 * the stored data fails.
	 *
	 * @param string $data
	 * @param string|null $key Defaults to $this->getConfig()->get( MainConfigNames::SecretKey )
	 * @return string
	 */
	final protected function hashToSessionId( $data, $key = null ) {
		if ( !is_string( $data ) ) {
			throw new InvalidArgumentException(
				'$data must be a string, ' . get_debug_type( $data ) . ' was passed'
			);
		}
		if ( $key !== null && !is_string( $key ) ) {
			throw new InvalidArgumentException(
				'$key must be a string or null, ' . get_debug_type( $key ) . ' was passed'
			);
		}

		$hash = \MWCryptHash::hmac( "$this\n$data",
			$key ?: $this->getConfig()->get( MainConfigNames::SecretKey ), false );
		if ( strlen( $hash ) < 32 ) {
			// Should never happen, even md5 is 128 bits
			// @codeCoverageIgnoreStart
			throw new \UnexpectedValueException( 'Hash function returned less than 128 bits' );
			// @codeCoverageIgnoreEnd
		}
		if ( strlen( $hash ) >= 40 ) {
			$hash = \Wikimedia\base_convert( $hash, 16, 32, 32 );
		}
		return substr( $hash, -32 );
	}

	/**
	 * Throw an exception, later. Needed because during session initialization the framework
	 * isn't quite ready to handle an exception.
	 *
	 * This should be called from provideSessionInfo() to fail in
	 * a user-friendly way when a session mechanism is used in a way it's not supposed to be used
	 * (e.g. invalid credentials or a non-API request when the session provider only supports
	 * API requests), and the returned SessionInfo should be returned by provideSessionInfo().
	 *
	 * @param string $key Key for the error message
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 * @return SessionInfo An anonymous session info with maximum priority, to force an
	 *   anonymous session in case throwing the exception doesn't happen.
	 */
	protected function makeException( $key, ...$params ): SessionInfo {
		$msg = wfMessage( $key, $params );

		if ( defined( 'MW_API' ) ) {
			$this->hookContainer->register(
				'ApiBeforeMain',
				// @phan-suppress-next-line PhanPluginNeverReturnFunction Closures should not get doc
				static function () use ( $msg ) {
					throw ApiUsageException::newWithMessage( null, $msg );
				}
			);
		} elseif ( defined( 'MW_REST_API' ) ) {
			$this->hookContainer->register(
				'RestCheckCanExecute',
				static function ( Module $module, Handler $handler, string $path,
					RequestInterface $request, ?HttpException &$error ) use ( $key, $params )
				{
					$msg = new MessageValue( $key, $params );
					$error = new LocalizedHttpException( $msg, 403 );
					return false;
				}
			);
		} else {
			$this->hookContainer->register(
				'BeforeInitialize',
				// @phan-suppress-next-line PhanPluginNeverReturnFunction Closures should not get doc
				static function () use ( $msg ) {
					RequestContext::getMain()->getOutput()->setStatusCode( 400 );
					throw new ErrorPageError( 'errorpagetitle', $msg );
				}
			);
			// Disable file cache, which would be looked up before the BeforeInitialize hook call.
			$this->hookContainer->register(
				'HTMLFileCache__useFileCache',
				static function () {
					return false;
				}
			);
		}

		$id = $this->hashToSessionId( 'bogus' );
		return new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $this,
			'id' => $id,
			'userInfo' => UserInfo::newAnonymous(),
			'persisted' => false,
		] );
	}

}
