<?php

namespace MediaWiki\Auth;

/**
 * Authentication request for the reason given for account creation.
 *
 * Used to add entries to Special:Log and for RCFeed notifications.
 *
 * @stable to extend
 * @ingroup Auth
 */
class CreationReasonAuthenticationRequest extends AuthenticationRequest {
	/** @var string Account creation reason (only used when creating for someone else) */
	public $reason;

	/** @inheritDoc */
	public $required = self::OPTIONAL;

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getFieldInfo() {
		return [
			'reason' => [
				'type' => 'string',
				'label' => wfMessage( 'createacct-reason' ),
				'help' => wfMessage( 'createacct-reason-help' ),
			],
		];
	}
}
