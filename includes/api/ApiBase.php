<?php


/*
 * Created on Sep 5, 2006
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

// Multi-valued enums, limit the values user can supply for the parameter
define('GN_ENUM_DFLT', 0);
define('GN_ENUM_ISMULTI', 1);
define('GN_ENUM_CHOICES', 2);

abstract class ApiBase {

	private $mMainModule;

	/**
	* Constructor
	*/
	public function __construct($mainModule) {
		$this->mMainModule = $mainModule;
	}

	/**
	 * Executes this module
	 */
	abstract function Execute();

	/**
	 * Get main module
	 */
	public function GetMain() {
		return $this->mMainModule;
	}

	/**
	 * If this module's $this is the same as $this->mMainModule, its the root, otherwise no
	 */
	public function IsMain() {
		return $this === $this->mMainModule;
	}

	/**
	 * Get result object
	 */
	public function GetResult() {
		// Main module has GetResult() method overriden
		// Safety - avoid infinite loop:
		if ($this->IsMain())
			$this->DieDebug(__METHOD__ .
			' base method was called on main module. ');
		return $this->GetMain()->GetResult();
	}

	/**
	 * Generates help message for this module, or false if there is no description
	 */
	public function MakeHelpMsg() {

		$msg = $this->GetDescription();

		if ($msg !== false) {

			if (is_array($msg))
				$msg = implode("\n", $msg);
			$msg .= "\n";

			// Parameters
			$params = $this->GetAllowedParams();
			if ($params !== false) {
				$paramsDescription = $this->GetParamDescription();
				$msg .= "Supported Parameters:\n";
				foreach (array_keys($params) as $paramName) {
					$desc = isset ($paramsDescription[$paramName]) ? $paramsDescription[$paramName] : '';
					$msg .= sprintf("  %-14s - %s\n", $paramName, $desc);
				}
			}

			// Examples
			$examples = $this->GetExamples();
			if ($examples !== false) {
				if (is_array($examples)) {
					$msg .= "Examples:\n";
					$msg .= implode("\n  ", $examples) . "\n";
				} else {
					$msg .= "Example:   $examples\n";
				}
			}
		}

		return $msg;
	}

	/**
	 * Returns the description string for this module
	 */
	protected function GetDescription() {
		return false;
	}

	/**
	 * Returns usage examples for this module. Return null if no examples are available.
	 */
	protected function GetExamples() {
		return false;
	}

	/**
	 * Returns an array of allowed parameters (keys) => default value for that parameter
	 */
	protected function GetAllowedParams() {
		return false;
	}

	/**
	 * Returns the description string for the given parameter.
	 */
	protected function GetParamDescription() {
		return false;
	}

	/**
	* Using GetAllowedParams(), makes an array of the values provided by the user,
	* with key being the name of the variable, and value - validated value from user or default.
	* This method can be used to generate local variables using extract().
	*/
	public function ExtractRequestParams() {
		global $wgRequest;

		$params = $this->GetAllowedParams();
		$results = array ();

		foreach ($params as $param => $dflt) {
			switch (gettype($dflt)) {
				case 'NULL' :
				case 'string' :
					$result = $wgRequest->getVal($param, $dflt);
					break;
				case 'integer' :
					$result = $wgRequest->getInt($param, $dflt);
					break;
				case 'boolean' :
					// Having a default value of 'true' is pointless
					$result = $wgRequest->getCheck($param);
					break;
				case 'array' :
					$enumParams = $dflt;
					$dflt = isset ($enumParams[GN_ENUM_DFLT]) ? $enumParams[GN_ENUM_DFLT] : null;
					$multi = isset ($enumParams[GN_ENUM_ISMULTI]) ? $enumParams[GN_ENUM_ISMULTI] : false;
					$choices = isset ($enumParams[GN_ENUM_CHOICES]) ? $enumParams[GN_ENUM_CHOICES] : null;
					$value = $wgRequest->getVal($param, $dflt);
					$result = $this->ParseMultiValue($param, $value, $multi, $choices);
					break;
				default :
					$this->DieDebug("In '$param', unprocessed type " . gettype($dflt));
			}
			$results[$param] = $result;
		}

		return $results;
	}

	/**
	* Return an array of values that were given in a "a|b|c" notation,
	* after it optionally validates them against the list allowed values.
	* 
	* @param valueName - The name of the parameter (for error reporting)
	* @param value - The value being parsed
	* @param allowMultiple - Can $value contain more than one value separated by '|'?
	* @param allowedValues - An array of values to check against. If null, all values are accepted.
	* @return (allowMultiple ? an_array_of_values : a_single_value) 
	*/
	protected function ParseMultiValue($valueName, $value, $allowMultiple, $allowedValues) {
		$valuesList = explode('|', $value);
		if (!$allowMultiple && count($valuesList) != 1) {
			$possibleValues = is_array($allowedValues) ? "of '" . implode("', '", $allowedValues) . "'" : '';
			$this->DieUsage("Only one $possibleValues is allowed for parameter '$valueName'", "multival_$valueName");
		}
		if (is_array($allowedValues)) {
			$unknownValues = array_diff($valuesList, $allowedValues);
			if ($unknownValues) {
				$this->DieUsage("Unrecognised value" . (count($unknownValues) > 1 ? "s '" : " '") . implode("', '", $unknownValues) . "' for parameter '$valueName'", "unknown_$valueName");
			}
		}

		return $allowMultiple ? $valuesList : $valuesList[0];
	}

	/**
	 * Call main module's error handler 
	 */
	public function DieUsage($description, $errorCode, $httpRespCode = 0) {
		$this->GetMain()->MainDieUsage($description, $errorCode, $httpRespCode);
	}

	protected function DieDebug($message) {
		wfDebugDieBacktrace("Internal error in '{get_class($this)}': $message");
	}
}
?>