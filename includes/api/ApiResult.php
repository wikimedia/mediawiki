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
		$this->mData = array();
	}

	function GetData() {
		return $this->mData;
	}

	/*	function addPage($title)
		{
			if (!isset($this->mPages))
				$this->mPages &= $this->mData['pages'];
		}
	*/
	
	function AddMessage($mainSection, $subSection, $value, $preserveXmlSpacing = false) {
		if (!array_key_exists($mainSection, $this->mData)) {
			$this->mData[$mainSection] = array ();
		}
		if ($subSection !== null) {
			if (!array_key_exists($subSection, $this->mData[$mainSection])) {
				$this->mData[$mainSection][$subSection] = array ();
			}
			$element = & $this->mData[$mainSection][$subSection];
		} else {
			$element = & $this->mData[$mainSection];
		}
		if (is_array($value)) {
			$element = array_merge($element, $value);
			if (!array_key_exists('*', $element)) {
				$element['*'] = '';
			}
		} else {
			if (array_key_exists('*', $element)) {
				$element['*'] .= $value;
			} else {
				$element['*'] = $value;
			}
			if ($preserveXmlSpacing) {
				$element['xml:space'] = 'preserve';
			}
		}
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
	
	public function Execute() {
		$this->DieDebug("Execute() is not supported on Result object");
	}
}
?>
