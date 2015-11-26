<?php
/**
 *
 *
 * Created on July 30, 2007
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
 * Query module to get information about the currently logged-in user
 *
 * @ingroup API
 */
class ApiQueryUserInfo extends ApiQueryBase {

	const WL_UNREAD_LIMIT = 1000;

	private $prop = array();

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'ui' );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$result = $this->getResult();

		if ( !is_null( $params['prop'] ) ) {
			$this->prop = array_flip( $params['prop'] );
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
	 */
	public static function getBlockInfo( Block $block ) {
		global $wgContLang;
		$vals = array();
		$vals['blockid'] = $block->getId();
		$vals['blockedby'] = $block->getByName();
		$vals['blockedbyid'] = $block->getBy();
		$vals['blockreason'] = $block->mReason;
		$vals['blockedtimestamp'] = wfTimestamp( TS_ISO_8601, $block->mTimestamp );
		$vals['blockexpiry'] = $wgContLang->formatExpiry(
			$block->getExpiry(), TS_ISO_8601, 'infinite'
		);
		return $vals;
	}

	protected function getCurrentUserInfo() {
		$user = $this->getUser();
		$vals = array();
		$vals['id'] = intval( $user->getId() );
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

		if ( isset( $this->prop['preferencestoken'] ) ) {
			$p = $this->getModulePrefix();
			$this->setWarning(
				"{$p}prop=preferencestoken has been deprecated. Please use action=query&meta=tokens instead."
			);
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
			$vals['editcount'] = intval( $user->getEditCount() );
		}

		if ( isset( $this->prop['ratelimits'] ) ) {
			$vals['ratelimits'] = $this->getRateLimits();
		}

		if ( isset( $this->prop['realname'] ) && !in_array( 'realname', $this->getConfig()->get( 'HiddenPrefs' ) ) ) {
			$vals['realname'] = $user->getRealName();
		}

		if ( $user->isAllowed( 'viewmyprivateinfo' ) ) {
			if ( isset( $this->prop['email'] ) ) {
				$vals['email'] = $user->getEmail();
				$auth = $user->getEmailAuthenticationTimestamp();
				if ( !is_null( $auth ) ) {
					$vals['emailauthenticated'] = wfTimestamp( TS_ISO_8601, $auth );
				}
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
			$acceptLang = array();
			foreach ( $langs as $lang => $val ) {
				$r = array( 'q' => $val );
				ApiResult::setContentValue( $r, 'code', $lang );
				$acceptLang[] = $r;
			}
			ApiResult::setIndexedTagName( $acceptLang, 'lang' );
			$vals['acceptlang'] = $acceptLang;
		}

		if ( isset( $this->prop['unreadcount'] ) ) {
			$dbr = $this->getQuery()->getNamedDB( 'watchlist', DB_SLAVE, 'watchlist' );

			$count = $dbr->selectRowCount(
				'watchlist',
				'1',
				array(
					'wl_user' => $user->getId(),
					'wl_notificationtimestamp IS NOT NULL',
				),
				__METHOD__,
				array( 'LIMIT' => self::WL_UNREAD_LIMIT )
			);

			if ( $count >= self::WL_UNREAD_LIMIT ) {
				$vals['unreadcount'] = self::WL_UNREAD_LIMIT . '+';
			} else {
				$vals['unreadcount'] = $count;
			}
		}

		return $vals;
	}

	protected function getRateLimits() {
		$retval = array(
			ApiResult::META_TYPE => 'assoc',
		);

		$user = $this->getUser();
		if ( !$user->isPingLimitable() ) {
			return $retval; // No limits
		}

		// Find out which categories we belong to
		$categories = array();
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
					$retval[$action][$cat]['hits'] = intval( $limits[$cat][0] );
					$retval[$action][$cat]['seconds'] = intval( $limits[$cat][1] );
				}
			}
		}

		return $retval;
	}

	public function getAllowedParams() {
		return array(
			'prop' => array(
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'blockinfo',
					'hasmsg',
					'groups',
					'implicitgroups',
					'rights',
					'changeablegroups',
					'options',
					'preferencestoken',
					'editcount',
					'ratelimits',
					'email',
					'realname',
					'acceptlang',
					'registrationdate',
					'unreadcount',
				),
				ApiBase::PARAM_HELP_MSG_PER_VALUE => array(
					'unreadcount' => array(
						'apihelp-query+userinfo-paramvalue-prop-unreadcount',
						self::WL_UNREAD_LIMIT - 1,
						self::WL_UNREAD_LIMIT . '+',
					),
				),
			)
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&meta=userinfo'
				=> 'apihelp-query+userinfo-example-simple',
			'action=query&meta=userinfo&uiprop=blockinfo|groups|rights|hasmsg'
				=> 'apihelp-query+userinfo-example-data',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Userinfo';
	}
}
