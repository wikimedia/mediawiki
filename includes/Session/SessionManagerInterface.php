<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Session;

use MediaWiki\Request\WebRequest;
use MediaWiki\User\User;
use Psr\Log\LoggerAwareInterface;

/**
 * MediaWiki\Session entry point interface
 *
 * This exists to make IDEs happy, so they don't see the
 * internal-but-required-to-be-public methods on SessionManager.
 *
 * @since 1.27
 * @ingroup Session
 */
interface SessionManagerInterface extends LoggerAwareInterface {
	/**
	 * Fetch the session for a request (or a new empty session if none is
	 * attached to it)
	 *
	 * @internal For WebRequest only. Use $request->getSession() instead. It's more
	 *  efficient and doesn't break FauxRequests or sessions that were changed
	 *  by $this->getSessionById() or $this->getEmptySession().
	 * @param WebRequest $request Any existing associated session will be reset
	 *  to the session corresponding to the data in the request itself.
	 * @return Session
	 * @throws \OverflowException if there are multiple sessions tied for top
	 *  priority in the request. Exception has a property "sessionInfos"
	 *  holding the SessionInfo objects for the sessions involved.
	 */
	public function getSessionForRequest( WebRequest $request );

	/**
	 * Fetch a session by ID
	 *
	 * @param string $id
	 * @param bool $create If no session exists for $id, try to create a new one.
	 *  May still return null if a session for $id exists but cannot be loaded.
	 * @param WebRequest|null $request Corresponding request. Any existing
	 *  session associated with this WebRequest object will be overwritten.
	 * @return Session|null
	 */
	public function getSessionById( $id, $create = false, ?WebRequest $request = null );

	/**
	 * Create a new, empty session
	 *
	 * The first provider configured that is able to provide an empty session
	 * will be used.
	 *
	 * @param WebRequest|null $request Corresponding request. Any existing
	 *  session associated with this WebRequest object will be overwritten.
	 * @return Session
	 */
	public function getEmptySession( ?WebRequest $request = null );

	/**
	 * Invalidate sessions for a user
	 *
	 * After calling this, existing sessions should be invalid. For mutable
	 * session providers, this generally means the user has to log in again;
	 * for immutable providers, it generally means the loss of session data.
	 */
	public function invalidateSessionsForUser( User $user );

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
	 * @return array<string,null>
	 */
	public function getVaryHeaders();

	/**
	 * Return the list of cookies that need varying on.
	 * @return string[]
	 */
	public function getVaryCookies();

}
