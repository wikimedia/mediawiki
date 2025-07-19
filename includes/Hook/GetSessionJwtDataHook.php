<?php

namespace MediaWiki\Hook;

use MediaWiki\Session\SessionManager;
use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetSessionJwtData" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface GetSessionJwtDataHook {

	/**
	 * Called when a JWT session token is created, can modify the information stored within.
	 * JWT tokens are used to authenticate requests for some session types. The details depend
	 * on the session type.
	 *
	 * Might be called both when the JWT is created (for mutable sessions during
	 * SessionProvider::persistSession(); for immutable sessions, where session tokens are
	 * created out-of-band, in the business logic of some API / special page / etc), and when
	 * the JWT is verified (during SessionProvider::provideSessionInfo() and maybe
	 * refreshSessionInfo()).
	 *
	 * Note that session verification happens before the user is autocreated, so you should
	 * not rely on $session->getUser()->isAnon() and similar.
	 *
	 * @param UserIdentity|null $user The user who is the subject of the claim. Guaranteed to not be
	 *   an IP user. Null if the session is anonymous.
	 * @param array &$jwtData A set of JWT claims that the hook can alter or expand.
	 *   Claim values are JSON-compatible data structures (ie. scalar values or array structures
	 *   where the leafs are scalar values).
	 * @return void This hook must not abort, it must return no value
	 *
	 * @since 1.45
	 * @see SessionManager::getJwtData()
	 */
	public function onGetSessionJwtData( ?UserIdentity $user, array &$jwtData ): void;

}
