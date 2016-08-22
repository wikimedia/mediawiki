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
		$params += [
			'exists' => false,
			'ipallowed' => false,
		];

		parent::__construct( $params );
	}

	public function validate( $value, $alldata ) {
		// check, if a user exists with the given username
		$user = User::newFromName( $value, false );

		if ( !$user ) {
			return $this->msg( 'htmlform-user-not-valid', $value )->parse();
		} elseif (
			( $this->mParams['exists'] && $user->getId() === 0 ) &&
			!( $this->mParams['ipallowed'] && User::isIP( $value ) )
		) {
			return $this->msg( 'htmlform-user-not-exists', $user->getName() )->parse();
		}

		return parent::validate( $value, $alldata );
	}

	protected function getInputWidget( $params ) {
		return new UserInputWidget( $params );
	}

	protected function shouldInfuseOOUI() {
		return true;
	}

	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.UserInputWidget' ];
	}

	public function getInputHtml( $value ) {
		// add the required module and css class for user suggestions in non-OOUI mode
		$this->mParent->getOutput()->addModules( 'mediawiki.userSuggest' );
		$this->mClass .= ' mw-autocomplete-user';

		// return parent html
		return parent::getInputHTML( $value );
	}
}
