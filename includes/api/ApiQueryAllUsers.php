<?php
/**
 * Copyright © 2007 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\RecentChanges\RecentChangeLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * Query module to enumerate all registered users.
 *
 * @ingroup API
 */
class ApiQueryAllUsers extends ApiQueryBase {
	use ApiQueryBlockInfoTrait;

	private UserFactory $userFactory;
	private UserGroupManager $userGroupManager;
	private GroupPermissionsLookup $groupPermissionsLookup;
	private Language $contentLanguage;
	private TempUserConfig $tempUserConfig;
	private RecentChangeLookup $recentChangeLookup;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		UserFactory $userFactory,
		UserGroupManager $userGroupManager,
		GroupPermissionsLookup $groupPermissionsLookup,
		Language $contentLanguage,
		TempUserConfig $tempUserConfig,
		RecentChangeLookup $recentChangeLookup
	) {
		parent::__construct( $query, $moduleName, 'au' );
		$this->userFactory = $userFactory;
		$this->userGroupManager = $userGroupManager;
		$this->groupPermissionsLookup = $groupPermissionsLookup;
		$this->contentLanguage = $contentLanguage;
		$this->tempUserConfig = $tempUserConfig;
		$this->recentChangeLookup = $recentChangeLookup;
	}

	/**
	 * This function converts the user name to a canonical form
	 * which is stored in the database.
	 * @param string $name
	 * @return string
	 */
	private function getCanonicalUserName( $name ) {
		$name = $this->contentLanguage->ucfirst( $name );
		return strtr( $name, '_', ' ' );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$activeUserDays = $this->getConfig()->get( MainConfigNames::ActiveUserDays );

		$db = $this->getDB();

		$prop = $params['prop'];
		if ( $prop !== null ) {
			$prop = array_fill_keys( $prop, true );
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
		$from = $params['from'] === null ? null : $this->getCanonicalUserName( $params['from'] );
		$to = $params['to'] === null ? null : $this->getCanonicalUserName( $params['to'] );

		# MySQL can't figure out that 'user_name' and 'qcc_title' are the same
		# despite the JOIN condition, so manually sort on the correct one.
		$userFieldToSort = $params['activeusers'] ? 'qcc_title' : 'user_name';

		# Some of these subtable joins are going to give us duplicate rows, so
		# calculate the maximum number of duplicates we might see.
		$maxDuplicateRows = 1;

		$this->addWhereRange( $userFieldToSort, $dir, $from, $to );

		if ( $params['prefix'] !== null ) {
			$this->addWhere(
				$db->expr(
					$userFieldToSort,
					IExpression::LIKE,
					new LikeValue( $this->getCanonicalUserName( $params['prefix'] ), $db->anyString() )
				)
			);
		}

		$excludeNamed = $params['excludenamed'];
		$excludeTemp = $params['excludetemp'];

		if ( $this->tempUserConfig->isKnown() ) {
			if ( $excludeTemp ) {
				$this->addWhere(
					$this->tempUserConfig->getMatchCondition( $db, 'user_name', IExpression::NOT_LIKE )
				);
			}
			if ( $excludeNamed ) {
				$this->addWhere(
					$this->tempUserConfig->getMatchCondition( $db, 'user_name', IExpression::LIKE )
				);
			}
		}

		if ( $params['rights'] !== null && count( $params['rights'] ) ) {
			$groups = [];
			// TODO: this does not properly account for $wgRevokePermissions
			foreach ( $params['rights'] as $r ) {
				if ( in_array( $r, $this->getPermissionManager()->getImplicitRights(), true ) ) {
					$groups[] = '*';
				} else {
					$groups = array_merge(
						$groups,
						$this->groupPermissionsLookup->getGroupsWithPermission( $r )
					);
				}
			}

			if ( $groups === [] ) {
				// No group with the given right(s) exists, no need for a query
				$this->getResult()->addIndexedTagName( [ 'query', $this->getModuleName() ], '' );

				return;
			}

			$groups = array_unique( $groups );
			if ( in_array( '*', $groups, true ) || in_array( 'user', $groups, true ) ) {
				// All user rows logically match but there are no "*"/"user" user_groups rows
				$groups = [];
			}

			if ( $params['group'] === null ) {
				$params['group'] = $groups;
			} else {
				$params['group'] = array_unique( array_merge( $params['group'], $groups ) );
			}
		}

		$this->requireMaxOneParameter( $params, 'group', 'excludegroup' );

		if ( $params['group'] !== null && count( $params['group'] ) ) {
			// Filter only users that belong to a given group. This might
			// produce as many rows-per-user as there are groups being checked.
			$this->addTables( 'user_groups', 'ug1' );
			$this->addJoinConds( [
				'ug1' => [
					'JOIN',
					[
						'ug1.ug_user=user_id',
						'ug1.ug_group' => $params['group'],
						$db->expr( 'ug1.ug_expiry', '=', null )->or( 'ug1.ug_expiry', '>=', $db->timestamp() ),
					]
				]
			] );
			$maxDuplicateRows *= count( $params['group'] );
		}

		if ( $params['excludegroup'] !== null && count( $params['excludegroup'] ) ) {
			// Filter only users don't belong to a given group. This can only
			// produce one row-per-user, because we only keep on "no match".
			$this->addTables( 'user_groups', 'ug1' );

			$this->addJoinConds( [ 'ug1' => [ 'LEFT JOIN',
				[
					'ug1.ug_user=user_id',
					$db->expr( 'ug1.ug_expiry', '=', null )->or( 'ug1.ug_expiry', '>=', $db->timestamp() ),
					'ug1.ug_group' => $params['excludegroup'],
				]
			] ] );
			$this->addWhere( [ 'ug1.ug_user' => null ] );
		}

		if ( $params['witheditsonly'] ) {
			$this->addWhere( $db->expr( 'user_editcount', '>', 0 ) );
		}

		$this->addDeletedUserFilter();

		if ( $fld_groups || $fld_rights ) {
			$this->addFields( [ 'groups' =>
				$db->newSelectQueryBuilder()
					->table( 'user_groups' )
					->field( 'ug_group' )
					->where( [
						'ug_user=user_id',
						$db->expr( 'ug_expiry', '=', null )->or( 'ug_expiry', '>=', $db->timestamp() )
					] )
					->buildGroupConcatField( '|' )
			] );
		}

		if ( $params['activeusers'] ) {
			$activeUserSeconds = $activeUserDays * 86400;

			// Filter query to only include users in the active users cache.
			// There shouldn't be any duplicate rows in querycachetwo here.
			$this->addTables( 'querycachetwo' );
			$this->addJoinConds( [ 'querycachetwo' => [
				'JOIN', [
					'qcc_type' => 'activeusers',
					'qcc_namespace' => NS_USER,
					'qcc_title=user_name',
				],
			] ] );

			// Actually count the actions using a subquery (T66505 and T66507)
			$timestamp = $db->timestamp( (int)wfTimestamp( TS_UNIX ) - $activeUserSeconds );
			$subqueryBuilder = $db->newSelectQueryBuilder()
				->select( 'COUNT(*)' )
				->from( 'recentchanges' )
				->join( 'actor', null, 'rc_actor = actor_id' )
				->where( [
					'actor_user = user_id',
					$db->expr( 'rc_source', '=', $this->recentChangeLookup->getPrimarySources() ),
					$db->expr( 'rc_log_type', '=', null )
						->or( 'rc_log_type', '!=', 'newusers' ),
					$db->expr( 'rc_timestamp', '>=', $timestamp ),
				] );
			$this->addFields( [
				'recentactions' => '(' . $subqueryBuilder->caller( __METHOD__ )->getSQL() . ')'
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
		$blockInfos = $fld_blockinfo ? $this->getBlockDetailsForRows( $res ) : null;
		foreach ( $res as $row ) {
			$count++;

			if ( $lastUser === $row->user_name ) {
				// Duplicate row due to one of the needed subtable joins.
				// Ignore it, but count the number of them to sensibly handle
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

			if ( $params['activeusers'] && (int)$row->recentactions === 0 ) {
				// activeusers cache was out of date
				continue;
			}

			$data = [
				'userid' => (int)$row->user_id,
				'name' => $row->user_name,
			];

			if ( $fld_centralids ) {
				$data += ApiQueryUserInfo::getCentralUserInfo(
					$this->getConfig(), $this->userFactory->newFromId( (int)$row->user_id ), $params['attachedwiki']
				);
			}

			if ( $fld_blockinfo && isset( $blockInfos[$row->user_id] ) ) {
				$data += $blockInfos[$row->user_id];
			}
			if ( $row->hu_deleted ) {
				$data['hidden'] = true;
			}
			if ( $fld_editcount ) {
				$data['editcount'] = (int)$row->user_editcount;
			}
			if ( $params['activeusers'] ) {
				$data['recentactions'] = (int)$row->recentactions;
			}
			if ( $fld_registration ) {
				$data['registration'] = $row->user_registration ?
					wfTimestamp( TS_ISO_8601, $row->user_registration ) : '';
			}

			if ( $fld_implicitgroups || $fld_groups || $fld_rights ) {
				$implicitGroups = $this->userGroupManager
					->getUserImplicitGroups( $this->userFactory->newFromId( (int)$row->user_id ) );
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
					$user = $this->userFactory->newFromId( (int)$row->user_id );
					$data['rights'] = $this->getPermissionManager()->getUserPermissions( $user );
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

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'anon-public-user-private';
	}

	/** @inheritDoc */
	public function getAllowedParams( $flags = 0 ) {
		$userGroups = $this->userGroupManager->listAllGroups();

		if ( $flags & ApiBase::GET_VALUES_FOR_HELP ) {
			sort( $userGroups );
		}

		return [
			'from' => null,
			'to' => null,
			'prefix' => null,
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending'
				],
			],
			'group' => [
				ParamValidator::PARAM_TYPE => $userGroups,
				ParamValidator::PARAM_ISMULTI => true,
			],
			'excludegroup' => [
				ParamValidator::PARAM_TYPE => $userGroups,
				ParamValidator::PARAM_ISMULTI => true,
			],
			'rights' => [
				ParamValidator::PARAM_TYPE => array_unique( array_merge(
					$this->getPermissionManager()->getAllPermissions(),
					$this->getPermissionManager()->getImplicitRights()
				) ),
				ParamValidator::PARAM_ISMULTI => true,
			],
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
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
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'witheditsonly' => false,
			'activeusers' => [
				ParamValidator::PARAM_DEFAULT => false,
				ApiBase::PARAM_HELP_MSG => [
					'apihelp-query+allusers-param-activeusers',
					$this->getConfig()->get( MainConfigNames::ActiveUserDays )
				],
			],
			'attachedwiki' => null,
			'excludenamed' => [
				ParamValidator::PARAM_TYPE => 'boolean',
			],
			'excludetemp' => [
				ParamValidator::PARAM_TYPE => 'boolean',
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=allusers&aufrom=Y'
				=> 'apihelp-query+allusers-example-y',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Allusers';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryAllUsers::class, 'ApiQueryAllUsers' );
