<?php
/**
 * Created on Jun 25, 2013
 *
 * Copyright © 2013 Brad Jorsch <bjorsch@wikimedia.org>
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
		if ( !$user->isAllowed( RevisionDeleter::getRestriction( $params['type'] ) ) ) {
			$this->dieUsageMsg( 'badaccess-group0' );
		}

		if ( $user->isBlocked() ) {
			$this->dieBlocked( $user->getBlock() );
		}

		if ( !$params['ids'] ) {
			$this->dieUsage( "At least one value is required for 'ids'", 'badparams' );
		}

		$hide = $params['hide'] ?: [];
		$show = $params['show'] ?: [];
		if ( array_intersect( $hide, $show ) ) {
			$this->dieUsage( "Mutually exclusive values for 'hide' and 'show'", 'badparams' );
		} elseif ( !$hide && !$show ) {
			$this->dieUsage( "At least one value is required for 'hide' or 'show'", 'badparams' );
		}
		$bits = [
			'content' => RevisionDeleter::getRevdelConstant( $params['type'] ),
			'comment' => Revision::DELETED_COMMENT,
			'user' => Revision::DELETED_USER,
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
			if ( !$user->isAllowed( 'suppressrevision' ) ) {
				$this->dieUsageMsg( 'badaccess-group0' );
			}
			$bitfield[Revision::DELETED_RESTRICTED] = 1;
		} elseif ( $params['suppress'] === 'no' ) {
			$bitfield[Revision::DELETED_RESTRICTED] = 0;
		} else {
			$bitfield[Revision::DELETED_RESTRICTED] = -1;
		}

		$targetObj = null;
		if ( $params['target'] ) {
			$targetObj = Title::newFromText( $params['target'] );
		}
		$targetObj = RevisionDeleter::suggestTarget( $params['type'], $targetObj, $params['ids'] );
		if ( $targetObj === null ) {
			$this->dieUsage( 'A target title is required for this RevDel type', 'needtarget' );
		}

		$list = RevisionDeleter::createList(
			$params['type'], $this->getContext(), $targetObj, $params['ids']
		);
		$status = $list->setVisibility(
			[ 'value' => $bitfield, 'comment' => $params['reason'], 'perItemStatus' => true ]
		);

		$result = $this->getResult();
		$data = $this->extractStatusInfo( $status );
		$data['target'] = $targetObj->getFullText();
		$data['items'] = [];

		foreach ( $status->itemStatuses as $id => $s ) {
			$data['items'][$id] = $this->extractStatusInfo( $s );
			$data['items'][$id]['id'] = $id;
		}

		$list->reloadFromMaster();
		// @codingStandardsIgnoreStart Avoid function calls in a FOR loop test part
		for ( $item = $list->reset(); $list->current(); $item = $list->next() ) {
			$data['items'][$item->getId()] += $item->getApiData( $this->getResult() );
		}
		// @codingStandardsIgnoreEnd

		$data['items'] = array_values( $data['items'] );
		ApiResult::setIndexedTagName( $data['items'], 'i' );
		$result->addValue( null, $this->getModuleName(), $data );
	}

	private function extractStatusInfo( $status ) {
		$ret = [
			'status' => $status->isOK() ? 'Success' : 'Fail',
		];
		$errors = $this->formatStatusMessages( $status->getErrorsByType( 'error' ) );
		if ( $errors ) {
			ApiResult::setIndexedTagName( $errors, 'e' );
			$ret['errors'] = $errors;
		}
		$warnings = $this->formatStatusMessages( $status->getErrorsByType( 'warning' ) );
		if ( $warnings ) {
			ApiResult::setIndexedTagName( $warnings, 'w' );
			$ret['warnings'] = $warnings;
		}

		return $ret;
	}

	private function formatStatusMessages( $messages ) {
		if ( !$messages ) {
			return [];
		}
		$ret = [];
		foreach ( $messages as $m ) {
			if ( $m['message'] instanceof Message ) {
				$msg = $m['message'];
				$message = [ 'message' => $msg->getKey() ];
				if ( $msg->getParams() ) {
					$message['params'] = $msg->getParams();
					ApiResult::setIndexedTagName( $message['params'], 'p' );
				}
			} else {
				$message = [ 'message' => $m['message'] ];
				$msg = wfMessage( $m['message'] );
				if ( isset( $m['params'] ) ) {
					$message['params'] = $m['params'];
					ApiResult::setIndexedTagName( $message['params'], 'p' );
					$msg->params( $m['params'] );
				}
			}
			$message['rendered'] = $msg->useDatabase( false )->inLanguage( 'en' )->plain();
			$ret[] = $message;
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
		return 'https://www.mediawiki.org/wiki/API:Revisiondelete';
	}
}
