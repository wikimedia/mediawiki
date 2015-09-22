<?php

namespace MediaWiki\Auth;


class MailPasswordAuthenticationRequest extends AuthenticationRequest {
	public $mailpassword;

	public function getFieldInfo() {
		return array(
			'mailpassword_TODO' => array(),
		);
	}
}
