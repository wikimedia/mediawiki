<?php

namespace Miraheze\ManageWiki\Api;

use ApiBase;
use MediaWiki\MediaWikiServices;
use Miraheze\CreateWiki\RemoteWiki;
use Miraheze\ManageWiki\ManageWiki;
use Wikimedia\ParamValidator\ParamValidator;

class ApiModifyServer extends ApiBase {
	public function execute() {
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' );
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		$this->useTransactionalTimeLimit();

		if ( !$config->get( 'CreateWikiUseCustomDomains' ) ) {
			$this->dieWithError( [ 'managewiki-custom-domains-disabled' ] );
		}

		if ( !ManageWiki::checkSetup( 'core' ) ) {
			$this->dieWithError( [ 'managewiki-disabled', 'core' ] );
		}

		$params = $this->extractRequestParams();

		if ( !$permissionManager->userHasRight( $this->getUser(), 'managewiki-restricted' ) ) {
			return;
		}

		if ( !self::validDatabase( $params['wiki'] ) ) {
			$this->dieWithError( [ 'managewiki-invalid-wiki' ] );
		}

		if ( !filter_var( $params['server'], FILTER_VALIDATE_URL ) ) {
			$this->dieWithError( [ 'managewiki-invalid-server' ] );
		}

		$this->setServer( $params['wiki'], $params['server'] );

		$this->getResult()->addValue( null, $this->getModuleName(), $params );
	}

	private function setServer( string $wiki, string $server ) {
		$remoteWiki = new RemoteWiki( $wiki );
		$remoteWiki->setServerName( $server );
		$remoteWiki->commit();
	}

	private static function validDatabase( string $wiki ) {
		$localDatabases = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' )->get( 'LocalDatabases' );
		return in_array( $wiki, $localDatabases );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return [
			'server' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'wiki' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return [
			'action=modifyserver&wiki=database_name&server=https://example.com&token=123ABC'
				=> 'apihelp-modifyserver-example',
		];
	}
}
