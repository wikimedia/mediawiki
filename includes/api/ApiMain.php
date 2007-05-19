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

/**
 * This is the main API class, used for both external and internal processing.
 * When executed, it will create the requested formatter object,
 * instantiate and execute an object associated with the needed action,
 * and use formatter to print results.
 * In case of an exception, an error message will be printed using the same formatter.
 *
 * To use API from another application, run it using FauxRequest object, in which
 * case any internal exceptions will not be handled but passed up to the caller.
 * After successful execution, use getResult() for the resulting data.   
 *  
 * @addtogroup API
 */
class ApiMain extends ApiBase {

	/**
	 * When no format parameter is given, this format will be used
	 */
	const API_DEFAULT_FORMAT = 'xmlfm';

	/**
	 * List of available modules: action name => module class
	 */
	private static $Modules = array (
//		'login' => 'ApiLogin',		// LOGIN is temporarily disabled until it becomes more secure
		'query' => 'ApiQuery',
		'opensearch' => 'ApiOpenSearch',
		'feedwatchlist' => 'ApiFeedWatchlist',
		'help' => 'ApiHelp',
	);

	/**
	 * List of available formats: format name => format class
	 */
	private static $Formats = array (
		'json' => 'ApiFormatJson',
		'jsonfm' => 'ApiFormatJson',
		'php' => 'ApiFormatPhp',
		'phpfm' => 'ApiFormatPhp',
		'wddx' => 'ApiFormatWddx',
		'wddxfm' => 'ApiFormatWddx',
		'xml' => 'ApiFormatXml',
		'xmlfm' => 'ApiFormatXml',
		'yaml' => 'ApiFormatYaml',
		'yamlfm' => 'ApiFormatYaml',
		'rawfm' => 'ApiFormatJson'
	);

	private $mPrinter, $mModules, $mModuleNames, $mFormats, $mFormatNames;
	private $mResult, $mShowVersions, $mEnableWrite, $mRequest, $mInternalMode, $mSquidMaxage;

	/**
	* Constructor
	* @param $request object - if this is an instance of FauxRequest, errors are thrown and no printing occurs
	* @param $enableWrite bool should be set to true if the api may modify data
	*/
	public function __construct($request, $enableWrite = false) {

		$this->mInternalMode = ($request instanceof FauxRequest);

		// Special handling for the main module: $parent === $this
		parent :: __construct($this, $this->mInternalMode ? 'main_int' : 'main');

		$this->mModules = self :: $Modules;
		$this->mModuleNames = array_keys($this->mModules); // todo: optimize
		$this->mFormats = self :: $Formats;
		$this->mFormatNames = array_keys($this->mFormats); // todo: optimize

		$this->mResult = new ApiResult($this);
		$this->mShowVersions = false;
		$this->mEnableWrite = $enableWrite;

		$this->mRequest = & $request;

		$this->mSquidMaxage = 0;
	}

	public function & getRequest() {
		return $this->mRequest;
	}

	public function getResult() {
		return $this->mResult;
	}

	public function requestWriteMode() {
		if (!$this->mEnableWrite)
			$this->dieUsage('Editing of this site is disabled. Make sure the $wgEnableWriteAPI=true; ' .
			'statement is included in the site\'s LocalSettings.php file', 'readonly');
	}

	public function setCacheMaxAge($maxage) {
		$this->mSquidMaxage = $maxage;
	}

	public function createPrinterByName($format) {
		return new $this->mFormats[$format] ($this, $format);
	}

	public function execute() {
		$this->profileIn();
		if ($this->mInternalMode)
			$this->executeAction();
		else
			$this->executeActionWithErrorHandling();
		$this->profileOut();
	}

	protected function executeActionWithErrorHandling() {

		// In case an error occurs during data output,
		// this clear the output buffer and print just the error information
		ob_start();

		try {
			$this->executeAction();
		} catch (Exception $e) {
			//
			// Handle any kind of exception by outputing properly formatted error message.
			// If this fails, an unhandled exception should be thrown so that global error
			// handler will process and log it.
			//

			// Error results should not be cached
			$this->setCacheMaxAge(0);

			// Printer may not be initialized if the extractRequestParams() fails for the main module
			if (!isset ($this->mPrinter)) {
				$this->mPrinter = $this->createPrinterByName(self :: API_DEFAULT_FORMAT);
				if ($this->mPrinter->getNeedsRawData())
					$this->getResult()->setRawMode();
			}

			if ($e instanceof UsageException) {
				//
				// User entered incorrect parameters - print usage screen
				//
				$errMessage = array (
				'code' => $e->getCodeString(), 'info' => $e->getMessage());
				ApiResult :: setContent($errMessage, $this->makeHelpMsg());

			} else {
				//
				// Something is seriously wrong
				//
				$errMessage = array (
					'code' => 'internal_api_error',
					'info' => "Exception Caught: {$e->getMessage()}"
				);
				ApiResult :: setContent($errMessage, "\n\n{$e->getTraceAsString()}\n\n");
			}

			$headerStr = 'MediaWiki-API-Error: ' . $errMessage['code'];
			if ($e->getCode() === 0)
				header($headerStr, true);
			else
				header($headerStr, true, $e->getCode());

			// Reset and print just the error message
			ob_clean();
			$this->getResult()->reset();
			$this->getResult()->addValue(null, 'error', $errMessage);

			// If the error occured during printing, do a printer->profileOut()
			$this->mPrinter->safeProfileOut();
			$this->printResult(true);
		}

		// Set the cache expiration at the last moment, as any errors may change the expiration.
		// if $this->mSquidMaxage == 0, the expiry time is set to the first second of unix epoch
		$expires = $this->mSquidMaxage == 0 ? 1 : time() + $this->mSquidMaxage;
		header('Expires: ' . wfTimestamp(TS_RFC2822, $expires));
		header('Cache-Control: s-maxage=' . $this->mSquidMaxage . ', must-revalidate, max-age=0');

		if($this->mPrinter->getIsHtml()) {
			echo wfReportTime();
		}

		ob_end_flush();
	}

