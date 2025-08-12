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

namespace MediaWiki\Auth;

use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;

/**
 * Check if the user is blocked, and prevent authentication if so.
 *
 * Not all scenarios are covered by this class, AuthManager does some block checks itself
 * via AuthManager::authorizeCreateAccount().
 *
 * @ingroup Auth
 * @since 1.27
 */
class CheckBlocksSecondaryAuthenticationProvider extends AbstractSecondaryAuthenticationProvider {

	/** @var bool */
	protected $blockDisablesLogin = null;

	/**
	 * @param array $params
	 *  - blockDisablesLogin: (bool) Whether blocked accounts can log in,
	 *    defaults to $wgBlockDisablesLogin
	 */
	public function __construct( $params = [] ) {
		if ( isset( $params['blockDisablesLogin'] ) ) {
			$this->blockDisablesLogin = (bool)$params['blockDisablesLogin'];
		}
	}

	/** @inheritDoc */
	protected function postInitSetup() {
		$this->blockDisablesLogin ??= $this->config->get( MainConfigNames::BlockDisablesLogin );
	}

	/** @inheritDoc */
	public function getAuthenticationRequests( $action, array $options ) {
		return [];
	}

	/** @inheritDoc */
	public function beginSecondaryAuthentication( $user, array $reqs ) {
		if ( !$this->blockDisablesLogin ) {
			return AuthenticationResponse::newAbstain();
		}
		$block = $user->getBlock();
		// Ignore IP blocks and partial blocks, $wgBlockDisablesLogin was meant for
		// blocks banning specific users.
		if ( $block && $block->isSitewide() && $block->isBlocking( $user ) ) {
			return AuthenticationResponse::newFail(
				new Message( 'login-userblocked', [ $user->getName() ] )
			);
		} else {
			return AuthenticationResponse::newPass();
		}
	}

	/** @inheritDoc */
	public function beginSecondaryAccountCreation( $user, $creator, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

}
