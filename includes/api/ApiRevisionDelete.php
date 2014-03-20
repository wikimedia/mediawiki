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
		$params = $this->extractRequestParams();
		$user = $this->getUser();

		if ( !$user->isAllowed( RevisionDeleter::getRestriction( $params['type'] ) ) ) {
			$this->dieUsageMsg( 'badaccess-group0' );
		}

		if ( !$params['ids'] ) {
			$this->dieUsage( "At least one value is required for 'ids'", 'badparams' );
		}

		$hide = $params['hide'] ?: array();
		$show = $params['show'] ?: array();
		if ( array_intersect( $hide, $show ) ) {
			$this->dieUsage( "Mutually exclusive values for 'hide' and 'show'", 'badparams' );
		} elseif ( !$hide && !$show ) {
			$this->dieUsage( "At least one value is required for 'hide' or 'show'", 'badparams' );
		}
		$bits = array(
			'content' => RevisionDeleter::getRevdelConstant( $params['type'] ),
			'comment' => Revision::DELETED_COMMENT,
			'user' => Revision::DELETED_USER,
		);
		$bitfield = array();
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
			array( 'value' => $bitfield, 'comment' => $params['reason'], 'perItemStatus' => true )
		);

		$result = $this->getResult();
		$data = $this->extractStatusInfo( $status );
		$data['target'] = $targetObj->getFullText();
		$data['items'] = array();

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
		$result->setIndexedTagName( $data['items'], 'i' );
		$result->addValue( null, $this->getModuleName(), $data );
	}

	private function extractStatusInfo( $status ) {
		$ret = array(
			'status' => $status->isOK() ? 'Success' : 'Fail',
		);
		$errors = $this->formatStatusMessages( $status->getErrorsByType( 'error' ) );
		if ( $errors ) {
			$this->getResult()->setIndexedTagName( $errors, 'e' );
			$ret['errors'] = $errors;
		}
		$warnings = $this->formatStatusMessages( $status->getErrorsByType( 'warning' ) );
		if ( $warnings ) {
			$this->getResult()->setIndexedTagName( $warnings, 'w' );
			$ret['warnings'] = $warnings;
		}

		return $ret;
	}

	private function formatStatusMessages( $messages ) {
		if ( !$messages ) {
			return array();
		}
		$result = $this->getResult();
		$ret = array();
		foreach ( $messages as $m ) {
			$message = array();
			if ( $m['message'] instanceof Message ) {
				$msg = $m['message'];
				$message = array( 'message' => $msg->getKey() );
				if ( $msg->getParams() ) {
					$message['params'] = $msg->getParams();
					$result->setIndexedTagName( $message['params'], 'p' );
				}
			} else {
				$message = array( 'message' => $m['message'] );
				$msg = wfMessage( $m['message'] );
				if ( isset( $m['params'] ) ) {
					$message['params'] = $m['params'];
					$result->setIndexedTagName( $message['params'], 'p' );
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
		return array(
			'type' => array(
				ApiBase::PARAM_TYPE => RevisionDeleter::getTypes(),
				ApiBase::PARAM_REQUIRED => true
			),
			'target' => null,
			'ids' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_REQUIRED => true
			),
			'hide' => array(
				ApiBase::PARAM_TYPE => array( 'content', 'comment', 'user' ),
				ApiBase::PARAM_ISMULTI => true,
			),
			'show' => array(
				ApiBase::PARAM_TYPE => array( 'content', 'comment', 'user' ),
				ApiBase::PARAM_ISMULTI => true,
			),
			'suppress' => array(
				ApiBase::PARAM_TYPE => array( 'yes', 'no', 'nochange' ),
				ApiBase::PARAM_DFLT => 'nochange',
			),
			'token' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'reason' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'type' => 'Type of revision deletion being performed',
			'target' => 'Page title for the revision deletion, if required for the type',
			'ids' => 'Identifiers for the revisions to be deleted',
			'hide' => 'What to hide for each revision',
			'show' => 'What to unhide for each revision',
			'suppress' => 'Whether to suppress data from administrators as well as others',
			'token' => 'A delete token previously retrieved through action=tokens',
			'reason' => 'Reason for the deletion/undeletion',
		);
	}

	public function getDescription() {
		return 'Delete/undelete revisions.';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(),
			array(
				array( 'code' => 'needtarget',
					'info' => 'A target title is required for this RevDel type' ),
				array( 'code' => 'badparams', 'info' => 'Bad value for some parameter' ),
			)
		);
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	public function getExamples() {
		return array(
			'api.php?action=revisiondelete&target=Main%20Page&type=revision&ids=12345&' .
				'hide=content&token=123ABC'
				=> 'Hide content for revision 12345 on the Main Page',
			'api.php?action=revisiondelete&type=logging&ids=67890&hide=content|comment|user&' .
				'reason=BLP%20violation&token=123ABC'
				=> 'Hide all data on log entry 67890 with the reason "BLP violation"',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Revisiondelete';
	}
}
