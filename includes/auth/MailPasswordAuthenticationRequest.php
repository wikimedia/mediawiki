<?php

namespace MediaWiki\Auth;

class MailPasswordAuthenticationRequest extends AuthenticationRequest {
	public $mailpassword;

	public function getFieldInfo() {
		return [
			'mailpassword_TODO' => [],
		];
	}
}
