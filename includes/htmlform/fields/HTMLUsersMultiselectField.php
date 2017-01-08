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
		if ( !$request->getCheck( $this->mName ) ) {
			return $this->getDefault();
		}

		$inputText = $request->getText( $this->mName );
		$usersArray = json_decode( $inputText );
		if ( json_last_error() == JSON_ERROR_NONE ) {
			return $usersArray;
		} else {
			return explode( PHP_EOL, $inputText );
		}
	}

	public function validate( $value, $alldata ) {
		if ( !$this->mParams['exists'] ) {
			return true;
		}

		if ( is_null( $value ) ) {
			return false;
		}

		foreach ( $value as $username ) {
			$result = parent::validate( $username, $alldata );
			if ( $result !== true ) {
				return $result;
			}
		}

		return true;
	}

	public function getInputHTML( $values ) {
		return $this->getInputOOUI( $values );
	}

	public function getInputOOUI( $values ) {
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

		if ( !is_null( $values ) ) {
			$params['users'] = $values;
		}

		return new UsersMultiselectWidget( $params );
	}

	protected function shouldInfuseOOUI() {
		return true;
	}

	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.UsersMultiselectWidget' ];
	}

}
