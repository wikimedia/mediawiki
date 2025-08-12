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

use MediaWiki\Request\WebRequest;
use MediaWiki\User\LoggedOutEditToken;

/**
 * Stores and matches CSRF tokens belonging to a given session user.
 *
 * @since 1.37
 * @ingroup Session
 */
class CsrfTokenSet {

	/**
	 * @var string default name for the form field to place the token in.
	 */
	public const DEFAULT_FIELD_NAME = 'wpEditToken';

	private WebRequest $request;

	public function __construct( WebRequest $request ) {
		$this->request = $request;
	}

	/**
	 * Initialize (if necessary) and return a current user CSRF token
	 * value which can be used in edit forms to show that the user's
	 * login credentials aren't being hijacked with a foreign form
	 * submission.
	 *
	 * The $salt for 'edit' and 'csrf' tokens is the default (empty string).
	 *
	 * @param string|string[] $salt Optional function-specific data for hashing
	 * @return Token
	 * @since 1.37
	 */
	public function getToken( $salt = '' ): Token {
		$session = $this->request->getSession();
		if ( !$session->getUser()->isRegistered() ) {
			return new LoggedOutEditToken();
		}
		return $session->getToken( $salt );
	}

	/**
	 * Check if a request contains a value named $valueName with the token value
	 * stored in the session.
	 *
	 * @param string $fieldName
	 * @param string|string[] $salt
	 * @return bool
	 * @since 1.37
	 * @see self::matchCSRFToken
	 */
	public function matchTokenField(
		string $fieldName = self::DEFAULT_FIELD_NAME,
		$salt = ''
	): bool {
		return $this->matchToken( $this->request->getVal( $fieldName ), $salt );
	}

	/**
	 * Check if a value matches with the token value stored in the session.
	 * A match should confirm that the form was submitted from the user's own
	 * login session, not a form submission from a third-party site.
	 *
	 * @param string|null $value
	 * @param string|string[] $salt
	 * @return bool
	 * @since 1.37
	 */
	public function matchToken(
		?string $value,
		$salt = ''
	): bool {
		if ( !$value ) {
			return false;
		}
		$session = $this->request->getSession();
		// It's expensive to generate a new registered user token, so take a shortcut.
		// Anon tokens are cheap and all the same, so we can afford to generate one just to match.
		if ( $session->getUser()->isRegistered() && !$session->hasToken() ) {
			return false;
		}
		return $this->getToken( $salt )->match( $value );
	}
}
