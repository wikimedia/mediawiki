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

if (!defined('MEDIAWIKI')) {
	// Eclipse helper - will be ignored in production
	require_once ("ApiBase.php");
}


/**
 * @addtogroup API
 */
class ApiMove extends ApiBase {

	public function __construct($main, $action) {
		parent :: __construct($main, $action);
	}
	
	public function execute() {
		global $wgUser;
		$this->getMain()->requestWriteMode();
		$params = $this->extractRequestParams();
		if(is_null($params['reason']))
			$params['reason'] = '';
	
		$titleObj = NULL;
		if(!isset($params['from']))
			$this->dieUsageMsg(array('missingparam', 'from'));
		if(!isset($params['to']))
			$this->dieUsageMsg(array('missingparam', 'to'));
		if(!isset($params['token']))
			$this->dieUsageMsg(array('missingparam', 'token'));
		if(!$wgUser->matchEditToken($params['token']))
			$this->dieUsageMsg(array('sessionfailure'));

		$fromTitle = Title::newFromText($params['from']);
		if(!$fromTitle)
			$this->dieUsageMsg(array('invalidtitle', $params['from']));
		if(!$fromTitle->exists())
			$this->dieUsageMsg(array('notanarticle'));
		$fromTalk = $fromTitle->getTalkPage();

		
		$toTitle = Title::newFromText($params['to']);
		if(!$toTitle)
			$this->dieUsageMsg(array('invalidtitle', $params['to']));
		$toTalk = $toTitle->getTalkPage();

		$dbw = wfGetDB(DB_MASTER);
		$dbw->begin();		
		$retval = $fromTitle->moveTo($toTitle, true, $params['reason'], !$params['noredirect']);
		if($retval !== true)
			$this->dieUsageMsg(array($retval));

		$r = array('from' => $fromTitle->getPrefixedText(), 'to' => $toTitle->getPrefixedText(), 'reason' => $params['reason']);
		if(!$params['noredirect'])
			$r['redirectcreated'] = '';
	
		if($params['movetalk'] && $fromTalk->exists() && !$fromTitle->isTalkPage())
		{
			// We need to move the talk page as well
			$toTalk = $toTitle->getTalkPage();
			$retval = $fromTalk->moveTo($toTalk, true, $params['reason'], !$params['noredirect']);
			if($retval === true)
			{
				$r['talkfrom'] = $fromTalk->getPrefixedText();
				$r['talkto'] = $toTalk->getPrefixedText();
			}
			// We're not gonna dieUsage() on failure, since we already changed something
			else
			{
				$r['talkmove-error-code'] = ApiBase::$messageMap[$retval]['code'];
				$r['talkmove-error-info'] = ApiBase::$messageMap[$retval]['info'];
			}	
		}
		$dbw->commit(); // Make sure all changes are really written to the DB
		$this->getResult()->addValue(null, $this->getModuleName(), $r);
	}
	
	protected function getAllowedParams() {
		return array (
			'from' => null,
			'to' => null,
			'token' => null,
			'reason' => null,
			'movetalk' => false,
			'noredirect' => false
		);
	}

	protected function getParamDescription() {
		return array (
			'from' => 'Title of the page you want to move.',
			'to' => 'Title you want to rename the page to.',
			'token' => 'A move token previously retrieved through prop=info',
			'reason' => 'Reason for the move (optional).',
			'movetalk' => 'Move the talk page, if it exists.',
			'noredirect' => 'Don\'t create a redirect'
		);
	}

	protected function getDescription() {
		return array(
			'Moves a page.'
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
