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

		// validate revid, rcid and logid
		$this->requireOnlyOneParameter( $params, 'revid', 'rcid', 'logid' );
		if ( $params['revid'] && !Revision::newFromId( $params['revid'] ) ) {
			$this->dieUsageMsg( array( 'nosuchrevid', $params['revid'] ) );
		}
		if ( $params['rcid'] && !RecentChange::newFromId( $params['rcid'] ) ) {
			$this->dieUsageMsg( array( 'nosuchrcid', $params['rcid'] ) );
		}
		if ( $params['logid'] && !self::validateLogId( $params['logid'] ) ) {
			$this->dieUsageMsg( array( 'nosuchlogid', $params['logid'] ) );
		}

		$status = ChangeTags::updateTagsWithChecks( $params['add'],
			$params['remove'], $params['rcid'], $params['revid'], $params['logid'],
			null, $params['reason'], $this->getUser() );
		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		$result = $this->getResult();
		$ret = array(
			'success' => '',
		);
		if ( is_null( $status->value->logId ) ) {
			$ret['noop'] = '';
		} else {
			$ret['logid'] = $status->value->logId;
			$ret['added'] = $status->value->addedTags;
			$result->setIndexedTagName( $ret['added'], 't' );
			$ret['removed'] = $status->value->removedTags;
			$result->setIndexedTagName( $ret['removed'], 't' );
		}
		$result->addValue( null, $this->getModuleName(), $ret );
	}

	protected static function validateLogId( $logid ) {
		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->selectField( 'logging', 'log_id', array( 'log_id' => $logid ),
			__METHOD__ );
		return (bool)$result;
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
			),
			'revid' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'logid' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'add' => array(
				ApiBase::PARAM_TYPE => $this->getAvailableTags(),
				ApiBase::PARAM_ISMULTI => true,
			),
			'remove' => array(
				ApiBase::PARAM_TYPE => $this->getAvailableTags(),
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
