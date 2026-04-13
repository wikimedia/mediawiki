<?php

namespace MediaWiki\Auth;

/**
 * An authentication request that allows users with sufficiently high privileges
 * to create a new account with a username that was previously used by a renamed account.
 */
class PreviouslyRenamedAccountAuthenticationRequest extends AuthenticationRequest {
	public bool $allowPreviouslyRenamedAccount = false;

	/**
	 * @inheritDoc
	 */
	public function getFieldInfo() {
		return [
			'allowPreviouslyRenamedAccount' => [
				'type' => 'checkbox',
				'label' => wfMessage( 'allow-previously-renamed-account' ),
				'help' => wfMessage( 'allow-previously-renamed-account-help' ),
				'optional' => true,
			],
		];
	}
}
