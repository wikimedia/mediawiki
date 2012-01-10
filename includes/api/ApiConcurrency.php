<?php

/**
 * API module that handles cooperative locking of web resources
 */
class ApiConcurrency extends ApiBase {
	
	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		global $wgUser;

		$this->checkPermission( $wgUser );

		$params = $this->extractRequestParams();

		$res = array();

		$concurrencyCheck = new ConcurrencyCheck( $params['resourcetype'], $wgUser );

		switch ( $params['ccaction'] ) {
			case 'checkout':
			case 'checkin':
				if ( $concurrencyCheck->$params['ccaction']( $params['record'] ) ) {
					$res['result'] = 'success';	
				}
				else {
					$res['result'] = 'failure';	
				}
				break;

			default:
				ApiBase::dieDebug( __METHOD__, "Unhandled concurrency action: {$params['ccaction']}" );
		}

		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'resourcetype' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'record' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => true
			),
			'token' => null,
			'expiry' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'ccaction' => array(
				ApiBase::PARAM_REQUIRED => true,
				ApiBase::PARAM_TYPE => array(
					'checkout',
					'checkin',
				),
			),
		);
	}

	public function getParamDescription() {
		return array(
			'resourcetype' => 'the resource type for concurrency check',
			'record' => 'an unique identifier for a record of the defined resource type',
			'expiry' => 'the time interval for expiration',
			'ccaction' => 'the action for concurrency check',
		);
	}

	public function getDescription() {
		return 'Get/Set a concurrency check for a web resource type';
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiConcurrency.php $';
	}
	
	private function checkPermission( $user ) {
		if ( $user->isAnon() ) {
			$this->dieUsage( "You don't have permission to do that", 'permission-denied' );
		}
		if ( $user->isBlocked( false ) ) {
			$this->dieUsageMsg( array( 'blockedtext' ) );
		}
	}

}