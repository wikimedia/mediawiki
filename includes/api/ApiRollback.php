<?php

/*
 * Created on Jun 20, 2007
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2007 Roan Kattouw <Firstname>.<Lastname>@home.nl
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( "ApiBase.php" );
}

/**
 * @ingroup API
 */
class ApiRollback extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}
	
	private $mTitleObj = null;

	public function execute() {
		$params = $this->extractRequestParams();

		// User and title already validated in call to getTokenSalt from Main

		$articleObj = new Article( $this->mTitleObj );
		$summary = ( isset( $params['summary'] ) ? $params['summary'] : '' );
		$details = null;
		$retval = $articleObj->doRollback( $params['user'], $summary, $params['token'], $params['markbot'], $details );

		if ( $retval ) {
			// We don't care about multiple errors, just report one of them
			$this->dieUsageMsg( reset( $retval ) );
		}
		
		$watch = $this->getWatchlistValue( $params['watchlist'], $this->mTitleObj );
		
		if ( $watch !== null) {
			if ( $watch ) {
				$articleObj->doWatch();
			} else {
				$articleObj->doUnwatch();
			}
		}

		$info = array(
			'title' => $this->mTitleObj->getPrefixedText(),
			'pageid' => intval( $details['current']->getPage() ),
			'summary' => $details['summary'],
			'revid' => intval( $details['newid'] ),
			'old_revid' => intval( $details['current']->getID() ),
			'last_revid' => intval( $details['target']->getID() )
		);

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
			'title' => null,
			'user' => null,
			'token' => null,
			'summary' => null,
			'markbot' => false,
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
			'title' => 'Title of the page you want to rollback.',
			'user' => 'Name of the user whose edits are to be rolled back. If set incorrectly, you\'ll get a badtoken error.',
			'token' => 'A rollback token previously retrieved through prop=revisions',
			'summary' => 'Custom edit summary. If not set, default summary will be used.',
			'markbot' => 'Mark the reverted edits and the revert as bot edits',
			'watchlist' => 'Unconditionally add or remove the page from your watchlist, use preferences or do not change watch',
		);
	}

	public function getDescription() {
		return array(
			'Undo the last edit to the page. If the last user who edited the page made multiple edits in a row,',
			'they will all be rolled back.'
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'missingparam', 'title' ),
			array( 'missingparam', 'user' ),
			array( 'invalidtitle', 'title' ),
			array( 'notanarticle' ),
			array( 'invaliduser', 'user' ),
		) );
	}

	public function getTokenSalt() {
		$params = $this->extractRequestParams();
		
		if ( !isset( $params['user'] ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'user' ) );
		}
		
		// We need to be able to revert IPs, but getCanonicalName rejects them
		$this->username = User::isIP( $params['user'] )
			? $params['user']
			: User::getCanonicalName( $params['user'] );
		if ( !$this->username ) {
			$this->dieUsageMsg( array( 'invaliduser', $params['user'] ) );
		}
		
		if ( !isset( $params['title'] ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'title' ) );
		}
		
		$this->mTitleObj = Title::newFromText( $params['title'] );
		if ( !$this->mTitleObj ) {
			$this->dieUsageMsg( array( 'invalidtitle', $params['title'] ) );
		}
		if ( !$this->mTitleObj->exists() ) {
			$this->dieUsageMsg( array( 'notanarticle' ) );
		}
		
		return array( $this->mTitleObj->getPrefixedText(), $this->username );
	}

	protected function getExamples() {
		return array(
			'api.php?action=rollback&title=Main%20Page&user=Catrope&token=123ABC',
			'api.php?action=rollback&title=Main%20Page&user=217.121.114.116&token=123ABC&summary=Reverting%20vandalism&markbot=1'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
