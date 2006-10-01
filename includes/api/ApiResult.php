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

class ApiResult extends ApiBase {

	private $mData;

	/**
	* Constructor
	*/
	public function __construct($main) {
		parent :: __construct($main);
		$this->Reset();
	}

	public function Reset() {
		$this->mData = array ();
	}

	function & getData() {
		return $this->mData;
	}

	/**
	 * Add an output value to the array by name.
	 * Verifies that value with the same name has not been added before.
	 */
	public static function addElement(& $arr, $name, $value) {
		if ($arr === null || $name === null || $value === null || !is_array($arr) || is_array($name))
			ApiBase :: dieDebug('Bad parameter for ' . __FUNCTION__);
		if (isset ($arr[$name]))
			ApiBase :: dieDebug("Attempting to add element $name=$value, existing value is {$arr[$name]}");
		$arr[$name] = $value;
	}

	/**
	 * Adds the content element to the array.
	 * Use this function instead of hardcoding the '*' element.
	 */
	public static function addContent(& $arr, $value) {
		if (is_array($value))
			ApiBase :: dieDebug('Bad parameter for ' . __FUNCTION__);
		ApiResult :: addElement($arr, '*', $value);
	}

	//	public static function makeContentElement($tag, $value) {
	//		$result = array();
	//		ApiResult::addContent($result, )
	//	}
	//
	/**
	 * In case the array contains indexed values (in addition to named),
	 * all indexed values will have the given tag name.
	 */
	public static function setIndexedTagName(& $arr, $tag) {
		// Do not use addElement() as it is ok to call this more than once
		if ($arr === null || $tag === null || !is_array($arr) || is_array($tag))
			ApiBase :: dieDebug('Bad parameter for ' . __FUNCTION__);
		$arr['_element'] = $tag;
	}

	/**
	 * Add value to the output data at the given path.
	 * Path is an indexed array, each element specifing the branch at which to add the new value
	 * Setting $path to array('a','b','c') is equivalent to data['a']['b']['c'] = $value  
	 */
	public function addValue($path, $name, $value) {

		$data = & $this->getData();

		if (isset ($path)) {
			if (is_array($path)) {
				foreach ($path as $p) {
					if (!isset ($data[$p]))
						$data[$p] = array ();
					$data = & $data[$p];
				}
			} else {
				if (!isset ($data[$path]))
					$data[$path] = array ();
				$data = & $data[$path];
			}
		}

		ApiResult :: addElement($data, $name, $value);
	}

	/**
	* Recursivelly removes any elements from the array that begin with an '_'.
	* The content element '*' is the only special element that is left.
	* Use this method when the entire data object gets sent to the user.
	*/
	public function SanitizeData() {
		ApiResult :: SanitizeDataInt($this->mData);
	}

	private static function SanitizeDataInt(& $data) {
		foreach ($data as $key => & $value) {
			if ($key[0] === '_') {
				unset ($data[$key]);
			}
			elseif ($key === '*' && $value === '') {
				unset ($data[$key]);
			}
			elseif (is_array($value)) {
				ApiResult :: SanitizeDataInt($value);
			}
		}
	}

	public function execute() {
		$this->dieDebug('execute() is not supported on Result object');
	}
}
?>
