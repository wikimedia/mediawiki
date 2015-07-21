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

	/**
	 * Properties whose contents does not depend on who is looking at them. If the usprops field
	 * contains anything not listed here, the cache mode will never be public for logged-in users.
	 * @var array
	 */
	protected static $publicProps = array(
		// everything except 'blockinfo' which might show hidden records if the user
		// making the request has the appropriate permissions
		'groups',
		'implicitgroups',
		'rights',
		'editcount',
		'registration',
		'emailable',
		'gender',
	);

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'us' );
	}

	/**
	 * Get an array mapping token names to their handler functions.
	 * The prototype for a token function is func($user)
	 * it should return a token or false (permission denied)
	 * @deprecated since 1.24
	 * @return array Array of tokenname => function
	 */
	protected function getTokenFunctions() {
		// Don't call the hooks twice
		if ( isset( $this->tokenFunctions ) ) {
			return $this->tokenFunctions;
		}

		// If we're in a mode that breaks the same-origin policy, no tokens can
		// be obtained
		if ( $this->lacksSameOriginSecurity() ) {
			return array();
		}

		$this->tokenFunctions = array(
			'userrights' => array( 'ApiQueryUsers', 'getUserrightsToken' ),
		);
		Hooks::run( 'APIQueryUsersTokens', array( &$this->tokenFunctions ) );

		return $this->tokenFunctions;
	}

	/**
	 * @deprecated since 1.24
	 * @param User $user
	 * @return string
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
				$vals = array( 'name' => $u, 'invalid' => true );
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
					$data[$name]['hidden'] = true;
				}
				if ( isset( $this->prop['blockinfo'] ) && !is_null( $row->ipb_by_text ) ) {
					$data[$name]['blockid'] = $row->ipb_id;
					$data[$name]['blockedby'] = $row->ipb_by_text;
					$data[$name]['blockedbyid'] = $row->ipb_by;
					$data[$name]['blockedtimestamp'] = wfTimestamp( TS_ISO_8601, $row->ipb_timestamp );
					$data[$name]['blockreason'] = $row->ipb_reason;
					$data[$name]['blockexpiry'] = $row->ipb_expiry;
				}

				if ( isset( $this->prop['emailable'] ) ) {
					$data[$name]['emailable'] = $user->canReceiveEmail();
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
					$data[$u]['interwiki'] = true;

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
					$data[$u]['missing'] = true;
				}
			} else {
				if ( isset( $this->prop['groups'] ) && isset( $data[$u]['groups'] ) ) {
					ApiResult::setArrayType( $data[$u]['groups'], 'array' );
					ApiResult::setIndexedTagName( $data[$u]['groups'], 'g' );
				}
				if ( isset( $this->prop['implicitgroups'] ) && isset( $data[$u]['implicitgroups'] ) ) {
					ApiResult::setArrayType( $data[$u]['implicitgroups'], 'array' );
					ApiResult::setIndexedTagName( $data[$u]['implicitgroups'], 'g' );
				}
				if ( isset( $this->prop['rights'] ) && isset( $data[$u]['rights'] ) ) {
					ApiResult::setArrayType( $data[$u]['rights'], 'array' );
					ApiResult::setIndexedTagName( $data[$u]['rights'], 'r' );
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
		$result->addIndexedTagName( array( 'query', $this->getModuleName() ), 'user' );
	}

	public function getCacheMode( $params ) {
		if ( isset( $params['token'] ) ) {
			return 'private';
		} elseif ( array_diff( (array)$params['prop'], static::$publicProps ) ) {
			return 'anon-public-user-private';
		} else {
			return 'public';
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
				ApiBase::PARAM_DEPRECATED => true,
				ApiBase::PARAM_TYPE => array_keys( $this->getTokenFunctions() ),
				ApiBase::PARAM_ISMULTI => true
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&list=users&ususers=Example&usprop=groups|editcount|gender'
				=> 'apihelp-query+users-example-simple',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Users';
	}
}
