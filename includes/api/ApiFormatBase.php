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
	require_once ('ApiBase.php');
}

abstract class ApiFormatBase extends ApiBase {

	private $mIsHtml, $mFormat;

	/**
	* Constructor
	*/
	public function __construct($main, $format) {
		parent :: __construct($main, $format);

		$this->mIsHtml = (substr($format, -2, 2) === 'fm'); // ends with 'fm'
		if ($this->mIsHtml)
			$this->mFormat = substr($format, 0, -2); // remove ending 'fm'
		else
			$this->mFormat = $format;
		$this->mFormat = strtoupper($this->mFormat);
	}

	/**
	 * Overriding class returns the mime type that should be sent to the client.
	 * This method is not called if getIsHtml() returns true.
	 * @return string
	 */
	public abstract function getMimeType();

	public function getNeedsRawData() {
		return false;
	}

	/**
	 * Returns true when an HTML filtering printer should be used.
	 * The default implementation assumes that formats ending with 'fm' 
	 * should be formatted in HTML. 
	 */
	public function getIsHtml() {
		return $this->mIsHtml;
	}

	/**
	 * Initialize the printer function and prepares the output headers, etc.
	 * This method must be the first outputing method during execution.
	 * A help screen's header is printed for the HTML-based output
	 */
	function initPrinter($isError) {
		$isHtml = $this->getIsHtml();
		$mime = $isHtml ? 'text/html' : $this->getMimeType();

		// Some printers (ex. Feed) do their own header settings,
		// in which case $mime will be set to null
		if (is_null($mime))
			return; // skip any initialization

		header("Content-Type: $mime; charset=utf-8;");

		if ($isHtml) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>MediaWiki API</title>
</head>
<body>
<?php


			if (!$isError) {
?>
<br/>
<small>
You are looking at the HTML representation of the <?=$this->mFormat?> format.<br/>
HTML is good for debugging, but probably is not suitable for your application.<br/>
Please see "format" parameter documentation at the <a href='api.php'>API help</a>
for more information.
</small>
<?php


			}
?>
<pre>
<?php


		}
	}

	/**
	 * Finish printing. Closes HTML tags.
	 */
	public function closePrinter() {
		if ($this->getIsHtml()) {
?>

</pre>
</body>
</html>
<?php


		}
	}

	public function printText($text) {
		if ($this->getIsHtml())
			echo $this->formatHTML($text);
		else
			echo $text;
	}

	/**
	* Prety-print various elements in HTML format, such as xml tags and URLs.
	* This method also replaces any '<' with &lt;
	*/
	protected function formatHTML($text) {
		// encode all tags as safe blue strings
		$text = ereg_replace('\<([^>]+)\>', '<span style="color:blue;">&lt;\1&gt;</span>', $text);
		// identify URLs
		$protos = "http|https|ftp|gopher";
		$text = ereg_replace("($protos)://[^ '\"()<\n]+", '<a href="\\0">\\0</a>', $text);
		// identify requests to api.php
		$text = ereg_replace("api\\.php\\?[^ ()<\n\t]+", '<a href="\\0">\\0</a>', $text);
		// make strings inside * bold
		$text = ereg_replace("\\*[^<>\n]+\\*", '<b>\\0</b>', $text);
		// make strings inside $ italic
		$text = ereg_replace("\\$[^<>\n]+\\$", '<b><i>\\0</i></b>', $text);

		return $text;
	}

	/**
	 * Returns usage examples for this format.
	 */
	protected function getExamples() {
		return 'api.php?action=query&meta=siteinfo&siprop=namespaces&format=' . $this->getModuleName();
	}

	public static function getBaseVersion() {
		return __CLASS__ . ': $Id$';
	}
}

/**
 * This printer is used to wrap an instance of the Feed class 
 */
class ApiFormatFeedWrapper extends ApiFormatBase {

	public function __construct($main) {
		parent :: __construct($main, 'feed');
	}

	/**
	 * Call this method to initialize output data
	 */
	public static function setResult($result, $feed, $feedItems) {
		// Store output in the Result data.
		// This way we can check during execution if any error has occured
		$data = & $result->getData();
		$data['_feed'] = $feed;
		$data['_feeditems'] = $feedItems;
	}

	/**
	 * Feed does its own headers
	 */
	public function getMimeType() {
		return null;
	}

	/**
	 * Optimization - no need to sanitize data that will not be needed
	 */
	public function getNeedsRawData() {
		return true;
	}

	public function execute() {
		$data = $this->getResultData();
		if (isset ($data['_feed']) && isset ($data['_feeditems'])) {
			$feed = $data['_feed'];
			$items = $data['_feeditems'];

			$feed->outHeader();
			foreach ($items as & $item)
				$feed->outItem($item);
			$feed->outFooter();
		} else {
			// Error has occured, print something usefull
			// TODO: make this error more informative using ApiBase :: dieDebug() or similar
			wfHttpError(500, 'Internal Server Error', '');
		}
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>
