<?php


/*
 * Created on Sep 25, 2006
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
	require_once ('ApiQueryBase.php');
}

class ApiQueryInfo extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName);
	}

	public function requestExtraData() {
		$pageSet = $this->getPageSet();
		$pageSet->requestField('page_is_redirect');
		$pageSet->requestField('page_touched');
		$pageSet->requestField('page_latest');
	}

	public function execute() {

		$pageSet = $this->getPageSet();
		$titles = $pageSet->getGoodTitles();
		$result = & $this->getResult();

		$pageIsRedir = $pageSet->getCustomField('page_is_redirect');
		$pageTouched = $pageSet->getCustomField('page_touched');
		$pageLatest = $pageSet->getCustomField('page_latest');

		foreach ($titles as $pageid => $title) {
			$pageInfo = array (
				'touched' => $pageTouched[$pageid],
				'lastrevid' => $pageLatest[$pageid]
			);

			if ($pageIsRedir[$pageid])
				$pageInfo['redirect'] = '';

			$result->addValue(array (
				'query',
				'pages'
			), $pageid, $pageInfo);
		}
	}

	protected function getDescription() {
		return 'Get basic page information such as namespace, title, last touched date, ...';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&prop=info&titles=Main%20Page'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>