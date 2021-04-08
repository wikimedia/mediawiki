<?php
/**
 * Copyright © 2016 Wikimedia Foundation and contributors
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @since 1.27
 */

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\CreateFromLoginAuthenticationRequest;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

/**
 * Helper class for AuthManager-using API modules. Intended for use via
 * composition.
 *
 * @ingroup API
 */
class ApiAuthManagerHelper {

	/** @var ApiBase API module, for context and parameters */
	private $module;

	/** @var string Message output format */
	private $messageFormat;

	/** @var AuthManager */
	private $authManager;

	/**
	 * @param ApiBase $module API module, for context and parameters
	 * @param AuthManager|null $authManager
	 */
	public function __construct( ApiBase $module, AuthManager $authManager = null ) {
		$this->module = $module;

		$params = $module->extractRequestParams();
		$this->messageFormat = $params['messageformat'] ?? 'wikitext';
		$this->authManager = $authManager ?: MediaWikiServices::getInstance()->getAuthManager();
	}

	/**
	 * Static version of the constructor, for chaining
	 * @param ApiBase $module API module, for context and parameters
	 * @param AuthManager|null $authManager
	 * @return ApiAuthManagerHelper
	 */
	public static function newForModule( ApiBase $module, AuthManager $authManager = null ) {
		return new self( $module, $authManager );
	}

	/**
	 * Format a message for output
	 * @param array &$res Result array
	 * @param string $key Result key
	 * @param Message $message
	 */
	private function formatMessage( array &$res, $key, Message $message ) {
		switch ( $this->messageFormat ) {
			case 'none':
				break;

			case 'wikitext':
				$res[$key] = $message->setContext( $this->module )->text();
				break;

			case 'html':
				$res[$key] = $message->setContext( $this->module )->parseAsBlock();
				$res[$key] = Parser::stripOuterParagraph( $res[$key] );
				break;

			case 'raw':
				$params = $message->getParams();
				$res[$key] = [
					'key' => $message->getKey(),
					'params' => $params,
				];
				ApiResult::setIndexedTagName( $params, 'param' );
				break;
		}
	}

	/**
	 * Call $manager->securitySensitiveOperationStatus()
	 * @param string $operation Operation being checked.
	 * @throws ApiUsageException
	 */
	public function securitySensitiveOperation( $operation ) {
		$status = $this->authManager->securitySensitiveOperationStatus( $operation );
		switch ( $status ) {
			case AuthManager::SEC_OK:
				return;

			case AuthManager::SEC_REAUTH:
				$this->module->dieWithError( 'apierror-reauthenticate' );

			case AuthManager::SEC_FAIL:
				$this->module->dieWithError( 'apierror-cannotreauthenticate' );

			default:
				throw new UnexpectedValueException( "Unknown status \"$status\"" );
		}
	}

	/**
	 * Filter out authentication requests by class name
	 * @param AuthenticationRequest[] $reqs Requests to filter
	 * @param string[] $blacklist Class names to remove
	 * @return AuthenticationRequest[]
	 */
	public static function blacklistAuthenticationRequests( array $reqs, array $blacklist ) {
		if ( $blacklist ) {
			$blacklist = array_flip( $blacklist );
			$reqs = array_filter( $reqs, function ( $req ) use ( $blacklist ) {
				return !isset( $blacklist[get_class( $req )] );
			} );
		}
		return $reqs;
	}

