<?php
/**
 *
 *
 * Created on July 30, 2007
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
 * Query module to get information about a list of users
 *
 * @ingroup API
 */
class ApiQueryUsers extends ApiQueryBase {

	private $tokenFunctions, $prop;

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'us' );
	}

	/**
	 * Get an array mapping token names to their handler functions.
	 * The prototype for a token function is func($user)
	 * it should return a token or false (permission denied)
	 * @return Array tokenname => function
	 */
	protected function getTokenFunctions() {
		// Don't call the hooks twice
		if ( isset( $this->tokenFunctions ) ) {
			return $this->tokenFunctions;
		}

		// If we're in JSON callback mode, no tokens can be obtained
		if ( !is_null( $this->getMain()->getRequest()->getVal( 'callback' ) ) ) {
			return array();
		}

		$this->tokenFunctions = array(
			'userrights' => array( 'ApiQueryUsers', 'getUserrightsToken' ),
		);
		wfRunHooks( 'APIQueryUsersTokens', array( &$this->tokenFunctions ) );
		return $this->tokenFunctions;
	}

	/**
	 * @param $user User
	 * @return String
	 */
	public static function getUserrightsToken( $user ) {
		global $wgUser;
		// Since the permissions check for userrights is non-trivial,
		// don't bother with it here
		return $wgUser->getEditToken( $user->getName() );
	}

	public function execute() {
		$params = $this->extractRequestParams();

		if ( !is_null( $params['prop'] ) ) {
			$this->prop = array_flip( $params['prop'] );
		} else {
			$this->prop = array();
		}

		$users = (array)$params['users'];
		$goodNames = $done = array();
		$result = $this->getResult();
		// Canonicalize user names
		foreach ( $users as $u ) {
			$n = User::getCanonicalName( $u );
			if ( $n === false || $n === '' ) {
				$vals = array( 'name' => $u, 'invalid' => '' );
				$fit = $result->addValue( array( 'query', $this->getModuleName() ),
						null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'users',
							implode( '|', array_diff( $users, $done ) ) );
					$goodNames = array();
					break;
				}
				$done[] = $u;
			} else {
				$goodNames[] = $n;
			}
		}

		$result = $this->getResult();

		if ( count( $goodNames ) ) {
			$this->addTables( 'user' );
			$this->addFields( User::selectFields() );
			$this->addWhereFld( 'user_name', $goodNames );

			$this->showHiddenUsersAddBlockInfo( isset( $this->prop['blockinfo'] ) );

			$data = array();
			$res = $this->select( __METHOD__ );
			$this->resetQueryParams();

			// get user groups if needed
			if ( isset( $this->prop['groups'] ) || isset( $this->prop['rights'] ) ) {
				$userGroups = array();

				$this->addTables( 'user' );
				$this->addWhereFld( 'user_name', $goodNames );
				$this->addTables( 'user_groups' );
				$this->addJoinConds( array( 'user_groups' => array( 'INNER JOIN', 'ug_user=user_id' ) ) );
				$this->addFields( array( 'user_name', 'ug_group' ) );
				$userGroupsRes = $this->select( __METHOD__ );

				foreach ( $userGroupsRes as $row ) {
					$userGroups[$row->user_name][] = $row->ug_group;
				}
			}

			foreach ( $res as $row ) {
				// create user object and pass along $userGroups if set
				// that reduces the number of database queries needed in User dramatically
				if ( !isset( $userGroups ) ) {
					$user = User::newFromRow( $row );
				} else {
					if ( !isset( $userGroups[$row->user_name] ) || !is_array( $userGroups[$row->user_name] ) ) {
						$userGroups[$row->user_name] = array();
					}
					$user = User::newFromRow( $row, array( 'user_groups' => $userGroups[$row->user_name] ) );
				}
				$name = $user->getName();

				$data[$name]['userid'] = $user->getId();
				$data[$name]['name'] = $name;

				if ( isset( $this->prop['editcount'] ) ) {
					$data[$name]['editcount'] = $user->getEditCount();
				}

				if ( isset( $this->prop['registration'] ) ) {
					$data[$name]['registration'] = wfTimestampOrNull( TS_ISO_8601, $user->getRegistration() );
				}

				if ( isset( $this->prop['groups'] ) ) {
					$data[$name]['groups'] = $user->getEffectiveGroups();
				}

				if ( isset( $this->prop['implicitgroups'] ) ) {
					$data[$name]['implicitgroups'] = $user->getAutomaticGroups();
				}

				if ( isset( $this->prop['rights'] ) ) {
					$data[$name]['rights'] = $user->getRights();
				}
				if ( $row->ipb_deleted ) {
					$data[$name]['hidden'] = '';
				}
				if ( isset( $this->prop['blockinfo'] ) && !is_null( $row->ipb_by_text ) ) {
					$data[$name]['blockid'] = $row->ipb_id;
					$data[$name]['blockedby'] = $row->ipb_by_text;
					$data[$name]['blockedbyid'] = $row->ipb_by;
					$data[$name]['blockreason'] = $row->ipb_reason;
					$data[$name]['blockexpiry'] = $row->ipb_expiry;
				}

				if ( isset( $this->prop['emailable'] ) && $user->canReceiveEmail() ) {
					$data[$name]['emailable'] = '';
				}

				if ( isset( $this->prop['gender'] ) ) {
					$gender = $user->getOption( 'gender' );
					if ( strval( $gender ) === '' ) {
						$gender = 'unknown';
					}
					$data[$name]['gender'] = $gender;
				}

				if ( !is_null( $params['token'] ) ) {
					$tokenFunctions = $this->getTokenFunctions();
					foreach ( $params['token'] as $t ) {
						$val = call_user_func( $tokenFunctions[$t], $user );
						if ( $val === false ) {
							$this->setWarning( "Action '$t' is not allowed for the current user" );
						} else {
							$data[$name][$t . 'token'] = $val;
						}
					}
				}
			}
		}

		$context = $this->getContext();
		// Second pass: add result data to $retval
		foreach ( $goodNames as $u ) {
			if ( !isset( $data[$u] ) ) {
				$data[$u] = array( 'name' => $u );
				$urPage = new UserrightsPage;
				$urPage->setContext( $context );
				$iwUser = $urPage->fetchUser( $u );

				if ( $iwUser instanceof UserRightsProxy ) {
					$data[$u]['interwiki'] = '';

					if ( !is_null( $params['token'] ) ) {
						$tokenFunctions = $this->getTokenFunctions();

						foreach ( $params['token'] as $t ) {
							$val = call_user_func( $tokenFunctions[$t], $iwUser );
							if ( $val === false ) {
								$this->setWarning( "Action '$t' is not allowed for the current user" );
							} else {
								$data[$u][$t . 'token'] = $val;
							}
						}
					}
				} else {
					$data[$u]['missing'] = '';
				}
			} else {
				if ( isset( $this->prop['groups'] ) && isset( $data[$u]['groups'] ) ) {
					$result->setIndexedTagName( $data[$u]['groups'], 'g' );
				}
				if ( isset( $this->prop['implicitgroups'] ) && isset( $data[$u]['implicitgroups'] ) ) {
					$result->setIndexedTagName( $data[$u]['implicitgroups'], 'g' );
				}
				if ( isset( $this->prop['rights'] ) && isset( $data[$u]['rights'] ) ) {
					$result->setIndexedTagName( $data[$u]['rights'], 'r' );
				}
			}

			$fit = $result->addValue( array( 'query', $this->getModuleName() ),
					null, $data[$u] );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'users',
						implode( '|', array_diff( $users, $done ) ) );
				break;
			}
			$done[] = $u;
		}
		$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'user' );
	}

	/**
	 * Gets all the groups that a user is automatically a member of (implicit groups)
	 *
	 * @deprecated since 1.20; call User::getAutomaticGroups() directly.
	 * @param $user User
	 * @return array
	 */
	public static function getAutoGroups( $user ) {
		wfDeprecated( __METHOD__, '1.20' );

		return $user->getAutomaticGroups();
	}

	public function getCacheMode( $params ) {
		if ( isset( $params['token'] ) ) {
			return 'private';
		} else {
			return 'anon-public-user-private';
		}
	}

	public function getAllowedParams() {
		return array(
			'prop' => array(
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'blockinfo',
					'groups',
					'implicitgroups',
					'rights',
					'editcount',
					'registration',
					'emailable',
					'gender',
				)
			),
			'users' => array(
				ApiBase::PARAM_ISMULTI => true
			),
			'token' => array(
				ApiBase::PARAM_TYPE => array_keys( $this->getTokenFunctions() ),
				ApiBase::PARAM_ISMULTI => true
			),
		);
	}

	public function getParamDescription() {
		return array(
			'prop' => array(
				'What pieces of information to include',
				'  blockinfo      - Tags if the user is blocked, by whom, and for what reason',
				'  groups         - Lists all the groups the user(s) belongs to',
				'  implicitgroups - Lists all the groups a user is automatically a member of',
				'  rights         - Lists all the rights the user(s) has',
				'  editcount      - Adds the user\'s edit count',
				'  registration   - Adds the user\'s registration timestamp',
				'  emailable      - Tags if the user can and wants to receive email through [[Special:Emailuser]]',
				'  gender         - Tags the gender of the user. Returns "male", "female", or "unknown"',
			),
			'users' => 'A list of users to obtain the same information for',
			'token' => 'Which tokens to obtain for each user',
		);
	}

	public function getResultProperties() {
		$props = array(
			'' => array(
				'userid' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				),
				'name' => 'string',
				'invalid' => 'boolean',
				'hidden' => 'boolean',
				'interwiki' => 'boolean',
				'missing' => 'boolean'
			),
			'editcount' => array(
				'editcount' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'registration' => array(
				'registration' => array(
					ApiBase::PROP_TYPE => 'timestamp',
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
					ApiBase::PROP_TYPE => 'timestamp',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'emailable' => array(
				'emailable' => 'boolean'
			),
			'gender' => array(
				'gender' => array(
					ApiBase::PROP_TYPE => array(
						'male',
						'female',
						'unknown'
					),
					ApiBase::PROP_NULLABLE => true
				)
			)
		);

		self::addTokenProperties( $props, $this->getTokenFunctions() );

		return $props;
	}

	public function getDescription() {
		return 'Get information about a list of users';
	}

	public function getExamples() {
		return 'api.php?action=query&list=users&ususers=brion|TimStarling&usprop=groups|editcount|gender';
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Users';
	}
}
