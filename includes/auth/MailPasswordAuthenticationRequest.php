<?php

namespace MediaWiki\Auth;

class MailPasswordAuthenticationRequest extends AuthenticationRequest {
	/** @var boolean Whether to send an auto-generated password via email */
	public $mailpassword;

	public function getFieldInfo() {
		return [
			'mailpassword' => [
				'type' => 'checkbox',
				'label' => wfMessage( 'createaccountmail' ),
				'help' => wfMessage( 'createaccountmail-help' ),
				'optional' => true,
			],
		];
	}
}
