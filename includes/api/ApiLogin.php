<?php


/*
 * Created on Sep 19, 2006
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2006 Yuri Astrakhan <FirstnameLastname@gmail.com>
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

class ApiLogin extends ApiBase {

	public function __construct($main, $action) {
		parent :: __construct($main);
	}

	public function execute() {
		$lgname = $lgpassword = $lgdomain = null;
		extract($this->extractRequestParams());

		$params = new FauxRequest(array (
			'wpName' => $lgname,
			'wpPassword' => $lgpassword,
			'wpDomain' => $lgdomain,
			'wpRemember' => ''
		));

		$result = array ();

		$loginForm = new LoginForm($params);
		switch ($loginForm->authenticateUserData()) {
			case LoginForm :: SUCCESS :
				global $wgUser;

				$wgUser->setOption('rememberpassword', 1);
				$wgUser->setCookies();

				$result['result'] = 'Success';
				$result['lguserid'] = $_SESSION['wsUserID'];
				$result['lgusername'] = $_SESSION['wsUserName'];
				$result['lgtoken'] = $_SESSION['wsToken'];
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
			default :
				ApiBase :: dieDebug(__METHOD__, 'Unhandled case value');
		}

		$this->getResult()->addValue(null, 'login', $result);
	}

	protected function getAllowedParams() {
		return array (
			'lgname' => '',
			'lgpassword' => '',
			'lgdomain' => null
		);
	}

	protected function getParamDescription() {
		return array (
			'lgname' => 'User Name',
			'lgpassword' => 'Password',
			'lgdomain' => 'Domain (optional)'
		);
	}

	protected function getDescription() {
		return array (
			'This module is used to login and get the authentication tokens.'
		);
	}
}
?>
