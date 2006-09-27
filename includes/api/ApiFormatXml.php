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
	require_once ("ApiFormatBase.php");
}

class ApiFormatXml extends ApiFormatBase {

	public function __construct($main, $format) {
		parent :: __construct($main, $format);
	}

	public function getMimeType() {
		return 'text/xml';
	}

	public function getNeedsRawData() {
		return true;
	}

	public function execute() {
		$xmlindent = null;
		extract($this->extractRequestParams());

		if ($xmlindent || $this->getIsHtml())
			$xmlindent = -2;
		else
			$xmlindent = null;

		$this->printText('<?xml version="1.0" encoding="utf-8"?>');
		$this->recXmlPrint('api', $this->getResult()->getData(), $xmlindent);
	}

	/**
	* This method takes an array and converts it into an xml.
	* There are several noteworthy cases:
	*
	*  If array contains a key "_element", then the code assumes that ALL other keys are not important and replaces them with the value['_element'].
	*	Example:	name="root",  value = array( "_element"=>"page", "x", "y", "z") creates <root>  <page>x</page>  <page>y</page>  <page>z</page> </root>
	*
	*  If any of the array's element key is "*", then the code treats all other key->value pairs as attributes, and the value['*'] as the element's content.
	*	Example:	name="root",  value = array( "*"=>"text", "lang"=>"en", "id"=>10)   creates  <root lang="en" id="10">text</root>
	*
	* If neither key is found, all keys become element names, and values become element content.
	* The method is recursive, so the same rules apply to any sub-arrays.
	*/
	function recXmlPrint($elemName, $elemValue, $indent) {
		$indstr = "";
		if (!is_null($indent)) {
			$indent += 2;
			$indstr = "\n" . str_repeat(" ", $indent);
		}

		switch (gettype($elemValue)) {
			case 'array' :
				if (array_key_exists('*', $elemValue)) {
					$subElemContent = $elemValue['*'];
					unset ($elemValue['*']);
					if (gettype($subElemContent) === 'array') {
						$this->printText($indstr . wfElement($elemName, $elemValue, null));
						$this->recXmlPrint($elemName, $subElemContent, $indent);
						$this->printText($indstr . "</$elemName>");
					} else {
						$this->printText($indstr . wfElement($elemName, $elemValue, $subElemContent));
					}
				} else {
					$this->printText($indstr . wfElement($elemName, null, null));
					if (array_key_exists('_element', $elemValue)) {
						$subElemName = $elemValue['_element'];
						foreach ($elemValue as $subElemId => & $subElemValue) {
							if ($subElemId !== '_element') {
								$this->recXmlPrint($subElemName, $subElemValue, $indent);
							}
						}
					} else {
						foreach ($elemValue as $subElemName => & $subElemValue) {
							$this->recXmlPrint($subElemName, $subElemValue, $indent);
						}
					}
					$this->printText($indstr . "</$elemName>");
				}
				break;
			case 'object' :
				// ignore
				break;
			default :
				$this->printText($indstr . wfElement($elemName, null, $elemValue));
				break;
		}
	}
	protected function getDescription() {
		return 'Output data in XML format';
	}

	protected function getAllowedParams() {
		return array (
			'xmlindent' => false
		);
	}

	protected function getParamDescription() {
		return array (
			'xmlindent' => 'Enable XML indentation'
		);
	}
}
?>