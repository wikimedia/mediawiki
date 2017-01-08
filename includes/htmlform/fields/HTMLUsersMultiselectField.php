<?php

use MediaWiki\Widget\UsersMultiselectWidget;

/**
 * Implements a capsule multiselect input field for users names.
 *
 * Besides the parameters recognized by HTMLFormField, additional recognized
 * parameters are:
 *  users - (optional) Array of usernames to use as preset data
 *  placeholder - (optional) Custom placeholder message for input
 *  exists - (optional, default: false) Whether to validate if all input users exist
 *  ipallowed - (optional, default: false) Are IPs allowed to be entered
 *
 * The result is the array of usernames
 *
 * @note This widget is not likely to remain functional in non-OOUI forms.
 * @note This widget depends on JavaScript support.
 */
class HTMLUsersMultiselectField extends HTMLFormField {

	public function __construct( $params ) {
		$params += [
			'exists' => false,
			'ipallowed' => false,
		];

		parent::__construct( $params );
	}

	public function loadDataFromRequest( $request ) {
		if ( !$request->getCheck( $this->mName ) ) {
			return $this->getDefault();
		}

		return json_decode( $request->getText( $this->mName ) );
	}

	public function validate( $value, $alldata ) {
		if ( !$this->mParams['exists'] ) {
			return true;
		}

		if ( is_null( $value ) ) {
			return false;
		}

		foreach ( $value as $username ) {
			// check, if a user exists with the given username
			$user = User::newFromName( $username, false );

			if ( !$user ) {
				return $this->msg( 'htmlform-user-not-valid', $value );
			}

			if ( $user->getId() === 0 && !( $this->mParams['ipallowed'] && User::isIP( $username ) ) ) {
				return $this->msg( 'htmlform-user-not-exists', $user->getName() );
			}
		}

		return true;
	}

	public function getInputHTML( $values ) {
		return $this->getInputOOUI( $values );
	}

	public function getInputOOUI( $values ) {
		$params = [ 'name' => $this->mName ];

		if ( isset( $this->mParams['users'] ) ) {
			$params['users'] = $this->mParams['users'];
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
