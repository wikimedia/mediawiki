<?php
/**
 *
 *
 * Created on Jul 3, 2007
 *
 * Copyright © 2007 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 * @ingroup API
 */
class ApiUndelete extends ApiBase {

	public function execute() {
		$params = $this->extractRequestParams();

		if ( !$this->getUser()->isAllowed( 'undelete' ) ) {
			$this->dieUsageMsg( 'permdenied-undelete' );
		}

		if ( $this->getUser()->isBlocked() ) {
			$this->dieUsageMsg( 'blockedtext' );
		}

		$titleObj = Title::newFromText( $params['title'] );
		if ( !$titleObj || $titleObj->isExternal() ) {
			$this->dieUsageMsg( array( 'invalidtitle', $params['title'] ) );
		}

		// Convert timestamps
		if ( !isset( $params['timestamps'] ) ) {
			$params['timestamps'] = array();
		}
		if ( !is_array( $params['timestamps'] ) ) {
			$params['timestamps'] = array( $params['timestamps'] );
		}
		foreach ( $params['timestamps'] as $i => $ts ) {
			$params['timestamps'][$i] = wfTimestamp( TS_MW, $ts );
		}

		$pa = new PageArchive( $titleObj, $this->getConfig() );
		$retval = $pa->undelete(
			( isset( $params['timestamps'] ) ? $params['timestamps'] : array() ),
			$params['reason'],
			$params['fileids'],
			false,
			$this->getUser()
		);
		if ( !is_array( $retval ) ) {
			$this->dieUsageMsg( 'cannotundelete' );
		}

		if ( $retval[1] ) {
			wfRunHooks( 'FileUndeleteComplete',
				array( $titleObj, $params['fileids'], $this->getUser(), $params['reason'] ) );
		}

		$this->setWatch( $params['watchlist'], $titleObj );

		$info['title'] = $titleObj->getPrefixedText();
		$info['revisions'] = intval( $retval[0] );
		$info['fileversions'] = intval( $retval[1] );
		$info['reason'] = $retval[2];
		$this->getResult()->addValue( null, $this->getModuleName(), $info );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'title' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'reason' => '',
			'timestamps' => array(
				ApiBase::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_ISMULTI => true,
			),
			'fileids' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_ISMULTI => true,
			),
			'watchlist' => array(
				ApiBase::PARAM_DFLT => 'preferences',
				ApiBase::PARAM_TYPE => array(
					'watch',
					'unwatch',
					'preferences',
					'nochange'
				),
			),
		);
	}

	public function getParamDescription() {
		return array(
			'title' => 'Title of the page you want to restore',
			'reason' => 'Reason for restoring',
			'timestamps' => array(
				'Timestamps of the revisions to restore.',
				'If both timestamps and fileids are empty, all will be restored.',
			),
			'fileids' => array(
				'IDs of the file revisions to restore.',
				'If both timestamps and fileids are empty, all will be restored.',
			),
			'watchlist' => 'Unconditionally add or remove the page from your ' .
				'watchlist, use preferences or do not change watch',
		);
	}

	public function getDescription() {
		return array(
			'Restore certain revisions of a deleted page. A list of deleted revisions ',
			'(including timestamps) can be retrieved through list=deletedrevs, and a list',
			'of deleted file ids can be retrieved through list=filearchive.'
		);
	}

	public function needsToken() {
		return 'csrf';
	}

	public function getExamples() {
		return array(
			'api.php?action=undelete&title=Main%20Page&token=123ABC&reason=Restoring%20main%20page',
			'api.php?action=undelete&title=Main%20Page&token=123ABC&timestamps=20070703220045|20070702194856'
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Undelete';
	}
}
