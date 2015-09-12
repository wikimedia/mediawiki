<?php

use MediaWiki\Widget\UserListInputWidget;

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
class HTMLUserListInputField extends HTMLTextField {
	protected function getInputWidget( $params ) {
		$this->mParent->getOutput()->addModules( 'mediawiki.widgets.userlistinputwidget' );

		return new UserListInputWidget( $this->mParams['fieldconfig'] );
	}
}
