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
	require_once ('ApiBase.php');
}

class ApiMain extends ApiBase {

	private $mPrinter, $mModules, $mModuleNames, $mFormats, $mFormatNames;
	private $mApiStartTime, $mResult, $mShowVersions, $mEnableWrite;

	/**
	* Constructor
	* $apiStartTime - time of the originating call for profiling purposes
	* $modules - an array of actions (keys) and classes that handle them (values) 
	*/
	public function __construct($apiStartTime, $modules, $formats, $enableWrite) {
		// Special handling for the main module: $parent === $this
		parent :: __construct($this, 'main');

		$this->mModules = $modules;
		$this->mModuleNames = array_keys($modules);
		$this->mFormats = $formats;
		$this->mFormatNames = array_keys($formats);
		$this->mApiStartTime = $apiStartTime;
		$this->mResult = new ApiResult($this);
		$this->mShowVersions = false;
		$this->mEnableWrite = $enableWrite;
	}

	public function & getResult() {
		return $this->mResult;
	}

	public function getShowVersions() {
		return $this->mShowVersions;
	}

	public function requestWriteMode() {
		if (!$this->mEnableWrite)
			$this->dieUsage('Editing of this site is disabled. Make sure the $wgEnableWriteAPI=true; ' .
			'statement is included in the site\'s LocalSettings.php file', 'readonly');
	}

	protected function getAllowedParams() {
		return array (
			'format' => array (
				ApiBase :: PARAM_DFLT => API_DEFAULT_FORMAT,
				ApiBase :: PARAM_TYPE => $this->mFormatNames
			),
			'action' => array (
				ApiBase :: PARAM_DFLT => 'help',
				ApiBase :: PARAM_TYPE => $this->mModuleNames
			),
			'version' => false
		);
	}

	protected function getParamDescription() {
		return array (
			'format' => 'The format of the output',
			'action' => 'What action you would like to perform',
			'version' => 'When showing help, include version for each module'
		);
	}

	public function execute() {
		$this->profileIn();
		$action = $format = $version = null;
		try {
			extract($this->extractRequestParams());
			$this->mShowVersions = $version;

			// Create an appropriate printer
			$this->mPrinter = new $this->mFormats[$format] ($this, $format);

			// Instantiate and execute module requested by the user
			$module = new $this->mModules[$action] ($this, $action);
			$module->profileIn();
			$module->execute();
			$module->profileOut();
			$this->printResult(false);

		} catch (UsageException $e) {

			// Printer may not be initialized if the extractRequestParams() fails for the main module
			if (!isset ($this->mPrinter))
				$this->mPrinter = new $this->mFormats[API_DEFAULT_FORMAT] ($this, API_DEFAULT_FORMAT);
			$this->printResult(true);

		}
		$this->profileOut();
	}

	/**
	 * Internal printer
	 */
	private function printResult($isError) {
		$printer = $this->mPrinter;
		$printer->profileIn();
		$printer->initPrinter($isError);
		if (!$printer->getNeedsRawData())
			$this->getResult()->SanitizeData();
		$printer->execute();
		$printer->closePrinter();
		$printer->profileOut();
	}

	protected function getDescription() {
		return array (
			'',
			'This API allows programs to access various functions of MediaWiki software.',
			'For more details see API Home Page @ http://meta.wikimedia.org/wiki/API',
			''
		);
	}

	public function mainDieUsage($description, $errorCode, $httpRespCode = 0) {
		$this->mResult->Reset();
		if ($httpRespCode === 0)
			header($errorCode, true);
		else
			header($errorCode, true, $httpRespCode);

		$data = array (
			'code' => $errorCode,
			'info' => $description
		);
		ApiResult :: setContent($data, $this->makeHelpMsg());
		$this->mResult->addValue(null, 'error', $data);

		throw new UsageException($description, $errorCode);
	}

	/**
	 * Override the parent to generate help messages for all available modules.
	 */
	public function makeHelpMsg() {

		// Use parent to make default message for the main module
		$msg = parent :: makeHelpMsg();

		$astriks = str_repeat('*** ', 10);
		$msg .= "\n\n$astriks Modules  $astriks\n\n";
		foreach ($this->mModules as $moduleName => $moduleClass) {
			$msg .= "* action=$moduleName *";
			$module = new $this->mModules[$moduleName] ($this, $moduleName);
			$msg2 = $module->makeHelpMsg();
			if ($msg2 !== false)
				$msg .= $msg2;
			$msg .= "\n";
		}

		$msg .= "\n$astriks Formats  $astriks\n\n";
		foreach ($this->mFormats as $moduleName => $moduleClass) {
			$msg .= "* format=$moduleName *";
			$module = new $this->mFormats[$moduleName] ($this, $moduleName);
			$msg2 = $module->makeHelpMsg();
			if ($msg2 !== false)
				$msg .= $msg2;
			$msg .= "\n";
		}

		return $msg;
	}

	private $mIsBot = null;
	public function isBot() {
		if (!isset ($this->mIsBot)) {
			global $wgUser;
			$this->mIsBot = $wgUser->isAllowed('bot');
		}
		return $this->mIsBot;
	}

	public function getVersion() {
		$vers = array ();
		$vers[] = __CLASS__ . ': $Id$';
		$vers[] = ApiBase :: getBaseVersion();
		$vers[] = ApiFormatBase :: getBaseVersion();
		$vers[] = ApiQueryBase :: getBaseVersion();
		return $vers;
	}
}

/**
* @desc This exception will be thrown when dieUsage is called to stop module execution.
*/
class UsageException extends Exception {

	private $codestr;

	public function __construct($message, $codestr) {
		parent :: __construct($message);
		$this->codestr = $codestr;
	}
	public function __toString() {
		return "{$this->codestr}: {$this->message}";
	}
}
?>