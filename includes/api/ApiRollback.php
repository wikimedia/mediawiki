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

if (!defined('MEDIAWIKI')) {
	// Eclipse helper - will be ignored in production
	require_once ("ApiBase.php");
}

/**
 * @addtogroup API
 */
class ApiRollback extends ApiBase {

	public function __construct($main, $action) {
		parent :: __construct($main, $action);
	}

	public function execute() {
		global $wgUser;
		$this->getMain()->requestWriteMode();
		$params = $this->extractRequestParams();
		
		$titleObj = NULL;
		if(!isset($params['title']))
			$this->dieUsage('The title parameter must be set', 'notitle');
		if(!isset($params['user']))
			$this->dieUsage('The user parameter must be set', 'nouser');
		if(!isset($params['token']))
			$this->dieUsage('The token parameter must be set', 'notoken');

		// doRollback() also checks for these, but we wanna save some work
		if($wgUser->isBlocked())
			$this->dieUsage('You have been blocked from editing', 'blocked');
		if(wfReadOnly())
			$this->dieUsage('The wiki is in read-only mode', 'readonly');

		$titleObj = Title::newFromText($params['title']);
		if(!$titleObj)
			$this->dieUsage("Bad title ``{$params['title']}''", 'invalidtitle');
		if(!$titleObj->userCan('rollback'))
			$this->dieUsage('You don\'t have permission to rollback', 'permissiondenied');

		$username = User::getCanonicalName($params['user']);
		if(!$username)
			$this->dieUsage("Invalid username ``{$params['user']}''", 'invaliduser');

		$articleObj = new Article($titleObj);
		$summary = (isset($params['summary']) ? $params['summary'] : "");
		$details = NULL;
		$dbw = wfGetDb(DB_MASTER);
		$dbw->begin();
		$retval = $articleObj->doRollback($username, $summary, $params['token'], $params['markbot'], &$details);

		switch($retval)
		{
			case Article::SUCCESS:
				break; // We'll deal with that later
			case Article::PERM_DENIED:
				$this->dieUsage("You don't have permission to rollback", 'permissiondenied');
			case Article::BLOCKED: // If we get BLOCKED or PERM_DENIED that's very weird, but it's possible
				$this->dieUsage('You have been blocked from editing', 'blocked');
			case Article::READONLY:
				$this->dieUsage('The wiki is in read-only mode', 'readonly');
			case Article::BAD_TOKEN:
				$this->dieUsage('Invalid token', 'badtoken');
			case Article::BAD_TITLE:
				$this->dieUsage("``{$params['title']}'' doesn't exist", 'missingtitle');
			case Article::ALREADYROLLED:
				$current = $details['current'];
				$currentID = $current->getId();
				$this->dieUsage("The edit(s) you tried to rollback is/are already rolled back." .
						"The current revision ID is ``$currentID''", 'alreadyrolled');
			case Article::ONLY_AUTHOR:
				$this->dieUsage("User ``$username'' is the only author of the page", 'onlyauthor');
			case Article::RATE_LIMITED:
				$this->dieUsage("You can't rollback too many articles in too short a time. Please wait a little while and try again", 'ratelimited');
			default:
				// rollback() has apparently invented a new error, which is extremely weird
				$this->dieDebug(__METHOD__, "rollback() returned an unknown error ($retval)");
		}
		// $retval has to be Article::SUCCESS if we get here
		$dbw->commit();
		$current = $target = $summary = NULL;
		extract($details);

		$info = array(
			'title' => $titleObj->getPrefixedText(),
			'pageid' => $current->getPage(),
			'summary' => $summary,
			'revid' => $titleObj->getLatestRevID(),
			'old_revid' => $current->getID(),
			'last_revid' => $target->getID()
		);

		$this->getResult()->addValue(null, $this->getModuleName(), $info);
	}
	
	protected function getAllowedParams() {
		return array (
			'title' => null,
			'user' => null,
			'token' => null,
			'summary' => null,
			'markbot' => false
		);
	}

	protected function getParamDescription() {
		return array (
			'title' => 'Title of the page you want to rollback.',
			'user' => 'Name of the user whose edits are to be rolled back. If set incorrectly, you\'ll get a badtoken error.',
			'token' => 'A rollback token previously retrieved through prop=info',
			'summary' => 'Custom edit summary. If not set, default summary will be used.',
			'markbot' => 'Mark the reverted edits and the revert as bot edits'
		);
	}

	protected function getDescription() {
		return array(
				'Undoes the last edit to the page. If the last user who edited the page made multiple edits in a row,',
				'they will all be rolled back. You need to be logged in as a sysop to use this function, see also action=login.'
			);
	}

	protected function getExamples() {
		return array (
			'api.php?action=rollback&title=Main%20Page&user=Catrope&token=123ABC',
			'api.php?action=rollback&title=Main%20Page&user=217.121.114.116&token=123ABC&summary=Reverting%20vandalism&markbot=1'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiRollback.php 22289 2007-05-20 23:31:44Z yurik $';
	}
}
