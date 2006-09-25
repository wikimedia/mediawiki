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
	require_once ("ApiBase.php");
}

class ApiPageSet {

	private $allPages; // [ns][dbkey] => page_id or 0 when missing
	private $db, $resolveRedirs;
	private $goodTitles, $missingTitles, $redirectTitles;

	public function __construct($db, $resolveRedirs) {
		$this->db = $db;
		$this->resolveRedirs = $resolveRedirs;

		$this->allPages = array ();
		$this->goodTitles = array ();
		$this->missingTitles = array ();

		// only when resolving redirects:
		if ($resolveRedirs) {
			$this->redirectTitles = array ();
		}
	}

	/**
	 * Title objects that were found in the database.
	 * @return array page_id (int) => Title (obj)
	 */
	public function GetGoodTitles() {
		return $this->goodTitles;
	}

	/**
	 * Title objects that were NOT found in the database.
	 * @return array of Title objects
	 */
	public function GetMissingTitles() {
		return $this->missingTitles;
	}

	/**
	 * Get a list of redirects when doing redirect resolution
	 * @return array prefixed_title (string) => prefixed_title (string)
	 */
	public function GetRedirectTitles() {
		return $this->redirectTitles;
	}

	/**
	 * This method populates internal variables with page information
	 * based on the list of page titles given as a LinkBatch object.
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
	public function PopulateTitles($linkBatch) {
		$pageFlds = array (
			'page_id',
			'page_namespace',
			'page_title'
		);
		if ($this->resolveRedirs) {
			$pageFlds[] = 'page_is_redirect';
		}

		//
		// Repeat until all redirects have been resolved
		//
		while (false !== ($set = $linkBatch->constructSet('page', $this->db))) {

			// Hack: Get the ns:titles stored in array(ns => array(titles)) format
			$remaining = $linkBatch->data;

			if ($this->resolveRedirs)
				$redirectIds = array ();

			//
			// Get data about $linkBatch from `page` table
			//
			$res = $this->db->select('page', $pageFlds, $set, __CLASS__ . '::' . __FUNCTION__);
			while ($row = $this->db->fetchObject($res)) {

				unset ($remaining[$row->page_namespace][$row->page_title]);
				$title = Title :: makeTitle($row->page_namespace, $row->page_title);
				$this->allPages[$row->page_namespace][$row->page_title] = $row->page_id;

				if ($this->resolveRedirs && $row->page_is_redirect == '1') {
					$redirectIds[$row->page_id] = $title;
				} else {
					$this->goodTitles[$row->page_id] = $title;
				}
			}
			$this->db->freeResult($res);

			//
			// The remaining titles in $remaining are non-existant pages
			//
			foreach ($remaining as $ns => $dbkeys) {
				foreach ($dbkeys as $dbkey => $nothing) {
					$this->missingTitles[] = Title :: makeTitle($ns, $dbkey);
					$this->allPages[$ns][$dbkey] = 0;
				}
			}

			if (!$this->resolveRedirs || empty ($redirectIds))
				break;

			//
			// Resolve redirects by querying the pagelinks table, and repeat the process
			//
			
			// Create a new linkBatch object for the next pass
			$linkBatch = new LinkBatch();

			// find redirect targets for all redirect pages
			$res = $this->db->select('pagelinks', array (
				'pl_from',
				'pl_namespace',
				'pl_title'
			), array (
				'pl_from' => array_keys($redirectIds
			)), __CLASS__ . '::' . __FUNCTION__);

			while ($row = $this->db->fetchObject($res)) {

				// Bug 7304 workaround 
				// ( http://bugzilla.wikipedia.org/show_bug.cgi?id=7304 )
				// A redirect page may have more than one link.
				// This code will only use the first link returned. 
				if (isset ($redirectIds[$row->pl_from])) {	// remove line when 7304 is fixed 

					$titleStrFrom = $redirectIds[$row->pl_from]->getPrefixedText();
					$titleStrTo = Title :: makeTitle($row->pl_namespace, $row->pl_title)->getPrefixedText();
					$this->redirectTitles[$titleStrFrom] = $titleStrTo;

					unset ($redirectIds[$row->pl_from]);	// remove line when 7304 is fixed

					// Avoid an infinite loop by checking if we have already processed this target
					if (!isset ($this->allPages[$row->pl_namespace][$row->pl_title])) {
						$linkBatch->add($row->pl_namespace, $row->pl_title);
					}
				}
			}
			$this->db->freeResult($res);
		}
	}
}
?>