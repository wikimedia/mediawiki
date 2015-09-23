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
class ApiTag extends ApiBase {

	protected function getAvailableTags() {
		return ChangeTags::listExplicitlyDefinedTags();
	}

	public function execute() {
		$params = $this->extractRequestParams();

		// make sure the user is allowed
		if ( !$this->getUser()->isAllowed( 'changetags' ) ) {
			$this->dieUsage( "You don't have permission to add or remove change tags from individual edits",
				'permissiondenied' );
		}

		// validate and process each revid, rcid and logid
		$this->requireAtLeastOneParameter( $params, 'revid', 'rcid', 'logid' );
		$ret = array();
		if ( $params['revid'] ) {
			foreach ( $params['revid'] as $id ) {
				$ret[] = $this->processIndividual( 'revid', $params, $id );
			}
		}
		if ( $params['rcid'] ) {
			foreach ( $params['rcid'] as $id ) {
				$ret[] = $this->processIndividual( 'rcid', $params, $id );
			}
		}
		if ( $params['logid'] ) {
			foreach ( $params['logid'] as $id ) {
				$ret[] = $this->processIndividual( 'logid', $params, $id );
			}
		}

		ApiResult::setIndexedTagName( $ret, 'result' );
		$this->getResult()->addValue( null, $this->getModuleName(), $ret );
	}

	protected static function validateLogId( $logid ) {
		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->selectField( 'logging', 'log_id', array( 'log_id' => $logid ),
			__METHOD__ );
		return (bool)$result;
	}

	protected function processIndividual( $type, $params, $id ) {
		$idResult = array( $type => $id );

		// validate the ID
		$valid = false;
		switch ( $type ) {
			case 'rcid':
				$valid = RecentChange::newFromId( $id );
				break;
			case 'revid':
				$valid = Revision::newFromId( $id );
				break;
			case 'logid':
				$valid = self::validateLogId( $id );
				break;
		}

		if ( !$valid ) {
			$idResult['status'] = 'error';
			$idResult += $this->parseMsg( array( "nosuch$type", $id ) );
			return $idResult;
		}

		$status = ChangeTags::updateTagsWithChecks( $params['add'],
			$params['remove'],
			( $type === 'rcid' ? $id : null ),
			( $type === 'revid' ? $id : null ),
			( $type === 'logid' ? $id : null ),
			null,
			$params['reason'],
			$this->getUser() );

		if ( !$status->isOK() ) {
			if ( $status->hasMessage( 'actionthrottledtext' ) ) {
				$idResult['status'] = 'skipped';
			} else {
				$idResult['status'] = 'failure';
				$idResult['errors'] = $this->getErrorFormatter()->arrayFromStatus( $status, 'error' );
			}
		} else {
			$idResult['status'] = 'success';
			if ( is_null( $status->value->logId ) ) {
				$idResult['noop'] = '';
			} else {
				$idResult['actionlogid'] = $status->value->logId;
				$idResult['added'] = $status->value->addedTags;
				ApiResult::setIndexedTagName( $idResult['added'], 't' );
				$idResult['removed'] = $status->value->removedTags;
				ApiResult::setIndexedTagName( $idResult['removed'], 't' );
			}
		}
		return $idResult;
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'rcid' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_ISMULTI => true,
			),
			'revid' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_ISMULTI => true,
			),
			'logid' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_ISMULTI => true,
			),
			'add' => array(
				ApiBase::PARAM_TYPE => $this->getAvailableTags(),
				ApiBase::PARAM_ISMULTI => true,
			),
			'remove' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_ISMULTI => true,
			),
			'reason' => array(
				ApiBase::PARAM_DFLT => '',
			),
		);
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return array(
			'action=tag&revid=123&add=vandalism&token=123ABC'
				=> 'apihelp-tag-example-rev',
			'action=tag&logid=123&remove=spam&reason=Wrongly+applied&token=123ABC'
				=> 'apihelp-tag-example-log',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Tag';
	}
}
