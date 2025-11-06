<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Session;

use InvalidArgumentException;
use Stringable;

/**
 * Value object returned by SessionProvider
 *
 * This holds the data necessary to construct a Session.
 * May require services to be injected into the constructor.
 *
 * @newable
 * @since 1.27
 * @ingroup Session
 */
class SessionInfo implements Stringable {
	/** Minimum allowed priority */
	public const MIN_PRIORITY = 1;

	/** Maximum allowed priority */
	public const MAX_PRIORITY = 100;

	/** @var SessionProvider|null */
	private $provider;

	/** @var string */
	private $id;

	/** @var int */
	private $priority;

	/** @var UserInfo|null */
	private $userInfo = null;

	/** @var bool */
	private $persisted = false;

	/** @var bool */
	private $remembered = false;

	/** @var bool */
	private $forceHTTPS = false;

	/** @var bool */
	private $idIsSafe = false;

	/** @var bool */
	private $needsRefresh = false;

	/** @var bool */
	private $forceUse = false;

	/** @var array|null */
	private $providerMetadata = null;

	/**
	 * @stable to call
	 *
	 * @param int $priority Session priority
	 * @param array $data
	 *  - provider: (SessionProvider|null) If not given, the provider will be
	 *    determined from the saved session data.
	 *  - id: (string|null) Session ID
	 *  - userInfo: (UserInfo|null) User known from the request. If
	 *    $provider->canChangeUser() is false, a verified user
	 *    must be provided.
	 *  - persisted: (bool) Whether this session was persisted
	 *  - remembered: (bool) Whether the verified user was remembered.
	 *    Defaults to true.
	 *  - forceHTTPS: (bool) Whether to force HTTPS for this session. This is
	 *    ignored if $wgForceHTTPS is true.
	 *  - metadata: (array) Provider metadata, to be returned by
	 *    Session::getProviderMetadata(). See SessionProvider::mergeMetadata()
	 *    and SessionProvider::refreshSessionInfo().
	 *  - idIsSafe: (bool) Set true if the 'id' did not come from the user.
	 *    Generally you'll use this from SessionProvider::newEmptySession(),
	 *    and not from any other method.
	 *  - needsRefresh: (bool, since 1.45) Set to true if the session metadata needs to be
	 *    re-persisted for some reason. This will result in Session::save() being called.
	 *  - forceUse: (bool) Set true if the 'id' is from
	 *    SessionProvider::hashToSessionId() to delete conflicting session
	 *    store data instead of discarding this SessionInfo. Ignored unless
	 *    both 'provider' and 'id' are given.
	 *  - copyFrom: (SessionInfo) SessionInfo to copy other data items from.
	 */
	public function __construct( $priority, array $data ) {
		if ( $priority < self::MIN_PRIORITY || $priority > self::MAX_PRIORITY ) {
			throw new InvalidArgumentException( 'Invalid priority' );
		}

		if ( isset( $data['copyFrom'] ) ) {
			$from = $data['copyFrom'];
			if ( !$from instanceof SessionInfo ) {
				throw new InvalidArgumentException( 'Invalid copyFrom' );
			}
			$data += [
				'provider' => $from->provider,
				'id' => $from->id,
				'userInfo' => $from->userInfo,
				'persisted' => $from->persisted,
				'remembered' => $from->remembered,
				'forceHTTPS' => $from->forceHTTPS,
				'metadata' => $from->providerMetadata,
				'idIsSafe' => $from->idIsSafe,
				'needsRefresh' => $from->needsRefresh,
				'forceUse' => $from->forceUse,
				// @codeCoverageIgnoreStart
			];
			// @codeCoverageIgnoreEnd
		} else {
			$data += [
				'provider' => null,
				'id' => null,
				'userInfo' => null,
				'persisted' => false,
				'remembered' => true,
				'forceHTTPS' => false,
				'metadata' => null,
				'idIsSafe' => false,
				'needsRefresh' => false,
				'forceUse' => false,
				// @codeCoverageIgnoreStart
			];
			// @codeCoverageIgnoreEnd
		}

		if ( $data['id'] !== null && !SessionManager::validateSessionId( $data['id'] ) ) {
			throw new InvalidArgumentException( 'Invalid session ID' );
		} elseif ( $data['userInfo'] !== null && !$data['userInfo'] instanceof UserInfo ) {
			throw new InvalidArgumentException( 'Invalid userInfo' );
		} elseif ( !$data['provider'] && $data['id'] === null ) {
			throw new InvalidArgumentException(
				'Must supply an ID when no provider is given'
			);
		} elseif ( $data['metadata'] !== null && !is_array( $data['metadata'] ) ) {
			throw new InvalidArgumentException( 'Invalid metadata' );
		} elseif ( $data['needsRefresh'] && !$data['persisted'] ) {
			throw new InvalidArgumentException( 'Cannot refresh an unpersisted session' );
		}

		$this->provider = $data['provider'];
		if ( $data['id'] !== null ) {
			$this->id = $data['id'];
			$this->idIsSafe = $data['idIsSafe'];
			$this->forceUse = $data['forceUse'] && $this->provider;
		} else {
			$this->id = $this->provider->getManager()->generateSessionId();
			$this->idIsSafe = true;
			$this->forceUse = false;
		}
		$this->needsRefresh = (bool)$data['needsRefresh'];
		$this->priority = (int)$priority;
		$this->userInfo = $data['userInfo'];
		$this->persisted = (bool)$data['persisted'];
		if ( $data['provider'] !== null ) {
			if ( $this->userInfo !== null && !$this->userInfo->isAnon() && $this->userInfo->isVerified() ) {
				$this->remembered = (bool)$data['remembered'];
			}
			$this->providerMetadata = $data['metadata'];
		}
		$this->forceHTTPS = (bool)$data['forceHTTPS'];
	}

