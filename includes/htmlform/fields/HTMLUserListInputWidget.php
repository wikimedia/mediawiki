<?php

use MediaWiki\Widget\UserListInputWidget;

/**
 * Implements an user input field, that autocompletes the
 * username while the user types in some characters.
 *
 * @since 1.26
 */
class HTMLUserListInputField extends HTMLTextField {
	protected function getInputWidget( $params ) {
		$this->mParent->getOutput()->addModules( 'mediawiki.widgets.userlistinputwidget' );

		return new UserListInputWidget( $this->mParams['fieldconfig'] );
	}
}
