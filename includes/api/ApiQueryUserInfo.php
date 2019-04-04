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

use MediaWiki\MediaWikiServices;

/**
 * Query module to get information about the currently logged-in user
 *
 * @ingroup API
 */
class ApiQueryUserInfo extends ApiQueryBase {

	const WL_UNREAD_LIMIT = 1000;

	private $params = [];
	private $prop = [];

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'ui' );
	}

	public function execute() {
		$this->params = $this->extractRequestParams();
		$result = $this->getResult();

		if ( !is_null( $this->params['prop'] ) ) {
			$this->prop = array_flip( $this->params['prop'] );
		}

		$r = $this->getCurrentUserInfo();
		$result->addValue( 'query', $this->getModuleName(), $r );
	}

	/**
	 * Get basic info about a given block
	 * @param Block $block
	 * @return array Array containing several keys:
	 *  - blockid - ID of the block
	 *  - blockedby - username of the blocker
	 *  - blockedbyid - user ID of the blocker
	 *  - blockreason - reason provided for the block
	 *  - blockedtimestamp - timestamp for when the block was placed/modified
	 *  - blockexpiry - expiry time of the block
	 *  - systemblocktype - system block type, if any
	 */
	public static function getBlockInfo( Block $block ) {
		$vals = [];
		$vals['blockid'] = $block->getId();
		$vals['blockedby'] = $block->getByName();
		$vals['blockedbyid'] = $block->getBy();
		$vals['blockreason'] = $block->getReason();
		$vals['blockedtimestamp'] = wfTimestamp( TS_ISO_8601, $block->getTimestamp() );
		$vals['blockexpiry'] = ApiResult::formatExpiry( $block->getExpiry(), 'infinite' );
		$vals['blockpartial'] = !$block->isSitewide();
		if ( $block->getSystemBlockType() !== null ) {
			$vals['systemblocktype'] = $block->getSystemBlockType();
		}
		return $vals;
	}

	/**
	 * Get central user info
	 * @param Config $config
	 * @param User $user
	 * @param string|null $attachedWiki
	 * @return array Central user info
	 *  - centralids: Array mapping non-local Central ID provider names to IDs
	 *  - attachedlocal: Array mapping Central ID provider names to booleans
	 *    indicating whether the local user is attached.
	 *  - attachedwiki: Array mapping Central ID provider names to booleans
	 *    indicating whether the user is attached to $attachedWiki.
	 */
	public static function getCentralUserInfo( Config $config, User $user, $attachedWiki = null ) {
		$providerIds = array_keys( $config->get( 'CentralIdLookupProviders' ) );

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
		foreach ( $providerIds as $providerId ) {
			$provider = CentralIdLookup::factory( $providerId );
			$ret['centralids'][$providerId] = $provider->centralIdFromName( $name );
			$ret['attachedlocal'][$providerId] = $provider->isAttached( $user );
			if ( $attachedWiki ) {
				$ret['attachedwiki'][$providerId] = $provider->isAttached( $user, $attachedWiki );
			}
		}

		return $ret;
	}

	protected function getCurrentUserInfo() {
		$user = $this->getUser();
		$vals = [];
		$vals['id'] = (int)$user->getId();
		$vals['name'] = $user->getName();

		if ( $user->isAnon() ) {
			$vals['anon'] = true;
		}

		if ( isset( $this->prop['blockinfo'] ) && $user->isBlocked() ) {
			$vals = array_merge( $vals, self::getBlockInfo( $user->getBlock() ) );
		}

		if ( isset( $this->prop['hasmsg'] ) ) {
			$vals['messages'] = $user->getNewtalk();
		}

		if ( isset( $this->prop['groups'] ) ) {
			$vals['groups'] = $user->getEffectiveGroups();
			ApiResult::setArrayType( $vals['groups'], 'array' ); // even if empty
			ApiResult::setIndexedTagName( $vals['groups'], 'g' ); // even if empty
		}

		if ( isset( $this->prop['groupmemberships'] ) ) {
			$ugms = $user->getGroupMemberships();
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
			$vals['implicitgroups'] = $user->getAutomaticGroups();
			ApiResult::setArrayType( $vals['implicitgroups'], 'array' ); // even if empty
			ApiResult::setIndexedTagName( $vals['implicitgroups'], 'g' ); // even if empty
		}

		if ( isset( $this->prop['rights'] ) ) {
			// User::getRights() may return duplicate values, strip them
			$vals['rights'] = array_values( array_unique( $user->getRights() ) );
			ApiResult::setArrayType( $vals['rights'], 'array' ); // even if empty
			ApiResult::setIndexedTagName( $vals['rights'], 'r' ); // even if empty
		}

		if ( isset( $this->prop['changeablegroups'] ) ) {
			$vals['changeablegroups'] = $user->changeableGroups();
			ApiResult::setIndexedTagName( $vals['changeablegroups']['add'], 'g' );
			ApiResult::setIndexedTagName( $vals['changeablegroups']['remove'], 'g' );
			ApiResult::setIndexedTagName( $vals['changeablegroups']['add-self'], 'g' );
			ApiResult::setIndexedTagName( $vals['changeablegroups']['remove-self'], 'g' );
		}

		if ( isset( $this->prop['options'] ) ) {
			$vals['options'] = $user->getOptions();
			$vals['options'][ApiResult::META_BC_BOOLS] = array_keys( $vals['options'] );
		}

		if ( isset( $this->prop['preferencestoken'] ) &&
			!$this->lacksSameOriginSecurity() &&
			$user->isAllowed( 'editmyoptions' )
		) {
			$vals['preferencestoken'] = $user->getEditToken( '', $this->getMain()->getRequest() );
		}

		if ( isset( $this->prop['editcount'] ) ) {
			// use intval to prevent null if a non-logged-in user calls
			// api.php?format=jsonfm&action=query&meta=userinfo&uiprop=editcount
			$vals['editcount'] = (int)$user->getEditCount();
		}

		if ( isset( $this->prop['ratelimits'] ) ) {
			$vals['ratelimits'] = $this->getRateLimits();
		}

		if ( isset( $this->prop['realname'] ) &&
			!in_array( 'realname', $this->getConfig()->get( 'HiddenPrefs' ) )
		) {
			$vals['realname'] = $user->getRealName();
		}

		if ( $user->isAllowed( 'viewmyprivateinfo' ) && isset( $this->prop['email'] ) ) {
			$vals['email'] = $user->getEmail();
			$auth = $user->getEmailAuthenticationTimestamp();
			if ( $auth !== null ) {
				$vals['emailauthenticated'] = wfTimestamp( TS_ISO_8601, $auth );
			}
		}

		if ( isset( $this->prop['registrationdate'] ) ) {
			$regDate = $user->getRegistration();
			if ( $regDate !== false ) {
				$vals['registrationdate'] = wfTimestamp( TS_ISO_8601, $regDate );
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
			$store = MediaWikiServices::getInstance()->getWatchedItemStore();
			$unreadNotifications = $store->countUnreadNotifications(
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

		return $vals;
	}

	protected function getRateLimits() {
		$retval = [
			ApiResult::META_TYPE => 'assoc',
		];

		$user = $this->getUser();
		if ( !$user->isPingLimitable() ) {
			return $retval; // No limits
		}

		// Find out which categories we belong to
		$categories = [];
		if ( $user->isAnon() ) {
			$categories[] = 'anon';
		} else {
			$categories[] = 'user';
		}
		if ( $user->isNewbie() ) {
			$categories[] = 'ip';
			$categories[] = 'subnet';
			if ( !$user->isAnon() ) {
				$categories[] = 'newbie';
			}
		}
		$categories = array_merge( $categories, $user->getGroups() );

		// Now get the actual limits
		foreach ( $this->getConfig()->get( 'RateLimits' ) as $action => $limits ) {
			foreach ( $categories as $cat ) {
				if ( isset( $limits[$cat] ) && !is_null( $limits[$cat] ) ) {
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
		global $wgActorTableSchemaMigrationStage;

		$user = $this->getUser();
		$dbr = $this->getDB();

		if ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_READ_NEW ) {
			if ( $user->getActorId() === null ) {
				return null;
			}
			$res = $dbr->selectField( 'revision_actor_temp',
				'MAX(revactor_timestamp)',
				[ 'revactor_actor' => $user->getActorId() ],
				__METHOD__
			);
		} else {
			if ( $user->isLoggedIn() ) {
				$conds = [ 'rev_user' => $user->getId() ];
			} else {
				$conds = [ 'rev_user_text' => $user->getName() ];
			}
			$res = $dbr->selectField( 'revision',
				'MAX(rev_timestamp)',
				$conds,
				__METHOD__
			);
		}

		return $res ? wfTimestamp( TS_ISO_8601, $res ) : null;
	}

	public function getAllowedParams() {
		return [
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
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
					'email',
					'realname',
					'acceptlang',
					'registrationdate',
					'unreadcount',
					'centralids',
					'preferencestoken',
					'latestcontrib',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'unreadcount' => [
						'apihelp-query+userinfo-paramvalue-prop-unreadcount',
						self::WL_UNREAD_LIMIT - 1,
						self::WL_UNREAD_LIMIT . '+',
					],
				],
				ApiBase::PARAM_DEPRECATED_VALUES => [
					'preferencestoken' => [
						'apiwarn-deprecation-withreplacement',
						$this->getModulePrefix() . "prop=preferencestoken",
						'action=query&meta=tokens',
					]
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
