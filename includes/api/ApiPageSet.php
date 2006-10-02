<?php


/*
 * Created on Sep 24, 2006
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

class ApiPageSet extends ApiQueryBase {

	private $mAllPages; // [ns][dbkey] => page_id or 0 when missing
	private $mGoodTitles, $mMissingTitles, $mMissingPageIDs, $mRedirectTitles, $mNormalizedTitles;

	private $mRequestedFields;

	public function __construct($query) {
		parent :: __construct($query, __CLASS__);

		$this->mAllPages = array ();
		$this->mGoodTitles = array ();
		$this->mMissingTitles = array ();
		$this->mMissingPageIDs = array ();
		$this->mRedirectTitles = array ();
		$this->mNormalizedTitles = array ();

		$this->mRequestedFields = array ();
	}

	public function requestField($fieldName) {
		$this->mRequestedFields[$fieldName] = null;
	}

	public function getCustomField($fieldName) {
		return $this->mRequestedFields[$fieldName];
	}

	/**
	 * Title objects that were found in the database.
	 * @return array page_id (int) => Title (obj)
	 */
	public function getGoodTitles() {
		return $this->mGoodTitles;
	}

	/**
	 * Title objects that were NOT found in the database.
	 * @return array of Title objects
	 */
	public function getMissingTitles() {
		return $this->mMissingTitles;
	}

	/**
	 * Page IDs that were not found in the database
	 * @return array of page IDs
	 */
	public function getMissingPageIDs() {
		return $this->mMissingPageIDs;
	}

	/**
	 * Get a list of redirects when doing redirect resolution
	 * @return array prefixed_title (string) => prefixed_title (string)
	 */
	public function getRedirectTitles() {
		return $this->mRedirectTitles;
	}

	/**
	 * Get a list of title normalizations - maps the title given 
	 * with its normalized version.
	 * @return array raw_prefixed_title (string) => prefixed_title (string) 
	 */
	public function getNormalizedTitles() {
		return $this->mNormalizedTitles;
	}

	/**
	 * Returns the number of unique pages (not revisions) in the set.
	 */
	public function getGoodTitleCount() {
		return count($this->getGoodTitles());
	}

	/**
	 * Get the list of revision IDs (requested with revids= parameter)
	 */
	public function getRevisionIDs() {
		$this->dieUsage(__METHOD__ . ' is not implemented', 'notimplemented');
	}

	/**
	 * Returns the number of revisions (requested with revids= parameter)
	 */
	public function getRevisionCount() {
		return 0; // TODO: implement
	}

	/**
	 * This method populates internal variables with page information
	 * based on the given array of title strings.
	 * 
	 * Steps:
	 * #1 For each title, get data from `page` table
	 * #2 If page was not found in the DB, store it as missing
	 * 
	 * Additionally, when resolving redirects:
	 * #3 If no more redirects left, stop.
	 * #4 For each redirect, get its links from `pagelinks` table.
	 * #5 Substitute the original LinkBatch object with the new list
	 * #6 Repeat from step #1     
	 */
	private function populatePages($titles, $pageids, $redirects) {
		if (!is_null($titles) && !is_null($pageids))
			ApiBase :: dieDebug(__METHOD__, 'bad parameters');
		$processTitles = !is_null($titles);

		// Ensure we get minimum required fields
		$pageFlds = array (
			'page_id' => null,
			'page_namespace' => null,
			'page_title' => null
		);

		// only store non-default fields
		$this->mRequestedFields = array_diff_key($this->mRequestedFields, $pageFlds);

		if ($redirects)
			$pageFlds['page_is_redirect'] = null;

		$pageFlds = array_keys(array_merge($pageFlds, $this->mRequestedFields));

		$db = $this->getDB();

		if ($processTitles) {

			// Get validated and normalized title objects
			$linkBatch = $this->processTitlesStrArray($titles);

			$set = $linkBatch->constructSet('page', $db);
		} else {
			$set = array (
				'page_id' => $pageids
			);
		}

		//
		// Repeat until all redirects have been resolved
		// The infinite loop is prevented by keeping all known pages in $this->mAllPages
		//
		do {
			if ($processTitles) {
				// Hack: get the ns:titles stored in array(ns => array(titles)) format
				$remaining = $linkBatch->data;
			} else {
				$remaining = array_flip($pageids); // turn pageids into keys 
			}

			$redirectIds = array ();

			//
			// Get data about $linkBatch from `page` table
			//
			$this->profileDBIn();
			$res = $db->select('page', $pageFlds, $set, __METHOD__);
			$this->profileDBOut();
			while ($row = $db->fetchObject($res)) {

				$pageId = intval($row->page_id);

				if ($processTitles)
					unset ($remaining[$row->page_namespace][$row->page_title]);
				else
					unset ($remaining[$pageId]);

				$title = Title :: makeTitle($row->page_namespace, $row->page_title);
				$this->mAllPages[$row->page_namespace][$row->page_title] = $pageId;

				if ($redirects && $row->page_is_redirect == '1') {
					$redirectIds[$pageId] = $title;
				} else {
					$this->mGoodTitles[$pageId] = $title;
				}

				foreach ($this->mRequestedFields as $fieldName => & $fieldValues) {
					$fieldValues[$pageId] = $row-> $fieldName;
				}
			}
			$db->freeResult($res);

			if ($processTitles) {
				// The remaining titles in $remaining are non-existant pages
				foreach ($remaining as $ns => $dbkeys) {
					foreach ($dbkeys as $dbkey => $nothing) {
						$this->mMissingTitles[] = Title :: makeTitle($ns, $dbkey);
						$this->mAllPages[$ns][$dbkey] = 0;
					}
				}
			} else {
				// The remaining pageids in $remaining do not exist
				foreach ($remaining as $pageid => $ignore) {
					$this->mMissingPageIDs[] = $pageid;
				}
			}

			if (!$redirects || empty ($redirectIds))
				break;

			//
			// Resolve redirects by querying the pagelinks table, and repeat the process
			// Create a new linkBatch object for the next pass
			//
			$linkBatch = $this->resolveRedirectList($redirectIds);

			// Redirects are always titles
			$processTitles = true;
		}
		while (false !== ($set = $linkBatch->constructSet('page', $db)));
	}

	private function resolveRedirectList($redirectIds) {

		$linkBatch = new LinkBatch();
		$db = $this->getDB();

		// find redirect targets for all redirect pages
		$this->profileDBIn();
		$res = $db->select('pagelinks', array (
			'pl_from',
			'pl_namespace',
			'pl_title'
		), array (
			'pl_from' => array_keys($redirectIds
		)), __METHOD__);
		$this->profileDBOut();

		while ($row = $db->fetchObject($res)) {

			$plfrom = intval($row->pl_from);

			// Bug 7304 workaround 
			// ( http://bugzilla.wikipedia.org/show_bug.cgi?id=7304 )
			// A redirect page may have more than one link.
			// This code will only use the first link returned. 
			if (isset ($redirectIds[$plfrom])) { // remove line when bug 7304 is fixed 

				$titleStrFrom = $redirectIds[$plfrom]->getPrefixedText();
				$titleStrTo = Title :: makeTitle($row->pl_namespace, $row->pl_title)->getPrefixedText();
				unset ($redirectIds[$plfrom]); // remove line when bug 7304 is fixed

				// Avoid an infinite loop by checking if we have already processed this target
				if (!isset ($this->mAllPages[$row->pl_namespace][$row->pl_title])) {
					$linkBatch->add($row->pl_namespace, $row->pl_title);
				}
			} else {
				// This redirect page has more than one link.
				// This is very slow, but safer until bug 7304 is resolved
				$title = Title :: newFromID($plfrom);
				$titleStrFrom = $title->getPrefixedText();

				$article = new Article($title);
				$text = $article->getContent();
				$titleTo = Title :: newFromRedirect($text);
				$titleStrTo = $titleTo->getPrefixedText();

				if (is_null($titleStrTo))
					ApiBase :: dieDebug(__METHOD__, 'Bug7304 workaround: redir target from {$title->getPrefixedText()} not found');

				// Avoid an infinite loop by checking if we have already processed this target
				if (!isset ($this->mAllPages[$titleTo->getNamespace()][$titleTo->getDBkey()])) {
					$linkBatch->addObj($titleTo);
				}
			}

			$this->mRedirectTitles[$titleStrFrom] = $titleStrTo;
		}
		$db->freeResult($res);

		return $linkBatch;
	}

	/**
	 * Given an array of title strings, convert them into Title objects.
	 * This method validates access rights for the title, 
	 * and appends normalization values to the output.
	 * 
	 * @return LinkBatch of title objects.
	 */
	private function processTitlesStrArray($titles) {

		$linkBatch = new LinkBatch();

		foreach ($titles as $titleString) {
			$titleObj = Title :: newFromText($titleString);

			// Validation
			if (!$titleObj)
				$this->dieUsage("bad title $titleString", 'invalidtitle');
			if ($titleObj->getNamespace() < 0)
				$this->dieUsage("No support for special page $titleString has been implemented", 'unsupportednamespace');
			if (!$titleObj->userCanRead())
				$this->dieUsage("No read permission for $titleString", 'titleaccessdenied');

			$linkBatch->addObj($titleObj);

			// Make sure we remember the original title that was given to us
			// This way the caller can correlate new titles with the originally requested,
			// i.e. namespace is localized or capitalization is different
			if ($titleString !== $titleObj->getPrefixedText()) {
				$this->mNormalizedTitles[$titleString] = $titleObj->getPrefixedText();
			}
		}

		return $linkBatch;
	}

	private function populateRevIDs($revids) {
		$this->dieUsage(__METHOD__ . ' is not implemented', 'notimplemented');
	}

	public function execute() {
		$this->profileIn();
		$titles = $pageids = $revids = $redirects = null;
		extract($this->extractRequestParams());

		// Only one of the titles/pageids/revids is allowed at the same time
		$dataSource = null;
		if (isset ($titles))
			$dataSource = 'titles';
		if (isset ($pageids)) {
			if (isset ($dataSource))
				$this->dieUsage("Cannot use 'pageids' at the same time as '$dataSource'", 'multisource');
			$dataSource = 'pageids';
		}
		if (isset ($revids)) {
			if (isset ($dataSource))
				$this->dieUsage("Cannot use 'revids' at the same time as '$dataSource'", 'multisource');
			$dataSource = 'revids';
		}

		switch ($dataSource) {
			case 'titles' :
			case 'pageids' :
				$this->populatePages($titles, $pageids, $redirects);
				break;
			case 'revids' :
				$this->populateRevIDs($revids);
				break;
			default :
				// Do nothing - some queries do not need any of the data sources.
				break;
		}
		$this->profileOut();
	}

	/**
	 * This method is used by generators to pass the list of pageIDs internaly
	 */
	public function executeForPageIDs($pageIDs) {
		$this->profileIn();
		$pageIDs = array_map( 'intval', $pageIDs );	// paranoia
		$this->populatePages(null, $pageIDs, $this->getParameter('redirects'));
		$this->profileOut();
	}
	
	protected function getAllowedParams() {
		return array (
			'titles' => array (
				ApiBase :: PARAM_ISMULTI => true
			),
			'pageids' => array (
				ApiBase :: PARAM_TYPE => 'integer',
				ApiBase :: PARAM_ISMULTI => true
			),
			'revids' => array (
				ApiBase :: PARAM_TYPE => 'integer',
				ApiBase :: PARAM_ISMULTI => true
			),
			'redirects' => false
		);
	}

	protected function getParamDescription() {
		return array (
			'titles' => 'A list of titles to work on',
			'pageids' => 'A list of page IDs to work on',
			'revids' => 'A list of revision IDs to work on',
			'redirects' => 'Automatically resolve redirects'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>