	/**
	 * Return the provider
	 * @return SessionProvider|null
	 */
	final public function getProvider() {
		return $this->provider;
	}

	/**
	 * Return the session ID
	 * @return string
	 */
	final public function getId() {
		return $this->id;
	}

	/**
	 * Indicate whether the ID is "safe"
	 *
	 * The ID is safe in the following cases:
	 * - The ID was randomly generated by the constructor.
	 * - The ID was found in the backend data store.
	 * - $this->getProvider()->persistsSessionId() is false.
	 * - The constructor was explicitly told it's safe using the 'idIsSafe'
	 *   parameter.
	 *
	 * @return bool
	 */
	final public function isIdSafe() {
		return $this->idIsSafe;
	}

	/**
	 * Indicate that the session metadata needs to be re-persisted.
	 * This will result in Session::save() being called.
	 * @since 1.45
	 */
	final public function needsRefresh(): bool {
		return $this->needsRefresh;
	}

	/**
	 * Force use of this SessionInfo if validation fails
	 *
	 * The normal behavior is to discard the SessionInfo if validation against
	 * the data stored in the session store fails. If this returns true,
	 * SessionManager will instead delete the session store data so this
	 * SessionInfo may still be used. This is important for providers which use
	 * deterministic IDs and so cannot just generate a random new one.
	 *
	 * @return bool
	 */
	final public function forceUse() {
		return $this->forceUse;
	}

	/**
	 * Return the priority
	 * @return int
	 */
	final public function getPriority() {
		return $this->priority;
	}

	/**
	 * Return the user
	 * @return UserInfo|null
	 */
	final public function getUserInfo() {
		return $this->userInfo;
	}

	/**
	 * Return whether the session is persisted
	 * @return bool
	 */
	final public function wasPersisted() {
		return $this->persisted;
	}

	/**
	 * Return provider metadata
	 * @return array|null
	 */
	final public function getProviderMetadata() {
		return $this->providerMetadata;
	}

	/**
	 * Return whether the user was remembered
	 *
	 * For providers that can persist the user separately from the session,
	 * the human using it may not actually *want* that to be done. For example,
	 * a cookie-based provider can set cookies that are longer-lived than the
	 * backend session data, but on a public terminal the human likely doesn't
	 * want those cookies set.
	 *
	 * This is false unless a non-anonymous verified user was passed to
	 * the SessionInfo constructor by the provider, and the provider didn't
	 * pass false for the 'remembered' data item.
	 *
	 * @return bool
	 */
	final public function wasRemembered() {
		return $this->remembered;
	}

	/**
	 * Whether this session should only be used over HTTPS. This should be
	 * ignored if $wgForceHTTPS is true.
	 *
	 * @return bool
	 */
	final public function forceHTTPS() {
		return $this->forceHTTPS;
	}

	public function __toString() {
		return '[' . $this->getPriority() . ']' .
			( $this->getProvider() ?: 'null' ) .
			( $this->userInfo ?: '<null>' ) . $this->getId();
	}

	/**
	 * Compare two SessionInfo objects by priority
	 * @param SessionInfo $a
	 * @param SessionInfo $b
	 * @return int Negative if $a < $b, positive if $a > $b, zero if equal
	 */
	public static function compare( $a, $b ) {
		return $a->getPriority() <=> $b->getPriority();
	}

}
