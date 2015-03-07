<?php
/**
 * Null authn session
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
 * @ingroup Auth
 */

use Psr\Log\NullLogger;

/**
 * An authn session that can never persist or return a user
 *
 * @ingroup Auth
 * @since 1.26
 */
class NullAuthnSession extends AuthnSession {
	public function __construct() {
		parent::__construct(
			new HashBagOStuff(), new NullLogger(), 'NonPersistentSession', 0
		);
	}

	protected function canResetSessionKey() {
		return false;
	}

	public function getSessionUserInfo() {
		return array( 0, null, null );
	}

	public function canSetSessionUserInfo() {
		return false;
	}
}
