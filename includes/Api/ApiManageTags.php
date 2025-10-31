<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\ChangeTags\ChangeTags;
use UnexpectedValueException;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @ingroup API
 * @since 1.25
 */
class ApiManageTags extends ApiBase {

	public function execute() {
		$params = $this->extractRequestParams();
		$authority = $this->getAuthority();

		// make sure the user is allowed
		if ( $params['operation'] !== 'delete'
			&& !$authority->isAllowed( 'managechangetags' )
		) {
			$this->dieWithError( 'tags-manage-no-permission', 'permissiondenied' );
		} elseif ( !$authority->isAllowed( 'deletechangetags' ) ) {
			$this->dieWithError( 'tags-delete-no-permission', 'permissiondenied' );
		}

		// Check if user can add the log entry tags which were requested
		if ( $params['tags'] ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $authority );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		$result = $this->getResult();
		$tag = $params['tag'];
		$reason = $params['reason'];
		$ignoreWarnings = $params['ignorewarnings'];
		$tags = $params['tags'] ?: [];
		switch ( $params['operation'] ) {
			case 'create':
				$status = ChangeTags::createTagWithChecks( $tag, $reason, $authority, $ignoreWarnings, $tags );
				break;
			case 'delete':
				$status = ChangeTags::deleteTagWithChecks( $tag, $reason, $authority, $ignoreWarnings, $tags );
				break;
			case 'activate':
				$status = ChangeTags::activateTagWithChecks( $tag, $reason, $authority, $ignoreWarnings, $tags );
				break;
			case 'deactivate':
				$status = ChangeTags::deactivateTagWithChecks( $tag, $reason, $authority, $ignoreWarnings, $tags );
				break;
			default:
				// unreachable
				throw new UnexpectedValueException( 'invalid operation' );
		}
		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		$ret = [
			'operation' => $params['operation'],
			'tag' => $params['tag'],
		];
		if ( !$status->isGood() ) {
			$ret['warnings'] = $this->getErrorFormatter()->arrayFromStatus( $status, 'warning' );
		}
		$ret['success'] = $status->value !== null;
		if ( $ret['success'] ) {
			$ret['logid'] = $status->value;
		}

		$result->addValue( null, $this->getModuleName(), $ret );
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'operation' => [
				ParamValidator::PARAM_TYPE => [ 'create', 'delete', 'activate', 'deactivate' ],
				ParamValidator::PARAM_REQUIRED => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'tag' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'reason' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
			],
			'ignorewarnings' => [
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false,
			],
			'tags' => [
				ParamValidator::PARAM_TYPE => 'tags',
				ParamValidator::PARAM_ISMULTI => true,
			],
		];
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'csrf';
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=managetags&operation=create&tag=spam&reason=For+use+in+edit+patrolling&token=123ABC'
				=> 'apihelp-managetags-example-create',
			'action=managetags&operation=delete&tag=vandlaism&reason=Misspelt&token=123ABC'
				=> 'apihelp-managetags-example-delete',
			'action=managetags&operation=activate&tag=spam&reason=For+use+in+edit+patrolling&token=123ABC'
				=> 'apihelp-managetags-example-activate',
			'action=managetags&operation=deactivate&tag=spam&reason=No+longer+required&token=123ABC'
				=> 'apihelp-managetags-example-deactivate',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Tag_management';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiManageTags::class, 'ApiManageTags' );
