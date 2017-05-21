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
		if ( $request->getCheck( $this->mName ) ) {
			$value = $request->getText( $this->mName );
		} else {
			$value = $this->getDefault();
		}

		$usersArray = explode( "\n", $value );
		// Remove empty lines
		$usersArray = array_values( array_filter( $usersArray, function( $username ) {
			return trim( $username ) !== '';
		} ) );
		return implode( "\n", $usersArray );
	}

	public function validate( $value, $alldata ) {
		if ( !$this->mParams['exists'] ) {
			return true;
		}

		if ( is_null( $value ) ) {
			return false;
		}

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

		if ( isset( $this->mParams['default'] ) ) {
			$params['default'] = $this->mParams['default'];
		}

		if ( isset( $this->mParams['placeholder'] ) ) {
			$params['placeholder'] = $this->mParams['placeholder'];
		} else {
			$params['placeholder'] = $this->msg( 'mw-widgets-usersmultiselect-placeholder' )
							->inContentLanguage()
							->plain();
		}

		if ( !is_null( $value ) ) {
			$params['default'] = explode( "\n", $value );
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
