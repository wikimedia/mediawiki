<?php

/**
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
 */

/**
 * @ingroup API
 * @since 1.25
 */
class ApiManageTags extends ApiBase {

	public function execute() {
		$params = $this->extractRequestParams();

		// make sure the user is allowed
		if ( !$this->getUser()->isAllowed( 'managechangetags' ) ) {
			$this->dieUsage( "You don't have permission to manage change tags", 'permissiondenied' );
		}

		$result = $this->getResult();
		$funcName = "{$params['operation']}TagWithChecks";
		$status = ChangeTags::$funcName( $params['tag'], $params['reason'],
			$this->getUser(), $params['ignorewarnings'] );

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

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return [
			'operation' => [
				ApiBase::PARAM_TYPE => [ 'create', 'delete', 'activate', 'deactivate' ],
				ApiBase::PARAM_REQUIRED => true,
			],
			'tag' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			],
			'reason' => [
				ApiBase::PARAM_TYPE => 'string',
			],
			'ignorewarnings' => [
				ApiBase::PARAM_TYPE => 'boolean',
				ApiBase::PARAM_DFLT => false,
			],
		];
	}

	public function needsToken() {
		return 'csrf';
	}

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

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Tag_management';
	}
}
