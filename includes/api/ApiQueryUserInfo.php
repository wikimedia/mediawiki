<?php
/**
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

namespace MediaWiki\Api;

use MediaWiki\Config\Config;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWTimestamp;
use MediaWiki\Watchlist\WatchedItemStore;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Query module to get information about the currently logged-in user
 *
 * @ingroup API
 */
class ApiQueryUserInfo extends ApiQueryBase {

	use ApiBlockInfoTrait;

	private const WL_UNREAD_LIMIT = 1000;

	/** @var array */
	private $params = [];

	/** @var array */
	private $prop = [];

	private TalkPageNotificationManager $talkPageNotificationManager;
	private WatchedItemStore $watchedItemStore;
	private UserEditTracker $userEditTracker;
	private UserOptionsLookup $userOptionsLookup;
	private UserGroupManager $userGroupManager;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		TalkPageNotificationManager $talkPageNotificationManager,
		WatchedItemStore $watchedItemStore,
		UserEditTracker $userEditTracker,
		UserOptionsLookup $userOptionsLookup,
		UserGroupManager $userGroupManager
	) {
		parent::__construct( $query, $moduleName, 'ui' );
		$this->talkPageNotificationManager = $talkPageNotificationManager;
		$this->watchedItemStore = $watchedItemStore;
		$this->userEditTracker = $userEditTracker;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->userGroupManager = $userGroupManager;
	}

	public function execute() {
		$this->params = $this->extractRequestParams();
		$result = $this->getResult();

		if ( $this->params['prop'] !== null ) {
			$this->prop = array_fill_keys( $this->params['prop'], true );
		}

		$r = $this->getCurrentUserInfo();
		$result->addValue( 'query', $this->getModuleName(), $r );
	}

	/**
	 * Get central user info
	 * @param Config $config
	 * @param UserIdentity $user
	 * @param string|false $attachedWiki
	 * @return array Central user info
	 *  - centralids: Array mapping non-local Central ID provider names to IDs
	 *  - attachedlocal: Array mapping Central ID provider names to booleans
	 *    indicating whether the local user is attached.
	 *  - attachedwiki: Array mapping Central ID provider names to booleans
	 *    indicating whether the user is attached to $attachedWiki.
	 */
	public static function getCentralUserInfo(
		Config $config,
		UserIdentity $user,
		$attachedWiki = UserIdentity::LOCAL
	) {
		$providerIds = array_keys( $config->get( MainConfigNames::CentralIdLookupProviders ) );

		$ret = [
			'centralids' => [],
			'attachedlocal' => [],
		];
		ApiResult::setArrayType( $ret['centralids'], 'assoc' );
		ApiResult::setArrayType( $ret['attachedlocal'], 'assoc' );
		if ( $attachedWiki ) {
			$ret['attachedwiki'] = [];
			ApiResult::setArrayType( $ret['attachedwiki'], 'assoc' );
		}

		$name = $user->getName();
		$centralIdLookupFactory = MediaWikiServices::getInstance()
			->getCentralIdLookupFactory();
		foreach ( $providerIds as $providerId ) {
			$provider = $centralIdLookupFactory->getLookup( $providerId );
			$ret['centralids'][$providerId] = $provider->centralIdFromName( $name );
			$ret['attachedlocal'][$providerId] = $provider->isAttached( $user );
			if ( $attachedWiki ) {
				$ret['attachedwiki'][$providerId] = $provider->isAttached( $user, $attachedWiki );
			}
		}

		return $ret;
	}

	protected function getCurrentUserInfo(): array {
		$user = $this->getUser();
		$vals = [];
		$vals['id'] = $user->getId();
		$vals['name'] = $user->getName();

		if ( !$user->isRegistered() ) {
			$vals['anon'] = true;
		}

		if ( $user->isTemp() ) {
			$vals['temp'] = true;
		}

		if ( isset( $this->prop['blockinfo'] ) ) {
			$block = $user->getBlock();
			if ( $block ) {
				$vals = array_merge( $vals, $this->getBlockDetails( $block ) );
			}
		}

		if ( isset( $this->prop['hasmsg'] ) ) {
			$vals['messages'] = $this->talkPageNotificationManager->userHasNewMessages( $user );
		}

		if ( isset( $this->prop['groups'] ) ) {
			$vals['groups'] = $this->userGroupManager->getUserEffectiveGroups( $user );
			ApiResult::setArrayType( $vals['groups'], 'array' ); // even if empty
			ApiResult::setIndexedTagName( $vals['groups'], 'g' ); // even if empty
		}

		if ( isset( $this->prop['groupmemberships'] ) ) {
			$ugms = $this->userGroupManager->getUserGroupMemberships( $user );
			$vals['groupmemberships'] = [];
			foreach ( $ugms as $group => $ugm ) {
				$vals['groupmemberships'][] = [
					'group' => $group,
					'expiry' => ApiResult::formatExpiry( $ugm->getExpiry() ),
				];
			}
			ApiResult::setArrayType( $vals['groupmemberships'], 'array' ); // even if empty
			ApiResult::setIndexedTagName( $vals['groupmemberships'], 'groupmembership' ); // even if empty
		}

		if ( isset( $this->prop['implicitgroups'] ) ) {
			$vals['implicitgroups'] = $this->userGroupManager->getUserImplicitGroups( $user );
			ApiResult::setArrayType( $vals['implicitgroups'], 'array' ); // even if empty
			ApiResult::setIndexedTagName( $vals['implicitgroups'], 'g' ); // even if empty
		}

		if ( isset( $this->prop['rights'] ) ) {
			$vals['rights'] = $this->getPermissionManager()->getUserPermissions( $user );
			ApiResult::setArrayType( $vals['rights'], 'array' ); // even if empty
			ApiResult::setIndexedTagName( $vals['rights'], 'r' ); // even if empty
		}

		if ( isset( $this->prop['changeablegroups'] ) ) {
			$vals['changeablegroups'] = $this->userGroupManager->getGroupsChangeableBy( $this->getAuthority() );
			ApiResult::setIndexedTagName( $vals['changeablegroups']['add'], 'g' );
			ApiResult::setIndexedTagName( $vals['changeablegroups']['remove'], 'g' );
			ApiResult::setIndexedTagName( $vals['changeablegroups']['add-self'], 'g' );
			ApiResult::setIndexedTagName( $vals['changeablegroups']['remove-self'], 'g' );
		}

		if ( isset( $this->prop['options'] ) ) {
			$vals['options'] = $this->userOptionsLookup->getOptions( $user );
			$vals['options'][ApiResult::META_BC_BOOLS] = array_keys( $vals['options'] );
		}

		if ( isset( $this->prop['editcount'] ) ) {
			// use intval to prevent null if a non-logged-in user calls
			// api.php?format=jsonfm&action=query&meta=userinfo&uiprop=editcount
			$vals['editcount'] = (int)$user->getEditCount();
		}

		if ( isset( $this->prop['ratelimits'] ) ) {
			// true = real rate limits, taking User::isPingLimitable into account
			$vals['ratelimits'] = $this->getRateLimits( true );
		}
		if ( isset( $this->prop['theoreticalratelimits'] ) ) {
			// false = ignore User::isPingLimitable
			$vals['theoreticalratelimits'] = $this->getRateLimits( false );
		}

		if ( isset( $this->prop['realname'] ) &&
			!in_array( 'realname', $this->getConfig()->get( MainConfigNames::HiddenPrefs ) )
		) {
			$vals['realname'] = $user->getRealName();
		}

		if ( $this->getAuthority()->isAllowed( 'viewmyprivateinfo' ) && isset( $this->prop['email'] ) ) {
			$vals['email'] = $user->getEmail();
			$auth = $user->getEmailAuthenticationTimestamp();
			if ( $auth !== null ) {
				$vals['emailauthenticated'] = wfTimestamp( TS_ISO_8601, $auth );
			}
		}

		if ( isset( $this->prop['registrationdate'] ) ) {
			$regDate = $user->getRegistration();
			if ( $regDate !== false ) {
				$vals['registrationdate'] = wfTimestampOrNull( TS_ISO_8601, $regDate );
			}
		}

		if ( isset( $this->prop['acceptlang'] ) ) {
			$langs = $this->getRequest()->getAcceptLang();
			$acceptLang = [];
			foreach ( $langs as $lang => $val ) {
				$r = [ 'q' => $val ];
				ApiResult::setContentValue( $r, 'code', $lang );
				$acceptLang[] = $r;
			}
			ApiResult::setIndexedTagName( $acceptLang, 'lang' );
			$vals['acceptlang'] = $acceptLang;
		}

		if ( isset( $this->prop['unreadcount'] ) ) {
			$unreadNotifications = $this->watchedItemStore->countUnreadNotifications(
				$user,
				self::WL_UNREAD_LIMIT
			);

			if ( $unreadNotifications === true ) {
				$vals['unreadcount'] = self::WL_UNREAD_LIMIT . '+';
			} else {
				$vals['unreadcount'] = $unreadNotifications;
			}
		}

		if ( isset( $this->prop['centralids'] ) ) {
			$vals += self::getCentralUserInfo(
				$this->getConfig(), $this->getUser(), $this->params['attachedwiki']
			);
		}

		if ( isset( $this->prop['latestcontrib'] ) ) {
			$ts = $this->getLatestContributionTime();
			if ( $ts !== null ) {
				$vals['latestcontrib'] = $ts;
			}
		}

		if ( isset( $this->prop['cancreateaccount'] ) ) {
			$status = PermissionStatus::newEmpty();
			$vals['cancreateaccount'] = $user->definitelyCan( 'createaccount',
				SpecialPage::getTitleFor( 'CreateAccount' ), $status );
			if ( !$status->isGood() ) {
				$vals['cancreateaccounterror'] = $this->getErrorFormatter()->arrayFromStatus( $status );
			}
		}

		return $vals;
	}

	/**
	 * Get the rate limits that apply to the user, or the rate limits
	 * that would apply if the user didn't have `noratelimit`
	 *
	 * @param bool $applyNoRateLimit
	 * @return array
	 */
	protected function getRateLimits( bool $applyNoRateLimit ) {
		$retval = [
			ApiResult::META_TYPE => 'assoc',
		];

		$user = $this->getUser();
		if ( $applyNoRateLimit && !$user->isPingLimitable() ) {
			return $retval; // No limits
		}

		// Find out which categories we belong to
		$categories = [];
		if ( !$user->isRegistered() ) {
			$categories[] = 'anon';
		} else {
			$categories[] = 'user';
		}
		if ( $user->isNewbie() ) {
			$categories[] = 'ip';
			$categories[] = 'subnet';
			if ( $user->isRegistered() ) {
				$categories[] = 'newbie';
			}
		}
		$categories = array_merge( $categories, $this->userGroupManager->getUserGroups( $user ) );

		// Now get the actual limits
		foreach ( $this->getConfig()->get( MainConfigNames::RateLimits ) as $action => $limits ) {
			foreach ( $categories as $cat ) {
				if ( isset( $limits[$cat] ) ) {
					$retval[$action][$cat]['hits'] = (int)$limits[$cat][0];
					$retval[$action][$cat]['seconds'] = (int)$limits[$cat][1];
				}
			}
		}

		return $retval;
	}

	/**
	 * @return string|null ISO 8601 timestamp of current user's last contribution or null if none
	 */
	protected function getLatestContributionTime() {
		$timestamp = $this->userEditTracker->getLatestEditTimestamp( $this->getUser() );
		if ( $timestamp === false ) {
			return null;
		}
		return MWTimestamp::convert( TS_ISO_8601, $timestamp );
	}

	public function getAllowedParams() {
		return [
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_ALL => true,
				ParamValidator::PARAM_TYPE => [
					'blockinfo',
					'hasmsg',
					'groups',
					'groupmemberships',
					'implicitgroups',
					'rights',
					'changeablegroups',
					'options',
					'editcount',
					'ratelimits',
					'theoreticalratelimits',
					'email',
					'realname',
					'acceptlang',
					'registrationdate',
					'unreadcount',
					'centralids',
					'latestcontrib',
					'cancreateaccount',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'unreadcount' => [
						'apihelp-query+userinfo-paramvalue-prop-unreadcount',
						self::WL_UNREAD_LIMIT - 1,
						self::WL_UNREAD_LIMIT . '+',
					],
				],
			],
			'attachedwiki' => null,
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&meta=userinfo'
				=> 'apihelp-query+userinfo-example-simple',
			'action=query&meta=userinfo&uiprop=blockinfo|groups|rights|hasmsg'
				=> 'apihelp-query+userinfo-example-data',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Userinfo';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryUserInfo::class, 'ApiQueryUserInfo' );
