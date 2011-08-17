<?php
/**
 *
 *
 * Created on Jan 4, 2008
 *
 * Copyright © 2008 Yuri Astrakhan <Firstname><Lastname>@gmail.com,
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiBase.php' );
}

/**
 * API module to allow users to watch a page
 *
 * @ingroup API
 */
class ApiWatch extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		global $wgUser;
		if ( !$wgUser->isLoggedIn() ) {
			$this->dieUsage( 'You must be logged-in to have a watchlist', 'notloggedin' );
		}

		$params = $this->extractRequestParams();
		$title = Title::newFromText( $params['title'] );

		if ( !$title ) {
			$this->dieUsageMsg( array( 'invalidtitle', $params['title'] ) );
		}

		$article = new Article( $title, 0 );
		$res = array( 'title' => $title->getPrefixedText() );

		if ( $params['unwatch'] ) {
			$res['unwatched'] = '';
			$res['message'] = wfMsgExt( 'removedwatchtext', array( 'parse' ), $title->getPrefixedText() );
			$success = UnwatchAction::doUnwatch( $title, $wgUser );
		} else {
			$res['watched'] = '';
			$res['message'] = wfMsgExt( 'addedwatchtext', array( 'parse' ), $title->getPrefixedText() );
			$success = WatchAction::doWatch( $title, $wgUser );
		}
		if ( !$success ) {
			$this->dieUsageMsg( 'hookaborted' );
		}
		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return 'watch';
	}

	public function getAllowedParams() {
		return array(
			'title' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'unwatch' => false,
			'token' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'title' => 'The page to (un)watch',
			'unwatch' => 'If set the page will be unwatched rather than watched',
			'token' => 'A token previously acquired via prop=info',
		);
	}

	public function getDescription() {
		return 'Add or remove a page from/to the current user\'s watchlist';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'notloggedin', 'info' => 'You must be logged-in to have a watchlist' ),
			array( 'invalidtitle', 'title' ),
			array( 'hookaborted' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=watch&title=Main_Page',
			'api.php?action=watch&title=Main_Page&unwatch=',
		);
	}

	public function getHelpUrls() {
		return 'http://www.mediawiki.org/wiki/API:Watch';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
