<?php
/**
 * Copyright © 2004 Brooke Vibber, lcrocker, Tim Starling,
 * Domas Mituzas, Antoine Musso, Jens Frank, Zhengzhu,
 * 2006 Rob Church <robchur@gmail.com>
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
 * @ingroup Pager
 */

namespace MediaWiki\Pager;

use MediaWiki\Block\HideUserUtils;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\Field\HTMLHiddenField;
use MediaWiki\HTMLForm\Field\HTMLInfoField;
use MediaWiki\HTMLForm\Field\HTMLSelectField;
use MediaWiki\HTMLForm\Field\HTMLSubmitField;
use MediaWiki\HTMLForm\Field\HTMLUserTextField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Linker\Linker;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupMembership;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityValue;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IExpression;

/**
 * This class is used to get a list of user. The ones with specials
 * rights (sysop, bureaucrat, developer) will have them displayed
 * next to their names.
 *
 * @ingroup Pager
 */
class UsersPager extends AlphabeticPager {

	/**
	 * @var array[] A array with user ids as key and a array of groups as value
	 */
	protected $userGroupCache;

	public ?string $requestedGroup;
	protected bool $editsOnly;
	protected bool $temporaryGroupsOnly;
	protected bool $temporaryAccountsOnly;
	protected bool $creationSort;
	protected ?bool $including;
	protected ?string $requestedUser;

	protected HideUserUtils $hideUserUtils;
	private HookRunner $hookRunner;
	private LinkBatchFactory $linkBatchFactory;
	private UserGroupManager $userGroupManager;
	private UserIdentityLookup $userIdentityLookup;
	private TempUserConfig $tempUserConfig;

	public function __construct(
		IContextSource $context,
		HookContainer $hookContainer,
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider,
		UserGroupManager $userGroupManager,
		UserIdentityLookup $userIdentityLookup,
		HideUserUtils $hideUserUtils,
		TempUserConfig $tempUserConfig,
		?string $par,
		?bool $including
	) {
		$this->setContext( $context );

		$request = $this->getRequest();
		$par ??= '';
		$parms = explode( '/', $par );
		$symsForAll = [ '*', 'user' ];

		if ( $parms[0] != '' &&
			( in_array( $par, $userGroupManager->listAllGroups() ) || in_array( $par, $symsForAll ) )
		) {
			$this->requestedGroup = $par;
			$un = $request->getText( 'username' );
		} elseif ( count( $parms ) == 2 ) {
			$this->requestedGroup = $parms[0];
			$un = $parms[1];
		} else {
			$this->requestedGroup = $request->getVal( 'group' );
			$un = ( $par != '' ) ? $par : $request->getText( 'username' );
		}

		if ( in_array( $this->requestedGroup, $symsForAll ) ) {
			$this->requestedGroup = '';
		}
		$this->editsOnly = $request->getBool( 'editsOnly' );
		$this->temporaryGroupsOnly = $request->getBool( 'temporaryGroupsOnly' );
		$this->temporaryAccountsOnly = $request->getBool( 'temporaryAccountsOnly' );
		$this->creationSort = $request->getBool( 'creationSort' );
		$this->including = $including;
		$this->mDefaultDirection = $request->getBool( 'desc' )
			? IndexPager::DIR_DESCENDING
			: IndexPager::DIR_ASCENDING;

		$this->requestedUser = '';

		if ( $un != '' ) {
			$username = Title::makeTitleSafe( NS_USER, $un );

			if ( $username !== null ) {
				$this->requestedUser = $username->getText();
			}
		}

		// Set database before parent constructor to avoid setting it there
		$this->mDb = $dbProvider->getReplicaDatabase();
		parent::__construct();
		$this->userGroupManager = $userGroupManager;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->userIdentityLookup = $userIdentityLookup;
		$this->hideUserUtils = $hideUserUtils;
		$this->tempUserConfig = $tempUserConfig;
	}

	/**
	 * @return string
	 */
	public function getIndexField() {
		return $this->creationSort ? 'user_id' : 'user_name';
	}

