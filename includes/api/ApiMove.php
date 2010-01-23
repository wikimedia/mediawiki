<?php

/*
 * Created on Oct 31, 2007
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
	require_once ( "ApiBase.php" );
}


/**
 * @ingroup API
 */
class ApiMove extends ApiBase {

	public function __construct( $main, $action ) {
		parent :: __construct( $main, $action );
	}

	public function execute() {
		global $wgUser;
		$params = $this->extractRequestParams();
		if ( is_null( $params['reason'] ) )
			$params['reason'] = '';

		$this->requireOnlyOneParameter( $params, 'from', 'fromid' );
		if ( !isset( $params['to'] ) )
			$this->dieUsageMsg( array( 'missingparam', 'to' ) );
		if ( !isset( $params['token'] ) )
			$this->dieUsageMsg( array( 'missingparam', 'token' ) );
		if ( !$wgUser->matchEditToken( $params['token'] ) )
			$this->dieUsageMsg( array( 'sessionfailure' ) );

		if ( isset( $params['from'] ) )
		{
			$fromTitle = Title::newFromText( $params['from'] );
			if ( !$fromTitle )
				$this->dieUsageMsg( array( 'invalidtitle', $params['from'] ) );
		}
		else if ( isset( $params['fromid'] ) )
		{
			$fromTitle = Title::newFromID( $params['fromid'] );
			if ( !$fromTitle )
				$this->dieUsageMsg( array( 'nosuchpageid', $params['fromid'] ) );
		}

		if ( !$fromTitle->exists() )
			$this->dieUsageMsg( array( 'notanarticle' ) );
		$fromTalk = $fromTitle->getTalkPage();

		$toTitle = Title::newFromText( $params['to'] );
		if ( !$toTitle )
			$this->dieUsageMsg( array( 'invalidtitle', $params['to'] ) );
		$toTalk = $toTitle->getTalkPage();
		
		if ( $toTitle->getNamespace() == NS_FILE
			&& !RepoGroup::singleton()->getLocalRepo()->findFile( $toTitle )
			&& wfFindFile( $toTitle ) )
		{
			if ( !$params['ignorewarnings'] && $wgUser->isAllowed( 'reupload-shared' ) ) {
				$this->dieUsageMsg( array( 'sharedfile-exists' ) );
			} elseif ( !$wgUser->isAllowed( 'reupload-shared' ) ) {
				$this->dieUsageMsg( array( 'cantoverwrite-sharedfile' ) );
			}
		}
		
		// Move the page
		$hookErr = null;
		$retval = $fromTitle->moveTo( $toTitle, true, $params['reason'], !$params['noredirect'] );
		if ( $retval !== true )
			$this->dieUsageMsg( reset( $retval ) );

		$r = array( 'from' => $fromTitle->getPrefixedText(), 'to' => $toTitle->getPrefixedText(), 'reason' => $params['reason'] );
		if ( !$params['noredirect'] || !$wgUser->isAllowed( 'suppressredirect' ) )
			$r['redirectcreated'] = '';

		// Move the talk page
		if ( $params['movetalk'] && $fromTalk->exists() && !$fromTitle->isTalkPage() )
		{
			$retval = $fromTalk->moveTo( $toTalk, true, $params['reason'], !$params['noredirect'] );
			if ( $retval === true )
			{
				$r['talkfrom'] = $fromTalk->getPrefixedText();
				$r['talkto'] = $toTalk->getPrefixedText();
			}
			// We're not gonna dieUsage() on failure, since we already changed something
			else
			{
				$parsed = $this->parseMsg( reset( $retval ) );
				$r['talkmove-error-code'] = $parsed['code'];
				$r['talkmove-error-info'] = $parsed['info'];
			}
		}

		// Move subpages
		if ( $params['movesubpages'] )
		{
			$r['subpages'] = $this->moveSubpages( $fromTitle, $toTitle,
					$params['reason'], $params['noredirect'] );
			$this->getResult()->setIndexedTagName( $r['subpages'], 'subpage' );
			if ( $params['movetalk'] )
			{
				$r['subpages-talk'] = $this->moveSubpages( $fromTalk, $toTalk,
					$params['reason'], $params['noredirect'] );
				$this->getResult()->setIndexedTagName( $r['subpages-talk'], 'subpage' );
			}
		}

		// Watch pages
		if ( $params['watch'] || $wgUser->getOption( 'watchmoves' ) )
		{
			$wgUser->addWatch( $fromTitle );
			$wgUser->addWatch( $toTitle );
		}
		else if ( $params['unwatch'] )
		{
			$wgUser->removeWatch( $fromTitle );
			$wgUser->removeWatch( $toTitle );
		}
		$this->getResult()->addValue( null, $this->getModuleName(), $r );
	}
	
	public function moveSubpages( $fromTitle, $toTitle, $reason, $noredirect )
	{
		$retval = array();
		$success = $fromTitle->moveSubpages( $toTitle, true, $reason, !$noredirect );
		if ( isset( $success[0] ) )
			return array( 'error' => $this->parseMsg( $success ) );
		else
		{
			// At least some pages could be moved
			// Report each of them separately
			foreach ( $success as $oldTitle => $newTitle )
			{
				$r = array( 'from' => $oldTitle );
				if ( is_array( $newTitle ) )
					$r['error'] = $this->parseMsg( reset( $newTitle ) );
				else
					// Success
					$r['to'] = $newTitle;
				$retval[] = $r;
			}
		}
		return $retval;
	}

	public function mustBePosted() { return true; }

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array (
			'from' => null,
			'fromid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'to' => null,
			'token' => null,
			'reason' => null,
			'movetalk' => false,
			'movesubpages' => false,
			'noredirect' => false,
			'watch' => false,
			'unwatch' => false,
			'ignorewarnings' => false
		);
	}

	public function getParamDescription() {
		return array (
			'from' => 'Title of the page you want to move. Cannot be used together with fromid.',
			'fromid' => 'Page ID of the page you want to move. Cannot be used together with from.',
			'to' => 'Title you want to rename the page to.',
			'token' => 'A move token previously retrieved through prop=info',
			'reason' => 'Reason for the move (optional).',
			'movetalk' => 'Move the talk page, if it exists.',
			'movesubpages' => 'Move subpages, if applicable',
			'noredirect' => 'Don\'t create a redirect',
			'watch' => 'Add the page and the redirect to your watchlist',
			'unwatch' => 'Remove the page and the redirect from your watchlist',
			'ignorewarnings' => 'Ignore any warnings'
		);
	}

	public function getDescription() {
		return array(
			'Move a page.'
		);
	}

	protected function getExamples() {
		return array (
			'api.php?action=move&from=Exampel&to=Example&token=123ABC&reason=Misspelled%20title&movetalk&noredirect'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
