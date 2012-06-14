<?php
/**
 *
 *
 * Created on July 30, 2007
 *
 * Copyright © 2007 Yuri Astrakhan <Firstname><Lastname>@gmail.com
 * Copyright © 2011 John Du Hart john@johnduhart.me
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

		if ( !is_null( $params['prop'] ) ) {
			$this->prop = array_flip( $params['prop'] );
		}

		$res = $this->getCurrentUserInfo();

		if ( !is_null( $params['tokens'] ) ) {
			if ( empty($params['tokens']) ) {
				$tokens = array('edit'); // set default for empty, but existing parameter
			} else {
				$tokens = $params['tokens'];
			}
			$res['tokens'] = $this->getTokens($tokens);
		}

		$this->getResult()->addValue( 'query', $this->getModuleName(), $res );
	}

	protected function getCurrentUserInfo() {
		global $wgHiddenPrefs;
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
				$block = $user->getBlock();
				$vals['blockid'] = $block->getId();
				$vals['blockedby'] = $block->getByName();
				$vals['blockedbyid'] = $block->getBy();
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
			$langs = $this->getRequest()->getAcceptLang();
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

	protected function getTokens($tokens) {
		$res = array();
		$types = $this->getTokenTypes();
		
		foreach ( $tokens as $type ) {
			$type = strtolower( $type );

			$val = call_user_func( $types[$type], null, null );

			if ( $val === false ) {
				$this->setWarning( "Action '$type' is not allowed for the current user" );
			} else {
				$res[$type . 'token'] = $val;
			}
		}
		return $res;
	}
	
	protected function getTokenTypes() {
		static $types = null;
		if ( $types ) {
			return $types;
		}
		wfProfileIn( __METHOD__ );
		$types = array( 'patrol' => 'ApiQueryRecentChanges::getPatrolToken' );
		$names = array( 'edit', 'delete', 'protect', 'move', 'block', 'unblock',
			'email', 'import', 'watch', 'options' );
		foreach ( $names as $name ) {
			$types[$name] = 'ApiQueryInfo::get' . ucfirst( $name ) . 'Token';
		}
		wfRunHooks( 'ApiTokensGetTokenTypes', array( &$types ) ); // remove "Tokens" from Hook name?
		ksort( $types );
		wfProfileOut( __METHOD__ );
		return $types;
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
			),
			'tokens' => array(
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array_keys( $this->getTokenTypes() ),
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
			),
			'tokens' => 'Type of token(s) to request. Defaults to "edit"'
		);
	}

	public function getResultProperties() {
		return array(
			ApiBase::PROP_LIST => false,
			'' => array(
				'id' => 'integer',
				'name' => 'string',
				'anon' => 'boolean'
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
				)
			),
			'hasmsg' => array(
				'messages' => 'boolean'
			),
			'preferencestoken' => array(
				'preferencestoken' => 'string'
			),
			'editcount' => array(
				'editcount' => 'integer'
			),
			'realname' => array(
				'realname' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'email' => array(
				'email' => 'string',
				'emailauthenticated' => array(
					ApiBase::PROP_TYPE => 'timestamp',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'registrationdate' => array(
				'registrationdate' => array(
					ApiBase::PROP_TYPE => 'timestamp',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'tokens' => array(
				'patroltoken' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'edittoken' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'deletetoken' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'protecttoken' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'movetoken' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'blocktoken' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'unblocktoken' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'emailtoken' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'importtoken' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'watchtoken' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'optionstoken' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			)
		);
	}

	public function getDescription() {
		return 'Get information about the current user and tokens for data-modifying actions';
	}

	public function getExamples() {
		return array(
			'api.php?action=query&meta=userinfo',
			'api.php?action=query&meta=userinfo&uiprop=blockinfo|groups|rights|hasmsg',
			'api.php?action=query&meta=userinfo&uitokens' => 'Retrieve an edit token (the default)',
			'api.php?action=query&meta=userinfo&uitokens=email|move' => 'Retrieve an email token and a move token'
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Meta#userinfo_.2F_ui';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
