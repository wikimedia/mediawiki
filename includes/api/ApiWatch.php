<?php
/**
 *
 *
 * Created on Jan 4, 2008
 *
 * Copyright © 2008 Yuri Astrakhan "<Firstname><Lastname>@gmail.com",
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
 * API module to allow users to watch a page
 *
 * @ingroup API
 */
class ApiWatch extends ApiBase {

	public function execute() {
		$user = $this->getUser();
		if ( !$user->isLoggedIn() ) {
			$this->dieUsage( 'You must be logged-in to have a watchlist', 'notloggedin' );
		}
		if ( !$user->isAllowed( 'editmywatchlist' ) ) {
			$this->dieUsage( 'You don\'t have permission to edit your watchlist', 'permissiondenied' );
		}

		$params = $this->extractRequestParams();
		$title = Title::newFromText( $params['title'] );

		if ( !$title || $title->isExternal() || !$title->canExist() ) {
			$this->dieUsageMsg( array( 'invalidtitle', $params['title'] ) );
		}

		$res = array( 'title' => $title->getPrefixedText() );

		// Currently unnecessary, code to act as a safeguard against any change in current behavior of uselang
		// Copy from ApiParse
		$oldLang = null;
		if ( isset( $params['uselang'] ) && $params['uselang'] != $this->getContext()->getLanguage()->getCode() ) {
			$oldLang = $this->getContext()->getLanguage(); // Backup language
			$this->getContext()->setLanguage( Language::factory( $params['uselang'] ) );
		}

		if ( $params['unwatch'] ) {
			$res['unwatched'] = '';
			$res['message'] = $this->msg( 'removedwatchtext', $title->getPrefixedText() )->title( $title )->parseAsBlock();
			$status = UnwatchAction::doUnwatch( $title, $user );
		} else {
			$res['watched'] = '';
			$res['message'] = $this->msg( 'addedwatchtext', $title->getPrefixedText() )->title( $title )->parseAsBlock();
			$status = WatchAction::doWatch( $title, $user );
		}

		if ( !is_null( $oldLang ) ) {
			$this->getContext()->setLanguage( $oldLang ); // Reset language to $oldLang
		}

		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
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
			'uselang' => null,
			'token' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
		);
	}

	public function getParamDescription() {
		return array(
			'title' => 'The page to (un)watch',
			'unwatch' => 'If set the page will be unwatched rather than watched',
			'uselang' => 'Language to show the message in',
			'token' => 'A token previously acquired via prop=info',
		);
	}

	public function getResultProperties() {
		return array(
			'' => array(
				'title' => 'string',
				'unwatched' => 'boolean',
				'watched' => 'boolean',
				'message' => 'string'
			)
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
			'api.php?action=watch&title=Main_Page' => 'Watch the page "Main Page"',
			'api.php?action=watch&title=Main_Page&unwatch=' => 'Unwatch the page "Main Page"',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Watch';
	}
}
