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

use Config;
use MediaWiki\MediaWikiServices;
use StatusValue;

/**
 * Check if the user is blocked, and prevent authentication if so.
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

	public function setConfig( Config $config ) {
		parent::setConfig( $config );

		if ( $this->blockDisablesLogin === null ) {
			$this->blockDisablesLogin = $this->config->get( 'BlockDisablesLogin' );
		}
	}

	public function getAuthenticationRequests( $action, array $options ) {
		return [];
	}

	public function beginSecondaryAuthentication( $user, array $reqs ) {
		// @TODO Partial blocks should not prevent the user from logging in.
		//       see: https://phabricator.wikimedia.org/T208895
		if ( !$this->blockDisablesLogin ) {
			return AuthenticationResponse::newAbstain();
		} elseif ( $user->getBlock() ) {
			return AuthenticationResponse::newFail(
				new \Message( 'login-userblocked', [ $user->getName() ] )
			);
		} else {
			return AuthenticationResponse::newPass();
		}
	}

	public function beginSecondaryAccountCreation( $user, $creator, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

	public function testUserForCreation( $user, $autocreate, array $options = [] ) {
		$block = $user->isBlockedFromCreateAccount();
		if ( $block ) {
			$formatter = MediaWikiServices::getInstance()->getBlockErrorFormatter();

			$language = \RequestContext::getMain()->getUser()->isSafeToLoad() ?
				\RequestContext::getMain()->getLanguage() :
				MediaWikiServices::getInstance()->getContentLanguage();

			$ip = $this->manager->getRequest()->getIP();

			return StatusValue::newFatal(
				$formatter->getMessage( $block, $user, $language, $ip )
			);
		} else {
			return StatusValue::newGood();
		}
	}

}
