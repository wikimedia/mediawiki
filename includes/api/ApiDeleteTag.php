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
class ApiDeleteTag extends ApiBase {

	public function execute() {
		$params = $this->extractRequestParams();

		// make sure the user is allowed
		if ( !$this->getUser()->isAllowed( 'managechangetags' ) ) {
			$this->dieUsage( "You don't have permission to manage change tags", 'permissiondenied' );
		}

		$status = ChangeTags::deleteTagWithChecks( $params['tag'], $params['reason'],
			$this->getUser(), $params['ignorewarnings'] );

		if ( !$status->isGood() ) {
			$this->dieStatus( $status );
		}

		$result = $this->getResult();
		$result->addValue( null, $this->getModuleName(), array(
			'tag' => $params['tag'],
			'success' => '',
			'logid' => $status->getValue(),
		) );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'tag' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'reason' => array(
				ApiBase::PARAM_TYPE => 'string',
			),
			'ignorewarnings' => array(
				ApiBase::PARAM_TYPE => 'boolean',
				ApiBase::PARAM_DFLT => false,
			),
		);
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return array(
			'action=deletetag&tag=vandlaism&reason=Misspelt&token=123ABC'
				=> 'apihelp-deletetag-example-simple',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Tag_management';
	}
}
