<?php
/**
 *
 *
 * Created on July 7, 2007
 *
 * Copyright © 2007 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 * Query module to enumerate all registered users.
 *
 * @ingroup API
 */
class ApiQueryAllUsers extends ApiQueryBase {
	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'au' );
	}

	/**
	 * This function converts the user name to a canonical form
	 * which is stored in the database.
	 * @param string $name
	 * @return string
	 */
	private function getCanonicalUserName( $name ) {
		return strtr( $name, '_', ' ' );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$activeUserDays = $this->getConfig()->get( 'ActiveUserDays' );

		$db = $this->getDB();

		$prop = $params['prop'];
		if ( !is_null( $prop ) ) {
			$prop = array_flip( $prop );
			$fld_blockinfo = isset( $prop['blockinfo'] );
			$fld_editcount = isset( $prop['editcount'] );
			$fld_groups = isset( $prop['groups'] );
			$fld_rights = isset( $prop['rights'] );
			$fld_registration = isset( $prop['registration'] );
			$fld_implicitgroups = isset( $prop['implicitgroups'] );
			$fld_centralids = isset( $prop['centralids'] );
		} else {
			$fld_blockinfo = $fld_editcount = $fld_groups = $fld_registration =
				$fld_rights = $fld_implicitgroups = $fld_centralids = false;
		}

		$limit = $params['limit'];

		$this->addTables( 'user' );

		$dir = ( $params['dir'] == 'descending' ? 'older' : 'newer' );
		$from = is_null( $params['from'] ) ? null : $this->getCanonicalUserName( $params['from'] );
		$to = is_null( $params['to'] ) ? null : $this->getCanonicalUserName( $params['to'] );

		# MySQL can't figure out that 'user_name' and 'qcc_title' are the same
		# despite the JOIN condition, so manually sort on the correct one.
		$userFieldToSort = $params['activeusers'] ? 'qcc_title' : 'user_name';

		# Some of these subtable joins are going to give us duplicate rows, so
		# calculate the maximum number of duplicates we might see.
		$maxDuplicateRows = 1;

		$this->addWhereRange( $userFieldToSort, $dir, $from, $to );

		if ( !is_null( $params['prefix'] ) ) {
			$this->addWhere( $userFieldToSort .
				$db->buildLike( $this->getCanonicalUserName( $params['prefix'] ), $db->anyString() ) );
		}

		if ( !is_null( $params['rights'] ) && count( $params['rights'] ) ) {
			$groups = [];
			foreach ( $params['rights'] as $r ) {
				$groups = array_merge( $groups, User::getGroupsWithPermission( $r ) );
			}

			// no group with the given right(s) exists, no need for a query
			if ( !count( $groups ) ) {
				$this->getResult()->addIndexedTagName( [ 'query', $this->getModuleName() ], '' );

				return;
			}

			$groups = array_unique( $groups );

			if ( is_null( $params['group'] ) ) {
				$params['group'] = $groups;
			} else {
				$params['group'] = array_unique( array_merge( $params['group'], $groups ) );
			}
		}

		if ( !is_null( $params['group'] ) && !is_null( $params['excludegroup'] ) ) {
			$this->dieUsage( 'group and excludegroup cannot be used together', 'group-excludegroup' );
		}

		if ( !is_null( $params['group'] ) && count( $params['group'] ) ) {
			// Filter only users that belong to a given group. This might
			// produce as many rows-per-user as there are groups being checked.
			$this->addTables( 'user_groups', 'ug1' );
			$this->addJoinConds( [ 'ug1' => [ 'INNER JOIN', [ 'ug1.ug_user=user_id',
				'ug1.ug_group' => $params['group'] ] ] ] );
			$maxDuplicateRows *= count( $params['group'] );
		}

		if ( !is_null( $params['excludegroup'] ) && count( $params['excludegroup'] ) ) {
			// Filter only users don't belong to a given group. This can only
			// produce one row-per-user, because we only keep on "no match".
			$this->addTables( 'user_groups', 'ug1' );

			if ( count( $params['excludegroup'] ) == 1 ) {
				$exclude = [ 'ug1.ug_group' => $params['excludegroup'][0] ];
			} else {
				$exclude = [ $db->makeList(
					[ 'ug1.ug_group' => $params['excludegroup'] ],
					LIST_OR
				) ];
			}
			$this->addJoinConds( [ 'ug1' => [ 'LEFT OUTER JOIN',
				array_merge( [ 'ug1.ug_user=user_id' ], $exclude )
			] ] );
			$this->addWhere( 'ug1.ug_user IS NULL' );
		}

		if ( $params['witheditsonly'] ) {
			$this->addWhere( 'user_editcount > 0' );
		}

		$this->showHiddenUsersAddBlockInfo( $fld_blockinfo );

		if ( $fld_groups || $fld_rights ) {
			$this->addFields( [ 'groups' =>
				$db->buildGroupConcatField( '|', 'user_groups', 'ug_group', 'ug_user=user_id' )
			] );
		}

		if ( $params['activeusers'] ) {
			$activeUserSeconds = $activeUserDays * 86400;

			// Filter query to only include users in the active users cache.
			// There shouldn't be any duplicate rows in querycachetwo here.
			$this->addTables( 'querycachetwo' );
			$this->addJoinConds( [ 'querycachetwo' => [
				'INNER JOIN', [
					'qcc_type' => 'activeusers',
					'qcc_namespace' => NS_USER,
					'qcc_title=user_name',
				],
			] ] );

			// Actually count the actions using a subquery (bug 64505 and bug 64507)
			$timestamp = $db->timestamp( wfTimestamp( TS_UNIX ) - $activeUserSeconds );
			$this->addFields( [
				'recentactions' => '(' . $db->selectSQLText(
					'recentchanges',
					'COUNT(*)',
					[
						'rc_user_text = user_name',
						'rc_type != ' . $db->addQuotes( RC_EXTERNAL ), // no wikidata
						'rc_log_type IS NULL OR rc_log_type != ' . $db->addQuotes( 'newusers' ),
						'rc_timestamp >= ' . $db->addQuotes( $timestamp ),
					]
				) . ')'
			] );
		}

		$sqlLimit = $limit + $maxDuplicateRows;
		$this->addOption( 'LIMIT', $sqlLimit );

		$this->addFields( [
			'user_name',
			'user_id'
		] );
		$this->addFieldsIf( 'user_editcount', $fld_editcount );
		$this->addFieldsIf( 'user_registration', $fld_registration );

		$res = $this->select( __METHOD__ );
		$count = 0;
		$countDuplicates = 0;
		$lastUser = false;
		$result = $this->getResult();
		foreach ( $res as $row ) {
			$count++;

			if ( $lastUser === $row->user_name ) {
				// Duplicate row due to one of the needed subtable joins.
				// Ignore it, but count the number of them to sanely handle
				// miscalculation of $maxDuplicateRows.
				$countDuplicates++;
				if ( $countDuplicates == $maxDuplicateRows ) {
					ApiBase::dieDebug( __METHOD__, 'Saw more duplicate rows than expected' );
				}
				continue;
			}

			$countDuplicates = 0;
			$lastUser = $row->user_name;

			if ( $count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'from', $row->user_name );
				break;
			}

			if ( $count == $sqlLimit ) {
				// Should never hit this (either the $countDuplicates check or
				// the $count > $limit check should hit first), but check it
				// anyway just in case.
				ApiBase::dieDebug( __METHOD__, 'Saw more duplicate rows than expected' );
			}

			if ( $params['activeusers'] && $row->recentactions === 0 ) {
				// activeusers cache was out of date
				continue;
			}

			$data = [
				'userid' => (int)$row->user_id,
				'name' => $row->user_name,
			];

			if ( $fld_centralids ) {
				$data += ApiQueryUserInfo::getCentralUserInfo(
					$this->getConfig(), User::newFromId( $row->user_id ), $params['attachedwiki']
				);
			}

			if ( $fld_blockinfo && !is_null( $row->ipb_by_text ) ) {
				$data['blockid'] = (int)$row->ipb_id;
				$data['blockedby'] = $row->ipb_by_text;
				$data['blockedbyid'] = (int)$row->ipb_by;
				$data['blockedtimestamp'] = wfTimestamp( TS_ISO_8601, $row->ipb_timestamp );
				$data['blockreason'] = $row->ipb_reason;
				$data['blockexpiry'] = $row->ipb_expiry;
			}
			if ( $row->ipb_deleted ) {
				$data['hidden'] = true;
			}
			if ( $fld_editcount ) {
				$data['editcount'] = intval( $row->user_editcount );
			}
			if ( $params['activeusers'] ) {
				$data['recentactions'] = intval( $row->recentactions );
				// @todo 'recenteditcount' is set for BC, remove in 1.25
				$data['recenteditcount'] = $data['recentactions'];
			}
			if ( $fld_registration ) {
				$data['registration'] = $row->user_registration ?
					wfTimestamp( TS_ISO_8601, $row->user_registration ) : '';
			}

			if ( $fld_implicitgroups || $fld_groups || $fld_rights ) {
				$implicitGroups = User::newFromId( $row->user_id )->getAutomaticGroups();
				if ( isset( $row->groups ) && $row->groups !== '' ) {
					$groups = array_merge( $implicitGroups, explode( '|', $row->groups ) );
				} else {
					$groups = $implicitGroups;
				}

				if ( $fld_groups ) {
					$data['groups'] = $groups;
					ApiResult::setIndexedTagName( $data['groups'], 'g' );
					ApiResult::setArrayType( $data['groups'], 'array' );
				}

				if ( $fld_implicitgroups ) {
					$data['implicitgroups'] = $implicitGroups;
					ApiResult::setIndexedTagName( $data['implicitgroups'], 'g' );
					ApiResult::setArrayType( $data['implicitgroups'], 'array' );
				}

				if ( $fld_rights ) {
					$data['rights'] = User::getGroupPermissions( $groups );
					ApiResult::setIndexedTagName( $data['rights'], 'r' );
					ApiResult::setArrayType( $data['rights'], 'array' );
				}
			}

			$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $data );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'from', $data['name'] );
				break;
			}
		}

		$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'u' );
	}

	public function getCacheMode( $params ) {
		return 'anon-public-user-private';
	}

	public function getAllowedParams() {
		$userGroups = User::getAllGroups();

		return [
			'from' => null,
			'to' => null,
			'prefix' => null,
			'dir' => [
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => [
					'ascending',
					'descending'
				],
			],
			'group' => [
				ApiBase::PARAM_TYPE => $userGroups,
				ApiBase::PARAM_ISMULTI => true,
			],
			'excludegroup' => [
				ApiBase::PARAM_TYPE => $userGroups,
				ApiBase::PARAM_ISMULTI => true,
			],
			'rights' => [
				ApiBase::PARAM_TYPE => User::getAllRights(),
				ApiBase::PARAM_ISMULTI => true,
			],
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'blockinfo',
					'groups',
					'implicitgroups',
					'rights',
					'editcount',
					'registration',
					'centralids',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'witheditsonly' => false,
			'activeusers' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => [
					'apihelp-query+allusers-param-activeusers',
					$this->getConfig()->get( 'ActiveUserDays' )
				],
			],
			'attachedwiki' => null,
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=allusers&aufrom=Y'
				=> 'apihelp-query+allusers-example-Y',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Allusers';
	}
}
