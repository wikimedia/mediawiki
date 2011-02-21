<?php
/**
 *
 *
 * Created on Sep 10, 2007
 *
 * Copyright © 2007 Roan Kattouw <Firstname>.<Lastname>@gmail.com
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
	require_once( 'ApiQueryBase.php' );
}

/**
 * Query module to enumerate all available pages.
 *
 * @ingroup API
 */
class ApiQueryBlocks extends ApiQueryBase {

	var $users;

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'bk' );
	}

	public function execute() {
		global $wgUser;

		$params = $this->extractRequestParams();
		if ( isset( $params['users'] ) && isset( $params['ip'] ) ) {
			$this->dieUsage( 'bkusers and bkip cannot be used together', 'usersandip' );
		}

		$prop = array_flip( $params['prop'] );
		$fld_id = isset( $prop['id'] );
		$fld_user = isset( $prop['user'] );
		$fld_by = isset( $prop['by'] );
		$fld_timestamp = isset( $prop['timestamp'] );
		$fld_expiry = isset( $prop['expiry'] );
		$fld_reason = isset( $prop['reason'] );
		$fld_range = isset( $prop['range'] );
		$fld_flags = isset( $prop['flags'] );

		$result = $this->getResult();

		$this->addTables( 'ipblocks' );
		$this->addFields( 'ipb_auto' );

		if ( $fld_id ) {
			$this->addFields( 'ipb_id' );
		}
		if ( $fld_user ) {
			$this->addFields( array( 'ipb_address', 'ipb_user' ) );
		}
		if ( $fld_by ) {
			$this->addFields( 'ipb_by_text' );
		}
		if ( $fld_timestamp ) {
			$this->addFields( 'ipb_timestamp' );
		}
		if ( $fld_expiry ) {
			$this->addFields( 'ipb_expiry' );
		}
		if ( $fld_reason ) {
			$this->addFields( 'ipb_reason' );
		}
		if ( $fld_range ) {
			$this->addFields( array( 'ipb_range_start', 'ipb_range_end' ) );
		}
		if ( $fld_flags ) {
			$this->addFields( array( 'ipb_anon_only', 'ipb_create_account', 'ipb_enable_autoblock', 'ipb_block_email', 'ipb_deleted', 'ipb_allow_usertalk' ) );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$this->addWhereRange( 'ipb_timestamp', $params['dir'], $params['start'], $params['end'] );
		if ( isset( $params['ids'] ) ) {
			$this->addWhereFld( 'ipb_id', $params['ids'] );
		}
		if ( isset( $params['users'] ) ) {
			foreach ( (array)$params['users'] as $u ) {
				$this->prepareUsername( $u );
			}
			$this->addWhereFld( 'ipb_address', $this->usernames );
			$this->addWhereFld( 'ipb_auto', 0 );
		}
		if ( isset( $params['ip'] ) ) {
			list( $ip, $range ) = IP::parseCIDR( $params['ip'] );
			if ( $ip && $range ) {
				// We got a CIDR range
				if ( $range < 16 )
					$this->dieUsage( 'CIDR ranges broader than /16 are not accepted', 'cidrtoobroad' );
				$lower = wfBaseConvert( $ip, 10, 16, 8, false );
				$upper = wfBaseConvert( $ip + pow( 2, 32 - $range ) - 1, 10, 16, 8, false );
			} else {
				$lower = $upper = IP::toHex( $params['ip'] );
			}
			$prefix = substr( $lower, 0, 4 );

			$db = $this->getDB();
			$this->addWhere( array(
				'ipb_range_start' . $db->buildLike( $prefix, $db->anyString() ),
				"ipb_range_start <= '$lower'",
				"ipb_range_end >= '$upper'",
				'ipb_auto' => 0
			) );
		}

		if ( !$wgUser->isAllowed( 'hideuser' ) ) {
			$this->addWhereFld( 'ipb_deleted', 0 );
		}

		// Purge expired entries on one in every 10 queries
		if ( !mt_rand( 0, 10 ) ) {
			Block::purgeExpired();
		}

		$res = $this->select( __METHOD__ );

		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've had enough
				$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->ipb_timestamp ) );
				break;
			}
			$block = array();
			if ( $fld_id ) {
				$block['id'] = $row->ipb_id;
			}
			if ( $fld_user && !$row->ipb_auto ) {
				$block['user'] = $row->ipb_address;
			}
			if ( $fld_by ) {
				$block['by'] = $row->ipb_by_text;
			}
			if ( $fld_timestamp ) {
				$block['timestamp'] = wfTimestamp( TS_ISO_8601, $row->ipb_timestamp );
			}
			if ( $fld_expiry ) {
				$block['expiry'] = Block::decodeExpiry( $row->ipb_expiry, TS_ISO_8601 );
			}
			if ( $fld_reason ) {
				$block['reason'] = $row->ipb_reason;
			}
			if ( $fld_range && !$row->ipb_auto ) {
				$block['rangestart'] = IP::hexToQuad( $row->ipb_range_start );
				$block['rangeend'] = IP::hexToQuad( $row->ipb_range_end );
			}
			if ( $fld_flags ) {
				// For clarity, these flags use the same names as their action=block counterparts
				if ( $row->ipb_auto ) {
					$block['automatic'] = '';
				}
				if ( $row->ipb_anon_only ) {
					$block['anononly'] = '';
				}
				if ( $row->ipb_create_account ) {
					$block['nocreate'] = '';
				}
				if ( $row->ipb_enable_autoblock ) {
					$block['autoblock'] = '';
				}
				if ( $row->ipb_block_email ) {
					$block['noemail'] = '';
				}
				if ( $row->ipb_deleted ) {
					$block['hidden'] = '';
				}
				if ( $row->ipb_allow_usertalk ) {
					$block['allowusertalk'] = '';
				}
			}
			$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $block );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->ipb_timestamp ) );
				break;
			}
		}
		$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'block' );
	}

	protected function prepareUsername( $user ) {
		if ( !$user ) {
			$this->dieUsage( 'User parameter may not be empty', 'param_user' );
		}
		$name = User::isIP( $user )
			? $user
			: User::getCanonicalName( $user, 'valid' );
		if ( $name === false ) {
			$this->dieUsage( "User name {$user} is not valid", 'param_user' );
		}
		$this->usernames[] = $name;
	}

	public function getAllowedParams() {
		return array(
			'start' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase::PARAM_TYPE => 'timestamp',
			),
			'dir' => array(
				ApiBase::PARAM_TYPE => array(
					'newer',
					'older'
				),
				ApiBase::PARAM_DFLT => 'older'
			),
			'ids' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_ISMULTI => true
			),
			'users' => array(
				ApiBase::PARAM_ISMULTI => true
			),
			'ip' => null,
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'prop' => array(
				ApiBase::PARAM_DFLT => 'id|user|by|timestamp|expiry|reason|flags',
				ApiBase::PARAM_TYPE => array(
					'id',
					'user',
					'by',
					'timestamp',
					'expiry',
					'reason',
					'range',
					'flags'
				),
				ApiBase::PARAM_ISMULTI => true
			)
		);
	}

	public function getParamDescription() {
		return array(
			'start' => 'The timestamp to start enumerating from',
			'end' => 'The timestamp to stop enumerating at',
			'dir' => 'The direction in which to enumerate',
			'ids' => 'Pipe-separated list of block IDs to list (optional)',
			'users' => 'Pipe-separated list of users to search for (optional)',
			'ip' => array(	'Get all blocks applying to this IP or CIDR range, including range blocks.',
					'Cannot be used together with bkusers. CIDR ranges broader than /16 are not accepted' ),
			'limit' => 'The maximum amount of blocks to list',
			'prop' => array(
				'Which properties to get',
				' id         - Adds the ID of the block',
				' user       - Adds the username of the blocked user',
				' by         - Adds the username of the blocking admin',
				' timestamp  - Adds the timestamp of when the block was given',
				' expiry     - Adds the timestamp of when the block expires',
				' reason     - Adds the reason given for the block',
				' range      - Adds the range of IPs affected by the block',
				' flags      - Tags the ban with (autoblock, anononly, etc)',
			),
		);
	}

	public function getDescription() {
		return 'List all blocked users and IP addresses';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'usersandip', 'info' => 'bkusers and bkip cannot be used together' ),
			array( 'code' => 'cidrtoobroad', 'info' => 'CIDR ranges broader than /16 are not accepted' ),
			array( 'code' => 'param_user', 'info' => 'User parameter may not be empty' ),
			array( 'code' => 'param_user', 'info' => 'User name user is not valid' ),
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&list=blocks',
			'api.php?action=query&list=blocks&bkusers=Alice|Bob'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