	/**
	 * Fetch and load the AuthenticationRequests for an action
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @return AuthenticationRequest[]
	 */
	public function loadAuthenticationRequests( $action ) {
		$params = $this->module->extractRequestParams();

		$reqs = $this->authManager->getAuthenticationRequests( $action, $this->module->getUser() );

		// Filter requests, if requested to do so
		$wantedRequests = null;
		if ( isset( $params['requests'] ) ) {
			$wantedRequests = array_flip( $params['requests'] );
		} elseif ( isset( $params['request'] ) ) {
			$wantedRequests = [ $params['request'] => true ];
		}
		if ( $wantedRequests !== null ) {
			$reqs = array_filter(
				$reqs,
				function ( AuthenticationRequest $req ) use ( $wantedRequests ) {
					return isset( $wantedRequests[$req->getUniqueId()] );
				}
			);
		}

		// Collect the fields for all the requests
		$fields = [];
		$sensitive = [];
		foreach ( $reqs as $req ) {
			$info = (array)$req->getFieldInfo();
			$fields += $info;
			$sensitive += array_filter( $info, function ( $opts ) {
				return !empty( $opts['sensitive'] );
			} );
		}

		// Extract the request data for the fields and mark those request
		// parameters as used
		$data = array_intersect_key( $this->module->getRequest()->getValues(), $fields );
		$this->module->getMain()->markParamsUsed( array_keys( $data ) );

		if ( $sensitive ) {
			$this->module->getMain()->markParamsSensitive( array_keys( $sensitive ) );
			$this->module->requirePostedParameters( array_keys( $sensitive ), 'noprefix' );
		}

		return AuthenticationRequest::loadRequestsFromSubmission( $reqs, $data );
	}

	/**
	 * Format an AuthenticationResponse for return
	 * @param AuthenticationResponse $res
	 * @return array
	 */
	public function formatAuthenticationResponse( AuthenticationResponse $res ) {
		$ret = [
			'status' => $res->status,
		];

		if ( $res->status === AuthenticationResponse::PASS && $res->username !== null ) {
			$ret['username'] = $res->username;
		}

		if ( $res->status === AuthenticationResponse::REDIRECT ) {
			$ret['redirecttarget'] = $res->redirectTarget;
			if ( $res->redirectApiData !== null ) {
				$ret['redirectdata'] = $res->redirectApiData;
			}
		}

		if ( $res->status === AuthenticationResponse::REDIRECT ||
			$res->status === AuthenticationResponse::UI ||
			$res->status === AuthenticationResponse::RESTART
		) {
			$ret += $this->formatRequests( $res->neededRequests );
		}

		if ( $res->status === AuthenticationResponse::FAIL ||
			$res->status === AuthenticationResponse::UI ||
			$res->status === AuthenticationResponse::RESTART
		) {
			$this->formatMessage( $ret, 'message', $res->message );
			$ret['messagecode'] = ApiMessage::create( $res->message )->getApiCode();
		}

		if ( $res->status === AuthenticationResponse::FAIL ||
			$res->status === AuthenticationResponse::RESTART
		) {
			$this->module->getRequest()->getSession()->set(
				'ApiAuthManagerHelper::createRequest',
				$res->createRequest
			);
			$ret['canpreservestate'] = $res->createRequest !== null;
		} else {
			$this->module->getRequest()->getSession()->remove( 'ApiAuthManagerHelper::createRequest' );
		}

		return $ret;
	}

	/**
	 * Logs successful or failed authentication.
	 * @param string $event Event type (e.g. 'accountcreation')
	 * @param string|AuthenticationResponse $result Response or error message
	 */
	public function logAuthenticationResult( $event, $result ) {
		if ( is_string( $result ) ) {
			$status = Status::newFatal( $result );
		} elseif ( $result->status === AuthenticationResponse::PASS ) {
			$status = Status::newGood();
		} elseif ( $result->status === AuthenticationResponse::FAIL ) {
			$status = Status::newFatal( $result->message );
		} else {
			return;
		}

		$module = $this->module->getModuleName();
		LoggerFactory::getInstance( 'authevents' )->info( "$module API attempt", [
			'event' => $event,
			'status' => $status->__toString(),
			'module' => $module,
		] );
	}

	/**
	 * Fetch the preserved CreateFromLoginAuthenticationRequest, if any
	 * @return CreateFromLoginAuthenticationRequest|null
	 */
	public function getPreservedRequest() {
		$ret = $this->module->getRequest()->getSession()->get( 'ApiAuthManagerHelper::createRequest' );
		return $ret instanceof CreateFromLoginAuthenticationRequest ? $ret : null;
	}

