<?php


/*
 * Created on Oct 13, 2006
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

class ApiFeedWatchlist extends ApiBase {

	public function __construct($main, $action) {
		parent :: __construct($main, $action);
	}

	public function getCustomPrinter() {
		return new ApiFormatFeedWrapper($this->getMain());
	}

	public function execute() {
		$feedformat = null;
		extract($this->extractRequestParams());

		// Prepare nested request
		$params = new FauxRequest(array (
			'action' => 'query',
			'meta' => 'siteinfo',
			'siprop' => 'general',
			'list' => 'watchlist',
			'wlstart' => wfTimestamp(TS_MW, time() - intval( 3 * 86400 )), // limit to 3 days
			'wllimit' => 50
		));

		// Execute
		$module = new ApiMain($params);
		$module->execute();

		// Get clean data
		$result = & $module->getResult();
		$result->SanitizeData();
		$data = & $result->GetData();

		$feedItems = array ();
		foreach ($data['query']['watchlist'] as $index => & $info) {
			$title = $info['title'];
			$titleUrl = Title :: newFromText($title)->getFullUrl();
			$feedItems[] = new FeedItem($title, '', $titleUrl, $info['timestamp'], $info['user']);
		}

		global $wgFeedClasses, $wgSitename, $wgContLanguageCode;
		$feedTitle = $wgSitename . ' - ' . wfMsgForContent('watchlist') . ' [' . $wgContLanguageCode . ']';
		$feedUrl = Title :: makeTitle(NS_SPECIAL, 'Watchlist')->getFullUrl();
		$feed = new $wgFeedClasses[$feedformat] ($feedTitle, '!Watchlist!', $feedUrl);

		ApiFormatFeedWrapper :: setResult($this->getResult(), $feed, $feedItems);
	}

	protected function GetAllowedParams() {
		global $wgFeedClasses;
		$feedFormatNames = array_keys($wgFeedClasses);
		return array (
			'feedformat' => array (
				ApiBase :: PARAM_DFLT => 'rss',
				ApiBase :: PARAM_TYPE => $feedFormatNames
			)
		);
	}

	protected function GetParamDescription() {
		return array (
			'feedformat' => 'The format of the feed'
		);
	}

	protected function GetDescription() {
		return 'This module returns a watchlist feed';
	}

	protected function GetExamples() {
		return array (
			'api.php?action=feedwatchlist'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>