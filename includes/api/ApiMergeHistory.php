<?php
/**
 *
 *
 * Created on Dec 29, 2015
 *
 * Copyright © 2015 Geoffrey Mon "geofbot@gmail.com"
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
 */

/**
 * API Module to merge page histories
 * @ingroup API
 */
class ApiMergeHistory extends ApiBase {

	public function execute() {
		$this->useTransactionalTimeLimit();

		$user = $this->getUser();
		$params = $this->extractRequestParams();

		$this->requireOnlyOneParameter( $params, 'from', 'fromid' );

		if ( isset( $params['from'] ) ) {
			$fromTitle = Title::newFromText( $params['from'] );
			if ( !$fromTitle || $fromTitle->isExternal() ) {
				$this->dieUsageMsg( array( 'invalidtitle', $params['from'] ) );
			}
		} elseif ( isset( $params['fromid'] ) ) {
			$fromTitle = Title::newFromID( $params['fromid'] );
			if ( !$fromTitle ) {
				$this->dieUsageMsg( array( 'nosuchpageid', $params['fromid'] ) );
			}
		}

		if ( !$fromTitle->exists() ) {
			$this->dieUsageMsg( 'notanarticle' );
		}

		if ( isset( $params['to'] ) ) {
			$toTitle = Title::newFromText( $params['to'] );
			if ( !$toTitle || $toTitle->isExternal() ) {
				$this->dieUsageMsg( array( 'invalidtitle', $params['to'] ) );
			}
		} elseif ( isset( $params['toid'] ) ) {
			$toTitle = Title::newFromID( $params['toid'] );
			if ( !$toTitle ) {
				$this->dieUsageMsg( array( 'nosuchpageid', $params['toid'] ) );
			}
		}

		if ( !$toTitle->exists() ) {
			$this->dieUsageMsg( 'notanarticle' );
		}

		$reason = $params['reason'];
		$timestamp = Title::newFromText( $params['timestamp'] );
		if ( !$toTitle || $toTitle->isExternal() ) {
			$this->dieUsageMsg( array( 'invalidtitle', $params['to'] ) );
		}
		$toTalk = $toTitle->getTalkPage();

		if ( $toTitle->getNamespace() == NS_FILE
			&& !RepoGroup::singleton()->getLocalRepo()->findFile( $toTitle )
			&& wfFindFile( $toTitle )
		) {
			if ( !$params['ignorewarnings'] && $user->isAllowed( 'reupload-shared' ) ) {
				$this->dieUsageMsg( 'sharedfile-exists' );
			} elseif ( !$user->isAllowed( 'reupload-shared' ) ) {
				$this->dieUsageMsg( 'cantoverwrite-sharedfile' );
			}
		}

		// Merge!
		$status = $this->merge( $fromTitle, $toTitle, $timestamp, $reason );
		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		$r = array(
			'from' => $fromTitle->getPrefixedText(),
			'to' => $toTitle->getPrefixedText(),
			'reason' => $params['reason']
		);
		$result = $this->getResult();

		$result->addValue( null, $this->getModuleName(), $r );
	}

	/**
	 * @param Title $from
	 * @param Title $to
	 * @param string $timestamp
	 * @param string $reason
	 * @return Status
	 */
	protected function merge( Title $from, Title $to, $timestamp, $reason ) {
		$mh = new MergeHistory( $from, $to, $timestamp );
		$valid = $mh->isValidMerge();
		if ( !$valid->isOK() ) {
			return $valid;
		}

		// TODO
		//$permStatus = $mp->checkPermissions( $this->getUser(), $reason );
		//if ( !$permStatus->isOK() ) {
		//	return $permStatus;
		//}

		return $mh->merge( $this->getUser(), $reason );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'from' => null,
			'fromid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'to' => null,
			'toid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'timestamp' => null, //TODO: required?
			'reason' => '',
		);
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return array(
			'action=move&from=Badtitle&to=Goodtitle&token=123ABC&' .
			'reason=Misspelled%20title&movetalk=&noredirect='
			=> 'apihelp-move-example-move',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Mergehistory';
	}
}
