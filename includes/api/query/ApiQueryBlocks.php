<?php

/*
 * Created on Sep 10, 2007
 *
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
	require_once ('ApiQueryBase.php');
}

/**
 * Query module to enumerate all available pages.
 * 
 * @addtogroup API
 */
class ApiQueryBlocks extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'bk');
	}

	public function execute() {
		$this->run();
	}

	private function run() {
		global $wgUser;

		$params = $this->extractRequestParams();
		$prop = array_flip($params['prop']);
		$fld_id = isset($prop['id']);
		$fld_user = isset($prop['user']);
		$fld_by = isset($prop['by']);
		$fld_timestamp = isset($prop['timestamp']);
		$fld_expiry = isset($prop['expiry']);
		$fld_reason = isset($prop['reason']);
		$fld_range = isset($prop['range']);
		$fld_flags = isset($prop['flags']);

		$result = $this->getResult();
		$pageSet = $this->getPageSet();
		$titles = $pageSet->getTitles();
		$data = array();

		$this->addTables('ipblocks');
		if($fld_id)
			$this->addFields('ipb_id');
		if($fld_user)
			$this->addFields(array('ipb_address', 'ipb_user'));
		if($fld_by)
		{
			$this->addTables('user');
			$this->addFields(array('ipb_by', 'user_name'));
			$this->addWhere('user_id = ipb_by');
		}
		if($fld_timestamp)
			$this->addFields('ipb_timestamp');
		if($fld_expiry)
			$this->addFields('ipb_expiry');
		if($fld_reason)
			$this->addFields('ipb_reason');
		if($fld_range)
			$this->addFields(array('ipb_range_start', 'ipb_range_end'));
		if($fld_flags)
			$this->addFields(array('ipb_auto', 'ipb_anon_only', 'ipb_create_account', 'ipb_enable_autoblock', 'ipb_block_email', 'ipb_deleted'));

		$this->addOption('LIMIT', $params['limit'] + 1);
		$this->addWhereRange('ipb_timestamp', $params['dir'], $params['start'], $params['end']);
		if(isset($params['ids']))
			$this->addWhere(array('ipb_id' => $params['ids']));
		if(isset($params['users']))
			$this->addWhere(array('ipb_address' => $params['users']));
		if(!$wgUser->isAllowed('oversight'))
			$this->addWhere(array('ipb_deleted' => 0));

		// Purge expired entries on one in every 10 queries
		if(!mt_rand(0, 10))
			Block::purgeExpired();

		$res = $this->select(__METHOD__);
		$db = wfGetDB();

		$count = 0;
		while($row = $db->fetchObject($res))
		{
			if($count++ == $params['limit'])
			{
				// We've had enough
				$this->setContinueEnumParameter('start', wfTimestamp(TS_ISO_8601, $row->ipb_timestamp));
				break;
			}
			$block = array();
			if($fld_id)
				$block['id'] = $row->ipb_id;
			if($fld_user)
			{
				$block['user'] = $row->ipb_address;
				$block['userid'] = $row->ipb_user;
			}
			if($fld_by)
			{
				$block['by'] = $row->user_name;
				$block['byuserid'] = $row->ipb_by;
			}
			if($fld_timestamp)
				$block['timestamp'] = wfTimestamp(TS_ISO_8601, $row->ipb_timestamp);
			if($fld_expiry)
				$block['expiry'] = Block::decodeExpiry($row->ipb_expiry, TS_ISO_8601);
			if($fld_reason)
				$block['reason'] = $row->ipb_reason;
			if($fld_range)
			{
				$block['rangestart'] = $this->convertHexIP($row->ipb_range_start);
				$block['rangeend'] = $this->convertHexIP($row->ipb_range_end);
			}
			if($fld_flags)
			{
				// For clarity, these flags use the same names as their action=block counterparts
				if($row->ipb_auto)
					$block['automatic'] = '';
				if($row->ipb_anon_only)
					$block['anononly'] = '';
				if($row->ipb_create_account)
					$block['nocreate'] = '';
				if($row->ipb_enable_autoblock)
					$block['autoblock'] = '';
				if($row->ipb_block_email)
					$block['noemail'] = '';
				if($row->ipb_deleted)
					$block['hidden'] = '';
			}
			$data[] = $block;
		}
		$result->setIndexedTagName($data, 'block');
		$result->addValue('query', $this->getModuleName(), $data);
	}

	protected function convertHexIP($ip)
	{
		// Converts a hexadecimal IP to nnn.nnn.nnn.nnn format
		$dec = wfBaseConvert($ip, 16, 10);
		$parts[0] = (int)($dec / (256*256*256));
		$dec %= 256*256*256;
		$parts[1] = (int)($dec / (256*256));
		$dec %= 256*256;
		$parts[2] = (int)($dec / 256);
		$parts[3] = $dec % 256;
		return implode('.', $parts);
	}

	protected function getAllowedParams() {
		return array (
			'start' => array(
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase :: PARAM_TYPE => 'timestamp',
			),
			'dir' => array(
				ApiBase :: PARAM_TYPE => array(
					'newer',
					'older'
				),
				ApiBase :: PARAM_DFLT => 'older'
			),
			'ids' => array(
				ApiBase :: PARAM_TYPE => 'integer',
				ApiBase :: PARAM_ISMULTI => true
			),
			'users' => array(
				ApiBase :: PARAM_ISMULTI => true
			),
			'limit' => array(
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			),
			'prop' => array(
				ApiBase :: PARAM_DFLT => 'id|user|by|timestamp|expiry|reason|flags',
				ApiBase :: PARAM_TYPE => array(
						'id',
						'user',
						'by',
						'timestamp',
						'expiry',
						'reason',
						'range',
						'flags'
					),
				ApiBase :: PARAM_ISMULTI => true
			)
		);
	}

	protected function getParamDescription() {
		return array (
			'start' => 'The timestamp to start enumerating from',
			'end' => 'The timestamp to stop enumerating at',
			'dir' => 'The direction in which to enumerate',
			'ids' => 'Pipe-separated list of block IDs to list (optional)',
			'users' => 'Pipe-separated list of users to search for (optional)',
			'limit' => 'The maximum amount of blocks to list',
			'prop' => 'Which properties to get',
		);
	}

	protected function getDescription() {
		return 'List all blocked users and IP addresses.';
	}

	protected function getExamples() {
		return array (
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiQueryBlocks.php 28209 2007-12-06 16:06:22Z vasilievvv $';
	}
}
