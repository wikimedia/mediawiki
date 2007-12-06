<?php

/*
 * Created on Sep 1, 2007
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
class ApiProtect extends ApiBase {

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
		if(!isset($params['token']))
			$this->dieUsage('The token parameter must be set', 'notoken');
		if(!isset($params['protections']) || empty($params['protections']))
			$this->dieUsage('The protections parameter must be set', 'noprotections');

		if($wgUser->isBlocked())
			$this->dieUsage('You have been blocked from editing', 'blocked');
		if(wfReadOnly())
			$this->dieUsage('The wiki is in read-only mode', 'readonly');
		if(!$wgUser->matchEditToken($params['token']))
			$this->dieUsage('Invalid token', 'badtoken');

		$titleObj = Title::newFromText($params['title']);
		if(!$titleObj)
			$this->dieUsage("Bad title ``{$params['title']}''", 'invalidtitle');
		if(!$titleObj->exists())
			$this->dieUsage("``{$params['title']}'' doesn't exist", 'missingtitle');
		if(!$titleObj->userCan('protect'))
			$this->dieUsage('You don\'t have permission to change protection levels', 'permissiondenied');
		$articleObj = new Article($titleObj);
		
		if(in_array($params['expiry'], array('infinite', 'indefinite', 'never')))
			$expiry = Block::infinity();
		else
		{
			$expiry = strtotime($params['expiry']);
			if($expiry < 0 || $expiry == false)
				$this->dieUsage('Invalid expiry time', 'invalidexpiry');
			
			$expiry = wfTimestamp(TS_MW, $expiry);
			if($expiry < wfTimestampNow())
				$this->dieUsage('Expiry time is in the past', 'pastexpiry');
		}

		$protections = array();
		foreach($params['protections'] as $prot)
		{
			$p = explode('=', $prot);
			$protections[$p[0]] = ($p[1] == 'all' ? '' : $p[1]);
		}

		$dbw = wfGetDb(DB_MASTER);
		$dbw->begin();
		$ok = $articleObj->updateRestrictions($protections, $params['reason'], $params['cascade'], $expiry);
		if(!$ok)
			// This is very weird. Maybe the article was deleted or the user was blocked/desysopped in the meantime?
			$this->dieUsage('Unknown error', 'unknownerror');
		$dbw->commit();
		$res = array('title' => $titleObj->getPrefixedText(), 'reason' => $params['reason'], 'expiry' => $expiry);
		if($params['cascade'])
			$res['cascade'] = '';
		$res['protections'] = $protections;
		$this->getResult()->addValue(null, $this->getModuleName(), $res);
	}

	protected function getAllowedParams() {
		return array (
			'title' => null,
			'token' => null,
			'protections' => array(
				ApiBase :: PARAM_ISMULTI => true
			),
			'expiry' => 'infinite',
			'reason' => '',
			'cascade' => false
		);
	}

	protected function getParamDescription() {
		return array (
			'title' => 'Title of the page you want to restore.',
			'token' => 'A protect token previously retrieved through prop=info',
			'protections' => 'Pipe-separated list of protection levels, formatted action=group (e.g. edit=sysop)',
			'expiry' => 'Expiry timestamp. If set to \'infinite\', \'indefinite\' or \'never\', the protection will never expire.',
			'reason' => 'Reason for (un)protecting (optional)',
			'cascade' => 'Enable cascading protection (i.e. protect pages included in this page)'
		);
	}

	protected function getDescription() {
		return array(
			'Change the protection level of a page.'
		);
	}

	protected function getExamples() {
		return array (
			'api.php?action=protect&title=Main%20Page&token=123ABC&protections=edit=sysop|move=sysop&cascade&expiry=20070901163000',
			'api.php?action=protect&title=Main%20Page&token=123ABC&protections=edit=all|move=all&reason=Lifting%20restrictions'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
