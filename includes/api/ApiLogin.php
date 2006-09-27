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
	require_once ("ApiBase.php");
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
			case (AuthSuccess) :
				$result['result'] = 'Success';
				$result['lguserid'] = $_SESSION['wsUserID'];
				$result['lgusername'] = $_SESSION['wsUserName'];
				$result['lgtoken'] = $_SESSION['wsToken'];
				break;

			case (AuthNoName) :
				$result['result'] = 'AuthNoName';
				break;
			case (AuthIllegal) :
				$result['result'] = 'AuthIllegal';
				break;
			case (AuthWrongPluginPass) :
				$result['result'] = 'AuthWrongPluginPass';
				break;
			case (AuthNotExists) :
				$result['result'] = 'AuthNotExists';
				break;
			case (AuthWrongPass) :
				$result['result'] = 'AuthWrongPass';
				break;
			case (AuthEmptyPass) :
				$result['result'] = 'AuthEmptyPass';
				break;
			default :
				$this->dieDebug("Unhandled case value");
		}

		$this->getResult()->addMessage('login', null, $result);
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