	/**
	 * @return array
	 */
	public function getQueryInfo() {
		$dbr = $this->getDatabase();
		$conds = [];
		$options = [];

		// Don't show hidden names
		if ( !$this->canSeeHideuser() ) {
			$conds[] = $this->hideUserUtils->getExpression( $dbr );
			$deleted = '1=0';
		} else {
			// In MySQL, there's no separate boolean type so getExpression()
			// effectively returns an integer, and MAX() works on the result of it.
			// In PostgreSQL, getExpression() returns a special boolean type which
			// can't go into MAX(). So we have to cast it to support PostgreSQL.

			// A neater PostgreSQL-only solution would be bool_or(), but MySQL
			// doesn't have that or need it. We could add a wrapper to SQLPlatform
			// which returns MAX() on MySQL and bool_or() on PostgreSQL.

			// This would not be necessary if we used "GROUP BY user_name,user_id",
			// but MariaDB forgets how to use indexes if you do that.
			$deleted = 'MAX(' . $dbr->buildIntegerCast(
				$this->hideUserUtils->getExpression( $dbr, 'user_id', HideUserUtils::HIDDEN_USERS )
			) . ')';
		}

		if ( $this->requestedGroup != '' || $this->temporaryGroupsOnly ) {
			$cond = $dbr->expr( 'ug_expiry', '>=', $dbr->timestamp() );
			if ( !$this->temporaryGroupsOnly ) {
				$cond = $cond->or( 'ug_expiry', '=', null );
			}
			$conds[] = $cond;
		}

		if ( $this->temporaryAccountsOnly && $this->tempUserConfig->isKnown() ) {
			$conds[] = $this->tempUserConfig->getMatchCondition(
				$dbr, 'user_name', IExpression::LIKE
			);
		}

		if ( $this->requestedGroup != '' ) {
			$conds['ug_group'] = $this->requestedGroup;
		}

		if ( $this->requestedUser != '' ) {
			# Sorted either by account creation or name
			if ( $this->creationSort ) {
				$userIdentity = $this->userIdentityLookup->getUserIdentityByName( $this->requestedUser );
				if ( $userIdentity && $userIdentity->isRegistered() ) {
					$conds[] = $dbr->expr( 'user_id', '>=', $userIdentity->getId() );
				}
			} else {
				$conds[] = $dbr->expr( 'user_name', '>=', $this->requestedUser );
			}
		}

		if ( $this->editsOnly ) {
			$conds[] = $dbr->expr( 'user_editcount', '>', 0 );
		}

		$options['GROUP BY'] = $this->creationSort ? 'user_id' : 'user_name';

		$query = [
			'tables' => [
				'user',
				'user_groups',
				'block_with_target' => [
					'block_target',
					'block'
				]
			],
			'fields' => [
				'user_name' => $this->creationSort ? 'MAX(user_name)' : 'user_name',
				'user_id' => $this->creationSort ? 'user_id' : 'MAX(user_id)',
				'edits' => 'MAX(user_editcount)',
				'creation' => 'MIN(user_registration)',
				'deleted' => $deleted, // block/hide status
				'sitewide' => 'MAX(bl_sitewide)'
			],
			'options' => $options,
			'join_conds' => [
				'user_groups' => [ 'LEFT JOIN', 'user_id=ug_user' ],
				'block_with_target' => [
					'LEFT JOIN', [
						'user_id=bt_user',
						'bt_auto' => 0
					]
				],
				'block' => [ 'JOIN', 'bl_target=bt_id' ]
			],
			'conds' => $conds
		];

		$this->hookRunner->onSpecialListusersQueryInfo( $this, $query );

		return $query;
	}

	/**
	 * @param stdClass $row
	 * @return string
	 */
	public function formatRow( $row ) {
		if ( $row->user_id == 0 ) { # T18487
			return '';
		}

		$userName = $row->user_name;

		$ulinks = Linker::userLink( $row->user_id, $userName );
		$ulinks .= Linker::userToolLinksRedContribs(
			$row->user_id,
			$userName,
			(int)$row->edits,
			// don't render parentheses in HTML markup (CSS will provide)
			false
		);

		$lang = $this->getLanguage();

		$groups = '';
		$userIdentity = new UserIdentityValue( intval( $row->user_id ), $userName );
		$ugms = $this->getGroupMemberships( $userIdentity );

		if ( !$this->including && count( $ugms ) > 0 ) {
			$list = [];
			foreach ( $ugms as $ugm ) {
				$list[] = $this->buildGroupLink( $ugm, $userName );
			}
			$groups = $lang->commaList( $list );
		}

		$item = $lang->specialList( $ulinks, $groups );

		if ( $row->deleted ) {
			$item = "<span class=\"deleted\">$item</span>";
		}

		$edits = '';
		if ( !$this->including && $this->getConfig()->get( MainConfigNames::Edititis ) ) {
			$count = $this->msg( 'usereditcount' )->numParams( $row->edits )->escaped();
			$edits = $this->msg( 'word-separator' )->escaped() . $this->msg( 'brackets', $count )->escaped();
		}

		$created = '';
		# Some rows may be null
		if ( !$this->including && $row->creation ) {
			$user = $this->getUser();
			$d = $lang->userDate( $row->creation, $user );
			$t = $lang->userTime( $row->creation, $user );
			$created = $this->msg( 'usercreated', $d, $t, $row->user_name )->escaped();
			$created = ' ' . $this->msg( 'parentheses' )->rawParams( $created )->escaped();
		}

		$blocked = $row->deleted !== null && $row->sitewide === '1' ?
			' ' . $this->msg( 'listusers-blocked', $userName )->escaped() :
			'';

		$this->hookRunner->onSpecialListusersFormatRow( $item, $row );

		return Html::rawElement( 'li', [], "{$item}{$edits}{$created}{$blocked}" );
	}

