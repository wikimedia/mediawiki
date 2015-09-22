<?php

/**
 * Authentication request for the reason given for account creation.
 * Used in logs and for notification.
 */
class CreationReasonAuthenticationRequest extends AuthenticationRequest {
	/** @var string */
	public $reason;

	public static function getFieldInfo() {
		return array(
			'reason' => array(
				'type' => 'string',
				'label' => 'createacct-reason',
				'help' => 'authmanager-reason-help', // TODO
			),
		);
	}
}
