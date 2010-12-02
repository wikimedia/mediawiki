<?php

class SpecialDisableAccount extends SpecialPage {
	function __construct() {
		parent::__construct( 'DisableAccount', 'disableaccount',
					true, array( $this, 'show' ) );
	}

	public function show( $par ) {
		$formFields = array(
			'account' => array(
				'type' => 'text',
				'validation-callback' => array( __CLASS__, 'validateUser' ),
				'label-message' => 'disableaccount-user',
			),
			'comment' => array(
				'type' => 'text',
				'label-message' => 'movereason',
			),
			'confirm' => array(
				'type' => 'toggle',
				'validation-callback' => array( __CLASS__, 'checkConfirmation' ),
				'label-message' => 'disableaccount-confirm',
			),
		);

		$htmlForm = new HTMLForm( $formFields, 'disableaccount' );

		$htmlForm->setSubmitCallback( array( __CLASS__, 'submit' ) );
		$htmlForm->setTitle( $this->getTitle() );

		$htmlForm->show();
	}

	static function validateUser( $field, $allFields ) {
		$u = User::newFromName( $field );

		if ( $u && $u->getID() != 0 ) {
			return true;
		} else {
			return wfMsgExt( 'disableaccount-nosuchuser', 'parseinline', array( $field ) );
		}
	}

	static function checkConfirmation( $field, $allFields ) {
		if ( $field ) {
			return true;
		} else {
			return wfMsgExt( 'disableaccount-mustconfirm', 'parseinline' );
		}
	}

	static function submit( $fields ) {
		$user = User::newFromName( $fields['account'] );

		$user->setPassword( null );
		$user->setEmail( null );
		$user->setToken();
		$user->addGroup( 'inactive' );

		$user->saveSettings();
		$user->invalidateCache();

		$logPage = new LogPage( 'rights' );

		$logPage->addEntry( 'disable', $user->getUserPage(), $fields['comment'] );

		global $wgOut;
		$wgOut->addWikiMsg( 'disableaccount-success', $user->getName() );

		return true;
	}
}