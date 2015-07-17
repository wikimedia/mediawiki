<?php

use MediaWiki\Widget\UserInputWidget;

/**
 * Implements a text input field for user names.
 * Automatically auto-completes if using the OOUI display format.
 *
 * FIXME: Does not work for forms that support GET requests.
 *
 * Optional parameters:
 * 'exists' - Whether to validate that the user already exists
 *
 * @since 1.26
 */
class HTMLUserTextField extends HTMLTextField {
	public function __construct( $params ) {
		$params += array(
			'exists' => false,
		);

		parent::__construct( $params );
	}

	public function validate( $value, $alldata ) {
		// check, if a user exists with the given username
		$user = User::newFromName( $value );

		if ( $this->mParams['exists'] && ( !$user || $user->getId() === 0 ) ) {
			return $this->msg( 'htmlform-user-not-exists', $user->getName() )->parse();
		}

		return parent::validate( $value, $alldata );
	}

	protected function getInputWidget( $params ) {
		$this->mParent->getOutput()->addModules( 'mediawiki.widgets' );

		return new UserInputWidget( $params );
	}
}