	/**
	 * Format an array of AuthenticationRequests for return
	 * @param AuthenticationRequest[] $reqs
	 * @return array Will have a 'requests' key, and also 'fields' if $module's
	 *  params include 'mergerequestfields'.
	 */
	public function formatRequests( array $reqs ) {
		$params = $this->module->extractRequestParams();
		$mergeFields = !empty( $params['mergerequestfields'] );

		$ret = [ 'requests' => [] ];
		foreach ( $reqs as $req ) {
			$describe = $req->describeCredentials();
			$reqInfo = [
				'id' => $req->getUniqueId(),
				'metadata' => $req->getMetadata() + [ ApiResult::META_TYPE => 'assoc' ],
			];
			switch ( $req->required ) {
				case AuthenticationRequest::OPTIONAL:
					$reqInfo['required'] = 'optional';
					break;
				case AuthenticationRequest::REQUIRED:
					$reqInfo['required'] = 'required';
					break;
				case AuthenticationRequest::PRIMARY_REQUIRED:
					$reqInfo['required'] = 'primary-required';
					break;
			}
			$this->formatMessage( $reqInfo, 'provider', $describe['provider'] );
			$this->formatMessage( $reqInfo, 'account', $describe['account'] );
			if ( !$mergeFields ) {
				$reqInfo['fields'] = $this->formatFields( (array)$req->getFieldInfo() );
			}
			$ret['requests'][] = $reqInfo;
		}

		if ( $mergeFields ) {
			$fields = AuthenticationRequest::mergeFieldInfo( $reqs );
			$ret['fields'] = $this->formatFields( $fields );
		}

		return $ret;
	}

	/**
	 * Clean up a field array for output
	 * @param array $fields
	 * @codingStandardsIgnoreStart
	 * @phan-param array{type:string,options:array,value:string,label:Message,help:Message,optional:bool,sensitive:bool,skippable:bool} $fields
	 * @codingStandardsIgnoreEnd
	 * @return array
	 */
	private function formatFields( array $fields ) {
		static $copy = [
			'type' => true,
			'value' => true,
		];

		$module = $this->module;
		$retFields = [];

		foreach ( $fields as $name => $field ) {
			$ret = array_intersect_key( $field, $copy );

			if ( isset( $field['options'] ) ) {
				$ret['options'] = array_map( function ( $msg ) use ( $module ) {
					return $msg->setContext( $module )->plain();
				}, $field['options'] );
				ApiResult::setArrayType( $ret['options'], 'assoc' );
			}
			$this->formatMessage( $ret, 'label', $field['label'] );
			$this->formatMessage( $ret, 'help', $field['help'] );
			$ret['optional'] = !empty( $field['optional'] );
			$ret['sensitive'] = !empty( $field['sensitive'] );

			$retFields[$name] = $ret;
		}

		ApiResult::setArrayType( $retFields, 'assoc' );

		return $retFields;
	}

	/**
	 * Fetch the standard parameters this helper recognizes
	 * @param string $action AuthManager action
	 * @param string ...$wantedParams Parameters to use
	 * @return array
	 */
	public static function getStandardParams( $action, ...$wantedParams ) {
		$params = [
			'requests' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG => [ 'api-help-authmanagerhelper-requests', $action ],
			],
			'request' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
				ApiBase::PARAM_HELP_MSG => [ 'api-help-authmanagerhelper-request', $action ],
			],
			'messageformat' => [
				ApiBase::PARAM_DFLT => 'wikitext',
				ApiBase::PARAM_TYPE => [ 'html', 'wikitext', 'raw', 'none' ],
				ApiBase::PARAM_HELP_MSG => 'api-help-authmanagerhelper-messageformat',
			],
			'mergerequestfields' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => 'api-help-authmanagerhelper-mergerequestfields',
			],
			'preservestate' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => 'api-help-authmanagerhelper-preservestate',
			],
			'returnurl' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_HELP_MSG => 'api-help-authmanagerhelper-returnurl',
			],
			'continue' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => 'api-help-authmanagerhelper-continue',
			],
		];

		$ret = [];
		foreach ( $wantedParams as $name ) {
			if ( isset( $params[$name] ) ) {
				$ret[$name] = $params[$name];
			}
		}
		return $ret;
	}
}
