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

use MediaWiki\Block\AbstractBlock;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use StatusValue;

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
				new \Message( 'login-userblocked', [ $user->getName() ] )
			);
		} else {
			return AuthenticationResponse::newPass();
		}
	}

	/** @inheritDoc */
	public function beginSecondaryAccountCreation( $user, $creator, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

	/** @inheritDoc */
	public function testUserForCreation( $user, $autocreate, array $options = [] ) {
		// isBlockedFromCreateAccount() does not return non-accountcreation blocks, but we need them
		// in the $wgBlockDisablesLogin case; getBlock() is unreliable for IP blocks. So we need both.
		$blocks = [
			'local-createaccount' => $user->isBlockedFromCreateAccount(),
			'local' => $user->getBlock(),
		];
		foreach ( $blocks as $block ) {
			/** @var AbstractBlock $block */
			if ( $block && $block->isSitewide()
				// This method is for checking a given account/username, not the current user, so
				// ignore IP blocks; they will be checked elsewhere via authorizeCreateAccount().
				// FIXME: special-case autocreation which doesn't do that check. Should it?
				&& ( $block->isBlocking( $user ) || $autocreate )
				&& (
					// Should blocks that prevent account creation also prevent autocreation?
					// We'll go with yes here.
					$block->isCreateAccountBlocked()
					// A successful autocreation means the user is logged in, so we must make sure to
					// honor $wgBlockDisablesLogin. If it's enabled, sitewide blocks are expected to
					// prevent login regardless of their flags.
					|| ( $autocreate && $this->blockDisablesLogin )
				)
				// FIXME: ideally on autocreation we'd figure out if the user has the ipblock-exempt
				//   or globalblock-exempt right via some central authorization system like
				//   CentralAuth global groups. But at this point the local account doesn't exist
				//   yet so there is no way to do that. There should probably be some separate hook
				//   to fetch user rights for a central user.
				// FIXME: T249444: there should probably be a way to force autocreation through blocks
			) {
				$formatter = MediaWikiServices::getInstance()->getBlockErrorFormatter();

				$context = \RequestContext::getMain();

				$language = $context->getUser()->isSafeToLoad() ?
					\RequestContext::getMain()->getLanguage() :
					MediaWikiServices::getInstance()->getContentLanguage();

				$ip = $context->getRequest()->getIP();

				return StatusValue::newFatal(
					$formatter->getMessage( $block, $user, $language, $ip )
				);
			}
		}
		return StatusValue::newGood();
	}

}
