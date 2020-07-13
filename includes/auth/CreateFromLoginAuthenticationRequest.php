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
 * @ingroup Auth
 */

namespace MediaWiki\Auth;

/**
 * This transfers state between the login and account creation flows.
 *
 * AuthManager::getAuthenticationRequests() won't return this type, but it
 * may be passed to AuthManager::beginAuthentication() or
 * AuthManager::beginAccountCreation() anyway.
 *
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
class CreateFromLoginAuthenticationRequest extends AuthenticationRequest {
	public $required = self::OPTIONAL;

	/** @var AuthenticationRequest|null */
	public $createRequest;

	/** @var AuthenticationRequest[] */
	public $maybeLink = [];

	/**
	 * @stable to call
	 * @param AuthenticationRequest|null $createRequest A request to use to
	 *  begin creating the account
	 * @param AuthenticationRequest[] $maybeLink Additional accounts to link
	 *  after creation.
	 */
	public function __construct(
		AuthenticationRequest $createRequest = null, array $maybeLink = []
	) {
		$this->createRequest = $createRequest;
		$this->maybeLink = $maybeLink;
		$this->username = $createRequest ? $createRequest->username : null;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getFieldInfo() {
		return [];
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function loadFromSubmission( array $data ) {
		return true;
	}

	/**
	 * Indicate whether this request contains any state for the specified
	 * action.
	 * @stable to override
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @return bool
	 */
	public function hasStateForAction( $action ) {
		switch ( $action ) {
			case AuthManager::ACTION_LOGIN:
				return (bool)$this->maybeLink;
			case AuthManager::ACTION_CREATE:
				return $this->maybeLink || $this->createRequest;
			default:
				return false;
		}
	}

	/**
	 * Indicate whether this request contains state for the specified
	 * action sufficient to replace other primary-required requests.
	 * @stable to override
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @return bool
	 */
	public function hasPrimaryStateForAction( $action ) {
		switch ( $action ) {
			case AuthManager::ACTION_CREATE:
				return (bool)$this->createRequest;
			default:
				return false;
		}
	}
}
