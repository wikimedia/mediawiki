<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Auth;

use MediaWiki\Language\RawMessage;

/**
 * This is a value object for authentication requests with a username, password, and domain
 *
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
class PasswordDomainAuthenticationRequest extends PasswordAuthenticationRequest {
	/** @var string[] Domains available */
	private $domainList;

	/** @var string|null */
	public $domain = null;

	/**
	 * @stable to call
	 * @param string[] $domainList List of available domains
	 */
	public function __construct( array $domainList ) {
		$this->domainList = $domainList;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getFieldInfo() {
		$ret = parent::getFieldInfo();

		// Only add a domain field if we have the username field included
		if ( isset( $ret['username'] ) ) {
			$ret['domain'] = [
				'type' => 'select',
				'options' => [],
				'label' => wfMessage( 'yourdomainname' ),
				'help' => wfMessage( 'authmanager-domain-help' ),
			];
			foreach ( $this->domainList as $domain ) {
				$ret['domain']['options'][$domain] = new RawMessage( '$1', [ $domain ] );
			}
		}

		return $ret;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function describeCredentials() {
		return [
			'provider' => wfMessage( 'authmanager-provider-password-domain' ),
			'account' => wfMessage(
				'authmanager-account-password-domain', [ $this->username, $this->domain ]
			),
		];
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $data
	 * @return AuthenticationRequest|static
	 */
	public static function __set_state( $data ) {
		$ret = new static( $data['domainList'] );
		foreach ( $data as $k => $v ) {
			if ( $k !== 'domainList' ) {
				$ret->$k = $v;
			}
		}
		return $ret;
	}
}
