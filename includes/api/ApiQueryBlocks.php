<?php
/**
 *
 *
 * Created on Sep 10, 2007
 *
 * Copyright © 2007 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 * Query module to enumerate all user blocks
 *
 * @ingroup API
 */
class ApiQueryBlocks extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'bk' );
	}

	public function execute() {
		global $wgContLang;

		$db = $this->getDB();
		$params = $this->extractRequestParams();
		$this->requireMaxOneParameter( $params, 'users', 'ip' );

		$prop = array_flip( $params['prop'] );
		$fld_id = isset( $prop['id'] );
		$fld_user = isset( $prop['user'] );
		$fld_userid = isset( $prop['userid'] );
		$fld_by = isset( $prop['by'] );
		$fld_byid = isset( $prop['byid'] );
		$fld_timestamp = isset( $prop['timestamp'] );
		$fld_expiry = isset( $prop['expiry'] );
		$fld_reason = isset( $prop['reason'] );
		$fld_range = isset( $prop['range'] );
		$fld_flags = isset( $prop['flags'] );

		$result = $this->getResult();

		$this->addTables( 'ipblocks' );
		$this->addFields( array( 'ipb_auto', 'ipb_id', 'ipb_timestamp' ) );

		$this->addFieldsIf( array( 'ipb_address', 'ipb_user' ), $fld_user || $fld_userid );
		$this->addFieldsIf( 'ipb_by_text', $fld_by );
		$this->addFieldsIf( 'ipb_by', $fld_byid );
		$this->addFieldsIf( 'ipb_expiry', $fld_expiry );
		$this->addFieldsIf( 'ipb_reason', $fld_reason );
		$this->addFieldsIf( array( 'ipb_range_start', 'ipb_range_end' ), $fld_range );
		$this->addFieldsIf( array( 'ipb_anon_only', 'ipb_create_account', 'ipb_enable_autoblock',
			'ipb_block_email', 'ipb_deleted', 'ipb_allow_usertalk' ),
			$fld_flags );

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$this->addTimestampWhereRange(
			'ipb_timestamp',
			$params['dir'],
			$params['start'],
			$params['end']
		);
		// Include in ORDER BY for uniqueness
		$this->addWhereRange( 'ipb_id', $params['dir'], null, null );

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$op = ( $params['dir'] == 'newer' ? '>' : '<' );
			$continueTimestamp = $db->addQuotes( $db->timestamp( $cont[0] ) );
			$continueId = (int)$cont[1];
			$this->dieContinueUsageIf( $continueId != $cont[1] );
			$this->addWhere( "ipb_timestamp $op $continueTimestamp OR " .
				"(ipb_timestamp = $continueTimestamp AND " .
				"ipb_id $op= $continueId)"
			);
		}

		if ( isset( $params['ids'] ) ) {
			$this->addWhereFld( 'ipb_id', $params['ids'] );
		}
		if ( isset( $params['users'] ) ) {
			$usernames = array();
			foreach ( (array)$params['users'] as $u ) {
				$usernames[] = $this->prepareUsername( $u );
			}
			$this->addWhereFld( 'ipb_address', $usernames );
			$this->addWhereFld( 'ipb_auto', 0 );
		}
		if ( isset( $params['ip'] ) ) {
			$blockCIDRLimit = $this->getConfig()->get( 'BlockCIDRLimit' );
			if ( IP::isIPv4( $params['ip'] ) ) {
				$type = 'IPv4';
				$cidrLimit = $blockCIDRLimit['IPv4'];
				$prefixLen = 0;
			} elseif ( IP::isIPv6( $params['ip'] ) ) {
				$type = 'IPv6';
				$cidrLimit = $blockCIDRLimit['IPv6'];
				$prefixLen = 3; // IP::toHex output is prefixed with "v6-"
			} else {
				$this->dieUsage( 'IP parameter is not valid', 'param_ip' );
			}

			# Check range validity, if it's a CIDR
			list( $ip, $range ) = IP::parseCIDR( $params['ip'] );
			if ( $ip !== false && $range !== false && $range < $cidrLimit ) {
				$this->dieUsage(
					"$type CIDR ranges broader than /$cidrLimit are not accepted",
					'cidrtoobroad'
				);
			}

			# Let IP::parseRange handle calculating $upper, instead of duplicating the logic here.
			list( $lower, $upper ) = IP::parseRange( $params['ip'] );

			# Extract the common prefix to any rangeblock affecting this IP/CIDR
			$prefix = substr( $lower, 0, $prefixLen + floor( $cidrLimit / 4 ) );

			# Fairly hard to make a malicious SQL statement out of hex characters,
			# but it is good practice to add quotes
			$lower = $db->addQuotes( $lower );
			$upper = $db->addQuotes( $upper );

			$this->addWhere( array(
				'ipb_range_start' . $db->buildLike( $prefix, $db->anyString() ),
				'ipb_range_start <= ' . $lower,
				'ipb_range_end >= ' . $upper,
				'ipb_auto' => 0
			) );
		}

		if ( !is_null( $params['show'] ) ) {
			$show = array_flip( $params['show'] );

			/* Check for conflicting parameters. */
			if ( ( isset( $show['account'] ) && isset( $show['!account'] ) )
				|| ( isset( $show['ip'] ) && isset( $show['!ip'] ) )
				|| ( isset( $show['range'] ) && isset( $show['!range'] ) )
				|| ( isset( $show['temp'] ) && isset( $show['!temp'] ) )
			) {
				$this->dieUsageMsg( 'show' );
			}

			$this->addWhereIf( 'ipb_user = 0', isset( $show['!account'] ) );
			$this->addWhereIf( 'ipb_user != 0', isset( $show['account'] ) );
			$this->addWhereIf( 'ipb_user != 0 OR ipb_range_end > ipb_range_start', isset( $show['!ip'] ) );
			$this->addWhereIf( 'ipb_user = 0 AND ipb_range_end = ipb_range_start', isset( $show['ip'] ) );
			$this->addWhereIf( 'ipb_expiry = ' .
				$db->addQuotes( $db->getInfinity() ), isset( $show['!temp'] ) );
			$this->addWhereIf( 'ipb_expiry != ' .
				$db->addQuotes( $db->getInfinity() ), isset( $show['temp'] ) );
			$this->addWhereIf( 'ipb_range_end = ipb_range_start', isset( $show['!range'] ) );
			$this->addWhereIf( 'ipb_range_end > ipb_range_start', isset( $show['range'] ) );
		}

		if ( !$this->getUser()->isAllowed( 'hideuser' ) ) {
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
				$this->setContinueEnumParameter( 'continue', "$row->ipb_timestamp|$row->ipb_id" );
				break;
			}
			$block = array(
				ApiResult::META_TYPE => 'assoc',
			);
			if ( $fld_id ) {
				$block['id'] = $row->ipb_id;
			}
			if ( $fld_user && !$row->ipb_auto ) {
				$block['user'] = $row->ipb_address;
			}
			if ( $fld_userid && !$row->ipb_auto ) {
				$block['userid'] = $row->ipb_user;
			}
			if ( $fld_by ) {
				$block['by'] = $row->ipb_by_text;
			}
			if ( $fld_byid ) {
				$block['byid'] = $row->ipb_by;
			}
			if ( $fld_timestamp ) {
				$block['timestamp'] = wfTimestamp( TS_ISO_8601, $row->ipb_timestamp );
			}
			if ( $fld_expiry ) {
				$block['expiry'] = $wgContLang->formatExpiry( $row->ipb_expiry, TS_ISO_8601 );
			}
			if ( $fld_reason ) {
				$block['reason'] = $row->ipb_reason;
			}
			if ( $fld_range && !$row->ipb_auto ) {
				$block['rangestart'] = IP::formatHex( $row->ipb_range_start );
				$block['rangeend'] = IP::formatHex( $row->ipb_range_end );
			}
			if ( $fld_flags ) {
				// For clarity, these flags use the same names as their action=block counterparts
				$block['automatic'] = (bool)$row->ipb_auto;
				$block['anononly'] = (bool)$row->ipb_anon_only;
				$block['nocreate'] = (bool)$row->ipb_create_account;
				$block['autoblock'] = (bool)$row->ipb_enable_autoblock;
				$block['noemail'] = (bool)$row->ipb_block_email;
				$block['hidden'] = (bool)$row->ipb_deleted;
				$block['allowusertalk'] = (bool)$row->ipb_allow_usertalk;
			}
			$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $block );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', "$row->ipb_timestamp|$row->ipb_id" );
				break;
			}
		}
		$result->addIndexedTagName( array( 'query', $this->getModuleName() ), 'block' );
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
		return $name;
	}

	public function getAllowedParams() {
		$blockCIDRLimit = $this->getConfig()->get( 'BlockCIDRLimit' );

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
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
			),
			'ids' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_ISMULTI => true
			),
			'users' => array(
				ApiBase::PARAM_ISMULTI => true
			),
			'ip' => array(
				ApiBase::PARAM_HELP_MSG => array(
					'apihelp-query+blocks-param-ip',
					$blockCIDRLimit['IPv4'],
					$blockCIDRLimit['IPv6'],
				),
			),
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
					'userid',
					'by',
					'byid',
					'timestamp',
					'expiry',
					'reason',
					'range',
					'flags'
				),
				ApiBase::PARAM_ISMULTI => true
			),
			'show' => array(
				ApiBase::PARAM_TYPE => array(
					'account',
					'!account',
					'temp',
					'!temp',
					'ip',
					'!ip',
					'range',
					'!range',
				),
				ApiBase::PARAM_ISMULTI => true
			),
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&list=blocks'
				=> 'apihelp-query+blocks-example-simple',
			'action=query&list=blocks&bkusers=Alice|Bob'
				=> 'apihelp-query+blocks-example-users',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Blocks';
	}
}