	/** @inheritDoc */
	protected function doBatchLookups() {
		$batch = $this->linkBatchFactory->newLinkBatch()->setCaller( __METHOD__ );
		$userIds = [];
		# Give some pointers to make user links
		foreach ( $this->mResult as $row ) {
			$user = new UserIdentityValue( $row->user_id, $row->user_name );
			$batch->addUser( $user );
			$userIds[] = $user->getId();
		}

		// Lookup groups for all the users
		$queryBuilder = $this->userGroupManager->newQueryBuilder( $this->getDatabase() );
		$groupRes = $queryBuilder->where( [ 'ug_user' => $userIds ] )
			->caller( __METHOD__ )
			->fetchResultSet();
		$cache = [];
		$groups = [];
		foreach ( $groupRes as $row ) {
			$ugm = $this->userGroupManager->newGroupMembershipFromRow( $row );
			if ( !$ugm->isExpired() ) {
				$cache[$row->ug_user][$row->ug_group] = $ugm;
				$groups[$row->ug_group] = true;
			}
		}

		// Give extensions a chance to add things like global user group data
		// into the cache array to ensure proper output later on
		$this->hookRunner->onUsersPagerDoBatchLookups( $this->getDatabase(), $userIds, $cache, $groups );

		$this->userGroupCache = $cache;

		// Add page of groups to link batch
		foreach ( $groups as $group => $unused ) {
			$groupPage = UserGroupMembership::getGroupPage( $group );
			if ( $groupPage ) {
				$batch->addObj( $groupPage );
			}
		}

		$batch->execute();
		$this->mResult->rewind();
	}

