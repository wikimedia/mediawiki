<?php


/*
 * Created on Sep 4, 2006
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

class ApiMain extends ApiBase {

	private $mModules, $mModuleNames, $mApiStartTime, $mResult;

	/**
	* Constructor
	* $apiStartTime - time of the originating call for profiling purposes
	* $modules - an array of actions (keys) and classes that handle them (values) 
	*/
	public function __construct($apiStartTime, $modules) {
		// Special handling for the main module: $parent === $this
		parent :: __construct($this);

		$this->mModules = $modules;
		$this->mModuleNames = array_keys($modules);
		$this->mApiStartTime = $apiStartTime;
		$this->mResult = new ApiResult($this);
	}

	public function GetResult() {
		return $this->mResult;
	}

	protected function GetAllowedParams() {
		return array (
			'format' => 'xmlfm',
			'action' => array (
				GN_ENUM_DFLT => 'help',
				GN_ENUM_ISMULTI => false,
				GN_ENUM_CHOICES => $this->mModuleNames
			)
		);
	}

	public function Execute() {
		$action = $format = null;
		extract($this->ExtractRequestParams());

		// Instantiate and execute module requested by the user
		$module = new $this->mModules[$action] ($this, $action);
		$module->Execute();
	}

	protected function GetDescription() {
		return "This API allows programs to access various functions of MediaWiki software.";
	} 
	
	protected function GetParamDescription($paramName) {
		switch($paramName) {
			case 'format': return "The format of the output";
			case 'action': return "What action you would like to perform";
			default: return parent :: GetParamDescription($paramName);
		}
	}
	
	public function MainDieUsage($description, $errorCode, $httpRespCode = 0) {
		$this->mResult->Reset();
		$this->mResult->addMessage('error', null, $errorCode);
		if ($httpRespCode === 0)
			header($errorCode, true);
		else
			header($errorCode, true, $httpRespCode);

		$this->mResult->addMessage('usage', null, $this->MakeHelpMsg());
		 
		var_export($this->mResult->GetData());
	}
}
?>
