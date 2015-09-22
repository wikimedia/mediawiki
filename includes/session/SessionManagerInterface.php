<?php
/**
 * MediaWiki\Session entry point interface
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
use WebRequest;

/**
 * This exists to make IDEs happy, so they don't see the
 * internal-but-required-to-be-public methods on SessionManager.
 *
 * @ingroup Session
 * @since 1.27
 */
interface SessionManagerInterface extends LoggerAwareInterface {
	/**
	 * Fetch the persisted session ID in a request.
	 *
	 * Note this is not the same thing as whether the session associated with
	 * the request is currently persistent, as the session might have been
	 * first made persistent during this request.
	 *
	 * @param WebRequest $request
	 * @return string|null
	 * @throws \\InvalidArgumentException if there are multiple sessions (of
	 *  equal priority) in the request
	 */
	public function getPersistedSessionId( WebRequest $request );

	/**
	 * Fetch the session for a request
	 *
	 * @note You probably want to use $request->getSession() instead. It's more
	 *  efficient and doesn't break FauxRequests or sessions that were changed
	 *  by $this->getSessionById() or $this->getEmptySession().
	 * @param WebRequest $request Any existing associated session will be reset
	 *  to the session corresponding to the data in the request itself.
	 * @return Session
	 * @throws \\HttpError if there are multiple sessions (of equal priority) in the request
	 */
	public function getSessionForRequest( WebRequest $request );

	/**
	 * Fetch a session by ID
	 * @param string $id
	 * @param bool $noEmpty Don't return an empty session
	 * @param WebRequest|null $request Corresponding request. Any existing
	 *  session associated with this WebRequest object will be overwritten.
	 * @return Session|null
	 */
	public function getSessionById( $id, $noEmpty = false, WebRequest $request = null );

	/**
	 * Fetch a new, empty session
	 *
	 * The first provider configured that is able to provide an empty session
	 * will be used.
	 *
	 * @param WebRequest|null $request Corresponding request. Any existing
	 *  session associated with this WebRequest object will be overwritten.
	 * @return Session
	 */
	public function getEmptySession( WebRequest $request = null );

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
	public function getVaryHeaders();

	/**
	 * Return the list of cookies that need varying on.
	 * @return string[]
	 */
	public function getVaryCookies();

}
