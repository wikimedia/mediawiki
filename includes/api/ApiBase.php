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
define('GN_ENUM_DFLT', 'dflt');
define('GN_ENUM_ISMULTI', 'multi');
define('GN_ENUM_TYPE', 'type');
define('GN_ENUM_MAX1', 'max1');
define('GN_ENUM_MAX2', 'max2');
define('GN_ENUM_MIN', 'min');

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

		static $lnPrfx = "\n  ";

		$msg = $this->GetDescription();

		if ($msg !== false) {

			if (!is_array($msg))
				$msg = array (
					$msg
				);
			$msg = $lnPrfx . implode($lnPrfx, $msg) . "\n";

			// Parameters
			$params = $this->GetAllowedParams();
			if ($params !== false) {
				$paramsDescription = $this->GetParamDescription();
				$msg .= "Parameters:\n";
				foreach (array_keys($params) as $paramName) {
					$desc = isset ($paramsDescription[$paramName]) ? $paramsDescription[$paramName] : '';
					if (is_array($desc))
						$desc = implode("\n" . str_repeat(' ', 19), $desc);
					$msg .= sprintf("  %-14s - %s\n", $paramName, $desc);
				}
			}

			// Examples
			$examples = $this->GetExamples();
			if ($examples !== false) {
				if (!is_array($examples))
					$examples = array (
						$examples
					);
				$msg .= 'Example' . (count($examples) > 1 ? 's' : '') . ":\n  ";
				$msg .= implode($lnPrfx, $examples) . "\n";
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

		foreach ($params as $param => $enumParams) {

			if (!is_array($enumParams)) {
				$default = $enumParams;
				$multi = false;
				$type = gettype($enumParams);
			} else {
				$default = isset ($enumParams[GN_ENUM_DFLT]) ? $enumParams[GN_ENUM_DFLT] : null;
				$multi = isset ($enumParams[GN_ENUM_ISMULTI]) ? $enumParams[GN_ENUM_ISMULTI] : false;
				$type = isset ($enumParams[GN_ENUM_TYPE]) ? $enumParams[GN_ENUM_TYPE] : null;

				// When type is not given, and no choices, the type is the same as $default
				if (!isset ($type)) {
					if (isset ($default))
						$type = gettype($default);
					else
						$type = 'NULL'; // allow everything
				}
			}

			if ($type == 'boolean') {
				if (!isset ($default))
					$default = false;

				if ($default !== false) {
					// Having a default value of anything other than 'false' is pointless
					$this->DieDebug("Boolean param $param's default is set to '$default'");
				}
			}

			$value = $wgRequest->getVal($param, $default);

			if (isset ($value) && ($multi || is_array($type)))
				$value = $this->ParseMultiValue($param, $value, $multi, is_array($type) ? $type : null);

			// More validation only when choices were not given
			// choices were validated in ParseMultiValue()
			if (!is_array ($type) && isset ($value)) {

				switch ($type) {
					case 'NULL' : // nothing to do
						break;
					case 'string' : // nothing to do
						break;
					case 'integer' : // Force everything using intval()
						$value = is_array($value) ? array_map('intval', $value) : intval($value);
						break;
					case 'limit':
						if (!isset ($enumParams[GN_ENUM_MAX1]) || !isset($enumParams[GN_ENUM_MAX2]))
							$this->DieDebug("MAX1 or MAX2 are not defined for the limit $param");
						if ($multi)
							$this->DieDebug("Multi-values not supported for $param");
						$min = isset($enumParams[GN_ENUM_MIN]) ? $enumParams[GN_ENUM_MIN] : 0;
						$value = intval($value);
						$this->ValidateLimit($param, $value, $min, $enumParams[GN_ENUM_MAX1], $enumParams[GN_ENUM_MAX2]);							
						break;
					case 'boolean' :
						if ($multi)
							$this->DieDebug("Multi-values not supported for $param");
						$value = isset ($value);
						break;
					case 'timestamp' :
						if ($multi)
							$this->DieDebug("Multi-values not supported for $param");
						$value = $this->prepareTimestamp($value); // Adds quotes around timestamp							
						break;
					default :
						$this->DieDebug("Param $param's type is unknown - $type");
				
				}
			}

			$results[$param] = $value;
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
	* Validate the proper format of the timestamp string (14 digits), and add quotes to it.
	*/
	function prepareTimestamp($value) {
		if (preg_match('/^[0-9]{14}$/', $value)) {
			return $this->db->addQuotes($value);
		} else {
			$this->dieUsage('Incorrect timestamp format', 'badtimestamp');
		}
	}

	/**
	* Validate the value against the minimum and user/bot maximum limits. Prints usage info on failure.
	*/
	function ValidateLimit( $varname, $value, $min, $max, $botMax )
	{
		global $wgUser;
		
		if ( $value < $min ) {
			$this->dieUsage( "$varname may not be less than $min (set to $value)", $varname );
		}
		
		if( $this->GetMain()->IsBot() ) {
			if ( $value > $botMax ) {
				$this->dieUsage( "$varname may not be over $botMax (set to $value) for bots", $varname );
			}
		} else {
			if( $value > $max ) {
				$this->dieUsage( "$varname may not be over $max (set to $value) for users", $varname );
			}
		}
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