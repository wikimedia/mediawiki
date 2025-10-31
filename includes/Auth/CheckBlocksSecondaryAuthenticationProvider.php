<?php
/**
 * @license GPL-2.0-or-later
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