	/**
	 * @return string
	 */
	public function getPageHeader() {
		$self = explode( '/', $this->getTitle()->getPrefixedDBkey(), 2 )[0];

		$groupOptions = [ $this->msg( 'group-all' )->text() => '' ];
		foreach ( $this->getAllGroups() as $group => $groupText ) {
			if ( array_key_exists( $groupText, $groupOptions ) ) {
				LoggerFactory::getInstance( 'translation-problem' )->error(
					'The group {group_one} has the same translation as {group_two} for {lang}. ' .
					'{group_one} will not be displayed in group dropdown of the UsersPager.',
					[
						'group_one' => $group,
						'group_two' => $groupOptions[$groupText],
						'lang' => $this->getLanguage()->getCode(),
					]
				);
				continue;
			}
			$groupOptions[ $groupText ] = $group;
		}

		$formDescriptor = [
			'user' => [
				'class' => HTMLUserTextField::class,
				'label' => $this->msg( 'listusersfrom' )->text(),
				'name' => 'username',
				'default' => $this->requestedUser,
			],
			'dropdown' => [
				'label' => $this->msg( 'group' )->text(),
				'name' => 'group',
				'default' => $this->requestedGroup,
				'class' => HTMLSelectField::class,
				'options' => $groupOptions,
			],
			'editsOnly' => [
				'type' => 'check',
				'label' => $this->msg( 'listusers-editsonly' )->text(),
				'name' => 'editsOnly',
				'id' => 'editsOnly',
				'default' => $this->editsOnly
			],
			'temporaryGroupsOnly' => [
				'type' => 'check',
				'label' => $this->msg( 'listusers-temporarygroupsonly' )->text(),
				'name' => 'temporaryGroupsOnly',
				'id' => 'temporaryGroupsOnly',
				'default' => $this->temporaryGroupsOnly
			],
		];

		// If temporary accounts are known, add an option to filter for them
		if ( $this->tempUserConfig->isKnown() ) {
			$formDescriptor = array_merge( $formDescriptor, [
				'temporaryAccountsOnly' => [
					'type' => 'check',
					'label' => $this->msg( 'listusers-temporaryaccountsonly' )->text(),
					'name' => 'temporaryAccountsOnly',
					'id' => 'temporaryAccountsOnly',
					'default' => $this->temporaryAccountsOnly
				]
			] );
		}

		// Add sort options
		$formDescriptor = array_merge( $formDescriptor, [
			'creationSort' => [
				'type' => 'check',
				'label' => $this->msg( 'listusers-creationsort' )->text(),
				'name' => 'creationSort',
				'id' => 'creationSort',
				'default' => $this->creationSort
			],
			'desc' => [
				'type' => 'check',
				'label' => $this->msg( 'listusers-desc' )->text(),
				'name' => 'desc',
				'id' => 'desc',
				'default' => $this->mDefaultDirection
			],
			'limithiddenfield' => [
				'class' => HTMLHiddenField::class,
				'name' => 'limit',
				'default' => $this->mLimit
			],
		] );

		$beforeSubmitButtonHookOut = '';
		$this->hookRunner->onSpecialListusersHeaderForm( $this, $beforeSubmitButtonHookOut );

		if ( $beforeSubmitButtonHookOut !== '' ) {
			$formDescriptor[ 'beforeSubmitButtonHookOut' ] = [
				'class' => HTMLInfoField::class,
				'raw' => true,
				'default' => $beforeSubmitButtonHookOut
			];
		}

		$formDescriptor[ 'submit' ] = [
			'class' => HTMLSubmitField::class,
			'buttonlabel-message' => 'listusers-submit',
		];

		$beforeClosingFieldsetHookOut = '';
		$this->hookRunner->onSpecialListusersHeader( $this, $beforeClosingFieldsetHookOut );

		if ( $beforeClosingFieldsetHookOut !== '' ) {
			$formDescriptor[ 'beforeClosingFieldsetHookOut' ] = [
				'class' => HTMLInfoField::class,
				'raw' => true,
				'default' => $beforeClosingFieldsetHookOut
			];
		}

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$htmlForm
			->setMethod( 'get' )
			->setTitle( Title::newFromText( $self ) )
			->setId( 'mw-listusers-form' )
			->setFormIdentifier( 'mw-listusers-form' )
			->suppressDefaultSubmit()
			->setWrapperLegendMsg( 'listusers' );
		return $htmlForm->prepareForm()->getHTML( true );
	}

	/**
	 * @return bool
	 */
	protected function canSeeHideuser() {
		return $this->getAuthority()->isAllowed( 'hideuser' );
	}

	/**
	 * Get a list of all explicit groups
	 * @return array
	 */
	private function getAllGroups() {
		$result = [];
		$lang = $this->getLanguage();
		foreach ( $this->userGroupManager->listAllGroups() as $group ) {
			$result[$group] = $lang->getGroupName( $group );
		}
		asort( $result );

		return $result;
	}

	/**
	 * Preserve group and username offset parameters when paging
	 * @return array
	 */
	public function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		if ( $this->requestedGroup != '' ) {
			$query['group'] = $this->requestedGroup;
		}
		if ( $this->requestedUser != '' ) {
			$query['username'] = $this->requestedUser;
		}
		$this->hookRunner->onSpecialListusersDefaultQuery( $this, $query );

		return $query;
	}

	/**
	 * Get an associative array containing groups the specified user belongs to,
	 * and the relevant UserGroupMembership objects
	 *
	 * @param UserIdentity $user
	 * @return UserGroupMembership[] (group name => UserGroupMembership object)
	 */
	protected function getGroupMemberships( $user ) {
		if ( $this->userGroupCache === null ) {
			return $this->userGroupManager->getUserGroupMemberships( $user );
		} else {
			return $this->userGroupCache[$user->getId()] ?? [];
		}
	}

	/**
	 * Format a link to a group description page
	 *
	 * @param string|UserGroupMembership $group Group name or UserGroupMembership object
	 * @param string $username
	 * @return string
	 */
	protected function buildGroupLink( $group, $username ) {
		return UserGroupMembership::getLinkHTML( $group, $this->getContext(), $username );
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( UsersPager::class, 'UsersPager' );
