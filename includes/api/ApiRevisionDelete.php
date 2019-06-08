<?php
/**
 * Copyright © 2013 Wikimedia Foundation and contributors
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
 * @since 1.23
 */

use MediaWiki\Revision\RevisionRecord;

/**
 * API interface to RevDel. The API equivalent of Special:RevisionDelete.
 * Requires API write mode to be enabled.
 *
 * @ingroup API
 */
class ApiRevisionDelete extends ApiBase {

	public function execute() {
		$this->useTransactionalTimeLimit();

		$params = $this->extractRequestParams();
		$user = $this->getUser();
		$this->checkUserRightsAny( RevisionDeleter::getRestriction( $params['type'] ) );

		if ( $user->isBlocked() ) {
			$this->dieBlocked( $user->getBlock() );
		}

		if ( !$params['ids'] ) {
			$this->dieWithError( [ 'apierror-paramempty', 'ids' ], 'paramempty_ids' );
		}

		// Check if user can add tags
		if ( $params['tags'] ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $user );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		$hide = $params['hide'] ?: [];
		$show = $params['show'] ?: [];
		if ( array_intersect( $hide, $show ) ) {
			$this->dieWithError( 'apierror-revdel-mutuallyexclusive', 'badparams' );
		} elseif ( !$hide && !$show ) {
			$this->dieWithError( 'apierror-revdel-paramneeded', 'badparams' );
		}
		$bits = [
			'content' => RevisionDeleter::getRevdelConstant( $params['type'] ),
			'comment' => RevisionRecord::DELETED_COMMENT,
			'user' => RevisionRecord::DELETED_USER,
		];
		$bitfield = [];
		foreach ( $bits as $key => $bit ) {
			if ( in_array( $key, $hide ) ) {
				$bitfield[$bit] = 1;
			} elseif ( in_array( $key, $show ) ) {
				$bitfield[$bit] = 0;
			} else {
				$bitfield[$bit] = -1;
			}
		}

		if ( $params['suppress'] === 'yes' ) {
			$this->checkUserRightsAny( 'suppressrevision' );
			$bitfield[RevisionRecord::DELETED_RESTRICTED] = 1;
		} elseif ( $params['suppress'] === 'no' ) {
			$bitfield[RevisionRecord::DELETED_RESTRICTED] = 0;
		} else {
			$bitfield[RevisionRecord::DELETED_RESTRICTED] = -1;
		}

		$targetObj = null;
		if ( $params['target'] ) {
			$targetObj = Title::newFromText( $params['target'] );
		}
		$targetObj = RevisionDeleter::suggestTarget( $params['type'], $targetObj, $params['ids'] );
		if ( $targetObj === null ) {
			$this->dieWithError( [ 'apierror-revdel-needtarget' ], 'needtarget' );
		}

		$list = RevisionDeleter::createList(
			$params['type'], $this->getContext(), $targetObj, $params['ids']
		);
		$status = $list->setVisibility( [
			'value' => $bitfield,
			'comment' => $params['reason'],
			'perItemStatus' => true,
			'tags' => $params['tags']
		] );

		$result = $this->getResult();
		$data = $this->extractStatusInfo( $status );
		$data['target'] = $targetObj->getFullText();
		$data['items'] = [];

		foreach ( $status->itemStatuses as $id => $s ) {
			$data['items'][$id] = $this->extractStatusInfo( $s );
			$data['items'][$id]['id'] = $id;
		}

		$list->reloadFromMaster();
		for ( $item = $list->reset(); $list->current(); $item = $list->next() ) {
			$data['items'][$item->getId()] += $item->getApiData( $this->getResult() );
		}

		$data['items'] = array_values( $data['items'] );
		ApiResult::setIndexedTagName( $data['items'], 'i' );
		$result->addValue( null, $this->getModuleName(), $data );
	}

	private function extractStatusInfo( $status ) {
		$ret = [
			'status' => $status->isOK() ? 'Success' : 'Fail',
		];

		$errors = $this->getErrorFormatter()->arrayFromStatus( $status, 'error' );
		if ( $errors ) {
			$ret['errors'] = $errors;
		}
		$warnings = $this->getErrorFormatter()->arrayFromStatus( $status, 'warning' );
		if ( $warnings ) {
			$ret['warnings'] = $warnings;
		}

		return $ret;
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return [
			'type' => [
				ApiBase::PARAM_TYPE => RevisionDeleter::getTypes(),
				ApiBase::PARAM_REQUIRED => true
			],
			'target' => null,
			'ids' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_REQUIRED => true
			],
			'hide' => [
				ApiBase::PARAM_TYPE => [ 'content', 'comment', 'user' ],
				ApiBase::PARAM_ISMULTI => true,
			],
			'show' => [
				ApiBase::PARAM_TYPE => [ 'content', 'comment', 'user' ],
				ApiBase::PARAM_ISMULTI => true,
			],
			'suppress' => [
				ApiBase::PARAM_TYPE => [ 'yes', 'no', 'nochange' ],
				ApiBase::PARAM_DFLT => 'nochange',
			],
			'reason' => null,
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true,
			],
		];
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return [
			'action=revisiondelete&target=Main%20Page&type=revision&ids=12345&' .
				'hide=content&token=123ABC'
				=> 'apihelp-revisiondelete-example-revision',
			'action=revisiondelete&type=logging&ids=67890&hide=content|comment|user&' .
				'reason=BLP%20violation&token=123ABC'
				=> 'apihelp-revisiondelete-example-log',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Revisiondelete';
	}
}