	/**
	 * Execute the actual module, without any error handling
	 */
	protected function executeAction() {
		$action = $format = $version = null;
		extract($this->extractRequestParams());
		$this->mShowVersions = $version;

		// Instantiate the module requested by the user
		$module = new $this->mModules[$action] ($this, $action);

		if (!$this->mInternalMode) {

			// See if custom printer is used
			$this->mPrinter = $module->getCustomPrinter();
			if (is_null($this->mPrinter)) {
				// Create an appropriate printer
				$this->mPrinter = $this->createPrinterByName($format);
			}

			if ($this->mPrinter->getNeedsRawData())
				$this->getResult()->setRawMode();
		}

		// Execute
		$module->profileIn();
		$module->execute();
		$module->profileOut();

		if (!$this->mInternalMode) {
			// Print result data
			$this->printResult(false);
		}
	}

	/**
	 * Internal printer
	 */
	protected function printResult($isError) {
		$printer = $this->mPrinter;
		$printer->profileIn();
		$printer->initPrinter($isError);
		$printer->execute();
		$printer->closePrinter();
		$printer->profileOut();
	}

	protected function getAllowedParams() {
		return array (
			'format' => array (
				ApiBase :: PARAM_DFLT => ApiMain :: API_DEFAULT_FORMAT,
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

	protected function getDescription() {
		return array (
			'',
			'This API allows programs to access various functions of MediaWiki software.',
			'For more details see API Home Page @ http://www.mediawiki.org/wiki/API',
			'',
			'Status: ALPHA -- all features shown on this page should be working,',
			'                 but the API is still in active development, and  may change at any time.',
			'                 Make sure you monitor changes to this page, wikitech-l mailing list,',
			'                 or the source code in the includes/api directory for any changes.',
			''
		);
	}
	
	protected function getCredits() {
		return array(
			'This API is being implemented by Yuri Astrakhan [[User:Yurik]] / FirstnameLastname@gmail.com',
			'Please leave your comments and suggestions at http://meta.wikimedia.org/wiki/API'
		);
	}

	/**
	 * Override the parent to generate help messages for all available modules.
	 */
	public function makeHelpMsg() {

		// Use parent to make default message for the main module
		$msg = parent :: makeHelpMsg();

		$astriks = str_repeat('*** ', 10);
		$msg .= "\n\n$astriks Modules  $astriks\n\n";
		foreach( $this->mModules as $moduleName => $unused ) {
			$msg .= "* action=$moduleName *";
			$module = new $this->mModules[$moduleName] ($this, $moduleName);
			$msg2 = $module->makeHelpMsg();
			if ($msg2 !== false)
				$msg .= $msg2;
			$msg .= "\n";
		}

		$msg .= "\n$astriks Formats  $astriks\n\n";
		foreach( $this->mFormats as $formatName => $unused ) {
			$msg .= "* format=$formatName *";
			$module = $this->createPrinterByName($formatName);
			$msg2 = $module->makeHelpMsg();
			if ($msg2 !== false)
				$msg .= $msg2;
			$msg .= "\n";
		}
		
		$msg .= "\n*** Credits: ***\n   " . implode("\n   ", $this->getCredits()) . "\n";
		

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

	public function getShowVersions() {
		return $this->mShowVersions;
	}

	public function getVersion() {
		$vers = array ();
		$vers[] = __CLASS__ . ': $Id$';
		$vers[] = ApiBase :: getBaseVersion();
		$vers[] = ApiFormatBase :: getBaseVersion();
		$vers[] = ApiQueryBase :: getBaseVersion();
		$vers[] = ApiFormatFeedWrapper :: getVersion(); // not accessible with format=xxx
		return $vers;
	}
}

/**
 * This exception will be thrown when dieUsage is called to stop module execution.
 * @addtogroup API
 */
class UsageException extends Exception {

	private $mCodestr;

	public function __construct($message, $codestr, $code = 0) {
		parent :: __construct($message, $code);
		$this->mCodestr = $codestr;
	}
	public function getCodeString() {
		return $this->mCodestr;
	}
	public function __toString() {
		return "{$this->getCodeString()}: {$this->getMessage()}";
	}
}
?>
