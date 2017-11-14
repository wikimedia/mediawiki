<?php

use MediaWiki\Widget\UsersMultiselectWidget;

/**
 * Implements a capsule multiselect input field for user names.
 *
 * Besides the parameters recognized by HTMLUserTextField, additional recognized
 * parameters are:
 *  default - (optional) Array of usernames to use as preset data
 *  placeholder - (optional) Custom placeholder message for input
 *
 * The result is the array of usernames
 *
 * @note This widget is not likely to remain functional in non-OOUI forms.
 */
class HTMLUsersMultiselectField extends HTMLUserTextField {
	public function loadDataFromRequest( $request ) {
		$value = $request->getText( $this->mName, $this->getDefault() );

		$usersArray = explode( "\n", $value );
		// Remove empty lines
		$usersArray = array_values( array_filter( $usersArray, function ( $username ) {
			return trim( $username ) !== '';
		} ) );
		// This function is expected to return a string
		return implode( "\n", $usersArray );
	}

	public function validate( $value, $alldata ) {
		if ( !$this->mParams['exists'] ) {
			return true;
		}

		if ( is_null( $value ) ) {
			return false;
		}

		// $value is a string, because HTMLForm fields store their values as strings
		$usersArray = explode( "\n", $value );
		foreach ( $usersArray as $username ) {
			$result = parent::validate( $username, $alldata );
			if ( $result !== true ) {
				return $result;
			}
		}

		return true;
	}

	public function getInputHTML( $value ) {
		$this->mParent->getOutput()->enableOOUI();
		return $this->getInputOOUI( $value );
	}

	public function getInputOOUI( $value ) {
		$params = [ 'name' => $this->mName ];

		if ( isset( $this->mParams['id'] ) ) {
			$params['id'] = $this->mParams['id'];
		}

		if ( isset( $this->mParams['disabled'] ) ) {
			$params['disabled'] = $this->mParams['disabled'];
		}

		if ( isset( $this->mParams['default'] ) ) {
			$params['default'] = $this->mParams['default'];
		}

		if ( isset( $this->mParams['placeholder'] ) ) {
			$params['placeholder'] = $this->mParams['placeholder'];
		} else {
			$params['placeholder'] = $this->msg( 'mw-widgets-usersmultiselect-placeholder' )->plain();
		}

		if ( !is_null( $value ) ) {
			// $value is a string, but the widget expects an array
			$params['default'] = $value === '' ? [] : explode( "\n", $value );
		}

		// Make the field auto-infusable when it's used inside a legacy HTMLForm rather than OOUIHTMLForm
		$params['infusable'] = true;
		$params['classes'] = [ 'mw-htmlform-field-autoinfuse' ];
		$widget = new UsersMultiselectWidget( $params );
		$widget->setAttributes( [ 'data-mw-modules' => implode( ',', $this->getOOUIModules() ) ] );

		return $widget;
	}

	protected function shouldInfuseOOUI() {
		return true;
	}

	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.UsersMultiselectWidget' ];
	}

}
