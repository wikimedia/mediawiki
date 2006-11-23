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
	private $mTitles, $mGoodTitles, $mMissingTitles, $mMissingPageIDs, $mRedirectTitles, $mNormalizedTitles;
	private $mResolveRedirects, $mPendingRedirectIDs;
	private $mGoodRevIDs, $mMissingRevIDs;

	private $mRequestedPageFields;

	public function __construct($query, $resolveRedirects = false) {
		parent :: __construct($query, __CLASS__);

		$this->mAllPages = array ();
		$this->mTitles = array();
		$this->mGoodTitles = array ();
		$this->mMissingTitles = array ();
		$this->mMissingPageIDs = array ();
		$this->mRedirectTitles = array ();
		$this->mNormalizedTitles = array ();
		$this->mGoodRevIDs = array();
		$this->mMissingRevIDs = array();

		$this->mRequestedPageFields = array ();
		$this->mResolveRedirects = $resolveRedirects;
		if($resolveRedirects)
			$this->mPendingRedirectIDs = array();
	}

	public function isResolvingRedirects() {
		return $this->mResolveRedirects;
	}

	public function requestField($fieldName) {
		$this->mRequestedPageFields[$fieldName] = null;
	}

	public function getCustomField($fieldName) {
		return $this->mRequestedPageFields[$fieldName];
	}

	/**
	 * Get fields that modules have requested from the page table
	 */
	public function getPageTableFields() {
		// Ensure we get minimum required fields
		$pageFlds = array (
			'page_id' => null,
			'page_namespace' => null,
			'page_title' => null
		);

		// only store non-default fields
		$this->mRequestedPageFields = array_diff_key($this->mRequestedPageFields, $pageFlds);

		if ($this->mResolveRedirects)
			$pageFlds['page_is_redirect'] = null;

		return array_keys(array_merge($pageFlds, $this->mRequestedPageFields));
	}

	/**
	 * All Title objects provided.
	 * @return array of Title objects
	 */
	public function getTitles() {
		return $this->mTitles;
	}

	/**
	 * Returns the number of unique pages (not revisions) in the set.
	 */
	public function getTitleCount() {
		return count($this->mTitles);
	}

	/**
	 * Title objects that were found in the database.
	 * @return array page_id (int) => Title (obj)
	 */
	public function getGoodTitles() {
		return $this->mGoodTitles;
	}

	/**
	 * Returns the number of found unique pages (not revisions) in the set.
	 */
	public function getGoodTitleCount() {
		return count($this->mGoodTitles);
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
	 * Get the list of revision IDs (requested with revids= parameter)
	 * @return array revID (int) => pageID (int)
	 */
	public function getRevisionIDs() {
		return $this->mGoodRevIDs;
	}

	/**
	 * Revision IDs that were not found in the database
	 * @return array of revision IDs
	 */
	public function getMissingRevisionIDs() {
		return $this->mMissingRevIDs;
	}

	/**
	 * Returns the number of revisions (requested with revids= parameter)
	 */
	public function getRevisionCount() {
		return count($this->getRevisionIDs());
	}

	/**
	 * Populate from the request parameters
	 */
	public function execute() {
		$this->profileIn();
		$titles = $pageids = $revids = null;
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
				$this->initFromTitles($titles);
				break;
			case 'pageids' :
				$this->initFromPageIds($pageids);
				break;
			case 'revids' :
				if($this->mResolveRedirects)
					$this->dieUsage('revids may not be used with redirect resolution', 'params');
				$this->initFromRevIDs($revids);
				break;
			default :
				// Do nothing - some queries do not need any of the data sources.
				break;
		}
		$this->profileOut();
	}

	/**
	 * Initialize PageSet from a list of Titles
	 */
	public function populateFromTitles($titles) {
		$this->profileIn();
		$this->initFromTitles($titles);
		$this->profileOut();
	}

	/**
	 * Initialize PageSet from a list of Page IDs
	 */
	public function populateFromPageIDs($pageIDs) {
		$this->profileIn();
		$pageIDs = array_map('intval', $pageIDs); // paranoia
		$this->initFromPageIds($pageIDs);
		$this->profileOut();
	}

	/**
	 * Initialize PageSet from a rowset returned from the database
	 */
	public function populateFromQueryResult($db, $queryResult) {
		$this->profileIn();
		$this->initFromQueryResult($db, $queryResult);
		$this->profileOut();
	}

	/**
	 * Initialize PageSet from a list of Revision IDs
	 */
	public function populateFromRevisionIDs($revIDs) {
		$this->profileIn();
		$revIDs = array_map('intval', $revIDs); // paranoia
		$this->initFromRevIDs($revIDs);
		$this->profileOut();
	}

	/**
	 * Extract all requested fields from the row received from the database
	 */
	public function processDbRow($row) {
	
		// Store Title object in various data structures
		$title = Title :: makeTitle($row->page_namespace, $row->page_title);
	
		// skip any pages that user has no rights to read
		if ($title->userCanRead()) {

			$pageId = intval($row->page_id);	
			$this->mAllPages[$row->page_namespace][$row->page_title] = $pageId;
			$this->mTitles[] = $title;
	
			if ($this->mResolveRedirects && $row->page_is_redirect == '1') {
				$this->mPendingRedirectIDs[$pageId] = $title;
			} else {
				$this->mGoodTitles[$pageId] = $title;
			}
	
			foreach ($this->mRequestedPageFields as $fieldName => & $fieldValues)
				$fieldValues[$pageId] = $row-> $fieldName;
		}
	}
	
	public function finishPageSetGeneration() {
		$this->profileIn();
		$this->resolvePendingRedirects();
		$this->profileOut();
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
	private function initFromTitles($titles) {

		// Get validated and normalized title objects
		$linkBatch = $this->processTitlesStrArray($titles);
		if($linkBatch->isEmpty())
			return;
			
		$db = & $this->getDB();
		$set = $linkBatch->constructSet('page', $db);

		// Get pageIDs data from the `page` table
		$this->profileDBIn();
		$res = $db->select('page', $this->getPageTableFields(), $set, __METHOD__);
		$this->profileDBOut();

		// Hack: get the ns:titles stored in array(ns => array(titles)) format
		$this->initFromQueryResult($db, $res, $linkBatch->data, true);	// process Titles

		// Resolve any found redirects
		$this->resolvePendingRedirects();
	}

	private function initFromPageIds($pageids) {
		if(empty($pageids))
			return;
			
		$set = array (
			'page_id' => $pageids
		);

		$db = & $this->getDB();

		// Get pageIDs data from the `page` table
		$this->profileDBIn();
		$res = $db->select('page', $this->getPageTableFields(), $set, __METHOD__);
		$this->profileDBOut();
		
		$this->initFromQueryResult($db, $res, array_flip($pageids), false);	// process PageIDs

		// Resolve any found redirects
		$this->resolvePendingRedirects();
	}
	
	/**
	 * Iterate through the result of the query on 'page' table,
	 * and for each row create and store title object and save any extra fields requested.
	 * @param $db Database
	 * @param $res DB Query result
	 * @param $remaining Array of either pageID or ns/title elements (optional).
	 *        If given, any missing items will go to $mMissingPageIDs and $mMissingTitles
	 * @param $processTitles bool Must be provided together with $remaining.
	 *        If true, treat $remaining as an array of [ns][title]
	 *        If false, treat it as an array of [pageIDs]
	 * @return Array of redirect IDs (only when resolving redirects)
	 */
	private function initFromQueryResult($db, $res, &$remaining = null, $processTitles = null) {
		if (!is_null($remaining) && is_null($processTitles))
			ApiBase :: dieDebug(__METHOD__, 'Missing $processTitles parameter when $remaining is provided');
			
		while ($row = $db->fetchObject($res)) {

			$pageId = intval($row->page_id);

			// Remove found page from the list of remaining items
			if (isset($remaining)) {
				if ($processTitles)
					unset ($remaining[$row->page_namespace][$row->page_title]);
				else
					unset ($remaining[$pageId]);
			}
			
			// Store any extra fields requested by modules
			$this->processDbRow($row);
		}
		$db->freeResult($res);
		
		if(isset($remaining)) {
			// Any items left in the $remaining list are added as missing
			if($processTitles) {
				// The remaining titles in $remaining are non-existant pages
				foreach ($remaining as $ns => $dbkeys) {
					foreach ( array_keys($dbkeys) as $dbkey ) {
						$title = Title :: makeTitle($ns, $dbkey);
						$this->mMissingTitles[] = $title;
						$this->mAllPages[$ns][$dbkey] = 0;
						$this->mTitles[] = $title;
					}
				}
			}
			else
			{
				// The remaining pageids do not exist
				if(empty($this->mMissingPageIDs))
					$this->mMissingPageIDs = array_keys($remaining);
				else
					$this->mMissingPageIDs = array_merge($this->mMissingPageIDs, array_keys($remaining));
			}
		}
	}

	private function initFromRevIDs($revids) {

		if(empty($revids))
			return;
			
		$db = & $this->getDB();
		$pageids = array();
		$remaining = array_flip($revids);
		
		$tables = array('revision');
		$fields = array('rev_id','rev_page');
		$where = array('rev_deleted' => 0, 'rev_id' => $revids);
		
		// Get pageIDs data from the `page` table
		$this->profileDBIn();
		$res = $db->select( $tables, $fields, $where,  __METHOD__ );
		while ( $row = $db->fetchObject( $res ) ) {
			$revid = intval($row->rev_id);
			$pageid = intval($row->rev_page);
			$this->mGoodRevIDs[$revid] = $pageid;
			$pageids[$pageid] = '';
			unset($remaining[$revid]);
		}
		$db->freeResult( $res );
		$this->profileDBOut();

		$this->mMissingRevIDs = array_keys($remaining);

		// Populate all the page information
		if($this->mResolveRedirects)
			ApiBase :: dieDebug(__METHOD__, 'revids may not be used with redirect resolution');
		$this->initFromPageIds(array_keys($pageids));
	}

	private function resolvePendingRedirects() {

		if($this->mResolveRedirects) {
			$db = & $this->getDB();
			$pageFlds = $this->getPageTableFields();
	
			// Repeat until all redirects have been resolved
			// The infinite loop is prevented by keeping all known pages in $this->mAllPages
			while (!empty ($this->mPendingRedirectIDs)) {			
	
				// Resolve redirects by querying the pagelinks table, and repeat the process
				// Create a new linkBatch object for the next pass
				$linkBatch = $this->getRedirectTargets();
	
				if ($linkBatch->isEmpty())
					break;
					
				$set = $linkBatch->constructSet('page', $db);
				if(false === $set)
					break;
		
				// Get pageIDs data from the `page` table
				$this->profileDBIn();
				$res = $db->select('page', $pageFlds, $set, __METHOD__);
				$this->profileDBOut();
			
				// Hack: get the ns:titles stored in array(ns => array(titles)) format
				$this->initFromQueryResult($db, $res, $linkBatch->data, true);
			}
		}
	}

	private function getRedirectTargets() {

		$linkBatch = new LinkBatch();
		$db = & $this->getDB();

		// find redirect targets for all redirect pages
		$this->profileDBIn();
		$res = $db->select('pagelinks', array (
			'pl_from',
			'pl_namespace',
			'pl_title'
		), array (
			'pl_from' => array_keys($this->mPendingRedirectIDs
		)), __METHOD__);
		$this->profileDBOut();

		while ($row = $db->fetchObject($res)) {

			$plfrom = intval($row->pl_from);

			// Bug 7304 workaround 
			// ( http://bugzilla.wikipedia.org/show_bug.cgi?id=7304 )
			// A redirect page may have more than one link.
			// This code will only use the first link returned. 
			if (isset ($this->mPendingRedirectIDs[$plfrom])) { // remove line when bug 7304 is fixed 

				$titleStrFrom = $this->mPendingRedirectIDs[$plfrom]->getPrefixedText();
				$titleStrTo = Title :: makeTitle($row->pl_namespace, $row->pl_title)->getPrefixedText();
				unset ($this->mPendingRedirectIDs[$plfrom]); // remove line when bug 7304 is fixed

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

		// All IDs must exist in the page table
		if (!empty($this->mPendingRedirectIDs[$plfrom]))
			ApiBase :: dieDebug(__METHOD__, 'Invalid redirect IDs were found');

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
			)
		);
	}

	protected function getParamDescription() {
		return array (
			'titles' => 'A list of titles to work on',
			'pageids' => 'A list of page IDs to work on',
			'revids' => 'A list of revision IDs to work on'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>