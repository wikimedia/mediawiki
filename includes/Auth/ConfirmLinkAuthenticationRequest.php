<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Auth;

use InvalidArgumentException;

/**
 * @stable to extend
 * @ingroup Auth
 */
class ConfirmLinkAuthenticationRequest extends AuthenticationRequest {
	/** @var AuthenticationRequest[] */
	protected $linkRequests;

	/** @var string[] List of unique IDs of the confirmed accounts. */
	public $confirmedLinkIDs = [];

	/**
	 * @stable to call
	 * @param AuthenticationRequest[] $linkRequests A list of autolink requests
	 *  which need to be confirmed.
	 */
	public function __construct( array $linkRequests ) {
		if ( !$linkRequests ) {
			throw new InvalidArgumentException( '$linkRequests must not be empty' );
		}
		$this->linkRequests = $linkRequests;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getFieldInfo() {
		$options = [];
		foreach ( $this->linkRequests as $req ) {
			$description = $req->describeCredentials();
			$options[$req->getUniqueId()] = wfMessage(
				'authprovider-confirmlink-option',
				$description['provider']->text(), $description['account']->text()
			);
		}
		return [
			'confirmedLinkIDs' => [
				'type' => 'multiselect',
				'options' => $options,
				'label' => wfMessage( 'authprovider-confirmlink-request-label' ),
				'help' => wfMessage( 'authprovider-confirmlink-request-help' ),
				'optional' => true,
			]
		];
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getUniqueId() {
		$ids = [];
		foreach ( $this->linkRequests as $req ) {
			$ids[] = $req->getUniqueId();
		}
		return parent::getUniqueId() . ':' . implode( '|', $ids );
	}

	/**
	 * Implementing this mainly for use from the unit tests.
	 * @param array $data
	 * @return AuthenticationRequest
	 */
	public static function __set_state( $data ) {
		$ret = new static( $data['linkRequests'] );
		foreach ( $data as $k => $v ) {
			$ret->$k = $v;
		}
		return $ret;
	}
}
