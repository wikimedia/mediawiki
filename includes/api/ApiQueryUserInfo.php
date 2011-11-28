<?php
/**
 *
 *
 * Created on July 30, 2007
 *
 * Copyright © 2007 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

	private $prop = array();

	public function __construct( $query, $moduleName ) {
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

	protected function getCurrentUserInfo() {
		global $wgRequest, $wgHiddenPrefs;
		$user = $this->getUser();
		$result = $this->getResult();
		$vals = array();
		$vals['id'] = intval( $user->getId() );
		$vals['name'] = $user->getName();

		if ( $user->isAnon() ) {
			$vals['anon'] = '';
		}

		if ( isset( $this->prop['blockinfo'] ) ) {
			if ( $user->isBlocked() ) {
				$vals['blockedby'] = User::whoIs( $user->blockedBy() );
				$vals['blockreason'] = $user->blockedFor();
			}
		}

		if ( isset( $this->prop['hasmsg'] ) && $user->getNewtalk() ) {
			$vals['messages'] = '';
		}

		if ( isset( $this->prop['groups'] ) ) {
			$autolist = ApiQueryUsers::getAutoGroups( $user );

			$vals['groups'] = array_merge( $autolist, $user->getGroups() );
			$result->setIndexedTagName( $vals['groups'], 'g' );	// even if empty
		}

		if ( isset( $this->prop['implicitgroups'] ) ) {
			$vals['implicitgroups'] = ApiQueryUsers::getAutoGroups( $user );
			$result->setIndexedTagName( $vals['implicitgroups'], 'g' );	// even if empty
		}

		if ( isset( $this->prop['rights'] ) ) {
			// User::getRights() may return duplicate values, strip them
			$vals['rights'] = array_values( array_unique( $user->getRights() ) );
			$result->setIndexedTagName( $vals['rights'], 'r' );	// even if empty
		}

		if ( isset( $this->prop['changeablegroups'] ) ) {
			$vals['changeablegroups'] = $user->changeableGroups();
			$result->setIndexedTagName( $vals['changeablegroups']['add'], 'g' );
			$result->setIndexedTagName( $vals['changeablegroups']['remove'], 'g' );
			$result->setIndexedTagName( $vals['changeablegroups']['add-self'], 'g' );
			$result->setIndexedTagName( $vals['changeablegroups']['remove-self'], 'g' );
		}

		if ( isset( $this->prop['options'] ) ) {
			$vals['options'] = $user->getOptions();
		}

		if ( isset( $this->prop['preferencestoken'] ) &&
			is_null( $this->getMain()->getRequest()->getVal( 'callback' ) )
		) {
			$vals['preferencestoken'] = $user->getEditToken( '', $this->getMain()->getRequest() );
		}

		if ( isset( $this->prop['editcount'] ) ) {
			$vals['editcount'] = intval( $user->getEditCount() );
		}

		if ( isset( $this->prop['ratelimits'] ) ) {
			$vals['ratelimits'] = $this->getRateLimits();
		}

		if ( isset( $this->prop['realname'] ) && !in_array( 'realname', $wgHiddenPrefs ) ) {
			$vals['realname'] = $user->getRealName();
		}

		if ( isset( $this->prop['email'] ) ) {
			$vals['email'] = $user->getEmail();
			$auth = $user->getEmailAuthenticationTimestamp();
			if ( !is_null( $auth ) ) {
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
			$langs = $wgRequest->getAcceptLang();
			$acceptLang = array();
			foreach ( $langs as $lang => $val ) {
				$r = array( 'q' => $val );
				ApiResult::setContent( $r, $lang );
				$acceptLang[] = $r;
			}
			$result->setIndexedTagName( $acceptLang, 'lang' );
			$vals['acceptlang'] = $acceptLang;
		}
		return $vals;
	}

	protected function getRateLimits() {
		global $wgRateLimits;
		$user = $this->getUser();
		if ( !$user->isPingLimitable() ) {
			return array(); // No limits
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
			if ( !$user->isAnon() )
				$categories[] = 'newbie';
		}
		$categories = array_merge( $categories, $user->getGroups() );

		// Now get the actual limits
		$retval = array();
		foreach ( $wgRateLimits as $action => $limits ) {
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
					'registrationdate'
				)
			)
		);
	}

	public function getParamDescription() {
		return array(
			'prop' => array(
				'What pieces of information to include',
				'  blockinfo        - Tags if the current user is blocked, by whom, and for what reason',
				'  hasmsg           - Adds a tag "message" if the current user has pending messages',
				'  groups           - Lists all the groups the current user belongs to',
				'  implicitgroups   - Lists all the groups the current user is automatically a member of',
				'  rights           - Lists all the rights the current user has',
				'  changeablegroups - Lists the groups the current user can add to and remove from',
				'  options          - Lists all preferences the current user has set',
				'  preferencestoken - Get a token to change current user\'s preferences',
				'  editcount        - Adds the current user\'s edit count',
				'  ratelimits       - Lists all rate limits applying to the current user',
				'  realname         - Adds the user\'s real name',
				'  email            - Adds the user\'s email address and email authentication date',
				'  acceptlang       - Echoes the Accept-Language header sent by the client in a structured format',
				'  registrationdate - Adds the user\'s registration date',
			)
		);
	}

	public function getDescription() {
		return 'Get information about the current user';
	}

	public function getExamples() {
		return array(
			'api.php?action=query&meta=userinfo',
			'api.php?action=query&meta=userinfo&uiprop=blockinfo|groups|rights|hasmsg',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Meta#userinfo_.2F_ui';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
