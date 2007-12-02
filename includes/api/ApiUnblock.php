<?php

/*
 * Created on Sep 7, 2007
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
class ApiUnblock extends ApiBase {

	public function __construct($main, $action) {
		parent :: __construct($main, $action);
	}

	public function execute() {
		global $wgUser;
		$this->requestWriteMode();
		$params = $this->extractRequestParams();

		if($params['gettoken'])
		{
			$res['unblocktoken'] = $wgUser->editToken();
			$this->getResult()->addValue(null, $this->getModuleName(), $res);
			return;
		}

		if(is_null($params['id']) && is_null($params['user']))
			$this->dieUsage('Either the id or the user parameter must be set', 'notarget');
		if(!is_null($params['id']) && !is_null($params['user']))
			$this->dieUsage('The id and user parameters can\'t be used together', 'idanduser');
		if(is_null($params['token']))
			$this->dieUsage('The token parameter must be set', 'notoken');
		if(!$wgUser->matchEditToken($params['token']))
			$this->dieUsage('Invalid token', 'badtoken');
		if(!$wgUser->isAllowed('block'))
			$this->dieUsage('You don\'t have permission to unblock users', 'permissiondenied');
		if(wfReadOnly())
			$this->dieUsage('The wiki is in read-only mode', 'readonly');

		$id = $params['id'];
		$user = $params['user'];
		$reason = $params['reason'];
		$dbw = wfGetDb(DB_MASTER);
		$dbw->begin();
		$retval = IPUnblockForm::doUnblock(&$id, &$user, &$reason, &$range);

		switch($retval)
		{
			case IPUnblockForm::UNBLOCK_SUCCESS:
				break; // We'll deal with that later
			case IPUnblockForm::UNBLOCK_NO_SUCH_ID:
				$this->dieUsage("There is no block with ID ``$id''", 'nosuchid');
			case IPUnblockForm::UNBLOCK_USER_NOT_BLOCKED:
				$this->dieUsage("User ``$user'' is not blocked", 'notblocked');
			case IPUnblockForm::UNBLOCK_BLOCKED_AS_RANGE:
				$this->dieUsage("IP address ``$user'' was blocked as part of range ``$range''. You can't unblock the IP invidually, but you can unblock the range as a whole.", 'blockedasrange');
			case IPUnblockForm::UNBLOCK_UNKNOWNERR:
				$this->dieUsage("Unknown error", 'unknownerr');
			default:
				$this->dieDebug(__METHOD__, "IPBlockForm::doBlock() returned an unknown error ($retval)");
		}
		$dbw->commit();
		
		$res['id'] = $id;
		$res['user'] = $user;
		$res['reason'] = $reason;
		$this->getResult()->addValue(null, $this->getModuleName(), $res);
	}

	protected function getAllowedParams() {
		return array (
			'id' => null,
			'user' => null,
			'token' => null,
			'gettoken' => false,
			'reason' => null,
		);
	}

	protected function getParamDescription() {
		return array (
			'id' => 'ID of the block you want to unblock (obtained through list=blocks). Cannot be user together with user',
			'user' => 'Username, IP address or IP range you want to unblock. Cannot be used together with id',
			'token' => 'An unblock token previously obtained through the gettoken parameter',
			'gettoken' => 'If set, an unblock token will be returned, and no other action will be taken',
			'reason' => 'Reason for unblock (optional)',
		);
	}

	protected function getDescription() {
		return array(
			'Unblock a user.'
		);
	}

	protected function getExamples() {
		return array (
			'api.php?action=unblock&id=105',
			'api.php?action=unblock&user=Bob&reason=Sorry%20Bob'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
