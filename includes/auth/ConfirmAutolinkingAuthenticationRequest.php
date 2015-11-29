<?php

namespace MediaWiki\Auth;

class ConfirmAutolinkingAuthenticationRequest extends AuthenticationRequest {
	/** @var AuthenticationRequest[] */
	protected $linkRequests;

	/** @var string[] List of unique IDs of the confirmed accounts. */
	protected $confirmAutolinking = array();

	/**
	 * @param AuthenticationRequest[] $linkRequests A list of autolink requests
	 *  which need to be confirmed. Should not be empty.
	 */
	public function __construct( array $linkRequests ) {
		$this->linkRequests = $linkRequests;
	}

	public function getFieldInfo() {
		$options = array();
		foreach ( $this->linkRequests as $req ) {
			$description = $req->describe();
			$options[$req->getUniqueId()] = wfMessage( 'authprovider-confirmautolinking-optionname',
				$description['provider'], $description['account'] );
		}
		return array(
			'confirmAutolinking' => array(
				'type' => 'multiselect',
				'options' => $options,
				'label' => 'authprovider-confirmautolinking-request-label',
				'help' => 'authprovider-confirmautolinking-request-help',
				'optional' => true,
			)
		);
	}

	public function getUniqueId() {
		return parent::getUniqueId() . ':' . implode( '|', array_map( function ( $req ) {
			return $req->getUniqueId();
		}, $this->linkRequests ) );
	}

	/**
	 * Returns the unique IDs of the requests which represent the accounts that have been confirmed.
	 * @return string[]
	 */
	public function getConfirmedAccounts() {
		return $this->confirmAutolinking;
	}
}
