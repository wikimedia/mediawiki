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

use MediaWiki\Auth\AuthManager;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserNameUtils;
use MediaWiki\User\UserOptionsLookup;

/**
 * Query module to get information about a list of users
 *
 * @ingroup API
 */
class ApiQueryUsers extends ApiQueryBase {
	use ApiQueryBlockInfoTrait;

	private $prop;

	/** @var UserNameUtils */
	private $userNameUtils;

	/** @var UserFactory */
	private $userFactory;

	/** @var UserGroupManager */
	private $userGroupManager;

	/** @var UserOptionsLookup */
	private $userOptionsLookup;

	/** @var AuthManager */
	private $authManager;

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

	/**
	 * @param ApiQuery $query
	 * @param string $moduleName
	 * @param UserNameUtils $userNameUtils
	 * @param UserFactory $userFactory
	 * @param UserGroupManager $userGroupManager
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param AuthManager $authManager
	 */
	public function __construct(
		ApiQuery $query,
		$moduleName,
		UserNameUtils $userNameUtils,
		UserFactory $userFactory,
		UserGroupManager $userGroupManager,
		UserOptionsLookup $userOptionsLookup,
		AuthManager $authManager
	) {
		parent::__construct( $query, $moduleName, 'us' );
		$this->userNameUtils = $userNameUtils;
		$this->userFactory = $userFactory;
		$this->userGroupManager = $userGroupManager;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->authManager = $authManager;
	}

	public function execute() {
		$db = $this->getDB();
		$params = $this->extractRequestParams();
		$this->requireMaxOneParameter( $params, 'userids', 'users' );

		if ( $params['prop'] !== null ) {
			$this->prop = array_fill_keys( $params['prop'], true );
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
			$n = $this->userNameUtils->getCanonical( $u );
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
				$this->addFields( $this->userGroupManager->getQueryInfo()['fields'] );
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
					$user = $this->userFactory->newFromRow( $row );
				} else {
					if ( !isset( $userGroups[$row->user_name] ) || !is_array( $userGroups[$row->user_name] ) ) {
						$userGroups[$row->user_name] = [];
					}
					$user = $this->userFactory->newFromRow( $row, [ 'user_groups' => $userGroups[$row->user_name] ] );
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
					$data[$key]['groups'] = $this->userGroupManager->getUserEffectiveGroups( $user );
				}

				if ( isset( $this->prop['groupmemberships'] ) ) {
					$data[$key]['groupmemberships'] = array_map( static function ( $ugm ) {
						return [
							'group' => $ugm->getGroup(),
							'expiry' => ApiResult::formatExpiry( $ugm->getExpiry() ),
						];
					}, $this->userGroupManager->getUserGroupMemberships( $user ) );
				}

				if ( isset( $this->prop['implicitgroups'] ) ) {
					$data[$key]['implicitgroups'] = $this->userGroupManager->getUserImplicitGroups( $user );
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
					$gender = $this->userOptionsLookup->getOption( $user, 'gender' );
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
					} else {
						$data[$u]['missing'] = true;
						if ( isset( $this->prop['cancreate'] ) ) {
							$status = $this->authManager->canCreateAccount( $u );
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
		if ( array_diff( (array)$params['prop'], static::$publicProps ) ) {
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
