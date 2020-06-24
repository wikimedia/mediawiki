<?php
/**
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

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\MediaWikiServices;

/**
 * Query module to get information about a list of users
 *
 * @ingroup API
 */
class ApiQueryUsers extends ApiQueryBase {
	use ApiQueryBlockInfoTrait;

	private $tokenFunctions, $prop;

	/**
	 * Properties whose contents does not depend on who is looking at them. If the usprops field
	 * contains anything not listed here, the cache mode will never be public for logged-in users.
	 * @var array
	 */
	protected static $publicProps = [
		// everything except 'blockinfo' which might show hidden records if the user
		// making the request has the appropriate permissions
		'groups',
		'groupmemberships',
		'implicitgroups',
		'rights',
		'editcount',
		'registration',
		'emailable',
		'gender',
		'centralids',
		'cancreate',
	];

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
			return [];
		}

		$this->tokenFunctions = [
			'userrights' => [ self::class, 'getUserrightsToken' ],
		];
		$this->getHookRunner()->onAPIQueryUsersTokens( $this->tokenFunctions );

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
		$db = $this->getDB();
		$params = $this->extractRequestParams();
		$this->requireMaxOneParameter( $params, 'userids', 'users' );

		if ( $params['prop'] !== null ) {
			$this->prop = array_flip( $params['prop'] );
		} else {
			$this->prop = [];
		}
		$useNames = $params['users'] !== null;

		$users = (array)$params['users'];
		$userids = (array)$params['userids'];

		$goodNames = $done = [];
		$result = $this->getResult();
		// Canonicalize user names
		foreach ( $users as $u ) {
			$n = User::getCanonicalName( $u );
			if ( $n === false || $n === '' ) {
				$vals = [ 'name' => $u, 'invalid' => true ];
				$fit = $result->addValue( [ 'query', $this->getModuleName() ],
					null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'users',
						implode( '|', array_diff( $users, $done ) ) );
					$goodNames = [];
					break;
				}
				$done[] = $u;
			} else {
				$goodNames[] = $n;
			}
		}

		if ( $useNames ) {
			$parameters = &$goodNames;
		} else {
			$parameters = &$userids;
		}

		$result = $this->getResult();

		if ( count( $parameters ) ) {
			$userQuery = User::getQueryInfo();
			$this->addTables( $userQuery['tables'] );
			$this->addFields( $userQuery['fields'] );
			$this->addJoinConds( $userQuery['joins'] );
			if ( $useNames ) {
				$this->addWhereFld( 'user_name', $goodNames );
			} else {
				$this->addWhereFld( 'user_id', $userids );
			}

			$this->addBlockInfoToQuery( isset( $this->prop['blockinfo'] ) );

			$data = [];
			$res = $this->select( __METHOD__ );
			$this->resetQueryParams();

			// get user groups if needed
			if ( isset( $this->prop['groups'] ) || isset( $this->prop['rights'] ) ) {
				$userGroups = [];

				$this->addTables( 'user' );
				if ( $useNames ) {
					$this->addWhereFld( 'user_name', $goodNames );
				} else {
					$this->addWhereFld( 'user_id', $userids );
				}

				$this->addTables( 'user_groups' );
				$this->addJoinConds( [ 'user_groups' => [ 'JOIN', 'ug_user=user_id' ] ] );
				$this->addFields( [ 'user_name' ] );
				$this->addFields( MediaWikiServices::getInstance()
					->getUserGroupManager()
					->getQueryInfo()['fields']
				);
				$this->addWhere( 'ug_expiry IS NULL OR ug_expiry >= ' .
					$db->addQuotes( $db->timestamp() ) );
				$userGroupsRes = $this->select( __METHOD__ );

				foreach ( $userGroupsRes as $row ) {
					$userGroups[$row->user_name][] = $row;
				}
			}

			foreach ( $res as $row ) {
				// create user object and pass along $userGroups if set
				// that reduces the number of database queries needed in User dramatically
				if ( !isset( $userGroups ) ) {
					$user = User::newFromRow( $row );
				} else {
					if ( !isset( $userGroups[$row->user_name] ) || !is_array( $userGroups[$row->user_name] ) ) {
						$userGroups[$row->user_name] = [];
					}
					$user = User::newFromRow( $row, [ 'user_groups' => $userGroups[$row->user_name] ] );
				}
				if ( $useNames ) {
					$key = $user->getName();
				} else {
					$key = $user->getId();
				}
				$data[$key]['userid'] = $user->getId();
				$data[$key]['name'] = $user->getName();

				if ( isset( $this->prop['editcount'] ) ) {
					$data[$key]['editcount'] = $user->getEditCount();
				}

				if ( isset( $this->prop['registration'] ) ) {
					$data[$key]['registration'] = wfTimestampOrNull( TS_ISO_8601, $user->getRegistration() );
				}

				if ( isset( $this->prop['groups'] ) ) {
					$data[$key]['groups'] = $user->getEffectiveGroups();
				}

				if ( isset( $this->prop['groupmemberships'] ) ) {
					$data[$key]['groupmemberships'] = array_map( function ( $ugm ) {
						return [
							'group' => $ugm->getGroup(),
							'expiry' => ApiResult::formatExpiry( $ugm->getExpiry() ),
						];
					}, $user->getGroupMemberships() );
				}

				if ( isset( $this->prop['implicitgroups'] ) ) {
					$data[$key]['implicitgroups'] = $user->getAutomaticGroups();
				}

				if ( isset( $this->prop['rights'] ) ) {
					$data[$key]['rights'] = $this->getPermissionManager()
						->getUserPermissions( $user );
				}
				if ( $row->ipb_deleted ) {
					$data[$key]['hidden'] = true;
				}
				if ( isset( $this->prop['blockinfo'] ) && $row->ipb_by_text !== null ) {
					$data[$key] += $this->getBlockDetails( DatabaseBlock::newFromRow( $row ) );
				}

				if ( isset( $this->prop['emailable'] ) ) {
					$data[$key]['emailable'] = $user->canReceiveEmail();
				}

				if ( isset( $this->prop['gender'] ) ) {
					$gender = $user->getOption( 'gender' );
					if ( strval( $gender ) === '' ) {
						$gender = 'unknown';
					}
					$data[$key]['gender'] = $gender;
				}

				if ( isset( $this->prop['centralids'] ) ) {
					$data[$key] += ApiQueryUserInfo::getCentralUserInfo(
						$this->getConfig(), $user, $params['attachedwiki']
					);
				}

				if ( $params['token'] !== null ) {
					$tokenFunctions = $this->getTokenFunctions();
					foreach ( $params['token'] as $t ) {
						$val = call_user_func( $tokenFunctions[$t], $user );
						if ( $val === false ) {
							$this->addWarning( [ 'apiwarn-tokennotallowed', $t ] );
						} else {
							$data[$key][$t . 'token'] = $val;
						}
					}
				}
			}
		}

		$context = $this->getContext();
		// Second pass: add result data to $retval
		foreach ( $parameters as $u ) {
			if ( !isset( $data[$u] ) ) {
				if ( $useNames ) {
					$data[$u] = [ 'name' => $u ];
					$urPage = new UserrightsPage;
					$urPage->setContext( $context );

					$iwUser = $urPage->fetchUser( $u );

					if ( $iwUser instanceof UserRightsProxy ) {
						$data[$u]['interwiki'] = true;

						if ( $params['token'] !== null ) {
							$tokenFunctions = $this->getTokenFunctions();

							foreach ( $params['token'] as $t ) {
								$val = call_user_func( $tokenFunctions[$t], $iwUser );
								if ( $val === false ) {
									$this->addWarning( [ 'apiwarn-tokennotallowed', $t ] );
								} else {
									$data[$u][$t . 'token'] = $val;
								}
							}
						}
					} else {
						$data[$u]['missing'] = true;
						if ( isset( $this->prop['cancreate'] ) ) {
							$status = MediaWikiServices::getInstance()->getAuthManager()
								->canCreateAccount( $u );
							$data[$u]['cancreate'] = $status->isGood();
							if ( !$status->isGood() ) {
								$data[$u]['cancreateerror'] = $this->getErrorFormatter()->arrayFromStatus( $status );
							}
						}
					}
				} else {
					$data[$u] = [ 'userid' => $u, 'missing' => true ];
				}

			} else {
				if ( isset( $this->prop['groups'] ) && isset( $data[$u]['groups'] ) ) {
					ApiResult::setArrayType( $data[$u]['groups'], 'array' );
					ApiResult::setIndexedTagName( $data[$u]['groups'], 'g' );
				}
				if ( isset( $this->prop['groupmemberships'] ) && isset( $data[$u]['groupmemberships'] ) ) {
					ApiResult::setArrayType( $data[$u]['groupmemberships'], 'array' );
					ApiResult::setIndexedTagName( $data[$u]['groupmemberships'], 'groupmembership' );
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

			$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $data[$u] );
			if ( !$fit ) {
				if ( $useNames ) {
					$this->setContinueEnumParameter( 'users',
						implode( '|', array_diff( $users, $done ) ) );
				} else {
					$this->setContinueEnumParameter( 'userids',
						implode( '|', array_diff( $userids, $done ) ) );
				}
				break;
			}
			$done[] = $u;
		}
		$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'user' );
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
		return [
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'blockinfo',
					'groups',
					'groupmemberships',
					'implicitgroups',
					'rights',
					'editcount',
					'registration',
					'emailable',
					'gender',
					'centralids',
					'cancreate',
					// When adding a prop, consider whether it should be added
					// to self::$publicProps
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'attachedwiki' => null,
			'users' => [
				ApiBase::PARAM_ISMULTI => true
			],
			'userids' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'integer'
			],
			'token' => [
				ApiBase::PARAM_DEPRECATED => true,
				ApiBase::PARAM_TYPE => array_keys( $this->getTokenFunctions() ),
				ApiBase::PARAM_ISMULTI => true
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=users&ususers=Example&usprop=groups|editcount|gender'
				=> 'apihelp-query+users-example-simple',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Users';
	}
}
