<?php

/*
 * Created on Sep 19, 2006
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2006-2007 Yuri Astrakhan <Firstname><Lastname>@gmail.com,
 * Daniel Cannon (cannon dot danielc at gmail dot com)
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if (!defined('MEDIAWIKI')) {
	// Eclipse helper - will be ignored in production
	require_once ('ApiBase.php');
}

/**
 * Unit to authenticate log-in attempts to the current wiki.
 *
 * @ingroup API
 */
class ApiLogin extends ApiBase {

	public function __construct($main, $action) {
		parent :: __construct($main, $action, 'lg');
	}

	/**
	 * Executes the log-in attempt using the parameters passed. If
	 * the log-in succeeeds, it attaches a cookie to the session
	 * and outputs the user id, username, and session token. If a
	 * log-in fails, as the result of a bad password, a nonexistant
	 * user, or any other reason, the host is cached with an expiry
	 * and no log-in attempts will be accepted until that expiry
	 * is reached. The expiry is $this->mLoginThrottle.
	 *
	 * @access public
	 */
	public function execute() {
		$name = $password = $domain = null;
		extract($this->extractRequestParams());

		$result = array ();

		$params = new FauxRequest(array (
			'wpName' => $name,
			'wpPassword' => $password,
			'wpDomain' => $domain,
			'wpRemember' => ''
		));

		// Init session if necessary
		if( session_id() == '' ) {
			wfSetupSession();
		}

		$loginForm = new LoginForm($params);
		switch ($authRes = $loginForm->authenticateUserData()) {
			case LoginForm :: SUCCESS :
				global $wgUser, $wgCookiePrefix;

				$wgUser->setOption('rememberpassword', 1);
				$wgUser->setCookies();

				// Run hooks. FIXME: split back and frontend from this hook.
				// FIXME: This hook should be placed in the backend
				$injected_html = '';
				wfRunHooks('UserLoginComplete', array(&$wgUser, &$injected_html));

				$result['result'] = 'Success';
				$result['lguserid'] = $wgUser->getId();
				$result['lgusername'] = $wgUser->getName();
				$result['lgtoken'] = $wgUser->getToken();
				$result['cookieprefix'] = $wgCookiePrefix;
				$result['sessionid'] = session_id();
				break;

			case LoginForm :: NO_NAME :
				$result['result'] = 'NoName';
				break;
			case LoginForm :: ILLEGAL :
				$result['result'] = 'Illegal';
				break;
			case LoginForm :: WRONG_PLUGIN_PASS :
				$result['result'] = 'WrongPluginPass';
				break;
			case LoginForm :: NOT_EXISTS :
				$result['result'] = 'NotExists';
				break;
			case LoginForm :: WRONG_PASS :
				$result['result'] = 'WrongPass';
				break;
			case LoginForm :: EMPTY_PASS :
				$result['result'] = 'EmptyPass';
				break;
			case LoginForm :: CREATE_BLOCKED :
				$result['result'] = 'CreateBlocked';
				$result['details'] = 'Your IP address is blocked from account creation';
				break;
			case LoginForm :: THROTTLED :
				global $wgPasswordAttemptThrottle;
				$result['result'] = 'Throttled';
				$result['wait'] = $wgPasswordAttemptThrottle['seconds'];
				break;
			default :
				ApiBase :: dieDebug(__METHOD__, "Unhandled case value: {$authRes}");
		}

		$this->getResult()->addValue(null, 'login', $result);
	}

	public function mustBePosted() { return true; }

	public function getAllowedParams() {
		return array (
			'name' => null,
			'password' => null,
			'domain' => null
		);
	}

	public function getParamDescription() {
		return array (
			'name' => 'User Name',
			'password' => 'Password',
			'domain' => 'Domain (optional)'
		);
	}

	public function getDescription() {
		return array (
			'This module is used to login and get the authentication tokens. ',
			'In the event of a successful log-in, a cookie will be attached',
			'to your session. In the event of a failed log-in, you will not ',
			'be able to attempt another log-in through this method for 5 seconds.',
			'This is to prevent password guessing by automated password crackers.'
		);
	}

	protected function getExamples() {
		return array(
			'api.php?action=login&lgname=user&lgpassword=password'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
