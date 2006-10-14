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
	require_once ('ApiFormatBase.php');
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

	public function executePrinter() {
		$xmlindent = null;
		extract($this->extractRequestParams());

		if ($xmlindent || $this->getIsHtml())
			$xmlindent = -2;
		else
			$xmlindent = null;

		$this->printText('<?xml version="1.0" encoding="utf-8"?>');
		$this->recXmlPrint('api', $this->getResultData(), $xmlindent);
	}

	/**
	* This method takes an array and converts it into an xml.
	* There are several noteworthy cases:
	*
	*  If array contains a key '_element', then the code assumes that ALL other keys are not important and replaces them with the value['_element'].
	*	Example:	name='root',  value = array( '_element'=>'page', 'x', 'y', 'z') creates <root>  <page>x</page>  <page>y</page>  <page>z</page> </root>
	*
	*  If any of the array's element key is '*', then the code treats all other key->value pairs as attributes, and the value['*'] as the element's content.
	*	Example:	name='root',  value = array( '*'=>'text', 'lang'=>'en', 'id'=>10)   creates  <root lang='en' id='10'>text</root>
	*
	* If neither key is found, all keys become element names, and values become element content.
	* The method is recursive, so the same rules apply to any sub-arrays.
	*/
	function recXmlPrint($elemName, $elemValue, $indent) {
		if (!is_null($indent)) {
			$indent += 2;
			$indstr = "\n" . str_repeat(" ", $indent);
		} else {
			$indstr = '';
		}

		switch (gettype($elemValue)) {
			case 'array' :

				if (isset ($elemValue['*'])) {
					$subElemContent = $elemValue['*'];
					unset ($elemValue['*']);
				} else {
					$subElemContent = null;
				}

				if (isset ($elemValue['_element'])) {
					$subElemIndName = $elemValue['_element'];
					unset ($elemValue['_element']);
				} else {
					$subElemIndName = null;
				}

				$indElements = array ();
				$subElements = array ();
				foreach ($elemValue as $subElemId => & $subElemValue) {
					if (gettype($subElemId) === 'integer') {
						if (!is_array($subElemValue))
							ApiBase :: dieDebug(__METHOD__, "($elemName, ...) has a scalar indexed value.");
						$indElements[] = $subElemValue;
						unset ($elemValue[$subElemId]);
					} elseif (is_array($subElemValue)) {
						$subElements[$subElemId] = $subElemValue;
						unset ($elemValue[$subElemId]);
					}
				}

				if (is_null($subElemIndName) && !empty ($indElements))
					ApiBase :: dieDebug(__METHOD__, "($elemName, ...) has integer keys without _element value");

				if (!empty ($subElements) && !empty ($indElements) && !is_null($subElemContent))
					ApiBase :: dieDebug(__METHOD__, "($elemName, ...) has content and subelements");

				if (!is_null($subElemContent)) {
					$this->printText($indstr . wfElement($elemName, $elemValue, $subElemContent));
				} elseif (empty ($indElements) && empty ($subElements)) {
						$this->printText($indstr . wfElement($elemName, $elemValue));
				} else {
					$this->printText($indstr . wfElement($elemName, $elemValue, null));

					foreach ($subElements as $subElemId => & $subElemValue)
						$this->recXmlPrint($subElemId, $subElemValue, $indent);

					foreach ($indElements as $subElemId => & $subElemValue)
						$this->recXmlPrint($subElemIndName, $subElemValue, $indent);

					$this->printText($indstr . wfCloseElement($elemName));
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

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>