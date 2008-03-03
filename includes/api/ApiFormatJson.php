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

class ApiFormatJson extends ApiFormatBase {

	private $mIsRaw;

	public function __construct($main, $format) {
		parent :: __construct($main, $format);
		$this->mIsRaw = ($format === 'rawfm');
	}

	public function getMimeType() {
		return 'application/json';
	}

	public function getNeedsRawData() {
		return $this->mIsRaw;
	}

	public function execute() {
		if (!function_exists('json_encode') || $this->getIsHtml()) {
			$json = new Services_JSON();
			$this->printText($json->encode($this->getResultData(), $this->getIsHtml()));
		} else {
			$this->printText(json_encode($this->getResultData()));
		}
	}

	protected function getDescription() {
		if ($this->mIsRaw)
			return 'Output data with the debuging elements in JSON format' . parent :: getDescription();
		else
			return 'Output data in JSON format' . parent :: getDescription();
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>