<?php

/*
 * Created on Sep 4, 2007
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
class ApiBlock extends ApiBase {

	public function __construct($main, $action) {
		parent :: __construct($main, $action);
	}

	public function execute() {
		global $wgUser;
		$this->getMain()->requestWriteMode();
		$params = $this->extractRequestParams();

		if($params['gettoken'])
		{
			$res['blocktoken'] = $wgUser->editToken();
			$this->getResult()->addValue(null, $this->getModuleName(), $res);
			return;
		}

		if(is_null($params['user']))
			$this->dieUsage('The user parameter must be set', 'nouser');
		if(is_null($params['token']))
			$this->dieUsage('The token parameter must be set', 'notoken');
		if(!$wgUser->matchEditToken($params['token']))
			$this->dieUsage('Invalid token', 'badtoken');
		if(!$wgUser->isAllowed('block'))
			$this->dieUsage('You don\'t have permission to block users', 'permissiondenied');
		if($params['hidename'] && !$wgUser->isAllowed('hideuser'))
			$this->dieUsage('You don\'t have permission to hide user names from the block log', 'nohide');
		if(wfReadOnly())
			$this->dieUsage('The wiki is in read-only mode', 'readonly');

		$form = new IPBlockForm('');
		$form->BlockAddress = $params['user'];
		$form->BlockReason = $params['reason'];
		$form->BlockReasonList = 'other';
		$form->BlockExpiry = ($params['expiry'] == 'never' ? 'infinite' : $params['expiry']);
		$form->BlockOther = '';
		$form->BlockAnonOnly = $params['anononly'];
		$form->BlockCreateAccount = $params['nocreate'];
		$form->BlockEnableAutoBlock = $params['autoblock'];
		$form->BlockEmail = $params['noemail'];
		$form->BlockHideName = $params['hidename'];

		$dbw = wfGetDb(DB_MASTER);
		$dbw->begin();
		$retval = $form->doBlock($userID, $expiry);
		switch($retval)
		{
			case IPBlockForm::BLOCK_SUCCESS:
				break; // We'll deal with that later
			case IPBlockForm::BLOCK_RANGE_INVALID:
				$this->dieUsage("Invalid IP range ``{$params['user']}''", 'invalidrange');
			case IPBlockForm::BLOCK_RANGE_DISABLED:
				$this->dieUsage('Blocking IP ranges has been disabled', 'rangedisabled');
			case IPBlockForm::BLOCK_NONEXISTENT_USER:
				$this->dieUsage("User ``{$params['user']}'' doesn't exist", 'nosuchuser');
			case IPBlockForm::BLOCK_IP_INVALID:
				$this->dieUsage("Invaild IP address ``{$params['user']}''", 'invalidip');
			case IPBlockForm::BLOCK_EXPIRY_INVALID:
				$this->dieUsage("Invalid expiry time ``{$params['expiry']}''", 'invalidexpiry');
			case IPBlockForm::BLOCK_ALREADY_BLOCKED:
				$this->dieUsage("User ``{$params['user']}'' is already blocked", 'alreadyblocked');
			default:
				$this->dieDebug(__METHOD__, "IPBlockForm::doBlock() returned an unknown error ($retval)");
		}
		$dbw->commit();
		
		$res['user'] = $params['user'];
		$res['userID'] = $userID;
		$res['expiry'] = ($expiry == Block::infinity() ? 'infinite' : $expiry);
		$res['reason'] = $params['reason'];
		if($params['anononly'])
			$res['anononly'] = '';
		if($params['nocreate'])
			$res['nocreate'] = '';
		if($params['autoblock'])
			$res['autoblock'] = '';
		if($params['noemail'])
			$res['noemail'] = '';
		if($params['hidename'])
			$res['hidename'] = '';

		$this->getResult()->addValue(null, $this->getModuleName(), $res);
	}

	protected function getAllowedParams() {
		return array (
			'user' => null,
			'token' => null,
			'gettoken' => false,
			'expiry' => 'never',
			'reason' => null,
			'anononly' => false,
			'nocreate' => false,
			'autoblock' => false,
			'noemail' => false,
			'hidename' => false,
		);
	}

	protected function getParamDescription() {
		return array (
			'user' => 'Username, IP address or IP range you want to block',
			'token' => 'A block token previously obtained through the gettoken parameter',
			'gettoken' => 'If set, a block token will be returned, and no other action will be taken',
			'expiry' => 'Relative expiry time, e.g. \'5 months\' or \'2 weeks\'. If set to \'infinite\', \'indefinite\' or \'never\', the block will never expire.',
			'reason' => 'Reason for block (optional)',
			'anononly' => 'Block anonymous users only (i.e. disable anonymous edits for this IP)',
			'nocreate' => 'Prevent account creation',
			'autoblock' => 'Automatically block the last used IP address, and any subsequent IP addresses they try to login from',
			'noemail' => 'Prevent user from sending e-mail through the wiki',
			'hidename' => 'Hide the username from the block log.'
		);
	}

	protected function getDescription() {
		return array(
			'Block a user.'
		);
	}

	protected function getExamples() {
		return array (
			'api.php?action=block&user=123.5.5.12&expiry=3%20days&reason=First%20strike',
			'api.php?action=block&user=Vandal&expiry=never&reason=Vandalism&nocreate&autoblock&noemail'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
