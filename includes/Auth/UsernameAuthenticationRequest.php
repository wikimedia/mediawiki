<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Auth;

/**
 * AuthenticationRequest to ensure something with a username is present
 *
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
class UsernameAuthenticationRequest extends AuthenticationRequest {

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getFieldInfo() {
		return [
			'username' => [
				'type' => 'string',
				'label' => wfMessage( 'userlogin-yourname' ),
				'help' => wfMessage( 'authmanager-username-help' ),
			],
		];
	}
}
