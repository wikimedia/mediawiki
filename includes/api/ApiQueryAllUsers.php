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
	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'au' );
	}

	/**
	 * This function converts the user name to a canonical form
	 * which is stored in the database.
	 * @param String $name
	 * @return String
	 */
	private function getCanonicalUserName( $name ) {
		return str_replace( '_', ' ', $name );
	}

	public function execute() {
		$db = $this->getDB();
		$params = $this->extractRequestParams();

		$prop = $params['prop'];
		if ( !is_null( $prop ) ) {
			$prop = array_flip( $prop );
			$fld_blockinfo = isset( $prop['blockinfo'] );
			$fld_editcount = isset( $prop['editcount'] );
			$fld_groups = isset( $prop['groups'] );
			$fld_rights = isset( $prop['rights'] );
			$fld_registration = isset( $prop['registration'] );
			$fld_implicitgroups = isset( $prop['implicitgroups'] );
		} else {
			$fld_blockinfo = $fld_editcount = $fld_groups = $fld_registration = $fld_rights = $fld_implicitgroups = false;
		}

		$limit = $params['limit'];

		$this->addTables( 'user' );
		$useIndex = true;

		$dir = ( $params['dir'] == 'descending' ? 'older' : 'newer' );
		$from = is_null( $params['from'] ) ? null : $this->getCanonicalUserName( $params['from'] );
		$to = is_null( $params['to'] ) ? null : $this->getCanonicalUserName( $params['to'] );

		# MySQL doesn't seem to use 'equality propagation' here, so like the
		# ActiveUsers special page, we have to use rc_user_text for some cases.
		$userFieldToSort = $params['activeusers'] ? 'rc_user_text' : 'user_name';

		$this->addWhereRange( $userFieldToSort, $dir, $from, $to );

		if ( !is_null( $params['prefix'] ) ) {
			$this->addWhere( $userFieldToSort .
				$db->buildLike( $this->getCanonicalUserName( $params['prefix'] ), $db->anyString() ) );
		}

		if ( !is_null( $params['rights'] ) ) {
			$groups = array();
			foreach( $params['rights'] as $r ) {
				$groups = array_merge( $groups, User::getGroupsWithPermission( $r ) );
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
			$useIndex = false;
			// Filter only users that belong to a given group
			$this->addTables( 'user_groups', 'ug1' );
			$this->addJoinConds( array( 'ug1' => array( 'INNER JOIN', array( 'ug1.ug_user=user_id',
					'ug1.ug_group' => $params['group'] ) ) ) );
		}

		if ( !is_null( $params['excludegroup'] ) && count( $params['excludegroup'] ) ) {
			$useIndex = false;
			// Filter only users don't belong to a given group
			$this->addTables( 'user_groups', 'ug1' );

			if ( count( $params['excludegroup'] ) == 1 ) {
				$exclude = array( 'ug1.ug_group' => $params['excludegroup'][0] );
			} else {
				$exclude = array( $db->makeList( array( 'ug1.ug_group' => $params['excludegroup'] ), LIST_OR ) );
			}
			$this->addJoinConds( array( 'ug1' => array( 'LEFT OUTER JOIN',
				array_merge( array( 'ug1.ug_user=user_id' ), $exclude )
				)
			) );
			$this->addWhere( 'ug1.ug_user IS NULL' );
		}

		if ( $params['witheditsonly'] ) {
			$this->addWhere( 'user_editcount > 0' );
		}

		$this->showHiddenUsersAddBlockInfo( $fld_blockinfo );

		if ( $fld_groups || $fld_rights ) {
			// Show the groups the given users belong to
			// request more than needed to avoid not getting all rows that belong to one user
			$groupCount = count( User::getAllGroups() );
			$sqlLimit = $limit + $groupCount + 1;

			$this->addTables( 'user_groups', 'ug2' );
			$this->addJoinConds( array( 'ug2' => array( 'LEFT JOIN', 'ug2.ug_user=user_id' ) ) );
			$this->addFields( 'ug2.ug_group ug_group2' );
		} else {
			$sqlLimit = $limit + 1;
		}

		if ( $params['activeusers'] ) {
			global $wgActiveUserDays;
			$this->addTables( 'recentchanges' );

			$this->addJoinConds( array( 'recentchanges' => array(
				'INNER JOIN', 'rc_user_text=user_name'
			) ) );

			$this->addFields( array( 'recentedits' => 'COUNT(*)' ) );

			$this->addWhere( 'rc_log_type IS NULL OR rc_log_type != ' . $db->addQuotes( 'newusers' ) );
			$timestamp = $db->timestamp( wfTimestamp( TS_UNIX ) - $wgActiveUserDays*24*3600 );
			$this->addWhere( 'rc_timestamp >= ' . $db->addQuotes( $timestamp ) );

			$this->addOption( 'GROUP BY', $userFieldToSort );
		}

		$this->addOption( 'LIMIT', $sqlLimit );

		$this->addFields( array(
			'user_name',
			'user_id'
		) );
		$this->addFieldsIf( 'user_editcount', $fld_editcount );
		$this->addFieldsIf( 'user_registration', $fld_registration );

		if ( $useIndex ) {
			$this->addOption( 'USE INDEX', array( 'user' => 'user_name' ) );
		}

		$res = $this->select( __METHOD__ );

		$count = 0;
		$lastUserData = false;
		$lastUser = false;
		$result = $this->getResult();

		//
		// This loop keeps track of the last entry.
		// For each new row, if the new row is for different user then the last, the last entry is added to results.
		// Otherwise, the group of the new row is appended to the last entry.
		// The setContinue... is more complex because of this, and takes into account the higher sql limit
		// to make sure all rows that belong to the same user are received.

		foreach ( $res as $row ) {
			$count++;

			if ( $lastUser !== $row->user_name ) {
				// Save the last pass's user data
				if ( is_array( $lastUserData ) ) {
					$fit = $result->addValue( array( 'query', $this->getModuleName() ),
							null, $lastUserData );

					$lastUserData = null;

					if ( !$fit ) {
						$this->setContinueEnumParameter( 'from', $lastUserData['name'] );
						break;
					}
				}

				if ( $count > $limit ) {
					// We've reached the one extra which shows that there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'from', $row->user_name );
					break;
				}

				// Record new user's data
				$lastUser = $row->user_name;
				$lastUserData = array(
					'userid' => $row->user_id,
					'name' => $lastUser,
				);
				if ( $fld_blockinfo && !is_null( $row->ipb_by_text ) ) {
					$lastUserData['blockid'] = $row->ipb_id;
					$lastUserData['blockedby'] = $row->ipb_by_text;
					$lastUserData['blockedbyid'] = $row->ipb_by;
					$lastUserData['blockreason'] = $row->ipb_reason;
					$lastUserData['blockexpiry'] = $row->ipb_expiry;
				}
				if ( $row->ipb_deleted ) {
					$lastUserData['hidden'] = '';
				}
				if ( $fld_editcount ) {
					$lastUserData['editcount'] = intval( $row->user_editcount );
				}
				if ( $params['activeusers'] ) {
					$lastUserData['recenteditcount'] = intval( $row->recentedits );
				}
				if ( $fld_registration ) {
					$lastUserData['registration'] = $row->user_registration ?
						wfTimestamp( TS_ISO_8601, $row->user_registration ) : '';
				}
			}

			if ( $sqlLimit == $count ) {
				// BUG!  database contains group name that User::getAllGroups() does not return
				// TODO: should handle this more gracefully
				ApiBase::dieDebug( __METHOD__,
					'MediaWiki configuration error: the database contains more user groups than known to User::getAllGroups() function' );
			}

			$lastUserObj = User::newFromId( $row->user_id );

			// Add user's group info
			if ( $fld_groups ) {
				if ( !isset( $lastUserData['groups'] ) ) {
					if ( $lastUserObj ) {
						$lastUserData['groups'] = $lastUserObj->getAutomaticGroups();
					} else {
						// This should not normally happen
						$lastUserData['groups'] = array();
					}
				}

				if ( !is_null( $row->ug_group2 ) ) {
					$lastUserData['groups'][] = $row->ug_group2;
				}

				$result->setIndexedTagName( $lastUserData['groups'], 'g' );
			}

			if ( $fld_implicitgroups && !isset( $lastUserData['implicitgroups'] ) && $lastUserObj ) {
				$lastUserData['implicitgroups'] = $lastUserObj->getAutomaticGroups();
				$result->setIndexedTagName( $lastUserData['implicitgroups'], 'g' );
			}
			if ( $fld_rights ) {
				if ( !isset( $lastUserData['rights'] ) ) {
					if ( $lastUserObj ) {
						$lastUserData['rights'] =  User::getGroupPermissions( $lastUserObj->getAutomaticGroups() );
					} else {
						// This should not normally happen
						$lastUserData['rights'] = array();
					}
				}

				if ( !is_null( $row->ug_group2 ) ) {
					$lastUserData['rights'] = array_unique( array_merge( $lastUserData['rights'],
						User::getGroupPermissions( array( $row->ug_group2 ) ) ) );
				}

				$result->setIndexedTagName( $lastUserData['rights'], 'r' );
			}
		}

		if ( is_array( $lastUserData ) ) {
			$fit = $result->addValue( array( 'query', $this->getModuleName() ),
				null, $lastUserData );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'from', $lastUserData['name'] );
			}
		}

		$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'u' );
	}

	public function getCacheMode( $params ) {
		return 'anon-public-user-private';
	}

	public function getAllowedParams() {
		$userGroups = User::getAllGroups();
		return array(
			'from' => null,
			'to' => null,
			'prefix' => null,
			'dir' => array(
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => array(
					'ascending',
					'descending'
				),
			),
			'group' => array(
				ApiBase::PARAM_TYPE => $userGroups,
				ApiBase::PARAM_ISMULTI => true,
			),
			'excludegroup' => array(
				ApiBase::PARAM_TYPE => $userGroups,
				ApiBase::PARAM_ISMULTI => true,
			),
			'rights' => array(
				ApiBase::PARAM_TYPE => User::getAllRights(),
				ApiBase::PARAM_ISMULTI => true,
			),
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'blockinfo',
					'groups',
					'implicitgroups',
					'rights',
					'editcount',
					'registration'
				)
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'witheditsonly' => false,
			'activeusers' => false,
		);
	}

	public function getParamDescription() {
		global $wgActiveUserDays;
		return array(
			'from' => 'The user name to start enumerating from',
			'to' => 'The user name to stop enumerating at',
			'prefix' => 'Search for all users that begin with this value',
			'dir' => 'Direction to sort in',
			'group' => 'Limit users to given group name(s)',
			'excludegroup' => 'Exclude users in given group name(s)',
			'rights' => 'Limit users to given right(s) (does not include rights granted by implicit or auto-promoted groups like *, user, or autoconfirmed)',
			'prop' => array(
				'What pieces of information to include.',
				' blockinfo      - Adds the information about a current block on the user',
				' groups         - Lists groups that the user is in. This uses more server resources and may return fewer results than the limit',
				' implicitgroups - Lists all the groups the user is automatically in',
				' rights         - Lists rights that the user has',
				' editcount      - Adds the edit count of the user',
				' registration   - Adds the timestamp of when the user registered if available (may be blank)',
				),
			'limit' => 'How many total user names to return',
			'witheditsonly' => 'Only list users who have made edits',
			'activeusers' => "Only list users active in the last {$wgActiveUserDays} days(s)"
		);
	}

	public function getResultProperties() {
		return array(
			'' => array(
				'userid' => 'integer',
				'name' => 'string',
				'recenteditcount' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'blockinfo' => array(
				'blockid' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				),
				'blockedby' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'blockedbyid' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				),
				'blockedreason' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'blockedexpiry' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'hidden' => 'boolean'
			),
			'editcount' => array(
				'editcount' => 'integer'
			),
			'registration' => array(
				'registration' => 'string'
			)
		);
	}

	public function getDescription() {
		return 'Enumerate all registered users';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'group-excludegroup', 'info' => 'group and excludegroup cannot be used together' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&list=allusers&aufrom=Y',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Allusers';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
